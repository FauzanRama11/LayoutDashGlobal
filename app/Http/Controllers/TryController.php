<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TryController extends Controller
{

public function showDatabase(Request $request)
{
    if ($request->ajax()) {
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
            ->where('g.link_download_naskah', '!=', '')
            ->orderByDesc('lapkerma_archive');

        // Add server-side search functionality
        return DataTables::of($data)
            ->addColumn('partner_involved', function ($item) {
                // Get related data for this row
                $result = DB::table('grie_moa_academic_scope as gs')
                    ->select(
                        DB::raw('GROUP_CONCAT(DISTINCT u2.name) AS partner_involved')
                    )
                    ->leftJoin('grie_moa_academic_partner as gapn', 'gapn.id_moa_academic', '=', 'gs.id_moa_academic')
                    ->leftJoin('m_university as u2', 'u2.id', '=', 'gapn.id_partner_university')
                    ->where('gs.id_moa_academic', $item->id)
                    ->groupBy('gs.id_moa_academic')
                    ->first();

                return $result ? $result->partner_involved : null;
            })
            ->addColumn('prodi_involved', function ($item) {
                // Similar logic for prodi_involved
                $result = DB::table('grie_moa_academic_scope as gs')
                    ->select(
                        DB::raw('GROUP_CONCAT(DISTINCT p.name_eng) AS prodi_involved')
                    )
                    ->leftJoin('grie_moa_academic_prodi as gap', 'gap.id_moa_academic', '=', 'gs.id_moa_academic')
                    ->leftJoin('m_prodi as p', 'p.id', '=', 'gap.id_program_study_unair')
                    ->where('gs.id_moa_academic', $item->id)
                    ->groupBy('gs.id_moa_academic')
                    ->first();

                return $result ? $result->prodi_involved : null;
            })
            ->addColumn('faculty_involved', function ($item) {
                // Similar logic for faculty_involved
                $result = DB::table('grie_moa_academic_scope as gs')
                    ->select(
                        DB::raw('GROUP_CONCAT(DISTINCT fu.nama_eng) AS faculty_involved')
                    )
                    ->leftJoin('grie_moa_academic_faculty as maf', 'maf.id_moa_academic', '=', 'gs.id_moa_academic')
                    ->leftJoin('m_fakultas_unit as fu', 'fu.id', '=', 'maf.id_faculty')
                    ->where('gs.id_moa_academic', $item->id)
                    ->groupBy('gs.id_moa_academic')
                    ->first();

                return $result ? $result->faculty_involved : null;
            })
            ->addColumn('collaboration_scope', function ($item) {
                // Similar logic for collaboration_scope
                $result = DB::table('grie_moa_academic_scope as gs')
                    ->select(
                        DB::raw('GROUP_CONCAT(DISTINCT cs.name) AS collaboration_scope')
                    )
                    ->leftJoin('m_collaboration_scope as cs', 'cs.id', '=', 'gs.id_collaboration_scope')
                    ->where('gs.id_moa_academic', $item->id)
                    ->groupBy('gs.id_moa_academic')
                    ->first();

                return $result ? $result->collaboration_scope : null;
            })
            ->make(true);  // Return data as JSON
    }

    return view('agreement.try');
}

}
