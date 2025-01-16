<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MStuOutProgram extends Model
{
    use HasFactory;

    protected $table = 'm_stu_out_programs';
    protected $primaryKey = "id";
    public $timestamps = false; 

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'category',
        'host_unit',
        'pic',
        'corresponding',
        'website',
        'created_by',
        'pt_ft',
        'via',
        'reg_date_start',
        'reg_date_closed',
        'description',
        'logo',
        'created_time',
        'duration',
        'host_unit_text',
        'category_text',
        'is_private_event',
        'is_program_age',
        'url_generate',
        'universitas_tujuan',
        'negara_tujuan',
        'email',
        'sub_mbkm'
    ];

    protected $casts = [
        // 'start_date' => 'datetime',
        // 'end_date' => 'datetime',
        // 'reg_date_start' => 'datetime',
        // 'reg_date_closed' => 'datetime',
        'created_time' => 'datetime',
        'category' => 'integer',
        'host_unit' => 'integer',
        'duration' => 'integer',
        'created_by' => 'integer'
    ];


    public function mStuOutPesertas()
    {
        return $this->hasMany(MStuOutPeserta::class, 'program_id');
    }
}
