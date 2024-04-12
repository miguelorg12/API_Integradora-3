<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstadoIncubadora;
use Illuminate\Support\Facades\Validator;

class EstadoIncubadoraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt', ['except' => []]);
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $estadoIncubadora = EstadoIncubadora::all();
            return response()->json(['estado' => $estadoIncubadora], 200);
        } else {
            $estadoIncubadora = EstadoIncubadora::where('is_active', true)->get();
            return response()->json(['estado' => $estadoIncubadora], 200);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $estadoIncubadora = EstadoIncubadora::where('id', $id)->first();
            if (!$estadoIncubadora) {
                return response()->json(['error' => 'EstadoIncubadora no encontrado'], 404);
            }
            return response()->json(['estado' => $estadoIncubadora], 200);
        } else {
            $estadoIncubadora = EstadoIncubadora::where('id', $id)->where('is_active', true)->first();
            if (!$estadoIncubadora) {
                return response()->json(['error' => 'EstadoIncubadora no encontrado'], 404);
            }
            return response()->json(['estado' => $estadoIncubadora], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string|max:100|min:3|regex:/^[a-zA-Z ]*$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $estadoIncubadora = new EstadoIncubadora();
        $estadoIncubadora->estado = $request->estado;
        $estadoIncubadora->save();
        return response()->json(['estadoIncubadora creado'], 201);
    }

    public function update(Request $request, $id)
    {
        $estadoIncubadora = EstadoIncubadora::find($id);
        if (!$estadoIncubadora) {
            return response()->json(['error' => 'EstadoIncubadora no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string|max:100|min:3|regex:/^[a-zA-Z ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $estadoIncubadora->estado = $request->estado;
        $estadoIncubadora->save();
        return response()->json(['estadoIncubadora actualizado'], 200);
    }

    public function destroy($id)
    {
        $estadoIncubadora = EstadoIncubadora::find($id);
        if (!$estadoIncubadora) {
            return response()->json(['error' => 'EstadoIncubadora no encontrado'], 404);
        }
        $estadoIncubadora->is_active = false;
        $estadoIncubadora->save();
        return response()->json(['estadoIncubadora eliminado']);
    }
}
