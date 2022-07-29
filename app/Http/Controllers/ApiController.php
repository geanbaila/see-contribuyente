<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ManifestController;

class ApiController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function buscarGuiaRemision(Request $request) {
        $doc_recibe_envia = trim($request->input('doc_recibe_envia'));
        $documento = trim($request->input('documento'));
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
        $history_ruc = new \App\Business\HistoryRUC;
        $row = $history_ruc->find($ruc);
        if($row == null){
            $token = config('services.apiservice.token');
            $url = config('services.apiservice.url');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url."ruc/$ruc?api_token=$token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $empresa = json_decode($response);
            if($empresa->success){
                $arrEmpresa = $empresa->data->nombre_o_razon_social;
                $history_ruc->ruc = $ruc;
                $history_ruc->razon_social = $arrEmpresa;
                $history_ruc->save();
            } else {
                $arrEmpresa = "";
            }
        } else {
            $arrEmpresa = $row->razon_social;
        }

        $response = [
            'result' =>[
                'nombre' => $arrEmpresa
            ],
        ];
        return response()->json($response);
    }
    
    public function getReniec(String $dni) {
        $history_dni = new \App\Business\HistoryDNI;
        $row = $history_dni->find($dni);
        
        if($row == null){
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
            if($persona->success){
                $arrPersona = $persona->data->nombre_completo;
            
                $history_dni->dni = $dni;
                $history_dni->nombres = $arrPersona;
                $history_dni->save();
            } else {
                $arrPersona = "";
            }
        } else {
            $arrPersona = $row->nombres;
        }
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
        $manifiesto = \App\Business\Manifiesto::where('id', $manifiesto_id)->get(['url_documento_pdf', 'nombre_archivo', 'fecha']);
        $file = storage_path('app/' . $manifiesto[0]->url_documento_pdf);
        return \response()
             ->download($file, $manifiesto[0]->nombre_archivo , ['Content-Type'=> 'application/pdf']);
    }

    public function despacho($encargo_id) {
        $fecha_hora_recibe = date(env('FORMATO_DATETIME'));
        $bool = \App\Business\Encargo::where('id', $encargo_id)->where('documento_id', '!=', 3)->update(['fecha_hora_recibe' => $fecha_hora_recibe]);
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

    public function buscarDespacho(Request $request) {
        $doc_recibe_envia = trim($request->input('doc_recibe_envia'));
        $prg_encargo = [];
        if (strlen($doc_recibe_envia)>0) {
            $encargo_estado_en_manifiesto = 3;
            $prg_encargo = \App\Business\Encargo::select('*',
                DB::raw('(select nombre from agencia where id = agencia_origen) as agencia_origen_nombre'),
                DB::raw('(select nombre from agencia where id = agencia_destino) as agencia_destino_nombre'),
                DB::raw('(select date_format(fecha_hora_envia, "%d-%m-%Y %H:%i:%s") ) as fecha_hora_envia')
                )
            ->where('estado', $encargo_estado_en_manifiesto)
            ->whereRaw('(doc_envia='. $doc_recibe_envia.' or doc_recibe=' . $doc_recibe_envia.')')
            ->orderBy('fecha_hora_envia', 'desc')
            ->orderBy('fecha_hora_recibe', 'desc')
            ->paginate(env('PAGINACION_DESPACHOS'));
        } 
        $response = [
            'result' => [
                'status' => 'OK',
                'message' => 'Encargos disponibles para despachar.',
                'encargo' => $prg_encargo,
            ],
        ];
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

            $row_manifiesto = \App\Business\Manifiesto::create([
                'fecha' => date(env('FORMATO_DATE')),
                'hora' => date('H:i:s'),
                'ruta' => $item->agenciasOrigen->departamento . ' - ' . $item->agenciasDestino->departamento,
                'origen_nombre' => $item->agenciasOrigen->departamento,
                'destino_nombre' => $item->agenciasDestino->departamento,
                'url_documento_pdf' => '',
                'nombre_archivo' => '',
                'cantidad_item' =>$cantidad_item,
                'subtotal_pagado' =>$subtotal_pagado,
                'subtotal_por_pagar' =>$subtotal_por_pagar,
                'total_general' =>$subtotal_por_pagar + $subtotal_pagado,
            ]);
            foreach($manifiesto_detalle as $item):
                \App\Business\ManifiestoDetalle::create(array_merge($item, ['manifiesto_id' => $row_manifiesto->id]));
            endforeach;

            $fecha = date('Y/m/d');
            $filename = $row_manifiesto->id.'_'.str_replace('/','',$fecha).'_manifiesto.pdf';

            $tree = 'manifiesto/'.$fecha;
            $estructura = storage_path('app/'.$tree);
            if(!@mkdir($estructura, 0777, true)) {
                if (file_exists($estructura)) { @unlink($estructura); }
            }

            $row_manifiesto->nombre_archivo = $filename;
            $row_manifiesto->url_documento_pdf = $tree . "/" . $filename;
            $row_manifiesto->save();

            (new ManifestController())->escribirPDFManifiesto($row_manifiesto);
            $response = [
                'result' =>[
                    'status' => 'OK',
                    'message' => 'Manifiesto generado.',
                    'manifiesto' => $row_manifiesto,
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
