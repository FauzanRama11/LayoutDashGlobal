<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgeLingua extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'age_lingua';
    public $timestamps = false;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'start_date_pendaftaran',
        'end_date_pendaftaran',
        'start_date_program',
        'end_date_program',
    ];

    public function ageLinguaMatkuls()
    {
        return $this->hasMany(AgeLinguaMatkul::class, 'id_age_lingua');
    }
}
