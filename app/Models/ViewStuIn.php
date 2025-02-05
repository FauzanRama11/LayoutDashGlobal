<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewStuIn extends Model
{
    use HasFactory;

    protected $table = 'view_student_inbound'; // Nama view di PostgreSQL
    protected $primaryKey = 'id'; // Menggunakan ID dari row_number()
    public $incrementing = false; // Karena ini bukan auto-increment asli
    public $timestamps = false; // View tidak memiliki kolom timestamps
}
