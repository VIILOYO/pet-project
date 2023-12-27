<?php

it('Авторизация пользователя', function () {
    $data = [
        'email' => fake()->unique()->email,
        'password' => fake()->password
    ];

    test()
        ->postJson(route('auth.registration', array_merge(
            $data,
            ['name' => fake()->word]
        )))
        ->assertStatus(200);

    $response = test()
        ->postJson(route('auth.login', $data))
        ->assertStatus(200);

    $response->assertStatus(200);

    expect($response->original)->toBeObject();
});

it('Ошибка при авторизация пользователя с неверными данными', function () {
    $data = [
        'email' => fake()->unique()->email,
        'password' => fake()->password
    ];

    test()
        ->postJson(route('auth.registration', array_merge(
            $data,
            ['name' => fake()->word]
        )))
        ->assertStatus(200);

    test()
        ->postJson(route('auth.login', [
            'email' => $data['email'],
            'password' => fake()->password
        ]))
        ->assertStatus(401);
})->repeat(5);
