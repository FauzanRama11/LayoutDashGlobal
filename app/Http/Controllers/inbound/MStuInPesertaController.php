<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MStuInPeserta;

class MStuInPesertaController extends Controller
{
    public function add_peserta(string $ids){
        $fakultas = DB::table('m_fakultas_unit')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $prodi = DB::table('m_prodi')->get();

        return view('stu_inbound.form_peserta', compact('ids', 'fakultas', 'univ',  'prodi'));
    }   

    public function store_peserta(Request $request){

        $country = DB::table('m_university')
        ->where('m_university.id', '=', $request->input('univTujuanPeserta'))
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->pluck('m_country.id')->first();

        $program = new MStuInPeserta();
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
        $storagePath = base_path('../penyimpanan'); 

        // Pastikan folder "penyimpanan" ada
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true); 
        }
    
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
    
                // Buat nama file unik
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
    
                // Pindahkan file ke direktori "penyimpanan"
                $file->move($storagePath, $fileName);
    
                // Simpan path ke dalam atribut model
                $filePaths[$attribute] = 'penyimpanan/' . $fileName;
            }
        }
    
        $program->photo_url = $filePaths['photo_url'];
        $program->cv_url = $filePaths['cv_url'];
        $program->loa_url = $filePaths['loa_url'];
        $program->passport_url = $filePaths['passport_url'];

        dd($program);

        MStuInPeserta::create($program->getAttributes());
        return redirect()->route('program_stuin.edit', ['id' => $request->input('progId')]);


    }
    
}
