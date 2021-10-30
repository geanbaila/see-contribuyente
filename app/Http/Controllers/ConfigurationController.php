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
            // hoy y mañana
            $dia0 = $this->getDay($w, 0);
            $dia1 = $this->getDay($w, 1);
            // siguientes 4 días
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
        $placa =  $request->input("placa");
        $dia = $request->input("dia");
        $agencia = $request->input("agencia");
        $horario = $request->input("horario");
        $key1 = $request->input("key1");
        $insertSalida = [
            "placa" => $placa,
            "dia" => $dia,
            "agencia" => $agencia,
            "horario" => $horario,
        ];

        $salida = Salida::create($insertSalida);
        $response = [
            "result" => [
                "status" => "OK", 
                "salidaId" => $salida->id,
            ]
        ];
        return response()->json($response);
    }
 
    function getBeforeDay($w) {
        $ww = ($w==0) ? 6 : $w-1;
        return $this->getDay($ww, 0);
    }
    
    function getDay($w, $j = 0) {
        for ($i = 1; $i <= $j; $i++) {
            $w = ($w == 6) ? 0 : $w+1;
        }
        switch($w){
            case 0: 
                $column = "domingo"; break;
            case 1: 
                $column = "lunes"; break;
            case 2: 
                $column = "martes"; break;
            case 3: 
                $column = "miercoles"; break;
            case 4: 
                $column = "jueves"; break;
            case 5: 
                $column = "viernes"; break;
            case 6: 
                $column = "sabado"; break;
            default:
                $response = ['status' => 'fails']; break;
        }
        return $column;
    }
}
