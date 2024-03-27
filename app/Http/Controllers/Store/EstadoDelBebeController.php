<?php
namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\EstadoDelBebe as MongoEstadoDelBebe;
use App\Http\Controllers\SQL\EstadoDelBebe as SQLEstadoDelBebe;

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
