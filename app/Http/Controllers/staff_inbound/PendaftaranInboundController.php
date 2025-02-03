<?php

namespace App\Http\Controllers\staff_inbound;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendaftaranInboundController extends Controller
{
    public function external_sta_in(){
        $country = DB::table('m_country')->get();
        $univ = DB::table('m_university')->get();
    
        return view('sta_inbound.registrasi_sta_in', [
            'univ' => $univ,
            'country' => $country
        ]);
    }
    
}
