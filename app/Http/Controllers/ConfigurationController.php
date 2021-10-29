<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Agencia;
use App\Business\Salida;
use App\Business\Vehiculo;

class ConfigurationController extends Controller
{
    
    public function list() {
        $agencia = Agencia::all();
        $salida = Salida::all();
        $vehiculo = Vehiculo::all();
        return view('config.list')->with(['agencia' => $agencia, 'salida' => $salida, 'vehiculo' => $vehiculo]);
    }
}
