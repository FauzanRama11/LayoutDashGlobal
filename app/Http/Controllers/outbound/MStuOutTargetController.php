<?php

namespace App\Http\Controllers\outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MStuOutTargetController extends Controller
{
    public function index()
    {
        return view('stu_outbound.target');
    }
}
