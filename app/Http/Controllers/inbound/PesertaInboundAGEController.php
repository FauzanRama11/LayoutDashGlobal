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

        if (!$data) {
            abort(404, 'Data not found');
        }

        $metadataFields = [
            'selected_program', 'mobility', 'fullname', 'firstname', 'lastname', 'sex', 'pob',
            'dob', 'nationality', 'passport_number', 'passport_date_issue', 'passport_date_exp',
            'issuing_authority', 'telephone', 'phone', 'address', 'mail_address', 'embassy_address',
            'kin_name', 'kin_relation', 'kin_address', 'kin_phone', 'kin_telephone', 'kin_email',
            'university', 'major', 'gpa', 'year_entry', 'native', 'english_score', 'field_of_study',
            'referee_name', 'referee_organization', 'referee_relation', 'referee_email', 'pic_name',
            'pic_email', 'pic_position', 'pic_telephone', 'pic_address', 'course1', 'course2', 'course3',
            'course4', 'course5', 'course6', 'taken_indo', 'take_indo', 'cv', 'passport', 'photo',
            'transcript', 'letter_recom', 'motivation_letter', 'english_certificate', 'research_proposal', 
            'program_info',
        ];

        $metadata = json_decode($data->metadata, true);

        $processedData = [
            'id' => $data->id,
            'type' => $data->type,
            'email' => $data->email,
            'created_date' => $data->created_date,
            'date_program' => $data->date_program,
            'is_approve' => $data->is_approve,
        ];

        foreach ($metadataFields as $field) {
            $processedData[$field] = $metadata[$field] ?? '-';
        }

        // dd($processedData);

        $univ = DB::table('m_university')->get();
        $country = DB::table('m_country')->whereNot('id', 95)->get();
        $course = DB::table('age_amerta_matkul')->get();

        return view('stu_inbound.form_peserta_inbound', [
            'processedData' => $processedData,
            'univ' => $univ,
            'country' => $country,
            'course' => $course,
        ]);
    }
}
