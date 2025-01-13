<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgeAmertaMatkul extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'age_amerta_matkul';
    public $timestamps = false;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'id_age_amerta',
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
    public function ageAmerta()
    {
        return $this->belongsTo(AgeAmerta::class, 'id_age_amerta');
    }

    public function prodi()
    {
        return $this->belongsTo(MProdi::class, 'id_prodi');
    }
}
