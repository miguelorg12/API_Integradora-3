<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoSensores as MongoSensores;
use App\Http\Controllers\SQL\Sensors as SQLSensores;

class SensoresIncubadorasHibrido extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoSensores;
        $mongoController->store($request);

        $sqlController = new SQLSensores;
        $sqlController->store($request);

        return response()->json(['message' => 'Sensores created in both databases'], 201);
    }
}
