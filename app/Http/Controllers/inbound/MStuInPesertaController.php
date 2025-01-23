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
        $fakultas = DB::table('m_fakultas_unit')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $prodi = DB::table('m_prodi')->get();
        $country = DB::table('m_country')->get();

        $data = null;
    
        if ($item_id) {
            $data = MStuInPeserta::find($item_id);
            $photoPath = 'inbound/' .  ltrim(str_replace('repo/', '', $data->photo_url), '/');
            $idPath = 'inbound/' .  ltrim(str_replace('repo/', '', $data->student_id_url), '/');
            
            // Student ID
            if ($data->student_id_url && Storage::disk('outside')->exists($idPath)) {
                $fileContent = Storage::disk('outside')->get($idPath);
                $data->id_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
                
            } else {
                $data->id_base64 = null;
            }

            // Proflie photo
            if ($data->photo_url && Storage::disk('outside')->exists($photoPath)) {
                $fileContent = Storage::disk('outside')->get($photoPath);
                $data->photo_base64 = 'data:image/png;base64,' . base64_encode($fileContent);
            } else {
                $data->photo_base64 = null;
            }
        }
    
        return view('stu_inbound.form_peserta', compact('prog_id', 'data', 'country', 'fakultas', 'univ',  'prodi'));
    }   

    public function store_peserta(Request $request){

        $country = DB::table('m_university')
        ->where('m_university.id', '=', $request->input('univTujuanPeserta'))
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->pluck('m_country.id')->first();

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
        $peserta->kebangsaan = $request->input('kebangsaan');;
        $peserta->tujuan_fakultas_unit = $request->input('tfakultasPeserta');
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
                if (!Storage::disk('outside')->exists($storagePath)) {
                        Storage::disk('outside')->makeDirectory($storagePath);
                }
    
                // Buat nama file unik
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs($storagePath, $fileName, 'outside');
    
                // Simpan path ke dalam atribut model
                $filePaths[$attribute] = 'repo/' . $fileName;
            }
        }
    
        $peserta->photo_url = $filePaths['photo_url'];
        $peserta->cv_url = $filePaths['cv_url'];
        $peserta->loa_url = $filePaths['loa_url'];
        $peserta->passport_url = $filePaths['passport_url'];
        $peserta->student_id_url = $filePaths['student_id_url'];

        MStuInPeserta::create($peserta->getAttributes());
        return redirect()->route('program_stuin.edit', ['id' => $request->input('progId')]);


    }

    public function update_peserta(Request $request)
    {
        // Ambil data peserta berdasarkan ID jika ada
        $peserta = MStuInPeserta::findOrFail($request->input('peserta_id'));

        // Update data lain pada peserta
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
        $peserta->tujuan_fakultas_unit = $request->input('tfakultasPeserta');
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
                if (!Storage::disk('outside')->exists($storagePath)) {
                    Storage::disk('outside')->makeDirectory($storagePath);
                }

                // Buat nama file unik
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs($storagePath, $fileName, 'outside');

                // Perbarui atribut model dengan path file baru
                $peserta->{$attribute} = 'repo/' . $fileName;
                // dd($peserta->{$attribute});
            }
        }

        // dd($peserta);

        // Simpan perubahan pada model
        $peserta->save();

        return redirect()->route('program_stuin.edit', ['id' => $request->input('progId')]);
    }

    
}
