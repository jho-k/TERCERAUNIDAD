<?php

namespace App\Http\Controllers;

use App\Services\ReniecService;
use Illuminate\Http\Request;

class DniController extends Controller
{
    protected $reniecService;

    public function __construct(ReniecService $reniecService)
    {
        $this->reniecService = $reniecService;
    }

    public function buscarDni(Request $request)
    {
        $request->validate(['dni' => 'required|digits:8']);
        $datos = $this->reniecService->consultarDni($request->dni);

        if (isset($datos['error'])) {
            return back()->with('error', 'Error al consultar el DNI: ' . $datos['error']);
        }

        return view('resultado', compact('datos'));
    }
}
