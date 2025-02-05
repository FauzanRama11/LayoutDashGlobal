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
        $model->is_approve = 'true';
        $model->save();

        // dd( $model);

        return redirect()->route($model->type === 'amerta' ? 'am_pendaftar' : 'li_pendaftar');
    }

    public function unapprove($id)
    {
        $model = AgePesertaInbound::findOrFail($id);
        $model->is_approve = null;
        $model->save();

        return redirect()->route($model->type === 'amerta' ? 'am_pendaftar' : 'li_pendaftar');
    }

    public function reject($id) 
    {
        $model = AgePesertaInbound::findOrFail($id);
        $model->is_approve = false;
        $model->save();

        return redirect()->route($model->type === 'amerta' ? 'am_pendaftar' : 'li_pendaftar');
    }

    // HALAMAN EDIT
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
            'university', 'degree', 'faculty', 'major', 'gpa', 'year_entry', 'native', 'english_score', 
            
            'referee_name', 'referee_organization', 'referee_relation', 'referee_email', 'progCategory', 'via', 

            'pic_name', 'pic_position', 'pic_email', 'pic_telephone', 'pic_address', 'course1', 'course2', 
            'course3', 'course4', 'course5', 'course6', 'taken_indo', 'take_indo', 'joined_lingua', 
            
            'cv', 'passport', 'photo', 'transcript', 'letter_recom', 'motivation_letter', 'english_certificate', 
            'research_proposal', 'field_of_study', 'start_date_prog', 'end_date_prog', 'outcome', 
            'supervisor', 'experience', 'program_info', 'loaPeserta', 'tFakultasPeserta', 'tProdiPeserta'
        ];

        $metadata = json_decode($data->metadata, true);

        $processedData = [
            'id' => $data->id,
            'type' => $data->type,
            'email' => $data->email,
            'secondary_email' => $data->secondary_email,
            'created_date' => $data->created_date,
            'date_program' => $data->date_program,
            'is_approve' => $data->is_approve,
        ];

        foreach ($metadataFields as $field) {
            $processedData[$field] = $metadata[$field] ?? '';
        }

        $photoPaths = [
            'pendaftaran/' . ltrim(str_replace('repo/pendaftaran/', '', $processedData['photo']), '/'),
            ltrim(str_replace('repo/', '', $processedData['photo']), '/')
        ];
        
        $photoFilePath = null;
        
        // Cek apakah salah satu path ada dalam Storage Disk "inside"
        foreach ($photoPaths as $path) {
            if (Storage::disk('inside')->exists($path)) {
                $photoFilePath = $path;
                break;
            }
        }
        
        // Mengubah foto menjadi Base64 jika ditemukan
        if ($photoFilePath) {
            $mime = Storage::disk('inside')->mimeType($photoFilePath); // Ambil MIME type (jpg, png, dll.)
            $base64 = base64_encode(Storage::disk('inside')->get($photoFilePath));
            $processedData['photo'] = "data:$mime;base64,$base64";
        } else {
            $processedData['photo'] = null; // Jika file tidak ditemukan, kosongkan
        }

        // dd($processedData);

        $univ = DB::table('m_university')->get();
        $unit = DB::table('m_fakultas_unit')->get();
        $prodi = DB::table('m_prodi')->get();
        $country = DB::table('m_country')->whereNot('id', 95)->get();
        $course = DB::table('age_amerta_matkul')->get();
        $category = DB::table('m_stu_in_program_category')->get();

        return view('stu_inbound.form_peserta_inbound', [
            'processedData' => $processedData,
            'univ' => $univ,
            'unit' => $unit,
            'prodi' => $prodi,
            'country' => $country,
            'course' => $course,
            'category' => $category,
        ]);
    }

    public function updateForm(Request $request, $id)
    {
        try {
            $peserta = AgePesertaInbound::findOrFail($id);
            $metadata = $peserta->metadata;

            foreach ($request->all() as $key => $value) {
                if (array_key_exists($key, $metadata) || in_array($key, array_keys($this->prepareMetadata([])))) {
                    $metadata[$key] = $value;
                }
            }


            $fileFields = [
                'cv', 'passport', 'photo', 'transcript',
                'letter_recom_referee', 'letter_recom', 'english_certificate',
                'motivation_letter', 'research_proposal', 'loaPeserta'
            ];

            $newFiles = [];

            // Mengecek dokumen baru dan store ke folder
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $filePath = $this->storeFile($request->file($field), 'pendaftaran');
                    $newFiles[$field] = $filePath;
                }
            }

            // Hapus dokumen lama dan memasukkan path baru
            foreach ($newFiles as $field => $newPath) {
                if (!empty($metadata[$field])) {
                    $this->deleteFile($metadata[$field]); 
                }
                $metadata[$field] = $newPath; 
            }

            
            // dd($metadata);

            // Simpan pembaharuan
            $peserta->email = $request->email ?? $peserta->email;
            $peserta->secondary_email = $request->secondary_email ?? $peserta->secondary_email;
            $peserta->metadata = $metadata;
            $peserta->save();

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update data: ' . $e->getMessage());
        }
    }


    // ADDITIONAL FUNCTION
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
            'university', 'degree', 'faculty', 'major', 'gpa', 'year_entry', 'native', 'english_score', 
            
            'referee_name', 'referee_organization', 'referee_relation', 'referee_email', 'progCategory', 'via', 

            'pic_name', 'pic_position', 'pic_email', 'pic_telephone', 'pic_address', 'course1', 'course2', 
            'course3', 'course4', 'course5', 'course6', 'taken_indo', 'take_indo', 'joined_lingua', 
            
            'cv', 'passport', 'photo', 'transcript', 'letter_recom', 'motivation_letter', 'english_certificate', 
            'research_proposal', 'field_of_study', 'start_date_prog', 'end_date_prog', 'outcome', 
            'supervisor', 'experience', 'program_info', 'loaPeserta', 'tFakultasPeserta', 'tProdiPeserta'
        ];

        $metadata = [];
        foreach ($metadataFields as $field) {
            $metadata[$field] = $data[$field] ?? null;
        }

        return $metadata;
    }

}
