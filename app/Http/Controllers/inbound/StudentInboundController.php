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

    public function actionPesertaApprove(Request $request, $id)
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
    
        return redirect()->route('stuin_approval_pelaporan');
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
            return redirect()->back()->with('error', 'Tidak ada peserta dengan sumber dana ' . ($tipe ?? 'RKAT') . '.');
        }

        $program = DB::table('m_stu_in_programs')->where('id', $id)->first();

        $logo = asset("app/static/header_age.jpg");
        $ttd = asset("app/static/ttd_pak_iman.jpg");

        $ptft = ($program->pt_ft == 'PT') ? 'Part Time' : 'Full Time';
        $pengajuan_dana = $tipe ?? 'RKAT';

        // **Buat Header PDF**
        $header = '<img src="' . $logo . '">';

        // **Buat Isi PDF**
        $html = '<style>
                    body { font-size: 10pt; font-family: Arial, sans-serif; }
                    .content { margin-top: 100px; }
                    .title { font-size: 12pt; font-weight: bold; text-align: center; }
                    .text { text-align: justify; font-size: 10pt; }
                    ol { padding-left: 20px; }
                </style>';

        $html .= '<div class="content">';
        $html .= '<p class="text">Permohonan Persetujuan Bantuan Pendanaan Student Inbound mahasiswa <b>' . $program->host_unit_text . '</b> yang akan mengikuti <b>' . $program->name . '</b> pada <b>' . ($program->start_date) . '</b> sampai <b>' . ($program->end_date) . '</b> atas nama:</p>';

        $html .= '<ol>';
        foreach ($peserta as $p) {
            $html .= '<li>' . $p->nama . ' - ' . $p->level . ' ' . $p->name . '</li>';
        }
        $html .= '</ol>';

        $html .= '<p class="text">Telah diverifikasi dan disetujui oleh Airlangga Global Engagement untuk diberikan bantuan pendanaan student inbound <b>' . $ptft . '</b> melalui <b>' . $pengajuan_dana . '</b> di ' . $program->host_unit_text . '. Setelah menyelesaikan program, mahasiswa diwajibkan membuat laporan dan sertifikat kegiatan untuk dikumpulkan ke fakultas.</p>';

        $html .= '<img src="' . $ttd . '" style="width: 380px; margin-left: 300px;">';
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

    // public function pdfPengajuanInbound($id, $tipe)
    // {
    //     // set_time_limit(300); // Mencegah timeout jika proses lama

    //     $program = MStuInProgram::with(['mStuInPesertas' => function ($query) use ($tipe) {
    //         $query->where('pengajuan_dana_status', 'APPROVED')
    //             ->where('sumber_dana', $tipe);
    //     }])->find($id);

    //     if (!$program || $program->mStuInPesertas->isEmpty()) {
    //         return redirect()->back()->with('error', 'Tidak ada peserta dengan sumber dana ' . $tipe . '.');
    //     }

    //     // **Pastikan Template Word Ada**
    //     $templatePath = public_path('assets/template/TemplatePendanaanInbound.docx');
    //     if (!file_exists($templatePath)) {
    //         return redirect()->back()->with('error', 'Template tidak ditemukan.');
    //     }

    //     // **Load Template Word**
    //     $templateProcessor = new TemplateProcessor($templatePath);

    //     // **Ganti placeholder dalam template**
    //     $templateProcessor->setValue('Fakultas', $program->host_unit_text);
    //     $templateProcessor->setValue('Nama Program', $program->name);
    //     $templateProcessor->setValue('Tgl. Mulai', $program->start_date);
    //     $templateProcessor->setValue('Tgl. Selesai', $program->end_date);
    //     $templateProcessor->setValue('Part Time/Full Time', ($program->pt_ft == 'PT') ? 'Part Time' : 'Full Time');
    //     $templateProcessor->setValue('RKAT/DAPT', $tipe);

    //     // **Ganti daftar mahasiswa sesuai sumber dana**
    //     $mahasiswaList = implode("\n", $program->mStuInPesertas->map(function ($p) {
    //         return "{$p->nama} – {$p->tujuan_prodi}";
    //     })->toArray());

    //     $templateProcessor->setValue('Mahasiswa List', $mahasiswaList);

    //     // **Simpan hasil perubahan ke file sementara**
    //     $tempDocPath = tempnam(sys_get_temp_dir(), 'word') . '.docx';
    //     $templateProcessor->saveAs($tempDocPath);

    //     // **Konversi Word ke PDF (Tanpa Menyimpan ke Storage)**
    //     $mpdf = new Mpdf();
    //     $mpdf->WriteHTML(nl2br(strip_tags(file_get_contents($tempDocPath)))); // Pastikan teks diproses dengan baik

    //     // **Langsung Stream PDF ke Browser**
    //     return response()->stream(function () use ($mpdf) {
    //         $mpdf->Output();
    //     }, 200, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'inline; filename="Persetujuan_Dana.pdf"',
    //     ]);
    // }

    private function generateRandomString($length = 10)
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }
    
}
