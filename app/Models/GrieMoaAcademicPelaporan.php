<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrieMoaAcademicPelaporan extends Model
{
    // Define the table name explicitly (optional if it follows Laravel naming convention)
    protected $table = 'grie_moa_academic_pelaporan';

    // Define the primary key (optional if it is 'id')
    protected $primaryKey = 'id';

    // Disable timestamps if the table does not have 'created_at' and 'updated_at'
    public $timestamps = false;

    // Define the attributes that are mass assignable
    protected $fillable = [
        "id_dosen",
        "id_fakultas",
        "id_partner_university",
        "jenis_naskah",
        "is_rancangan",
        "url_rancangan",
        "durasi_naskah",
        "kegiatan_tobe",
        "kegiatan_asis",
        "pic_mitra_nama",
        "pic_mitra_jabatan",
        "pic_mitra_email",
        "pic_fak_nama",
        "pic_fak_jabatan",
        "pic_fak_email",
        "current_lvl",
        "status",
        "created_date",
        "created_by",
        "current_id_status",
        "current_role",
        "current_iterasi",
        "mou_start_date",
        "mou_end_date",
        "type",
        "permohonan_telaah",
        "lvl",
        "area_collab",
        "url_remarks_attachment",
        "year",
        "year_no",
        "year_order_no",
        "lapkerma_archive",
        "renewal",
        "id_country",
        "title",
        "signatories_unair_name",
        "signatories_unair_pos",
        "signatories_partner_name",
        "signatories_partner_pos",
        "pic_mitra_phone",
        "pic_fak_phone",
        "raw_id",
        "text_country",
        "text_partner_university",
        "link_download_naskah",
        "text_start_date",
        "text_end_date",
        "category_document",
        "link_partnership_profile",
        "age_archive_sn",
        "website_lapkerma",
        "text_fakultas",
        "id_department_unair",
        "text_department_unair",
        "id_program_study_unair",
        "text_program_study_unair",
        "faculty_partner",
        "department_partner",
        "program_study_partner",
        "type_institution_partner",
        "type_grant",
        "source_funding",
        "sum_funding",
        "world_rank",
        "number_agreements",
        "level_activities",
        "number_faculty_involved",
        "scival_publication",
        "total_score",
        "partnership_badge",
        "tipe_moa",
        "email",
        "ranking",
        "region",
        "is_need_ttd",
        "url_surat_pengajuan",
        "url_naskah_kerjasama",
        "url_naskah_kerjasama_ttd",
        "inisiator",
        "is_queue",
        "kategori",
        "skema",
        "approval_pelaporan",
        "approval_status",
        "approval_note",
        "kategori_tridharma"
    ];



    public function grieMoaAcademicPelaporanPartners()
    {
        return $this->hasMany(GrieMoaAcademicPelaporanPartner::class, 'id_moa_academic');
    }

    // public function idCountry()
    // {
    //     return $this->belongsTo(MCountry::class, 'id_country');
    // }

    // public function idDepartmentUnair()
    // {
    //     return $this->belongsTo(MDepartemen::class, 'id_department_unair');
    // }

    // public function idDosen()
    // {
    //     return $this->belongsTo(MDosen::class, 'id_dosen');
    // }

    // public function idFakultas()
    // {
    //     return $this->belongsTo(MFakultasUnit::class, 'id_fakultas');
    // }

    // public function idPartnerUniversity()
    // {
    //     return $this->belongsTo(MUniversity::class, 'id_partner_university');
    // }

    // public function idProgramStudyUnair()
    // {
    //     return $this->belongsTo(MProdi::class, 'id_program_study_unair');
    // }

    public function grieMoaAcademicPelaporanFaculties()
    {
        return $this->hasMany(GrieMoaAcademicPelaporanFaculty::class, 'id_moa_academic');
    }

    public function grieMoaAcademicPelaporanProdis()
    {
        return $this->hasMany(GrieMoaAcademicPelaporanProdi::class, 'id_moa_academic');
    }

    public function grieMoaAcademicPelaporanScopes()
    {
        return $this->hasMany(GrieMoaAcademicPelaporanScope::class, 'id_moa_academic');
    }

    // Optionally define attribute casts for date and numeric fields
    protected $casts = [
        // 'created_date' => 'datetime',
        // 'mou_start_date' => 'datetime',
        // 'mou_end_date' => 'datetime',
        'total_score' => 'float',
        'id_country' => 'integer',
        'id_dosen' => 'integer',
        'id_fakultas' => 'integer',
        // Add other casts as needed
    ];

}
