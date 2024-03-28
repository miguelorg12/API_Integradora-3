<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensores;

class Sensors extends Controller
{
    public function index()
    {
        $sensors = Sensores::all();
        return response()->json(['msg' => 'Sensores', 'data' => $sensors]);
    }

    public function show($id)
    {
        $sensor = Sensores::where('id', $id)->first();
        if (!$sensor) {
            return response()->json(['msg' => 'Sensor no encontrado']);
        }
        return response()->json(['msg' => 'Sensor', 'data' => $sensor]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'unidad' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
        ]);
        $sensor = new Sensores;
        $sensor->nombre = $request->nombre;
        $sensor->unidad = $request->unidad;
        $sensor->save();
        return response()->json(['msg' => 'Sensor creado']);
    }

    public function update(Request $request, $id)
    {
        $sensor = Sensores::where('id', $id)->first();
        if (!$sensor) {
            return response()->json(['msg' => 'Sensor no encontrado']);
        }
        $request->validate([
            'nombre' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'unidad' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
        ]);
        $sensor->nombre = $request->nombre;
        $sensor->unidad = $request->unidad;
        $sensor->save();
        return response()->json(['msg' => 'Sensor actualizado']);
    }
}
