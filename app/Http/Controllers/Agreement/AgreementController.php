<?php

namespace App\Http\Controllers\agreement;
use App\Http\Controllers\Controller;
use App\Models\GrieMoaAcademicPelaporan;
use App\Models\GrieMoaAcademicPelaporanFaculty;
use App\Models\GrieMoaAcademicPelaporanPartner;
use App\Models\GrieMoaAcademicPelaporanProdi;
use App\Models\GrieMoaAcademicPelaporanScope;
use App\Models\GrieMoaAcademic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AgreementController extends Controller
{
    public function tambah_pelaporan($id = null){

        $univ = DB::table('m_university')
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

        return view('agreement.form_pelaporan', compact('data', 'univ', 'unit','country', 'scope', 'department', 'prodi'));
    }


public function destroy_pelaporan($id){
        $del = GrieMoaAcademicPelaporan::where("ID", $id)->first();

        if ($del) {
            $del->delete();  
            return redirect()->route('view_pelaporan');
        }        
} 

public function store_pelaporan(Request $request, $id = null) {
    $country = DB::table("m_country")
        ->where("id", "=", $request->input('countryP'))
        ->pluck("m_country.name")->first();

    $dept = DB::table("m_departemen")
        ->where("id", "=", $request->input('deptP'))
        ->pluck("nama_ind")->first();
    

    if ($id) {
        $pelaporan = GrieMoaAcademicPelaporan::findOrFail($id); 
    } else {
        $pelaporan = new GrieMoaAcademicPelaporan();
    }

    // Assign nilai ke model
    $pelaporan->id_dosen = 0;
    $pelaporan->id_partner_university = 0;
    $pelaporan->tipe_moa = $request->input('jenisP');
    $pelaporan->kategori_tridharma = $request->input('triDharma');
    $pelaporan->kategori = $request->input('jenisP');
    $pelaporan->id_country = $request->input('countryP');
    $pelaporan->text_country = $country;
    $pelaporan->id_fakultas = $request->input('unitP');
    $pelaporan->text_end_date = "Automatically Renewed";
    $pelaporan->jenis_naskah = $request->input('docP');
    $pelaporan->title = $request->input('tittleP');
    $pelaporan->link_download_naskah = $request->input('linkDownload');
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
    $pelaporan->approval_pelaporan = 0;

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

    session()->flash('success', 'Data berhasil disimpan!');
    return redirect()->route('view_pelaporan');
}

    public function  view_pelaporan(){

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
                    DB::raw('GROUP_CONCAT(u.name) AS partner')
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
        ->orderBy('created_date', 'desc')
        ->get();

        return view('agreement.view_pelaporan', compact('data'));
    }

    public function approve_pelaporan(Request $request, $id){
        $pelaporan = GrieMoaAcademicPelaporan::find($id);
        $pelaporan->approval_pelaporan = 1;
        $pelaporan->approval_status = null;
        $pelaporan->save();
        return redirect()->route('view_pelaporan');
    }

    public function reject_pelaporan(Request $request, $id){
        $pelaporan = GrieMoaAcademicPelaporan::find($id);
        $pelaporan->approval_pelaporan = 0;
        $pelaporan->approval_status = "REJECTED";
        $pelaporan->approval_note = $request->input('notes');
        $pelaporan->save();
        return redirect()->route('view_pelaporan');
    }

    public function revise_pelaporan(Request $request, $id){
        $pelaporan = GrieMoaAcademicPelaporan::find($id);
        $pelaporan->approval_pelaporan = 0;
        $pelaporan->approval_status = "NEED REVISE";
        $pelaporan->approval_note = $request->input('notes');
        $pelaporan->save();
        return redirect()->route('view_pelaporan');
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
            })  // Kondisi untuk status Completed atau Progress
            ->whereNotNull('g.link_download_naskah')  // Kondisi untuk memastikan link_download_naskah tidak null
            ->where('g.link_download_naskah', '!=', '')  // K
            ->get();

        $result = DB::table('grie_moa_academic_scope as gs')
                ->select(
                    'gs.id_moa_academic',
                    DB::raw('GROUP_CONCAT(DISTINCT u2.name) AS partner_involved'),
                    DB::raw('GROUP_CONCAT(DISTINCT p.name_eng) AS prodi_involved'),
                    DB::raw('GROUP_CONCAT(DISTINCT fu.nama_eng) AS faculty_involved'),
                    DB::raw('GROUP_CONCAT(DISTINCT cs.name) AS collaboration_scope')
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
        $del = GrieMoaAcademic::where("ID", $id)->first();

        if ($del) {
            $del->delete();  
            return redirect()->route('view_database');
        }        

    }

    public function email_list(){
        
        return view('agreement.email_list');
    }

    public function review_agreement(){

    
        return view('agreement.view_review');
    }
    public function  completed_agreement(){

        return view('agreement.view_completed');
    }

    
}
