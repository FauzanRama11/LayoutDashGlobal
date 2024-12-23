<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinguaRpsController extends Controller
{
    public function index()
    {
        return view('stu_inbound.lingua.template_rps');
    }
}
