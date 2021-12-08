<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;
use App\Http\Controllers\ManifestController;

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
        $encargo = \App\Business\Encargo::where('_id', new ObjectId("$encargo_id"))->get(['url_documento_pdf', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_pdf);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.pdf', ['Content-Type'=> 'application/pdf']);
    }

    public function downloadXml($encargo_id) {
        $encargo = \App\Business\Encargo::where('_id', new ObjectId("$encargo_id"))->get(['url_documento_xml', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_xml);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.xml', ['Content-Type'=> 'application/xml']);
    }

    public function downloadCdr($encargo_id) {
        $encargo = \App\Business\Encargo::where('_id', new ObjectId("$encargo_id"))->get(['url_documento_cdr', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_cdr);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.zip', ['Content-Type'=> 'application/zip']);
    }

    public function downloadCdrBaja($encargo_id) {
        $encargo = \App\Business\Encargo::where('_id', new ObjectId("$encargo_id"))->get(['url_documento_baja', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_baja);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.zip', ['Content-Type'=> 'application/zip']);
    }

    public function downloadManifiesto($manifiesto_id) {
        $manifiesto = \App\Business\Manifiesto::where('_id', new ObjectId("$manifiesto_id"))->get(['url_documento_pdf', 'nombre_archivo']);
        // $file = storage_path('app/' . $manifiesto[0]->url_documento_pdf);
        $file = base_path('public/' . $manifiesto[0]->url_documento_pdf);
        return \response()
             ->download($file, $manifiesto[0]->nombre_archivo . '.pdf', ['Content-Type'=> 'application/pdf']);
    }

    public function despacho($encargo_id) {
        $fecha_hora_recibe = date('d-m-Y H:i:s');
        $bool = \App\Business\Encargo::where('_id', new ObjectId("$encargo_id"))->update(['fecha_hora_recibe' => $fecha_hora_recibe]);
        if($bool) {
            $response = [
                'result' =>[
                    'status' => 'OK',
                    'message' => 'Encargo entregado.',
                    'fecha_hora_recibe' => $fecha_hora_recibe,
                ],
            ];
        } else {
            $response = [
                'result' =>[
                    'status' => 'fails',
                    'message' => 'No se ha podido registrar la entrega del encargo.',
                    'fecha_hora_recibe' => $fecha_hora_recibe,
                ],
            ];
        }
        return response()->json($response);
    }

    public function noTransportar(Request $request) {
        $prg_encargo_id = $request->input('encargos');
        $bool = \App\Business\Encargo::whereIn('_id', $prg_encargo_id)->update(['estado' => new ObjectId('61af9090d3f9efe2cb27e8b9')]);
        if($bool) {
            $response = [
                'result' =>[
                    'status' => 'OK',
                    'message' => 'Encargos dejados.',
                ],
            ];
        } else {
            $response = [
                'result' =>[
                    'status' => 'fails',
                    'message' => 'No se ha podido registrar el estado del encargo.',
                ],
            ];   
        }
        return response()->json($response);
    }

    public function transportar(Request $request) {
        $prg_encargo_id = $request->input('encargos');
        $bool = \App\Business\Encargo::whereIn('_id', $prg_encargo_id)->update(['estado' => new ObjectId('61af9089d3f9efe2cb27e8b6')]);
        if($bool) {
            $response = [
                'result' =>[
                    'status' => 'OK',
                    'message' => 'Encargos listos para trasladar.',
                ],
            ];
        } else {
            $response = [
                'result' =>[
                    'status' => 'fails',
                    'message' => 'No se ha podido registrar el estado del encargo.',
                ],
            ];   
        }
        return response()->json($response);
    }

    public function empaquetarEnvio(Request $request) {
        $prg_encargo_id = $request->input('encargos');
        $encargo = \App\Business\Encargo::whereIn('_id', $prg_encargo_id);
        $bool = $encargo->update(['estado' => new ObjectId('61af909ad3f9efe2cb27e8be')]);
        if($bool) {
            $prg_encargo = $encargo->get(['agencia_origen', 'agencia_destino', 'documento_serie', 'documento_correlativo', 'cantidad_item', 'oferta']);
            $detalle = [];
            $cantidad_item = 0;
            $subtotal_pagado = 0;
            $subtotal_por_pagar = 0;
            foreach($prg_encargo as $encargo):
                $detalle[] = [
                    'oferta' => $encargo->oferta,
                    'subtotal' => $encargo->subtotal,
                    'cantidad_item' => $encargo->cantidad_item,
                    'agencia_origen' => $encargo->agencia_origen,
                    'agencia_destino' => $encargo->agencia_destino,
                    'documento_serie' => $encargo->documento_serie,
                    'documento_correlativo' => $encargo->documento_correlativo,
                ];
                $cantidad_item += $encargo->cantidad_item;
                $subtotal_pagado += $encargo->oferta;
            endforeach;

            $fecha = date('Y/m/d');
            $nombre_archivo = 'manifiesto.pdf';
            $url_documento_pdf = 'resources/manifiesto/'.$fecha.'/'.$nombre_archivo;
            $manifiesto = \App\Business\Manifiesto::create([
                'fecha' => date('d-m-Y'),
                'hora' => date('H:i:s'),
                'url_documento_pdf' => $url_documento_pdf,
                'nombre_archivo' => $nombre_archivo,
                'items' => 10,
                'detalle' => $detalle,
                'cantidad_item' =>$cantidad_item,
                'subtotal_pagado' =>$subtotal_pagado,
                'subtotal_por_pagar' =>$subtotal_por_pagar,
                'total_general' =>$subtotal_por_pagar + $subtotal_pagado,
            ]);
            (new ManifestController())->escribirPDF($manifiesto);
            $response = [
                'result' =>[
                    'status' => 'OK',
                    'message' => 'Manifiesto generado.',
                    'manifiesto' => $manifiesto,
                ],
            ];
        } else {
            $response = [
                'result' =>[
                    'status' => 'fails',
                    'message' => 'No se ha podido generar el manifiesto.',
                ],
            ];   
        }
        return response()->json($response);
    }

}
