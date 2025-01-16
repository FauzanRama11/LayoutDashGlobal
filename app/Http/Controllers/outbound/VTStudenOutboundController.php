<?php

namespace App\Http\Controllers\outbound;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VTStudenOutboundController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('fakultas')) {
            $fa = $user->name;
            
            $data = DB::table('v_t_student_outbound')
            ->select('nim', 'nama_mhs', 'unit_kerja_text', 'prodi_text', 'nama_program', 'jk', 'via', 'prodi_tujuan_text', 'fakultas_tujuan_text', 'univ_tujuan_text', 'negara_tujuan_text', 'jenjang', 'tgl_mulai', 'tgl_selesai', 'tipe_text', 'created_date', 'photo_url', 'cv_url', 'loa')
            ->where('unit_kerja_text', 'like', "%$fa%")
            ->limit(500)
            ->orderBy('created_date', 'desc')
            ->get();
        }
        else{
            $data = DB::table('v_t_student_outbound')
            ->select('nim', 'nama_mhs', 'unit_kerja_text', 'prodi_text', 'nama_program', 'jk', 'via', 'prodi_tujuan_text', 'fakultas_tujuan_text', 'univ_tujuan_text', 'negara_tujuan_text', 'jenjang', 'tgl_mulai', 'tgl_selesai', 'tipe_text', 'created_date', 'photo_url', 'cv_url', 'loa')
            ->limit(500)
            ->orderBy('created_date', 'desc')
            ->get();

        };
    
        return view('stu_outbound.view_peserta', compact('data')); 
    }
    
}
