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
            "id", "id_dosen", "id_fakultas", "id_partner_university", "jenis_naskah", "is_rancangan", "url_rancangan", 
            "durasi_naskah", "kegiatan_tobe", "kegiatan_asis", "pic_mitra_nama", "pic_mitra_jabatan", "pic_mitra_email", 
            "pic_fak_nama", "pic_fak_jabatan", "pic_fak_email", "current_lvl", "status", "created_date", "created_by", 
            "current_id_status", "current_role", "current_iterasi", "mou_start_date", "mou_end_date", "type", 
            "permohonan_telaah", "lvl", "area_collab", "url_remarks_attachment", "year", "year_no", "year_order_no", 
            "lapkerma_archive", "renewal", "id_country", "title", "signatories_unair_name", "signatories_unair_pos", 
            "signatories_partner_name", "signatories_partner_pos", "pic_mitra_phone", "pic_fak_phone", "raw_id", 
            "text_country", "text_partner_university", "link_download_naskah", "text_start_date", "text_end_date", 
            "category_document", "link_partnership_profile", "age_archive_sn", "website_lapkerma", "text_fakultas", 
            "id_department_unair", "text_department_unair", "id_program_study_unair", "text_program_study_unair", 
            "faculty_partner", "department_partner", "program_study_partner", "type_institution_partner", "type_grant", 
            "source_funding", "sum_funding", "world_rank", "number_agreements", "level_activities", "number_faculty_involved", 
            "scival_publication", "total_score", "partnership_badge", "tipe_moa", "email", "ranking", "region", "is_need_ttd", 
            "url_surat_pengajuan", "url_naskah_kerjasama", "url_naskah_kerjasama_ttd", "inisiator", "is_queue", "kategori", "link_pelaporan",
            "skema", "is_lpm", "kategori_tridharma", "status_lapkerma"
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


    public static function generateNumber()
    {
        $y = date('Y');
        $last = DB::table('grie_moa_academic')
            ->where('age_archive_sn', 'LIKE', $y.'%')
            ->max('age_archive_sn');
        
        $new = $last ? (explode('.', $last)[1] + 1) : 1;
        
        return $y . '.' . str_pad($new, 3, '0', STR_PAD_LEFT);
    }
}
