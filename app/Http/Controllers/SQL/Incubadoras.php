<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incubadora;
use App\Models\Sensores_Incubadoras;
use App\Models\Sensores;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Incubadoras extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function indexwithBabys()
    {
        $user = auth('api_jwt')->user();
        if ($user->id_rol == 1) {
            $incubadoras = DB::table('incubadoras')
                ->join('hospitals', 'hospitals.id', '=', 'incubadoras.id_hospital')
                ->join('bebes', 'bebes.id_incubadora', '=', 'incubadoras.id')
                ->join('estado_incubadoras', 'incubadoras.id_estado', '=', 'estado_incubadoras.id')
                ->select(
                    'incubadoras.*',
                    'hospitals.id',
                    'hospitals.nombre as hospital',
                    'bebes.id as id_bebe',
                    'bebes.nombre as nombre',
                    'bebes.apellido as apellido',
                    'bebes.fecha_nacimiento as fecha_nacimiento',
                    'bebes.sexo as sexo',
                    'bebes.id_estado',
                    'bebes.peso as peso',
                    'estado_incubadoras.estado as estado'
                )
                ->get();
        } else {
            $incubadoras = DB::table('incubadoras')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->join('bebes', 'bebes.id_incubadora', '=', 'incubadoras.id')
                ->join('estado_incubadoras', 'incubadoras.id_estado', '=', 'estado_incubadoras.id')
                ->select(
                    'incubadoras.*',
                    'hospitals.id',
                    'hospitals.nombre as hospital',
                    'bebes.id as id_bebe',
                    'bebes.nombre as nombre',
                    'bebes.apellido as apellido',
                    'bebes.fecha_nacimiento as fecha_nacimiento',
                    'bebes.sexo as sexo',
                    'bebes.id_estado',
                    'bebes.peso as peso',
                    'estado_incubadoras.estado as estado'
                )
                ->where('hospitals.id', $user->id_hospital)
                ->where('incubadoras.is_active', true)
                ->get();
        }

        return response()->json(['Incubadoras' => $incubadoras], 200);
    }

    public function indexBebesIncubadoras(){
        $user = auth('api_jwt')->user();
        $incubadoras = DB::table('incubadoras')
            ->where('incubadoras.is_active', true)
            ->where('incubadoras.is_occupied', true)
            ->get();

        if ($user->id_rol == 1) {
            foreach ($incubadoras as $incubadora) {
                $incubadora->id_bebe = DB::table('bebes')
                    ->where('id_incubadora', $incubadora->id)
                    ->where('id_estado', 1)
                    ->pluck('id')
                    ->first();
            }
        } else {
            foreach ($incubadoras as $incubadora) {
                $incubadora->id_bebe = DB::table('bebes')
                    ->where('id_incubadora', $incubadora->id)
                    ->where('id_estado', 1)
                    ->where('id_hospital', $user->id_hospital)
                    ->pluck('id')
                    ->first();
            }
        }

        return response()->json(['Incubadoras' => $incubadoras], 200);
    }


    public function index()
    {
        $user = auth('api_jwt')->user();
        if ($user->id_rol == 1) {
            $incubadoras = DB::table('incubadoras')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->join('estado_incubadoras', 'incubadoras.id_estado', '=', 'estado_incubadoras.id')
                ->select(
                    'incubadoras.*',
                    'hospitals.nombre as hospital',
                    'estado_incubadoras.estado as estado'
                )
                ->get();
        } else {
            $incubadoras = DB::table('incubadoras')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->join('estado_incubadoras', 'incubadoras.id_estado', '=', 'estado_incubadoras.id')
                ->select(
                    'incubadoras.*',
                    'hospitals.id',
                    'hospitals.nombre as hospital',
                    'estado_incubadoras.estado as estado'
                )
                ->where('hospitals.id', $user->id_hospital)
                ->where('incubadoras.is_active', true)
                ->get();
        }

        return response()->json(['Incubadoras' => $incubadoras], 200);
    }


    public function Incubadoras()
    {
        $incubadoras = DB::table('incubadoras')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->join('estado_incubadoras', 'incubadoras.id_estado', '=', 'estado_incubadoras.id')
            ->select('incubadoras.*',  'hospitals.nombre as hospital', 'estado_incubadoras.estado as estado')
            ->get();

        return response()->json(['Incubadoras' => $incubadoras], 200);
    }

    public function incubadorasDisponibles()
    {
        $user = auth('api_jwt')->user();
        if ($user->id_rol == 1) {
            $incubadoras = DB::table('incubadoras')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->join('estado_incubadoras', 'incubadoras.id_estado', '=', 'estado_incubadoras.id')
                ->select('incubadoras.*',  'hospitals.nombre as hospital', 'estado_incubadoras.estado as estado')
                ->where('incubadoras.is_active', true)
                ->where('incubadoras.is_occupied', false)
                ->get();
        } else {
            $incubadoras =  DB::table('incubadoras')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->select('incubadoras.*',  'hospitals.nombre as hospital')
                ->where('incubadoras.is_active', true)
                ->where('incubadoras.is_occupied', false)
                ->where('hospitals.id', $user->id_hospital)
                ->get();
        }
        return response()->json(['Incubadoras' => $incubadoras], 200);
    }

    public function show($id)
    {
        $user = auth('api_jwt')->user();
        if ($user->id_rol == 1) {
            $incubadora = Incubadora::where('id', $id)->first();
            $sensores_incubadora = Sensores_Incubadoras::where('id_incubadora', $id)->get();
        } else {
            $incubadora = Incubadora::where('id', $id)->where('is_active', 1)->first();
            $sensores_incubadora = Sensores_Incubadoras::where('id_incubadora', $id)->get();
        }
        if (!$incubadora) {
            return response()->json(['msg' => 'Incubadora no encontrada'], 404);
        }
        return response()->json(['Incubadora' => $incubadora, 'Sensores' => $sensores_incubadora], 200);
    }

    public function store(Request $request)
    {
        $user = auth('api_jwt')->user();
        $validator = Validator::make($request->all(), [
            'id_estado' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        if ($user->id_rol == 1) {
            $incubadora = new Incubadora;
            $incubadora->id_hospital = $request->id_hospital;
            $incubadora->id_estado = $request->id_estado;
            $incubadora->save();
            $sensores = $request->input('id_sensores');
        }
        else{
            $incubadora = new Incubadora;
            $incubadora->id_hospital = $user->id_hospital;
            $incubadora->id_estado = $request->id_estado;
            $incubadora->save();
            $sensores = $request->input('id_sensores');
        }
        foreach ($sensores as $sensorId) {
            $sensor = Sensores::find($sensorId);
            if ($sensor) {
                $folio = rand(100, 999) . strtoupper(substr($sensor->nombre, 0, 1));
                $sensores_incubadora = new Sensores_Incubadoras;
                $sensores_incubadora->id_incubadora = $incubadora->id;
                $sensores_incubadora->id_sensor = $sensorId;
                $sensores_incubadora->folio = $folio;
                $sensores_incubadora->save();
            }
        }
        return response()->json(['msg' => 'Incubadora creada'], 201);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api_jwt')->user();
        if ($user->id_rol == 1) {
            $incubadora = Incubadora::where('id', $id)->first();
        } else {
            $incubadora = Incubadora::where('id', $id)->where('is_active', 1)->first();
        }
        if (!$incubadora) {
            return response()->json(['msg' => 'Incubadora no encontrada'], 404);
        }
        $validator = Validator::make($request->all(), [
            'id_hospital' => 'required|integer|exists:hospitals,id',
            'is_active' => 'required|boolean',
            'id_estado' => 'required',
            'id_sensores' => 'required|array|min:1',
            'id_sensores.*' => 'exists:sensores,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        if ($user -> id_rol == 1){
            $incubadora->id_hospital = $request->id_hospital;
        }
        else {
            $incubadora->id_hospital = $user->id_hospital;
        }
        $incubadora->is_active = $request->is_active;
        $incubadora->is_occupied = $request->is_occupied;
        $incubadora->id_estado = $request->id_estado;
        $incubadora->is_occupied = $request->get('is_occupied');
        $incubadora->save();
        $sensores_ids = $request->input('id_sensores');
        $syncData = [];
        foreach ($sensores_ids as $sensorId) {
            $folio = rand(100, 999) . 'S';
            $syncData[$sensorId] = ['folio' => $folio];
        }
        $incubadora->sensores()->sync($syncData);
        return response()->json(['msg' => 'Incubadora actualizada'], 200);
    }

    public function destroy($id)
    {
        $incubadora = Incubadora::where('id', $id)->where('is_active', 1)->first();
        if (!$incubadora) {
            return response()->json(['msg' => 'Incubadora no encontrada'], 404);
        }
        $incubadora->is_active = false;
        $incubadora->is_occupied = false;
        $incubadora->save();
        return response()->json(['msg' => 'Incubadora eliminada'], 200);
    }
}
