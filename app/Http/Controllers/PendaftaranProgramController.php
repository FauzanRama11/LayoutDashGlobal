<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\MStuInProgram;
use App\Models\MStuInPeserta;
use App\Models\MStuOutProgram;
use App\Models\MStuOutPeserta;
use App\Models\User;

class PendaftaranProgramController extends Controller
{
    public function stuin(Request $request, $url_generate)
    {
        $program =  MStuInProgram::where('url_generate', $url_generate)->first();

        // Jika program tidak ditemukan, langsung redirect ke halaman gagal
        if (!$program) {
            return redirect()->route('failed')->withErrors(['error' => 'Program tidak ditemukan.']);
        }

        // Setelah mendapatkan program, cek apakah masa registrasi sudah berakhir
        $isActivePeriod = DB::table('m_stu_in_programs')
            ->where('url_generate', $url_generate)
            ->whereDate('reg_date_start', '<=', now())
            ->whereDate('reg_date_closed', '>=', now())
            ->exists();

            
        // Jika periode tidak aktif, arahkan ke halaman result_stuin
        if (!$isActivePeriod) {
            $title = $program->name; 
            $message = 'Currently, there is no active registration period for this program type. 
                        Please check back later or contact support for assistance.';

            $cleanPathRoot = ltrim(str_replace('repo/inbound/', '', $program->logo), '/');
            $cleanPathInbound = ltrim(str_replace('repo/', '', $program->logo), '/');

            if (Storage::disk('inside')->exists($cleanPathInbound)) {
                $filePath = $cleanPathInbound;
            } elseif (Storage::disk('inside')->exists($cleanPathRoot)) {
                $filePath = $cleanPathRoot;
            } else {
                $filePath = null;
            }

            // Konversi gambar ke base64 jika file ditemukan
            if ($filePath) {
                $fileContent = Storage::disk('inside')->get($filePath);
                $program->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
            } else {
                $program->logo_base64 = null;
            }

            return view('pendaftaran.result_stuin', compact('title', 'message', 'program'));
        }

        // Proses mencari logo program
        $cleanPathRoot = ltrim(str_replace('repo/inbound/', '', $program->logo), '/');
        $cleanPathInbound = ltrim(str_replace('repo/', '', $program->logo), '/');

        if (Storage::disk('inside')->exists($cleanPathInbound)) {
            $filePath = $cleanPathInbound;
        } elseif (Storage::disk('inside')->exists($cleanPathRoot)) {
            $filePath = $cleanPathRoot;
        } else {
            $filePath = null;
        }

        // Konversi gambar ke base64 jika file ditemukan
        if ($filePath) {
            $fileContent = Storage::disk('inside')->get($filePath);
            $program->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
        } else {
            $program->logo_base64 = null;
        }

        // Ambil data tambahan
        $univ = DB::table('m_university')->get();
        $country = DB::table('m_country')->where('id', '!=', 95)->get(); // Fix `whereNot`
        $course = DB::table('age_amerta_matkul')->get();

        // Load halaman registrasi dengan semua data
        return view('pendaftaran.registrasi_stu_in', [
            'id_period' => $program->id ?? null, // Gunakan $program->id karena $period tidak ada
            'univ' => $univ,
            'country' => $country,
            'course' => $course,
            'program' => $program
        ]);
    }

