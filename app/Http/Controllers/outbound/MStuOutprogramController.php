<?php

namespace App\Http\Controllers\outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MStuOutprogramController extends Controller
{
    public function program_fak()
    {
        return view('stu_outbound.program_fak');
    }
    public function program_age()
    {
        return view('stu_outbound.program_age');
    }
}
