<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mongo\Incubadora;

class MongoIncubadora extends Controller
{
    public function store(Request $request)
    {
        $incubadora = new Incubadora;
        $incubadora->fill($request->all());
        //$incubadora->save();
        return response()->json(['message' => 'Incubadora created in MongoDB'], 201);
    }
}
