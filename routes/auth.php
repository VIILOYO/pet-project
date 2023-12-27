<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/registration', [AuthController::class, 'registration'])->name('registration');
Route::post('/login', [AuthController::class, 'login'])->name('login');

