<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\AgeLinguaRps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LinguaRpsController extends Controller
{
    public function index()
    {
        $data = $data = DB::table('age_lingua_rps')
        ->select('id','url_attachment', 'is_active', 'created_date')
        ->orderBy('id', 'desc')
        ->limit(500)
        ->get();
        
        
        return view('stu_inbound.lingua.template_rps', compact('data'));
    }

    public function form_rps(Request $request, $id = null)
    {
        $rps = null;

       

        if ($id) {
            $rps = AgeLinguaRps::find($id);

            if (!$rps) {
                return redirect()->route('li_form_rps.create')->with('error', 'Data tidak ditemukan.');
            }
        }

        return view('stu_inbound.lingua.form_rps', compact('rps'));
    }

    public function tambah_rps(Request $request, $id = null)
    {
    
        if ($id) {
            $request->validate([
                'is_active' => 'required|string|in:Y,N', 
                'url_attachment' => 'nullable|file|mimes:pdf,docx', 
            ]);
        } else {
            $request->validate([
                'is_active' => 'required|string|in:Y,N', 
                'url_attachment' => 'required|file|mimes:pdf,docx', 
            ]);
        }
        

        if ($id) {
            $rps = AgeLinguaRps::findOrFail($id);

            // Cek apakah sudah ada data lain dengan status aktif
            $isAnotherActive = AgeLinguaRps::where('is_active', 'Y')
            ->where('id', '!=', $id) 
            ->exists();

            if ($request->is_active === 'Y' && $isAnotherActive) {
                return redirect()->back()->withErrors([
                    'is_active' => 'Mohon nonaktifkan template yang masih aktif sebelum mengaktifkan template ini.',
                ]);
            }

            if (!$request->hasFile('url_attachment')) {
                
                $rps->is_active = $request->is_active === 'Y' ? 'Y' : 'N';
                $rps->save();

            } else {
                $file = $request->file('url_attachment');
        
                // Tentukan direktori penyimpanan di luar folder Laravel
                $storagePath = base_path('../penyimpanan'); 
        
                // Pastikan folder "penyimpanan" ada
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0777, true); 
                }
        
                // Simpan file ke folder "penyimpanan"
                $fileName = uniqid() . '_' . $file->getClientOriginalName(); 
                $file->move($storagePath, $fileName);
        
                $rps->update([
                    'is_active' => $request->is_active === 'Y' ? 'Y' : 'N',
                    'url_attachment' => 'penyimpanan/' . $fileName,
                ]);
            }

            return redirect()->route('li_template_rps')->with('success', 'Data berhasil diperbarui.');

        } else {
            
            // Jika status adalah "Y", nonaktifkan data lain yang aktif
            if ($request->is_active === 'Y') {
                AgeLinguaRps::where('is_active', 'Y')->update(['is_active' => 'N']);
            }
            
            if (!$request->hasFile('url_attachment')) {
                AgeLinguaRps::create([
                    'is_active' => $request->is_active,
                    'created_date' => now(), 
                    'created_by' => Auth::id(), 
                ]);
            } else {
                $file = $request->file('url_attachment');
        
                // Tentukan direktori penyimpanan di luar folder Laravel
                $storagePath = base_path('../penyimpanan'); 
        
                // Pastikan folder "penyimpanan" ada
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0777, true); 
                }
        
                // Simpan file ke folder "penyimpanan"
                $fileName = uniqid() . '_' . $file->getClientOriginalName(); 
                $file->move($storagePath, $fileName);
        
                AgeLinguaRps::create([
                    'is_active' => $request->is_active,
                    'url_attachment' =>'penyimpanan/' . $fileName,
                    'created_date' => now(), 
                    'created_by' => Auth::id(), 
                ]);
            }
            
            return redirect()->route('li_template_rps')->with('success', 'Data berhasil ditambahkan.');
        }
    }

    public function hapus_rps($id)
    {
        $ids = explode(',', $id);

        if (count($ids) > 1) {
            AgeLinguaRps::whereIn('id', $ids)->delete();
        } else {
            $rps = AgeLinguaRps::findOrFail($id);
            $rps->delete();
        }

        return redirect()->route('li_template_rps')->with('success', 'Data berhasil dihapus.');
    }
}
