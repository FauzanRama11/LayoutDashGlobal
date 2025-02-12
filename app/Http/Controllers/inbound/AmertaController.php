<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use App\Models\AgeAmerta;
use App\Models\AgeAmertaMatkul;
use App\Models\MProdi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AmertaController extends Controller
{
    public function pendaftar()
    {
        $data = DB::table('age_peserta_inbound')
            ->join('age_amerta', 'age_peserta_inbound.id_period', '=', 'age_amerta.id')
            ->select(
                'age_peserta_inbound.id as id',
                'age_peserta_inbound.type as type', // Perbaikan di sini
                'age_peserta_inbound.email as email',
                'age_peserta_inbound.secondary_email as secondary_email',
                'age_peserta_inbound.metadata as metadata',
                'age_peserta_inbound.created_date as created_date',
                DB::raw("CONCAT(TO_CHAR(age_amerta.start_date_program, 'DD Mon YYYY'), ' - ', TO_CHAR(age_amerta.end_date_program, 'DD Mon YYYY')) as date_program"),
                'age_peserta_inbound.is_approve as is_approve'
            )
            ->where('age_peserta_inbound.type', 'amerta')
            ->orderByDesc('age_peserta_inbound.created_date')
            ->get();

        
        // Decode metadata dan tambahkan key dari JSON sebagai properti
        $processedData = $data->map(function ($item) {
            $metadata = json_decode($item->metadata, true); // Decode JSON menjadi array

            return [
                'id' => $item->id,
                'type' => $item->type,
                'email' => $item->email,
                'created_date' => $item->created_date,
                'date_program' => $item->date_program ?? '',
                'is_approve' => $item->is_approve,
                'full_name' => $metadata['fullname'] ?? '', 
                'selected_program' => $metadata['selected_program'] ?? '',
                'loaPeserta' => $metadata['loaPeserta'] ?? '',
            ];
        });

        // dd($processedData);
        
        return view('stu_inbound.amerta.pendaftar', compact('processedData'));
    }

    public function periode()
    {
        $data = $data = DB::table('age_amerta')
        ->select('id','start_date_pendaftaran', 'end_date_pendaftaran', 'start_date_program', 'end_date_program', 'semester')
        ->orderBy('id', 'desc')
        ->limit(500)
        ->get();

        return view('stu_inbound.amerta.periode', compact('data'));
    }

    public function form_periode(Request $request, $id = null)
    {
        $periode = null;

        if ($id) {
            $periode = DB::table('age_amerta')->where('id', $id)->first(); 
            if (!$periode) {
                return redirect()->route('am_form_periode.create')->with('error', 'Data tidak ditemukan.');
            }
        }

        return view('stu_inbound.amerta.form_periode', compact('periode'));
    }

    public function tambah_periode(Request $request, $id = null)
    {
        $request->validate([
            'start_date_pendaftaran' => 'required|date',
            'end_date_pendaftaran' => 'required|date|after_or_equal:start_date_pendaftaran',
            'start_date_program' => 'required|date',
            'end_date_program' => 'required|date|after_or_equal:start_date_program',
        ]);

        if ($id) {
            // Update data
            DB::table('age_amerta')->where('id', $id)->update([
                'start_date_pendaftaran' => $request->start_date_pendaftaran,
                'end_date_pendaftaran' => $request->end_date_pendaftaran,
                'start_date_program' => $request->start_date_program,
                'end_date_program' => $request->end_date_program,
            ]);

            return redirect()->route('am_periode')->with('success', 'Data berhasil diupdate.');
        } else {
            // Simpan data baru
            DB::table('age_amerta')->insert([
                'start_date_pendaftaran' => $request->start_date_pendaftaran,
                'end_date_pendaftaran' => $request->end_date_pendaftaran,
                'start_date_program' => $request->start_date_program,
                'end_date_program' => $request->end_date_program,
            ]);

            return redirect()->route('am_periode')->with('success', 'Data berhasil ditambahkan.');
        }
    }


    public function hapus_periode($id)
    {
        $periode = DB::table('age_amerta')->where('id', $id)->first();

        if (!$periode) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        DB::table('age_amerta')->where('id', $id)->delete();

        return redirect()->route('am_periode')->with('success', 'Data berhasil dihapus.');
    }

    // CRUD Matkul

    // public function nominasi_matkul()
    // {
    //     $data = DB::table('age_amerta_matkul')
    //     ->join('age_amerta', 'age_amerta_matkul.id_age_amerta', '=', 'age_amerta.id')
    //     ->join('m_prodi', 'age_amerta_matkul.id_prodi', '=', 'm_prodi.id')
    //     ->select('age_amerta_matkul.id', DB::raw('CONCAT(DATE_FORMAT(age_amerta.start_date_program, \'%d %b %Y\'), \' - \', DATE_FORMAT(age_amerta.end_date_program, \'%d %b %Y\')) as date_program'),
    //     'm_prodi.name', 'age_amerta_matkul.code', 'age_amerta_matkul.title', 'age_amerta_matkul.semester', 'age_amerta_matkul.sks', 'age_amerta_matkul.created_date', 'age_amerta_matkul.status')
    //     ->get();

    //     return view('stu_inbound.amerta.nominasi_matkul', compact('data'));
    // }

    public function nominasi_matkul()
    {
        
        $user = Auth::user();

        if ($user->hasRole('kps')) {
            $kps = $user->id;

            $data = DB::table('age_amerta_matkul')
            ->join('age_amerta', 'age_amerta_matkul.id_age_amerta', '=', 'age_amerta.id')
            ->join('m_prodi', 'age_amerta_matkul.id_prodi', '=', 'm_prodi.id')
            ->leftJoin('m_fakultas_unit', 'm_fakultas_unit.id', '=', 'm_prodi.id_fakultas_unit')
            ->where('created_by', '=', "$kps")
            ->select(
                'age_amerta_matkul.id',
                DB::raw("CONCAT(TO_CHAR(age_amerta.start_date_program, 'DD Mon YYYY'), ' - ', TO_CHAR(age_amerta.end_date_program, 'DD Mon YYYY')) as date_program"),
                'm_prodi.name',
                'm_fakultas_unit.nama_ind',
                'age_amerta_matkul.code',
                'age_amerta_matkul.title',
                'age_amerta_matkul.semester',
                'age_amerta_matkul.sks',
                'age_amerta_matkul.created_date',
                'age_amerta_matkul.status'
            )
            ->get();
            // dd($kps);

        }
        else if($user->hasRole('gmp|dirpen')) {

            $data = DB::table('age_amerta_matkul')
            ->join('age_amerta', 'age_amerta_matkul.id_age_amerta', '=', 'age_amerta.id')
            ->join('m_prodi', 'age_amerta_matkul.id_prodi', '=', 'm_prodi.id')
            ->leftJoin('m_fakultas_unit', 'm_fakultas_unit.id', '=', 'm_prodi.id_fakultas_unit')
            ->select(
                'age_amerta_matkul.id',
                DB::raw("CONCAT(TO_CHAR(age_amerta.start_date_program, 'DD Mon YYYY'), ' - ', TO_CHAR(age_amerta.end_date_program, 'DD Mon YYYY')) as date_program"),
                'm_prodi.name',
                'm_fakultas_unit.nama_ind',
                'age_amerta_matkul.code',
                'age_amerta_matkul.title',
                'age_amerta_matkul.semester',
                'age_amerta_matkul.sks',
                'age_amerta_matkul.created_date',
                'age_amerta_matkul.status'
            )
            ->get();

        };

        return view('stu_inbound.amerta.nominasi_matkul', compact('data'));
    }

    public function form_matkul($id = null)
    {
        $matkul = $id ? AgeAmertaMatkul::findOrFail($id) : null;

        $periode = DB::table('age_amerta')
            ->select(
                'id',
                DB::raw("CONCAT(TO_CHAR(start_date_program, 'DD Mon YYYY'), ' - ', TO_CHAR(end_date_program, 'DD Mon YYYY')) as date_program")
            )
            ->get();

        $prodi = MProdi::all(); // Menampilkan daftar prodi untuk dropdown
        return view('stu_inbound.amerta.form_matkul', compact('matkul', 'prodi', 'periode'));
    }



    // public function form_matkul($id = null)
    // {
    //     $matkul = $id ? AgeAmertaMatkul::findOrFail($id) : null;

    //     $periode = DB::table('age_amerta')
    //     ->select(
    //         'id',
    //         DB::raw('CONCAT(DATE_FORMAT(start_date_program, "%d %b %Y"), " - ", DATE_FORMAT(end_date_program, "%d %b %Y")) as date_program')
    //     )
    //     ->get();

    //     $prodi = MProdi::all(); // Menampilkan daftar prodi untuk dropdown
    //     return view('stu_inbound.amerta.form_matkul', compact('matkul', 'prodi', 'periode'));
    // }

    // Menambah data mata kuliah
    public function tambah_matkul(Request $request, $id = null)
    {
        $data = $request->validate([
            'id_prodi' => 'required|exists:m_prodi,id',
            'id_age_amerta' => 'required|exists:age_amerta,id',
            'code' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'semester' => 'required|string|max:10',
            'lecturers' => 'required|string',
            'sks' => 'required|numeric|min:1',
            'description' => 'required|string',
            'schedule' => 'required|string',
            'capacity' => 'required|numeric|min:1',
            'status' => 'nullable|string',
            'url_attachment' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
    
        // Handling file upload
        if ($request->hasFile('url_attachment')) {
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
    
            // Simpan path ke dalam $data
            $data['url_attachment'] = 'penyimpanan/' . $fileName;
        }
    
        if ($id) {
            // Update data jika ID ada
            $matkul = AgeAmertaMatkul::findOrFail($id); // Cari data berdasarkan ID
            $matkul->update($data); // Perbarui data
            $message = 'Data mata kuliah berhasil diperbarui.';
        } else {
            // Buat data baru jika ID tidak ada
            $data = array_merge($data, [
                'created_date' => now(),
                'created_by' => Auth::id(),
                'status' => 'approved',
                'verified_date' => now(),
                'verified_by' => Auth::id(),
            ]);
    
            // Simpan data baru
            AgeAmertaMatkul::create($data);
            $message = 'Data mata kuliah berhasil dibuat.';
        }

        // Redirect dengan pesan sukses
        return redirect()->route('am_nominasi_matkul')->with('success', $message);
    }

       

    // Menghapus data mata kuliah
    public function hapus_matkul($id)
    {
        $matkul = AgeAmertaMatkul::findOrFail($id);
        $matkul->delete();

        return redirect()->route('am_nominasi_matkul')->with('success', 'Data mata kuliah berhasil dihapus.');
    }

    // Backup data table: gabisa filter
    // public function pendaftar(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = DB::table('age_peserta_inbound')
    //             ->select(
    //                 'age_peserta_inbound.id as id',
    //                 'age_peserta_inbound.type as type',
    //                 'age_peserta_inbound.email as email',
    //                 'age_peserta_inbound.metadata as metadata',
    //                 'age_peserta_inbound.created_date as created_date',
    //                 DB::raw("CONCAT(TO_CHAR(age_amerta.start_date_program, 'DD Mon YYYY'), ' - ', TO_CHAR(age_amerta.end_date_program, 'DD Mon YYYY')) as date_program"),
    //                 'age_peserta_inbound.is_approve as is_approve'
    //             )
    //             ->leftJoin('age_amerta', 'age_peserta_inbound.id_period', '=', 'age_amerta.id')
    //             ->where('age_peserta_inbound.type', 'amerta');

    //         return DataTables::of($data)
    //             ->addColumn('fullname', function ($item) {
    //                 $metadata = json_decode($item->metadata, true); // Decode JSON jika belum otomatis array
    //                 return $metadata['fullname'] ?? '';
    //             })
    //             ->addColumn('selected_program', function ($item) {
    //                 $metadata = json_decode($item->metadata, true);
    //                 return ($metadata['selected_program'] ?? '') === 'AMERTA' ? 'Amerta' : ($metadata['selected_program'] ?? '');
    //             })
    //             ->addColumn('edit', function ($item) {
    //                 return '<form action="' . route('edit_peserta_inbound', ['id' => $item->id]) . '" method="GET">
    //                             <button type="submit" class="btn btn-primary edit-button"><i class="fa fa-edit"></i>  Edit</button>
    //                         </form>';
    //             })
    //             ->addColumn('approve', function ($item) {
    //                 $metadata = json_decode($item->metadata, true);
    //                 if (!empty($metadata['loaPeserta'])) {
    //                     if ($item->is_approve === true) {
    //                         return '<form action="' . route('unapprove_peserta_inbound', ['id' => $item->id]) . '" method="POST">
    //                                     ' . csrf_field() . method_field('PUT') . '
    //                             <button type="submit" class="btn btn-warning edit-button"><i class="fa fa-times-circle"></i>  Unapprove</button>
    //                                 </form>';
    //                     } else {
    //                         return '<form action="' . route('approve_peserta_inbound', ['id' => $item->id]) . '" method="POST">
    //                                     ' . csrf_field() . method_field('PUT') . '
    //                             <button type="submit" class="btn btn-primary edit-button"><i class="fa fa-ban"></i>  Approve</button>
    //                                 </form>';
    //                     }
    //                 }
    //                 return '';
    //             })
    //             ->addColumn('reject', function ($item) {
    //                 return '<form action="' . route('reject_peserta_inbound', ['id' => $item->id]) . '" method="POST">
    //                             ' . csrf_field() . method_field('PUT') . '
    //                             <button type="submit" class="btn btn-danger edit-button"><i class="fa fa-ban"></i>  Reject</button>
    //                         </form>';
    //             })
    //             ->rawColumns(['fullname','edit', 'approve', 'reject'])
    //             ->make(true);
    //     }  
    //     return view('stu_inbound.amerta.pendaftar');
    // }


    
}
