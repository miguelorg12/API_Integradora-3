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
        $this->middleware('auth:api_jwt', ['except' => ['recoveryPassword', 'resetPassword']]);
    }

    public function index()
    {
        $usuario = auth()->user();

        if ($usuario->id_rol == 1) {
            $users = User::join('rols', 'users.id_rol', '=', 'rols.id')
            ->join('hospitals', 'users.id_hospital', '=', 'hospitals.id')
            ->orderBy('users.id', 'asc')
            ->select('users.id', 'users.name', 'users.last_name', 'users.email','users.is_active', 'rols.nombre as rol', 'hospitals.nombre as hospital')
            ->where('users.is_active', true)
            ->get();
            return response()->json(['msg' => 'Usuarios', 'data' => $users]);
        } else if ($usuario->id_rol == 2) {
            $users = User::where('id_hospital', $usuario->id_hospital)
            ->join('rols', 'users.id_rol', '=', 'rols.id')
            ->join('hospitals', 'users.id_hospital', '=', 'hospitals.id')
            ->select('users.id', 'users.name', 'users.last_name', 'users.email','users.is_active', 'rols.nombre as rol', 'hospitals.nombre as hospital')
            ->where('rols.id', '!=', 1)
            ->where('users.is_active', true)
            ->get();
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
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password',
            'id_rol' => 'required|integer'

        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->last_name = $request->last_name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->id_rol = $request->id_rol;
        $usuario->id_hospital = $user->id_hospital;
        $usuario->is_active = true;
        $usuario->activated_at = now();
        $usuario->save();
        return response()->json(['msg' => 'Usuario creado']);
    }

    public function update(Request $request, $id)
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
            $user = User::where('id', $id)->where('is_active', 1)->first();
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
                return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
            }
            
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            if ($request->id_hospital != null && $request->id_hospital != 0) {
                $user->id_rol = 5;
                $user->id_hospital = $request->id_hospital;
            }
            $user->save();
            return response()->json(['msg' => 'Usuario actualizado', 'rol' => $user->id_rol, 'name' => $user->name, 'last_name' => $user->last_name, 'id_hospital' => $user->id_hospital]);
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
}
