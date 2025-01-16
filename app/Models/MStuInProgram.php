<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MStuInProgram extends Model
{
    use HasFactory;

    protected $table = 'm_stu_in_programs';
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
        'pt_ft',
        'via',
        'reg_date_start',
        'reg_date_closed',
        'logo',
        'created_time',
        'duration',
        'url_generate',
        'description',
        'host_unit_text',
        'category_text',
        'created_by',
        'is_private_event',
        'is_program_age',
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


    public function mStuInPesertas()
    {
        return $this->hasMany(MStuInPeserta::class, 'program_id');
    }
}
