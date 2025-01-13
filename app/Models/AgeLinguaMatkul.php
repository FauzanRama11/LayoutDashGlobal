<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgeLinguaMatkul extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'age_lingua_matkul';
    public $timestamps = false;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'id_age_lingua',
        'code',
        'title',
        'semester',
        'lecturers',
        'sks',
        'description',
        'schedule',
        'capacity',
        'url_attachment',
        'created_date',
        'created_by',
        'id_prodi',
        'status',
        'prerequisites',
        'verified_date',
        'verified_by',
    ];

    // Define the relationships
    public function agelingua()
    {
        return $this->belongsTo(AgeLingua::class, 'id_age_lingua');
    }

    public function prodi()
    {
        return $this->belongsTo(MProdi::class, 'id_prodi');
    }
}
