<?php

namespace App\Http\Controllers\agreement;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\GrieMoaAcademicPelaporan;
use App\Models\GrieMoaAcademicPelaporanFaculty;
use App\Models\GrieMoaAcademicPelaporanPartner;
use App\Models\GrieMoaAcademicPelaporanProdi;
use App\Models\GrieMoaAcademicPelaporanScope;
use App\Models\GrieMoaAcademic;
use App\Models\GrieMoaAcademicFaculty;
use App\Models\GrieMoaAcademicPartner;
use App\Models\GrieMoaAcademicProdi;
use App\Models\GrieMoaAcademicScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;


class AgreementController extends Controller
{
    public function tambah_pelaporan($id = null){

        $univ = DB::table('m_university')
        ->leftJoinSub(
            DB::table('m_univ_ranking')
                ->selectRaw('univ, MIN(rank_value_min) as rank_value_min, MIN(subject) as subject')
                ->where('type', 5)
                ->groupBy('univ'),
            'r',
            'r.univ',
            '=',
            'm_university.id'
        )
        ->select(
            'm_university.*',
            DB::raw('r.univ as rank_univ'),
            DB::raw('r.rank_value_min'),
            DB::raw('r.subject as subject')
        )
        ->get();

        $unit = DB::table('m_fakultas_unit')
        ->get(); 
        $country = DB::table('m_country')
        ->get();
        $scope = DB::table('m_collaboration_scope')
        ->get();
        $department = DB::table('m_departemen')
        ->get();
        $prodi = DB::table('m_prodi')
        ->get();
        $data = $id ? GrieMoaAcademicPelaporan::findOrFail($id) : null;
        
        $user_unit = DB::table('m_dosen')
        ->select('id_fakultas', 'm_fakultas_unit.nama_ind as nama_ind')
        ->leftJoin('m_fakultas_unit',  'm_fakultas_unit.id', '=','id_fakultas' )
        ->where('nik', '=', Auth::user()->username)
        ->first();

        // dd($user_unit);

        if ($data !== null) {
                $selPartners = GrieMoaAcademicPelaporanPartner::where('id_moa_academic', $data->id)->get()->toArray();
                $selPartners = array_column($selPartners, 'id_partner_university'); 
                $selFaculties = GrieMoaAcademicPelaporanFaculty::where('id_moa_academic', $data->id)->get()->toArray();
                $selFaculties = array_column($selFaculties, 'id_faculty'); 
                $selScopes = GrieMoaAcademicPelaporanScope::where('id_moa_academic', $data->id)->get()->toArray();
                $selScopes = array_column($selScopes, 'id_collaboration_scope'); 
                $selProdis = GrieMoaAcademicPelaporanProdi::where('id_moa_academic', $data->id)->get()->toArray();
                $selProdis = array_column($selProdis, column_key: 'id_program_study_unair'); 
                return view('agreement.form_pelaporan', compact('data',  'selPartners', 'selFaculties','selScopes', 'selProdis', 'univ', 'unit','country', 'scope', 'department', 'prodi'));
            }
        else{ 
            return view('agreement.form_pelaporan', compact('data',  'univ', 'unit','country', 'scope', 'department', 'prodi', 'user_unit'));}
    }


public function destroy_pelaporan($id){
    
        try {
            $del = GrieMoaAcademicPelaporan::where("id", $id)->first();
            $del->delete();  
    
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data.'
            ]);
        }
} 

