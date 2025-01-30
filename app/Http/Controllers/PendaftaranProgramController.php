<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\MStuInProgram;
use App\Models\MStuInPeserta;

class PendaftaranProgramController extends Controller
{
    public function stuin(Request $request, $url_generate)
        {
            
            $program =  MStuInProgram::where('url_generate', $url_generate)->first();
            
            $cleanPathRoot = ltrim(str_replace('repo/inbound/', '', $program->logo), '/');
            $cleanPathInbound = ltrim(str_replace('repo/', '', $program->logo), '/');

            // Pencarian file dalam repo
            if (Storage::disk('inside')->exists($cleanPathInbound)) {
                $filePath = $cleanPathInbound;
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
    
            $period = DB::table('m_stu_in_programs')
                ->whereDate('reg_date_start', '<=', now())
                ->whereDate('reg_date_closed', '>=', now())
                ->first();
        
            if (!$period) {
                return redirect()->route('failed')->withErrors(['error' => 'Masa pengisian telah selesai, hubungi admin ']);
            }
        
            $univ = DB::table('m_university')->get();
            $country = DB::table('m_country')->whereNot('id', 95)->get();
            $course = DB::table('age_amerta_matkul')->get();
        
            return view('pendaftaran.registrasi_stu_in', [
                'id_period' => $period->id ?? null,
                'univ' => $univ,
                'country' => $country,
                'course' => $course,
                'program' => $program
            ]);
        }

    public function Simpan_stuin(Request $request)
    {
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan', 
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
            'passport_url' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'program_info' => 'nullable|string|max:255',
            'program_id' => 'required',
        ]);

        $fileFields = [
            'photo_url' => 'photo_url',
            'passport_url' => 'passport_url',
            'cv_url' => 'cv_url',
        ];

        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Simpan file dan tambahkan ke array $uploadedFiles
                $filePath = $this->storeFile($file, 'inbound');
                $validated[$attribute] = $filePath;
                $uploadedFiles[] = $filePath;
            }
        }

        $validated['reg_time'] = now();

        // dd($validated);

        // Create the record
        MStuInPeserta::create($validated);

        return redirect('/');
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
}
