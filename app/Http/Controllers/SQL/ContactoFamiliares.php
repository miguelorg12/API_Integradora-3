<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactoFamiliar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ContactoFamiliares extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $contactoFamiliar = DB::table('contacto_familiars')
                ->join('bebes', 'contacto_familiars.id_bebe', '=', 'bebes.id')
                ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->select('contacto_familiars.*', 'bebes.id as id_bebe', 'bebes.nombre as bebe_nombre', 'bebes.apellido as bebe_apellido', 'incubadoras.id as id_incubadora', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
                ->where('contacto_familiars.is_active', true)
                ->get();
            return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar], 200);
        } else {
            $contactoFamiliar = DB::table('contacto_familiars')
                ->join('bebes', 'contacto_familiars.id_bebe', '=', 'bebes.id')
                ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
                ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
                ->select('contacto_familiars.*', 'bebes.id as id_bebe', 'bebes.nombre as bebe', 'incubadoras.id as id_incubadora', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
                ->where('contacto_familiars.is_active', true)
                ->where('hospitals.id', $user->id_hospital)
                ->get();
        }
        return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar], 200);
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $contactoFamiliar = ContactoFamiliar::where('id', $id)->first();
            if (!$contactoFamiliar) {
                return response()->json(['msg' => 'ContactoFamiliar no encontrado'], 404);
            }
            return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar], 200);
        } else {
            $contactoFamiliar = ContactoFamiliar::where('id', $id)->where('is_active', true)->first();
        }
        if (!$contactoFamiliar) {
            return response()->json(['msg' => 'ContactoFamiliar no encontrado'], 404);
        }
        return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
            'email' => 'required|email',
            'id_bebe' => 'required|integer|exists:bebes,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $contactoFamiliar = new ContactoFamiliar();
        $contactoFamiliar->nombre = $request->nombre;
        $contactoFamiliar->apellido = $request->apellido;
        $contactoFamiliar->telefono = $request->telefono;
        $contactoFamiliar->email = $request->email;
        $contactoFamiliar->id_bebe = $request->id_bebe;
        $contactoFamiliar->save();
        return response()->json(['msg' => 'ContactoFamiliar creado'], 201);
    }

    public function update(Request $request, $id)
    {
        $contactoFamiliar = ContactoFamiliar::where('id', $id)->where('is_active', true)->first();
        if (!$contactoFamiliar) {
            return response()->json(['msg' => 'ContactoFamiliar no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
            'email' => 'required|email',
            'id_bebe' => 'required|integer|exists:bebes,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Error en los datos', 'errors' => $validator->errors()], 400);
        }
        $contactoFamiliar->nombre = $request->nombre;
        $contactoFamiliar->apellido = $request->apellido;
        $contactoFamiliar->telefono = $request->telefono;
        $contactoFamiliar->email = $request->email;
        $contactoFamiliar->id_bebe = $request->id_bebe;
        $contactoFamiliar->save();
        return response()->json(['msg' => 'ContactoFamiliar actualizado'], 200);
    }

    public function destroy($id)
    {
        $contactoFamiliar = ContactoFamiliar::where('id', $id)->where('is_active', true)->first();
        if (!$contactoFamiliar) {
            return response()->json(['msg' => 'ContactoFamiliar no encontrado'], 404);
        }
        $contactoFamiliar->is_active = false;
        $contactoFamiliar->save();
        return response()->json(['msg' => 'ContactoFamiliar eliminado'], 200);
    }
}
