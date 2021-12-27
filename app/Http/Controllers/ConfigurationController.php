<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Agencia;
use App\Business\Salida;
use App\Business\Movimiento;
use App\Business\Vehiculo;
use MongoDB\BSON\ObjectId;

class ConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list() {
        // $agencia = Agencia::all();
        // $salida = Salida::all()->sortBy("agencia");
        // $vehiculo = Vehiculo::all();
        // return view('config.list')->with(['agencia' => $agencia, 'salida' => $salida, 'vehiculo' => $vehiculo, 'menu_configuracion_active' => 'active']);
        return view('config.list');
    }

    /*
    public function update(Request $request) {
        $w = $request->input("dia");
        $column = $this->getDay($w, 0);
        if (isset($response)) {
            return response()->json($response);
        }

        $salidaId = $request->input("salidaId");
        $agencia_id = $request->input("agencia_id");
        $placa =  $request->input("placa");
        $horario = $request->input("horario");
        $w = date("w");
        $update_salida = [
            "agencia" => $agencia_id,
            "horario" => $horario,
            $column => $placa,
            $this->getBeforeDay($w, 0) => "",
        ];
        
        $status = Salida::find($salidaId)->update($update_salida, ['upsert' => true]);
        if ($status) {
            // autocompletar los demás días
            $salida = Salida::find($salidaId);
            $dia0 = $this->getDay($w, 0);
            $dia1 = $this->getDay($w, 1);
            
            $dia2 = $this->getDay($w, 2);
            $dia3 = $this->getDay($w, 3);
            $dia4 = $this->getDay($w, 4);
            $dia5 = $this->getDay($w, 5);
            $update_salida = [
                $dia2 => $salida[$dia0],
                $dia3 => $salida[$dia1],
                $dia4 => $salida[$dia0],
                $dia5 => $salida[$dia1],
            ];
            $status = Salida::find($salidaId)->update($update_salida, ['upsert' => true]);
        }
        
        $response = [
            "result" => [
                "status" => "OK",
            ]
        ];
        return response()->json($response);
    }

    public function register(Request $request) {
        // acción para ingreso de más buses
    }
    */

}
