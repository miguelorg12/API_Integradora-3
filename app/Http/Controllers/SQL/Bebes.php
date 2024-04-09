<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bebes;
use App\Models\Incubadora;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Bebe extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $bebes = Bebes::all();
            return response()->json(['msg' => 'Bebes', 'data' => $bebes]);
        }
        $bebes = DB::table('bebes')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->select('bebes.*', 'incubadoras.id', 'incubadoras.nombre as incubadora', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
            ->where('bebes.is_active', true)
            ->where('hospitals.id', $user->id_hospital)
            ->get();
        return response()->json(['Bebes' => $bebes]);
    }

    public function bebeIncubadora($id_incubadora)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $bebes = DB::table('bebes')
                ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
                ->select('bebes.*', 'incubadoras.id', 'incubadoras.nombre as incubadora', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
                ->where('bebes.is_active', true)
                ->where('bebes.id_incubadora', $id_incubadora)
                ->get();
        }
        $bebes = DB::table('bebes')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('hospitals', 'bebes.id_hospital', '=', 'hospitals.id')
            ->select('bebes.*', 'incubadoras.id', 'incubadoras.nombre as incubadora', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
            ->where('bebes.is_active', true)
            ->where('bebes.id_incubadora', $id_incubadora)
            ->where('hospitals.id', 'incubadoras.id_hospital')
            ->get();
        return response()->json(['Bebes' => $bebes]);
    }

    public function show(Request $request)
    {
        $bebe = Bebes::where('id', $request->id)->first();
        if (!$bebe) {
            return response()->json(['msg' => 'Bebe no encontrado']);
        }
        return response()->json(['msg' => 'Bebe', 'data' => $bebe]);
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
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $bebe = new Bebes;
        $bebe->nombre = $request->nombre;
        $bebe->apellido = $request->apellido;
        $bebe->sexo = $request->sexo;
        $bebe->fecha_nacimiento = $request->fecha_nacimiento;
        $bebe->edad = $request->edad;
        $bebe->id_estado = $request->id_estado;
        $bebe->id_incubadora = $request->id_incubadora;
        $bebe->save();
        $incubadora = Incubadora::find($request->id_incubadora);
        if ($incubadora) {
            $incubadora->is_occupied = true;
            $incubadora->save();
        }
        return response()->json(['msg' => 'Bebe creado']);
    }

    public function update(Request $request, $id)
    {
        $bebe = Bebes::find($id);
        if (!$bebe) {
            return response()->json(['msg' => 'Bebe no encontrado']);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'peso' => 'required|numeric',
            'id_estado' => 'required|integer|exists:estados_del_bebes,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()]);
        }
        $bebe->nombre = $request->nombre;
        $bebe->apellido = $request->apellido;
        $bebe->peso = $request->peso;
        $bebe->id_estado = $request->id_estado;
        $bebe->save();
        return response()->json(['msg' => 'Bebe actualizado']);
    }

    public function destroy($id)
    {
        $bebe = Bebes::where('id', $id)->first();
        if (!$bebe) {
            return response()->json(['msg' => 'Bebe no encontrado']);
        }
        $incubadora = Incubadora::where('id', $bebe->id_incubadora)->first();
        $incubadora->is_occupied = false;
        $bebe->id_estado = 2;
        $bebe->save();
        return response()->json(['msg' => 'Bebe dado de alta']);
    }
}
