<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ManifestController;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getEncargo(Request $request) {
        $doc_recibe_envia = $request->input('doc_recibe_envia');
        $documento = $request->input('documento');
        $encargo = \App\Business\Encargo::getAllGuiaRemision($doc_recibe_envia, $documento);
        return response()->json([
            'result' => [
                'encargo' => $encargo
                ]
        ]);
    }

    public function getGuiaRemision(Request $request) {
        $encargo_id = $request->input('encargo_id');
        $encargo = \App\Business\Encargo::find($encargo_id);
        $encargo_detalles = [];
        foreach($encargo->detalles as $item):
            array_push($encargo_detalles, [
                'descripcion' => $item['descripcion'], 
                'codigo_producto' => $item['codigo_producto'], 
                'cantidad_item' => $item['cantidad_item'], 
                'valor_unitario' => $item['valor_unitario'], 
            ]);
        endforeach;
        return response()->json([
            'result' => [
                'status' => 'OK',
                'encargo' => $encargo,
                'detalles' =>$encargo_detalles,
                ]
        ]);
    }

    public function getAgencia(int $sede_id) {
        $agencia = \App\Business\Agencia::where('sede_id', '!=', $sede_id)->get();
        return response()->json($agencia);
    }

    public function getSerie($agencia_origen_id, $agencia_destino_id, $documento_id) {
        $documento = \App\Business\Documento::find($documento_id);
        $agencia = ($documento->alias === 'G') ? $agencia_destino_id : $agencia_origen_id;
        $serie = \App\Business\Serie::where('agencia_id', $agencia)->where('documento_id', $documento_id)->get();
        return response()->json($serie);
    }

    public function getSunat(String $ruc) {
        $token = config('services.apiservice.token');
        $url = config('services.apiservice.url');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url."api/ruc/$ruc?api_token=$token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $empresa = json_decode($response);
        $arrEmpresa = $empresa->data->nombre_o_razon_social;

        $response = [
            'result' =>[
                'nombre' => $arrEmpresa
            ],
        ];
        return response()->json($response);
    }
    
    public function getReniec(String $dni) {
        $token = config('services.apiservice.token');
        $url = config('services.apiservice.url');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url."dni/$dni?api_token=$token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $persona = json_decode($response);
        $arrPersona = $persona->data->nombre_completo;

        $response = [
            'result' =>[
                'nombre' => $arrPersona
            ],
        ];
        return response()->json($response);
    }

    public function downloadPdfBase64($encargo_id) {
        $encargo = \App\Business\Encargo::where('id', $encargo_id)->get(['url_documento_pdf', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_pdf);
        return \response(base64_encode(file_get_contents($file)));
    }

    public function downloadPdf($encargo_id) {
        $encargo = \App\Business\Encargo::where('id', $encargo_id)->get(['url_documento_pdf', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_pdf);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.pdf', ['Content-Type'=> 'application/pdf']);
    }

    public function downloadXml($encargo_id) {
        $encargo = \App\Business\Encargo::where('id', $encargo_id)->get(['url_documento_xml', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_xml);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.xml', ['Content-Type'=> 'application/xml']);
    }

    public function downloadCdr($encargo_id) {
        $encargo = \App\Business\Encargo::where('id', $encargo_id)->get(['url_documento_cdr', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_cdr);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.zip', ['Content-Type'=> 'application/zip']);
    }

    public function downloadCdrBaja($encargo_id) {
        $encargo = \App\Business\Encargo::where('id', $encargo_id)->get(['url_documento_baja', 'nombre_archivo']);
        $file = storage_path('app/' . $encargo[0]->url_documento_baja);
        return \response()
             ->download($file, $encargo[0]->nombre_archivo . '.zip', ['Content-Type'=> 'application/zip']);
    }

    public function downloadManifiesto($manifiesto_id) {
        $manifiesto = \App\Business\Manifiesto::where('id', $manifiesto_id)->get(['url_documento_pdf', 'nombre_archivo']);
        // $file = storage_path('app/' . $manifiesto[0]->url_documento_pdf);
        $file = base_path('public/' . $manifiesto[0]->url_documento_pdf);
        return \response()
             ->download($file, $manifiesto[0]->nombre_archivo . '.pdf', ['Content-Type'=> 'application/pdf']);
    }

    public function despacho($encargo_id) {
        $fecha_hora_recibe = date(env('FORMATO_DATETIME'));
        $bool = \App\Business\Encargo::where('id', $encargo_id)->update(['fecha_hora_recibe' => $fecha_hora_recibe]);
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
        $bool = \App\Business\Encargo::whereIn('id', $prg_encargo_id)->update(['estado' => 2]); // encargo_estado: no transportar
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
        $bool = \App\Business\Encargo::whereIn('id', $prg_encargo_id)->update(['estado' => 1]);  // encargo_estado:  transportar
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
        $encargo = \App\Business\Encargo::whereIn('id', $prg_encargo_id);
        $bool = $encargo->update(['estado' => 3]);  // encargo_estado: en manifiesto
        if($bool) {
            $prg_encargo = $encargo->get([
                'id',
                'oferta',
                'subtotal',
                'pagado',
                'por_pagar',
                'cantidad_item',
                'agencia_origen',
                'agencia_destino',
                'documento_id',
                'documento_serie',
                'documento_correlativo',
            ])->sortBy('documento_id')->values();


            $manifiesto_detalle = [];
            $cantidad_item = 0;
            $subtotal_pagado = 0;
            $subtotal_por_pagar = 0;
            foreach($prg_encargo as $item):
                $manifiesto_detalle[] = [
                    'encargo_id' => $item->id,
                    'oferta' => $item->oferta,
                    'subtotal' => $item->subtotal,
                    'pagado' => $item->pagado,
                    'por_pagar' => $item->por_pagar,
                    'cantidad_item' => $item->cantidad_item,
                    'agencia_origen' => $item->agencia_origen,
                    'agencia_destino' => $item->agencia_destino,
                    'documento_serie' => $item->documento_serie,
                    'documento_correlativo' => $item->documento_correlativo,
                ];
                $cantidad_item += $item->cantidad_item;
                $subtotal_pagado += $item->oferta;
            endforeach;

            $fecha = date('Y/m/d');
            $nombre_archivo = 'manifiesto.pdf';
            $url_documento_pdf = 'resources/manifiesto/'.$fecha.'/'.$nombre_archivo;
            $manifiesto = \App\Business\Manifiesto::create([
                'fecha' => date(env('FORMATO_DATE')),
                'hora' => date('H:i:s'),
                'ruta' => $item->agenciasOrigen->departamento . ' - ' . $item->agenciasDestino->departamento,
                'origen_nombre' => $item->agenciasOrigen->departamento,
                'destino_nombre' => $item->agenciasDestino->departamento,
                'url_documento_pdf' => $url_documento_pdf,
                'nombre_archivo' => $nombre_archivo,
                'cantidad_item' =>$cantidad_item,
                'subtotal_pagado' =>$subtotal_pagado,
                'subtotal_por_pagar' =>$subtotal_por_pagar,
                'total_general' =>$subtotal_por_pagar + $subtotal_pagado,
            ]);
            foreach($manifiesto_detalle as $item):
                \App\Business\ManifiestoDetalle::create(array_merge($item, ['manifiesto_id' => $manifiesto['id']]));
            endforeach;
            
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
