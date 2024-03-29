<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Mongo\MongoSensores as MongoSensores;
use App\Http\Controllers\SQL\Sensoress as SQLSensores;

class SensoresHibrido extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'unidad' => 'required|string|min:1|max:100',
            'folio' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/|unique:sensores,folio',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }

        $mongoController = new MongoSensores;
        $mongoController->store($request);

        $sqlController = new SQLSensores;
        $sqlController->store($request);

        return response()->json(['message' => 'Sensores created in both databases'], 201);
    }
}
