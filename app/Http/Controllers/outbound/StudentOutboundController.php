<?php

namespace App\Http\Controllers\outbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MStuOutPeserta;
use Illuminate\Support\Facades\Auth;

class StudentOutboundController extends Controller
{
    public function approval_dana()
    {
        $data = DB::table('m_stu_out_peserta')
        ->select(  'reg_time', 'm_stu_out_peserta.id', 'm_stu_out_peserta.nama', 'm_fakultas_unit.nama_ind as fakultas','m_stu_out_programs.name as program', 'm_stu_out_programs.pt_ft as tipe', 'm_university.name as univ', 'm_country.name as negara_asal_univ', 'photo_url','passport_url','student_id_url', 'loa_url','cv_url','pengajuan_dana_status as dana_status')
        ->join('m_stu_out_programs', 'm_stu_out_peserta.program_id', '=', 'm_stu_out_programs.id')
        ->join('m_fakultas_unit', 'm_stu_out_peserta.tujuan_fakultas_unit', '=', 'm_fakultas_unit.id')
        ->join('m_university', 'm_stu_out_peserta.univ', '=', 'm_university.id')
        ->join('m_country', 'm_university.country', '=', 'm_country.id')
        ->where('pengajuan_dana_status', '!=', "")
        ->limit(500)
        ->orderBy('reg_time', 'desc')
        ->get();

        return view('stu_outbound.approval_dana', compact('data'));
    }
    
    public function approval_pelaporan()
    {
        $data = DB::table('m_stu_out_peserta as t')
        ->select('t.*', 'p.via as via', 'p.start_date as start_date', 'p.end_date as end_date', 'p.host_unit_text as host_unit', 'u.name as univ_name', 'c.name as country_name', 'p.pt_ft as tipe', 'p.name as program')
        ->join('m_university as u', 'u.id', '=', 't.univ')
        ->join('m_country as c', 'c.id', '=', 't.kebangsaan')
        ->join('m_stu_out_programs as p', function ($join) {
            $join->on('p.id', '=', 't.program_id')
                ->where('p.is_private_event', '=', 'Ya');
        })
        ->get();

        return view('stu_outbound.approval_pelaporan', compact('data'));
    }

    public function action_approve(Request $request, $id)
    {
        $peserta = MStuOutPeserta::find($id);
        $peserta->is_approved = 1;
        $peserta->approved_time = now();
        $peserta->approved_by = Auth::user()->id;
        $peserta->save();
    
        return redirect()->route('stuout_approval_pelaporan');
    }
    
    

    public function pengajuan_setneg()
    {
        $data = DB::table('m_stu_out_peserta_setneg as t')
        ->select('t.*','s.nama as nama', 'p.name as program', 'p.id as pid', 'c.name as country', 'p.start_date as start_date', 'f.nama_ind as fakultas',  DB::raw("CONCAT(pr.level, ' ', pr.name) as prodi"))
        ->join('m_stu_out_peserta as s', 's.id', '=', 't.stu_out_id')
        ->join('m_stu_out_programs as p', 'p.id', '=', 's.program_id')
        ->join('m_country as c', 'c.id', '=', 's.kebangsaan')
        ->leftJoin('m_fakultas_unit as f', 'f.id', '=', 's.tujuan_fakultas_unit')
        ->leftJoin('m_prodi as pr', 'pr.id', '=', 's.tujuan_prodi')
        ->orderBy('p.id', 'desc')
        ->orderBy('t.id', 'desc')
        ->get();
    
        return view('stu_outbound.pengajuan_setneg', compact('data'));
    }
}
