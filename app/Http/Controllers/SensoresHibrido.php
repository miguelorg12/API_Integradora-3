<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoSensores_Incubadoras as MongoSensores_Incubadoras;
use App\Http\Controllers\SQL\Sensors_Incubadoras as SQLSensores_Incubadoras;

class SensoresHibrido extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoSensores_Incubadoras;
        $mongoController->store($request);

        $sqlController = new SQLSensores_Incubadoras;
        $sqlController->store($request);

        return response()->json(['message' => 'Sensores_Incubadoras created in both databases'], 201);
    }
}
