<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensores_Incubadoras;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SensoresIncubadorass extends Controller
{
    public function index()
    {
        $sensores_incubadoras = DB::table('sensores__incubadoras')
            ->join('sensores', 'sensores__incubadoras.id_sensor', '=', 'sensores.id')
            ->join('incubadoras', 'sensores__incubadoras.id_incubadora', '=', 'incubadoras.id')
            ->select('sensores__incubadoras.*', 'sensores.nombre as sensor', 'incubadoras.id as incubadora')
            ->get();
        return response()->json(['msg' => 'Sensores_Incubadoras', 'data' => $sensores_incubadoras], 200);
    }

    public function show($id)
    {
        $sensores_incubadoras = DB::table('sensores__incubadoras')
            ->join('sensores', 'sensores__incubadoras.id_sensor', '=', 'sensores.id')
            ->join('incubadoras', 'sensores__incubadoras.id_incubadora', '=', 'incubadoras.id')
            ->select('sensores__incubadoras.*', 'sensores.nombre as sensor', 'incubadoras.id as incubadora')
            ->where('sensores__incubadoras.id', $id)
            ->first();
        if (!$sensores_incubadoras) {
            return response()->json(['msg' => 'Sensor_Incubadora no encontrado']);
        }
        return response()->json(['msg' => 'Sensor_Incubadora', 'data' => $sensores_incubadoras], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sensor' => 'required|integer|exists:sensores,id',
            'id_incubadora' => 'required|integer|exists:incubadoras,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $sensores_incubadoras = new Sensores_Incubadoras;
        $sensores_incubadoras->id_sensor = $request->id_sensor;
        $sensores_incubadoras->id_incubadora = $request->id_incubadora;
        $sensores_incubadoras->folio = $request->folio;
        $sensores_incubadoras->save();
        return response()->json(['msg' => 'Sensor_Incubadora creado'], 201);
    }

    public function update(Request $request, $id)
    {
        $sensores_incubadoras = Sensores_Incubadoras::where('id', $id)->first();
        if (!$sensores_incubadoras) {
            return response()->json(['msg' => 'Sensor_Incubadora no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [
            'id_sensor' => 'required|integer|exists:sensores,id',
            'id_incubadora' => 'required|integer|exists:incubadoras,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $sensores_incubadoras->id_sensor = $request->id_sensor;
        $sensores_incubadoras->id_incubadora = $request->id_incubadora;
        $sensores_incubadoras->save();
        return response()->json(['msg' => 'Sensor_Incubadora actualizado'], 200);
    }

    public function destroy($id)
    {
        $sensores_incubadoras = Sensores_Incubadoras::where('id', $id)->first();
        if (!$sensores_incubadoras) {
            return response()->json(['msg' => 'Sensor_Incubadora no encontrado'], 404);
        }
        $sensores_incubadoras->is_active = false;
        $sensores_incubadoras->save();
        return response()->json(['msg' => 'Sensor_Incubadora eliminado'], 200);
    }
}
