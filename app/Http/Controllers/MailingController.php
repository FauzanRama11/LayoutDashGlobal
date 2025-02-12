<?php

namespace App\Http\Controllers;

use App\Mail\AccountVerificationMail;
use App\Mail\EmailVerificationMail;
use App\Mail\ForgetPasswordMail;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailingController extends Controller
{

    public function email_verification($encryptedUsername, $encryptedEmail) {
        // dd(Crypt::encryptString("XZY9690"));

        // try {
        //     $sent = Mail::to(Crypt::decryptString($encryptedEmail))->send(new EmailVerificationMail($encryptedUsername, $encryptedEmail));
        //     if ($sent) {
        //         return "Email telah dikirim";
        //     } else {
        //         return "Email gagal dikirim ya.";
        //     }
        // }catch (\Exception $e){
        //     return "Email gagal dikirim: " . $e->getMessage();   
        // }
        return view('emails.email_verification', compact('encryptedUsername', 'encryptedEmail'));
    }

    public function verify($encryptedUsername, $encryptedEmail) {
    $username = Crypt::decryptString($encryptedUsername);
    $email = Crypt::decryptString($encryptedEmail);
    try {
    
        $user = User::where('username', $username)->where('email', $email)->first();
        if ($user) {
            if(empty($user->email_verified_at )){
                $user->email_verified_at = now();
                $user->save();
                return response()->json(['success' => true, 'message' => 'Email successfully verified.']);
            }else{
                return response()->json(['success' => true, 'message' => 'You have been verified']);
            }
        } else {
            return response()->json(['success' => false, 'message' => $username], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Invalid verification link.'], 400);
    }
}

public function password_verification($encryptedUsername, $program_id) {
        $username = Crypt::decryptString($encryptedUsername);
        $ver_code = DB::table('users')
        ->select('user_token')
        ->where('username', '=', $username)
        ->first();
        $program = DB::table('m_stu_in_programs')
        ->select('name', 'pic', 'corresponding')
        ->where('id', '=', $program_id)
        ->first();
        $nama = DB::table('m_stu_in_peserta')
        ->select('nama')
        ->where('passport_no', '=', $username)
        // ->orWhere('student_id_no', '=', $username)
        ->first();

        $email = DB::table('m_stu_in_peserta')
        ->select('email')
        ->where('passport_no', '=', $username)
        // ->orWhere('student_id_no', '=', $username)
        ->first();
        

        try {
            $sent = Mail::to($email->email)->send(new AccountVerificationMail($nama, $program, $username, $encryptedUsername, $ver_code));
            if ($sent) {
                // \Log::info('Email sent successfully to ' . $username);
                return "Email telah dikirim";
            } else {
                return "Email gagal dikirim ya.";
            }
        }catch (\Exception $e){
            return "Email gagal dikirim: " . $e->getMessage();   
        }

        // dd($ver_code);
    // return view('emails.confirm_pass', compact('encryptedUsername', 'username', 'ver_code', 'program', 'nama'));
}
public function forget_password($encryptedUsername) {
    $username = Crypt::decryptString($encryptedUsername);
    $ver_code = DB::table('users')
    ->select('user_token')
    ->where('username', '=', $username)
    ->first();
    $nama = DB::table('m_stu_in_peserta')
    ->select('nama')
    ->where('passport_no', '=', $username)
    // ->orWhere('student_id_no', '=', $username)
    ->first();
    $email = DB::table('m_stu_in_peserta')
        ->select('email')
        ->where('passport_no', '=', $username)
        // ->orWhere('student_id_no', '=', $username)
        ->first();

    try {
        $sent = Mail::to($email->email)->send(new ForgetPasswordMail($nama, $username, $encryptedUsername, $ver_code));
        if ($sent) {
            // \Log::info('Email sent successfully to ' . $username);
            return "Email telah dikirim";
        } else {
            return "Email gagal dikirim ya.";
        }
    }catch (\Exception $e){
        return "Email gagal dikirim: " . $e->getMessage();   
    }

    // dd($ver_code);
// return view('emails.forgot_pass', compact('encryptedUsername', 'username', 'ver_code', 'nama'));
}

}
