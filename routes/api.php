<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Usuario;
use App\Http\Controllers\SQL\Bebe;
use App\Http\Controllers\SQL\ContactoFamiliars;
use App\Http\Controllers\SQL\HistorialMedicoBebes;
use App\Http\Controllers\SQL\Sensors;
use App\Http\Controllers\SQL\Sensors_Incubadoras;
use App\Http\Controllers\SQL\Incubadoras;
use App\Http\Controllers\SQL\Hospitals;
use App\Http\Controllers\Store\BebesCoordinator;
use App\Http\Controllers\Store\EstadoDelBebeCoordinator;
use App\Http\Controllers\Store\HospitalCoordinator;
use App\Http\Controllers\Store\IncubadoraCoordinator;
use App\Http\Controllers\Store\Sensores_IncubadorasCoordinator;
use App\Http\Controllers\Store\SensoresCoordinator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which  
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Rutas Usuario
Route::prefix('auth')->group(function ($router) {
    Route::post('hola', [AuthController::class, 'hola']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logCode', [AuthController::class, 'logCode'])->middleware('active');
    Route::post('verifyCode', [AuthController::class, 'verifyCode']);
    Route::post('me', [AuthController::class, 'me']);
    Route::get('activate/{user}', [AuthController::class, 'activate'])
        ->name('activate')->middleware('signed');
    Route::get('checkActive/{user}', [AuthController::class, 'checkActive'])
        ->name('checkActive');
    Route::post('verifyToken', [AuthController::class, 'verifyToken'])->middleware('active');
});

//Rutas Usuarios
Route::prefix('user')->group(function ($router) {
    Route::get('/list', [Usuario::class, 'index']);
    Route::get('/oneUser/{id}', [Usuario::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [Usuario::class, 'store']);
    Route::put('/update/{id}', [Usuario::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Usuario::class, 'destroy'])->where('id', '[0-9]+');
});
