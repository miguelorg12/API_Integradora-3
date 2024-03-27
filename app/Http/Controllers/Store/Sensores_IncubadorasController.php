<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\Sensores_Incubadoras as MongoSensores_Incubadoras;
use App\Http\Controllers\SQL\Sensores_Incubadoras as SQLSensores_Incubadoras;

class Sensores_IncubadorasCoordinator extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoSensores_Incubadoras;
        $mongoController->store($request);

        $sqlController = new SQLSensores_Incubadoras;
        $sqlController->store($request);

        return response()->json(['message' => 'Sensores_Incubadoras created in both databases'], 201);
    }
}