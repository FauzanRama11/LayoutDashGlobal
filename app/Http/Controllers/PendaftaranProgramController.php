<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\MStuInProgram;

class PendaftaranProgramController extends Controller
{
    public function stuin(Request $request, $url_generate)
        {
            
            $program =  MStuInProgram::where('url_generate', $url_generate)->first();
            // dd($program->logo);

            $cleanPath = ltrim(str_replace('penyimpanan/', '', $program->logo), '/');
        
            // $filePath = base_path('../' . $program->logo);
            
            if ($program->logo && Storage::disk('outside')->exists($cleanPath)) {
                // Dapatkan konten file
                
                $fileContent = Storage::disk('outside')->get($cleanPath);
                $program->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
    
            } else {
                $program->logo_base64 = null;
            }
    

            $period = DB::table('m_stu_in_programs')
                ->whereDate('reg_date_start', '<=', now())
                ->whereDate('reg_date_closed', '>=', now())
                ->first();
        
            if (!$period) {
                return redirect()->route('failed')->withErrors(['error' => 'Masa pengisian telah selesai, hubungi admin ']);
            }
        
            $univ = DB::table('m_university')->get();
            $country = DB::table('m_country')->whereNot('id', 95)->get();
            $course = DB::table('age_amerta_matkul')->get();
        
            return view('pendaftaran.registrasi_stu_in', [
                'id_period' => $period->id ?? null,
                'univ' => $univ,
                'country' => $country,
                'course' => $course,
                'program' => $program
            ]);
        }
}
