<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeAmertaRps extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'age_amerta_rps';
    public $timestamps = false;


    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'is_active',
        'url_attachment',
        'created_by',
        'created_date', 
    ];

}
