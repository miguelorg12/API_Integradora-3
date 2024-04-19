<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mongo\Sensores;

class MongoSensores extends Controller
{
    public function store(Request $request)
    {
        $sensores = new Sensores;
        $sensores->fill($request->all());
        $sensores->save();
        return response()->json(['message' => 'Sensores created in MongoDB'], 201);
    }
}
