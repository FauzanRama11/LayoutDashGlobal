<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GrieMoaAcademic extends Model
{
    use HasFactory;

    protected $table = 'grie_moa_academic'; // Table name
    protected $primaryKey = 'id'; // Primary key (if it's not 'id')
    public $timestamps = false; // Disable Laravel timestamps if not present in the table

    // Fillable attributes for mass assignment
    protected $fillable = [
        'created_date',
        'created_by',
        'current_role',
        'id_dosen',
        'id_fakultas',
        'id_partner_university',
        'durasi_naskah',
        'current_lvl',
        'current_id_status',
        'current_iterasi',
        'year',
        'year_no',
        'id_country',
        'raw_id',
        'id_department_unair',
        'id_program_study_unair',
        'world_rank',
        'number_agreements',
        'level_activities',
        'number_faculty_involved',
        'scival_publication',
        'jenis_naskah',
        'is_rancangan',
        'renewal',
        'is_need_ttd',
        'is_queue',
        'is_lpm',
        'pic_mitra_nama',
        'pic_mitra_jabatan',
        'pic_mitra_email',
        'pic_fak_nama',
        'pic_fak_jabatan',
        'pic_fak_email',
        'status',
        'type',
        'permohonan_telaah',
        'lvl',
        'url_remarks_attachment',
        'year_order_no',
        'lapkerma_archive',
        'signatories_unair_name',
        'signatories_unair_pos',
        'signatories_partner_name',
        'signatories_partner_pos',
        'pic_mitra_phone',
        'pic_fak_phone',
        'text_country',
        'text_partner_university',
        'text_start_date',
        'text_end_date',
        'category_document',
        'age_archive_sn',
        'text_fakultas',
        'text_department_unair',
        'text_program_study_unair',
        'faculty_partner',
        'department_partner',
        'program_study_partner',
        'type_institution_partner',
        'type_grant',
        'source_funding',
        'partnership_badge',
        'tipe_moa',
        'email',
        'ranking',
        'region',
        'inisiator',
        'kategori',
        'skema',
        'is_lpm',
    ];

    // Define relationships (you may need to adjust the foreign keys)
    // public function grieMoaAcademicHistories()
    // {
    //     return $this->hasMany(GrieMoaAcademicHistory::class, 'id_moa_academic');
    // }

    public function grieMoaAcademicPartners()
    {
        return $this->hasMany(GrieMoaAcademicPartner::class, 'id_moa_academic');
    }

    // public function idCountry()
    // {
    //     return $this->belongsTo(MCountry::class, 'id_country');
    // }

    // public function idDosen()
    // {
    //     return $this->belongsTo(MDosen::class, 'id_dosen');
    // }

    // public function idDepartmentUnair()
    // {
    //     return $this->belongsTo(MDepartemen::class, 'id_department_unair');
    // }

    // public function idProgramStudyUnair()
    // {
    //     return $this->belongsTo(MProdi::class, 'id_program_study_unair');
    // }

    // public function idFakultas()
    // {
    //     return $this->belongsTo(MFakultasUnit::class, 'id_fakultas');
    // }

    // public function idPartnerUniversity()
    // {
    //     return $this->belongsTo(MUniversity::class, 'id_partner_university');
    // }

    // Custom method for generateNumber
    public function generateNumber()
    {
        $y = date('Y');
        $last = DB::table('grie_moa_academic')
            ->where('age_archive_sn', 'LIKE', $y.'%')
            ->max('age_archive_sn');
        
        $new = $last ? (explode('.', $last)[1] + 1) : 1;
        
        return $y . '.' . str_pad($new, 3, '0', STR_PAD_LEFT);
    }

    // Additional custom methods (e.g., allRoles, addCol) can be similarly adapted
}
