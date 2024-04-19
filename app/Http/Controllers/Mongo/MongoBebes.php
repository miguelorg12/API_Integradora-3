<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mongo\Bebes;

class MongoBebes extends Controller
{
    public function store(Request $request)
    {
        $bebe = new Bebes;
        $bebe->fill($request->all());
        $bebe->save();
        return response()->json(['message' => 'Bebe created in MongoDB'], 201);
    }
}
