<?php
namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Mongo\MongoBebes;
use App\Http\Controllers\SQL\BebesController;

class BebesCoordinator extends Controller
{
    public function store(Request $request)
    {
        $mongoController = new MongoBebes;
        $mongoController->store($request);

        $sqlController = new BebesController;
        $sqlController->store($request);

        return response()->json(['message' => 'Bebes created in both databases'], 201);
    }
}