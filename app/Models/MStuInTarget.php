<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MStuInTarget extends Model
{
    use HasFactory;

    protected $table = 'm_stu_in_target';
    protected $primaryKey = 'id';

    protected $fillable = [
        'year',
        'id_fakultas',
        'target_pt',
        'target_ft',
    ];

    public static $rules = [
        'year' => 'required|integer',
        'id_fakultas' => 'required|integer',
        'target_pt' => 'required|integer',
        'target_ft' => 'required|integer',
    ];

    public function fakultas()
    {
        return $this->belongsTo(MFakultasUnit::class, 'id_fakultas', 'id');
    }

}
