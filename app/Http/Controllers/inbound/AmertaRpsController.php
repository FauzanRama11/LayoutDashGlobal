<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AmertaRpsController extends Controller
{
    public function index()
    {
        return view('stu_inbound.amerta.template_rps');
    }
}
