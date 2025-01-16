<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MStuInTargetController extends Controller
{
    public function index()
    {
        $data = DB::table('m_stu_in_target')
        ->join('m_fakultas_unit', 'm_stu_in_target.id_fakultas', '=', 'm_fakultas_unit.id')
        ->select('m_stu_in_target.id', 'm_stu_in_target.year', 'm_fakultas_unit.nama_ind as fakultas_name', 'm_stu_in_target.target_pt', 'm_stu_in_target.target_ft')
        ->get();

        return view('stu_inbound.target', compact('data'));
    
    }
}
