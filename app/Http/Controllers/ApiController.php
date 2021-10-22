<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class ApiController extends Controller
{
    public function getEncargo(Request $request) {
            $envia = $request->input('docEnvia');
            $recibe = $request->input('docRecibe');
            // $documento = $request->input('documento');
            $encargo = new \App\Business\Encargo();
            if (strlen($envia)>0) {
                $encargo->where('docEnvia', $envia);
            }
            if (strlen($recibe)>0) {
                $encargo->where('docRecibe', $recibe);
            }
            return response()->json([ 'result' => ['encargo' => $encargo->first()] ]);
    }

    public function getAgencia(String $sedeId) {
        $agencia = \App\Business\Agencia::where('sede_id', new ObjectId("$sedeId"))->get();
        return response()->json($agencia);
    }

    public function getSerie($agenciaId, $documentoId) {
        $serie = \App\Business\Serie::where('agencia_id', new ObjectId("$agenciaId"))
        ->where('documento_id', new ObjectId("$documentoId"))
        ->get();
        return response()->json($serie);
    }

}
