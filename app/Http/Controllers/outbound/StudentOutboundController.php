<?php

namespace App\Http\Controllers\outbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MStuOutPeserta;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpWord\TemplateProcessor;

class StudentOutboundController extends Controller
{
    public function approval_dana(Request $request)
    {
        if($request->ajax()){
            $data = DB::table('m_stu_out_peserta')
            ->select(
                'reg_time', 'm_stu_out_peserta.id', 'm_stu_out_peserta.nama',
                'host_unit_text', 'm_stu_out_programs.name as program',
                'm_stu_out_programs.pt_ft as tipe', 'm_university.name as univ',
                'm_country.name as negara_asal_univ', 'photo_url', 'passport_url',
                'student_id_url', 'loa_url', 'cv_url', 'pengajuan_dana_status', 
                'm_stu_out_peserta.sumber_dana'
            )
            ->leftJoin('m_university', 'm_stu_out_peserta.univ', '=', 'm_university.id')
            ->leftJoin('m_country', 'm_university.country', '=', 'm_country.id')
            ->leftJoin('m_stu_out_programs', 'm_stu_out_peserta.program_id', '=', 'm_stu_out_programs.id')
            ->whereNotNull('pengajuan_dana_status')  
            ->where('pengajuan_dana_status', '!=', '')
            ->where('pengajuan_dana_status', '!=', 'EMPTY');
        

                if ($request->has('order')) {
                    foreach ($request->order as $order) {
                        $columnIndex = $order['column']; 
                        $columnDir = $order['dir'];
                        $columnName = $request->columns[$columnIndex]['data'];      
                    }
                } else {
                    $data->orderBy('reg_time', 'desc'); 
                }

            return DataTables::of($data)
            ->editColumn('photo_url', function ($row) {
                return $this->getFileDownloadButton($row->photo_url);
            })
            ->editColumn('passport_url', function ($row) {
                return $this->getFileDownloadButton($row->passport_url);
            })
            ->editColumn('student_id_url', function ($row) {
                return $this->getFileDownloadButton($row->student_id_url);
            })
            ->editColumn('loa_url', function ($row) {
                return $this->getFileDownloadButton($row->loa_url);
            })
            ->editColumn('cv_url', function ($row) {
                return $this->getFileDownloadButton($row->cv_url);
            })
            ->editColumn('sumber_dana', function ($item) {
                if ($item->sumber_dana === 'RKAT') {
                    return '<button class="btn btn-success btn-sm" disabled>RKAT</button>';
                } elseif ($item->sumber_dana === 'DPAT' || $item->sumber_dana === 'DAPT') {
                    return '<button class="btn btn-warning btn-sm" disabled>DAPT</button>';
                }
            })
            ->editColumn('pengajuan_dana_status', function ($item) {
                if ($item->pengajuan_dana_status === 'APPROVED') {
                    return '<button class="btn btn-success btn-sm" disabled>Approved</button>';
                } elseif ($item->pengajuan_dana_status === 'REQUESTED') {
                    return '<button class="btn btn-warning btn-sm" disabled>Requested</button>';
                }
            })
            ->addColumn('action', function ($item) {
                if ($item->pengajuan_dana_status === 'APPROVED') {
                    return '<form action="' . route('stuout.unapprove.dana', $item->id) . '" method="POST">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-warning edit-button"><i class="fa fa-times-circle"></i>  Unapprove</button>
                            </form>';
                } else {
                    return '<form action="' . route('stuout.approve.dana', $item->id) . '" method="POST">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-primary edit-button"><i class="fa fa-check-circle"></i>  Approve</button>
                            </form>';
                }
            })
            // ->filterColumn('sumber_dana', function ($query, $keyword) {
            //     $sql = "(CASE 
            //                 WHEN sumber_dana = 'RKAT' THEN 'RKAT'
            //                 WHEN sumber_dana IN ('DPAT', 'DAPT') THEN 'DAPT'
            //             END) LIKE ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })       
            ->rawColumns(['photo_url', 'passport_url', 'student_id_url', 'loa_url', 'cv_url', 'sumber_dana', 'pengajuan_dana_status', 'action'])
            ->make(true);
        }

        return view('stu_outbound.approval_dana');
    }

