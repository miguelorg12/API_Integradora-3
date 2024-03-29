<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoIncubadora as MongoIncubadora;
use App\Http\Controllers\SQL\Incubadoras as SQLIncubadora;

class IncubadorasHibrido extends Controller
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
