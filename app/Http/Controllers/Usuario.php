<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
            $user = User::where('id', $request->id)->first();
            if (!$user) {
                return response()->json(['msg' => 'Usuario no encontrado']);
            }
            return response()->json(['msg' => 'Usuario', 'data' => $user]);
        } else if ($usuario->id_rol == 2) {
            $user = User::where('id', $request->id)->where('id_hospital', $usuario->id_hospital)->where('is_active', 1)->first();
            if (!$user) {
                return response()->json(['msg' => 'Usuario no encontrado']);
            }
            return response()->json(['msg' => 'Usuario', 'data' => $user]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->last_name = $request->last_name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->id_rol = 5;
        $usuario->id_hospital = 1;
        $usuario->is_active = true;
        $usuario->activated_at = now();
        $usuario->save();
        return response()->json(['msg' => 'Usuario creado']);
    }

    public function update(Request $request)
    {
        $usuario = auth()->user();

        if ($usuario->id_rol == 1) {
            $user = User::where('id', $request->id)->first();
        } elseif ($usuario->id_rol == 2) {
            $user = User::where('id', $request->id)->where('id_hospital', $usuario->id_hospital)->where('is_active', 1)->first();
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100|min:3|regex:/^[a-zA-Z ]*$/',
                'last_name' => 'required|string|max:100|min:3|regex:/^[a-zA-Z ]*$/',
                'id_rol' => 'required|integer|exists:rols,id',
                'id_hospital' => 'required|integer|exists:hospitals,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
            }
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->id_rol = $request->id_rol;
            $user->id_hospital = $request->id_hospital;
            $user->save();
            return response()->json(['msg' => 'Usuario actualizado']);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
            ]);
            if ($validator->fails()) {
                return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
            }
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->save();
            return response()->json(['msg' => 'Usuario actualizado']);
        }
    }

    public function delete(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado']);
        }
        $user->is_active = 0;
        $user->save();
        return response()->json(['msg' => 'Usuario eliminado']);
    }

    public function restPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado']);
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
            'confirm_password' => 'required|string|min:8|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $user->password = Hash::make($request->password);
        $user->save();
    }
}
