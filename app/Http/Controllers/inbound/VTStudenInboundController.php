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
            
            $data = DB::table('view_stu_in')
            ->select('nama', 'unit_kerja_text', 'jenjang', 'prodi_asal', 'fakultas_asal', 'jenis_kelamin', 'via', 'tipe_text', 'tujuan_fakultas_unit_text', 'tujuan_prodi_text', 'jenjang', 'univ_asal_text', 'negara_asal_text', 'program_text', 'jenis_kegiatan_text', 'created_date', 'foto', 'passport', 'student_id','loa')
            ->where('unit_kerja_text', 'like', "%$fa%")
            ->limit(500)
            ->orderBy('created_date', 'desc')
            ->get();
        }
        else{
            $data = DB::table('m_stu_in_peserta as t')
                ->select(
                    't.*',
                    'p.host_unit_text as unit_kerja',
                    'p.name as nama_prog',
                    'p.start_date',
                    'p.end_date',
                    'p.via',
                    'p.created_time',
                    'p.pt_ft',
                    'u.name as nama_univ',
                    'n.name as kebangsaan',
                    'f.nama_ind as fak_ind',
                    'm.name as tujuan_p',
                    'c.name as tipe',
                    'p.category_text as jenis_kegiatan',
                    'm.level as jenjang_prodi',
                    't.program_info as info'
                )
                ->join('m_prodi as m', 'm.id', '=', 't.tujuan_prodi')
                ->leftJoin('m_stu_in_programs as p', 'p.id', '=', 't.program_id')
                ->leftJoin('m_university as u', 'u.id', '=', 't.univ')
                ->leftJoin('m_country as n', 'n.id', '=', 't.kebangsaan')
                ->leftJoin('m_fakultas_unit as f', 'f.id', '=', 't.tujuan_fakultas_unit')
                ->leftJoin('m_stu_in_program_category as c', 'c.id', '=', 't.program_id')
                ->where('t.is_approved', 1)
                ->orderByDesc('p.created_time')
                ->get();

        };

        return view('stu_inbound.view_peserta', compact('data'));
    }
}
