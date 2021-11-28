<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;

class ApiController extends Controller
{
    public function getEncargo(Request $request) {
            $doc_recibe_envia = $request->input('doc_recibe_envia');
            $documento = $request->input('documento');
            $encargo = new \App\Business\Encargo();
            if (strlen($doc_recibe_envia)>0) {
                $encargo = $encargo->where('doc_envia', $doc_recibe_envia)->orWhere('doc_recibe', $doc_recibe_envia);
            }
            return response()->json([ 'result' => ['encargo' => $encargo->get()] ]);
    }

    public function getAgencia(String $sede_id) {
        $agencia = \App\Business\Agencia::where('sede', '!=', new ObjectId("$sede_id"))->get();
        return response()->json($agencia);
    }

    public function getSerie($agencia_origen_id, $agencia_destino_id, $documento_id) {
        $documento = \App\Business\Documento::find($documento_id);
        $agencia = ($documento->alias === 'G') ? $agencia_destino_id : $agencia_origen_id;
        $serie = \App\Business\Serie::where('agencia', new ObjectId("$agencia"))
        ->where('documento', new ObjectId("$documento_id"))
        ->get();
        return response()->json($serie);
    }

    public function getSunat() {
        $response = [
            'result' =>[
                'nombre' => 'BITERA EIRL',
                'nombre_comercial' => 'BITERA EIRL',
                'direccion' => 'LAURA CALLER',
                'activo' => true,
                'habido' => true,
                'ubigeo' => '150117',
                'departamento' => 'LIMA',
                'provincia' => 'LIMA',
                'distrito' => 'LOS OLIVOS',
            ]
        ];
        return response()->json($response);
    }
    
    public function getReniec() {
        $response = [
            'result' =>[
                'nombre' => 'GEAN CARLOS BAILA LAURENTE',
                'direccion' => '', 
                'foto' => '',
            ],
        ];
        return response()->json($response);
    }

    public function downloadPdf($encargo_id) {
        $prg_encargo = \App\Business\Encargo::find(new ObjectId("$encargo_id"))->get(['url_documento_pdf', 'nombre_archivo']);
        $file = storage_path('app/' . $prg_encargo[0]->url_documento_pdf);
        return \response()
             ->download($file, $prg_encargo[0]->nombre_archivo . '.pdf', ['Content-Type'=> 'application/pdf']);
    }

    public function downloadXml($encargo_id) {
        $prg_encargo = \App\Business\Encargo::find(new ObjectId("$encargo_id"))->get(['url_documento_xml', 'nombre_archivo']);
        $file = storage_path('app/' . $prg_encargo[0]->url_documento_xml);
        return \response()
             ->download($file, $prg_encargo[0]->nombre_archivo . '.xml', ['Content-Type'=> 'application/xml']);
    }

    public function downloadCdr($encargo_id) {
        $prg_encargo = \App\Business\Encargo::find(new ObjectId("$encargo_id"))->get(['url_documento_cdr', 'nombre_archivo']);
        $file = storage_path('app/' . $prg_encargo[0]->url_documento_cdr);
        return \response()
             ->download($file, $prg_encargo[0]->nombre_archivo . '.zip', ['Content-Type'=> 'application/zip']);
    }

    public function despacho($encargo_id) {
        $fecha_hora_recibe = date('d-m-Y H:i:s');
        $bool = \App\Business\Encargo::where('_id', new ObjectId("$encargo_id"))->update(['fecha_hora_recibe' => $fecha_hora_recibe]);
        if($bool) {
            $response = [
                'result' =>[
                    'status' => 'OK',
                    'message' => 'Paquete entregado.',
                    'fecha_hora_recibe' => $fecha_hora_recibe,
                ],
            ];
        } else {
            $response = [
                'result' =>[
                    'status' => 'fails',
                    'message' => 'No se ha podido registrar la entrega del paquete.',
                    'fecha_hora_recibe' => $fecha_hora_recibe,
                ],
            ];
            
        }
        return response()->json($response);
    }

}
