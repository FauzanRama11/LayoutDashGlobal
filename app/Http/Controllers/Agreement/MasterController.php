<?php

namespace App\Http\Controllers\agreement;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GrieMoaAcademic;
use App\Models\GrieMoaAcademicFaculty;
use App\Models\GrieMoaAcademicPartner;
use App\Models\GrieMoaAcademicProdi;
use App\Models\GrieMoaAcademicScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MasterController extends Controller
{
    public function tambah_master_database($id = null){

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

        $data = $id ? GrieMoaAcademic::findOrFail($id) : null;
        if ($data !== null) {
            $selPartners = GrieMoaAcademicPartner::where('id_moa_academic', $data->id)->get()->toArray();
            $selPartners = array_column($selPartners, 'id_partner_university'); 
            $selFaculties = GrieMoaAcademicFaculty::where('id_moa_academic', $data->id)->get()->toArray();
            $selFaculties = array_column($selFaculties, 'id_faculty'); 
            $selScopes = GrieMoaAcademicScope::where('id_moa_academic', $data->id)->get()->toArray();
            $selScopes = array_column($selScopes, 'id_collaboration_scope'); 
            $selProdis = GrieMoaAcademicProdi::where('id_moa_academic', $data->id)->get()->toArray();
            $selProdis = array_column($selProdis, column_key: 'id_program_study_unair'); 

            return view('agreement.form_master_database', compact('data', 'selPartners', 'selFaculties', 'selScopes', 'selProdis', 'univ', 'unit','country', 'scope', 'department', 'prodi'));
        }else{
                return view('agreement.form_master_database', compact('data', 'univ', 'unit','country', 'scope', 'department', 'prodi'));
    }

        }

    public function database_agreement(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('grie_moa_academic as g')
                ->select([
                    'g.*',
                    'c.name as country',
                    'f.nama_eng as fakultas',
                    'dep.nama_eng as department_unair',
                ])
                ->leftJoin('m_country as c', 'c.id', '=', 'g.id_country')
                ->leftJoin('m_fakultas_unit as f', 'f.id', '=', 'g.id_fakultas')
                ->leftJoin('m_departemen as dep', 'dep.id', '=', 'g.id_department_unair')
                ->where(function ($query) {
                    $query->where('g.status', '=', 'Completed')
                          ->orWhere('g.status', '=', 'Progress');
                })
                ->whereNotNull('g.link_download_naskah')
                ->where('g.link_download_naskah', '!=', '');

                if ($request->has('start_date') && $request->has('end_date')) {
                    $data->whereBetween('mou_start_date', [$request->start_date, $request->end_date]);
                }

                if ($request->has('order')) {
                    foreach ($request->order as $order) {
                        $columnIndex = $order['column']; 
                        $columnDir = $order['dir'];
                        $columnName = $request->columns[$columnIndex]['data']; 

                        if ($columnName == 'status') {
                            $data->orderByRaw("
                                CASE 
                                    WHEN mou_end_date IS NOT NULL AND mou_end_date < NOW() THEN 1
                                    WHEN mou_end_date IS NOT NULL THEN 2
                                    ELSE 3
                                END $columnDir
                            ");
                        } elseif ($columnName == 'area_collab') {
                            $data->orderByRaw("
                                CASE 
                                    WHEN link_pelaporan IS NOT NULL AND link_pelaporan <> '' THEN 1
                                    ELSE 2
                                END $columnDir
                            ");
                        } else {
                            $data->orderBy($columnName, $columnDir);
                        }  
                    }       
                } else {
                    $data->orderBy('lapkerma_archive', 'desc'); 
                }
                
                $total = $data->count();
            
            return DataTables::of($data)
            ->editColumn('link_download_naskah', function($item){
                if (strtotime($item->created_date) >= strtotime("2025-01-09 14:54:42")) {
                    $url = route('view_naskah.pdf', basename($item->link_download_naskah));
                } else {
                    $url = $item->link_download_naskah;
                }
            
                return '<a href="' . $url . '" target="_blank" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                        </a>
                        <span style="display: none;">' . $url . '</span>';
            })
            ->editColumn('mou_end_date', function($item){
              if($item->mou_end_date && $item->mou_end_date != "0000-00-00"){
                return $item->mou_end_date;
              }else{
                return $item->text_end_date;
              }
            })
            ->editColumn('status', function ($item) {
                // Cek apakah mou_end_date valid
                if ($item->mou_end_date && $item->mou_end_date !== "0000-00-00") {
                    if (\Carbon\Carbon::parse($item->mou_end_date)->isPast()) {
                        return '<button class="btn btn-danger btn-sm">Expired</button>';
                    } else {
                        return '<button class="btn btn-success btn-sm">Active</button>';
                    }
                } else {
                    return '<button class="btn btn-success btn-sm">Active</button>';
                }
            })
            ->editColumn('status_lapkerma', function ($item) {
                if ($item->status_lapkerma == "SUDAH") {
                    return '<button class="btn btn-success btn-sm">Sudah</button>';
                } elseif ($item->status_lapkerma == "BELUM") {
                    return '<button class="btn btn-danger btn-sm">Belum</button>';
                } else {
                    return '<button class="btn btn-warning btn-sm">Unknown</button>';
                }
            })
            ->editColumn('link_partnership_profile', function($item){
                $url = $item->link_partnership_profile;
                return '<a href="' . $url . '" target="_blank" class="btn btn-primary">
                            <i class="fa fa-external-link-square"></i>
                        </a>
                        <span style="display: none;">' . $url . '</span>';
            })
            ->editColumn('website_lapkerma', function($item){
                $url = $item->website_lapkerma;
                return '<a href="' . $url . '" target="_blank" class="btn btn-primary">
                            <i class="fa fa-external-link-square"></i>
                        </a>
                        <span style="display: none;">' . $url . '</span>';
            })
            ->editColumn('area_collab', function ($item) {
                if (!empty($item->link_pelaporan)) {
                    return '<button class="btn btn-success btn-sm">Sudah</button>';
                } else {
                    return '<button class="btn btn-danger btn-sm">Belum</button>';
                }
            })
            ->editColumn('status_pelaporan_lapkerma', function ($item) {
                if ($item->status_pelaporan_lapkerma == "Sudah") {
                    return '<button class="btn btn-success btn-sm">Sudah</button>';
                } elseif ($item->status_pelaporan_lapkerma == "Belum") {
                    return '<button class="btn btn-danger btn-sm">Belum</button>';
                } else {
                    return '<button class="btn btn-warning btn-sm">Unknown</button>';
                }
            })
            ->editColumn('add_bukti', function($item) {
                $buttons = '';
                if ($item->created_by == Auth::user()->id || Auth::user()->hasRole('gpc')) {
                    $buttons .= '<form action="' . route('bukti.upload', ['id' => $item->id]) . '" method="GET" style="display:inline;">
                                    <button class="btn btn-warning btn-sm" type="submit" data-toggle="tooltip" data-placement="top" title="Upload">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                </form>';
                }
                return $buttons; 
            })
            ->editColumn('link_pelaporan', function ($item) {
                if (!empty($item->link_pelaporan)) {
                    $url = route('view_naskah.pdf', basename($item->link_pelaporan));
                    return '<a href="' . $url . '" target="_blank" class="btn btn-primary">
                                <i class="fa fa-download"></i>
                            </a>
                            <span style="display: none;">' . $url . '</span>';
                }
            })
            ->addColumn('edit', function ($item) {
                if (Auth::user()->hasRole('gpc')) {
                return '<form action="' . route('master_database.edit', ['id' => $item->id]) . '" method="GET" style="display:inline;">
                            <button class="btn btn-success btn-sm" type="submit" data-toggle="tooltip" data-placement="top" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                        </form>';
                }
            })
            ->addColumn('delete', function ($item) {
                if (Auth::user()->hasRole('gpc')) {
                return '<form id="deleteForm' . $item->id . '" action="' . route('database_agreement.destroy', ['id' => $item->id]) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\'' . $item->id . '\', \'Data yang telah dihapus tidak dapat dipulihkan\')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>';
                    }
            })
                ->addColumn('partner_involved', function ($item) {
                    $result = DB::table('grie_moa_academic_partner as gs')
                        // ->select(DB::raw('GROUP_CONCAT(DISTINCT u2.name) AS partner_involved'))
                        ->select(DB::raw('STRING_AGG(DISTINCT u2.name, \', \') AS partner_involved'))
                        ->leftJoin('m_university as u2', 'u2.id', '=', 'gs.id_partner_university')
                        ->where('gs.id_moa_academic', operator: $item->id)
                        ->groupBy('gs.id_moa_academic')
                        ->first();

                    return $result ? $result->partner_involved : null;
                })
                ->addColumn('prodi_involved', function ($item) {
                    $result = DB::table('grie_moa_academic_prodi as gs')
                        // ->select(DB::raw('GROUP_CONCAT(DISTINCT p.name_eng) AS prodi_involved'))
                        ->select(DB::raw('STRING_AGG(DISTINCT p.name_eng, \', \') AS prodi_involved'))
                        ->leftJoin('m_prodi as p', 'p.id', '=', 'gs.id_program_study_unair')
                        ->where('gs.id_moa_academic', $item->id)
                        ->groupBy('gs.id_moa_academic')
                        ->first();

                    return $result ? $result->prodi_involved : null;
                })
                ->addColumn('faculty_involved', function ($item) {
                    $result = DB::table('grie_moa_academic_faculty as gs')
                        // ->select(DB::raw('GROUP_CONCAT(DISTINCT fu.nama_eng) AS faculty_involved'))
                        ->select(DB::raw('STRING_AGG(DISTINCT fu.nama_eng, \', \') AS faculty_involved'))
                        ->leftJoin('m_fakultas_unit as fu', 'fu.id', '=', 'gs.id_faculty')
                        ->where('gs.id_moa_academic', $item->id)
                        ->groupBy('gs.id_moa_academic')
                        ->first();

                    return $result ? $result->faculty_involved : null;
                })
                ->addColumn('collaboration_scope', function ($item) {
                    $result = DB::table('grie_moa_academic_scope as gs')
                        // ->select(DB::raw('GROUP_CONCAT(DISTINCT cs.name) AS collaboration_scope'))
                        ->select(DB::raw('STRING_AGG(DISTINCT cs.name, \', \') AS collaboration_scope'))
                        ->leftJoin('m_collaboration_scope as cs', 'cs.id', '=', 'gs.id_collaboration_scope')
                        ->where('gs.id_moa_academic', $item->id)
                        ->groupBy('gs.id_moa_academic')
                        ->first();

                    return $result ? $result->collaboration_scope : null;
                })
                ->filterColumn('mou_end_date', function($query, $keyword) {
                    $sql = "
                        (CASE 
                            WHEN mou_end_date IS NOT NULL THEN mou_end_date::TEXT
                            ELSE text_end_date
                        END) ILIKE ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })        
                ->filterColumn('partner_involved', function ($query, $keyword) {
                    $sql = "EXISTS (
                        SELECT 1 
                        FROM grie_moa_academic_partner AS gs
                        LEFT JOIN m_university AS u2 ON u2.id = gs.id_partner_university
                        WHERE gs.id_moa_academic = g.id
                        AND u2.name ILIKE ?
                    )";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('prodi_involved', function ($query, $keyword) {
                    $sql = "EXISTS (
                        SELECT 1 
                        FROM grie_moa_academic_prodi AS gs
                        LEFT JOIN m_prodi AS p ON p.id = gs.id_program_study_unair
                        WHERE gs.id_moa_academic = g.id
                        AND p.name_eng ILIKE ?
                    )";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('faculty_involved', function ($query, $keyword) {
                    $sql = "EXISTS (
                        SELECT 1 
                        FROM grie_moa_academic_faculty AS gs
                        LEFT JOIN m_fakultas_unit AS fu ON fu.id = gs.id_faculty
                        WHERE gs.id_moa_academic = g.id
                        AND fu.nama_eng ILIKE ?
                    )";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('collaboration_scope', function ($query, $keyword) {
                    $sql = "EXISTS (
                        SELECT 1 
                        FROM grie_moa_academic_scope AS gs
                        LEFT JOIN m_collaboration_scope AS cs ON cs.id = gs.id_collaboration_scope
                        WHERE gs.id_moa_academic = g.id
                        AND cs.name ILIKE ?
                    )";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('status', function ($query, $keyword) {
                    $sql = "CASE 
                                WHEN mou_end_date IS NOT NULL AND mou_end_date < NOW() THEN 'Expired'
                                WHEN mou_end_date IS NOT NULL THEN 'Active'
                                ELSE 'Active'
                            END ILIKE ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('area_collab', function ($query, $keyword) {
                    $sql = "CASE 
                                WHEN link_pelaporan <> '' THEN 'Sudah' 
                                ELSE 'Belum' 
                            END ILIKE ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('status_pelaporan_lapkerma', function ($query, $keyword) {
                    $sql = "CASE 
                                WHEN status_pelaporan_lapkerma = 'Sudah' THEN 'Sudah' 
                                WHEN status_pelaporan_lapkerma = 'Belum' THEN 'Belum' 
                                ELSE 'Unknown' 
                            END ILIKE ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })                
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->input('search.value')) {
                        $searchValue = $request->input('search.value');
                        $query->where(function ($q) use ($searchValue) {
                            // $q->where('number', 'like', "%{$searchValue}%")
                            $q->where('c.name', 'like', "%{$searchValue}%")
                            // ->orWhere('partner_involved', 'like', "%{$searchValue}%")
                            ->orWhere('jenis_naskah', 'like', "%{$searchValue}%")
                            ->orWhere('title', 'like', "%{$searchValue}%")
                            ->orWhere('link_download_naskah', 'like', "%{$searchValue}%")
                            ->orWhere('mou_start_date', 'like', "%{$searchValue}%")
                            // ->orWhere('mou_end_date', 'like', "%{$searchValue}%")
                            ->orWhere('status_lapkerma', 'like', "%{$searchValue}%")
                            ->orWhere('category_document', 'like', "%{$searchValue}%")
                            ->orWhere('skema', 'like', "%{$searchValue}%")
                            ->orWhere('link_partnership_profile', 'like', "%{$searchValue}%")
                            ->orWhere('year', 'like', "%{$searchValue}%")
                            ->orWhere('age_archive_sn', 'like', "%{$searchValue}%")
                            ->orWhere('lapkerma_archive', 'like', "%{$searchValue}%")
                            ->orWhere('website_lapkerma', 'like', "%{$searchValue}%")
                            ->orWhere('f.nama_eng', 'like', "%{$searchValue}%")
                            ->orWhere('dep.nama_eng', 'like', "%{$searchValue}%")
                            // ->orWhere('prodi_involved', 'like', "%{$searchValue}%")
                            ->orWhere('faculty_partner', 'like', "%{$searchValue}%")
                            ->orWhere('department_partner', 'like', "%{$searchValue}%")
                            ->orWhere('program_study_partner', 'like', "%{$searchValue}%")
                            ->orWhere('type_institution_partner', 'like', "%{$searchValue}%")
                            ->orWhere('region', 'like', "%{$searchValue}%")
                            ->orWhere('type_grant', 'like', "%{$searchValue}%")
                            ->orWhere('source_funding', 'like', "%{$searchValue}%")
                            ->orWhere('sum_funding', 'like', "%{$searchValue}%")
                            ->orWhere('signatories_unair_name', 'like', "%{$searchValue}%")
                            ->orWhere('signatories_unair_pos', 'like', "%{$searchValue}%")
                            ->orWhere('signatories_partner_name', 'like', "%{$searchValue}%")
                            ->orWhere('signatories_partner_pos', 'like', "%{$searchValue}%")
                            // ->orWhere('collaboration_scope', 'like', "%{$searchValue}%")
                            ->orWhere('pic_mitra_nama', 'like', "%{$searchValue}%")
                            ->orWhere('pic_mitra_jabatan', 'like', "%{$searchValue}%")
                            ->orWhere('pic_mitra_email', 'like', "%{$searchValue}%")
                            ->orWhere('pic_mitra_phone', 'like', "%{$searchValue}%")
                            ->orWhere('pic_fak_nama', 'like', "%{$searchValue}%")
                            ->orWhere('pic_fak_jabatan', 'like', "%{$searchValue}%")
                            ->orWhere('pic_fak_email', 'like', "%{$searchValue}%")
                            ->orWhere('pic_fak_phone', 'like', "%{$searchValue}%");
                            // ->orWhere('faculty_involved', 'like', "%{$searchValue}%")
                        });
                    }
                })
                ->rawColumns(['number','link_download_naskah', 'link_partnership_profile', 'website_lapkerma', 'status_lapkerma', 
                            'status','area_collab', 'status_pelaporan_lapkerma', 'add_bukti', 'link_pelaporan', 'edit', 'delete']) 
                ->orderColumns(['parnter_involved', 'faculty_involved'], '-:column $1')
                ->make(true);
        }

        return view('agreement.view_database');
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

    public function store_master_database(Request $request, $id = null) {
        try {
            if($request->input('docP') != "MoU"){
            $request->validate([
                'jenisP' => 'required|string', 
                'queueP' => 'required|string', 
                'triDharma' => 'required|string', 
                'statusLapkerma' => 'required|string', 
                'unitP' => 'required|integer', 
                'countryP' => 'required|integer', 
                'partnerP' => 'required|array', 
                'partnerP.*' => 'integer',
                'docP' => 'required|string', 
                'tittleP' => 'required|string|max:255', 
                'scopeP' => 'required|array', 
                'scopeP.*' => 'integer', 
                'startDate' => 'required|date', 
                'endDate' => 'required|date|', 
                'catNaskah' => 'required|string', 
                'skema' => 'required|string', 
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
                'queueP' => 'required|string', 
                'triDharma' => 'required|string', 
                'statusLapkerma' => 'required|string', 
                'unitP' => 'required|integer', 
                'countryP' => 'required|integer', 
                'partnerP' => 'required|array', 
                'partnerP.*' => 'integer',
                'docP' => 'required|string', 
                'tittleP' => 'required|string|max:255', 
                'scopeP' => 'required|array', 
                'scopeP.*' => 'integer', 
                'startDate' => 'required|date', 
                'endDate' => 'required|date|', 
                'catNaskah' => 'required|string', 
                'skema' => 'required|string', 
                'deptP' => 'required|integer', 
                // 'FacP' => 'required|array', 
                // 'FacP.*' => 'integer', 
                // 'stuProgP' => 'required|array', 
                // 'stuProgP.*' => 'integer',
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
            $agreement = GrieMoaAcademic::findOrFail($id); 
            GrieMoaAcademicFaculty::where("id_moa_academic", $id)->delete();
            GrieMoaAcademicProdi::where("id_moa_academic", $id)->delete();
            GrieMoaAcademicScope::where("id_moa_academic", $id)->delete();
            GrieMoaAcademicPartner::where("id_moa_academic", $id)->delete();
    
        } else {
            $agreement = new GrieMoaAcademic();
            $agreement->created_by = Auth::user()->id;
            if(Auth::user()->hasRole('gpc')){
                $agreement->current_role = 'KSLN';
                $agreement->current_iterasi = 1;
                $agreement->age_archive_sn = GrieMoaAcademic::generateNumber();        
                $agreement->lapkerma_archive = $agreement->age_archive_sn.'.KSLN';
                $agreement->link_pelaporan = null;
            }
        }
        $agreement->current_id_status = 0;
        $agreement->id_dosen = null;
        $agreement->created_date = now();
        $agreement->id_partner_university = null;
        $agreement->tipe_moa = $request->input('jenisP');
        $agreement->kategori_tridharma = $request->input('triDharma');
        $agreement->kategori = $request->input('jenisP');
        $agreement->id_country = $request->input('countryP');
        $agreement->text_country = $country;
        $agreement->id_fakultas = $request->input('unitP');
        $agreement->text_end_date = "Automatically Renewed";
        $agreement->jenis_naskah = $request->input('docP');
        $agreement->title = $request->input('tittleP');
        if ($request->hasFile('linkDownload')) {
            $file = $request->file('linkDownload');
            $storagePath = '/naskah';
        
            if (!Storage::disk('outside')->exists($storagePath)) {
                Storage::disk('outside')->makeDirectory($storagePath);
            }
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs($storagePath, $fileName, 'outside');
            $agreement->link_download_naskah = $storagePath . '/' . $fileName;
        }
        $agreement->mou_start_date = $request->input('startDate');
        $agreement->mou_end_date = $request->input('endDate');
        $agreement->id_department_unair = $request->input('deptP');
        $agreement->text_department_unair = $dept;
        $agreement->department_partner = $request->input('partDept');
        $agreement->faculty_partner = $request->input('partnerFac');
        $agreement->program_study_partner = $request->input('partnerStuProg');
        $agreement->type_institution_partner = $request->input('typeP');
        $agreement->signatories_unair_name = $request->input('nosUnair');
        $agreement->signatories_unair_pos = $request->input('nopUnair');
        $agreement->signatories_partner_name = $request->input('nosPart');
        $agreement->signatories_partner_pos = $request->input('nopPart');
        $agreement->pic_mitra_nama = $request->input('namePic');
        $agreement->pic_mitra_jabatan = $request->input('postPic');
        $agreement->pic_mitra_email = $request->input('emailPic');
        $agreement->pic_mitra_phone = $request->input('telpPic');
        $agreement->pic_fak_nama = $request->input('namePic2');
        $agreement->pic_fak_jabatan = $request->input('postPic2');
        $agreement->pic_fak_email = $request->input('emailPic2');
        $agreement->pic_fak_phone = $request->input('telpPic2');
        $agreement->is_queue = $request->input('queueP');
        $agreement->status_lapkerma = $request->input('statusLapkerma');
        $agreement->category_document = $request->input(key: 'catNaskah');
        $agreement->skema = $request->input('skema');
        $agreement->status = 'Completed';
        // $agreement->status_lapkerma = 'BELUM';
        $agreement->year = date('Y', strtotime($agreement->mou_start_date));
        $agreement->status_pelaporan_lapkerma = "Belum";
        $agreement->current_id_status = 0;
    
    
        $agreement->save();
        $agreement_id = $agreement->id;
    
        foreach ($request->input('partnerP', []) as $partnerId) {
            GrieMoaAcademicPartner::updateOrCreate(
                ['id_moa_academic' => $agreement_id, 'id_partner_university' => (int) $partnerId],
                ['id_moa_academic' => $agreement_id, 'id_partner_university' => (int) $partnerId]
            );
        }
    
        foreach ($request->input('scopeP', []) as $scopeId) {
            GrieMoaAcademicScope::updateOrCreate(
                ['id_moa_academic' => $agreement_id, 'id_collaboration_scope' => (int) $scopeId],
                ['id_moa_academic' => $agreement_id, 'id_collaboration_scope' => (int) $scopeId]
            );
        }
    
        foreach ($request->input('FacP', []) as $facultyId) {
            GrieMoaAcademicFaculty::updateOrCreate(
                ['id_moa_academic' => $agreement_id, 'id_faculty' => (int) $facultyId],
                ['id_moa_academic' => $agreement_id, 'id_faculty' => (int) $facultyId]
            );
        }
    
        foreach ($request->input('stuProgP', []) as $programStudyId) {
            GrieMoaAcademicProdi::updateOrCreate(
                ['id_moa_academic' => $agreement_id, 'id_program_study_unair' => (int) $programStudyId],
                ['id_moa_academic' => $agreement_id, 'id_program_study_unair' => (int) $programStudyId]
            );
        }
    
        // session()->flash('success', 'Data berhasil disimpan!');
        // return redirect()->route('view_database');

        return response()->json(['status' => 'success', 'redirect' => route('view_database')]);
        }

        public function upload_bukti($id){

            $data = GrieMoaAcademic::find($id);
            return view('agreement.upload_pelaporan', compact('data'));
    
        }
    
        public function store_bukti(Request $request, $id){
    
            $agreement = GrieMoaAcademic::find($id);

            if($request->hasFile('linkPelaporan')){
                try {
                    $request->validate([
                        'linkPelaporan' => 'required|file|mimes:pdf|max:1000', 
                    ]);
                } catch (ValidationException $e) {
                    return response()->json(['status' => 'error', 'message' => 'Please upload a PDF file smaller than 1 MB!'], 500);
                }
            }
    
            if ($request->hasFile('linkPelaporan')) {
                $file = $request->file('linkPelaporan');
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
    
}
