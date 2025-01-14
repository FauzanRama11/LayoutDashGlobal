<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrieMoaAcademicScope extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'grie_moa_academic_scope';

    // Specify the primary key if it's not the default 'id'
    protected $primaryKey = 'id';

    // Disable timestamps if your table doesn't have created_at and updated_at columns
    public $timestamps = false;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'id_moa_academic',
        'id_collaboration_scope',
    ];

    // Define relationships
    public function idMoaAcademic()
    {
        return $this->belongsTo(GrieMoaAcademic::class, 'id_moa_academic');
    }

    public function idCollaborationScope()
    {
        return $this->belongsTo(MCollaborationScope::class, 'id_collaboration_scope');
    }
}
