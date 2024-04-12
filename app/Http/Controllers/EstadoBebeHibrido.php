<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoEstadoDelBebe as MongoEstadoDelBebe;
use App\Http\Controllers\SQL\EstadoBebe as SQLEstadoDelBebe;
use Illuminate\Support\Facades\Validator;

class EstadoBebeHibrido extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string|min:3|max:50|regex:/^[a-zA-Z ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $mongoController = new MongoEstadoDelBebe;
        $mongoController->store($request);

        $sqlController = new SQLEstadoDelBebe;
        $sqlController->store($request);

        return response()->json(['message' => 'EstadoDelBebe created in both databases'], 201);
    }
}