    public function approveDana($id)
    {
        $model = MStuOutPeserta::find($id);

        if (!$model) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $model->pengajuan_dana_status = 'APPROVED';
        $model->save();

        return redirect()->back()->with('success', 'Bantuan Dana berhasil disetujui.');
    }

    public function unapproveDana($id)
    {
        $model = MStuOutPeserta::find($id);

        if (!$model) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $model->pengajuan_dana_status = 'REQUESTED';
        $model->save();

        return redirect()->back()->with('success', 'Bantuan Dana berhasil disetujui.');
    }


    
    public function approval_pelaporan(Request $request)
    {
        if($request->ajax()){
            $data = DB::table('m_stu_out_peserta as t')
                ->select('t.*', 'p.via as via', 'p.start_date as start_date', 'p.end_date as end_date', 'p.host_unit_text as host_unit', 'u.name as univ_name', 'c.name as country_name', 'p.pt_ft as tipe', 'p.name as program')
                ->leftJoin('m_university as u', 'u.id', '=', 't.univ')
                ->leftJoin('m_country as c', 'c.id', '=', 't.kebangsaan')
                ->leftJoin('m_stu_out_programs as p', 'p.id', '=', 't.program_id')
                ->where('is_private_event', '=', 'Ya');

                if ($request->has('order')) {
                    foreach ($request->order as $order) {
                        $columnIndex = $order['column']; 
                        $columnDir = $order['dir'];
                        $columnName = $request->columns[$columnIndex]['data'];      
                    }
                } else {
                    $data->orderBy('reg_time', 'desc'); 
                }
            
            return DataTables::of($data)
            ->editColumn('photo_url', function ($row) {
                return $this->getFileDownloadButton($row->photo_url);
            })
            ->editColumn('passport_url', function ($row) {
                return $this->getFileDownloadButton($row->passport_url);
            })
            ->editColumn('student_id_url', function ($row) {
                return $this->getFileDownloadButton($row->student_id_url);
            })
            ->editColumn('loa_url', function ($row) {
                return $this->getFileDownloadButton($row->loa_url);
            })
            ->editColumn('cv_url', function ($row) {
                return $this->getFileDownloadButton($row->cv_url);
            })
            ->editColumn('is_approved', function ($item) {
                if ($item->is_approved === 1) {
                    return '<button class="btn btn-success btn-sm" disabled>Approved</button>';
                } elseif ($item->is_approved === -1) {
                    return '<button class="btn btn-danger btn-sm" disabled>Rejected</button>' . 
                           (!empty($item->revision_note) ? '<button type="button" class="btn btn-warning btn-sm viewRevisionButton" data-revision="' . htmlspecialchars($item->revision_note) . '"><i class="fa fa-eye"></i></button>' : '');
                } else {
                    return '<button class="btn btn-info btn-sm" disabled>Processed</button>' . 
                           (!empty($item->revision_note) ? '<button type="button" class="btn btn-warning btn-sm viewRevisionButton" data-revision="' . htmlspecialchars($item->revision_note) . '"><i class="fa fa-eye"></i></button>' : '');
                }
            })
            ->addColumn('action', function ($item) {
                if ($item->is_approved == 1) {
                    return '<form action="' . route('stuout_peserta.unapprove', $item->id) . '" method="POST">
                                ' . csrf_field() . '
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="btn btn-warning edit-button"><i class="fa fa-times-circle"></i>  Unapprove</button>
                            </form>';
                } else {
                    return '<form action="' . route('stuout_peserta.approve', $item->id) . '" method="POST">
                                ' . csrf_field() . '
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="btn btn-success edit-button"><i class="fa fa-check-circle"></i>  Approve</button>
                            </form>';
                }
            })
            ->addColumn('revise', function ($item) {
                return '<button type="button" class="btn btn-warning reviseButton" data-id="' . $item->id . '">
                            <i class="fa fa-edit"></i> Revise
                        </button>';
            })
            ->addColumn('reject', function ($item) {
                return '<form action="' . route('stuout.reject', $item->id) . '" method="POST">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-danger edit-button">Reject</button>
                        </form>';
            })
            ->filterColumn('is_approved', function ($query, $keyword) {
                $sql = "(CASE 
                            WHEN is_approved = 1 THEN 'Approved'
                            WHEN is_approved = -1 THEN 'Rejected'
                            WHEN is_approved = 0 THEN 'Processed'
                            END) LIKE ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->rawColumns(['photo_url', 'passport_url', 'student_id_url', 'loa_url', 'cv_url','is_approved', 'action', 'revise', 'reject']) // Enable HTML rendering
            ->make(true);
        }
        
        return view('stu_outbound.approval_pelaporan');
    }

    public function action_approve(Request $request, $id)
    {
        $peserta = MStuOutPeserta::find($id);

        $peserta->is_approved = 1;
        $peserta->approved_time = now();
        $peserta->approved_by = Auth::user()->id;
        $peserta->save();
    
        return redirect()->route('stuout_approval_pelaporan');
    }

    public function action_unapprove(Request $request, $id)
    {
        $peserta = MStuOutPeserta::find($id);

        $peserta->is_approved = 0;
        $peserta->approved_time = null;
        $peserta->approved_by = null;
        $peserta->save();
    
        return redirect()->route('stuout_approval_pelaporan');
    }

    public function action_revise(Request $request, $id){

        $validated = $request->validate([
            'revision_note' => 'required|string|max:255'
        ]);

        $peserta = MStuOutPeserta::find($id);
        $peserta->is_approved = 0;
        // $peserta->approval_status = "NEED REVISE";      
        $peserta->approved_time = null;
        $peserta->approved_by = null;

        $peserta->revision_note = $request->input('revision_note');
        $peserta->save();
      
        
        return response()->json(['message' => 'Revisi berhasil disimpan!']);

    }

    public function actionPesertaApprove(Request $request, $id)
    {
        $peserta = MStuOutPeserta::find($id);
        
        if (is_null($peserta->approval_code)) {
            $peserta->approval_code = $this->generateRandomString(10);
        }

        $peserta->update([
            'is_approved' => 1,
            'approved_time' => now(),
            'approved_by' => Auth::id(),
            'approval_code' => $peserta->approval_code, 
        ]);
    
        if ($peserta->program->is_private_event === 'Ya') {
            return redirect()->route('stuout_approval_pelaporan');
        } else {
            return redirect()->route('stuout_peserta.edit', [
                'prog_id' => $peserta->program->id, 
                'item_id' => $peserta->id
            ]);
        }
    
    }

    public function actionPesertaReject(Request $request, $id)
    {
        $peserta = MStuOutPeserta::find($id);
        
        if (is_null($peserta->approval_code)) {
            $peserta->approval_code = $this->generateRandomString(10);
        }

        $peserta->update([
            'is_approved' => -1,
            'approved_time' => now(),
            'approved_by' => Auth::id(),
            'approval_code' => $peserta->approval_code, 
        ]);

        if ($peserta->program->is_private_event === 'Ya') {
            return redirect()->route('stuout_approval_pelaporan');
        } else {
            return redirect()->route('stuout_peserta.edit', [
                'prog_id' => $peserta->program->id, 
                'item_id' => $peserta->id
            ]);
        }
    }

    public function actionPesertaUnapprove(Request $request, $id)
    {
        $peserta = MStuOutPeserta::find($id);
        
        if (is_null($peserta->approval_code)) {
            $peserta->approval_code = $this->generateRandomString(10);
        }

        $peserta->update([
            'is_approved' => 0,
            'approved_time' => now(),
            'approved_by' => Auth::id(),
            'approval_code' => $peserta->approval_code, 
        ]);

        if ($peserta->program->is_private_event === 'Ya') {
            return redirect()->route('stuout_approval_pelaporan');
        } else {
            return redirect()->route('stuout_peserta.edit', [
                'prog_id' => $peserta->program->id, 
                'item_id' => $peserta->id
            ]);
        }
    
    }

    public function pengajuan_setneg()
    {
        $data = DB::table('m_stu_out_peserta_setneg as t')
        ->select('t.*','s.nama as nama', 'p.name as program', 'p.id as pid', 'c.name as country', 'p.start_date as start_date', 'f.nama_ind as fakultas',  DB::raw("CONCAT(pr.level, ' ', pr.name) as prodi"))
        ->join('m_stu_out_peserta as s', 's.id', '=', 't.stu_out_id')
        ->join('m_stu_out_programs as p', 'p.id', '=', 's.program_id')
        ->join('m_country as c', 'c.id', '=', 's.kebangsaan')
        ->leftJoin('m_fakultas_unit as f', 'f.id', '=', 's.tujuan_fakultas_unit')
        ->leftJoin('m_prodi as pr', 'pr.id', '=', 's.tujuan_prodi')
        ->orderBy('p.id', 'desc')
        ->orderBy('t.id', 'desc')
        ->get();
    
        return view('stu_outbound.pengajuan_setneg', compact('data'));
    }

    public function pdfPengajuanOutbound($id, $tipe = null)
    {
        $peserta = DB::table('m_stu_out_peserta')
            ->join('m_prodi', 'm_stu_out_peserta.tujuan_prodi', '=', 'm_prodi.id') // Join untuk ambil level & name
            ->where('m_stu_out_peserta.program_id', $id)
            ->where('m_stu_out_peserta.pengajuan_dana_status', 'APPROVED')
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('m_stu_out_peserta.sumber_dana', $tipe);
            })
            ->select('m_stu_out_peserta.nama', 'm_prodi.level', 'm_prodi.name') // Pilih hanya yang dibutuhkan
            ->get();

