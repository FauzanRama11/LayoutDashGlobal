<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MitraAkademikController extends Controller
{
    public function daftarmitra()
    {
        return view('fakultas.daftarmitra');
    }
    public function submitmitra()
    {
        return view('fakultas.submitmitra');
    }
}

