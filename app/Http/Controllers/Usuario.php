<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Usuario extends Controller
{
    public function index()
    {
        $users = User::where('is_active', 1)->get();
        return response()->json(['msg' => 'Usuarios', 'data' => $users]);
    }

    public function show(Request $request)
    {
        $user = User::where($request->id)->where('is_active', 1)->first();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado']);
        }
        return response()->json(['msg' => 'Usuario', 'data' => $user]);
    }

    public function update(Request $request)
    {
        $user = User::where($request->id)->where('is_active', 1)->first();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado']);
        }
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
