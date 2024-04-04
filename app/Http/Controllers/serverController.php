<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiControlador extends Controller
{
    public function index()
    {
        // Configurar los encabezados de la solicitud
        $response = Http::withHeaders([
            'ngrok-skip-browser-warning' => 'true'
        ])->get('https://allowed-thankfully-sparrow.ngrok-free.app/');

        // Procesar la respuesta
        return $response;
    }
}
