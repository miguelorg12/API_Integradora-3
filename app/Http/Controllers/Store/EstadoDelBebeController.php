<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoEstadoDelBebe as MongoEstadoDelBebe;
use App\Http\Controllers\EstadoDelBebes as SQLEstadoDelBebe;

class EstadoDelBebeCoordinator extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoEstadoDelBebe;
        $mongoController->store($request);

        $sqlController = new SQLEstadoDelBebe;
        $sqlController->store($request);

        return response()->json(['message' => 'EstadoDelBebe created in both databases'], 201);
    }
}
