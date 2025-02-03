<?php

namespace App\Http\Controllers\outbound;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewStuOut;

class VTStudenOutboundController extends Controller
{

    public function index(Request $request){
        $user = Auth::user();

            $query = ViewStuOut::query();

            if ($user->hasRole('fakultas')) {
                $fa = $user->name;
                                
                $fakultas = DB::table('m_fakultas_unit')
                    ->where('nama_ind', '=', $fa)
                    ->value('nama_eng');
            
                $query->where('fakultas_text', '=', $fa)
                    ->orWhere('fakultas_text', '=',$fakultas);
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
                    ->addIndexColumn() 
                    ->editColumn('photo_url', function ($row) {
                        return $this->getFileDownloadButton($row->photo_url);
                    })
                    ->editColumn('passport_url', function ($row) {
                        return $this->getFileDownloadButton($row->passport_url);
                    })
                    ->editColumn('loa', function ($row) {
                        return $this->getFileDownloadButton($row->loa);
                    })
                    ->editColumn('cv_url', function ($row) {
                        return $this->getFileDownloadButton($row->cv_url);
                    })
                    ->rawColumns(['photo_url', 'passport_url', 'loa', 'cv_url'])
                    ->make(true);
        }  
        return view('stu_outbound.view_peserta');
    }

    // public function index(Request $request)
    // {
    //     $user = Auth::user();

    //     if ($request->ajax()) {
    //         $results = DB::table('v_t_student_outbound')
    //             ->select([
    //                 'nim',
    //                 'nama_mhs',
    //                 'fakultas_text',
    //                 'prodi_text',
    //                 'nama_program',
    //                 'jk',
    //                 'via',
    //                 'prodi_tujuan_text',
    //                 'fakultas_tujuan_text',
    //                 'univ_tujuan_text',
    //                 'negara_tujuan_text',
    //                 'jenjang',
    //                 'tgl_mulai',
    //                 'tgl_selesai',
    //                 'tipe_text',
    //                 'created_date',
    //                 'photo_url',
    //                 'passport_url',
    //                 'cv_url',
    //                 'loa'
    //             ])
    //             ->where('created_date', '<', '2023-08-10 11:19:23') // Filter hanya di v_t_student_outbound
    //             ->unionAll(
    //                 DB::table('m_stu_out_peserta')
    //                     ->select([
    //                         'nim',
    //                         'nama as nama_mhs',
    //                         'f.nama_eng as fakultas_text',
    //                         'p.name as prodi_text',
    //                         'prog.name as nama_program',
    //                         'jenis_kelamin as jk',
    //                         'via',
    //                         'prodi_asal as prodi_tujuan_text',
    //                         'fakultas_Asal as fakultas_tujuan_text',
    //                         'u.name as univ_tujuan_text',
    //                         'prog.negara_tujuan as negara_tujuan_text',
    //                         'jenjang',
    //                         'start_date as tgl_mulai',
    //                         'end_date as tgl_selesai',
    //                         'pt_ft as tipe_text',
    //                         'reg_time as created_date',
    //                         'photo_url',
    //                         'passport_url',
    //                         'cv_url',
    //                         'loa_url as loa'
    //                     ])
    //                     ->where('is_approved', '=', 1) // Tidak ada filter created_date di sini
    //                     // ->where('created_time', '<', '2023-08-10 10:16:37') 
    //                     ->leftJoin('m_fakultas_unit as f', 'f.id', '=', 'm_stu_out_peserta.tujuan_fakultas_unit')
    //                     ->leftJoin('m_prodi as p', 'p.id', '=', 'm_stu_out_peserta.tujuan_prodi')
    //                     ->leftJoin('m_university as u', 'u.id', '=', 'm_stu_out_peserta.univ')
    //                     ->leftJoin('m_stu_out_programs as prog', 'prog.id', '=', 'm_stu_out_peserta.program_id')
    //                     );

    //             if ($user->hasRole('fakultas')) {
    //                 $fa = $user->name;
                    
    //                 $fakultas = DB::table('m_fakultas_unit')
    //                 ->where('nama_ind', '=', $fa)
    //                 ->value('nama_eng');

    //                 $results->where('fakultas_text', '=', $fa)
    //                 ->orWhere('fakultas_text', '=',$fakultas);
    //             };

    //             if ($request->has('order')) {
    //                 foreach ($request->order as $order) {
    //                     $columnIndex = $order['column']; 
    //                     $columnDir = $order['dir'];
    //                     $columnName = $request->columns[$columnIndex]['data'];      
    //                 }
    //             } else {
    //                 $results->orderBy('created_date', 'desc'); 
    //             }

    //         return DataTables::of($results)
    //             ->editColumn('photo_url', function ($row) {
    //                 return $this->getFileDownloadButton($row->photo_url);
    //             })
    //             ->editColumn('passport_url', function ($row) {
    //                 return $this->getFileDownloadButton($row->passport_url);
    //             })
    //             ->editColumn('loa', function ($row) {
    //                 return $this->getFileDownloadButton($row->loa);
    //             })
    //             ->editColumn('cv_url', function ($row) {
    //                 return $this->getFileDownloadButton($row->cv_url);
    //             })
    //             ->rawColumns(['photo_url', 'passport_url', 'loa', 'cv_url'])
    //             ->make(true);
    //     }

         

    //     return view('stu_outbound.view_peserta');
    // }
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
