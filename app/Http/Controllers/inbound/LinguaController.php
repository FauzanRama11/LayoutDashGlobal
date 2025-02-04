<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LinguaController extends Controller
{

    public function pendaftar()
    {
        
            $data = DB::table('age_peserta_inbound')
            ->join('age_lingua', 'age_peserta_inbound.id_period', '=', 'age_lingua.id')
            ->select(
                'age_peserta_inbound.id as id',
                'age_peserta_inbound.type as type', // Perbaikan di sini
                'age_peserta_inbound.email as email',
                'age_peserta_inbound.secondary_email as secondary_email',
                'age_peserta_inbound.metadata as metadata',
                'age_peserta_inbound.created_date as created_date',
                DB::raw("CONCAT(TO_CHAR(age_lingua.start_date_program, 'DD Mon YYYY'), ' - ', TO_CHAR(age_lingua.end_date_program, 'DD Mon YYYY')) as date_program"),
                'age_peserta_inbound.is_approve as is_approve'
            )
            ->where('age_peserta_inbound.type', 'lingua')
            ->orderByDesc('age_peserta_inbound.id')
            ->get();
        
        // Decode metadata dan tambahkan key dari JSON sebagai properti
        $processedData = $data->map(function ($item) {
            $metadata = json_decode($item->metadata, true); // Decode JSON menjadi array

            return [
                'id' => $item->id,
                'type' => $item->type,
                'email' => $item->email,
                'created_date' => $item->created_date,
                'date_program' => $item->date_program,
                'is_approve' => $item->is_approve,
                'full_name' => $metadata['fullname'] ?? '', 
                'selected_program' => $metadata['selected_program'] ?? '',
                'loaPeserta' => $metadata['loaPeserta'] ?? '',
            ];
        });
        
        return view('stu_inbound.lingua.pendaftar', compact('processedData'));
    }

    public function periode()
    {
        $data = DB::table('age_lingua')
        ->select('id','start_date_pendaftaran', 'end_date_pendaftaran', 'start_date_program', 'end_date_program' )
        ->orderBy('id', 'desc')
        ->limit(500)
        ->get();

        return view('stu_inbound.lingua.periode', compact('data'));
    }

   // CRUD Periode lingua

   public function form_periode(Request $request, $id = null)
   {
       $periode = null;

       if ($id) {
           $periode = DB::table('age_lingua')->where('id', $id)->first(); 
           if (!$periode) {
               return redirect()->route('li_form_periode.create')->with('error', 'Data tidak ditemukan.');
           }
       }

       return view('stu_inbound.lingua.form_periode', compact('periode'));
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
           DB::table('age_lingua')->where('id', $id)->update([
               'start_date_pendaftaran' => $request->start_date_pendaftaran,
               'end_date_pendaftaran' => $request->end_date_pendaftaran,
               'start_date_program' => $request->start_date_program,
               'end_date_program' => $request->end_date_program,
           ]);

           return redirect()->route('li_periode')->with('success', 'Data berhasil diupdate.');
       } else {
           // Simpan data baru
           DB::table('age_lingua')->insert([
               'start_date_pendaftaran' => $request->start_date_pendaftaran,
               'end_date_pendaftaran' => $request->end_date_pendaftaran,
               'start_date_program' => $request->start_date_program,
               'end_date_program' => $request->end_date_program,
           ]);

           return redirect()->route('li_periode')->with('success', 'Data berhasil ditambahkan.');
       }
   }


   public function hapus_periode($id)
   {
       $periode = DB::table('age_lingua')->where('id', $id)->first();

       if (!$periode) {
           return redirect()->back()->with('error', 'Data tidak ditemukan.');
       }

       DB::table('age_lingua')->where('id', $id)->delete();

       return redirect()->route('li_periode')->with('success', 'Data berhasil dihapus.');
   }


}
