<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistorialMedico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistorialMedicoBebes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $historialMedico = HistorialMedico::all();
            return response()->json(['msg' => 'HistorialMedico', 'data' => $historialMedico]);
        }
        $historialMedico = DB::table('historial_medicos')
            ->join('bebes', 'historial_medicos.id_bebe', '=', 'bebes.id')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->select('historial_medicos.*', 'bebes.id as id_bebe', 'bebes.nombre as bebe')
            ->where('hospitals.id', $user->id_hospital)
            ->where('historial_medicos.is_active', true)
            ->get();
        return response()->json(['msg' => 'HistorialMedico', 'data' => $historialMedico]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $historialMedico = HistorialMedico::where('id', $id)->first();
            if (!$historialMedico) {
                return response()->json(['msg' => 'HistorialMedico no encontrado']);
            }
            return response()->json(['msg' => 'HistorialMedico', 'data' => $historialMedico]);
        }
        $historialMedico = DB::table('historial_medicos')
            ->join('bebes', 'historial_medicos.id_bebe', '=', 'bebes.id')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->select('historial_medicos.*', 'bebes.id as id_bebe', 'bebes.nombre as bebe')
            ->where('historial_medicos.id', $id)
            ->where('hospitals.id', $user->id_hospital)
            ->where('historial_medicos.is_active', true)
            ->get();
        return response()->json(['msg' => 'HistorialMedico', 'data' => $historialMedico]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_bebe' => 'required|integer|exists:bebes,id',
            'diagnostico' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'medicamentos' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $historialMedico = new HistorialMedico();
        $historialMedico->id_bebe = $request->id_bebe;
        $historialMedico->diagnostico = $request->diagnostico;
        $historialMedico->medicamentos = $request->medicamentos;
        $historialMedico->save();
        return response()->json(['msg' => 'HistorialMedico creado']);
    }

    public function update(Request $request, $id)
    {
        $historialMedico = HistorialMedico::where('id', $id)->first();
        if (!$historialMedico) {
            return response()->json(['msg' => 'HistorialMedico no encontrado']);
        }
        $validator = Validator::make($request->all(), [
            'diagnostico' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
            'medicamentos' => 'required|string|min:3|max:100|regex:/^[a-zA-Z0-9 ]*$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $historialMedico->diagnostico = $request->diagnostico;
        $historialMedico->medicamentos = $request->medicamentos;
        $historialMedico->save();
        return response()->json(['msg' => 'HistorialMedico actualizado']);
    }

    public function destroy($id)
    {
        $historialMedico = HistorialMedico::where('id', $id)->where('is_active', true)->first();
        if (!$historialMedico) {
            return response()->json(['msg' => 'HistorialMedico no encontrado']);
        }
        $historialMedico->is_active = false;
        $historialMedico->save();
        return response()->json(['msg' => 'HistorialMedico eliminado']);
    }
}
