<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\AgePesertaInbound;

class PendaftaranController extends Controller
{
    public function view_regist(Request $request, $type = 'amerta', $step = '1')
    {
        // Define title and initialize variables
        $title = strtoupper($type) . ' REGISTRATION';

        if ($type !== 'amerta' && $type !== 'lingua') {
            // return redirect()->route('failed')->withErrors(['error' => 'Please enter the right URL']);
        }
    
        // Fetch period data specific to type
        $table = $type === 'amerta-ua' ? 'age_amerta' : 'age_' . $type;
        $period = DB::table($table)
            ->whereDate('start_date_pendaftaran', '<=', now())
            ->whereDate('end_date_pendaftaran', '>=', now())
            ->first();
        
    
        if (!$period) {
            // return redirect()->route('failed')->withErrors(['error' => 'No active period found for ' . $type]);
        }
    
        $univ = DB::table('m_university')->get();
        $country = DB::table('m_country')->whereNot('id', 95)->get();
        $course = DB::table('age_amerta_matkul')->get();
    
        return view('pendaftaran.registrasi', [
            'title' => $title,
            'type' => $type,
            'step' => $step,
            'id_period' => $period->id ?? null,
            'univ' => $univ,
            'country' => $country,
            'course' => $course,
        ]);
    }

    public function view_submission(Request $request, $type = 'amerta', $step = '1')
    {
        // Define title and initialize variables
        $title = 'Your ' . $type. 'Registration Have been submitted!';

        $univ = DB::table('m_university')->get();
        $country = DB::table('m_country')->whereNot('id', 95)->get();
        $course = DB::table('age_amerta_matkul')->get();
    
        return view('pendaftaran.submission', [
            'title' => $title,
            'type' => $type,
        ]);
    }

    public function storeRegistrationForm(Request $request, $type = 'amerta')
{
    // Fetch active period data specific to program type
    $programTable = $type === 'amerta-ua' ? 'age_amerta' : 'age_' . $type;
    $period = DB::table($programTable)
        ->whereDate('start_date_pendaftaran', '<=', now())
        ->whereDate('end_date_pendaftaran', '>=', now())
        ->first();

    if (!$period) {
        return redirect()->back()->withErrors(['error' => 'No active period found for the selected type.']);
    }

    // Prepare data for metadata and file processing
    $data = $request->except('_token');
    $data['period'] = $period->id;
    // dd($data);

    // File upload configuration
    $fileFields = [
        'cv' => 'cv',
        'passport' => 'passport',
        'photo' => 'photo',
        'transcript' => 'transcript',
        'letter_recom_referee' => 'letter_recom_referee',
        'letter_recom' => 'letter_recom',
        'english_certificate' => 'english_certificate',
        'research_proposal' => 'research_proposal',
    ];

    // dd($request->allFiles());

    foreach ($fileFields as $field => $attribute) {
        if ($request->hasFile($field)) {
            $file = $request->file($field);

            // Tentukan path penyimpanan
            $storagePath = '/inbound';
            if (!Storage::disk('outside')->exists($storagePath)) {
                Storage::disk('outside')->makeDirectory($storagePath);
            }

            // Buat nama file unik
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs($storagePath, $fileName, 'outside');

            // Simpan URL file ke data untuk metadata
            $data[$attribute] = 'repo/' . $fileName;

        }
    }

    // Prepare metadata
    $metadata = $this->prepareMetadata($data);

    // dd($metadata);

    // Save registration data to the database
    AgePesertaInbound::create([
        'email' => $data['email'] ?? null,
        'secondary_email' => $data['secondary_email'] ?? null,
        'type' => $type,
        'metadata' => $metadata,
        'created_date' => now(),
        'is_approve' => null,
        'id_period' => $data['period'],
    ]);

    // Redirect with success message
    return redirect('/')->with('success', 'Registration completed successfully.');
}

/**
 * Prepare metadata for storage.
 */
private function prepareMetadata($data)
{
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
        'letter_recom_referee', 'program_info',
    ];

    $metadata = [];
    foreach ($metadataFields as $field) {
        $metadata[$field] = $data[$field] ?? null;
    }

    return $metadata;
}

}