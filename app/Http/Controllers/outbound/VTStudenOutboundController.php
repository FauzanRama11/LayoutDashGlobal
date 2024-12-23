<?php

namespace App\Http\Controllers\outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VTStudenOutboundController extends Controller
{
    public function index()
    {
        return view('stu_outbound.view_peserta');
    }
}
