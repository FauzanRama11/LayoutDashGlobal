<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MStuInPeserta extends Model
{
    use HasFactory;

    protected $table = 'm_stu_in_peserta';
    public $timestamps = false; 
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tgl_lahir',
        'telp',
        'email',
        'jenjang',
        'prodi_asal',
        'fakultas_asal',
        'negara_asal_univ',
        'kebangsaan',
        'photo_url',
        'loa_url',
        'tujuan_fakultas_unit',
        'tujuan_prodi',
        'univ',
        'home_address',
        'selected_id',
        'passport_no',
        'passport_url',
        'student_no',
        'student_id_url',
        'reg_time',
        'program_id',
        'approved_time',
        'approved_by',
        'is_approved',
        'is_loa',
        'approval_code',
        'profil_fill',
        'cv_url',
        'pengajuan_dana_status',
        'revision_note',
        'program_info'
    ];
    public function program()
    {
        return $this->belongsTo(MStuInProgram::class, 'program_id');
    }
}
