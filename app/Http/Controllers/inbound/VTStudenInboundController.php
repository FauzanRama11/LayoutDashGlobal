<?php

namespace App\Http\Controllers\inbound;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewStuIn;

class VTStudenInboundController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

            $query = ViewStuIn::query();

                if ($user->hasRole('fakultas')) {
                    $fa = $user->name;
                    
                    $fakultas = DB::table('m_fakultas_unit')
                    ->where('nama_ind', '=', $fa)
                    ->value('nama_ind');

                    $query->where('tujuan_fakultas_unit_text', '=', $fa)
                    ->orWhere('tujuan_fakultas_unit_text', '=',$fakultas);
                };

                if ($request->has('order')) {
                    foreach ($request->order as $order) {
                        $columnIndex = $order['column']; 
                        $columnDir = $order['dir'];
                        $columnName = $request->columns[$columnIndex]['data'];      
                    }
                } else {
                    $query->orderBy('created_date', 'desc'); 
                }

            if ($request->ajax()) {
                return DataTables::of($query)
                    ->editColumn('foto', function ($row) {
                        return $this->getFileDownloadButton($row->foto);
                    })
                    ->editColumn('passport', function ($row) {
                        return $this->getFileDownloadButton($row->passport);
                    })
                    ->editColumn('loa', function ($row) {
                        return $this->getFileDownloadButton($row->loa);
                    })
                    ->editColumn('student_id', function ($row) {
                        return $this->getFileDownloadButton($row->student_id);
                    })
                    ->rawColumns(['foto', 'passport', 'loa', 'student_id'])
                    ->make(true);
            }
        return view('stu_inbound.view_peserta');
    }

    private function getFileDownloadButton($fileUrl)
    {
        if (empty($fileUrl)) return '';
        
        $filePath = ltrim(str_replace('repo/', '', $fileUrl), '/');
        $segments = explode('/', $filePath);
        $fileName = array_pop($segments);
        $folder = implode('/', $segments);
        
        $url = !empty($folder)
            ? route('view.dokumen', ['folder' => $folder, 'fileName' => $fileName])
            : route('view.dokumen', ['folder' => $fileName]);
        
        return '<a href="' . $url . '" target="_blank" class="btn btn-primary">
                    <i class="fa fa-download"></i>
                </a>';
    }
}
    // ublic function index(Request $request)
    // {
    //     $user = Auth::user();

    //     if ($request->ajax()) {

    //         $query = DB::table('age_peserta_inbound as p')
    //         ->selectRaw("
    //             DISTINCT ON (p.metadata->>'fullname', fu.id, pr.id)
    //             p.metadata->>'fullname' AS nama, 
    //             fu.nama_ind AS unit_kerja_text,
    //             p.metadata->>'degree' AS jenjang,
    //             p.metadata->>'major' AS prodi_asal,
    //             p.metadata->>'faculty' AS fakultas_asal,
    //             CASE 
    //                 WHEN p.metadata->>'sex' = 'M' THEN 'Laki-laki'
    //                 WHEN p.metadata->>'sex' = 'F' THEN 'Perempuan'
    //                 ELSE 'Other'
    //             END AS jenis_kelamin,
    //             p.metadata->>'via' AS via,
    //             CASE
    //                 WHEN 
    //                     AGE(
    //                         CASE 
    //                             WHEN p.metadata->>'selected_program' = 'AMERTA' THEN am.end_date_program
    //                             WHEN p.metadata->>'selected_program' = 'Amerta Internship' THEN (p.metadata->>'end_date_prog')::DATE
    //                             WHEN p.metadata->>'selected_program' = 'Amerta Research' THEN (p.metadata->>'end_date_prog')::DATE
    //                             WHEN p.type = 'lingua' THEN li.end_date_program
    //                             ELSE NULL
    //                         END,
    //                         CASE 
    //                             WHEN p.metadata->>'selected_program' = 'AMERTA' THEN am.start_date_program
    //                             WHEN p.metadata->>'selected_program' = 'Amerta Internship' THEN (p.metadata->>'start_date_prog')::DATE
    //                             WHEN p.metadata->>'selected_program' = 'Amerta Research' THEN (p.metadata->>'start_date_prog')::DATE
    //                             WHEN p.type = 'lingua' THEN li.start_date_program
    //                             ELSE NULL
    //                         END
    //                     ) < INTERVAL '5 months' THEN 'PT'
    //                 ELSE 'FT'
    //             END AS tipe_text,
    //             fu.nama_ind AS tujuan_fakultas_unit,
    //             pr.name AS tujuan_prodi,
    //             pr.level as jenjang_prodi,
    //             u.name AS univ_asal_text,
    //             CASE 
    //                 WHEN p.metadata->>'nationality' ~ '^[0-9]+$' THEN c.name
    //                 ELSE p.metadata->>'nationality'
    //             END AS negara_asal_text,
    //             COALESCE(p.metadata->>'selected_program', '') || ' - ' || COALESCE(p.id_period::TEXT, '') AS program_text,
    //             p.metadata->>'progCategory' AS jenis_kegiatan_text,
    //             p.created_date AS created_date,
    //             p.metadata->>'photo' AS foto,
    //             p.metadata->>'passport' AS passport,
    //             p.metadata->>'student_id_url' AS student_id,
    //             p.metadata->>'loaPeserta' AS loa
    //         ")
    //         ->crossJoin(DB::raw("LATERAL (VALUES 
    //             (p.metadata->>'course1'),
    //             (p.metadata->>'course2'),
    //             (p.metadata->>'course3'),
    //             (p.metadata->>'course4'),
    //             (p.metadata->>'course5'),
    //             (p.metadata->>'course6')
    //         ) AS courses(course)"))
    //         ->leftJoin('age_amerta_matkul as a', 'courses.course', '=', 'a.code')
    //         ->leftJoin('m_prodi as pr', function ($join) {
    //             $join->on('pr.id', '=', DB::raw("
    //                 CASE 
    //                     WHEN (p.metadata->>'selected_program') = 'Amerta Internship' THEN (p.metadata->>'tProdiPeserta')::int
    //                     WHEN (p.metadata->>'selected_program') = 'Amerta Research' THEN (p.metadata->>'tProdiPeserta')::int
    //                     ELSE a.id_prodi
    //                 END
    //             "));
    //         })
        
    //         ->leftJoin('m_fakultas_unit as fu', function ($join) {
    //             $join->on('fu.id', '=', DB::raw("
    //                 CASE 
    //                     WHEN (p.metadata->>'selected_program') = 'Amerta Internship' THEN (p.metadata->>'tFakultasPeserta')::int
    //                     WHEN (p.metadata->>'selected_program') = 'Amerta Research' THEN (p.metadata->>'tFakultasPeserta')::int
    //                     ELSE pr.id_fakultas_unit
    //                 END
    //             "));
    //         })
    //         ->leftJoin('m_university as u', DB::raw("p.metadata->>'university'"), '=', 'u.name')
    //         ->leftJoin('m_country as c', function ($join) {
    //             $join->on(DB::raw("CASE 
    //                     WHEN p.metadata->>'nationality' ~ '^[0-9]+$' 
    //                     THEN (p.metadata->>'nationality')::INTEGER 
    //                     ELSE NULL 
    //                 END"), '=', 'c.id');
    //         })

    //         ->leftJoin('age_amerta as am', function ($join) {
    //             $join->on('p.id_period', '=', 'am.id')
    //                 ->where('p.type', '=', 'amerta');
    //         })
    //         ->leftJoin('age_lingua as li', function ($join) {
    //             $join->on('p.id_period', '=', 'li.id')
    //                 ->where('p.type', '=', 'lingua');
    //         })

    //         ->where('p.is_approve', true)
    //         ->where(function ($query) {
    //             $query->where(DB::raw("p.metadata->>'selected_program'"), '<>', 'amerta')
    //                 ->orWhere(function ($subquery) {
    //                     $subquery->whereNotNull('courses.course')
    //                             ->where('courses.course', '<>', '');
    //                 });
    //         })

    //         ->orderByRaw("p.metadata->>'fullname', fu.id, pr.id, p.id");
            
    //         $results = DB::table('v_t_student_inbound')
    //             ->select([
    //                 'nama',
    //                 'unit_kerja_text',
    //                 'jenjang',
    //                 'prodi_asal',
    //                 'fakultas_asal',
    //                 'jenis_kelamin',
    //                 'via',
    //                 'tipe_text',
    //                 'tujuan_fakultas_unit_text',
    //                 'tujuan_prodi_text',
    //                 'jenjang_prodi',
    //                 'univ_asal_text',
    //                 'negara_asal_text',
    //                 'program_text',
    //                 'jenis_kegiatan_text',
    //                 'created_date',
    //                 'foto',
    //                 'passport',
    //                 'student_id',
    //                 'loa'
    //             ])
    //             ->where('created_date', '<', '2023-08-10 10:16:37') // Filter hanya di v_t_student_inbound
    //             ->unionAll(
    //                 DB::table('m_stu_in_peserta')
    //                     ->select([
    //                         'nama',
    //                         'p.host_unit_text AS unit_kerja_text',
    //                         'jenjang',
    //                         'prodi_asal',
    //                         'fakultas_asal',
    //                         'jenis_kelamin',
    //                         'via',
    //                         'pt_ft as tipe_text',
    //                         'f.nama_ind as tujuan_fakultas_unit_text',
    //                         'm.name as tujuan_prodi_text',
    //                         'm.level as jenjang_prodi',
    //                         'u.name as univ_asal_text',
    //                         'n.name as negara_asal_text',
    //                         'p.name as program_text',
    //                         'p.category_text as jenis_kegiatan_text',
    //                         'p.created_time as created_date',
    //                         'photo_url as foto',
    //                         'passport_url as passport',
    //                         'student_id_url as student_id',
    //                         'loa_url as loa'
    //                     ])
    //                     ->where('is_approved', '=', 1) 
    //                     // ->where('created_time', '<', '2023-08-10 10:16:37') 
                        
    //                     ->Join('m_prodi as m', 'm.id', '=', 'm_stu_in_peserta.tujuan_prodi')
    //                     ->leftJoin('m_stu_in_programs as p', 'p.id', '=', 'm_stu_in_peserta.program_id')
    //                     ->leftJoin('m_university as u', 'u.id', '=', 'm_stu_in_peserta.univ')
    //                     ->leftJoin('m_country as n', 'n.id', '=', 'm_stu_in_peserta.kebangsaan')
    //                     ->leftJoin('m_fakultas_unit as f', 'f.id', '=', 'm_stu_in_peserta.tujuan_fakultas_unit')
    //                     ->leftJoin('m_stu_in_program_category as c', 'c.id', '=', 'm_stu_in_peserta.program_id')
    //                     )
    //                     ->unionAll($query);

