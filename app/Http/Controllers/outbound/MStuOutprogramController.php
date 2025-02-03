<?php

namespace App\Http\Controllers\outbound;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\MStuOutPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MStuOutProgram;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MStuOutprogramController extends Controller
{
    public function program_fak()
    {
        $user = Auth::user();

        if ($user->hasRole('fakultas')) {
            $fa = $user->name;

        $data = DB::table('m_stu_out_programs')
            ->select('id', 'name', 'start_date', 'end_date', 'category_text as cat', 'via', 'sub_mbkm',  'host_unit_text as unit','universitas_tujuan', 'negara_tujuan',  'pt_ft', 'is_private_event', 'created_time')
            ->where('host_unit_text', 'like', "%$fa%")
            ->where("is_program_age", "N")
            ->orderBy('created_time', 'asc')
            ->get();
        }
        else{

            $data = DB::table('m_stu_out_programs')
            ->select('id', 'name', 'start_date', 'end_date', 'category_text as cat', 'via', 'sub_mbkm',  'host_unit_text as unit','universitas_tujuan', 'negara_tujuan',  'pt_ft', 'is_private_event', 'created_time')
            ->where("is_program_age", "N")
            ->orderBy('created_time', 'desc')
            ->get();
        };
        return view('stu_outbound.program_fak', compact('data'));
    }
    public function program_age()
    {
        $data = DB::table('m_stu_out_programs')
            ->select('id', 'name', 'start_date', 'end_date', 'category_text as cat', 'via', 'sub_mbkm',  'host_unit_text as unit','universitas_tujuan', 'negara_tujuan',  'pt_ft', 'is_private_event', 'created_time')
            ->where("is_program_age", "Y")
            ->orWhere("host_unit_text", "Airlangga Global Engagement")
            ->orderBy('created_time', 'desc')
            ->get();

        return view('stu_outbound.program_age', compact('data'));
    }

    public function add_program_fak(){
        $category = DB::table('m_stu_out_program_category')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        $dosen = DB::table('m_dosen')->get();

        return view('stu_outbound.form_program_fakultas', compact('category', 'univ',  'dosen'));
    }

    public function add_program_age(){

        $category = DB::table('m_stu_out_program_category')->get();
        $dosen = DB::table('m_dosen')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();
        return view('stu_outbound.form_program_age', compact('category', 'dosen', 'univ'));
    }

    public function store_program(Request $request){
        $unit = DB::table('m_fakultas_unit')
        ->where('nama_ind', $request->input('hostUnit'))
        ->pluck('id')->first(); 
        
        $country = DB::table('m_university')
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->where('m_university.name', $request->input('univTujuan'))
        ->pluck('m_country.name')->first();


        $cat = DB::table('m_stu_out_program_category')
        ->where('id', '=', $request->input('progCategory'))
        ->pluck('name')->first();
    
        $program = new MStuOutProgram();

        
        $program->is_program_age = $request->input('progAge');
        $program->is_private_event = $request->input('jenisSelect');
        $program->name = $request->input('nameProg');
        $program->start_date = $request->input('startDate');
        $program->end_date = $request->input('endDate');
        $program->category = $request->input('progCategory');
        $program->category_text = $cat;
        // $program->sub_mbkm = $request->input('subMbkm') ?? null;
        $program->host_unit = $unit;
        $program->host_unit_text = $request->input('hostUnit');
        $program->pic = $request->input('pic');
        $program->corresponding = $request->input('email');
        $program->negara_tujuan = $country;
        $program->universitas_tujuan = $request->input('univTujuan');
        $program->website = $request->input('website') ?? null;
        $program->pt_ft = $this->calculateDuration($request->input('startDate'), $request->input('endDate'));
        $program->via = $request->input('via');
        $program->created_by = Auth::User()->id;
        $program->created_time = now();

    

        if ($request->input('jenisSelect')=== 'Tidak') {
            $program->reg_date_start = $request->input('regOpen');
            $program->reg_date_closed = $request->input('regClose');
            $program->description = $request->input('programDesc') ?? null;

            $kode = DB::table('m_fakultas_unit')
                ->where('nama_ind', $request->input('hostUnit'))
                ->pluck('kode')->first(); 

        $namadepan = explode(' ', $program->name)[0];

        $program->url_generate = ($kode.'_'. $namadepan.'_'.uniqid());

        if ($request->hasFile('programLogo')) {

            $file = $request->file('programLogo');
            $folder = 'outbound';

            if (!Storage::disk('inside')->exists($folder)) {
                Storage::disk('inside')->makeDirectory($folder);
            }

            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $filePath = Storage::disk('inside')->putFileAs($folder, $file, $fileName);

            // dd($filePath);
        
            // Simpan path relatif ke database
            $program->logo = 'repo/' . $filePath;

        }
        }

        MStuOutProgram::create($program->getAttributes());
        if ($request->input('progAge') === "N"){
            return redirect()->route('stuout_program_fak');
        }else{
            return redirect()->route('stuout_program_age');
        }
        

    }

