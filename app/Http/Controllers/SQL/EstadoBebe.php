<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EstadoDelBebe;
use Illuminate\Support\Facades\Validator;

class EstadoBebe extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth('api_jwt')->user();
        if ($user->id_rol == 1) {
            $estadoDelBebe = EstadoDelBebe::all();
            return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe], 200);
        } else {
            $estadoDelBebe = EstadoDelBebe::where('is_active', true)->get();
        }
        return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe], 200);
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->first();
            if (!$estadoDelBebe) {
                return response()->json(['msg' => 'EstadoDelBebe no encontrado'], 404);
            }
            return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe], 200);
        } else {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->where('is_active', true)->first();
        }
        if (!$estadoDelBebe) {
            return response()->json(['msg' => 'EstadoDelBebe no encontrado'], 404);
        }
        return response()->json(['msg' => 'EstadoDelBebe', 'data' => $estadoDelBebe], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string|min:3|max:50|regex:/^[a-zA-Z ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $estadoDelBebe = new EstadoDelBebe;
        $estadoDelBebe->estado = $request->estado;
        $estadoDelBebe->save();
        return response()->json(['msg' => 'EstadoDelBebe creado'], 201);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->first();
            if (!$estadoDelBebe) {
                return response()->json(['msg' => 'EstadoDelBebe no encontrado'], 404);
            }
            $validator = Validator::make($request->all(), [
                'estado' => 'required|string|min:3|max:50|regex:/^[a-zA-Z ]*$/',
            ]);
            if ($validator->fails()) {
                return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
            }
            $estadoDelBebe->estado = $request->estado;
            $estadoDelBebe->save();
            return response()->json(['msg' => 'EstadoDelBebe actualizado'], 200);
        } else {
            $estadoDelBebe = EstadoDelBebe::where('id', $id)->where('is_active', true)->first();

            if (!$estadoDelBebe) {
                return response()->json(['msg' => 'EstadoDelBebe no encontrado'], 404);
            }
            $validator = Validator::make($request->all(), [
                'estado' => 'required|string|min:3|max:50|regex:/^[a-zA-Z ]*$/',
            ]);
            if ($validator->fails()) {
                return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
            }
            $estadoDelBebe->estado = $request->estado;
            $estadoDelBebe->save();
            return response()->json(['msg' => 'EstadoDelBebe actualizado'], 200);
        }
    }

    public function destroy($id)
    {
        $estadoDelBebe = EstadoDelBebe::where('id', $id)->where('is_active', true)->first();
        if (!$estadoDelBebe) {
            return response()->json(['msg' => 'EstadoDelBebe no encontrado'], 404);
        }
        $estadoDelBebe->is_active = false;
        $estadoDelBebe->save();
        return response()->json(['msg' => 'EstadoDelBebe eliminado'], 200);
    }
}
