<?php

namespace App\Http\Controllers\Activador;

use App\Http\Controllers\Controller;
use App\Events\activadorWebSocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class BuzzerController extends Controller
{
    public function activate($state)
    {
        Log::info('Activating buzzer with state: ' . $state);
    event(new activadorWebSocket($state));
    return response()->json(['message' => 'Buzzer state updated']);

    }
}