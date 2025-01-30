<?php

namespace App\Http\Controllers\agreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class TelaahNaskahController extends Controller
{
    public function view_telaah_general(Request $request)
    {  
        $data = DB::table('grie_moa_academic as gs')
            ->select(
                'dos.nama as applicant', 
                'gs.created_date', 
                'gs.jenis_naskah', 
                'fak.nama_ind as faculty', 
                'gs.title',
                'part.partner as partner',
                'latest_created_time as last',
                DB::raw('EXTRACT(EPOCH FROM (current_date - latest_created_time)) / 86400 AS days') 
                // DB::raw('DATEDIFF(current_date(), latest_created_time) AS days')
            )
            // ->where('gs.current_role', '=',  $request->input('current_role')) 
            ->where('gs.tipe_moa', '=',  'General') 
            ->where('gs.type', '=',  'internal') 
            ->whereNot("gs.status", "=", "Archived")
            ->leftJoin('m_dosen as dos', 'dos.id', '=', 'gs.id_dosen') 
            ->leftJoin('m_fakultas_unit as fak', 'fak.id', '=', 'gs.id_fakultas')
            ->leftjoinSub(
                DB::table('grie_moa_academic_partner')
                    ->select(
                        'id_moa_academic',
                        DB::raw('STRING_AGG(u.name, \', \') AS partner')
                        // DB::raw('GROUP_CONCAT(u.name) AS partner')
                    )
                    ->leftjoin('m_university as u', 'u.id', '=', 'grie_moa_academic_partner.id_partner_university')
                    ->groupBy('id_moa_academic')
                    ->orderBy('id_moa_academic', 'DESC'),
                'part',
                'part.id_moa_academic',
                '=',
                'gs.id'
            )
            ->leftJoinSub(
                DB::table('grie_moa_academic_history')
                    ->select(
                        'id_moa_academic',
                        DB::raw('MAX(grie_moa_academic_history.created_time) AS latest_created_time')
                    )
                    ->groupBy('id_moa_academic'),
                'history',
                'history.id_moa_academic',
                '=',
                'gs.id'
            );

            if ($request->input('current_role') != 'all' &&$request->input('current_role') != 'age' ) {
                $data->where('gs.current_role', '=', $request->input('current_role'));
            }elseif($request->input('current_role') == 'age' ){
                $data->where('gs.current_role', '=', 'ksln_ttd')
                    ->Where('gs.status', '=', 'Progress');
            } elseif ($request->input('current_role') == 'all') {
                // Jika role adalah 'all', ambil semua data role lainnya
                $data->where(function($query) {
                    $query->where('gs.current_role', '=', 'dosen')
                            ->orWhere('gs.current_role', '=', 'wadek3')
                            ->orWhere('gs.current_role', '=', 'dipp')
                            ->orWhere('gs.current_role', '=', 'bpbrin')
                            ->orWhere('gs.current_role', '=', 'hukum')
                            ->orWhere('gs.current_role', '=', 'sekretaris')
                            ->orWhere('gs.current_role', '=', 'warek_ama')
                            ->orWhere('gs.current_role', '=', 'warek_idi')
                            ->orWhere('gs.current_role', '=', 'ksln_ttd')
                            ->orWhere('gs.current_role', '=', 'ksln_final');
                });
            }
            
            // Execute query
            $data = $data->get();
        // dd($data);
        return view('agreement.view_review_general', compact('data'));
    }

    public function view_telaah_riset(Request $request){

        $data = DB::table('grie_moa_academic as gs')
        ->select(
            'dos.nama as applicant', 
            'gs.created_date', 
            'gs.jenis_naskah', 
            'fak.nama_ind as faculty', 
            'gs.title',
            'part.partner as partner',
            'latest_created_time as last',
            DB::raw('EXTRACT(EPOCH FROM (current_date - latest_created_time)) / 86400 AS days') 
            // DB::raw('DATEDIFF(current_date(), latest_created_time) AS days')
        )
        // ->where('gs.current_role', '=',  $request->input('current_role')) 
        ->where('gs.tipe_moa', '=',  'Riset') 
        ->where('gs.type', '=',  'internal') 
        ->whereNot("gs.status", "=", "Archived")
        ->leftJoin('m_dosen as dos', 'dos.id', '=', 'gs.id_dosen') 
        ->leftJoin('m_fakultas_unit as fak', 'fak.id', '=', 'gs.id_fakultas')
        ->leftjoinSub(
            DB::table('grie_moa_academic_partner')
                ->select(
                    'id_moa_academic',
                    // DB::raw('STRING_AGG(u.name, \', \') AS partner')
                    DB::raw('GROUP_CONCAT(u.name) AS partner')
                )
                ->leftjoin('m_university as u', 'u.id', '=', 'grie_moa_academic_partner.id_partner_university')
                ->groupBy('id_moa_academic')
                ->orderBy('id_moa_academic', 'DESC'),
            'part',
            'part.id_moa_academic',
            '=',
            'gs.id'
        )
        ->leftJoinSub(
            DB::table('grie_moa_academic_history')
                ->select(
                    'id_moa_academic',
                    DB::raw('MAX(grie_moa_academic_history.created_time) AS latest_created_time')
                )
                ->groupBy('id_moa_academic'),
            'history',
            'history.id_moa_academic',
            '=',
            'gs.id'
        );

        if ($request->input('current_role') != 'all' &&$request->input('current_role') != 'age' ) {
            $data->where('gs.current_role', '=', $request->input('current_role'));
        }elseif($request->input('current_role') == 'age' ){
            $data->where('gs.current_role', '=', 'grie.ia')
                ->orWhere('gs.current_role', '=', value: 'grie.ia_tt');
        } elseif ($request->input('current_role') == 'all') {
            // Jika role adalah 'all', ambil semua data role lainnya
            $data->where(function($query) {
                $query->where('gs.current_role', '=', 'dosen')
                        ->orWhere('gs.current_role', '=', 'lpm')
                        ->orWhere('gs.current_role', '=', 'hukum')
                        ->orWhere('gs.current_role', '=', 'sekretaris')
                        ->orWhere('gs.current_role', '=', 'warek_rcid')
                        ->orWhere('gs.current_role', '=', 'warek_idi')
                        ->orWhere('gs.current_role', '=', 'grie.ia')
                        ->orWhere('gs.current_role', '=', 'grie.ia_tt');
            });
        }
        
        // Execute query
        $data = $data->get();
    // dd($data);

        return view('agreement.view_review_riset', compact("data"));
    }
    
}
