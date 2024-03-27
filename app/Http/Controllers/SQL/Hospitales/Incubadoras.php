<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incubadora;
use Illuminate\Support\Facades\DB;

class Incubadoras extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $incubadoras = Incubadora::all();
            return response()->json(['msg' => 'Incubadoras', 'data' => $incubadoras]);
        }
        $incubadoras = DB::table('incubadoras')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->select('incubadoras.*', 'hospitals.id', 'hospitals.nombre as hospital', 'bebes.id as id_bebe', 'bebes.nombre as bebe')
            ->where('hospitals.id', $user->id_hospital)
            ->where('incubadoras.is_active', true)
            ->get();

        return response()->json(['Incubadoras' => $incubadoras]);
    }

    public function incubadorasDisponibles()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $incubadoras = DB::table('incubadoras')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->select('incubadoras.*',  'hospitals.nombre as hospital')
                ->where('incubadoras.is_active', true)
                ->where('incubadoras.is_occupied', false)
                ->get();
        }
        $incubadoras =  DB::table('incubadoras')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->select('incubadoras.*',  'hospitals.nombre as hospital')
            ->where('incubadoras.is_active', true)
            ->where('incubadoras.is_occupied', false)
            ->where('hospitals.id', $user->id_hospital)
            ->get();
        return response()->json(['Incubadoras' => $incubadoras]);
    }

    public function showIncubadora($id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $incubadora = DB::table('incubadoras')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->select('incubadoras.*',  'hospitals.nombre as hospital', 'bebes.id as id_bebe', 'bebes.nombre as bebe')
                ->where('incubadoras.id', $id)
                ->first();
        } else {
        }
        $incubadora = DB::table('incubadoras')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->join('bebes', 'incubadoras.id', '=', 'bebes.id_incubadora')
            ->select('incubadoras.*',  'hospitals.nombre as hospital', 'bebes.id as id_bebe', 'bebes.nombre as bebe')
            ->where('incubadoras.id', $id)
            ->where('incubadoras.is_active', true)
            ->first();

        if (!$incubadora) {
            return response()->json(['msg' => 'Incubadora no encontrada']);
        }

        return response()->json(['Incubadora' => $incubadora]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_hospital' => 'required|integer|exists:hospitals,id',
        ]);

        $incubadora = new Incubadora();
        $incubadora->id_hospital = $request->id_hospital;
        $incubadora->save();
        return response()->json(['msg' => 'Incubadora creada']);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $incubadora = Incubadora::where('id', $id)->first();
        } else {
            $incubadora = Incubadora::where('id', $id)->where('is_active', 1)->first();
        }
        if (!$incubadora) {
            return response()->json(['msg' => 'Incubadora no encontrada']);
        }
        $request->validate([
            'id_hospital' => 'required|integer|exists:hospitals,id',
            'is_active' => 'required|boolean',
            'is_occupied' => 'required|boolean',
            'optimo' => 'required|boolean',
        ]);
        $incubadora->id_hospital = $request->id_hospital;
        $incubadora->is_active = $request->is_active;
        $incubadora->is_occupied = $request->is_occupied;
        $incubadora->optimo = $request->optimo;
        $incubadora->save();
        return response()->json(['msg' => 'Incubadora actualizada']);
    }

    public function destroy($id)
    {
        $incubadora = Incubadora::where('id', $id)->where('is_active', 1)->first();
        if (!$incubadora) {
            return response()->json(['msg' => 'Incubadora no encontrada']);
        }
        $incubadora->is_active = false;
        $incubadora->is_ocucupied = false;
        $incubadora->optimo = false;
        $incubadora->save();
        return response()->json(['msg' => 'Incubadora eliminada']);
    }
}
