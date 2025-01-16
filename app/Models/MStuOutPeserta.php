<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MStuOutPeserta extends Model
{
    use HasFactory;

    protected $table = 'm_stu_out_peserta';
    public $timestamps = false; 
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tgl_lahir',
        'telp',
        'email',
        'jenjang',
        'angkatan',
        'prodi_asal',
        'fakultas_asal',
        'tujuan_fakultas_unit',
        'tujuan_prodi',
        'univ',
        'home_address',
        'photo_url',
        'passport_no',
        'passport_url',
        'student_id_url',
        'reg_time',
        'program_id',
        'kebangsaan',
        'approved_time',
        'loa_url',
        'approved_by',
        'is_approved',
        'approval_code',
        'profil_fill',
        'cv_url',
        'nim',
        'pengajuan_dana_status',
        'revision_note',
    ];

    protected $casts = [
        'tgl_lahir' => 'datetime',
        'reg_time' => 'datetime',
        'approved_time' => 'datetime',
        'program_id' => 'integer',
        'kebangsaan' => 'integer',
        'approved_by' => 'integer',
        'is_approved' => 'boolean',
    ];
    
    public function program()
    {
        return $this->belongsTo(MStuOutProgram::class, 'program_id');
    }
}
