<?php

namespace App\Http\Controllers\outbound;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MStuOutPeserta;
use Carbon\Carbon;

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

        // $path = $request->file('fotoPeserta')->store('program_logos', 'public');
        // $path = $request->file('cvPeserta')->store('program_logos', 'public');
        // $path = $request->file('loaPeserta')->store('program_logos', 'public');
        // $path = $request->file('passPeserta')->store('program_logos', 'public');
        $path = "Anggap/Ini/Path";
        $program->photo_url = $path;
        $program->cv_url = $path;
        $program->loa_url = $path;
        $program->passport_url = $path;

        //dd($country);

        MStuOutPeserta::create($program->getAttributes());
        return redirect()->route('program_stuout.edit', ['id' => $request->input('progId')]);


    }
}
