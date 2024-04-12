<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoBebes;
use App\Http\Controllers\SQL\Bebess;
use Illuminate\Support\Facades\Validator;

class BebesHibrido extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'sexo' => 'required|in:M,F|min:1|max:1|regex:/^[a-zA-Z ]*$/',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer',
            'peso' => 'required|numeric',
            'id_estado' => 'required|integer',
            'id_incubadora' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }

        $mongoController = new MongoBebes;
        $mongoController->store($request);

        $sqlController = new Bebess;
        $sqlController->store($request);

        return response()->json(['message' => 'Bebes created in both databases'], 201);
    }
}
