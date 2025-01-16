<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AgePesertaInbound;

class PendaftaranController extends Controller
{
    public function amerta(Request $request, $type = 'amerta', $step = '1')
    {
        // Define title and initialize variables
        $title = strtoupper($type) . ' REGISTRATION';
    
        // Fetch period data specific to type
        $table = $type === 'amerta-ua' ? 'age_amerta' : 'age_' . $type;
        $period = DB::table($table)
            ->whereDate('start_date_pendaftaran', '<=', now())
            ->whereDate('end_date_pendaftaran', '>=', now())
            ->first();
        
    
        if (!$period) {
            return redirect()->route('failed')->withErrors(['error' => 'No active period found for ' . $type]);
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

    public function lingua(Request $request, $type = 'lingua', $step = '1')
        {
            // Define title and initialize variables
            $title = strtoupper($type) . ' REGISTRATION';
        
            // Fetch period data specific to type
            $table = $type === 'amerta-ua' ? 'age_amerta' : 'age_' . $type;
            $period = DB::table($table)
                ->whereDate('start_date_pendaftaran', '<=', now())
                ->whereDate('end_date_pendaftaran', '>=', now())
                ->first();
            
        
            if (!$period) {
                return redirect()->route('failed')->withErrors(['error' => 'No active period found for ' . $type]);
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

    public function storeRegistrationForm(Request $request, $type = 'amerta')
    {
        // Initialize session for wizard
        $sessionKey = "registration_{$type}";

        // Fetch period data specific to type
        $table = $type === 'amerta-ua' ? 'age_amerta' : 'age_' . $type;
        $period = DB::table($table)
            ->whereDate('start_date_pendaftaran', '<=', now())
            ->whereDate('end_date_pendaftaran', '>=', now())
            ->first();

        if (!$period) {
            return redirect()->back()->withErrors(['error' => 'No active period found for the selected type.']);
        }

        // Ambil data dari session dan gabungkan dengan data baru
        $stepData = $request->except('_token'); // Exclude CSRF token
        $stepData['period'] = $period->id; // Tambahkan id_period
        $sessionData = $request->session()->get($sessionKey, []);
        $request->session()->put($sessionKey, array_merge($sessionData, $stepData));

        // Validasi berdasarkan langkah
        $step = $request->input('step', '1'); // Langkah saat ini

        if ($step === '2') {
            // Validate step 2 fields
            $validatedData = $request->validate([
                'email' => 'required|email',
                // 'secondary_email' => 'required|email',
                // 'type' => 'required|in:amerta,lingua',
            ]);
        } elseif ($step === '3') {
            // Validate step 3 fields
            $validatedData = $request->validate([
                // 'full_name' => 'required',
                // 'first_name' => 'required',
                // 'last_name' => 'required',
                // 'sex' => 'required',
                // 'place_of_birth' => 'required',
                // 'date_of_birth' => 'required|date',
                // 'nationality' => 'required',
                // 'telephone_number' => 'required',
                // 'mobile_number' => 'required',
                // 'home_address' => 'required',
                // 'mailing_address' => 'required',
                // 'degree' => 'required',
                // 'gpa' => 'required',
                // 'year_entry' => 'required|date',
                // 'native_language' => 'required',
                // 'english_score' => 'required',
                // 'referee_name' => 'required',
                // 'referee_organization' => 'required',
                // 'referee_relationship' => 'required',
                // 'referee_email' => 'required|email',
                // 'contact_name' => 'required',
                // 'contact_position' => 'required',
                // 'contact_email' => 'required|email',
                // 'contact_telephone' => 'required',
                // 'contact_mailing_address' => 'required',
            ]);

            // Simpan data jika ini langkah terakhir
            $this->saveRegistration($type, $request->session()->get($sessionKey, []));

            $request->session()->forget($sessionKey);

            return redirect('/');
        }

        // Redirect to next step
        return redirect()->route('amerta.registrasi', ['type' => $type, 'step' => $step + 1]);
    }

    private function saveRegistration($type, $data)
    {
        // Separate specific fields and metadata
        $mainData = [
            'email' => $data['email'] ?? null,
            'secondary_email' => $data['secondary_email'] ?? null,
            'type' => $data['type'] ?? $type,
            'metadata' => json_encode($data),
            'created_date' => now(),
            'is_approve' => null,
            'id_period' => $data['period'],
        ];

        // dd($mainData);

        // Save to database
        DB::table('age_peserta_inbound')->insert($mainData);
    }
    
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
                    WHEN p.type = "lingua" THEN CONCAT(DATE_FORMAT(lingua.start_date_program, "%d %b %Y"), " - ", DATE_FORMAT(lingua.end_date_program, "%d %b %Y"))
                    WHEN p.type = "amerta" THEN CONCAT(DATE_FORMAT(amerta.start_date_program, "%d %b %Y"), " - ", DATE_FORMAT(amerta.end_date_program, "%d %b %Y"))
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

    // Return view dengan data yang diproses
    return view('stu_inbound.form_peserta_inbound', compact('processedData'));
    }
}
