<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentInboundController extends Controller
{
    public function approval_dana()
    {
        return view('stu_inbound.approval_dana');
    }
    
    public function approval_pelaporan()
    {
        return view('stu_inbound.approval_pelaporan');
    }
}
