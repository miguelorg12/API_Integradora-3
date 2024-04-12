<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rol;

class Roles extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt', ['except' => []]);
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $roles = Rol::all();
            return response()->json(['msg' => 'Roles', 'data' => $roles], 200);
        } else {
            $roles = Rol::where('is_active', true)
                ->where('id', '!=', 1)
                ->get();
        }
        return response()->json(['msg' => 'Roles', 'data' => $roles], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->save();
        return response()->json(['msg' => 'Rol creado', 'data' => $rol], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rol = Rol::where('id', $id)->first();
        if (!$rol) {
            return response()->json(['msg' => 'Rol no encontrado'], 404);
        }
        return response()->json(['Rol' => $rol], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::where('id', $id)->first();
        if (!$rol) {
            return response()->json(['msg' => 'Rol no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $rol->nombre = $request->nombre;
        $rol->save();
        return response()->json(['msg' => 'Rol actualizado'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rol = Rol::where('id', $id)->first();
        if (!$rol) {
            return response()->json(['msg' => 'Rol no encontrado'], 404);
        }
        $rol->is_active = false;
        $rol->save();
        return response()->json(['msg' => 'Rol eliminado'], 200);
    }
}
