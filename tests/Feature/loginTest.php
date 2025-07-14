<?php

use App\Models\User;
use function Pest\Laravel\actingAs;





test('user and admin login returns a successfull response', function () {
    $user = User::factory()->create(
        [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('secret'),
        ]
    );
    $response = $this->post('/login', ['email' => $user->email, 'password' => 'secret', 'remember' => 'on']);
    $this->assertAuthenticated($guard = null);
    $response->assertStatus(302);
    $response->assertRedirect('/admin');


    $user = User::factory()->create(
        [
            'name' => 'test',
            'email' => 'test2@gmail.com',
            'password' => bcrypt('secret'),
        ]
    );
    $response = $this->post('/login', ['email' => $user->email, 'password' => 'secret', 'remember' => 'on']);

    $this->assertAuthenticated($guard = null);
    $response->assertRedirect('/admin');

    $response = $this->get('/webpage');
    $response->assertStatus(200);


});



test('user can not login with wrong password', function () {
    $user = User::factory()->create([
        'name' => 'name',
        'email' => 'test@gmail.com',
        'password' => bcrypt('secret')
    ]);
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong password',
    ]);
    $this->assertGuest($guard = null);
    $response->assertSessionHas('error', 'user not found');
});


test('user can not login with wrong email', function () {
    $user = User::factory()->create([
        'name' => 'name',
        'email' => 'test@gmail.com',
        'password' => bcrypt('secret')
    ]);
    $response = $this->post('/login', [
        'email' => 'email@gmail.com',
        'password' => 'secret',
    ]);
    $this->assertGuest($guard = null);
    $response->assertSessionHas('error', 'user not found');

});



test('ensure that the validation works properly', function () {
    $user = User::factory()->create([
        'name' => 'name',
        'email' => 'test@gmail.com',
        'password' => bcrypt('secret')
    ]);
    $response = $this->post('/login', [
        'email' => 'email',
        'password' => 'secre',
    ]);
    $this->assertGuest($guard = null);
    $response->assertSessionHasErrors('email');
    $response->assertSessionHasErrors('password');

});

