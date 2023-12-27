<?php

it('Получение авторизованного пользователя', function () {
    $response = asUser()
        ->getJson(route('users.me'));

    $response->assertStatus(200);

    expect($response->original)->toBeObject();
});

it('Ошибка доступа при получении авторизованного пользователя', function () {
    test()
        ->getJson(route('users.me'))
        ->assertStatus(403);
});
