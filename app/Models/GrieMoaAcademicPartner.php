<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrieMoaAcademicPartner extends Model
{
    use HasFactory;
    protected $table = 'grie_moa_academic_partner';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_moa_academic',
        'id_partner_university',
        'name',
    ];

    public function idMoaAcademic()
    {
        return $this->belongsTo(GrieMoaAcademic::class, 'id_moa_academic');
    }

    public function idPartnerUniversity()
    {
        return $this->belongsTo(MUniversity::class, 'id_partner_university');
    }

    // Custom attribute labels (can be useful for forms or API responses)
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_moa_academic' => 'Moa Academic',
            'id_partner_university' => 'Partner University',
            'name' => 'Name',
        ];
    }


}
