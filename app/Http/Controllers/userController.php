<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;



class userController extends Controller
{

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',

        ]);
        $user = User::create($request->all());
        event(new Registered($user));
        Auth::login($user);
        $user = Auth::user();
        $user = $user->name;
        session(['user' => $user]);
        return redirect()->route('webpage');
        // return redirect()->route('login-view');
        // return redirect()->route('verification.send');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',

        ]);
        $userdata = $request->only('email', 'password');
        if (Auth::attempt($userdata)) {
            $user = Auth::user();
            $user = $user->name;
            session(['user' => $user]);
            // $maxIdleTime = config('session.lifetime') * 60;
            return redirect()->route('webpage');
        } else {
            return redirect()->back()->with('error', 'user not found');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('user');
        return redirect()->route('login');
    }
    public function passwordLinkSent(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
    public function passwordReset(Request $request){
        $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only( 'email','password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PasswordReset
        ? redirect()->route('login-view')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
    }

}