    public function Simpan_stuin(Request $request)
    {
        try {
            DB::beginTransaction(); 
            
            Log::info('Memulai Simpan_stuin', ['request' => $request->all()]);

            $rules = [
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan,Other',
                'tgl_lahir' => 'required|date',
                'telp' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'jenjang' => 'required|string|max:50',
                'prodi_asal' => 'required|string|max:255',
                'fakultas_asal' => 'required|string|max:255',
                'univ' => 'required|string|max:255',
                'negara_asal_univ' => 'required',
                'kebangsaan' => 'required',
                'photo_url' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'cv_url' => 'required|file|mimes:pdf|max:2048',
                'program_info' => 'nullable|string|max:255',
                'kode' => 'required',
            ];

            if ($request->selected_id === 'student_id') {
                $rules['selected_id'] = 'required|in:student_id';
                $rules['student_no'] = 'required';
                $rules['student_id_url'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
            } elseif ($request->selected_id === 'passport') {
                $rules['selected_id'] = 'required|in:passport';
                $rules['passport_no'] = 'required';
                $rules['passport_url'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
            }

            $validated = $request->validate($rules);

            // **Simpan File (Jika Ada)**
            $fileFields = [
                'photo_url' => 'photo_url',
                'passport_url' => 'passport_url',
                'student_id_url' => 'student_id_url',
                'cv_url' => 'cv_url',
            ];

            foreach ($fileFields as $field => $attribute) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filePath = $this->storeFile($file, 'inbound');
                    $validated[$attribute] = $filePath;
                }
            }

            // **Cari Program Berdasarkan Kode**
            $program = MStuInProgram::where('url_generate', $validated['kode'])->first();
            if (!$program) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program tidak ditemukan.'
                ], 404);
            }

            // **Gunakan Data Peserta Lama Jika ID Tidak Dipilih**
            if (empty($validated['selected_id'])) {
                $existpeserta = MStuInPeserta::where('email', $request->input('email'))
                    ->orderBy('reg_time', 'desc')
                    ->first();

                if ($existpeserta) {
                    $validated['selected_id'] = $existpeserta->selected_id;
                    $validated['passport_no'] = $existpeserta->passport_no;
                    $validated['student_no'] = $existpeserta->student_no;
                    $validated['passport_url'] = $existpeserta->passport_url;
                    $validated['student_id_url'] = $existpeserta->student_id_url;
                }
            }

            // **Tambahkan Data Ke Peserta**
            $validated['tujuan_fakultas_unit'] = $program->host_unit;
            $validated['program_id'] = $program->id;
            $validated['reg_time'] = now();
            $validated['is_loa'] = 0;

            MStuInPeserta::create($validated);

            // **Tambahkan Data Ke Users**
            if(!User::where('email', $request->input('email'))->exists()){
                User::create([
                    'username' => $validated['passport_no'] ?? $validated['student_no'],
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['email']),
                    'is_active' => 'False',
                ]);
            }

            // **Simpan Kode ke Session**
            session(['kode' => $validated['kode']]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'redirect' => route('result.stuin')
            ]);

        } catch (ValidationException $e) {
            DB::rollBack(); 
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal!',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack(); 

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    private function storeFile($file, $subfolder = null)
    {
        $folder = $subfolder ? "/{$subfolder}" : '';

        if (!Storage::disk('inside')->exists($folder)) {
            Storage::disk('inside')->makeDirectory($folder);
        }

        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $file->storeAs($folder, $fileName, 'inside');

        return $subfolder ? "repo/{$subfolder}/{$fileName}" : "repo/{$fileName}";
    }

    public function checkemail(Request $request)
    {
        Log::info('Memeriksa email', ['email' => $request->input('email'), 'kode' => $request->input('kode')]);

        $email = $request->input('email');
        $kode = $request->input('kode');

        if (!$kode) {
            Log::error('Kode program tidak dikirim');
            return response()->json(['error' => 'Kode program is missing.'], 400);
        }

        $program = MStuInProgram::where('url_generate', $kode)->first();

        if (!$program) {
            Log::error('Program tidak ditemukan', ['kode' => $kode]);
            return response()->json(['error' => 'Program not found.'], 404);
        }

        // **Cek apakah email sudah ada dalam peserta program ini**
        $existsInPeserta = MStuInPeserta::where('program_id', $program->id)
                                        ->where('email', $email)
                                        ->exists();

        Log::info('Hasil cek di peserta', ['email' => $email, 'exists_in_peserta' => $existsInPeserta]);

        if ($existsInPeserta) {
            return response()->json(['exists_in_peserta' => true]);
        }

        // **Cek apakah email ada di tabel users**
        $existsInUsers = User::where('email', $email)->exists();

        Log::info('Hasil cek di users', ['email' => $email, 'exists_in_users' => $existsInUsers]);

        return response()->json([
            'exists_in_peserta' => false,
            'exists_in_users' => $existsInUsers
        ]);
    }



    public function result(Request $request)
    {
        $kode = session('kode', null);

        if (!$kode) {
            return redirect('/')->with('error', 'Program ID is missing.');
        }

        try {
            $program =  MStuInProgram::where('url_generate', $kode)->first();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/')->with('error', 'Program not found.');
        }

        // Membersihkan path file untuk pencarian di storage
        $cleanPathRoot = ltrim(str_replace('repo/inbound/', '', $program->logo), '/');
        $cleanPathInbound = ltrim(str_replace('repo/', '', $program->logo), '/');

        // Cek apakah file ada di dalam storage
        if (Storage::disk('inside')->exists($cleanPathInbound)) {
            $filePath = $cleanPathInbound;
        } elseif (Storage::disk('inside')->exists($cleanPathRoot)) {
            $filePath = $cleanPathRoot;
        } else {
            $filePath = null;
        }

        $program->logo_base64 = $filePath ? 'data:image/jpeg;base64,' . base64_encode(Storage::disk('inside')->get($filePath)) : null;

        $message = session('success') ?? 'Your registration process has been recorded.';

        return view('pendaftaran.result_stuin', [
            'message' => $message,
            'program' => $program
        ]);
    }


    public function stuout(Request $request, $url_generate)
    {
        
        $program =  MStuOutProgram::where('url_generate', $url_generate)->first();
        
        $cleanPathRoot = ltrim(str_replace('repo/outbound/', '', $program->logo), '/');
        $cleanPathOutbound = ltrim(str_replace('repo/', '', $program->logo), '/');

        // Pencarian file dalam repo
        if (Storage::disk('inside')->exists($cleanPathOutbound)) {
            $filePath = $cleanPathOutbound;
        } elseif (Storage::disk('inside')->exists($cleanPathRoot)) {
            $filePath = $cleanPathRoot;
        } else {
            $filePath = null;
        }        

        // Jika file ditemukan, ambil konten file dalam format base64
        if ($filePath) {
            $fileContent = Storage::disk('inside')->get($filePath);
            $program->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
        } else {
            $program->logo_base64 = null;
        }

        $period = DB::table('m_stu_out_programs')
            ->whereDate('reg_date_start', '<=', now())
            ->whereDate('reg_date_closed', '>=', now())
            ->first();

        $fakultas = DB::table('m_fakultas_unit')->get();

        $prodi = DB::table('m_prodi')->get();
    
        if (!$period) {
            return redirect()->route('failed')->withErrors(['error' => 'Masa pengisian telah selesai, hubungi admin ']);
        }
    
        $univ = DB::table('m_university')->get();
        $country = DB::table('m_country')->get();
        // $course = DB::table('age_amerta_matkul')->get();
    
        return view('pendaftaran.registrasi_stu_out', [
            'id_period' => $period->id ?? null,
            'univ' => $univ,
            'country' => $country,
            'program' => $program,
            'fakultas' => $fakultas,
            'prodi' => $prodi
        ]);
    }

    public function Simpan_stuout(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan', 
            'tgl_lahir' => 'required|date',
            'telp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tujuan_fakultas_unit' => 'required|integer',
            'tujuan_prodi' => 'required|integer',
            'jenjang' => 'required|string|max:50',
            'prodi_asal' => 'required|string|max:255',
            'fakultas_asal' => 'required|string|max:255',
            'univ' => 'required|string|max:255',
            'kebangsaan' => 'required',
            'photo_url' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'cv_url' => 'required|file|mimes:pdf|max:2048',
            'student_id_url' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Include this in validation
            'program_id' => 'required',
        ]);
        

        $country = DB::table('m_university')
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->where('m_university.name', $request->input('univ')) // Sesuaikan dengan input yang benar
        ->pluck('m_country.name')
        ->first();

        $validated['negara_tujuan'] = $country;
    
        $fileFields = [
            'photo_url',
            'student_id_url',
            'cv_url',
        ];
    
        $uploadedFiles = []; // Initialize the array
    
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filePath = $this->storeFile($file, 'outbound');
                $validated[$field] = $filePath; // Use field name directly
                $uploadedFiles[] = $filePath;
            }
        }
    
        $validated['reg_time'] = now();
    
        // Uncomment this to inspect the validated data
        // dd($validated);
    
        // Create the record
        MStuOutPeserta::create($validated);
    
        // return redirect('/')->with('success', 'Application submitted successfully!');
        return response()->json(['status' => 'success', 'redirect' => route('login')]);
    }
}
