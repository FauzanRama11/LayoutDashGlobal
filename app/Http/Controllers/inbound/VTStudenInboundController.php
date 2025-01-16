<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VTStudenInboundController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('fakultas')) {
            $fa = $user->name;
            
            $data = DB::table('v_t_student_inbound')
            ->select('nama', 'unit_kerja_text', 'jenjang', 'prodi_asal', 'fakultas_asal', 'jenis_kelamin', 'via', 'tipe_text', 'tujuan_fakultas_unit_text', 'tujuan_prodi_text', 'jenjang', 'univ_asal_text', 'negara_asal_text', 'program_text', 'jenis_kegiatan_text', 'created_date', 'foto', 'passport', 'student_id','loa')
            ->where('unit_kerja_text', 'like', "%$fa%")
            ->limit(500)
            ->orderBy('created_date', 'desc')
            ->get();
        }
        else{
            $data = DB::table('v_t_student_inbound')
            ->select('nama', 'unit_kerja_text', 'jenjang', 'prodi_asal', 'fakultas_asal', 'jenis_kelamin', 'via', 'tipe_text', 'tujuan_fakultas_unit_text', 'tujuan_prodi_text', 'jenjang', 'univ_asal_text', 'negara_asal_text', 'program_text', 'jenis_kegiatan_text', 'created_date', 'foto', 'passport', 'student_id','loa')
            ->limit(500)
            ->orderBy('created_date', 'desc')
            ->get();
        };

        return view('stu_inbound.view_peserta', compact('data'));
    }
}
