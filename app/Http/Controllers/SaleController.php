<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Business\Sede;
use App\Business\Documento;
use App\Business\Encargo;
use MongoDB\BSON\ObjectId;

class SaleController extends Controller
{

    public function register(Request $request){
        $data = $request->all();
        $insertEncargo = [
            'doc_envia' => $data['docEnvia'],
            'nombre_envia' => $data['nombreEnvia'],
            'celular_envia' => $data['celularEnvia'],
            'email_envia' => $data['emailEnvia'],
            'fecha_envia' => $data['fechaEnvia'],

            'doc_recibe' => $data['docRecibe'],
            'nombre_recibe' => $data['nombreRecibe'],
            'celular_recibe' => $data['celularRecibe'],
            'email_recibe' => $data['emailRecibe'],
            'fecha_recibe' => $data['fechaRecibe'],

            'origen' => $data['origen'],
            'destino' => $data['destino'],
            'agencia' => $data['agencia'],
            'medio_pago' => $data['medioPago'],
            'documento' => $data['documento'],
            'documento_serie' => $data['documentoSerie'],
            'documento_numero' => $data['documentoNumero'],
        ];
        if (strlen($data['encargoId']) > 0) {
            $ObjectId = new ObjectId($data['encargoId']);
            $encargo = Encargo::where('_id', $ObjectId)->update($insertEncargo, ['upsert' => true]);
            $encargoId = $data['encargoId'];
        } else {
            $encargo = Encargo::create($insertEncargo);
            $encargoId = $encargo['id'];
        }
        return \response()->json([ 'status' => 'OK', 'result' => ['encargoId' => $encargoId] ]);
    }

    public function show(){
        $sede = Sede::all();
        $documento = Documento::all();
        return view('sale.show')->with([ 'sede' => $sede, 'documento' => $documento ]);
    }
}
