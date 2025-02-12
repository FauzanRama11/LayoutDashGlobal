<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function forget_password(){
        // ini akai parameter internal atau external, kalau external langsung disuruh isi email untuk checking
        // kalau internal langsung kirim email aja 
    }
    public function checking_email(Request $request){
        // kalau verified kirim email kalau ga verified gabisa
        // bisa jadi verified tapi akun tidak aktif
        try{    
            $validated = $request->validate([
            'emailConfirm' => 'required|email'
        ]);

        $email = $request->input('emailConfirm');
        $ver = User::where(strtolower('email'), strtolower($email))->first();

        if (!$ver || empty($ver->email_verified_at)) {
            return response()->json(['success' => false, 'message' => "Your email hasn't been verified yet."], 500);
        }

        $user = User::where('username', $ver->username)->first();
        if ($user) {
           if($ver->is_active == "True"){ 
            $user->user_token = Str::random(length: 8);
            $user->save();

            $encryptedUsername = Crypt::encryptString($user->username);
            // Mail::to($user->email)->send(new ResetPasswordMail($user->user_token));
            // http://127.0.0.1:8000/email-forget-pass/

            // dd($encryptedUsername);

            return response()->json([
                'status' => 'success',
                'message' => 'Your email is verified, please check your email to reset your password.',
                'encryptedUsername' => $encryptedUsername
            ]);}
            else{
                return response()->json(['success' => false, 'message' => "Your account hasn't activated yet."], 404);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Invalid email.'], 400);
    }
   
    }
    public function verifyBase($encryptedUsername){
        // $encryptedUsername = Crypt::encryptString($uname);
        return view('auth.make-password', data: ['encryptedUsername' => $encryptedUsername]);
    }

    public function verifyCode(Request $request, $encryptedUsername)
    {
    try {
        $validated = $request->validate([
            'code' => 'required|string|max:10'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid code.',
            'errors'  => $e->errors() 
        ], 400);
    }

    $username = Crypt::decryptString($encryptedUsername);
    
    $user = User::where('username', $username)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    $validCode = $user->user_token; 

    if ($request->code === $validCode) {
        session(['verified' => true]);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid code.'], 400);
}

public function setPassword(Request $request, $encryptedUsername)
{
    // Validasi password
    $validator = Validator::make($request->all(), [
        'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }


    $username = Crypt::decryptString($encryptedUsername);
    $user = User::where('username', $username)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    $user->password = bcrypt($request->password);
    $user->user_token = null; 
    $user->save(); 

    return response()->json(['success' => true, 'message' => 'Password set successfully.']);
}
    public function changepass($encryptedUsername)
    {
        $username = Crypt::decryptString($encryptedUsername);
        $data = User::where('username', $username)->first();

        return view('auth.reset-password2', compact('data'));
    }

    public function store_changepass(Request $request, $encryptedUsername)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'old_password' => 'required|string|min:5',
                'new_password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6',
            ]);
    
     
            $decUsername = Crypt::decryptString($encryptedUsername);
            
            $user = User::where('username', $decUsername)->firstOrFail();
            
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Old password is incorrect.'
                ], 400); 
            }

            if ($request->new_password !== $request->confirm_password) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'New password and confirmation do not match.'
                ], 400); 
            }
            
            $user->password = Hash::make($request->new_password);
            $user->save();
    
            
            // return redirect()->route('login')->with('success', 'Password changed successfully.');
            return response()->json(['status' => 'success', 'message' => 'Password changed successfully.', 'redirect' => route('mahasiswa.dashboard')]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    
    }

    
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
