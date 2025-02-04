<?php

namespace App\Http\Controllers\inbound;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Mpdf\Mpdf;
use PhpOffice\PhpWord\TemplateProcessor;

use App\Models\MStuInProgram;
use App\Models\MProdi;
use App\Models\MStuInPeserta;


class StudentInboundController extends Controller
{
    public function approval_dana()
    {
        $data = DB::table('m_stu_in_peserta')
        ->select('reg_time', 'm_stu_in_peserta.id', 'm_stu_in_peserta.nama', 'm_fakultas_unit.nama_ind as fakultas','m_stu_in_programs.name as program', 'm_stu_in_programs.pt_ft as tipe', 'm_university.name as univ', 'm_country.name as negara_asal_univ', 'photo_url','passport_url','student_id_url', 'loa_url','cv_url','pengajuan_dana_status as dana_status')
        ->join('m_stu_in_programs', 'm_stu_in_peserta.program_id', '=', 'm_stu_in_programs.id')
        ->join('m_fakultas_unit', 'm_stu_in_peserta.tujuan_fakultas_unit', '=', 'm_fakultas_unit.id')
        ->join('m_university', 'm_stu_in_peserta.univ', '=', 'm_university.id')
        ->join('m_country', 'm_university.country', '=', 'm_country.id')
        ->whereNotNull('pengajuan_dana_status') 
        ->where('pengajuan_dana_status', '!=', 'EMPTY')  
        ->orderBy('reg_time', 'desc')
        ->get();


        return view('stu_inbound.approval_dana', compact('data'));
    }
    
    public function approval_pelaporan()
    {
        $data = DB::table('m_stu_in_peserta as t')
            ->select('t.*', 'p.via as via', 'p.start_date as start_date', 'p.end_date as end_date', 'p.host_unit_text as host_unit', 'u.name as univ_name', 'c.name as country_name', 'p.pt_ft as tipe', 'p.name as program')
            ->join('m_university as u', 'u.id', '=', 't.univ')
            ->join('m_country as c', 'c.id', '=', 't.kebangsaan')
            ->join('m_stu_in_programs as p', function ($join) {
                $join->on('p.id', '=', 't.program_id')
                    ->where('p.is_private_event', '=', 'Ya');
            })
            ->get();
    
        return view('stu_inbound.approval_pelaporan', compact('data'));
    }

    // APPROVAL
    public function pesertaApprove(Request $request, $id)
    {
        $peserta = MStuInPeserta::find($id);
        
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
            return redirect()->route('stuin_approval_pelaporan');
        } else {
            return redirect()->route('stuin_peserta.edit', [
                'prog_id' => $peserta->program->id, 
                'item_id' => $peserta->id
            ]);
        }

    }

    public function pesertaUnapprove(Request $request, $id)
    {
        $peserta = MStuInPeserta::find($id);
        
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
            return redirect()->route('stuin_approval_pelaporan');
        } else {
            return redirect()->route('stuin_peserta.edit', [
                'prog_id' => $peserta->program->id, 
                'item_id' => $peserta->id
            ]);
        }
    
    }

    public function pesertaReject(Request $request, $id)
    {
        $peserta = MStuInPeserta::find($id);
        
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
            return redirect()->route('stuin_approval_pelaporan');
        } else {
            return redirect()->route('stuin_peserta.edit', [
                'prog_id' => $peserta->program->id, 
                'item_id' => $peserta->id
            ]);
        }
    
    }

    public function approveDana($id)
    {
        $model = MStuInPeserta::find($id);

        if (!$model) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $model->pengajuan_dana_status = 'APPROVED';
        $model->save();

        return redirect()->back()->with('success', 'Bantuan Dana berhasil disetujui.');
    }

    public function unapproveDana($id)
    {
        $model = MStuInPeserta::find($id);

        if (!$model) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $model->pengajuan_dana_status = 'REQUESTED';
        $model->save();

        return redirect()->back()->with('success', 'Bantuan Dana berhasil disetujui.');
    }

    public function saveRevision(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'revision_note' => 'required|string|max:255'
        ]);

        // Find the record
        $data = MStuInPeserta::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        // Save the revision note
        $data->revision_note = $request->revision_note;
        $data->save();

        return response()->json(['message' => 'Revisi berhasil disimpan!']);
    }


    public function pdfPengajuanInbound($id, $tipe = null)
    {
        $peserta = DB::table('m_stu_in_peserta')
            ->join('m_prodi', 'm_stu_in_peserta.tujuan_prodi', '=', 'm_prodi.id') // Join untuk ambil level & name
            ->where('m_stu_in_peserta.program_id', $id)
            ->where('m_stu_in_peserta.pengajuan_dana_status', 'APPROVED')
            ->when($tipe, function ($query) use ($tipe) {
                return $query->where('m_stu_in_peserta.sumber_dana', $tipe);
            })
            ->select('m_stu_in_peserta.nama', 'm_prodi.level', 'm_prodi.name') // Pilih hanya yang dibutuhkan
            ->get();

        if ($peserta->isEmpty()) {
            return redirect()->route('program_stuin.edit', ['id' => $id])
                ->with('sweetalert', [
                    'type' => 'error',
                    'message' => 'Tidak ada peserta dengan sumber dana ' . ($tipe ?? 'RKAT') . '.'
                ]);
        }

        $program = DB::table('m_stu_in_programs')->where('id', $id)->first();

        $logo = public_path('assets/template/header_age.png');
        $ttd = public_path('assets/template/ttd_pak_iman.png');


        $ptft = ($program->pt_ft == 'PT') ? 'Part Time' : 'Full Time';
        $pengajuan_dana = $tipe ?? 'RKAT';

        // **Buat Header PDF**
        $header = '<img src="' . $logo . '" style="width: 100%;">';

        // **Buat Isi PDF**
        $html = '<style>
                    body { font-size: 10pt; font-family: Arial, sans-serif; }
                    .content { margin-top: 100px; }
                    .title { font-size: 12pt; font-weight: bold; text-align: center; }
                    .text { text-align: justify; font-size: 10pt; }
                    ol { padding-left: 20px; }
                </style>';

        $html .= '<div class="content"> <br>';
        $html .= '<p class="text">Permohonan Persetujuan Bantuan Pendanaan Student Inbound mahasiswa ' . $program->host_unit_text . 
        ' yang akan mengikuti ' . $program->name . ' pada ' . ($program->start_date) . ' sampai ' . ($program->end_date) . ' atas nama:</p>';

        $html .= '<ol>';
        foreach ($peserta as $p) {
            $html .= '<li>' . $p->nama . ' - ' . $p->level . ' ' . $p->name . '</li>';
        }
        $html .= '</ol>';

        $html .= '<p class="text">Telah diverifikasi dan disetujui oleh Airlangga Global Engagement untuk diberikan bantuan pendanaan student 
        inbound ' . $ptft . ' melalui ' . $pengajuan_dana . ' ' . $program->host_unit_text . '. Setelah menyelesaikan program, mahasiswa 
        diwajibkan membuat laporan dan sertifikat kegiatan untuk dikumpulkan ke fakultas.</p>';

        $html .= '<img src="' . $ttd . '" style="width: 320px; margin-left: 380px;">';
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
        $mpdf->SetHTMLHeader($header);
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
    
}
