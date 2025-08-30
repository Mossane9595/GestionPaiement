<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControleurAuthentification;


Route::get('/', function () {
    return view('welcome');
});
Route::prefix('web')->group(function () {
    Route::post('/inscription', [AuthentificationController::class, 'inscription']);
    Route::post('/connexion', [AuthentificationController::class, 'connexion']);
});