<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bebes;
use App\Models\Incubadora;
use App\Models\ContactoFamiliar;
use App\Models\HistorialMedico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Bebess extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $bebes = DB::table('bebes')
                ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->join('estado_del_bebes', 'bebes.id_estado', '=', 'estado_del_bebes.id')
                ->select('bebes.*', 'estado_del_bebes.estado', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
                ->get();
            return response()->json(['Bebes' => $bebes], 200);
        }
        $bebes = DB::table('bebes')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->join('estado_del_bebes', 'bebes.id_estado', '=', 'estado_del_bebes.id')
            ->select('bebes.*', 'estado_del_bebes.estado', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
            ->where('hospitals.id', $user->id_hospital)
            ->get();
        return response()->json(['Bebes' => $bebes], 200);
    }

    public function bebeIncubadora($id_incubadora)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $bebes = DB::table('bebes')
                ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
                ->join('hospitals', 'bebes.id_hospital', '=', 'hospitals.id')
                ->join('estados_del_bebes', 'bebes.id_estado', '=', 'estados_del_bebes.id')
                ->select(
                    'bebes.*',
                    'estado_del_bebes.estado',
                    'incubadoras.id',
                    'hospitals.id as id_hospital',
                    'hospitals.nombre as hospital'
                )
                ->where('bebes.id_incubadora', $id_incubadora)
                ->get();
        }
        $bebes = DB::table('bebes')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('hospitals', 'bebes.id_hospital', '=', 'hospitals.id')
            ->join('estados_del_bebes', 'bebes.id_estado', '=', 'estados_del_bebes.id')
            ->select(
                'bebes.*',
                'estado_del_bebes.estado',
                'incubadoras.id',
                'hospitals.id as id_hospital',
                'hospitals.nombre as hospital'
            )
            ->where('bebes.id_incubadora', $id_incubadora)
            ->where('hospitals.id', 'incubadoras.id_hospital')
            ->where('hospitals.id', $user->id_hospital)
            ->get();
        return response()->json(['Bebes' => $bebes], 200);
    }


    public function bebefull($id)
    {
        $bebe = Bebes::where('id', $id)->first();
        if (!$bebe) {
            return response()->json(['msg' => 'Bebe no encontrado'], 404);
        }

        $contactos = DB::table('contacto_familiars')
            ->where('id_bebe', $id)
            ->where('is_active', true)
            ->get([
                'nombre as nombre',
                'apellido as apellido',
                'telefono as telefono',
                'email',
                'id as id_contactoFamiliar',
                'is_active as is_active_contactoFamiliar',
            ]);

        $historial = DB::table('historial_medicos')
            ->where('id_bebe', $id)
            ->get();

        $bebeDetails = DB::table('bebes')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('estado_del_bebes', 'bebes.id_estado', '=', 'estado_del_bebes.id')
            ->select('bebes.*', 'estado_del_bebes.estado', 'incubadoras.id')
            ->where('bebes.id', $id)
            ->where('bebes.id_estado', '!=', 3)
            ->where('bebes.id_estado', '!=', 2)
            ->first();

        return response()->json([
            'Bebe' => $bebeDetails,
            'Contactos' => $contactos,
            'Historial' => $historial
        ], 200);
    }

    public function show(Request $request)
    {
        $bebe = Bebes::where('id', $request->id)->first();
        if (!$bebe) {
            return response()->json(['msg' => 'Bebe no encontrado'], 404);
        }
        return response()->json(['Bebe' => $bebe, 200]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'sexo' => 'required|in:M,F|min:1|max:1|regex:/^[a-zA-Z ]*$/',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer',
            'peso' => 'required|numeric',
            'id_estado' => 'required|integer',
            'id_incubadora' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $bebe = new Bebes;
        $bebe->nombre = $request->nombre;
        $bebe->apellido = $request->apellido;
        $bebe->sexo = $request->sexo;
        $bebe->fecha_nacimiento = $request->fecha_nacimiento;
        $bebe->edad = $request->edad;
        $bebe->peso = $request->peso;
        $bebe->id_estado = $request->id_estado;
        $bebe->id_incubadora = $request->id_incubadora;
        $incubadora = Incubadora::find($request->id_incubadora);
        if ($incubadora) {
            $incubadora->is_occupied = true;
            $incubadora->save();
        }
        $bebe->save();
        return response()->json(['msg' => 'Bebe creado'], 201);
    }

    public function update(Request $request, $id)
    {
        $bebe = Bebes::find($id);
        if (!$bebe) {
            return response()->json(['msg' => 'Bebe no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'peso' => 'required|numeric',
            'id_estado' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $bebe->nombre = $request->nombre;
        $bebe->apellido = $request->apellido;
        $bebe->peso = $request->peso;
        $bebe->id_estado = $request->id_estado;
        $bebe->save();
        if ($request->id_estado == 3 || $request->id_estado == 2) {
            $incubadora = Incubadora::where('id', $bebe->id_incubadora)->first();
            $incubadora->is_occupied = false;
            $incubadora->save();
        }
        return response()->json(['msg' => 'Bebe actualizado'], 200);
    }

    public function destroy(Request $request, $id)
    {
        $bebe = Bebes::where('id', $id)->first();
        if (!$bebe) {
            return response()->json(['msg' => 'Bebe no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [
            'id_estado' => 'required|integer|exists:estados_del_bebes,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $incubadora = Incubadora::where('id', $bebe->id_incubadora)->first();
        $incubadora->is_occupied = false;
        $bebe->id_estado = 2;
        $bebe->save();
        return response()->json(['msg' => 'Bebe Eliminado'], 200);
    }
}
