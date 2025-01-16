<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MStuInPeserta;
use Illuminate\Support\Facades\Auth;

class StudentInboundController extends Controller
{
    public function approval_dana()
    {
        $data = DB::table('m_stu_in_peserta')
        ->select(  'reg_time', 'm_stu_in_peserta.id', 'm_stu_in_peserta.nama', 'm_fakultas_unit.nama_ind as fakultas','m_stu_in_programs.name as program', 'm_stu_in_programs.pt_ft as tipe', 'm_university.name as univ', 'm_country.name as negara_asal_univ', 'photo_url','passport_url','student_id_url', 'loa_url','cv_url','pengajuan_dana_status as dana_status')
        ->join('m_stu_in_programs', 'm_stu_in_peserta.program_id', '=', 'm_stu_in_programs.id')
        ->join('m_fakultas_unit', 'm_stu_in_peserta.tujuan_fakultas_unit', '=', 'm_fakultas_unit.id')
        ->join('m_university', 'm_stu_in_peserta.univ', '=', 'm_university.id')
        ->join('m_country', 'm_university.country', '=', 'm_country.id')
        ->where('pengajuan_dana_status', '!=', "")
        ->limit(500)
        ->orderBy('reg_time', 'desc')
        ->get();


        return view('stu_inbound.approval_dana', compact('data'));
    }
    
    public function approval_pelaporan()
    {
        $data = DB::table('m_stu_in_peserta as t')
            ->select('t.*', 'p.via as via', 'p.start_date as start_date', 'p.end_date as end_date', 'p.host_unit_text as host_unit', 'u.name as univ_name', 'c.name as country_name', 'p.pt_ft as tipe', 'p.name as program')
            ->join('m_university as u', 'u.id', '=', 't.univ')
            ->join('m_country as c', 'c.id', '=', 't.kebangsaan')
            ->join('m_stu_in_programs as p', function ($join) {
                $join->on('p.id', '=', 't.program_id')
                    ->where('p.is_private_event', '=', 'Ya');
            })
            ->get();
    
        return view('stu_inbound.approval_pelaporan', compact('data'));
    }

    public function action_approve(Request $request, $id)
    {
        $peserta = MStuInPeserta::find($id);
        $peserta->is_approved = 1;
        $peserta->approved_time = now();
        $peserta->approved_by = Auth::user()->id;
        $peserta->save();
    
        return redirect()->route('stuout_approval_pelaporan');
    }
    
}
