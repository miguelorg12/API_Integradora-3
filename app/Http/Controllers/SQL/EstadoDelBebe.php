<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mongo\EstadoDelBebe;
use Illuminate\Support\Facades\Validator;

class EstadoDelBebes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $estadoDelBebe = EstadoDelBebe::all();
            return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe]);
        } else {
            $estadoDelBebe = EstadoDelBebe::where('is_active', true)->get();
        }
        return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->first();
            if (!$estadoDelBebe) {
                return response()->json(['msg' => 'EstadoDelBebe no encontrado']);
            }
            return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe]);
        } else {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->where('is_active', true)->first();
        }
        if (!$estadoDelBebe) {
            return response()->json(['msg' => 'EstadoDelBebe no encontrado']);
        }
        return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string|min:3|max:50|regex:/^[a-zA-Z ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $estadoDelBebe = new EstadoDelBebe;
        $estadoDelBebe->estado = $request->estado;
        $estadoDelBebe->save();
        return response()->json(['msg' => 'EstadoDelBebe creado']);
        return response()->json(['msg' => 'No tienes permisos']);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->first();
            if (!$estadoDelBebe) {
                return response()->json(['msg' => 'EstadoDelBebe no encontrado']);
            }
            $validator = Validator::make($request->all(), [
                'estado' => 'required|string|min:3|max:50|regex:/^[a-zA-Z ]*$/',
            ]);
            if ($validator->fails()) {
                return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
            }
            $estadoDelBebe->estado = $request->estado;
            $estadoDelBebe->save();
            return response()->json(['msg' => 'EstadoDelBebe actualizado']);
        } else {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->where('is_active', true)->first();
        }
        if (!$estadoDelBebe) {
            return response()->json(['msg' => 'EstadoDelBebe no encontrado']);
        }
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string|min:3|max:50|regex:/^[a-zA-Z ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $estadoDelBebe->estado = $request->estado;
        $estadoDelBebe->save();
        return response()->json(['msg' => 'EstadoDelBebe actualizado']);
    }

    public function destroy($id)
    {
        $estadoDelBebe = EstadoDelBebe::where('id', $id)->where('is_active', true)->first();
        if (!$estadoDelBebe) {
            return response()->json(['msg' => 'EstadoDelBebe no encontrado']);
        }
        $estadoDelBebe->is_active = false;
        $estadoDelBebe->save();
        return response()->json(['msg' => 'EstadoDelBebe eliminado']);
    }
}
