<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use Illuminate\Support\Facades\Validator;

class Hospitals extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt', ['except' => ['hospitals']]);
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->id_rol == 1) {
            $hospitals = Hospital::all();
            return response()->json(['msg' => 'Hospitales', 'data' => $hospitals]);
        } else {
            $hospitals = Hospital::where('id', $user->id_hospital)->where('is_active', true)->get();
        }
        return response()->json(['Hospitales' => $hospitals]);
    }

    public function hospitals()
    {
        $hospitals = Hospital::where('is_active', true)
        ->where('nombre', '!=', 'Hospital General')
        ->get();
        return response()->json(['Hospitales' => $hospitals]);
    }

    public function show(Request $request)
    {
        $hospital = Hospital::where('id', $request->id)->first();
        if (!$hospital) {
            return response()->json(['msg' => 'Hospital no encontrado']);
        }
        return response()->json(['msg' => 'Hospital', 'data' => $hospital]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'direccion' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $hospital = new Hospital;
        $hospital->nombre = $request->nombre;
        $hospital->direccion = $request->direccion;
        $hospital->telefono = $request->telefono;
        $hospital->save();
        return response()->json(['msg' => 'Hospital creado']);
    }

    public function update(Request $request, $id)
    {
        $hospital = Hospital::where('id', $id)->where('is_active', true)->first();
        if (!$hospital) {
            return response()->json(['msg' => 'Hospital no encontrado']);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'direccion' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $hospital->nombre = $request->nombre;
        $hospital->direccion = $request->direccion;
        $hospital->telefono = $request->telefono;
        $hospital->save();
        return response()->json(['msg' => 'Hospital actualizado']);
    }

    public function destroy($id)
    {
        $hospital = Hospital::where('id', $id)->where('is_active', true)->first();
        if (!$hospital) {
            return response()->json(['msg' => 'Hospital no encontrado']);
        }
        $hospital->is_active = false;
        $hospital->save();
        return response()->json(['msg' => 'Hospital eliminado']);
    }
}
