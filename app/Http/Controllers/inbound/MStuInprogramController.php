<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MStuInProgram;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MStuInProgramController extends Controller
{
    public function program_fak()
    {
        $user = Auth::user();

        if ($user->hasRole('fakultas')) {
            $fa = $user->name;
            
            $data = DB::table('m_stu_in_programs')
            ->select('id','name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
            ->where('host_unit_text', 'like', "%$fa%")
            ->where("is_program_age", "N")
            ->limit(500)
            ->orderBy('created_time', 'desc')
            ->get();
        }
        else{
            $data = DB::table('m_stu_in_programs')
            ->select('id', 'name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
            ->where("is_program_age", "N")
            ->limit(500)
            ->orderBy('created_time', 'desc')
            ->get();
        };

        return view('stu_inbound.program_fak', compact('data'));
    }
    public function program_age()
    {
        $data = $data = DB::table('m_stu_in_programs')
        ->select('id','name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
        ->where("is_program_age", "Y")
        ->orWhere('host_unit_text', 'Airlangga Global Engagement')
        ->limit(500)
        ->orderBy('created_time', 'desc')
        ->get();
        return view('stu_inbound.program_age', compact('data'));
    }
    public function add_program_fak(){
        $category = DB::table('m_stu_in_program_category')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $dosen = DB::table('m_dosen')->get();

        return view('stu_inbound.form_program_fakultas', compact('category', 'univ',  'dosen'));
    }

    public function add_program_age(){

        $category = DB::table('m_stu_in_program_category')->get();
        $dosen = DB::table('m_dosen')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        return view('stu_inbound.form_program_age', compact('category', 'dosen', 'univ'));
    }

    public function store_program(Request $request){
        $unit = DB::table('m_fakultas_unit')
        ->where('nama_ind', $request->input('hostUnit'))
        ->pluck('id')->first(); 
        
        $country = DB::table('m_university')
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->where('m_university.name', $request->input('univTujuan'))
        ->pluck('m_country.name')->first();


        $cat = DB::table('m_stu_in_program_category')
        ->where('id', '=', $request->input('progCategory'))
        ->pluck('name')->first();

        // dd($request->input('jenisSelect'));
    
        $program = new MStuInProgram();
        $program->is_program_age = $request->input('progAge');
        $program->is_private_event = $request->input('jenisSelect');
        $program->name = $request->input('nameProg');
        $program->start_date = $request->input('startDate');
        $program->end_date = $request->input('endDate');
        $program->category = $request->input('progCategory');
        $program->category_text = $cat;
        $program->host_unit = $unit;
        $program->host_unit_text = $request->input('hostUnit');
        $program->pic = $request->input('pic');
        $program->corresponding = $request->input('email');
        $program->website = $request->input('website') ?? null;
        $program->pt_ft = $this->calculateDuration($request->input('startDate'), $request->input('endDate'));
        $program->via = $request->input('via');
        $program->created_by = Auth::User()->id;
        $program->created_time = now();

        

        if ($request->input('jenisSelect')=== 'Tidak') {
            $program->reg_date_start = $request->input('regOpen');
            $program->reg_date_closed = $request->input('regClose');
            $program->description = $request->input('programDesc') ?? null;

            // Pembuatan kode generate url
            $kode = DB::table('m_fakultas_unit')
            ->where('nama_ind', $request->input('hostUnit'))
            ->pluck('kode')->first(); 

            $namadepan = explode(' ', $program->name)[0];

            $program->url_generate = ($kode.'_'. $namadepan.'_'.uniqid());

            if ($request->hasFile('programLogo')) {
                $file = $request->file('programLogo');
        
                // Tentukan direktori penyimpanan di luar folder Laravel
                $storagePath = base_path('../penyimpanan'); 
        
                // Pastikan folder "penyimpanan" ada
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0777, true); 
                }
        
                // Simpan file ke folder "penyimpanan"
                $fileName = uniqid() . '_' . $file->getClientOriginalName(); 
                $file->move($storagePath, $fileName);
                $program->logo = 'penyimpanan/' . $fileName;
                // dd($program);
            }

        }

        MStuInProgram::create($program->getAttributes());
        if ($request->input('progAge') === "N"){
            return redirect()->route('stuin_program_fak');
        }else{
            return redirect()->route('stuin_program_age');
        }
        
    }

    public function edit(string $id)
    {   
        $category = DB::table('m_stu_out_program_category')->get();
        $dosen = DB::table('m_dosen')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();


        $data = MStuInProgram::find($id);
        $cleanPath = ltrim(str_replace('penyimpanan/', '', $data->logo), '/');
        
        $filePath = base_path('../' . $data->logo);
        
        if ($data->logo && Storage::disk('outside')->exists($cleanPath)) {
            // Dapatkan konten file
            
            $fileContent = Storage::disk('outside')->get($cleanPath);
            $data->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
            // dd($data->logo_base64);

        } else {
            $data->logo_base64 = null;
        }

        $peserta = DB::table('m_stu_in_peserta')
        
        ->select('m_stu_in_peserta.*', 'm_university.name as univ_name', 'm_country.name as country_name')
        ->where("program_id", "=", $id)
        ->leftjoin('m_university', 'm_university.id', '=', 'm_stu_in_peserta.univ')
        ->leftjoin('m_country', 'm_country.id', '=', 'm_stu_in_peserta.kebangsaan')
        ->get();
        // dd($peserta);
        return view("stu_inbound.edit_program", compact("peserta", "data", "category", "dosen", "univ"));
    
    }

    public function update(Request $request, string $id)
    {
    $program = MStuInProgram::find($id);
    $unit = DB::table('m_fakultas_unit')
        ->where('nama_ind', $request->input('hostUnit'))
        ->pluck('id')
        ->first();

    $country = DB::table('m_university')
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->where('m_university.name', $request->input('univTujuan'))
        ->pluck('m_country.name')
        ->first();

    $cat = DB::table('m_stu_in_program_category')
        ->where('id', '=', $request->input('progCategory'))
        ->pluck('name')
        ->first();

    $program->name = $request->input('nameProg');
    $program->start_date = $request->input('startDate');
    $program->end_date = $request->input('endDate');
    $program->category = $request->input('progCategory');
    $program->category_text = $cat;
    $program->host_unit = $unit;
    $program->host_unit_text = $request->input('hostUnit');
    $program->pic = $request->input('pic');
    $program->corresponding = $request->input('email');
    $program->website = $request->input('website') ?? null;
    $program->pt_ft = $this->calculateDuration($request->input('startDate'), $request->input('endDate'));
    $program->via = $request->input('via');

    if ($request->hasFile('programLogo')) {
        $file = $request->file('programLogo');
        
        // Tentukan direktori penyimpanan di luar folder Laravel
        $storagePath = base_path('../penyimpanan'); 

        // Pastikan folder "penyimpanan" ada
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true); 
        }

        // Simpan file ke folder "penyimpanan"
        $fileName = uniqid() . '_' . $file->getClientOriginalName(); 
        $file->move($storagePath, $fileName);
        $program->logo = 'penyimpanan/' . $fileName;
    }

    if ($request->input('jenisSelect') === 'Registrasi') {
        $program->reg_date_start = $request->input('regOpen');
        $program->reg_date_closed = $request->input('regClose');
        
        $program->description = $request->input('programDesc') ?? null;

        if ($program->url_generate === null) {
            // Pembuatan kode generate url
            $kode = DB::table('m_fakultas_unit')
            ->where('nama_ind', $request->input('hostUnit'))
            ->pluck('kode')->first(); 

            $namadepan = explode(' ', $program->name)[0];
            
            $program->url_generate = ($kode.'_'. $namadepan.'_'.uniqid());
        }

    }


    // dd($program);

    $program->save();

    if ($request->input('progAge') === "N") {
        return redirect()->route('stuin_program_fak')->with('success', 'Program updated successfully.');
    } else {
        return redirect()->route('stuin_program_age')->with('success', 'Program updated successfully.');
    }
}




    public function destroy_program_fak($id)
    {
        $del = MStuInProgram::where("ID", $id)->first();
        $is_age = MStuInProgram::where("ID", $id)->pluck("is_program_age")->first();
        
        if ($del) {
          
                $del->delete();
                if ($is_age === "N"){
                    return redirect()->route('stuin_program_fak');
                }else{
                    return redirect()->route('stuin_program_age');
                }
        
        } 
    }

    public function calculateDuration($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $duration = $start->diffInDays($end);

        if ($duration > 90) {
            $pt_ft = "FT"; 
        } else {
            $pt_ft = "PT";  
        }

        return $pt_ft; 
    }
}
