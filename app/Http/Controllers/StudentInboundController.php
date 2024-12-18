<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentInboundController extends Controller
{
    public function program_fak()
    {
        return view('gmp.program_fak');
    }
    public function program_age()
    {
        return view('gmp.program_age');
    }
}
