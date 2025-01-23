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
