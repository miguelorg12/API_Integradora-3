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
        $user = auth('api_jwt')->user();
        $validator = Validator::make($request->all(), [
            'id_estado' => 'required|integer|exists:estado_incubadoras,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $request->merge(['is_active' => true]);
        $request->merge(['is_occupied' => false]);
        $request->merge(['optimo' => true]);
        $request->merge(['folio' => rand(100, 999)]);
        if ($user->id_ro == 1) {
            $mongoController = new MongoIncubadora;
            $mongoController->store($request);
        } else {
            $mongoController = new MongoIncubadora;
            $request->merge(['id_hospital' => $user->id_hospital]);
            $mongoController->store($request);
        }


        $sqlController = new SQLIncubadora;
        $sqlController->store($request);

        return response()->json(['message' => 'Incubadora created in both databases'], 201);
    }
}
