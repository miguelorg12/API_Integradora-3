<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoBebes;
use App\Http\Controllers\SQL\Bebe;

class BebesCoordinator extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoBebes;
        $mongoController->store($request);

        $sqlController = new Bebe;
        $sqlController->store($request);

        return response()->json(['message' => 'Bebes created in both databases'], 201);
    }
}
