<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\AgeAmertaRps;
use Illuminate\Http\Request;

class AmertaRpsController extends Controller
{
    public function index()
    {
        $data = $data = DB::table('age_amerta_rps')
        ->select('id','url_attachment', 'is_active', 'created_date')
        ->orderBy('id', 'desc')
        ->limit(500)
        ->get();
        
        return view('stu_inbound.amerta.template_rps', compact('data'));
    }

    public function form_rps(Request $request, $id = null)
    {
        $rps = null;

       

        if ($id) {
            $rps = AgeAmertaRps::find($id);

            if (!$rps) {
                return redirect()->route('am_form_rps.create')->with('error', 'Data tidak ditemukan.');
            }
        }

        return view('stu_inbound.amerta.form_rps', compact('rps'));
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
            $rps = AgeAmertaRps::findOrFail($id);

            // Cek apakah sudah ada data lain dengan status aktif
            $isAnotherActive = AgeAmertaRps::where('is_active', 'Y')
                ->where('id', '!=', $id) // Mengecualikan data yang sedang diperbarui
                ->exists();

            if ($request->is_active === 'Y') {
                if ($isAnotherActive) {
                    // Nonaktifkan semua data lain yang aktif
                    AgeAmertaRps::where('is_active', 'Y')
                        ->where('id', '!=', $id) // Mengecualikan data yang sedang diperbarui
                        ->update(['is_active' => 'N']);
                }
            }

            // Jika tidak ada file yang diunggah
            if (!$request->hasFile('url_attachment')) {
                $rps->is_active = $request->is_active === 'Y' ? 'Y' : 'N';
                $rps->save();
            } else {
                // Proses file yang diunggah
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

                // Perbarui data
                $rps->update([
                    'is_active' => $request->is_active === 'Y' ? 'Y' : 'N',
                    'url_attachment' => 'penyimpanan/' . $fileName,
                ]);
            }

            return redirect()->route('am_template_rps')->with('success', 'Data berhasil diperbarui.');

        } else {
            
            // Jika status adalah "Y", nonaktifkan data lain yang aktif
            if ($request->is_active === 'Y') {
                AgeAmertaRps::where('is_active', 'Y')->update(['is_active' => 'N']);
            }
            
            if (!$request->hasFile('url_attachment')) {
                AgeAmertaRps::create([
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
        
                AgeAmertaRps::create([
                    'is_active' => $request->is_active,
                    'url_attachment' =>'penyimpanan/' . $fileName,
                    'created_date' => now(), 
                    'created_by' => Auth::id(), 
                ]);
            }
            
            return redirect()->route('am_template_rps')->with('success', 'Data berhasil ditambahkan.');
        }
    }

    public function hapus_rps($id)
    {
        $ids = explode(',', $id);

        if (count($ids) > 1) {
            AgeAmertaRps::whereIn('id', $ids)->delete();
        } else {
            $rps = AgeAmertaRps::findOrFail($id);
            $rps->delete();
        }

        return redirect()->route('am_template_rps')->with('success', 'Data berhasil dihapus.');
    }
}
