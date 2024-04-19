<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mongo\EstadoDelBebe;

class MongoEstadoDelBebe extends Controller
{
    public function store(Request $request)
    {
        $estadoDelBebe = new EstadoDelBebe;
        $estadoDelBebe->fill($request->all());
        $estadoDelBebe->save();
        return response()->json(['message' => 'EstadoDelBebe created in MongoDB'], 201);
    }
}
