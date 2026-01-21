<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\CredentialsMail;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forget-password');
    }

    /**
     * Handle sending credentials to user's email
     */
    public function sendResetPassword(Request $request)
{
    // Validate the email
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
    ], [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.exists' => 'This email is not registered in our system.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Check if user recently requested (within 3 minutes)
    $lastRequest = session('last_password_request_time');
    $currentTime = now();

    if ($lastRequest && $currentTime->diffInMinutes($lastRequest) < 3) {
        return redirect()->back()
            ->with('error', 'Please wait 3 minutes before requesting again.')
            ->withInput();
    }

    // Fetch user data from database
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return redirect()->back()
            ->with('error', 'User not found in our system.')
            ->withInput();
    }

    try {
        // Send email directly without Mailable
        Mail::send([], [], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Account Credentials')
                ->setBody(
                    '<h3>Your account credentials:</h3>
                     <p>Username: ' . $user->email . '</p>
                     <p>Password: ' . $user->password_hashed . '</p>',
                    'text/html'
                );
        });

        // Store the request time in session
        session(['last_password_request_time' => $currentTime]);

        return redirect()->back()
            ->with('success', 'Username and password have been sent to your email successfully!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to send email. Please try again later.')
            ->withInput();
    }
}

}