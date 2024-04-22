<?php

namespace App\Http\Controllers\Mongo;

use App\Events\testWebsocket;
use App\Http\Controllers\Controller;
use App\Models\Value;
use Illuminate\Http\Request;
use App\Mail\Aviso;
use Illuminate\Support\Facades\Mail;

class Values extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_jwt', ['except' => ['store', 'historial']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user = auth('api_jwt')->user();
        $values = Value::orderBy('_id', 'desc')
            ->whereNotNull('name')
            ->where('name', '!=', 'e')
            ->where('name', '!=', '')
            ->where('name', '!=', 'Pu')
            ->where('name', '!=', 'Te')
            ->get()
            ->groupBy('name')
            ->map(function ($group) {
                return $group->first();
            })
            ->slice(-7);

        $shouldSendMail = false;
        foreach ($values as $value) {
            if ($value->name == 'te' && ($value->value > 40.00 || $value->value < 5.00)) {
                $shouldSendMail = true;
            }
            if ($value->name == 'pu' && ($value->value > 500.00 || $value->value < 20.00)) {
                $shouldSendMail = true;
            }
            if ($value->name == 'ca' && ($value->value > 900.00 || $value->value < 350.00)) {
                $shouldSendMail = true;
            }
            if ($value->name == 'so' && $value->value > 700.00) {
                $shouldSendMail = true;
            }
        }
        if ($shouldSendMail) {
            Mail::to($user->email)->send(new Aviso($user));
        }
        return response()->json(['msg' => 'Valores', 'data' => $values], 200);
    }

    public function historial(Request $request)
    {
        $historial = Value::orderBy('_id', 'desc')->where('name', $request->name)->get();
        return response()->json(['msg' => 'Historial', 'data' => $historial], 200);
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
        $value = Value::create([
            'name' => $request->name,
            'unit' => $request->unit,
            'value' => $request->value,
        ]);
        $message = 'Nuevo valor creado';
        event(new testWebsocket($message));
        return response()->json($value, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function show(Value $value)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function edit(Value $value)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Value $value)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function destroy(Value $value)
    {
        //
    }
}
