<?php

namespace App\Http\Controllers\inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AmertaController extends Controller
{
    public function pendaftar()
    {
        return view('stu_inbound.amerta.pendaftar');
    }

    public function periode()
    {
        return view('stu_inbound.amerta.periode');
    }

    public function nominasi_matkul()
    {
        return view('stu_inbound.amerta.nominasi_matkul');
    }

    public function synced_data()
    {
        return view('stu_inbound.amerta.synced_data');
    }
    
}
