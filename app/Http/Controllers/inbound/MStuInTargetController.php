<?php

namespace App\Http\Controllers\inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MStuInTargetController extends Controller
{
    public function index()
    {
        $data = DB::table('m_stu_in_target')
        ->join('m_fakultas_unit', 'm_stu_in_target.id_fakultas', '=', 'm_fakultas_unit.id')
        ->select('m_stu_in_target.id', 'm_stu_in_target.year', 'm_fakultas_unit.nama_ind as fakultas_name', 'm_stu_in_target.target_pt', 'm_stu_in_target.target_ft')
        ->get();

        return view('stu_inbound.target', compact('data'));
    
    }

    public function form_target(Request $request, $id = null)
    {
        $target = null;
        $unit = DB::table('m_fakultas_unit')->get();

        if ($id) {
            $target = DB::table('m_stu_in_target')->where('id', $id)->first(); 
            if (!$target) {
                return redirect()->route('stuin_target')->with('error', 'Data tidak ditemukan.');
            }
        }

        return view('stu_inbound.form_target', compact('target','unit'));
    }

    public function tambah_target(Request $request, $id = null)
    {
        
        $request->validate([
            'fakultas' => 'required|int',
            'year' => 'required|int',
            'target_pt' => 'required|int',
            'target_ft' => 'required|int',
        ]);

        if ($id) {
            // Update data
            DB::table('m_stu_in_target')->where('id', $id)->update([
                'id_fakultas' => $request->fakultas,
                'year' => $request->year,
                'target_pt' => $request->target_pt,
                'target_ft' => $request->target_ft,
            ]);

            return redirect()->route('stuin_target')->with('success', 'Data berhasil diupdate.');
        } else {
            // Simpan data baru
            DB::table('m_stu_in_target')->insert([
                'id_fakultas' => $request->fakultas,
                'year' => $request->year,
                'target_pt' => $request->target_pt,
                'target_ft' => $request->target_ft,
            ]);

            return redirect()->route('stuin_target')->with('success', 'Data berhasil ditambahkan.');
        }
    }


    public function hapus_target($id)
    {
        $target = DB::table('m_stu_in_target')->where('id', $id)->first();

        if (!$target) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        DB::table('m_stu_in_target')->where('id', $id)->delete();

        return redirect()->route('stuin_target')->with('success', 'Data berhasil dihapus.');
    }
}
