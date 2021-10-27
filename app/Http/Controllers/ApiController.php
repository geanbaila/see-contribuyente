<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class ApiController extends Controller
{
    public function getEncargo(Request $request) {
            $docRecibeOenvia = $request->input('docRecibeOenvia');
            $documento = $request->input('documento');
            $encargo = new \App\Business\Encargo();
            if (strlen($docRecibeOenvia)>0) {
                $encargo = $encargo->where('doc_envia', $docRecibeOenvia)->orWhere('doc_recibe', $docRecibeOenvia);
            }
            return response()->json([ 'result' => ['encargo' => $encargo->get()] ]);
    }

    public function getAgencia(String $sedeId) {
        $agencia = \App\Business\Agencia::where('sede_id', new ObjectId("$sedeId"))->get();
        return response()->json($agencia);
    }

    public function getSerie($agenciaOrigenId, $agenciaDestinoId, $documentoId) {
        $documento = \App\Business\Documento::find($documentoId);
        $agencia = ($documento->alias === 'G') ? $agenciaDestinoId : $agenciaOrigenId;
        $serie = \App\Business\Serie::where('agencia_id', new ObjectId("$agencia"))
        ->where('documento_id', new ObjectId("$documentoId"))
        ->get();
        return response()->json($serie);
    }

    public function getComprobantePago(String $encargoId) {
        $response = ['result' => ['urlComprobantePago' => url('pruebas/2021/' . $encargoId . '.pdf?v=' .uniqid())]];
        return response()->json($response);
    }

    public function getSunat() {
        $response = [
            'result' =>[
                'nombre' => 'GEAN CARLOS BAILA LAURENTE'
            ],
        ];
        return response()->json($response);
    }
    
    public function getReniec() {
        $response = [
            'result' =>[
                'nombre' => 'GEAN CARLOS BAILA LAURENTE'
            ],
        ];
        return response()->json($response);
    }

}
