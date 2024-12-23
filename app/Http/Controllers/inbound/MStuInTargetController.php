<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MStuInTargetController extends Controller
{
    public function index()
    {
        return view('stu_inbound.target');
    }
}
