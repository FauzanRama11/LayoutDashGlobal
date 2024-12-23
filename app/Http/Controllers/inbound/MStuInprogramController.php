<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MStuInprogramController extends Controller
{
    public function program_fak()
    {
        return view('stu_inbound.program_fak');
    }
    public function program_age()
    {
        return view('stu_inbound.program_age');
    }
    public function target()
    {
        return view('stu_inbound.target');
    }
}
