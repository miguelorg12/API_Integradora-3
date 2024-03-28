<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoHospital as MongoHospital;
use App\Http\Controllers\SQL\Hospitals as SQLHospital;

class HospitalCoordinator extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoHospital;
        $mongoController->store($request);

        $sqlController = new SQLHospital;
        $sqlController->store($request);

        return response()->json(['message' => 'Hospital created in both databases'], 201);
    }
}
