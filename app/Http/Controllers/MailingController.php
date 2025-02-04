<?php

namespace App\Http\Controllers;

use App\Mail\MailingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class MailingController extends Controller
{
    public function index(){
        // Mail::to(auth()->user()->email)->send(new MailingMail());

    try {
        Mail::to("nabila.dien.jasmine-2021@ftmm.unair.ac.id")->send(new MailingMail());
        return "Email telah dikirim";
    } catch (\Exception $e) {
        return "Email gagal dikirim: " . $e->getMessage();
    }
}

 
	}

