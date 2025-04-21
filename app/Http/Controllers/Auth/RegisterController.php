<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryUser; // New model for temporary user storage
use App\Models\User; // Make sure to include the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

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
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Generate verification code
    $verificationCode = Str::random(40);

    // Store temporary user details
    $temporaryUser = TemporaryUser::create([
        'name' => $request->name,
        'username' => $request->username,
        'dob_day' => $request->dob_day,
        'dob_month' => $request->dob_month,
        'dob_year' => $request->dob_year,
        'email' => $request->email,
        'verification_code' => $verificationCode,
    ]);

    // Send verification email
    Mail::to($temporaryUser->email)->send(new EmailVerification($verificationCode));
    if (Mail::failures()) {
        \Log::error('Mail failed to send', ['errors' => Mail::failures()]);
    }
    // Redirect to the verification code input page
    return redirect()->route('verify.code.form')->with('status', 'Please check your email for the verification code to complete your registration.');
}
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $temporaryUser = TemporaryUser::where('verification_code', $request->verification_code)->first();

        if ($temporaryUser) {
            // Create the actual user
            $user = User::create([
                'name' => $temporaryUser->name,
                'username' => $temporaryUser->username,
                'dob_day' => $temporaryUser->dob_day,
                'dob_month' => $temporaryUser->dob_month,
                'dob_year' => $temporaryUser->dob_year,
                'email' => $temporaryUser->email,
                'password' => Hash::make('your_default_password_if_needed'), // You can modify this later to allow users to set their own password
                'email_verified_at' => now(), // Mark as verified
            ]);

            // Optionally, delete the temporary record after creating the user
            $temporaryUser->delete();

            return redirect()->route('login')->with('status', 'Registration completed successfully. You can log in now.');
        }

        return redirect()->back()->withErrors(['verification_code' => 'Invalid verification code.']);
    }
}