<?php
use app\Models\User;
test('only admin can change user role', function () {
    $user1 = User::factory()->create([
        'id' => 1,
        'role' => 'admin',
        'is_admin' => true
    ]);
    $user1 = User::factory()->create([
        'id' => 2,
        'role' => 'client',
        'is_admin' => false

    ]);
    $user1 = User::find(1);
    $user2 = User::find(2);

    $response = $this->put('/update/role', [
        "role" => [
            1 => 'admin',
            2 => 'client'
        ]
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/admin');
    $response->assertSessionMissing('error');
});

test('user can not change access admin dashboard', function () {
    $user = User::factory()->create([
        'is_admin' => false
    ]);
    $this->actingAs($user);
    $response = $this->get('/admin');

    $response->assertStatus(302);
    $response->assertRedirect('/webpage');
    // $response->assertSessionMissing('error');
});
