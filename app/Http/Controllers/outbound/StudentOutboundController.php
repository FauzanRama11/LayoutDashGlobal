<?php

namespace App\Http\Controllers\outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentOutboundController extends Controller
{
    public function approval_dana()
    {
        return view('stu_inbound.approval_dana');
    }
    
    public function approval_pelaporan()
    {
        return view('stu_inbound.approval_pelaporan');
    }

    public function pengajuan_setneg()
    {
        return view('stu_outbound.pengajuan_setneg');
    }
}
