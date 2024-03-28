<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensores_Incubadoras;
use Illuminate\Support\Facades\DB;

class Sensors_Incubadoras extends Controller
{
    public function index()
    {
        $sensores_incubadoras = DB::table('sensores_incubadoras')
            ->join('sensores', 'sensores_incubadoras.id_sensor', '=', 'sensores.id')
            ->join('incubadoras', 'sensores_incubadoras.id_incubadora', '=', 'incubadoras.id')
            ->select('sensores_incubadoras.*', 'sensores.nombre as sensor', 'incubadoras.nombre as incubadora')
            ->get();
        return response()->json(['msg' => 'Sensores_Incubadoras', 'data' => $sensores_incubadoras]);
    }

    public function show($id)
    {
        $sensores_incubadoras = DB::table('sensores_incubadoras')
            ->join('sensores', 'sensores_incubadoras.id_sensor', '=', 'sensores.id')
            ->join('incubadoras', 'sensores_incubadoras.id_incubadora', '=', 'incubadoras.id')
            ->select('sensores_incubadoras.*', 'sensores.nombre as sensor', 'incubadoras.nombre as incubadora')
            ->where('sensores_incubadoras.id', $id)
            ->first();
        if (!$sensores_incubadoras) {
            return response()->json(['msg' => 'Sensor_Incubadora no encontrado']);
        }
        return response()->json(['msg' => 'Sensor_Incubadora', 'data' => $sensores_incubadoras]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sensor' => 'required|integer|exists:sensores,id',
            'id_incubadora' => 'required|integer|exists:incubadoras,id',
        ]);
        $sensores_incubadoras = new Sensores_Incubadoras;
        $sensores_incubadoras->id_sensor = $request->id_sensor;
        $sensores_incubadoras->id_incubadora = $request->id_incubadora;
        $sensores_incubadoras->save();
        return response()->json(['msg' => 'Sensor_Incubadora creado']);
    }

    public function update(Request $request, $id)
    {
        $sensores_incubadoras = Sensores_Incubadoras::where('id', $id)->first();
        if (!$sensores_incubadoras) {
            return response()->json(['msg' => 'Sensor_Incubadora no encontrado']);
        }
        $request->validate([
            'id_sensor' => 'required|integer|exists:sensores,id',
            'id_incubadora' => 'required|integer|exists:incubadoras,id',
        ]);
        $sensores_incubadoras->id_sensor = $request->id_sensor;
        $sensores_incubadoras->id_incubadora = $request->id_incubadora;
        $sensores_incubadoras->save();
        return response()->json(['msg' => 'Sensor_Incubadora actualizado']);
    }
}
