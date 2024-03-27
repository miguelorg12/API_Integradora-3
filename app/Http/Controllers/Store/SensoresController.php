<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\Sensores as MongoSensores;
use App\Http\Controllers\SQL\Sensores as SQLSensores;

class SensoresCoordinator extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoSensores;
        $mongoController->store($request);

        $sqlController = new SQLSensores;
        $sqlController->store($request);

        return response()->json(['message' => 'Sensores created in both databases'], 201);
    }
}