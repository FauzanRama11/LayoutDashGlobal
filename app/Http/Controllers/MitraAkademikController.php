<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MitraAkademikController extends Controller
{
    public function daftarmitra()
    {
        return view('mitra_akademik.daftarmitra');
    }
    public function submitmitra()
    {
        return view('mitra_akademik.submitmitra');
    }
}

