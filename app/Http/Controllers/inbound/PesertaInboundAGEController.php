<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\AgePesertaInbound;

class PesertaInboundAGEController extends Controller
{
    
    public function approve($id)
    {
        $model = AgePesertaInbound::findOrFail($id);
        $model->is_approve = 't';
        $model->save();

        // dd( $model);

        if( $model->type === 'amerta'){
            return redirect()->route('am_pendaftar');
        } else {
            return redirect()->route('li_pendaftar');
        }

    }

    public function reject($id)
    {
        $model = AgePesertaInbound::findOrFail($id);
        $model->is_approve = 'f';
        $model->save();

        if( $model->type === 'amerta'){
            return redirect()->route('am_pendaftar');
        } else {
            return redirect()->route('li_pendaftar');
        }

    }

    public function edit(Request $request, $id = null)
    {
         // Ambil data berdasarkan ID
        $data = DB::table('age_peserta_inbound as p')
        ->leftJoin('age_lingua as lingua', function ($join) {
            $join->on('p.id_period', '=', 'lingua.id')
                ->where('p.type', '=', 'lingua');
        })
        ->leftJoin('age_amerta as amerta', function ($join) {
            $join->on('p.id_period', '=', 'amerta.id')
                ->where('p.type', '=', 'amerta');
        })
        ->select(
            'p.id as id',
            'p.type as type',
            'p.email as email',
            'p.secondary_email as secondary_email',
            'p.metadata as metadata',
            'p.created_date as created_date',
            'p.is_approve as is_approve',
            DB::raw('
                CASE
                    WHEN p.type = \'lingua\' THEN CONCAT(TO_CHAR(lingua.start_date_program, \'DD Mon YYYY\'), \' - \', TO_CHAR(lingua.end_date_program, \'DD Mon YYYY\'))
                    WHEN p.type = \'amerta\' THEN CONCAT(TO_CHAR(amerta.start_date_program, \'DD Mon YYYY\'), \' - \', TO_CHAR(amerta.end_date_program, \'DD Mon YYYY\'))
                    ELSE NULL
                END as date_program
            ')
        )
        ->where('p.id', $id)
        ->first();

    // Cek apakah data ditemukan
    if (!$data) {
        abort(404, 'Data not found');
    }

    // Decode metadata dari JSON menjadi array
    $metadata = json_decode($data->metadata, true);

    // dd($metadata);

    // Gabungkan data metadata dengan hasil query
    $processedData = [
        'id' => $data->id,
        'type' => $data->type,
        'email' => $data->email,
        'secondary_email' => $data->secondary_email,
        'created_date' => $data->created_date,
        'date_program' => $data->date_program ?? null,
        'is_approve' => $data->is_approve,
        'full_name' => $metadata['fullname'] ?? '-',
        'first_name' => $metadata['firstname'] ?? '-',
        'last_name' => $metadata['lastname'] ?? '-',
        'dob' => $metadata['dob'] ?? null,
        'pob' => $metadata['pob'] ?? '-',
        'gpa' => $metadata['gpa'] ?? null,
        'sex' => $metadata['sex'] ?? '-',
        'major' => $metadata['major'] ?? '-',
        'phone' => $metadata['phone'] ?? '-',
        'photo' => $metadata['photo'] ?? '-',
        'native_language' => $metadata['native'] ?? '-',
        'address' => $metadata['address'] ?? '-',
        'university' => $metadata['university'] ?? '-',
        'year_entry' => $metadata['year_entry'] ?? null,
        'nationality' => $metadata['nationality'] ?? '-',
        'passport_number' => $metadata['passport_number'] ?? '-',
        'passport_date_issue' => $metadata['passport_date_issue'] ?? null,
        'passport_date_exp' => $metadata['passport_date_exp'] ?? null,
        'issuing_authority' => $metadata['issuing_authority'] ?? '-',
        'motivation_letter' => $metadata['motivation_letter'] ?? '-',
        'transcript' => $metadata['transcript'] ?? '-',
        'letter_recom' => $metadata['letter_recom'] ?? '-',
        'selected_program' => $metadata['selected_program'] ?? '-',
        'take_indo' => $metadata['take_indo'] ?? '-',
        'taken_indo' => $metadata['taken_indo'] ?? '-',
        'english_certificate' => $metadata['english_certificate'] ?? '-',
        'english_score' => $metadata['english_score'] ?? '-',
        'pic_name' => $metadata['pic_name'] ?? '-',
        'pic_email' => $metadata['pic_email'] ?? '-',
        'pic_telephone' => $metadata['pic_telephone'] ?? '-',
        'pic_address' => $metadata['pic_address'] ?? '-',
        'pic_position' => $metadata['pic_position'] ?? '-',
        'referee_name' => $metadata['referee_name'] ?? '-',
        'referee_email' => $metadata['referee_email'] ?? '-',
        'referee_relation' => $metadata['referee_relation'] ?? '-',
        'referee_organization' => $metadata['referee_organization'] ?? '-',
        'kin_name' => $metadata['kin_name'] ?? '-',
        'kin_email' => $metadata['kin_email'] ?? '-',
        'kin_phone' => $metadata['kin_phone'] ?? '-',
        'kin_address' => $metadata['kin_address'] ?? '-',
        'kin_relation' => $metadata['kin_relation'] ?? '-',
    ];

    // dd($processedData);

    // Return view dengan data yang diproses
    return view('stu_inbound.form_peserta_inbound', compact('processedData'));
    }
}
