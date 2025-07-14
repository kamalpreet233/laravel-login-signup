<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
// use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\SessionCheck;
use Illuminate\Http\Request;
use App\Models\User;






Route::get('/', function () {
    return view('login');
});

Route::view('register', 'register')->name('register');
Route::view('login', 'login')->name('login-view');



Route::middleware('web')->group(function () {
    Route::post('/signup', [userController::class, 'signup'])->name('signup');
    Route::post('/login', [userController::class, 'login'])->name('login');
});

Route::get('/webpage', function () {
    return view('webpage');
})->name('webpage')->middleware('verified','client_check','auth.session','auth');

Route::get('/admin',[userController::class,'show'])->middleware('is_admin','auth.session','auth')->name('admin');
Route::get('/fetch-users',[userController::class]);
Route::put('/update/role',[userController::class,'updateRole'])->name('updaterole');
route::get('logout', [userController::class, 'logout'])->name('logout');


Route::get('/email/verfiy', function () {

    return view('email.verify');
})->middleware('auth')->name('verification.notice');



Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect("/webpage");
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {

    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


route::get('/email/sent', function () {
    Mail::raw('Hello World!', function ($msg) {
        $msg->to('test@gmail.com')->subject('Test Email');
    });

});


// password reset routes




Route::get('/forgot-password', function () {
    return view('password.forget-password');
})->middleware('guest')->name('password.request');




Route::post('/forgot-password', [userController::class, 'passwordLinkSent'])->middleware('guest')->name('password.email');



Route::get('/reset-password/{token}', function (string $token,Request $request) {
    return view('password.reset-password', ['token' => $token,'email'=>$request->query('email')]);
})->middleware('guest')->name('password.reset');



Route::post('/reset-password', [userController::class, 'passwordReset'])->middleware('guest')->name('password.update');