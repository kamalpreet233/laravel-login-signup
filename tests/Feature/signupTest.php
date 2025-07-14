<?php

use function Pest\Laravel\actingAs;
use app\Models\User;




test('signup returns a successfull response', function () {
    $user = user::factory()->make([
        'name' => 'test',
        'email' => 'test@gmail.com',
        'password' => bcrypt('secret'),
    ]);

    $response = $this->post('/signup', ['name' => $user->name, 'email' => $user->email, 'password' => $user->password, 'password_confirmation' => $user->password]);
    $this->actingAs($user);
    $response->assertRedirect('/webpage');
    $this->assertAuthenticatedAs($user);



    $user = user::factory()->make([
        'name' => 'test',
        'email' => 'test2@gmail.com',
        'password' => bcrypt('secret'),
    ]);

    $response = $this->actingAs($user)->post('/signup', ['name' => $user->name, 'email' => $user->email, 'password' => $user->password, 'password_confirmation' => $user->password]);
    $response->assertRedirect('/webpage');
    $this->actingAs($user);
    $this->assertAuthenticatedAs($user);


});

test('first registerd user get is_admin true ', function () {
    $user = User::factory()->make([
        'name' => 'test',
        'email' => 'test@gmail.com',
        'password' => bcrypt('secret'),
    ]);

    $response = $this->post('/signup', ['name' => $user->name, 'email' => $user->email, 'password' => $user->password, 'password_confirmation' => $user->password]);
    $this->actingAs($user);
    $response->assertRedirect('/webpage');
    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('users', [
        'is_admin'=>true
    ]);

});

test('other than first user registration gets is_admin false', function () {
    User::factory()->create([
        'name' => 'test',
        'email' => 'test@gmail.com',
        'password' => bcrypt('secret'),
        'is_admin'=>true
    ]);


    $response = $this->post('/signup', ['name' => 'test', 'email' => 'test2@gmail.com', 'password' => 'secret', 'password_confirmation' => 'secret']);
    $response->assertRedirect('/webpage');
    $this->assertDatabaseHas('users',[
        'is_admin'=>false
    ]);


});





test('Ensure that the validation works properly', function () {
    $user = user::factory()->make([
        'name' => '59',
        'email' => 'testgmail4242co',
    ]);

    $response = $this->post('/signup', ['name' => $user->name, 'email' => $user->email, 'password' => 's', 'password_confirmation' => 's']);
    $response->assertInvalid(['name', 'email','password']);

});

