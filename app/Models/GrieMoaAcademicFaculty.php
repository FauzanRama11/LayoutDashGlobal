<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrieMoaAcademicFaculty extends Model
{
    use HasFactory;

    protected $table = 'grie_moa_academic_faculty';
    protected $primaryKey = 'id';
        public $timestamps = false;
    protected $fillable = [
        'id_moa_academic',
        'id_faculty',
        'name',
    ];

    public function idMoaAcademic()
    {
        return $this->belongsTo(GrieMoaAcademic::class, 'id_moa_academic');
    }

    // public function idFaculty()
    // {
    //     return $this->belongsTo(MFakultasUnit::class, 'id_faculty');
    // }

    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_moa_academic' => 'Id Moa Academic',
            'id_faculty' => 'Id Faculty',
            'name' => 'Name',
        ];
    }

}
