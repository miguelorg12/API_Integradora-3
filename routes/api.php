<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Usuario;
use App\Http\Controllers\Roles;
use App\Http\Controllers\SQL\Bebess;
use App\Http\Controllers\SQL\ContactoFamiliares;
use App\Http\Controllers\SQL\HistorialMedicoBebes;
use App\Http\Controllers\SQL\Sensoress;
use App\Http\Controllers\SQL\EstadoBebe;
use App\Http\Controllers\SQL\SensoresIncubadorass;
use App\Http\Controllers\SQL\Incubadoras;
use App\Http\Controllers\EstadoIncubadoraController;
use App\Http\Controllers\HospitalHibrido;
use App\Http\Controllers\BebesHibrido;
use App\Http\Controllers\EstadoBebeHibrido;
use App\Http\Controllers\IncubadorasHibrido;
use App\Http\Controllers\SensoresHibrido;
use App\Http\Controllers\SensoresIncubadorasHibrido;
use App\Http\Controllers\SQL\Hospitals;

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

Route::get('/server', 'serverController@index');


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
    Route::post('verifyToken', [AuthController::class, 'verifyToken']);
    Route::post('restablecer', [AuthController::class, 'restablecer']);
    Route::get('recoveryPassword/{user}', [AuthController::class, 'recoveryPassword'])->name('recoveryPassword');
});

//Rutas Usuarios
Route::prefix('user')->group(function ($router) {
    Route::get('/list', [Usuario::class, 'index']);
    Route::get('/oneUser/{id}', [Usuario::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [Usuario::class, 'store']);
    Route::put('/update/{id}', [Usuario::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Usuario::class, 'delete'])->where('id', '[0-9]+');
    Route::get('/getRole', [Usuario::class, 'getRole']);
    Route::get('/isActive', [Usuario::class, 'isActive']);
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
    Route::get('/listNtoken', [Hospitals::class, 'hospitals']);
    Route::get('/oneHospital/{id}', [Hospitals::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [HospitalHibrido::class, 'store']);
    Route::put('/update/{id}', [Hospitals::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Hospitals::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas EstadoIncubadora
Route::prefix('estadoIncubadora')->group(function ($router) {
    Route::get('/list', [EstadoIncubadoraController::class, 'index']);
    Route::get('/oneEstadoIncubadora/{id}', [EstadoIncubadoraController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [EstadoIncubadoraController::class, 'store']);
    Route::put('/update/{id}', [EstadoIncubadoraController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [EstadoIncubadoraController::class, 'destroy'])->where('id', '[0-9]+');
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
    Route::get('/list', [ContactoFamiliares::class, 'index']);
    Route::get('/oneContactoFamiliar/{id}', [ContactoFamiliares::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [ContactoFamiliares::class, 'store']);
    Route::put('/update/{id}', [ContactoFamiliares::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [ContactoFamiliares::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas HistorialMedicoBebe
Route::prefix('historial')->group(function ($router) {
    Route::get('/list', [HistorialMedicoBebes::class, 'index']);
    Route::get('/oneHistorial/{id}', [HistorialMedicoBebes::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [HistorialMedicoBebes::class, 'store']);
    Route::put('/update/{id}', [HistorialMedicoBebes::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [HistorialMedicoBebes::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas EstadoDelBebe
Route::prefix('estadoDelBebe')->group(function ($router) {
    Route::get('/list', [EstadoBebe::class, 'index']);
    Route::get('/oneEstadoDelBebe/{id}', [EstadoBebe::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [EstadoBebeHibrido::class, 'store']);
    Route::put('/update/{id}', [EstadoBebe::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [EstadoBebe::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas Sensores
Route::prefix('sensores')->group(function ($router) {
    Route::get('/list', [Sensoress::class, 'index']);
    Route::get('/oneSensor/{id}', [Sensoress::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [SensoresHibrido::class, 'store']);
    Route::put('/update/{id}', [Sensoress::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [Sensoress::class, 'destroy'])->where('id', '[0-9]+');
});

//Rutas Sensores_Incubadoras
Route::prefix('sensoresIncubadoras')->group(function ($router) {
    Route::get('/list', [SensoresIncubadorass::class, 'index']);
    Route::get('/oneSensorIncubadora/{id}', [SensoresIncubadorass::class, 'show'])->where('id', '[0-9]+');
    Route::post('/create', [SensoresIncubadorasHibrido::class, 'store']);
    Route::put('/update/{id}', [SensoresIncubadorass::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/delete/{id}', [SensoresIncubadorass::class, 'destroy'])->where('id', '[0-9]+');
});
