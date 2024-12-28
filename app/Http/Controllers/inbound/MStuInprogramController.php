<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MStuInprogramController extends Controller
{
    public function program_fak()
    {
        $user = Auth::user();

        if ($user->hasRole('fakultas')) {
            $fa = $user->name;
            
            $data = $data = DB::table('m_stu_in_programs')
            ->select( 'name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
            ->where('host_unit_text', 'like', "%$fa%")
            ->where("is_program_age", "N")
            ->limit(500)
            ->orderBy('created_time', 'desc')
            ->get();
        }
        else{
            $data = DB::table('m_stu_in_programs')
            ->select( 'name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
            ->where("is_program_age", "N")
            ->limit(500)
            ->orderBy('created_time', 'desc')
            ->get();
        };

        return view('stu_inbound.program_fak', compact('data'));
    }
    public function program_age()
    {
        $data = $data = DB::table('m_stu_in_programs')
        ->select( 'name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
        ->where("is_program_age", "Y")
        ->orWhere('host_unit_text', 'Airlangga Global Engagement')
        ->limit(500)
        ->orderBy('created_time', 'desc')
        ->get();
        return view('stu_inbound.program_age', compact('data'));
    }
    public function target()
    {
        
    }
}
