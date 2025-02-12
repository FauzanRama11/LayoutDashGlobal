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

