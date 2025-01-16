<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MProdi extends Model
{
    use HasFactory;

    // Define the table name if it does not follow Laravel's naming conventions
    protected $table = 'm_prodi';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'id_fakultas_unit',
        'name',
        'kaprodi',
        'level',
        'name_eng',
        'gelar_panjang',
        'gelar_pendek',
        'gelar_eng',
        'id_cyber',
    ];

}
