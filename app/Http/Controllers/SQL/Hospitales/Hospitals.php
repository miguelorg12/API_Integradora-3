<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;

class Hospitals extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->id_rol == 1) {
            $hospitals = Hospital::all();
            return response()->json(['msg' => 'Hospitales', 'data' => $hospitals]);
        }
        $hospitals = Hospital::where('id', $user->id_hospital)->where('is_active', true)->get();
        return response()->json(['Hospitales' => $hospitals]);
    }

    public function show(Request $request)
    {
        $hospital = Hospital::where($request->id)->first();
        if (!$hospital) {
            return response()->json(['msg' => 'Hospital no encontrado']);
        }
        return response()->json(['msg' => 'Hospital', 'data' => $hospital]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'direccion' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
        ]);

        $hospital = new Hospital();
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
        $request->validate([
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'direccion' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
        ]);
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
