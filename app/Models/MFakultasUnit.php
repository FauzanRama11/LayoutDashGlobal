<?php

namespace App\Models;

    use HasFactory;

    protected $table = 'm_fakultas_unit';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_ind',
        'nama_eng',
        'kode',
        'tipe',
        'wadek3',
        'staf',
        'id_cyber',
        'dekan',
    ];

}
