<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgePesertaInbound extends Model
{

     protected $table = 'age_peserta_inbound';

     protected $fillable = [
         'type', 
         'email', 
         'secondary_email', 
         'metadata', 
         'created_date', 
         'id_period', 
         'is_approve',
     ];
 
     // Casting metadata menjadi array JSON
     protected $casts = [
         'metadata' => 'array',
     ];

     public $timestamps = false;
 
     // Relasi (jika ada)
    public function ageAmerta()
    {
        return $this->belongsTo(AgeAmerta::class, 'id_age_amerta');
    }
 
     public function scopePesertaInbound($query, $params)
     {
         if (!empty($params['type'])) {
             $query->where('type', $params['type']);
         }
 
         if (!empty($params['id_period'])) {
             $query->where('id_period', $params['id_period']);
         }
 
         $query->where('is_approve', true);
 
         return $query;
     }
 
     // Query Scope untuk Data dengan Metadata
     public function scopeWithMetadata($query, $metadataKey)
     {
         return $query->selectRaw("
             id,
             type,
             email,
             secondary_email,
             metadata->>'$metadataKey' as metadata_value
         ");
     }
 
     // Mengambil data dengan kondisi
     public function getData($type, $isCount = false, $where = [])
     {
         $query = $this->newQuery();
 
         if ($isCount) {
             return $query->where('type', $type)->where($where)->count();
         }
 
         return $query->where('type', $type)
             ->where($where)
             ->select([
                 'id',
                 'id_period as periode',
                 'created_date',
                 'metadata->>fullname as Full_Name',
                 'email',
                 'metadata->>cv as CV',
                 'metadata->>dob as Date_of_Birth',
                 'metadata->>gpa as GPA',
                 'metadata->>pob as Place_of_Birth',
                 'metadata->>sex as Sex',
                 'metadata->>major as Major',
                 'metadata->>phone as Phone',
                 'metadata->>photo as Photo',
                 'metadata->>native as Native',
                 'metadata->>address as Address',
                 'metadata->>course1 as Course_1',
                 'metadata->>course2 as Course_2',
                 'metadata->>course3 as Course_3',
                 'metadata->>course4 as Course_4',
                 'metadata->>course5 as Course_5',
                 'metadata->>course6 as Course_6',
                 'metadata->>kin_name as Kin_Name',
                 'metadata->>lastname as Last_Name',
                 'metadata->>mobility as Mobility',
                 'metadata->>passport as Passport',
                 'metadata->>pic_name as PIC_Name',
                 'metadata->>firstname as First_Name',
                 'metadata->>kin_email as Kin_Email',
                 'metadata->>kin_phone as Kin_Phone',
                 'metadata->>pic_email as PIC_Email',
                 'metadata->>take_indo as Take_Indo',
                 'metadata->>telephone as Telephone',
                 'metadata->>taken_indo as Taken_Indo',
                 'metadata->>transcript as Transcript',
                 'metadata->>university as University',
                 'metadata->>year_entry as Year_Entry',
                 'metadata->>kin_address as Kin_Address',
                 'metadata->>nationality as Nationality',
                 'metadata->>pic_address as PIC_Address',
                 'metadata->>kin_relation as Kin_Relation',
                 'metadata->>letter_recom as Letter_Recom',
                 'metadata->>mail_address as Mail_Address',
                 'metadata->>pic_position as PIC_Position',
                 'metadata->>referee_name as Referee_Name',
                 'metadata->>english_score as English_Score',
                 'metadata->>kin_telephone as Kin_Telephone',
                 'metadata->>pic_telephone as PIC_Telephone',
                 'metadata->>referee_email as Referee_Email',
                 'metadata->>embassy_address as Embassy_Address',
                 'metadata->>passport_number as Passport_Number',
                 'metadata->>referee_relation as Referee_Relation',
                 'metadata->>selected_program as Selected_Program',
                 'metadata->>issuing_authority as Issuing_Authority',
                 'metadata->>motivation_letter as Motivation_Letter',
                 'metadata->>passport_date_exp as Passport_Expired_Date',
                 'metadata->>english_certificate as English_Certificate',
                 'metadata->>passport_date_issue as Passport_Date_Issue',
                 'metadata->>referee_organization as Referee_Organization',
                 'metadata->>degree as Degree',
                 'metadata->>end_date_prog as End_Date_Prog',
                'metadata->>experience as Experience',
                'metadata->>faculty as Faculty',
                'metadata->>field_of_study as Field_Of_Study',
                'metadata->>fullname as Fullname',
                'metadata->>joined_lingua as Joined_Lingua',
                'metadata->>loaPeserta as LoA_Peserta',
                'metadata->>outcome as Outcome',
                'metadata->>progCategory as Program_Category',
                'metadata->>program_info as Program_Info',
                'metadata->>research_proposal as Research_Proposal',
                'metadata->>start_date_prog as Start_Date_Prog',
                'metadata->>supervisor as Supervisor',
                'metadata->>tFakultasPeserta as Target_Faculty',
                'metadata->>tProdiPeserta as Target_Prodi',
                'metadata->>via as Via',
                 'is_approve'
             ])
             ->orderByDesc('created_date')
             ->paginate(10); 
}
}
