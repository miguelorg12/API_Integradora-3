<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mongo\Hospital;

class MongoHospital extends Controller
{
    public function store(Request $request)
    {
        $hospital = new Hospital;
        $hospital->fill($request->all());
        //$hospital->save();
        return response()->json(['message' => 'Hospital created in MongoDB'], 201);
    }
}
