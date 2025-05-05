<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryUser; // Temporary user storage
use App\Models\User; // Main user model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:temporary_users',
        'dob_day' => 'required|integer|between:1,31',
        'dob_month' => 'required|integer|between:1,12',
        'dob_year' => 'required|integer|min:1900|max:' . date('Y'),
        'email' => 'required|string|email|max:255|unique:temporary_users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $verificationCode = Str::random(5);

    $temporaryUser = TemporaryUser::create([
        'name' => $request->name,
        'username' => $request->username,
        'dob_day' => $request->dob_day,
        'dob_month' => $request->dob_month,
        'dob_year' => $request->dob_year,
        'email' => $request->email,
        'verification_code' => $verificationCode,
        'password' => Hash::make($request->password),
    ]);

    try {
        Mail::to($temporaryUser->email)->send(new EmailVerification($verificationCode));
        Log::info('Attempted to send email to ' . $temporaryUser->email);
    } catch (\Exception $e) {
        Log::error('Mail failed to send', ['error' => $e->getMessage()]);

    }

    return redirect()->route('verify.code.form');
}
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $temporaryUser = TemporaryUser::where('verification_code', $request->verification_code)->first();

        if (!$temporaryUser) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.']);
        }

        // Create User
        $user = User::create([
            'name' => $temporaryUser->name,
            'username' => $temporaryUser->username,
            'dob_day' => $temporaryUser->dob_day,
            'dob_month' => $temporaryUser->dob_month,
            'dob_year' => $temporaryUser->dob_year,
            'email' => $temporaryUser->email,
            'password' => $temporaryUser->password,
            'email_verified_at' => now(),
        ]);

        $temporaryUser->delete();

        return redirect()->route('home');
    }
}
