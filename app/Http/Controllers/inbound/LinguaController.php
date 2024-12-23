<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinguaController extends Controller
{
    public function pendaftar()
    {
        return view('stu_inbound.lingua.pendaftar');
    }
    public function periode()
    {
        return view('stu_inbound.lingua.periode');
    }
}