    public function edit(string $id)
    {   $category = DB::table('m_stu_out_program_category')->get();
        $dosen = DB::table('m_dosen')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();

        $pesertaRKAT = DB::table('m_stu_out_peserta')
            ->join('m_prodi', 'm_stu_out_peserta.tujuan_prodi', '=', 'm_prodi.id')
            ->where('m_stu_out_peserta.program_id', $id)
            ->where('m_stu_out_peserta.pengajuan_dana_status', 'APPROVED')
            ->where('m_stu_out_peserta.sumber_dana', 'RKAT') // Filter RKAT langsung
            ->select('m_stu_out_peserta.nama', 'm_prodi.level', 'm_prodi.name')
            ->get();
        // Ambil data peserta dengan sumber dana DPAT
        $pesertaDPAT = DB::table('m_stu_out_peserta')
            ->join('m_prodi', 'm_stu_out_peserta.tujuan_prodi', '=', 'm_prodi.id')
            ->where('m_stu_out_peserta.program_id', $id)
            ->where('m_stu_out_peserta.pengajuan_dana_status', 'APPROVED')
            ->where('m_stu_out_peserta.sumber_dana', 'DPAT') // Filter DPAT langsung
            ->select('m_stu_out_peserta.nama', 'm_prodi.level', 'm_prodi.name')
            ->get();
        $data = MStuOutProgram::find($id);

        $cleanPathRoot = ltrim(str_replace('repo/outbound/', '', $data->logo), '/');
        $cleanPathOutbound = ltrim(str_replace('repo/', '', $data->logo), '/');
        

        // Pencarian file dalam repo
        if (Storage::disk('inside')->exists($cleanPathOutbound)) {
            $filePath = $cleanPathOutbound;
        } elseif (Storage::disk('inside')->exists($cleanPathRoot)) {
            $filePath = $cleanPathRoot;
        } else {
            $filePath = null;
        }        

        // Jika file ditemukan, ambil konten file dalam format base64
        if ($filePath) {
            $fileContent = Storage::disk('inside')->get($filePath);
            $data->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
        } else {
            $data->logo_base64 = null;
        }

        $peserta = DB::table('m_stu_out_peserta')
        ->select('m_stu_out_peserta.*', 'm_university.name as univ_name', 'm_country.name as country_name')
        ->where("program_id", "=", $id)
        ->leftjoin('m_university', 'm_university.id', '=', 'm_stu_out_peserta.univ')
        ->leftjoin('m_country', 'm_country.id', '=', 'm_stu_out_peserta.kebangsaan')
        ->get();
        // dd($peserta);
        return view("stu_outbound.edit_program", compact("peserta", "data", "category", "dosen", "univ", "pesertaDPAT", "pesertaRKAT"));
    }

    public function update(Request $request, string $id)
    {
    $program = MStuOutProgram::find($id);
    $unit = DB::table('m_fakultas_unit')
        ->where('nama_ind', $request->input('hostUnit'))
        ->pluck('id')
        ->first();

    $country = DB::table('m_university')
        ->join('m_country', 'm_country.id', '=', 'm_university.country')
        ->where('m_university.name', $request->input('univTujuan'))
        ->pluck('m_country.name')
        ->first();

    $cat = DB::table('m_stu_out_program_category')
        ->where('id', '=', $request->input('progCategory'))
        ->pluck('name')
        ->first();
        if($request->input('jenisSelect') == "Pelaporan"){
            $program->is_private_event = "Ya";
        }else{
            $program->is_private_event = "Tidak";
        }
    
    $program->name = $request->input('nameProg');
    $program->start_date = $request->input('startDate');
    $program->end_date = $request->input('endDate');
    $program->category = $request->input('progCategory');
    $program->category_text = $cat;
    $program->host_unit = $unit;
    $program->host_unit_text = $request->input('hostUnit');
    $program->pic = $request->input('pic');
    $program->corresponding = $request->input('email');
    $program->negara_tujuan = $country;
    $program->universitas_tujuan = $request->input('univTujuan');
    $program->website = $request->input('website') ?? null;
    $program->pt_ft = $this->calculateDuration($request->input('startDate'), $request->input('endDate'));
    $program->via = $request->input('via');
    $program->created_by = Auth::User()->id;
    $program->created_time = now();

    if ($request->hasFile('programLogo')) {
        $path = $request->file('programLogo')->store('program_logos', 'public');
        $program->logo = $path;
    }

    if ($request->input('jenisSelect') === 'Tidak' || $request->input('jenisSelect') === 'Registrasi' ) {
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

        if ($request->hasFile('programLogo')) {
            
            $file = $request->file('programLogo');
            $folder = 'outbound';

            // Hapus file lama jika ada
            if ($program->logo) {
                $oldFilePath = str_replace('repo/', '', $program->logo); // Konversi path ke relative
                if (Storage::disk('inside')->exists($oldFilePath)) {
                    Storage::disk('inside')->delete($oldFilePath); // Hapus file lama
                }
            }

            if (!Storage::disk('inside')->exists($folder)) {
                Storage::disk('inside')->makeDirectory($folder);
            }

            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $filePath = Storage::disk('inside')->putFileAs($folder, $file, $fileName);
        
            // Simpan path relatif ke database
            $program->logo = 'repo/' . $filePath;

        }
    }

    $program->save();

    if ($request->input('progAge') === "N") {
        return redirect()->route('stuout_program_fak')->with('success', 'Program updated successfully.');
    } else {
        return redirect()->route('stuout_program_age')->with('success', 'Program updated successfully.');
    }
}


    public function destroy_program_fak($id)
    {
        $del = MStuOutProgram::where("ID", $id)->first();
        $is_age = MStuOutProgram::where("ID", $id)->pluck("is_program_age")->first();
        
        if ($del) {
          
                $del->delete();
                if ($is_age === "N"){
                    return redirect()->route('stuout_program_fak');
                }else{
                    return redirect()->route('stuout_program_age');
                }
        
        } 
    }

    public function calculateDuration($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $duration = $start->diffInDays($end);

        if ($duration > 150) {
            $pt_ft = "FT"; 
        } else {
            $pt_ft = "PT";  
        }

        return $pt_ft; 
    }
    
}
