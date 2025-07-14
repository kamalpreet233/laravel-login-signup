<?php
use app\models\User;
use Illuminate\Validation\Rules\Email;
use Illuminate\Support\Facades\Password;

   
test('Password rest link send returns a successfull response', function () {
    $user = User::factory()->create([
        'email' => 'test@gmail.com',
    ]);
    $response = $this->post('/forgot-password',['email'=>$user->email]);
   $response->assertRedirectBack();
});

test('Password rest link can not send if email does not exits', function () {
    $user = User::factory()->create([
        'email' => 'test@gmail.com',
    ]);
    $response = $this->post('/forgot-password',['email'=>'wrongemail']);
    $response->assertRedirectBackWithErrors(['email']);

});

test('password reset feature gives a successfull response', function () {
    $user = User::factory()->create([
        'password'=>bcrypt('secret'),
        'email' => 'test@gmail.com',
    ]);
    $token = Password::createToken(User::first());
    $response = $this->post('/reset-password',['email'=>$user->email,'password'=>'secret','password_confirmation'=>'secret','token'=>$token]);
    $response->assertRedirectToRoute('login-view');
});



test('password can not be reset with wrong email', function () {
    $user = User::factory()->create([
        'password'=>bcrypt('secret'),
        'email' => 'test@gmail.com',
       
    ]);
    $token = Password::createToken(User::first());
    $response = $this->post('/reset-password',['email'=>'example@gmail.com','password'=>'secret','password_confirmation'=>'secret','token'=>$token]);
    $response->assertRedirectBack();
});

test('password can not be reset if less than 6 characters are provided', function () {
    $user = User::factory()->create([
        'password'=>bcrypt('secret'),
        'email' => 'test@gmail.com',
       
    ]);
    $token = Password::createToken(User::first());
    $response = $this->post('/reset-password',['email'=>'test@gmail.com','password'=>'secre','password_confirmation'=>'secre','token'=>$token]);
       $response->assertRedirectBack();


});

test('password can not be reset if confirm password and password does not match', function () {
    $user = User::factory()->create([
        'password'=>bcrypt('secret'),
        'email' => 'test@gmail.com',
       
    ]);
    $token = Password::createToken(User::first());
    $response = $this->post('/reset-password',['email'=>'test@gmail.com','password'=>'secret','password_confirmation'=>'secrett','token'=>$token]);
    $response->assertRedirectBack();
});

