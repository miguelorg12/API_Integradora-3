<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\Incubadora as MongoIncubadora;
use App\Http\Controllers\SQL\Hospitales\Incubadora as SQLIncubadora;

class IncubadoraCoordinator extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoIncubadora;
        $mongoController->store($request);

        $sqlController = new SQLIncubadora;
        $sqlController->store($request);

        return response()->json(['message' => 'Incubadora created in both databases'], 201);
    }
}