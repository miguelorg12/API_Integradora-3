<?php

namespace App\Http\Controllers\SQL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactoFamiliar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ContactoFamiliars extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $contactoFamiliar = ContactoFamiliar::all();
            return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar]);
        }
        $contactoFamiliar = DB::table('contacto_familiars')
            ->join('bebes', 'contacto_familiars.id_bebe', '=', 'bebes.id')
            ->join('incubadoras', 'bebes.id_incubadora', '=', 'incubadoras.id')
            ->join('hospitals', 'incubadoras.id_hospital', '=', 'hospitals.id')
            ->select('contacto_familiars.*', 'bebes.id as id_bebe', 'bebes.nombre as bebe', 'incubadoras.id as id_incubadora', 'incubadoras.nombre as incubadora', 'hospitals.id as id_hospital', 'hospitals.nombre as hospital')
            ->where('contacto_familiars.is_active', true)
            ->where('hospitals.id', $user->id_hospital)
            ->get();
        return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->id_rol == 1) {
            $contactoFamiliar = ContactoFamiliar::where('id', $id)->first();
            if (!$contactoFamiliar) {
                return response()->json(['msg' => 'ContactoFamiliar no encontrado']);
            }
            return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar]);
        }
        $contactoFamiliar = ContactoFamiliar::where('id', $id)->where('is_active', true)->first();
        if (!$contactoFamiliar) {
            return response()->json(['msg' => 'ContactoFamiliar no encontrado']);
        }
        return response()->json(['msg' => 'ContactoFamiliar', 'data' => $contactoFamiliar]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
            'email' => 'required|email',
            'id_bebe' => 'required|integer|exists:bebes,id',
        ]);
        $contactoFamiliar = new ContactoFamiliar();
        $contactoFamiliar->nombre = $request->nombre;
        $contactoFamiliar->apellido = $request->apellido;
        $contactoFamiliar->telefono = $request->telefono;
        $contactoFamiliar->email = $request->email;
        $contactoFamiliar->id_bebe = $request->id_bebe;
        $contactoFamiliar->save();
        return response()->json(['msg' => 'ContactoFamiliar creado']);
    }

    public function upadate(Request $request, $id)
    {
        $contactoFamiliar = ContactoFamiliar::where('id', $id)->where('is_active', true)->first();
        if (!$contactoFamiliar) {
            return response()->json(['msg' => 'ContactoFamiliar no encontrado']);
        }
        $request->validate([
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'apellido' => 'required|string|min:3|max:100|regex:/^[a-zA-Z ]*$/',
            'telefono' => 'required|string|min:10|max:10|regex:/^[0-9]*$/',
            'email' => 'required|email',
            'id_bebe' => 'required|integer|exists:bebes,id',
        ]);
        $contactoFamiliar->nombre = $request->nombre;
        $contactoFamiliar->apellido = $request->apellido;
        $contactoFamiliar->telefono = $request->telefono;
        $contactoFamiliar->email = $request->email;
        $contactoFamiliar->id_bebe = $request->id_bebe;
        $contactoFamiliar->save();
        return response()->json(['msg' => 'ContactoFamiliar actualizado']);
    }

    public function destroy($id)
    {
        $contactoFamiliar = ContactoFamiliar::where('id', $id)->where('is_active', true)->first();
        if (!$contactoFamiliar) {
            return response()->json(['msg' => 'ContactoFamiliar no encontrado']);
        }
        $contactoFamiliar->is_active = false;
        $contactoFamiliar->save();
        return response()->json(['msg' => 'ContactoFamiliar eliminado']);
    }
}
