<?php

namespace App\Http\Controllers\SQL;

use App\Events\testWebsocket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensores;
use Illuminate\Support\Facades\Validator;

class Sensoress extends Controller
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
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'unidad' => 'required|string|min:1|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
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
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'unidad' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $sensor->nombre = $request->nombre;
        $sensor->unidad = $request->unidad;
        $sensor->save();
        return response()->json(['msg' => 'Sensor actualizado']);
    }

    public function destroy($id)
    {
        $sensor = Sensores::where('id', $id)->first();
        if (!$sensor) {
            return response()->json(['msg' => 'Sensor no encontrado']);
        }
        $sensor->is_active = false;
        $sensor->save();
        return response()->json(['msg' => 'Sensor eliminado']);
    }

    public function message(Request $request)
    {
        event(new testWebsocket($request->all()));
        return response()->json(['msg' => $request->all()]);
    }
}
