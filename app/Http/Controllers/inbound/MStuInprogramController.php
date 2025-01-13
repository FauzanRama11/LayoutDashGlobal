<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MStuInProgram;
use Carbon\Carbon;

class MStuInProgramController extends Controller
{
    public function program_fak()
    {
        $user = Auth::user();

        if ($user->hasRole('fakultas')) {
            $fa = $user->name;
            
            $data = $data = DB::table('m_stu_in_programs')
            ->select('id','name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
            ->where('host_unit_text', 'like', "%$fa%")
            ->where("is_program_age", "N")
            ->limit(500)
            ->orderBy('created_time', 'desc')
            ->get();
        }
        else{
            $data = DB::table('m_stu_in_programs')
            ->select( 'name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time')
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

        dd($request->input('jenisSelect'));
    
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

        if ($request->hasFile('programLogo')) {
            // $path = $request->file('programLogo')->store('program_logos', 'public');
            $path = "Anggap/Ini/Path";
            $program->logo = $path;
        }

        if ($request->input('jenisSelect')=== 'Tidak') {
            $program->reg_date_start = $request->input('regOpen');
            $program->reg_date_closed = $request->input('regClose');
            $program->description = $request->input('programDesc') ?? null;
        }

        MStuInProgram::create($program->getAttributes());
        if ($request->input('progAge') === "N"){
            return redirect()->route('stuin_program_fak');
        }else{
            return redirect()->route('stuin_program_age');
        }
        
    }

    public function edit(string $id)
    {   $category = DB::table('m_stu_in_program_category')->get();
        $dosen = DB::table('m_dosen')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $data = MStuInProgram::find($id);
        // dd($data);
        return view("stu_inbound.edit_program", compact("data", "category", "dosen", "univ"));
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

    if ($request->hasFile('programLogo')) {
        $path = $request->file('programLogo')->store('program_logos', 'public');
        $program->logo = $path;
    }

    if ($request->input('jenisSelect') === 'Tidak') {
        $program->reg_date_start = $request->input('regOpen');
        $program->reg_date_closed = $request->input('regClose');
        $program->description = $request->input('programDesc') ?? null;
    }

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
