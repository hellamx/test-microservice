<?php

use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Http\Controllers\HomeController;

// Horizon
Route::get('/horizon', [HomeController::class, 'index']);

// Отдаем всё на фронт
Route::name('client')->get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
