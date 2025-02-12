<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

use Mpdf\Mpdf;
use PhpOffice\PhpWord\TemplateProcessor;

use App\Models\MStuInProgram;
use App\Models\MProdi;
use App\Models\MStuInPeserta;
use App\Models\User;
use Illuminate\Http\Request;

class StudentInboundLoaController extends Controller
{

    public function pesertaApprove(Request $request, $id)
    {
        try {
            $peserta = MStuInPeserta::findOrFail($id);
    
            if (is_null($peserta->approval_code)) {
                $peserta->approval_code = $this->generateRandomString(10);
            }
    
            DB::beginTransaction();
    
            $peserta->update([
                'is_approved' => 1,
                'is_loa' => 2,
                'approved_time' => now(),
                'approved_by' => Auth::id(),
                'approval_code' => $peserta->approval_code, 
            ]);
    
            $pdfPath = $this->generateLoaPDF($peserta->id);
    
            if (!$pdfPath) {
                throw new \Exception("Gagal menyimpan LOA PDF.");
            }
    
            $peserta->update(['loa_url' => $pdfPath]);

            // AKTIVASI AKUN
            if (User::where('email', $peserta->email)->exists()) {
                
                User::where('email', $peserta->email)->update([
                    'is_active' => 'True',

                ]);
            }

    
            DB::commit();
    
            return response()->json(['status' => 'success', 'message' => 'Participant approved successfully.']);
    
        } catch (\Exception $e) {
            // **Rollback perubahan jika terjadi error**
            DB::rollBack();
    
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyetujui peserta: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function generateLoaPDF($id)
    {
        // **Ambil data peserta**
        $peserta = DB::table('m_stu_in_peserta')
            ->leftJoin('m_prodi', 'm_stu_in_peserta.tujuan_prodi', '=', 'm_prodi.id') 
            ->leftJoin('m_country', 'm_stu_in_peserta.kebangsaan', '=', 'm_country.id') 
            ->leftJoin('m_university', 'm_stu_in_peserta.univ', '=', 'm_university.id') 
            ->where('m_stu_in_peserta.id', $id)
            ->select(
                'm_stu_in_peserta.nama', 
                'm_country.name as kebangsaan', 
                'm_university.name as univ', 
                'm_stu_in_peserta.prodi_asal', 
                'm_prodi.level', 
                'm_prodi.name', 
                'm_stu_in_peserta.program_id'
            )->first();
    
        if (!$peserta) {
            return null;
        }
    
        $program = DB::table('m_stu_in_programs')
            ->leftJoin('m_fakultas_unit', 'm_stu_in_programs.host_unit', '=', 'm_fakultas_unit.id')
            ->where('m_stu_in_programs.id', $peserta->program_id)
            ->first();
    
        $ttd = public_path('assets/template/ttd_pak_iman.png');
        $logo = public_path('assets/images/LogoUnair.png');

        $programinfo = $this->getSemesterInfo($program->start_date);
        $ptft = ($program->pt_ft == 'PT') ? 'Part Time' : 'Full Time';
        
        $html = '<style>
                    body { font-size: 10pt; font-family: "Times New Roman", Times, serif; }
                    .content { margin-top: 100px; }
                    .title { font-size: 12pt; font-weight: bold; text-align: center; margin-top: 0px}
                    .text { text-align: justify; font-size: 10pt;  z-index: 1;}
                    ol { padding-left: 20px; }
                    li { margin-left: 20px; }
                    h5 ( margin: 0px; padding: 0px;)
                    .header-table {width: 100%; border-bottom: 2px solid black; border-collapse: collapse;}
                    .header-table td { text-align: center; }
                    .header-logo { width: 80px; height: auto; }
                    .header-text { text-align: center; font-size: 12pt;font-weight: bold; }
                    .subheader { text-align: center; font-size: 10pt;font-weight: bold; }
                    .sub-header { text-align: center; font-size: 8pt; font-style: italic; }
                    .contact { text-align: center; font-size: 8pt; }
                </style>';

        $header = '<table class="header-table">
                        <tr>
                            <!-- Logo -->
                            <td style="width: 20%; text-align: center;">
                                <img src="'.$logo.'" class="header-logo">
                            </td>

                            <!-- Teks Header -->
                            <td style="width: 80%; text-align: center;">
                                <p class="header-text">UNIVERSITAS AIRLANGGA</p>
                                <p class="subheader">AIRLANGGA GLOBAL ENGAGEMENT</p>
                                <p class="sub-header">Jalan Airlangga 4-6 ASEEC (<i>Airlangga Sharia & Entrepreneurship Education Center</i>) Tower Lantai 12<br>
                                Surabaya, Kode pos <a href="#">60286</a>, Telp. (031) 5966864 Fax (031) 5955582</p>
                            </td>
                        </tr>
                    </table>
                    <hr style="border: 2x solid black; width: 100%; margin-top: 10px;">';
                    

        $html .= '<div class="content"> <br>';

        $html .= '<h2 class="title"><u>LETTER OF ACCEPTANCE</u></h2>';

        $html .= '<p class="text">
                    Congratulations!  <br><br>
                    
                    You have been admitted to Universitas Airlangga’s (UNAIR) student inbound program ' 
                    . $ptft . ', '  . ($programinfo['semester'] ?? 'Unknown') . ' semester ' 
                    . ($programinfo['year'] ?? 'Unknown') . ', ' 
                    . $this->formatDateRange($program->start_date) . '.<br><br>
                    
                    The particulars of this offer are as follows: 
                  </p>';

        $html .= '<ol>';
        $html .= '<li>' . $peserta->nama . ' - ' . $peserta->univ . '</li>';
        $html .= '</ol>';

        $html .= '<h4>UNIVERSITY REGULATION & ASSESSMENT</h4>
                    <p class="text">
                    Attendance at lectures is compulsory. If for some reason(s) students are unable to attend the classes, please 
                    ensure the lecturer is informed of the situation before the day. All participants have to sit for all the courses 
                    and the site visits schedule.  
                    </p>';

        $html .= '<h4>EARLY RETURN POLICY</h4>
                    <p class="text">
                        Students who return early before the end date of the program are obliged to notify the coordinator of the program in 
                        Airlangga Global Engagement. Students who return early before the end date of the program will not receive a hard copy 
                        of the certificate. Universitas Airlangga will not be responsible for anything that happens to students once they 
                        leave early from the university.
                    </p>';

        $html .= '<h4>FURTHER INQUIRY</h4>
                  <h5>Airlangga Global Engagement</h5>
                    <p class="text">
                    Office hours: Monday to Friday / 08.00 AM to 04.00 PM (UTC+7)<br>
                    Email: <a href="mailto:' . $program->corresponding  . '">' . $program->corresponding  . '</a><br><br>
                    We look forward to welcoming you to Universitas Airlangga, Indonesia. <br><br>
                    Surabaya, ' .  $this->formatDateRange($program->start_date)  . '<br>
                    Yours Sincerely,
                    </p>';

        $html .= '<img src="' . $ttd . '" style="width: 340px;  position: absolute; margin-top: -44px;  margin-left: -28px; z-index: -1;">';

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
    
        $storagePath = '/loa';
        if (!Storage::disk('inside')->exists($storagePath)) {
            Storage::disk('inside')->makeDirectory($storagePath);
        }
    
        $fileName = uniqid() . '_LOA_' . str_replace(' ', '_', $peserta->nama) . '.pdf';
        $filePath = $storagePath . '/' . $fileName;
    
        Storage::disk('inside')->put($filePath, $mpdf->Output('', 'S'));
    
        return 'repo/loa/' . $fileName; 
    }
    
    public function pdfloa($id)
    {
        $peserta = DB::table('m_stu_in_peserta')
            ->leftJoin('m_prodi', 'm_stu_in_peserta.tujuan_prodi', '=', 'm_prodi.id') 
            ->leftJoin('m_country', 'm_stu_in_peserta.kebangsaan', '=', 'm_country.id') 
            ->leftJoin('m_university', 'm_stu_in_peserta.univ', '=', 'm_university.id') 
            ->where('m_stu_in_peserta.program_id', $id)
            ->where('m_stu_in_peserta.is_approved', '1')
            ->select('m_stu_in_peserta.nama', 'm_country.name as kebangsaan', 'm_university.name as univ', 'm_stu_in_peserta.prodi_asal', 'm_prodi.level', 'm_prodi.name') // Pilih hanya yang dibutuhkan
            ->get();

        if ($peserta->isEmpty()) {
            return redirect()->route('program_stuin.edit', ['id' => $id])
                ->with('sweetalert', [
                    'type' => 'error',
                    'message' => 'Tidak ada peserta yang memenuhi persyaratan.'
                ]);
        }

        $program = DB::table('m_stu_in_programs')
        ->leftJoin('m_fakultas_unit', 'm_stu_in_programs.host_unit', '=', 'm_fakultas_unit.id')
        ->where('m_stu_in_programs.id', $id)->first();

        $ttd = public_path('assets/template/ttd_pak_iman.png');
        $logo = public_path('assets/images/LogoUnair.png');

        $programinfo = $this->getSemesterInfo($program->start_date);
        $ptft = ($program->pt_ft == 'PT') ? 'Part Time' : 'Full Time';
        
        $html = '<style>
                    body { font-size: 10pt; font-family: "Times New Roman", Times, serif; }
                    .content { margin-top: 100px; }
                    .title { font-size: 12pt; font-weight: bold; text-align: center; margin-top: 0px}
                    .text { text-align: justify; font-size: 10pt;  z-index: 1;}
                    ol { padding-left: 20px; }
                    li { margin-left: 20px; }
                    h5 ( margin: 0px; padding: 0px;)
                    .header-table {width: 100%; border-bottom: 2px solid black; border-collapse: collapse;}
                    .header-table td { text-align: center; }
                    .header-logo { width: 80px; height: auto; }
                    .header-text { text-align: center; font-size: 12pt;font-weight: bold; }
                    .subheader { text-align: center; font-size: 10pt;font-weight: bold; }
                    .sub-header { text-align: center; font-size: 8pt; font-style: italic; }
                    .contact { text-align: center; font-size: 8pt; }
                </style>';

        // **Buat Header PDF**
        $header = '<table class="header-table">
                        <tr>
                            <!-- Logo -->
                            <td style="width: 20%; text-align: center;">
                                <img src="'.$logo.'" class="header-logo">
                            </td>

                            <!-- Teks Header -->
                            <td style="width: 80%; text-align: center;">
                                <p class="header-text">UNIVERSITAS AIRLANGGA</p>
                                <p class="subheader">AIRLANGGA GLOBAL ENGAGEMENT</p>
                                <p class="sub-header">Jalan Airlangga 4-6 ASEEC (<i>Airlangga Sharia & Entrepreneurship Education Center</i>) Tower Lantai 12<br>
                                Surabaya, Kode pos <a href="#">60286</a>, Telp. (031) 5966864 Fax (031) 5955582</p>
                            </td>
                        </tr>
                    </table>
                    <hr style="border: 2x solid black; width: 100%; margin-top: 10px;">';
                    

        $html .= '<div class="content"> <br>';

        $html .= '<h2 class="title"><u>LETTER OF ACCEPTANCE</u></h2>';

        $html .= '<p class="text">
                    Congratulations!  <br><br>
                    
                    You have been admitted to Universitas Airlangga’s (UNAIR) student inbound program ' 
                    . $ptft . ', '  . ($programinfo['semester'] ?? 'Unknown') . ' semester ' 
                    . ($programinfo['year'] ?? 'Unknown') . ', ' 
                    . $this->formatDateRange($program->start_date) . '.<br><br>
                    
                    The particulars of this offer are as follows: 
                  </p>';

        $html .= '<ol>';
          foreach ($peserta as $p) {
            $html .= '<li>' . $p->nama . ' - ' . $p->univ . '</li>';
          }
        $html .= '</ol>';

        $html .= '<h4>UNIVERSITY REGULATION & ASSESSMENT</h4>
                    <p class="text">
                    Attendance at lectures is compulsory. If for some reason(s) students are unable to attend the classes, please 
                    ensure the lecturer is informed of the situation before the day. All participants have to sit for all the courses 
                    and the site visits schedule.  
                    </p>';

        $html .= '<h4>EARLY RETURN POLICY</h4>
                    <p class="text">
                        Students who return early before the end date of the program are obliged to notify the coordinator of the program in 
                        Airlangga Global Engagement. Students who return early before the end date of the program will not receive a hard copy 
                        of the certificate. Universitas Airlangga will not be responsible for anything that happens to students once they 
                        leave early from the university.
                    </p>';

        $html .= '<h4>FURTHER INQUIRY</h4>
                  <h5>Airlangga Global Engagement</h5>
                    <p class="text">
                    Office hours: Monday to Friday / 08.00 AM to 04.00 PM (UTC+7)<br>
                    Email: <a href="mailto:' . $program->corresponding  . '">' . $program->corresponding  . '</a><br><br>
                    We look forward to welcoming you to Universitas Airlangga, Indonesia. <br><br>
                    Surabaya, ' .  $this->formatDateRange($program->start_date)  . '<br>
                    Yours Sincerely,
                    </p>';

        $html .= '<img src="' . $ttd . '" style="width: 340px;  position: absolute; margin-top: -44px;  margin-left: -28px; z-index: -1;">';

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

    private function formatDateRange($dateRange)
    {
        // Check if the input contains " - " to separate start and end dates
        if (!str_contains($dateRange, ' - ')) {
            // If it's a single date, just format it properly
            $timestamp = strtotime($dateRange);
            return date('d F Y', $timestamp);
        }
    
        // Split the date range into start and end dates
        $dates = explode(' - ', $dateRange);
    
        // Ensure both start and end dates exist
        $startDate = isset($dates[0]) ? $dates[0] : null;
        $endDate = isset($dates[1]) ? $dates[1] : $startDate; // Default to start date if missing
    
        // Convert to timestamp
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
    
        // Extract date components
        $startDay = date('d', $startTimestamp);
        $startMonth = date('F', $startTimestamp);
        $endDay = date('d', $endTimestamp);
        $endMonth = date('F', $endTimestamp);
        $year = date('Y', $endTimestamp); // Use year from end date
    
        // Format output based on month similarity
        if ($startMonth === $endMonth) {
            return "{$startDay} - {$endDay} {$startMonth} {$year}";
        }
    
        return "{$startDay} {$startMonth} - {$endDay} {$endMonth} {$year}";
    }

    private function getSemesterInfo($startDate)
    {
        if (!$startDate) {
            return []; // Pastikan return default adalah array kosong, bukan string/null
        }
    
        $date = \Carbon\Carbon::parse($startDate);
        $month = $date->month;
        $year = $date->year;
        $semester = ($month >= 8 && $month <= 12) ? 'Odd' : 'Even';
    
        return [
            'year' => $year,
            'semester' => $semester
        ];
    }
    
    private function generateRandomString($length = 10)
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }



}
