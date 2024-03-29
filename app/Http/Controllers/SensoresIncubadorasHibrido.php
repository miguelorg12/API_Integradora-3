<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Mongo\MongoSensores_Incubadoras as MongoSensores_Incubadoras;
use App\Http\Controllers\SQL\SensoresIncubadorass as SQLSensores_Incubadoras;

class SensoresIncubadorasHibrido extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sensor' => 'required|integer|exists:sensores,id',
            'id_incubadora' => 'required|integer|exists:incubadoras,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $mongoController = new MongoSensores_Incubadoras;
        $mongoController->store($request);

        $sqlController = new SQLSensores_Incubadoras;
        $sqlController->store($request);

        return response()->json(['message' => 'Sensores_Incubadoras created in both databases'], 201);
    }
}
