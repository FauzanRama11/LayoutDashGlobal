<?php

namespace App\Http\Controllers\outbound;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\MStuOutPeserta;

class MStuOutPesertaController extends Controller
{
    public function add_peserta(string $ids){
        $fakultas = DB::table('m_fakultas_unit')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $prodi = DB::table('m_prodi')->get();

        return view('stu_outbound.form_peserta', compact('ids', 'fakultas', 'univ',  'prodi'));
    }   

    public function peserta(string $prog_id, $item_id = null){
        $fakultas = DB::table('m_fakultas_unit')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $prodi = DB::table('m_prodi')->get();
        $country = DB::table('m_country')->get();

        $data = null;
    
        if ($item_id) {
            $data = MStuOutPeserta::find($item_id);

            if ($data && $data->tujuan_fakultas_unit) {
                $fakultasNama = DB::table('m_fakultas_unit')
                    ->where('id', $data->tujuan_fakultas_unit) 
                    ->value('nama_ind'); 

                $data->tujuan_fakultas_unit = $fakultasNama;  
            }
            
            $studentIdPaths = [
                'outbound/' . ltrim(str_replace('repo/outbound/', '', $data->student_id_url), '/'),
                ltrim(str_replace('repo/', '', $data->student_id_url), '/')
            ];
            $idFilePath = $this->getFilePath('inside', $studentIdPaths); 
            $data->id_base64 = $this->getFileBase64('inside', $idFilePath, 'image/jpeg');
            
            $photoPaths = [
                'outbound/' . ltrim(str_replace('repo/outbound/', '', $data->photo_url), '/'),
                ltrim(str_replace('repo/', '', $data->photo_url), '/')
            ];

            $photoFilePath = $this->getFilePath('inside', $photoPaths); 
            $data->photo_base64 = $this->getFileBase64('inside', $photoFilePath, 'image/png');
        }
        // dd($data);
    
        return view('stu_outbound.form_peserta', compact('prog_id', 'data', 'country', 'fakultas', 'univ',  'prodi'));
    }   

    public function store_peserta(Request $request){

        $country = DB::table('m_university')
        ->where('m_university.id', '=', $request->input('univTujuanPeserta'))
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->pluck('m_country.id')->first();

        $program = new MStuOutPeserta();
        $program->program_id = $request->input('progId');
        $program->nama = $request->input('namePeserta');
        $program->nim = $request->input('nimPeserta');
        $program->angkatan = $request->input('angkatanPeserta');
        $program->jenis_kelamin = $request->input('jkPeserta');
        $program->tgl_lahir = $request->input('dobPeserta');
        $program->telp = $request->input('telpPeserta');
        $program->email = $request->input('emailPeserta');
        $program->jenjang = $request->input('jenjangPeserta');
        $program->prodi_asal = $request->input('tProdiPeserta');
        $program->fakultas_asal = $request->input('tFakultasPeserta');
        $program->univ = $request->input('univTujuanPeserta');
        $program->kebangsaan = $country;
        $program->tujuan_fakultas_unit = $request->input('fakultasPeserta');
        $program->tujuan_prodi = $request->input('prodiPeserta');
        $program->passport_no = $request->input('noPassPeserta');
        $program->home_address = $request->input('homePeserta');
        $program->is_approved = 0;

        // Inisialisasi array untuk menyimpan path file
        $filePaths = [
            'photo_url' => null,
            'cv_url' => null,
            'loa_url' => null,
            'passport_url' => null,
        ];
    
        // Array field file dan atribut model yang bersesuaian
        $fileFields = [
            'fotoPeserta' => 'photo_url',
            'cvPeserta' => 'cv_url',
            'loaPeserta' => 'loa_url',
            'passPeserta' => 'passport_url',
        ];
    
        // Iterasi setiap field file untuk diproses
        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                $storagePath = '/outbound';
                if (!Storage::disk('inside')->exists($storagePath)) {
                        Storage::disk('inside')->makeDirectory($storagePath);
                }
    
                // Buat nama file unik
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs($storagePath, $fileName, 'inside');
    
                // Simpan path ke dalam atribut model
                $filePaths[$attribute] = 'repo/outbound/' . $fileName;
            }
        }
        
        $program->photo_url = $filePaths['photo_url'];
        $program->cv_url = $filePaths['cv_url'];
        $program->loa_url = $filePaths['loa_url'];
        $program->passport_url = $filePaths['passport_url'];

        //dd($country);

        MStuOutPeserta::create($program->getAttributes());
        return redirect()->route('program_stuout.edit', ['id' => $request->input('progId')]);


    }

    public function update_peserta(Request $request)
    {
        $country = DB::table('m_university')
        ->where('m_university.id', '=', $request->input('univTujuanPeserta'))
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->pluck('m_country.id')->first();

        // Ambil data peserta berdasarkan ID jika ada
        $peserta = MStuOutPeserta::findOrFail($request->input('peserta_id'));

        // Update data lain pada peserta
        $peserta->program_id = $request->input('progId');
        $peserta->nama = $request->input('namePeserta');
        $peserta->jenis_kelamin = $request->input('jkPeserta');
        $peserta->tgl_lahir = $request->input('dobPeserta');
        $peserta->telp = $request->input('telpPeserta');
        $peserta->email = $request->input('emailPeserta');
        $peserta->reg_time = now();

        $peserta->jenjang = $request->input('jenjangPeserta');
        $peserta->prodi_asal = $request->input('tProdiPeserta');
        $peserta->fakultas_asal = $request->input('tFakultasPeserta');
        $peserta->univ = $request->input('univTujuanPeserta');
        $peserta->kebangsaan = $country;
        // $peserta->kebangsaan = $request->input('kebangsaan');
        $peserta->tujuan_fakultas_unit = $request->input('fakultasPeserta');
        $peserta->tujuan_prodi = $request->input('prodiPeserta');
        $peserta->passport_no = $request->input('noPassPeserta');
        $peserta->home_address = $request->input('homePeserta');
        // $peserta->is_approved = 0;

        // Array field file dan atribut model yang bersesuaian
        $fileFields = [
            'fotoPeserta' => 'photo_url',
            'cvPeserta' => 'cv_url',
            'loaPeserta' => 'loa_url',
            'passPeserta' => 'passport_url',
        ];

        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Tentukan path penyimpanan
                $storagePath = '/outbound';
                if (!Storage::disk(name: 'inside')->exists($storagePath)) {
                    Storage::disk('inside')->makeDirectory($storagePath);
                }

                // Buat nama file unik
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs($storagePath, $fileName, 'inside');

                // Perbarui atribut model dengan path file baru
                $peserta->{$attribute} = 'repo/outbound/' . $fileName;
                // dd($peserta->{$attribute});
            }
        }

        // dd($peserta);

        // Simpan perubahan pada model
        $peserta->save();

        return redirect()->route('program_stuout.edit', ['id' => $request->input('progId')]);
    }
    public function BantuanDana(Request $request) {
        $request->validate([
            'id' => 'required|integer',
            'tipe' => 'required|string|in:RKAT,DAPT'
        ]);
    
        $model = MStuOutPeserta::find($request->id);
        
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
