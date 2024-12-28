<?php

namespace App\Http\Controllers\outbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MStuOutTargetController extends Controller
{
    public function index()
    {
        $data = DB::table('m_stu_out_target')
                    ->join('m_fakultas_unit', 'm_stu_out_target.id_fakultas', '=', 'm_fakultas_unit.id')
                    ->select('m_stu_out_target.id', 'm_stu_out_target.year', 'm_fakultas_unit.nama_ind as fakultas_name', 'm_stu_out_target.target_pt', 'm_stu_out_target.target_ft')
                    ->get();
            
        return view('stu_outbound.target', compact('data'));
}
}
