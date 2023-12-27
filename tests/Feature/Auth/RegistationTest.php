<?php

use Illuminate\Testing\TestResponse;

it('Регистрация пользователя', function () {
    $data = [
        'name' => fake()->name,
        'email' => fake()->unique()->email,
        'password' => fake()->password,
    ];

    /** @var TestResponse $response */
    $response = test()
        ->postJson(route('auth.registration', $data));

    $response->assertStatus(200);

    $user = test()
        ->actingAs($response->original?->user)
        ->getJson(route('users.me'))
        ->original;

    expect($user?->name)->toBe($data['name'])
        ->and($user?->email)->toBe($data['email']);
});

it('Ошибка регистрации пользователя с одинаковой почтой', function () {
    $email = fake()->unique()->email;

    // Создание пользователя с email
    test()
        ->postJson(route('auth.registration',[
            'name' => fake()->name,
            'email' => $email,
            'password' => fake()->password,
        ]))
        ->assertStatus(200);

    // Создание пользователя с уже существующим email
    test()
        ->postJson(route('auth.registration',[
            'name' => fake()->name,
            'email' => $email,
            'password' => fake()->password,
        ]))
        ->assertStatus(422);
});
