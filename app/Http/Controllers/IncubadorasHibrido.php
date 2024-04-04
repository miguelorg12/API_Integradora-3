<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoIncubadora as MongoIncubadora;
use App\Http\Controllers\SQL\Incubadoras as SQLIncubadora;
use Illuminate\Support\Facades\Validator;

class IncubadorasHibrido extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_hospital' => 'required|integer|exists:hospitals,id',
            'id_estado' => 'required|integer|exists:estado_incubadoras,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $mongoController = new MongoIncubadora;
        $mongoController->store($request);

        $sqlController = new SQLIncubadora;
        $sqlController->store($request);

        return response()->json(['message' => 'Incubadora created in both databases'], 201);
    }
}
