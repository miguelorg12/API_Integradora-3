<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rol;

class Roles extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Rol::all();
        return response()->json(['msg' => 'Roles', 'data' => $roles]);
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
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->save();
        return response()->json(['msg' => 'Rol creado', 'data' => $rol]);
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
            return response()->json(['msg' => 'Rol no encontrado']);
        }
        return response()->json(['msg' => 'Rol', 'data' => $rol]);
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
            return response()->json(['msg' => 'Rol no encontrado']);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $rol->nombre = $request->nombre;
        $rol->save();
        return response()->json(['msg' => 'Rol actualizado', 'data' => $rol]);
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
            return response()->json(['msg' => 'Rol no encontrado']);
        }
        $rol->is_active = false;
        $rol->save();
        return response()->json(['msg' => 'Rol eliminado']);
    }
}
