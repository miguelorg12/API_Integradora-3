<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Usuario;
use App\Http\Controllers\Roles;
use App\Http\Controllers\SQL\Bebess;
use App\Http\Controllers\SQL\ContactoFamiliars;
use App\Http\Controllers\SQL\HistorialMedicoBebes;
use App\Http\Controllers\SQL\Sensors;
use App\Http\Controllers\EstadoDelBebes;
use App\Http\Controllers\SQL\Sensors_Incubadoras;
use App\Http\Controllers\SQL\Incubadoras;
use App\Http\Controllers\Store\HospitalCoordinator;
use App\Http\Controllers\HospitalHibrido;
use App\Http\Controllers\BebesHibrido;
use App\Http\Controllers\EstadoBebeHibrido;
use App\Http\Controllers\IncubadorasHibrido;
use App\Http\Controllers\SensoresHibrido;
use App\Http\Controllers\SensoresIncubadorasHibrido;
use App\Http\Controllers\SQL\Hospitals;
use App\Http\Controllers\Store\BebesCoordinator;
use App\Http\Controllers\Store\EstadoDelBebeCoordinator;
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
    Route::delete('/delete/{id}', [Usuario::class, 'delete'])->where('id', '[0-9]+');
});

//Rutas Roles
Route::prefix('roles')->group(function ($router) {
    Route::get('/list', [Roles::class, 'index']);
    Route::get('/oneRol/{id}', [Roles::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [Roles::class, 'store']);
    Route::put('/update/{id}', [Roles::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Roles::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas Hospitales
Route::prefix('hospital')->group(function ($router) {
    Route::get('/list', [Hospitals::class, 'index']);
    Route::get('/oneHospital/{id}', [Hospitals::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [HospitalHibrido::class, 'store']);
    Route::put('/update/{id}', [Hospitals::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Hospitals::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas Incubadoras
Route::prefix('incubadora')->group(function ($router) {
    Route::get('/list', [Incubadoras::class, 'index']);
    Route::get('incubadoras', [Incubadoras::class, 'Incubadoras']);
    Route::get('/oneIncubadora/{id}', [Incubadoras::class, 'show'])->where('id', '[0-9]+');
    Route::get('/incubadorasDisponibles', [Incubadoras::class, 'incubadorasDisponibles']);
    Route::post('/create', [IncubadorasHibrido::class, 'store']);
    Route::put('/update/{id}', [Incubadoras::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Incubadoras::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas Bebes
Route::prefix('bebes')->group(function ($router) {
    Route::get('/list', [Bebess::class, 'index']);
    Route::get('/oneBebe/{id}', [Bebess::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [BebesHibrido::class, 'store']);
    Route::put('/update/{id}', [Bebess::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Bebess::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas ContactoFamiliar
Route::prefix('contactoFamiliar')->group(function ($router) {
    Route::get('/list', [ContactoFamiliars::class, 'index']);
    Route::get('/oneContactoFamiliar/{id}', [ContactoFamiliars::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [ContactoFamiliars::class, 'store']);
    Route::put('/update/{id}', [ContactoFamiliars::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [ContactoFamiliars::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas HistorialMedicoBebe
Route::prefix('historial')->group(function ($router) {
    Route::get('/list', [HistorialMedicoBebes::class, 'index']);
    Route::get('/oneHistorialMedicoBebe/{id}', [HistorialMedicoBebes::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [HistorialMedicoBebes::class, 'store']);
    Route::put('/update/{id}', [HistorialMedicoBebes::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [HistorialMedicoBebes::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas EstadoDelBebe
Route::prefix('estadoDelBebe')->group(function ($router) {
    Route::get('/list', [EstadoDelBebes::class, 'index']);
    Route::get('/oneEstadoDelBebe/{id}', [EstadoDelBebes::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [EstadoBebeHibrido::class, 'store']);
    Route::put('/update/{id}', [EstadoDelBebes::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [EstadoDelBebes::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas Sensores
Route::prefix('sensores')->group(function ($router) {
    Route::get('/list', [Sensors::class, 'index']);
    Route::get('/oneSensor/{id}', [Sensors::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [SensoresHibrido::class, 'store']);
    Route::put('/update/{id}', [Sensors::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Sensors::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas Sensores_Incubadoras
Route::prefix('sensoresIncubadoras')->group(function ($router) {
    Route::get('/list', [Sensors_Incubadoras::class, 'index']);
    Route::get('/oneSensorIncubadora/{id}', [Sensors_Incubadoras::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [SensoresIncubadorasHibrido::class, 'store']);
    Route::put('/update/{id}', [Sensors_Incubadoras::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Sensors_Incubadoras::class, 'destroy'])->where('id', '[0-9]+');
});
