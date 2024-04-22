<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Value;

class Historial extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api_jwt', ['except' => ['store', 'historial']]);
    }

    public function historial(Request $request)
    {
        $name = "";
        switch ($request->name) {
            case 'Sensor de Temperatura':
                $name = 'te';
                break;
            case 'Sensor de Pulso':
                $name = 'pu';
                break;
            case 'Sensor de Calidad de Aire':
                $name = 'ca';
                break;
            case 'Sensor de Sonido':
                $name = 'so';
                break;
            case 'Buzzer':
                $name = 'bu';
                break;
            default:
                return response()->json(['msg' => 'Sensor no encontrado'], 404);
        }

        $historial = Value::orderBy('_id', 'desc')->where('name', $name)->get();
        return response()->json(['msg' => 'Historial', 'data' => $historial], 200);
    }
}
