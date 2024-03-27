<?php

namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mongo\Values;

class ValuesController extends Controller
{
    public function index()
    {
        $values = Values::all();
        return response()->json($values);
    }

    public function store(Request $request)
    {
        $value = new Values;
        $value->fill($request->all());
        $value->save();

        return response()->json($value, 201);
    }

    public function show($id)
    {
        $value = Values::find($id);
        return response()->json($value);
    }

    public function update(Request $request, $id)
    {
        $value = Values::find($id);
        $value->fill($request->all());
        $value->save();

        return response()->json($value);
    }

    public function destroy($id)
    {
        $value = Values::find($id);
        $value->delete();

        return response()->json(null, 204);
    }
}