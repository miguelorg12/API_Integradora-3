<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mongo\Sensores_Incubadoras;

class MongoSensores_Incubadoras extends Controller
{
    public function store(Request $request)
    {
        $sensores_incubadoras = new Sensores_Incubadoras;
        $sensores_incubadoras->fill($request->all());
        $sensores_incubadoras->save();
        return response()->json(['message' => 'Sensores_Incubadoras created in MongoDB'], 201);
    }
}
