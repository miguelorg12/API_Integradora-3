<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Mongo\MongoHospital as MongoHospital;
use App\Http\Controllers\SQL\Hospitals as SQLHospital;

class HospitalHibrido extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'direccion' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Error in the data', 'errors' => $validator->errors()]);
        }
        $mongoController = new MongoHospital;
        $mongoController->store($request);

        $sqlController = new SQLHospital;
        $sqlController->store($request);

        return response()->json(['message' => 'Hospital created in both databases'], 201);
    }
}
