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
            $message = 'Please enter the right link to go to the form registration!';

            return view('pendaftaran.result', [
                'title' => 'INVALID PROGRAM REGISTRATION',
                'type' => $type,
                'message' => $message,
            ]);
        }

        // Fetch period data specific to type
        $table = $type === 'amerta-ua' ? 'age_amerta' : 'age_' . $type;
        $period = DB::table($table)
            ->whereDate('start_date_pendaftaran', '<=', now())
            ->whereDate('end_date_pendaftaran', '>=', now())
            ->first();
        
    
        if (!$period) {
            
            $title = strtoupper($type) . ' REGISTRATION STATUS';
            $message = 'Currently, there is no active registration period for this program type. 
                       Please check back later or contact support for assistance.';

            return view('pendaftaran.result', [
                'title' => $title,
                'type' => $type,
                'message' => $message,
            ]);
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

    public function result(Request $request, $type)
    {
        $title = strtoupper($type) . ' REGISTRATION SUBMITTED';

        // Fetch success message from session
        $message = session('success') ?? 'Your registration process has been recorded.';

        if ($type !== 'amerta' && $type !== 'lingua') {
            $message = 'Please enter the right link to go to the form registration!';
            
            return view('pendaftaran.result', [
                'title' => 'INVALID PROGRAM REGISTRATION',
                'type' => $type,
                'message' => $message,
            ]);
        }

        return view('pendaftaran.result', [
            'title' => $title,
            'type' => $type,
            'message' => $message,
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
    
    if($type=='lingua'){
        $data['selected_program'] = 'Lingua';
    }

    $data['period'] = $period->id;

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
    
    try {
        foreach ($fileFields as $field => $attribute) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Simpan file dan tambahkan ke array $uploadedFiles
                $filePath = $this->storeFile($file, 'pendaftaran');
                $data[$attribute] = $filePath;
                $uploadedFiles[] = $filePath;
            }
        }

        // Prepare metadata
        $metadata = $this->prepareMetadata($data);

        // dd($metadata);

        AgePesertaInbound::create([
            'email' => $data['email'] ?? null,
            'secondary_email' => $data['secondary_email'] ?? null,
            'type' => $type,
            'metadata' => $metadata,
            'created_date' => now(),
            'is_approve' => null,
            'id_period' => $data['period'],
        ]);

        return redirect()->route('result', ['type' => $type]);

    } catch (\Exception $e) {
      
        foreach ($uploadedFiles as $filePath) {
            $this->deleteFile($filePath);
        }

        return redirect()->back()->with('error', 'Failed to save data: ' . $e->getMessage());
    }
}

    private function storeFile($file, $subfolder = null)
    {
        $folder = $subfolder ? "/{$subfolder}" : '';

        if (!Storage::disk('inside')->exists($folder)) {
            Storage::disk('inside')->makeDirectory($folder);
        }

        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $file->storeAs($folder, $fileName, 'inside');

        return $subfolder ? "repo/{$subfolder}/{$fileName}" : "repo/{$fileName}";
    }

    private function deleteFile($filePath)
    {
        // Konversi path relatif menjadi path internal
        $relativePath = str_replace('repo/', '', $filePath);

        // Periksa dan hapus file
        if (Storage::disk('inside')->exists($relativePath)) {
            Storage::disk('inside')->delete($relativePath);
        }
    }


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