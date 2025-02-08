<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MStuInProgram;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class MStuInProgramController extends Controller
{
    public function program_fak(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();

            if ($user->hasRole('fakultas')) {
                $fa = $user->name;

                $data = DB::table('m_stu_in_programs')
                    ->select('id', 'name', 'start_date', 'end_date', 'category_text as cat', 'via', 
                             'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time',
                             DB::raw("(SELECT COUNT(*) FROM m_stu_in_peserta ps WHERE ps.program_id = m_stu_in_programs.id) as jumlah_peserta"))
                    ->where('host_unit_text', 'like', "%$fa%")
                    ->where("is_program_age", "N");
            } else {
                $data = DB::table('m_stu_in_programs')
                    ->select('id', 'name', 'start_date', 'end_date', 'category_text as cat', 'via', 
                             'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time',
                             DB::raw("(SELECT COUNT(*) FROM m_stu_in_peserta ps WHERE ps.program_id = m_stu_in_programs.id) as jumlah_peserta"))
                    ->where("is_program_age", "N");
            }

                if ($request->has('order')) {
                    foreach ($request->order as $order) {
                        $columnIndex = $order['column']; 
                        $columnDir = $order['dir'];
                        $columnName = $request->columns[$columnIndex]['data'];      
                    }
                } else {
                    $data->orderBy('created_time', 'desc'); 
                }

            return DataTables::of($data)
                ->editColumn('is_private_event', function ($item) {
                    return $item->is_private_event === 'Tidak' ? 'Registrasi' : 'Pelaporan';
                })
                ->addColumn('edit', function ($item) {
                    return '<form action="' . route('program_stuin.edit', ['id' => $item->id]) . '" method="GET">
                                <button type="submit" class="btn btn-primary edit-button"><i class="fa fa-edit"></i>  Edit</button>
                            </form>';
                })
                ->addColumn('delete', function ($item) {
                    return '<form action="' . route('program_stuin.destroy', ['id' => $item->id]) . '" method="POST" 
                                onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dipulihkan\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger delete-button"><i class="fa fa-trash"></i>  Delete</button>
                            </form>';
                })
                ->rawColumns(['is_private_event', 'edit', 'delete']) // Render as HTML
                ->make(true);
        }

        return view('stu_inbound.program_fak');
    }

    public function program_age(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('m_stu_in_programs')
            ->select('id','name','start_date', 'end_date', 'category_text as cat', 'via', 'host_unit_text as unit', 'pt_ft', 'is_private_event', 'created_time',
                DB::raw("(SELECT COUNT(*) FROM m_stu_in_peserta ps WHERE ps.program_id = m_stu_in_programs.id) as jumlah_peserta"))
            ->where("is_program_age", "Y")
            ->orWhere('host_unit_text', 'Airlangga Global Engagement');

                if ($request->has('order')) {
                    foreach ($request->order as $order) {
                        $columnIndex = $order['column']; 
                        $columnDir = $order['dir'];
                        $columnName = $request->columns[$columnIndex]['data'];      
                    }
                } else {
                    $data->orderBy('created_time', 'desc'); 
                }

            return DataTables::of($data)
                ->editColumn('is_private_event', function ($item) {
                    return $item->is_private_event === 'Tidak' ? 'Registrasi' : 'Pelaporan';
                })
                ->addColumn('edit', function ($item) {
                    return '<form action="' . route('program_stuin.edit', ['id' => $item->id]) . '" method="GET">
                                <button type="submit" class="btn btn-primary edit-button"><i class="fa fa-edit"></i>  Edit</button>
                            </form>';
                })
                ->addColumn('delete', function ($item) {
                    return '<form action="' . route('program_stuin.destroy', ['id' => $item->id]) . '" method="POST" 
                                onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dipulihkan\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger delete-button"><i class="fa fa-trash"></i>  Delete</button>
                            </form>';
                })
                ->rawColumns(['is_private_event', 'edit', 'delete']) // Render as HTML
                ->make(true);
        }

        return view('stu_inbound.program_age');
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

        $pic_name = DB::table('m_dosen')
        ->where('id', '=', $request->input('pic'))
        ->pluck('nama')->first();

        $program->pic = $pic_name;
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
                $folder = 'inbound';

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

        MStuInProgram::create($program->getAttributes());
        if ($request->input('progAge') === "N"){
            return redirect()->route('stuin_program_fak');
        }else{
            return redirect()->route('stuin_program_age');
        }
        
    }

    public function edit(string $id)
    {   
       // Ambil data peserta dengan sumber dana RKAT
        $pesertaRKAT = DB::table('m_stu_in_peserta')
            ->join('m_prodi', 'm_stu_in_peserta.tujuan_prodi', '=', 'm_prodi.id')
            ->where('m_stu_in_peserta.program_id', $id)
            ->where('m_stu_in_peserta.pengajuan_dana_status', 'APPROVED')
            ->where('m_stu_in_peserta.sumber_dana', 'RKAT') // Filter RKAT langsung
            ->select('m_stu_in_peserta.nama', 'm_prodi.level', 'm_prodi.name')
            ->get();

        // Ambil data peserta dengan sumber dana DPAT
        $pesertaDPAT = DB::table('m_stu_in_peserta')
            ->join('m_prodi', 'm_stu_in_peserta.tujuan_prodi', '=', 'm_prodi.id')
            ->where('m_stu_in_peserta.program_id', $id)
            ->where('m_stu_in_peserta.pengajuan_dana_status', 'APPROVED')
            ->where('m_stu_in_peserta.sumber_dana', 'DPAT') // Filter DPAT langsung
            ->select('m_stu_in_peserta.nama', 'm_prodi.level', 'm_prodi.name')
            ->get();

        $category = DB::table('m_stu_in_program_category')->get();
        $dosen = DB::table('m_dosen')->get();
        $univ = DB::table('m_university')
            ->whereNot('country', 95)
            ->get();


        $data = MStuInProgram::find($id);

        $cleanPathRoot = ltrim(str_replace('repo/inbound/', '', $data->logo), '/');
        $cleanPathInbound = ltrim(str_replace('repo/', '', $data->logo), '/');
        

        // Pencarian file dalam repo
        if (Storage::disk('inside')->exists($cleanPathInbound)) {
            $filePath = $cleanPathInbound;
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

        $peserta = DB::table('m_stu_in_peserta')
        ->select('m_stu_in_peserta.*', 'm_university.name as univ_name', 'm_country.name as country_name')
        ->where("program_id", "=", $id)
        ->leftjoin('m_university', 'm_university.id', '=', 'm_stu_in_peserta.univ')
        ->leftjoin('m_country', 'm_country.id', '=', 'm_stu_in_peserta.kebangsaan')
        ->get();

        return view("stu_inbound.edit_program", compact("peserta", "pesertaRKAT", "pesertaDPAT", "data", "category", "dosen", "univ"));
    
    }

    public function update(Request $request, string $id)
    {
    $program = MStuInProgram::find($id);
    $unit = DB::table('m_fakultas_unit')
        ->where('nama_ind', $request->input('hostUnit'))
        ->pluck('id')
        ->first();

    $cat = DB::table('m_stu_in_program_category')
        ->where('id', '=', $request->input('progCategory'))
        ->pluck('name')
        ->first();

    $pic_name = DB::table('m_dosen')
    ->where('id', '=', $request->input('pic'))
    ->pluck('nama')->first();


    $program->name = $request->input('nameProg');
    $program->start_date = $request->input('startDate');
    $program->end_date = $request->input('endDate');
    $program->category = $request->input('progCategory');
    $program->category_text = $cat;
    $program->host_unit = $unit;
    $program->host_unit_text = $request->input('hostUnit');    
    $program->pic = $pic_name;
    $program->corresponding = $request->input('email');
    $program->website = $request->input('website') ?? null;
    $program->pt_ft = $this->calculateDuration($request->input('startDate'), $request->input('endDate'));
    $program->via = $request->input('via');

    

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

        if ($request->hasFile('programLogo')) {
            
            $file = $request->file('programLogo');
            $folder = 'inbound';

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

        if ($duration > 150) {
            $pt_ft = "FT"; 
        } else {
            $pt_ft = "PT";  
        }

        return $pt_ft; 
    }
}