public function store_pelaporan(Request $request, $id = null) {
    try {
        if($request->input('docP') != "MoU"){
        $request->validate([
            'jenisP' => 'required|string', 
            'triDharma' => 'required|string', 
            'unitP' => 'required|integer', 
            'countryP' => 'required|integer', 
            'partnerP' => 'required|array', 
            'partnerP.*' => 'integer',
            'docP' => 'required|string', 
            'tittleP' => 'required|string|max:255', 
            'scopeP' => 'required|array', 
            'scopeP.*' => 'integer', 
            'startDate' => 'required|date', 
            'endDate' => 'required|date', 
            'deptP' => 'required|integer', 
            'FacP' => 'required|array', 
            'FacP.*' => 'integer', 
            'stuProgP' => 'required|array', 
            'stuProgP.*' => 'integer',
            'partDept' => 'required|string', 
            'partnerFac' => 'required|string', 
            'partnerStuProg' => 'required|string', 
            'typeP' => 'required|string', 
            'nosUnair' => 'required|string', 
            'nopUnair' => 'required|string', 
            'nosPart' => 'required|string', 
            'nopPart' => 'required|string', 
            'namePic' => 'required|string', 
            'postPic' => 'required|string', 
            'emailPic' => 'required|email', 
            'telpPic' => 'required|numeric', 
            'namePic2' => 'required|string', 
            'postPic2' => 'required|string', 
            'emailPic2' => 'required|email', 
            'telpPic2' => 'required|numeric', 
        ]);
    }else{
        $request->validate([
            'jenisP' => 'required|string', 
            'triDharma' => 'required|string', 
            'unitP' => 'required|integer', 
            'countryP' => 'required|integer', 
            'partnerP' => 'required|array', 
            'partnerP.*' => 'integer',
            'docP' => 'required|string', 
            'tittleP' => 'required|string|max:255', 
            'scopeP' => 'required|array', 
            'scopeP.*' => 'integer', 
            'startDate' => 'required|date', 
            'endDate' => 'required|date', 
            'deptP' => 'required|integer', 
            'FacP' => 'array', 
            'FacP.*' => 'integer', 
            'stuProgP' => 'array', 
            'stuProgP.*' => 'integer',
            'partDept' => 'required|string', 
            'partnerFac' => 'required|string', 
            'partnerStuProg' => 'required|string', 
            'typeP' => 'required|string', 
            'nosUnair' => 'required|string', 
            'nopUnair' => 'required|string', 
            'nosPart' => 'required|string', 
            'nopPart' => 'required|string', 
            'namePic' => 'required|string', 
            'postPic' => 'required|string', 
            'emailPic' => 'required|email', 
            'telpPic' => 'required|numeric', 
            'namePic2' => 'required|string', 
            'postPic2' => 'required|string', 
            'emailPic2' => 'required|email', 
            'telpPic2' => 'required|numeric', 
        ]);
    }
        if($request->input('jenisP') == "Riset"){
            $request->validate([                  
                'sourceFund' => 'required', 
                'sumFund' => 'required|numeric'
            ]);
        }
    } catch (ValidationException $e) {
        return response()->json(['status' => 'error', 'message' => 'Please make sure all required fields are completed!'], 500);
    }


    if($request->hasFile('linkDownload')){
        try {
            $request->validate([
                'linkDownload' => 'required|file|mimes:pdf|max:2560', 
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Please upload a PDF file smaller than 2.5 MB!'], 500);
        }
    }
 
    $country = DB::table("m_country")
        ->where("id", "=", $request->input('countryP'))
        ->pluck("m_country.name")->first();

    $dept = DB::table("m_departemen")
        ->where("id", "=", $request->input('deptP'))
        ->pluck("nama_ind")->first();

    if ($id) {
        $pelaporan = GrieMoaAcademicPelaporan::findOrFail($id); 

        GrieMoaAcademicPelaporanFaculty::where("id_moa_academic", $id)->delete();
        GrieMoaAcademicPelaporanProdi::where("id_moa_academic", $id)->delete();
        GrieMoaAcademicPelaporanScope::where("id_moa_academic", $id)->delete();
        GrieMoaAcademicPelaporanPartner::where("id_moa_academic", $id)->delete();

    } else {
        $pelaporan = new GrieMoaAcademicPelaporan();
        $pelaporan->approval_pelaporan = 0;
        $pelaporan->created_by = Auth::user()->id;
    }

    $pelaporan->id_dosen = null;
    $pelaporan->created_date = now();
    $pelaporan->id_partner_university = null;
    $pelaporan->tipe_moa = $request->input('jenisP');
    $pelaporan->kategori_tridharma = $request->input('triDharma');
    $pelaporan->kategori = $request->input('jenisP');
    $pelaporan->id_country = $request->input('countryP');
    $pelaporan->text_country = $country;
    $pelaporan->id_fakultas = $request->input('unitP');
    $pelaporan->text_end_date = "Automatically Renewed";
    $pelaporan->jenis_naskah = $request->input('docP');
    $pelaporan->title = $request->input('tittleP');
    // $pelaporan->link_download_naskah = $request->input('linkDownload');
    if ($request->hasFile('linkDownload')) {
        $file = $request->file('linkDownload');
        $storagePath = '/naskah';
    
        if (!Storage::disk('outside')->exists($storagePath)) {
            Storage::disk('outside')->makeDirectory($storagePath);
        }
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $file->storeAs($storagePath, $fileName, 'outside');
        $pelaporan->link_download_naskah = $storagePath . '/' . $fileName;
    }

    $pelaporan->mou_start_date = $request->input('startDate');
    $pelaporan->mou_end_date = $request->input('endDate');
    $pelaporan->id_department_unair = $request->input('deptP');
    $pelaporan->text_department_unair = $dept;
    $pelaporan->department_partner = $request->input('partDept');
    $pelaporan->faculty_partner = $request->input('partnerFac');
    $pelaporan->program_study_partner = $request->input('partnerStuProg');
    $pelaporan->type_institution_partner = $request->input('typeP');
    $pelaporan->signatories_unair_name = $request->input('nosUnair');
    $pelaporan->signatories_unair_pos = $request->input('nopUnair');
    $pelaporan->current_role = 'KSLN';
    $pelaporan->signatories_partner_name = $request->input('nosPart');
    $pelaporan->signatories_partner_pos = $request->input('nopPart');
    $pelaporan->pic_mitra_nama = $request->input('namePic');
    $pelaporan->pic_mitra_jabatan = $request->input('postPic');
    $pelaporan->pic_mitra_email = $request->input('emailPic');
    $pelaporan->pic_mitra_phone = $request->input('telpPic');
    $pelaporan->pic_fak_nama = $request->input('namePic2');
    $pelaporan->pic_fak_jabatan = $request->input('postPic2');
    $pelaporan->pic_fak_email = $request->input('emailPic2');
    $pelaporan->pic_fak_phone = $request->input('telpPic2');


    if ($request->input('jenisP') == "Riset") {
        $pelaporan->source_funding = $request->input('sourceFund');
        $pelaporan->sum_funding = $request->input('sumFund');
    } else {
        $pelaporan->source_funding = "Universitas Airlangga";
    }

   
    $pelaporan->save();
    $pelaporan_id = $pelaporan->id;

    foreach ($request->input('partnerP', []) as $partnerId) {
        GrieMoaAcademicPelaporanPartner::updateOrCreate(
            ['id_moa_academic' => $pelaporan_id, 'id_partner_university' => (int) $partnerId],
            ['id_moa_academic' => $pelaporan_id, 'id_partner_university' => (int) $partnerId]
        );
    }

    foreach ($request->input('scopeP', []) as $scopeId) {
        GrieMoaAcademicPelaporanScope::updateOrCreate(
            ['id_moa_academic' => $pelaporan_id, 'id_collaboration_scope' => (int) $scopeId],
            ['id_moa_academic' => $pelaporan_id, 'id_collaboration_scope' => (int) $scopeId]
        );
    }

    foreach ($request->input('FacP', []) as $facultyId) {
        GrieMoaAcademicPelaporanFaculty::updateOrCreate(
            ['id_moa_academic' => $pelaporan_id, 'id_faculty' => (int) $facultyId],
            ['id_moa_academic' => $pelaporan_id, 'id_faculty' => (int) $facultyId]
        );
    }

    foreach ($request->input('stuProgP', []) as $programStudyId) {
        GrieMoaAcademicPelaporanProdi::updateOrCreate(
            ['id_moa_academic' => $pelaporan_id, 'id_program_study_unair' => (int) $programStudyId],
            ['id_moa_academic' => $pelaporan_id, 'id_program_study_unair' => (int) $programStudyId]
        );
    }
    // return redirect()->route('view_pelaporan');
    return response()->json(['status' => 'success', 'redirect' => route('view_pelaporan')]);
}

    public function  view_pelaporan(Request $request){

        if ($request->ajax()) {
        $data = DB::table('grie_moa_academic_pelaporan as t')
        ->select('t.*', 'fac.partner as partner', 'f.nama_ind as fakultas')
        ->where(function ($query) {
            $query->where('t.status', '=', 'Completed')
                  ->orWhere('t.status', '=', 'Progress'); 
        })
        ->leftjoinSub(
            DB::table('grie_moa_academic_pelaporan_partner')
                ->select(
                    'id_moa_academic',
                    DB::raw('STRING_AGG(u.name, \', \') AS partner')
                    // DB::raw('GROUP_CONCAT(u.name) AS partner')

                )
                ->leftjoin('m_university as u', 'u.id', '=', 'grie_moa_academic_pelaporan_partner.id_partner_university')
                ->groupBy('id_moa_academic')
                ->orderBy('id_moa_academic', 'DESC'),
            'fac',
            'fac.id_moa_academic',
            '=',
            't.id'
        )
        ->leftjoin('m_fakultas_unit as f', 'f.id', '=', 't.id_fakultas')
        ->when(!Auth::user()->hasRole('gpc'), function ($query) {
            $query->where('t.created_by', '=', Auth::user()->id);
        });

        if ($request->has('order')) {
            foreach ($request->order as $order) {
                $columnIndex = $order['column']; 
                $columnDir = $order['dir'];
                $columnName = $request->columns[$columnIndex]['data']; 
        
                if ($columnName === 'approval_pelaporan') {
                    $data->orderByRaw("
                        CASE 
                            WHEN approval_pelaporan = true THEN 1
                            WHEN approval_pelaporan = false AND approval_status IS NULL THEN 2
                            WHEN approval_status = 'NEED REVISE' OR approval_status = 'REVISE' THEN 3
                            WHEN approval_status = 'REJECTED' OR approval_status = 'REJECT' THEN 4
                            ELSE 5
                        END $columnDir
                    ");
                } else {
                    $data->orderBy($columnName, $columnDir);
                }
            }       
        } else {
            $data->orderBy('created_date', 'desc'); 
        }

        return DataTables::of($data)
        ->editColumn('mou_end_date', function($item){
            if($item->mou_end_date && $item->mou_end_date != "0000-00-00"){
                return $item->mou_end_date;
              }else{
                return $item->text_end_date;
              }
          })
          ->editColumn('approval_pelaporan', function($item){
            if($item->approval_pelaporan || $item->approval_pelaporan == 1){
                return '<button type="submit" class="btn btn-success btn-sm">APPROVED</button>';
            }elseif((!$item->approval_pelaporan || $item->approval_pelaporan == 0) && $item->approval_status == null){
                return '<button type="submit" class="btn btn-dark btn-sm">SUBMITTED</button>';
            }elseif($item->approval_status == 'NEED REVISE' || $item->approval_status == 'REVISE' ){
                return '<button type="submit" class="btn btn-warning btn-sm">NEED REVISE</button>' . 
                       (!empty($item->approval_note) ? '<button type="button" class="btn btn-info btn-sm viewRevisionButton" data-revision="' . htmlspecialchars($item->approval_note) . '"><i class="fa fa-eye"></i></button>' : '');
            }elseif($item->approval_status == 'REJECTED' || $item->approval_status == 'REJECT'){
                return '<button type="submit" class="btn btn-danger btn-sm">REJECTED</button>' . 
                       (!empty($item->approval_note) ? '<button type="button" class="btn btn-info btn-sm viewRevisionButton" data-revision="' . htmlspecialchars($item->approval_note) . '"><i class="fa fa-eye"></i></button>' : '');
            }
        })        
          ->addColumn('edit', function ($item) {
            return '<form action="' . route('pelaporan.edit', ['id' => $item->id]) . '" method="GET" style="display:inline;">
                        <button class="btn btn-success btn-sm" type="submit" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                    </form>';
        })
        ->addColumn('delete', function ($item) {
            return '<form id="deleteForm' . $item->id . '" action="' . route('pelaporan.destroy', ['id' => $item->id]) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\'' . $item->id . '\', \'Data yang telah dihapus tidak dapat dipulihkan\')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>';
        })
        ->filterColumn('mou_end_date', function($query, $keyword) {
            $sql = "
                (CASE 
                    WHEN mou_end_date IS NOT NULL THEN mou_end_date::TEXT
                    ELSE text_end_date
                END) ILIKE ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        })        
        ->filterColumn('approval_pelaporan', function($query, $keyword) {
            $query->whereRaw("
                (CASE 
                    WHEN approval_pelaporan = true THEN 'APPROVED'
                    WHEN approval_pelaporan = false AND approval_status IS NULL THEN 'SUBMITTED'
                    WHEN approval_status IN ('NEED REVISE', 'REVISE') THEN 'NEED REVISE'
                    WHEN approval_status IN ('REJECTED', 'REJECT') THEN 'REJECTED'
                    ELSE 'UNKNOWN'
                END) ILIKE ?", ["%{$keyword}%"]);
        })        
        ->filter(function ($query) use ($request) {
            if ($request->has('search') && $request->input('search.value')) {
                $searchValue = $request->input('search.value');
                $query->where(function ($q) use ($searchValue) {
                    $q->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('partner', 'like', "%{$searchValue}%")
                    // ->orWhere('approval_pelaporan', 'like', "%{$searchValue}%")
                    ->orWhere('f.nama_ind', 'like', "%{$searchValue}%")
                    ->orWhere('jenis_naskah', 'like', "%{$searchValue}%")
                    ->orWhere('mou_start_date', 'like', "%{$searchValue}%")
                    ->orWhere('created_date', 'like', "%{$searchValue}%");
                });
            }
        })
        ->rawColumns(['approval_pelaporan', 'edit', 'delete']) 
        // ->orderColumns(['mou_started_date', 'mou_end_date'], '-:column $1')
        ->make(true);
    }
        return view('agreement.view_pelaporan');
    }

    public function approve_pelaporan(Request $request, $id){
        
        $pelaporan = GrieMoaAcademicPelaporan::find($id);
    
        $partnerId = GrieMoaAcademicPelaporanPartner::where('id_moa_academic', $pelaporan->id)->get()->toArray();
        $facultyId = GrieMoaAcademicPelaporanFaculty::where('id_moa_academic', $pelaporan->id)->get()->toArray();
        $programStudyId = GrieMoaAcademicPelaporanProdi::where('id_moa_academic', $pelaporan->id)->get()->toArray();
        $scopeId = GrieMoaAcademicPelaporanScope::where('id_moa_academic', $pelaporan->id)->get()->toArray();

        $pelaporan->approval_pelaporan = 1;
        $pelaporan->approval_status = null;
        $pelaporan->save();
        
        $newMoa = new GrieMoaAcademic;
        
        $newMoa->fill(Arr::except($pelaporan->getAttributes(), ['id']));
        $newMoa->status = 'Completed';
        $newMoa->status_lapkerma = 'BELUM';
        $newMoa->age_archive_sn = GrieMoaAcademic::generateNumber();
        $newMoa->lapkerma_archive = $newMoa->age_archive_sn.'.KSLN';
        $newMoa->year = date('Y', strtotime($newMoa->mou_start_date));
        $newMoa->link_pelaporan = '';
        $newMoa->status_pelaporan_lapkerma = 'Belum';
        $new_moa_id = $newMoa->id;

        $newMoa->save();
            $new_moa_id = $newMoa->id;
            foreach ($partnerId as $partnerIds) {
                GrieMoaAcademicPartner::updateOrCreate(
                    ['id_moa_academic' => $new_moa_id, 'id_partner_university' => (int) $partnerIds['id_partner_university']],
                    ['id_moa_academic' => $new_moa_id, 'id_partner_university' => (int) $partnerIds['id_partner_university']]
                );
            }
            
            foreach ($scopeId as $scopeIds) {
                GrieMoaAcademicScope::updateOrCreate(
                    ['id_moa_academic' => $new_moa_id, 'id_collaboration_scope' => (int) $scopeIds['id_collaboration_scope']],
                    ['id_moa_academic' => $new_moa_id, 'id_collaboration_scope' => (int) $scopeIds['id_collaboration_scope']]
                );
            }
            
            foreach ($facultyId as $facultyIds) {
                GrieMoaAcademicFaculty::updateOrCreate(
                    ['id_moa_academic' => $new_moa_id, 'id_faculty' => (int) $facultyIds['id_faculty']],
                    ['id_moa_academic' => $new_moa_id, 'id_faculty' => (int) $facultyIds['id_faculty']]
                );
            }
            
            foreach ($programStudyId as $programStudyIds) {
                GrieMoaAcademicProdi::updateOrCreate(
                    ['id_moa_academic' => $new_moa_id, 'id_program_study_unair' => (int) $programStudyIds['id_program_study_unair']],
                    ['id_moa_academic' => $new_moa_id, 'id_program_study_unair' => (int) $programStudyIds['id_program_study_unair']]
                );
            }

        // return redirect()->route('view_database');
        return response()->json(['status' => 'success', 'redirect' => route('view_database')]);
    }

    public function reject_pelaporan(Request $request, $id){
        $validated = $request->validate([
            'revision_note' => 'required|string|max:255'
        ]);

        $pelaporan = GrieMoaAcademicPelaporan::find($id);
        $pelaporan->approval_pelaporan = 0;
        $pelaporan->approval_status = "REJECTED";
        $pelaporan->approval_note = $request->input('revision_note');
        $pelaporan->save();
        // return redirect()->route('view_pelaporan');
        return response()->json(['status' => 'success', 'redirect' => route('view_pelaporan')]);
    }

    public function revise_pelaporan(Request $request, $id){

        $validated = $request->validate([
            'revision_note' => 'required|string|max:255'
        ]);

        $pelaporan = GrieMoaAcademicPelaporan::find($id);
        $pelaporan->approval_pelaporan = 0;
        $pelaporan->approval_status = "NEED REVISE";
        $pelaporan->approval_note = $request->input('revision_note');
        $pelaporan->save();
      
        // return redirect()->route('view_pelaporan');
        return response()->json(['status' => 'success', 'redirect' => route('view_pelaporan')]);

    }

    public function database_agreement(){
        $data = DB::table('grie_moa_academic as g')
            ->select('g.*', 'c.name as country', 'f.nama_eng as fakultas', 'dep.nama_eng as department_unair')
            ->leftJoin('m_country as c', 'c.id', '=', 'g.id_country')
            ->leftJoin('m_fakultas_unit as f', 'f.id', '=', 'g.id_fakultas')
            ->leftJoin('m_departemen as dep', 'dep.id', '=', 'g.id_department_unair')
            ->where(function($query) {
                $query->where('g.status', '=', 'Completed')
                      ->orWhere('g.status', '=', 'Progress');
            })  
            ->whereNotNull('g.link_download_naskah')  
            ->where('g.link_download_naskah', '!=', '')  // K
            ->orderByDesc('lapkerma_archive')
            ->get();

        $result = DB::table('grie_moa_academic_scope as gs')
                ->select(
                    'gs.id_moa_academic',
                    // DB::raw('GROUP_CONCAT(DISTINCT u2.name) AS partner_involved'),
                    // DB::raw('GROUP_CONCAT(DISTINCT p.name_eng) AS prodi_involved'),
                    // DB::raw('GROUP_CONCAT(DISTINCT fu.nama_eng) AS faculty_involved'),
                    // DB::raw('GROUP_CONCAT(DISTINCT cs.name) AS collaboration_scope')
                    DB::raw('STRING_AGG(DISTINCT u2.name, \', \') AS partner_involved'),
                    DB::raw('STRING_AGG(DISTINCT p.name_eng, \', \') AS prodi_involved'),
                    DB::raw('STRING_AGG(DISTINCT fu.nama_eng, \', \') AS faculty_involved'),
                    DB::raw('STRING_AGG(DISTINCT cs.name, \', \') AS collaboration_scope'),

                )
                ->leftJoin('grie_moa_academic_prodi as gap', 'gap.id_moa_academic', '=', 'gs.id_moa_academic')
                ->leftJoin('m_prodi as p', 'p.id', '=', 'gap.id_program_study_unair')
                ->leftJoin('grie_moa_academic_faculty as maf', 'maf.id_moa_academic', '=', 'gs.id_moa_academic')
                ->leftJoin('m_fakultas_unit as fu', 'fu.id', '=', 'maf.id_faculty')
                ->leftJoin('grie_moa_academic_partner as gapn', 'gapn.id_moa_academic', '=', 'gs.id_moa_academic')
                ->leftJoin('m_university as u2', 'u2.id', '=', 'gapn.id_partner_university')
                ->leftJoin('m_collaboration_scope as cs', 'cs.id', '=', 'gs.id_collaboration_scope')
                ->groupBy('gs.id_moa_academic')
                ->orderByDesc('gs.id_moa_academic')
                ->get();

                $merged = $data->map(function ($item) use ($result) {
              
                    $relatedData = $result->firstWhere('id_moa_academic', $item->id);

                    $item->partner_involved = $relatedData ? $relatedData->partner_involved : null;
                    $item->prodi_involved = $relatedData ? $relatedData->prodi_involved : null;
                    $item->faculty_involved = $relatedData ? $relatedData->faculty_involved : null;
                    $item->collaboration_scope = $relatedData ? $relatedData->collaboration_scope : null;
                
                    return $item;
                });

        
        return view('agreement.view_database', compact( 'merged'));
    }

    public function destroy_database_agreement($id){
       

        try {
            $del = GrieMoaAcademic::where("id", $id)->first();
            $del->delete();  
    
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data.'
            ]);
        }

    }

    public function upload_bukti($id){

        $data = GrieMoaAcademic::find($id);
        return view('agreement.upload_pelaporan', compact('data'));

    }


    public function store_bukti(Request $request, $id){

        $agreement = GrieMoaAcademic::find($id);

        try {
            $request->validate([
                'buktiP' => 'required|string', 
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Please select valid value!'], 500);
        }

        if ($request->hasFile('linkPelaporan')) {
            $file = $request->file('linkPelaporan');

            try {
                $request->validate([
                    'linkPelaporan' => 'required|file|mimes:pdf|max:1024', 
                ]);
            } catch (ValidationException $e) {
                return response()->json(['status' => 'error', 'message' => 'Please upload a PDF file smaller than 1 MB!'], 500);
            }

            $storagePath = '/naskah';

            if (!Storage::disk('outside')->exists($storagePath)) {
                Storage::disk('outside')->makeDirectory($storagePath);
            }
    
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs($storagePath, $fileName, 'outside');
    
            // Perbarui hanya kolom link_pelaporan
            $agreement->update([
                'link_pelaporan' => $storagePath . '/' . $fileName,
            ]);
        }
        // dd( $request->input('buktiP'));

        $agreement->update([
            'status_pelaporan_lapkerma' => $request->input('buktiP')
        ]);


        // return redirect()->route('view_database');
        return response()->json(['status' => 'success', 'redirect' => route('view_database')]);
    }

    public function generate_number(){
        $year = date('Y');
        $lastRecord = DB::table('grie_moa_academic')
            ->where('age_archive_sn', 'like', $year . '%')
            ->orderBy('age_archive_sn', 'desc')
            ->first();
    
        if ($lastRecord && isset($lastRecord->age_archive_sn)) {
            $lastNumber = explode('.', $lastRecord->age_archive_sn)[1];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
    
        $result = $year . '.' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return $result;
    }

    public function email_list(){
        
        return view('agreement.email_list');
    }


    public function  completed_agreement(){

        return view('agreement.view_completed');
    }

    public function  view_pelaporan2(){

        $data = DB::table('grie_moa_academic_pelaporan as t')
        ->select('t.*', 'fac.partner as partner', 'f.nama_ind as fakultas')
        ->where(function ($query) {
            $query->where('t.status', '=', 'Completed')
                  ->orWhere('t.status', '=', 'Progress'); 
        })
        ->leftjoinSub(
            DB::table('grie_moa_academic_pelaporan_partner')
                ->select(
                    'id_moa_academic',
                    // DB::raw('GROUP_CONCAT(u.name) AS partner')
                    DB::raw('STRING_AGG(u.name, \', \') AS partner')
                )
                ->leftjoin('m_university as u', 'u.id', '=', 'grie_moa_academic_pelaporan_partner.id_partner_university')
                ->groupBy('id_moa_academic')
                ->orderBy('id_moa_academic', 'DESC'),
            'fac',
            'fac.id_moa_academic',
            '=',
            't.id'
        )
        ->leftjoin('m_fakultas_unit as f', 'f.id', '=', 't.id_fakultas')
        ->when(!Auth::user()->hasRole('gpc'), function ($query) {
            $query->where('t.created_by', '=', Auth::user()->id);
        })
        ->orderBy('created_date', 'desc')
        ->get();

        return view('agreement.arsip_view_pelaporan', compact('data'));}
    
}