        if ($peserta->isEmpty()) {
            return redirect()->route('program_stuout.edit', ['id' => $id])
                ->with('sweetalert', [
                    'type' => 'error',
                    'message' => 'Tidak ada peserta dengan sumber dana ' . ($tipe ?? 'RKAT') . '.'
                ]);
        }

        $program = DB::table('m_stu_out_programs')->where('id', $id)->first();

        // $logo = public_path('assets/template/header_age.png');
        // $ttd = public_path('assets/template/ttd_pak_iman.png');


        $ptft = ($program->pt_ft == 'PT') ? 'Part Time' : 'Full Time';
        $pengajuan_dana = $tipe ?? 'RKAT';

        // **Buat Header PDF**
        // $header = '<img src="' . $logo . '" style="width: 100%;">';

        // **Buat Isi PDF**
        $html = '<style>
                    body { font-size: 10pt; font-family: Arial, sans-serif; }
                    .content { margin-top: 100px; }
                    .title { font-size: 12pt; font-weight: bold; text-align: center; }
                    .text { text-align: justify; font-size: 10pt; }
                    ol { padding-left: 20px; }
                </style>';

        $html .= '<div class="content"> <br>';
        $html .= '<p class="text">Permohonan Persetujuan Bantuan Pendanaan Student Outbound mahasiswa ' . $program->host_unit_text . 
        ' yang akan mengikuti ' . $program->name . ' pada ' . ($program->start_date) . ' sampai ' . ($program->end_date) . ' atas nama:</p>';

        $html .= '<ol>';
        foreach ($peserta as $p) {
            $html .= '<li>' . $p->nama . ' - ' . $p->level . ' ' . $p->name . '</li>';
        }
        $html .= '</ol>';

        $html .= '<p class="text">Telah diverifikasi dan disetujui oleh Airlangga Global Engagement untuk diberikan bantuan pendanaan student 
        outbound ' . $ptft . ' melalui ' . $pengajuan_dana . ' ' . $program->host_unit_text . '. Setelah menyelesaikan program, mahasiswa 
        diwajibkan membuat laporan dan sertifikat kegiatan untuk dikumpulkan ke fakultas.</p>';

        // $html .= '<img src="' . $ttd . '" style="width: 320px; margin-left: 380px;">';
        $html .= '</div>';

        // **Buat PDF Menggunakan mPDF**
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 40,
            'margin_bottom' => 10
        ]);

        $mpdf->showImageErrors = true;
        $mpdf->autoLangToFont = false;
        // $mpdf->SetHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        // **Tampilkan PDF di Browser Tanpa Download**
        return response()->stream(function () use ($mpdf) {
            $mpdf->Output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Persetujuan_Dana.pdf"',
        ]);
    }

    private function generateRandomString($length = 10)
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
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
