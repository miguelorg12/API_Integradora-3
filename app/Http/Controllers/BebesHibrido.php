<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoBebes;
use App\Http\Controllers\SQL\Bebess;

class BebesHibrido extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoBebes;
        $mongoController->store($request);

        $sqlController = new Bebess;
        $sqlController->store($request);

        return response()->json(['message' => 'Bebes created in both databases'], 201);
    }
}
