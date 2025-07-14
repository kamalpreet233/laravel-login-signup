<?php
use app\Models\User;

test('logout page give a successfull response', function () {
    $user = User::factory()->create([
        'name' => 'name',
        'email' => 'test@gmail.com',
        'password' => bcrypt('secret')
    ]);
    $this->actingAs($user);
    $response = $this->get('/logout');
    $this->assertGuest();
    $response->assertRedirect('/login');
});
