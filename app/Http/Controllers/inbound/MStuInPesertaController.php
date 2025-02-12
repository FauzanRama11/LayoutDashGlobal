<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\MStuInPeserta;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class MStuInPesertaController extends Controller
{

    public function profile(){
        $data = DB::table('m_stu_in_peserta as m')
        ->leftJoin('m_university as u', 'm.univ', '=', 'u.id')
        ->leftJoin('m_country as c', 'm.kebangsaan', '=', 'c.id')
        ->select(
            'm.*', 
            'u.name as university_name', 
            'c.name as country'
        )
        ->where('m.passport_no', Auth::user()->username) 
        ->orderBy('reg_time', 'desc')
        // ->orWhere('m.student_id_no', Auth::user()->username) 
        ->first(); 

        if($data->photo_url){   
            $photoPaths = [
                'inbound/' . ltrim(str_replace('repo/inbound/', '', $data->photo_url), '/'),
                ltrim(str_replace('repo/', '', $data->photo_url), '/')
            ];
            $photoPath = $this->getFilePath('inside', $photoPaths); 
            $data->photo_base64 = $this->getFileBase64('inside', $photoPath, mimeType: 'image/jpeg');
        }

        
        if($data->student_id_url){   
            $studentIdPaths = [
                'inbound/' . ltrim(str_replace('repo/inbound/', '', $data->student_id_url), '/'),
                ltrim(str_replace('repo/', '', $data->student_id_url), '/')
            ];
            $idFilePath = $this->getFilePath('inside', $studentIdPaths); 
            $data->id_base64 = $this->getFileBase64('inside', $idFilePath, mimeType: 'image/jpeg');
        }
        
        if($data->passport_url){   
            $passportPaths = [
                'inbound/' . ltrim(str_replace('repo/inbound/', '', $data->passport_url), '/'),
                ltrim(str_replace('repo/', '', $data->passport_url), '/')
            ];

            $passportFilePath = $this->getFilePath('inside', $passportPaths); 
            $data->passport_base64 = $this->getFileBase64('inside', $passportFilePath, 'image/png');
    }
        
        return view('stu_inbound.mahasiswa_profile', compact('data'));
    }

    public function program($params){
        $programs = DB::table('m_stu_in_peserta as s')
            ->select('s.*', 'p.*', 'p.name as nama_program', 'u.*')
            ->leftJoin('m_stu_in_programs as p', 's.program_id', '=', 'p.id')
            ->leftJoin('m_university as u', 's.univ', '=', 'u.id')
            ->where('s.passport_no', Auth::user()->username);

        if ($params == "finished") {
            $programs->whereNotNull('end_date')->whereDate('end_date', '<', now());
        } else {
            $programs->where(function ($query) {
                $query->whereNull('end_date') 
                    ->orWhereDate('end_date', '>=', now());
            });
        }

        $programs = $programs->get();
        // dd($programs);

        foreach ($programs as $program) {
        
            if ($program && $program->logo) {
                $cleanPathRoot = ltrim(str_replace('repo/inbound/', '', $program->logo), '/');
                $cleanPathInbound = ltrim(str_replace('repo/', '', $program->logo), '/');
            
                if (Storage::disk('inside')->exists($cleanPathInbound)) {
                    $filePath = $cleanPathInbound;
                } elseif (Storage::disk('inside')->exists($cleanPathRoot)) {
                    $filePath = $cleanPathRoot;
                } else {
                    $filePath = null;
                }
            
                if ($filePath) {
                    $fileContent = Storage::disk('inside')->get($filePath);
                    $program->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
                } else {
                    $program->logo_base64 = null;
                }
            }else{
                $program->logo_base64 = null;
            }

            if($program->photo_url){   
                $photoPaths = [
                    'inbound/' . ltrim(str_replace('repo/inbound/', '', $program->photo_url), '/'),
                    ltrim(str_replace('repo/', '', $program->photo_url), '/')
                ];
                $photoPath = $this->getFilePath('inside', $photoPaths); 
                $program->photo_base64 = $this->getFileBase64('inside', $photoPath, mimeType: 'image/jpeg');
            }
    
        }

        return view('stu_inbound.mahasiswa_program', compact('programs', 'params'));
    }
 
    public function peserta(string $prog_id, $item_id = null){

        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $prodi = DB::table('m_prodi')->get();
        $country = DB::table('m_country')->get();

        $data = null;
    
        if ($item_id) {

            $data = MStuInPeserta::find($item_id);
            

            if ($data && $data->tujuan_fakultas_unit) {
                $fakultasNama = DB::table('m_fakultas_unit')
                    ->where('id', $data->tujuan_fakultas_unit) 
                    ->value('nama_ind'); 

                $data->tujuan_fakultas_unit = $fakultasNama;  
            }
            
            // Student ID ======
            $studentIdPaths = [
                'inbound/' . ltrim(str_replace('repo/inbound/', '', $data->student_id_url), '/'),
                ltrim(str_replace('repo/', '', $data->student_id_url), '/')
            ];
            $idFilePath = $this->getFilePath('inside', $studentIdPaths); 
            $data->id_base64 = $this->getFileBase64('inside', $idFilePath, 'image/jpeg');

            // Passport ID ======
            $passportPaths = [
                'inbound/' . ltrim(str_replace('repo/inbound/', '', $data->passport_url), '/'),
                ltrim(str_replace('repo/', '', $data->passport_url), '/')
            ];
            $passportFilePath = $this->getFilePath('inside', $passportPaths); 
            $data->pass_base64 = $this->getFileBase64('inside', $passportFilePath, 'image/jpeg');

            // Photo 
            $photoPaths = [
                'inbound/' . ltrim(str_replace('repo/inbound/', '', $data->photo_url), '/'),
                ltrim(str_replace('repo/', '', $data->photo_url), '/')
            ];

            $photoFilePath = $this->getFilePath('inside', $photoPaths); 
            $data->photo_base64 = $this->getFileBase64('inside', $photoFilePath, 'image/png');
        }
    
        return view('stu_inbound.form_peserta', compact('prog_id', 'data', 'country', 'univ',  'prodi'));
    }   

    public function store_peserta(Request $request){

        $unit = DB::table('m_fakultas_unit')
        ->where('nama_ind', $request->input('tfakultasPeserta'))
        ->pluck('id')->first(); 

        $peserta = new MStuInPeserta();
        $peserta->program_id = $request->input('progId');
        $peserta->nama = $request->input('namePeserta');
        $peserta->jenis_kelamin = $request->input('jkPeserta');
        $peserta->tgl_lahir = $request->input('dobPeserta');
        $peserta->telp = $request->input('telpPeserta');
        $peserta->email = $request->input('emailPeserta');
        $peserta->reg_time = now();

        $peserta->jenjang = $request->input('jenjangPeserta');
        $peserta->prodi_asal = $request->input('prodi_asal');
        $peserta->fakultas_asal = $request->input('fakultas_asal');
        $peserta->univ = $request->input('univ_asal');
        $peserta->negara_asal_univ = $request->input('negara_asal');
        $peserta->kebangsaan = $request->input('kebangsaan');
        $peserta->tujuan_fakultas_unit = $unit;
        $peserta->tujuan_prodi = $request->input('tprodiPeserta');

        
        $peserta->selected_id = $request->input('selected_id');
        $peserta->passport_no = $request->input('passport_no');
        $peserta->student_no = $request->input('student_no');
        $peserta->home_address = $request->input('homePeserta');

        $peserta->is_approved = 0;
        $peserta->is_loa = 1;
    
        // Inisialisasi array untuk menyimpan path file
        $filePaths = [
            'photo_url' => null,
            'cv_url' => null,
            // 'loa_url' => null,
            'passport_url' => null,
            'student_id_url' => null,
        ];
    
        // Array field file dan atribut model yang bersesuaian
        $fileFields = [
            'photo_url' => 'photo_url',
            'cvPeserta' => 'cv_url',
            // 'loaPeserta' => 'loa_url',
            'passport_url' => 'passport_url',
            'student_id_url' => 'student_id_url',
        ];

        $mimeTypesMap = [
            'cvPeserta' => 'pdf',
            // 'loaPeserta' => 'pdf',
            'photo_url' => 'png,jpg,jpeg',
            'passport_url' => 'pdf,png,jpg,jpeg',
            'student_id_url' => 'pdf,png,jpg,jpeg',
        ];
    
        // Iterasi setiap field file untuk diproses
        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                $mimeTypes = $mimeTypesMap[$field] ?? 'png,jpg,jpeg';

                try {
                    $request->validate([
                        $field => 'required|file|mimes:' . $mimeTypes . '|max:2048',
                    ]);
                } catch (ValidationException $e) {
                    return response()->json(['status' => 'error', 'message' => 'Please check again your file type!'], 500);
                }


                $storagePath = '/inbound';
                if (!Storage::disk('inside')->exists($storagePath)) {
                        Storage::disk('inside')->makeDirectory($storagePath);
                }
    
                // Buat nama file unik
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs($storagePath, $fileName, 'inside');
    
                // Simpan path ke dalam atribut model
                $filePaths[$attribute] = 'repo/inbound/' . $fileName;
            }
        }
    
        $peserta->photo_url = $filePaths['photo_url'];
        $peserta->cv_url = $filePaths['cv_url'];
        // $peserta->loa_url = $filePaths['loa_url'];
        $peserta->passport_url = $filePaths['passport_url'];
        $peserta->student_id_url = $filePaths['student_id_url'];

        // dd($peserta->getAttributes());

        MStuInPeserta::create($peserta->getAttributes());
        // return redirect()->route('program_stuin.edit', ['id' => $request->input('progId')]);
        return response()->json(['status' => 'success', 'redirect' => route('program_stuin.edit', ['id' => $request->input('progId')])]);


    }

    public function update_peserta(Request $request)
    {
        
        $peserta = MStuInPeserta::findOrFail($request->input('peserta_id'));
        
        $unit = DB::table('m_fakultas_unit')
        ->where('nama_ind', $request->input('tfakultasPeserta'))
        ->pluck('id')->first(); 

        $peserta->program_id = $request->input('progId');
        $peserta->nama = $request->input('namePeserta');
        $peserta->jenis_kelamin = $request->input('jkPeserta');
        $peserta->tgl_lahir = $request->input('dobPeserta');
        $peserta->telp = $request->input('telpPeserta');
        $peserta->email = $request->input('emailPeserta');

        $peserta->jenjang = $request->input('jenjangPeserta');
        $peserta->prodi_asal = $request->input('prodi_asal');
        $peserta->fakultas_asal = $request->input('fakultas_asal');
        $peserta->univ = $request->input('univ_asal');
        $peserta->negara_asal_univ = $request->input('negara_asal');
        $peserta->kebangsaan = $request->input('kebangsaan');
        $peserta->tujuan_fakultas_unit = $unit;
        $peserta->tujuan_prodi = $request->input('tprodiPeserta'); 

        if ($peserta->selected_id === null || $peserta->selected_id === '') {
            $peserta->selected_id = $request->input('selected_id');
        }

        $peserta->passport_no = $request->input('passport_no');
        $peserta->student_no = $request->input('student_no');
        $peserta->home_address = $request->input('homePeserta');

        if ($peserta->tujuan_prodi && $peserta->is_loa === null ) {
            $peserta->is_loa = 0;
        }

        $fileFields = [
            'cvPeserta' => 'cv_url',
            // 'loaPeserta' => 'loa_url',
            'photo_url' => 'photo_url',
            'passport_url' => 'passport_url',
            'student_id_url' => 'student_id_url',
        ];

        $mimeTypesMap = [
            'cvPeserta' => 'pdf',
            // 'loaPeserta' => 'pdf',
            'photo_url' => 'png,jpg,jpeg',
            'passport_url' => 'pdf,png,jpg,jpeg',
            'student_id_url' => 'pdf,png,jpg,jpeg',
        ];


        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $mimeTypes = $mimeTypesMap[$field] ?? 'png,jpg,jpeg';

                try {
                    $request->validate([
                        $field => 'required|file|mimes:' . $mimeTypes . '|max:2048',
                    ]);
                } catch (ValidationException $e) {
                    return response()->json(['status' => 'error', 'message' => 'Please upload <2 MB valid files!'], 500);
                }

                // Tentukan path penyimpanan
                $storagePath = '/inbound';
                if (!Storage::disk('inside')->exists($storagePath)) {
                    Storage::disk('inside')->makeDirectory($storagePath);
                }

                // Buat nama file unik
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs($storagePath, $fileName, 'inside');

                $peserta->{$attribute} = 'repo/inbound/' . $fileName;
            }
        }


        $peserta->save();

        return response()->json(['peserta' => $peserta,'status' => 'success', 'redirect' => route('stuin_peserta.edit', ['prog_id' => $request->input('progId'), 'item_id' => $request->input('peserta_id')])]);

    }

    public function BantuanDana(Request $request) {
        $request->validate([
            'id' => 'required|integer',
            'tipe' => 'required|string|in:RKAT,DPAT'
        ]);
    
        $model = MStuInPeserta::find($request->id);
        
        if (!$model) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }
    
        $model->pengajuan_dana_status = 'REQUESTED';
        $model->sumber_dana = $request->tipe;
        $model->save();
    
        return response()->json(['message' => 'Pengajuan Bantuan Dana berhasil diajukan.']);
    }
    

    private function getFilePath($disk, $paths)
    {
        foreach ($paths as $path) {
            if (Storage::disk($disk)->exists($path)) {
                return $path;
            }
        }
        return null;
    }

    private function getFileBase64($disk, $filePath, $mimeType)
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            $fileContent = Storage::disk($disk)->get($filePath);
            return 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);
        }
        return null;
    }

    
}
