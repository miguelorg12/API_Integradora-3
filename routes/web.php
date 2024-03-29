<?php

use App\Http\Controllers\Usuario;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/password', [AuthController::class, 'recoveryPassword'])->name('reset');
Route::post('/password_reset', [AuthController::class, 'resetPassword'])->name('password');
