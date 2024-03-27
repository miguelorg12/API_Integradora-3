<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Usuario extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $usuario = auth()->user();

        if ($usuario->id_rol == 1) {
            $users = User::all();
            return response()->json(['msg' => 'Usuarios', 'data' => $users]);
        } else if ($usuario->id_rol == 2) {
            $users = User::where('id_hospital', $usuario->id_hospital)->where('is_active', true)->get();
            return response()->json(['msg' => 'Usuarios', 'data' => $users]);
        }
    }

    public function show(Request $request)
    {
        $usuario = auth()->user();

        if ($usuario->id_rol == 1) {
            $user = User::where($request->id)->first();
            if (!$user) {
                return response()->json(['msg' => 'Usuario no encontrado']);
            }
            return response()->json(['msg' => 'Usuario', 'data' => $user]);
        } else if ($usuario->id_rol == 2) {
            $user = User::where($request->id)->where('id_hospital', $usuario->id_hospital)->where('is_active', 1)->first();
            if (!$user) {
                return response()->json(['msg' => 'Usuario no encontrado']);
            }
            return response()->json(['msg' => 'Usuario', 'data' => $user]);
        }
    }

    public function update(Request $request)
    {
        $usuario = auth()->user();

        if ($usuario->id_rol == 1) {
            $user = User::where($request->id)->first();
        } elseif ($usuario->id_rol == 2) {
            $user = User::where($request->id)->where('id_hospital', $usuario->id_hospital)->where('is_active', 1)->first();
            if (!$user) {
                return response()->json(['msg' => 'Usuario no encontrado']);
            }
        } else {
            $user = User::where($request->id)->where('is_active', 1)->first();
            if (!$user) {
                return response()->json(['msg' => 'Usuario no encontrado']);
            }
        }
        if ($usuario->id_rol == 1 | $usuario->id_rol == 2) {
            $request->validate([
                'name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'id_rol' => 'required|integer|exists:rols,id',
                'id_hospital' => 'required|integer|exists:hospitals,id'
            ]);
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->id_rol = $request->id_rol;
            $user->id_hospital = $request->id_hospital;
            $user->save();
            return response()->json(['msg' => 'Usuario actualizado']);
        } else {
            $request->validate([
                'name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
            ]);
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->save();
            return response()->json(['msg' => 'Usuario actualizado']);
        }
        $request->validate([
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'id_rol' => 'required|integer|exists:rols,id',
            'id_hospital' => 'required|integer|exists:hospitals,id'
        ]);
    }

    public function delete(Request $request)
    {
        $user = User::where($request->id)->where('is_active', 1)->first();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado']);
        }
        $user->is_active = 0;
        $user->save();
        return response()->json(['msg' => 'Usuario eliminado']);
    }
}
