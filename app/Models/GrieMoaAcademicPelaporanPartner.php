<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrieMoaAcademicPelaporanPartner extends Model
{
    // Define the table name explicitly (optional if it follows Laravel naming convention)
    protected $table = 'grie_moa_academic_pelaporan_partner';

    // Define the primary key (optional if it is 'id')
    protected $primaryKey = 'id';

    // Disable timestamps if the table does not have 'created_at' and 'updated_at'
    public $timestamps = false;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'id_moa_academic',
        'id_partner_university',
        'name',
    ];

    // Define relationships

    public function idMoaAcademic()
    {
        return $this->belongsTo(GrieMoaAcademicPelaporan::class, 'id_moa_academic');
    }

    // public function idPartnerUniversity()
    // {
    //     return $this->belongsTo(MUniversity::class, 'id_partner_university');
    // }

    // Optionally define attribute casts for numeric fields
    protected $casts = [
        'id_moa_academic' => 'integer',
        'id_partner_university' => 'integer',
    ];
}
