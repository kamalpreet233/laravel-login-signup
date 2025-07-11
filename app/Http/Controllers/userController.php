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
use function PHPUnit\Framework\returnArgument;



class userController extends Controller
{

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',

        ]);
        $count = User::count();
        $boolean = $count === 0 ? true : false;
        $value = $count === 0 ? 'admin' : 'client';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $value,
            'is_admin' => $boolean,
        ]);
        // event(new Registered($user));
        Auth::login($user);
        $user = Auth::user();
        $user = $user->name;
        session(['user' => $user]);



        return redirect()->route('webpage');

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
            return redirect()->route('admin');
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
    public function passwordReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
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

    public function show(Request $request)
    {
        $posts = User::where('is_admin', '=', false)->get();
        $admin = User::where('is_admin', '=', true)->first();

        return view('admin.admin', compact('posts'), compact('admin'));
    }
    public function updaterole(Request $request)
    {
        $posts = User::where('is_admin', '=', false)->get();
        foreach ($posts as $index => $post) {
            $updated = $post->update([
                'role' => $request->role[$post->id],
            ]);
        }
        if ($updated) {
            return redirect()->route('admin')->with('success', 'changes updated successfully');
        } else {
            return redirect()->route('admin')->with('error', 'changes not updated');
        }

    }

}