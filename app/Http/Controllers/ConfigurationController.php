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
    
    public function list() {
        $agencia = Agencia::all();
        $salida = Salida::all()->sortBy("agencia_id");
        $vehiculo = Vehiculo::all();
        return view('config.list')->with(['agencia' => $agencia, 'salida' => $salida, 'vehiculo' => $vehiculo]);
    }

    public function update(Request $request) {
        $w = $request->input("dia");
        $column = $this->getDay($w, 0);
        if (isset($response)) {
            return response()->json($response);
        }

        $salidaId = $request->input("salidaId");
        $agenciaId = $request->input("agenciaId");
        $placa =  $request->input("placa");
        $horario = $request->input("horario");
        date_default_timezone_set('America/Lima');
        $w = date("w");
        $updateSalida = [
            "agencia_id" => $agenciaId,
            "horario" => $horario,
            $column => $placa,
            $this->getBeforeDay($w, 0) => "",
        ];
        
        $status = Salida::find($salidaId)->update($updateSalida, ['upsert' => true]);
        if ($status) {
            // autocompletar los demás días
            $salida = Salida::find($salidaId);
            $dia0 = $this->getDay($w, 0);
            $dia1 = $this->getDay($w, 1);
            
            $dia2 = $this->getDay($w, 2);
            $dia3 = $this->getDay($w, 3);
            $dia4 = $this->getDay($w, 4);
            $dia5 = $this->getDay($w, 5);
            $updateSalida = [
                $dia2 => $salida[$dia0],
                $dia3 => $salida[$dia1],
                $dia4 => $salida[$dia0],
                $dia5 => $salida[$dia1],
            ];
            $status = Salida::find($salidaId)->update($updateSalida, ['upsert' => true]);
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

}
