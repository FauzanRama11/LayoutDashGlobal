<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\MStuInPeserta;

class MStuInPesertaController extends Controller
{
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
            
            $studentIdPaths = [
                'inbound/' . ltrim(str_replace('repo/inbound/', '', $data->student_id_url), '/'),
                ltrim(str_replace('repo/', '', $data->student_id_url), '/')
            ];
            $idFilePath = $this->getFilePath('inside', $studentIdPaths); 
            $data->id_base64 = $this->getFileBase64('inside', $idFilePath, 'image/jpeg');
            
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

        $peserta->passport_no = $request->input('noPassPeserta');
        $peserta->home_address = $request->input('homePeserta');

        $peserta->is_approved = 0;
    
        // Inisialisasi array untuk menyimpan path file
        $filePaths = [
            'photo_url' => null,
            'cv_url' => null,
            'loa_url' => null,
            'passport_url' => null,
            'student_id_url' => null,
        ];
    
        // Array field file dan atribut model yang bersesuaian
        $fileFields = [
            'fotoPeserta' => 'photo_url',
            'cvPeserta' => 'cv_url',
            'loaPeserta' => 'loa_url',
            'passPeserta' => 'passport_url',
            'idPeserta' => 'student_id_url',
        ];
    
        // Iterasi setiap field file untuk diproses
        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

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
        $peserta->loa_url = $filePaths['loa_url'];
        $peserta->passport_url = $filePaths['passport_url'];
        $peserta->student_id_url = $filePaths['student_id_url'];

        // dd($peserta->getAttributes());

        MStuInPeserta::create($peserta->getAttributes());
        return redirect()->route('program_stuin.edit', ['id' => $request->input('progId')]);


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
        $peserta->reg_time = now();

        $peserta->jenjang = $request->input('jenjangPeserta');
        $peserta->prodi_asal = $request->input('prodi_asal');
        $peserta->fakultas_asal = $request->input('fakultas_asal');
        $peserta->univ = $request->input('univ_asal');
        $peserta->negara_asal_univ = $request->input('negara_asal');
        $peserta->kebangsaan = $request->input('kebangsaan');
        $peserta->tujuan_fakultas_unit = $unit;
        $peserta->tujuan_prodi = $request->input('tprodiPeserta');
        $peserta->passport_no = $request->input('noPassPeserta');
        $peserta->home_address = $request->input('homePeserta');
        $peserta->is_approved = 0;

        // Array field file dan atribut model yang bersesuaian
        $fileFields = [
            'fotoPeserta' => 'photo_url',
            'cvPeserta' => 'cv_url',
            'loaPeserta' => 'loa_url',
            'passPeserta' => 'passport_url',
            'idPeserta' => 'student_id_url',
        ];

        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

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

        // dd($peserta);

        // Simpan perubahan pada model
        $peserta->save();

        return redirect()->route('program_stuin.edit', ['id' => $request->input('progId')]);
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
