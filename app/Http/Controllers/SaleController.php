<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Business\Sede;
use App\Business\Item;
use App\Business\Agencia;
use App\Business\Documento;
use App\Business\Encargo;
use App\Business\EncargoDetalle;
use App\Business\Adquiriente;
use App\Business\Baja;
use \Greenter\Model\Response\BillResult;
use \Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use \Greenter\Model\Sale\Invoice;
use \Greenter\Model\Sale\SaleDetail;
use \Greenter\Model\Sale\Legend;
use \Greenter\Model\Sale\Charge;
use \Greenter\Model\Sale\Detraction;
use \Greenter\Ws\Services\SunatEndpoints;
use \Greenter\XMLSecLibs\Sunat\SignedXml;
use \Greenter\See;
use \Greenter\Model\Client\Client;
use \Greenter\Model\Company\Address;
use \Greenter\Model\Voided\Voided;
use \Greenter\Model\Voided\VoidedDetail;

use \PHPQRCode\QRcode;
use PDF;
use App\Http\Controllers\Util;


class SaleController extends Controller
{
    public function list() {
        $encargo = Encargo::all()->sortByDesc('fecha_hora_envia')->values();
        return view('sale.list')->with([
            'encargo' => $encargo,
            'menu_venta_active' => 'active',
        ]);
    }

    public function show() {
        $sede = Sede::all();
        $item = Item::orderBy('nombre','asc')->get();
        $agencia_origen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        return view('sale.show')->with([
            'agencia_origen' => $agencia_origen,
            'sede' => $sede,
            'documento' => $documento,
            'carga' => $item,
            'menu_venta_active' => 'active',
        ]);
    }

    public function edit($encargo_id) {
        $sede = Sede::all();
        $item = Item::all();
        $agencia_origen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        $encargo = Encargo::find($encargo_id);
        return view('sale.edit')->with([
            'agencia_origen' => $agencia_origen,
            'sede' => $sede,
            'documento' => $documento,
            'carga' => $item,
            'encargo' => $encargo,
            'menu_venta_active' => 'active',
        ]);
    }

    public function register(Request $request) {
        // try {
            $data = $request->all();
            $var = function() {     
                $prg = func_get_arg(0);
                $gravado = [];
                $exonerado = [];
                $inafecto = [];
                $gravado_gratuito = [];
                $inafecto_gratuito = [];
                $precio_venta = 0;
                $monto_gravado = 0;
                $monto_exonerado = 0;
                $monto_inafecto = 0;
                $cantidad_item = 0;

                foreach($prg as $item) :
                    if ($item['descripcion'] !== "--") :
                        $row_item = Item::find($item['descripcion']);
                        if ($row_item) :
                            if ($row_item->tipo_afectaciones->codigo == env('AFECTACION_GRAVADO')) {
                                array_push($gravado, [
                                    'item_id' => $row_item->id,
                                    'codigo_producto' => $row_item->codigo_producto,
                                    'descripcion' => $row_item->nombre,
                                    'cantidad_item' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    'valor_unitario' => $item['valor_unitario'],
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => env('IGV') * 100,
                                    'igv_venta' => ($item['cantidad'] * $item['valor_unitario'] * env('IGV') * 100) / 100,
                                    'tipo_afectacion' => $row_item->tipo_afectaciones->codigo,
                                    'precio_unitario' => $item['valor_unitario'] * (1 + env('IGV')),
                                ]);
                                $monto_gravado += $item['cantidad'] * $item['valor_unitario'];
                                $precio_venta += $item['cantidad'] * $item['valor_unitario'] * (1 + env('IGV'));
                                $cantidad_item += $item['cantidad'];
                            }
                            
                            if ($row_item->tipo_afectaciones->codigo == env('AFECTACION_EXONERADO')) {
                                array_push($exonerado, [
                                    'item_id' => $row_item->id,
                                    'codigo_producto' => $row_item->codigo_producto,
                                    'descripcion' => $row_item->nombre,
                                    'cantidad_item' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    'valor_unitario' => $item['valor_unitario'],
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => 0,
                                    'igv_venta' => 0,
                                    'tipo_afectacion' => $row_item->tipo_afectaciones->codigo,
                                    'precio_unitario' => $item['valor_unitario'],
                                ]);
                                $monto_exonerado += $item['cantidad'] * $item['valor_unitario'];
                                $precio_venta += $item['cantidad'] * $item['valor_unitario'] * (1 + env('IGV'));
                                $cantidad_item += $item['cantidad'];
                            }
                            
                            if ($row_item->tipo_afectaciones->codigo == env('AFECTACION_INAFECTO')) {
                                array_push($inafecto, [
                                    'item_id' => $row_item->id,
                                    'codigo_producto' => $row_item->codigo_producto,
                                    'descripcion' => $row_item->nombre,
                                    'cantidad_item' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    'valor_unitario' => $item['valor_unitario'],
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => 0,
                                    'igv_venta' => 0,
                                    'tipo_afectacion' => $row_item->tipo_afectaciones->codigo,
                                    'precio_unitario' => $item['valor_unitario'],
                                ]);
                                $monto_inafecto += $item['cantidad'] * $item['valor_unitario'];
                                $precio_venta += $item['cantidad'] * $item['valor_unitario'] * (1 + env('IGV'));
                                $cantidad_item += $item['cantidad'];
                            }

                            if ($row_item->tipo_afectaciones->codigo == env('AFECTACION_GRAVADO_GRATUITO')) {
                                array_push($gravado_gratuito, [
                                    'item_id' => $row_item->id,
                                    'codigo_producto' => $row_item->codigo_producto,
                                    'descripcion' => $row_item->nombre,
                                    'cantidad_item' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    'valor_unitario' => 0,
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_gratuito' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => env('IGV') * 100,
                                    'igv_venta' => ($item['cantidad'] * $item['valor_unitario'] * env('IGV') * 100) / 100,
                                    'tipo_afectacion' => $row_item->tipo_afectaciones->codigo,
                                    'precio_unitario' => 0,
                                ]);
                                $precio_venta += $item['cantidad'] * 0;
                                $cantidad_item += $item['cantidad'];
                            }

                            if ($row_item->tipo_afectaciones->codigo == env('AFECTACION_INAFECTO_GRATUITO')) {
                                array_push($inafecto_gratuito, [
                                    'item_id' => $row_item->id,
                                    'codigo_producto' => $row_item->codigo_producto,
                                    'descripcion' => $row_item->nombre,
                                    'cantidad_item' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    'valor_unitario' => 0,
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_gratuito' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => 0,
                                    'igv_venta' => 0,
                                    'tipo_afectacion' => $row_item->tipo_afectaciones->codigo,
                                    'precio_unitario' => 0,
                                ]);
                                $precio_venta += $item['cantidad'] * 0;
                                $cantidad_item += $item['cantidad'];
                            } 
                        endif;
                    endif;
                endforeach;

                return [
                    array_merge($gravado, $exonerado,$inafecto,$gravado_gratuito,$inafecto_gratuito),
                    [
                        'cantidad_item' => $cantidad_item,
                        'monto_gravado' => number_format($monto_gravado, 2, '.', ''),
                        'monto_exonerado' => number_format($monto_exonerado, 2, '.', ''),
                        'monto_inafecto' => number_format($monto_inafecto, 2, '.', ''),
                        'importe_pagar_con_igv' => number_format($precio_venta, 2, '.', ''),
                        'importe_pagar_sin_igv' => number_format($precio_venta / (1 + env('IGV')) , 2, '.', ''),
                        'importe_pagar_igv' => number_format($precio_venta - ($precio_venta / (1 + env('IGV'))), 2, '.', ''),
                    ]
                ];
            };

            if (!$columnas = $var($data['encargo'])) {
                return \response()->json([
                    'result' => [
                        'status' => 'fails', 
                        'message' => 'No ha ingresado el detalle de la encomienda.'
                    ]
                ]);
            }

            if (strlen($data['doc_recibe']) === 8) {
                $tipo_documento = 'DNI';
            }else if (strlen($data['doc_recibe']) === 11) {
                $tipo_documento = 'RUC';
            } else {
                $tipo_documento = '';
            }

            $row_documento = Documento::find($data['documento']);
            
            // registrar o actualizar datos del adquiriente
            if ($row_documento->alias === 'B') {
                // boletas
                $insert_adquiriente = [
                    'tipo_documento' => $tipo_documento,
                    'documento' => $data['doc_envia'],
                    'razon_social' => $data['nombre_envia'],
                    'nombre_comercial' => $data['nombre_comercial_envia'],
                    'direccion' => $data['direccion_envia'],
                ];
            } else if ($row_documento->alias === 'F') {
                // facturas
                $insert_adquiriente = [
                    'tipo_documento' => $tipo_documento,
                    'documento' => $data['doc_envia'],
                    'razon_social' => $data['nombre_envia'],
                    'nombre_comercial' => $data['nombre_comercial_envia'],
                    'direccion' => $data['direccion_envia'],
                ];
            } else if ($row_documento->alias === 'G') {
                // guía de remisión
                $insert_adquiriente = [
                    'tipo_documento' => $tipo_documento,
                    'documento' => $data['doc_recibe'],
                    'razon_social' => $data['nombre_recibe'],
                    'nombre_comercial' => $data['nombre_comercial_recibe'],
                    'direccion' => $data['direccion_recibe'],
                ];
            } else {
                return \response()->json([
                    'result' => [
                        'status' => 'fails', 
                        'message' => 'Ha ocurrido un error interno en el sistema, contacta con el proveedor.<br>Código de error: 100',
                    ]
                ]);
            }

            if (strlen($data['encargo_id']) > 0) {
                $adquiriente_id = $data['adquiriente'];
            } else {
                $adquiriente_id = (Adquiriente::create($insert_adquiriente))->id;
            }
            
            // registrar o actualizar el encargo
            $agencia_id = $data['agencia_origen']; // agencia que está en sesión. hacer luego
            $insert_encargo = array_merge([
                'doc_envia' => $data['doc_envia'],
                'nombre_envia' => $data['nombre_envia'],
                // 'celular_envia' => $data['celular_envia'],
                // 'email_envia' => $data['email_envia'],
                'fecha_hora_envia' => (empty($data['fecha_hora_envia']))?date(env('FORMATO_DATETIME')):$data['fecha_hora_envia'],

                'doc_recibe' => $data['doc_recibe'],
                'nombre_recibe' => $data['nombre_recibe'],
                // 'celular_recibe' => $data['celular_recibe'],
                // 'email_recibe' => $data['email_recibe'],
                'fecha_recibe' => (empty($data['fecha_recibe']))?date(env('FORMATO_DATE')):$data['fecha_recibe'],

                // 'origen' => $data['origen'],
                // 'destino' => $data['destino']),
                'agencia_origen' => $data['agencia_origen'],
                'agencia_destino' => $data['agencia_destino'],

                'agencia_id' => $agencia_id,
                'adquiriente_id' => $adquiriente_id,
                // 'medio_pago' => $data['medio_pago'],
                'documento_id' => $data['documento'],
                'documento_serie' => $data['documento_serie'],
                'documento_correlativo' => $data['documento_correlativo'],
                'documento_fecha' => date(env('FORMATO_DATE')),
                'documento_hora' => date('H:i:s'),

                'subtotal' => number_format($data['subtotal'], 2, '.', ''),
                'oferta' => number_format($data['importe_pagar_con_descuento'], 2, '.', ''),
                'descuento' => number_format($data['descuento'], 2, '.', ''),

                'estado' => 2, // encargo_estado: no trasladar
            ], $columnas[1]);
            
            $encargo = null;
            if (strlen($data['encargo_id']) > 0) {
                //controlar duplicidad de registros
                $encargo_id = $data['encargo_id']; 
                $fecha_hora_envia = $data['fecha_hora_envia'];
                $documento_correlativo = $data['documento_correlativo'];
                
                // bloquear actualizar los registros
                // $object_id = $data['encargo_id'];
                // $encargo = Encargo::where('id', $object_id)->update($insert_encargo, ['upsert' => true]);
                // if (!$encargo) {
                //     return \response()->json(['result' => ['status' => 'fails', 'message' => 'No hubo ningún cambio.']]);
                // }

            } else {
                $encargo_id = Encargo::create($insert_encargo)->id;

                foreach($columnas[0] as $item):
                    $insert_encargo_detalle = array_merge($item, ['encargo_id' => $encargo_id]);
                    EncargoDetalle::create($insert_encargo_detalle);
                endforeach;

                $documento_correlativo = Documento::nuevoCorrelativo($encargo_id, $data['documento_serie']);
                $fecha_hora_envia = date(env('FORMATO_DATETIME'));
                $update = ['fecha_hora_envia' => $fecha_hora_envia, 'documento_correlativo' => $documento_correlativo];
                
                if(number_format($data['subtotal'], 2, '.', '') > env('DETRACCION')) {
                    $update = array_merge($update, [
                        'detraccion_codigo' => env('EMPRESA_CODIGO_DETRACCION'),
                        'detraccion_medio_pago' => '001', // catalog. 59 : Depósito en cuenta
                        'detraccion_cta_banco' => env('EMPRESA_CUENTA_BANCARIA_DETRACCION'),
                        'detraccion_porcentaje' => env('EMPRESA_TASA_DETRACCION') * 100,
                        'detraccion_monto' => number_format(($encargo['monto_gravado']-$encargo['descuento']) * (1 + env('IGV')) * env('EMPRESA_TASA_DETRACCION') , 2, '.', ''),
                    ]);
                }
                $encargo = Encargo::where('id', $encargo_id)->update($update);

            }

            // registrar o actualizar el PDF
            if ($row_documento->alias === 'B') {
                $url_documento_pdf = $this->escribirBoleta($encargo_id);
                $url_documento = $this->escribirXMLBoleta($encargo_id);

            } else if ($row_documento->alias === 'F') {
                $url_documento_pdf = $this->escribirFactura($encargo_id);
                $url_documento = $this->escribirXMLFactura($encargo_id);
             
            } else if ($row_documento->alias === 'G') {
                $url_documento_pdf = $this->escribirGuiaRemision($encargo_id);
                $url_documento = ['error'=> '', 'xml' => '', 'cdr' => '', 'nombre_archivo' => '', 'cdr_descripcion' => '|||'];

            } else {
                // no escribir PDF
                return \response()->json([
                    'result' => [
                        'status' => 'fails',
                        'message' => 'Ha ocurrido un error(200) en el sistema, contacta con el proveedor.'
                        ]
                    ]);
            }
            
            if ($url_documento['error']) {
                return \response()->json([
                    'result' => [
                        'status' => 'fails', 
                        'message' => 'Ha ocurrido un error interno.<br>' . $url_documento['error']
                        ]
                    ]);
            }

            list($cdr_id, $cdr_codigo, $cdr_descripcion, $cdr_notas) = explode('|', $url_documento['cdr_descripcion']);
            Encargo::where('id', $encargo_id)->update([
                'url_documento_pdf' => $url_documento_pdf,
                'url_documento_xml' => $url_documento['xml'],
                'url_documento_cdr' => $url_documento['cdr'],
                'nombre_archivo' => $url_documento['nombre_archivo'],
                'cdr_id' => $cdr_id,
                'cdr_codigo' => $cdr_codigo,
                'cdr_descripcion' => $cdr_descripcion,
                'cdr_notas' => $cdr_notas,
            ]);

            return \response()->json([
                'result' => [
                    'status' => 'OK', 
                    'message' => 'Registrado correctamente', 
                    'encargo_id' => $encargo_id, 
                    'adquiriente' => $adquiriente_id, 
                    'documento_correlativo' => $documento_correlativo,
                    'fecha_hora_envia' => $fecha_hora_envia,
                    'url_documento_pdf' => $url_documento_pdf,
                    'cdr_descripcion' => $cdr_descripcion .' <img src="'. asset('assets/media/check-circle.svg'). '" width="20" />',
                ]
            ]);
        // } catch(\Throwable $e) {
        //     return \response()->json([
        //         'result' => [
        //             'status' => 'fails', 
        //             'message' => 'Ha ocurrido un error interno.<br>'.$e->getMessage()
        //             ]
        //         ]);
        // }
    }

    public function baja(Request $request) {
        $prg_encargo = Encargo::whereIn('id', $request->encargo_id)->get();
        if (!empty($prg_encargo)):
            $util = Util::getInstance();
            $items = null;
            $documentos = '';
            foreach($prg_encargo as $encargo):
                if ($encargo->documentos->alias === 'F') {
                    $tipo_documento = '01';
                } else if ($encargo->documentos->alias === 'B') { 
                    $tipo_documento = '03';
                } else {
                    continue;
                }
                $items[] = (new VoidedDetail())
                    ->setTipoDoc($tipo_documento)
                    ->setSerie($encargo->documento_serie)
                    ->setCorrelativo($encargo->documento_correlativo)
                    ->setDesMotivoBaja('ERROR DE SISTEMA');
                $documentos .= $encargo->documento_serie . '-' .$encargo->documento_correlativo . '<br>';

            endforeach;

            if(!empty($items)):
                $correlativo = sprintf("%05d", Baja::getNextSequence());
                $voided = new Voided();
                $voided->setCorrelativo($correlativo)
                    ->setFecGeneracion(new \DateTime($encargo->documento_fecha.' '.$encargo->documento_hora))
                    ->setFecComunicacion(new \DateTime())
                    ->setCompany(Util::getCompany())
                    ->setDetails($items);

                list($year, $month, $day) = explode('-', $encargo->documento_fecha); // yyyy-mm-dd 
                $folder = 'bajas/' . $year . '/' . $month . '/' . $correlativo;
                    
                $see = $util->getSee(SunatEndpoints::FE_BETA);
                $res = $see->send($voided);
                $util->writeXml($folder, $voided, $see->getFactory()->getLastXml());
                
                if (!$res->isSuccess()) {
                    $response = [
                        'result' => [
                            'status' => 'fails',
                            'message' => 'No se pudo comunicar la baja del comprobante de pago electrónico.' . $util->getErrorResponse($res->getError()),
                            ]
                        ];
                    return \response()->json($response);
                }

                $ticket = $res->getTicket();
                $res = $see->getStatus($ticket);
                if (!$res->isSuccess()) {
                    $response = [
                        'result' => [
                            'status' => 'fails',
                            'message' => 'No se pudo comunicar la baja del comprobante de pago electrónico.' . $util->getErrorResponse($res->getError()),
                            ]
                        ];
                    return \response()->json($response);
                }

                $cdr = $res->getCdrResponse();
                $url_documento_baja = $util->writeCdr($folder, $voided, $res->getCdrZip());

                $update = ['url_documento_baja' => $url_documento_baja];
                $bool = Encargo::whereIn('id', $request->encargo_id)->update($update, ['upsert' => true]);
                if (!$bool) {
                    $documentos .= "La base de datos fue actualizada.";
                }

                $response = [
                    'result' => [
                        'status' => 'OK',
                        'message' => 'Se comunicó a SUNAT la baja del Comprobante de Pago Electrónico <b>'.$documentos .'</b>.',
                    ]
                ];
                return response()->json($response);
            endif;
        endif;
        $response = [
            'result' => [
                'status' => 'fails',
                'message' => 'No se ha encontrado Comprobante de Pago Electrónico para comunicar su baja.',
                ]
            ];
        return response()->json($response);
    }

    public function escribirBoleta($encargo_id) {
        $data = Encargo::buscarBoleta($encargo_id);
        $url_documento_pdf = '';
        if ($data) :
            $textodniruc = (strlen($data['adquiriente_ruc_dni_ce']) === 11) ? 'RUC: ': 'DNI/CE: ';
            $textodniruc .= $data['adquiriente_ruc_dni_ce'];
            list($year, $month, $day) = explode("-", $data['emisor_fecha_documento_electronico']); // yyyy-mm-dd

            PDF::SetTitle($data['titulo_documento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            $font_size_grande = 9;
            $font_size_gigante = 10;
            $font_size_regular = 7;
            
            $border = 'B';
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::MultiCell($width, $height, $data['emisor_nombre_comercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_razon_social'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_direccion_fiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_ruc'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::Cell($width, $height, "TELÉFONO: " . $data['emisor_agencia_telefono'], 'T', 1, 'L', 0);
            PDF::MultiCell($width, $height, "TERMINAL: ".$data['emisor_agencia_direccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------
            
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell($width, $height, $data['emisor_tipo_documento_electronico'], 'T', 1, 'C', 0);
            PDF::MultiCell($width, $height, $data['emisor_numero_documento_electronico'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "OPERADOR: ", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " .$day.'/'.$month.'/'.$year, 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: " . $data['emisor_hora_documento_electronico'], 'B', 1, 'R', 0);
            // -------------
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['adquiriente_razon_social'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, $textodniruc, '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $font_size_grande);
            foreach($data['consigna'] as $nombre):
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "DIRECCIÓN: ". $data['destino_direccion'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::MultiCell(40, $height, $data['destino'], '', $align_right, 1, 0, $x, $y);
            PDF::Ln();

            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $font_size_regular);
            PDF::Cell(34, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(8, $height, "CANT", '', 0, 'L', 1);
            PDF::Cell(8, $height, "PREC", '', 0, 'L', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            foreach($data['detalle'] as $encargo):
                PDF::MultiCell(34, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(8, $height, $encargo['cantidad_item'], '', 'R', 1, 0, $x, $y);
                PDF::MultiCell(8, $height, number_format($encargo['precio_unitario'], 2, '.', '') , '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, number_format($encargo['valor_venta'] + $encargo['igv_venta'], 2, '.', ''), '', 'R', 1, 0, $x, $y);
                PDF::Ln();
            endforeach;
            
            $descuentos = number_format($data['descuento'], 2, '.', '');         
         
            PDF::Cell(35, $height, "DESCUENTOS GLOBALES", 'T', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", 'T', 0, 'C', 1);
            PDF::Cell(20, $height, $descuentos, 'T', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "SUBTOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, number_format($data['importe_pagar_sin_igv'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.GRAVADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, number_format($data['importe_pagar_sin_igv'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.EXONERADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0.00", '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::Cell(35, $height, "OP.GRATUITA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0.00", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "IGV " . (env('IGV')*100) . "%", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, number_format($data['importe_pagar_igv'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::Cell(35, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell(20, $height, number_format($data['oferta'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();

            // PDF::SetFont('times', '', $font_size_grande);
            // PDF::Cell($width, $height, "hash SUNAT:", 'T', 1, 'L', 1);
            // PDF::Cell($width, $height, "1c7a92ae351d4e21ebdfb897508f59d6", '', 1, 'L', 1);

            $tree = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
            $filename = $data['emisor_ruc'].'-03-'.$data['emisor_numero_documento_electronico'] . '.pdf';
            $estructura = storage_path('app/'.$tree);
            if(!@mkdir($estructura, 0777, true)) {
                if (file_exists($estructura . "/" . $filename)) { @unlink($estructura . "/" . $filename); }
            }
            $qr = $this->getQR($estructura, $data);
            QRcode::png($qr['value'], $qr['img'], 'L', 4, 2);
            PDF::Image($qr['img'], '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "Representación impresa de ".$data['emisor_tipo_documento_electronico'].". Puede descargarlo y/o consultarlo desde www.enlacesbus.com.pe/see", $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
            
            $output = $estructura . "/" . $filename;
            PDF::Output($output, 'F');
            PDF::reset();
            $url_documento_pdf = $tree . "/" . $filename;
        endif;
        return $url_documento_pdf;
    }

    public function escribirFactura($encargo_id) {
        $data = Encargo::buscarFactura($encargo_id);
        $url_documento_pdf = '';
        if ($data) :
            $textodniruc = 'RUC: '.$data['adquiriente_ruc'];
            list($year, $month, $day) = explode("-", $data['emisor_fecha_documento_electronico']); // yyyy-mm-dd
            
            PDF::SetTitle($data['titulo_documento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            $font_size_grande = 9;
            $font_size_gigante = 10;
            $font_size_regular = 7;
            
            $border = 'B';
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::MultiCell($width, $height, $data['emisor_nombre_comercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_razon_social'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_direccion_fiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_ruc'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::Cell($width, $height, "TELÉFONO: " . $data['emisor_agencia_telefono'], 'T', 1, 'L', 0);
            PDF::MultiCell($width, $height, "TERMINAL: ".$data['emisor_agencia_direccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------
            
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell($width, $height, $data['emisor_tipo_documento_electronico'], 'T', 1, 'C', 0);
            PDF::MultiCell($width, $height, $data['emisor_numero_documento_electronico'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "OPERADOR: ", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " .$day.'/'.$month.'/'.$year, 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: " . $data['emisor_hora_documento_electronico'], 'B', 1, 'R', 0);
            // -------------
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['adquiriente_razon_social'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            
            PDF::MultiCell($width, $height, $textodniruc, '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $font_size_grande);
            foreach($data['consigna'] as $nombre):
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "DIRECCIÓN: ". $data['destino_direccion'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::MultiCell(40, $height, $data['destino'], '', $align_right, 1, 0, $x, $y);
            PDF::Ln();

            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $font_size_regular);
            PDF::Cell(34, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(8, $height, "CANT", '', 0, 'L', 1);
            PDF::Cell(8, $height, "PREC", '', 0, 'L', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            foreach($data['detalle'] as $encargo):
                PDF::MultiCell(34, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(8, $height, $encargo['cantidad_item'], '', 'R', 1, 0, $x, $y);
                PDF::MultiCell(8, $height, number_format($encargo['precio_unitario'], 2, '.', '') , '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, number_format($encargo['valor_venta'] + $encargo['igv_venta'], 2, '.', ''), '', 'R', 1, 0, $x, $y);
                PDF::Ln();
            endforeach;
     
            $descuentos = number_format($data['descuento'], 2, '.', '');         
            PDF::Cell(35, $height, "DESCUENTOS GLOBALES", 'T', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", 'T', 0, 'C', 1);
            PDF::Cell(20, $height, $descuentos, 'T', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "SUBTOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, number_format($data['importe_pagar_sin_igv'], 2, '.',''), '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.GRAVADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, number_format($data['importe_pagar_sin_igv'], 2, '.',''), '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.EXONERADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0.00", '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::Cell(35, $height, "OP.GRATUITA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0.00", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "IGV " . (env('IGV')*100) . "%", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, number_format($data['importe_pagar_igv'], 2, '.',''), '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::Cell(35, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell(20, $height, number_format($data['oferta'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();

            // PDF::SetFont('times', '', $font_size_grande);
            // PDF::Cell($width, $height, "hash SUNAT:", 'T', 1, 'L', 1);
            // PDF::Cell($width, $height, "1c7a92ae351d4e21ebdfb897508f59d6", '', 1, 'L', 1);
 
            $tree = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
            $filename = $data['emisor_ruc'].'-01-'.$data['emisor_numero_documento_electronico'] . '.pdf';
            $estructura = storage_path('app/'.$tree);
            if(!@mkdir($estructura, 0777, true)) {
                if (file_exists($estructura . "/" . $filename)) { @unlink($estructura . "/" . $filename); }
            }
            $qr = $this->getQR($estructura, $data);
            QRcode::png($qr['value'], $qr['img'], 'L', 4, 2);
            PDF::Image($qr['img'], '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "Representación impresa de ".$data['emisor_tipo_documento_electronico'].". Puede descargarlo y/o consultarlo desde www.enlacesbus.com.pe/see", $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
            
            $output = $estructura . "/" . $filename;
            PDF::Output($output, 'F');
            PDF::reset();
            $url_documento_pdf = $tree . "/" . $filename;
        endif;
        return $url_documento_pdf;
    }

    public function escribirGuiaRemision($encargo_id) {
        $data = Encargo::buscarGuiaRemision($encargo_id);
        $url_documento_pdf = '';
        if ($data) :
            $textodniruc = (strlen($data['adquiriente_ruc_dni_ce']) === 11) ? 'RUC: ': 'DNI/CE: ';
            $textodniruc .= $data['adquiriente_ruc_dni_ce'];
            list($year, $month, $day) = explode("-", $data['emisor_fecha_documento_electronico']); // yyyy-mm-dd

            PDF::SetTitle($data['titulo_documento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            $font_size_grande = 9;
            $font_size_gigante = 10;
            $font_size_regular = 7;
            
            $border = 'B';
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::MultiCell($width, $height, $data['emisor_nombre_comercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_razon_social'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_direccion_fiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, $data['emisor_ruc'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::Cell($width, $height, "TELÉFONO: " . $data['emisor_agencia_telefono'], 'T', 1, 'L', 0);
            PDF::MultiCell($width, $height, "TERMINAL: ".$data['emisor_agencia_direccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------
            
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell($width, $height, $data['emisor_tipo_documento_electronico'], 'T', 1, 'C', 0);
            PDF::MultiCell($width, $height, $data['emisor_numero_documento_electronico'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", '', 1, 'L', 1);
            $img = base_path('public/assets/media/bus.svg');
            
            PDF::Image($img, '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "OPERADOR: ", 'T', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " .$day.'/'.$month.'/'.$year, 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: " . $data['emisor_hora_documento_electronico'], 'B', 1, 'R', 0);
            // -------------
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['adquiriente_razon_social'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, $textodniruc, '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $font_size_grande);
            foreach($data['consigna'] as $nombre):
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "DIRECCIÓN: ". $data['destino_direccion'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
 

            PDF::SetFont('times', 'B', $font_size_regular);
            PDF::Cell(20, $height, "DESCRIP", '', 0, 'L', 1);
            PDF::Cell(8, $height, "CÓD", '', 0, 'R', 1);
            PDF::Cell(8, $height, "CANT", '', 0, 'L', 1);
            PDF::Cell(8, $height, "PESO", '', 0, 'L', 1);
            PDF::Cell(8, $height, "UM", '', 0, 'L', 1);
            PDF::Cell(8, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            foreach($data['detalle'] as $encargo):
                PDF::MultiCell(20, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(8, $height, $encargo['codigo_producto'], '', 'R', 1, 0, $x, $y);
                PDF::MultiCell(8, $height, $encargo['cantidad_item'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(8, $height, 1, '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(8, $height, 'KG', '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(8, $height, $encargo['valor_venta'], '', $align_center, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;
             
            $descuentos = number_format($data['descuento'], 2, '.', '');         

            PDF::Cell(36, $height, "DESCUENTOS", 'T', 0, 'L', 1);
            PDF::Cell(8, $height, "S/.", 'T', 0, 'C', 1);
            PDF::Cell(17, $height, $descuentos, 'T', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(36, $height, "SUBTOTAL", '', 0, 'L', 1);
            PDF::Cell(8, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(17, $height, number_format($data['importe_pagar_sin_igv'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(36, $height, "OP.GRAVADA", '', 0, 'L', 1);
            PDF::Cell(8, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(17, $height, number_format($data['importe_pagar_sin_igv'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(36, $height, "OP.EXONERADA", '', 0, 'L', 1);
            PDF::Cell(8, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(17, $height, "0", '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::Cell(36, $height, "OP.GRATUITA", '', 0, 'L', 1);
            PDF::Cell(8, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(17, $height, "0", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(36, $height, "IGV " . (env('IGV')*100) . "%", '', 0, 'L', 1);
            PDF::Cell(8, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(17, $height, number_format($data['importe_pagar_igv'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::Cell(36, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(8, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell(17, $height, number_format($data['oferta'], 2, '.', ''), '', 0, 'R', 1);
            PDF::Ln();
            PDF::Cell(60, $height,'', 'T', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "FECHA DE EMISIÓN: ". $data['documento_fecha'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "FECHA DE TRASLADO: ". $data['documento_fecha'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "DIRECCIÓN PARTIDA: ". $data['emisor_agencia_direccion'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "DIRECCIÓN LLEGADA: ". $data['destino_direccion'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "REMITENTE: ". $data['emisor_razon_social'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "DESTINATARIO: ". $data['emisor_razon_social'], '',$align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Ln();

            $tree = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
            $filename = $data['emisor_ruc'].'-09-'.$data['emisor_numero_documento_electronico'] . '.pdf';
            $estructura = storage_path('app/'.$tree);
            if(!@mkdir($estructura, 0777, true)) {
                if (file_exists($estructura . "/" . $filename)) { @unlink($estructura . "/" . $filename); }
            }
            // $qr = $this->getQR($estructura, $data);
            // QRcode::png($qr['value'], $qr['img'], 'L', 4, 2);
            // PDF::Image($qr['img'], '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);
            PDF::SetFont('times', '', $font_size_regular);
            // PDF::MultiCell($width, $height, "Representación impresa de ".$data['emisor_tipo_documento_electronico'].". Puede descargarlo y/o consultarlo desde www.enlacesbus.com.pe/see", $border, $align_center, 1, 0, $x, $y);
            // PDF::Ln();
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
            
            $output = $estructura . "/" . $filename;
            PDF::Output($output, 'F');
            PDF::reset();
            $url_documento_pdf = $tree . "/" . $filename;
        endif;
        return $url_documento_pdf;
    }

    public function escribirXMLFactura($encargo_id) {
        $data = Encargo::buscarFactura($encargo_id);
        $document = ['xml'=> '', 'cdr'=> '', 'cdr_descripcion'=> '', 'nombre_archivo'=> '','error'=> ''];
        if ($data) :
            // IMPORTANTE: La factura electrónica deberá tener información de los por lo menos uno de siguientes 
            // campos definidos como opcionales: 18. Total valor de venta – operaciones gravadas, 
            // 19. Total valor de venta – operaciones inafectas o 20. Total valor de vento - operaciones exoneradas

            $cliente = (new Client())
                ->setTipoDoc('6')
                ->setNumDoc($data['adquiriente_ruc'])
                ->setRznSocial($data['adquiriente_razon_social'])
                ->setAddress((new Address())
                    ->setDireccion($data['adquiriente_direccion']));

            $invoice = new Invoice();
            $invoice
                ->setUblVersion('2.1')
                // ->setFecVencimiento(new \DateTime())
                ->setTipoDoc('01') // factura
                ->setSerie($data['emisor_serie_documento_electronico'])
                ->setCorrelativo($data['emisor_correlativo_documento_electronico'])
                ->setFechaEmision(new \DateTime($data['documento_fecha'].' '.$data['documento_hora']))
                ->setFormaPago(new FormaPagoContado())
                ->setTipoMoneda('PEN')
                ->setCompany(Util::getCompany())
                ->setClient($cliente);

            if($data['descuento'] > 0){
                $invoice->setDescuentos([
                    (new Charge())
                        ->setCodTipo('02') // Catalog. 53
                        ->setFactor(1)
                        ->setMontoBase(number_format($data['descuento'], 2, '.', ''))
                        ->setMonto(number_format($data['descuento'], 2, '.', ''))
                        ->setFactor(1)
                ]);
            }

            $gravadas = $data['monto_gravado'] - $data['descuento'];
            $invoice
                ->setMtoOperGravadas(number_format($gravadas, 2, '.', ''))
                // ->setMtoOperExoneradas(number_format($data['monto_exonerado'], 2, '.', ''))
                ->setMtoIGV(number_format(( ($gravadas) * (1 + env('IGV')) ) - $gravadas, 2, '.', '') )
                ->setTotalImpuestos(number_format(( ($gravadas) * (1 + env('IGV')) ) - $gravadas, 2, '.', '') )
                ->setValorVenta(number_format(($gravadas), 2, '.', ''))
                ->setSubTotal(number_format(($gravadas) * (1 + env('IGV')), 2, '.', ''))
                ->setMtoImpVenta(number_format(($gravadas) * (1 + env('IGV')), 2, '.', ''))
                ;

            if($data['subtotal'] > env('DETRACCION')){
                $invoice->setTipoOperacion('1001') // Catalogo 51: Operación Sujeta a Detracción Factura, Boletas
                ->setDetraccion(
                    (new Detraction())
                        ->setCodBienDetraccion($data['detraccion_codigo']) // catalog. 54 : Bien inmueble gravado con IGV
                        // Deposito en cuenta
                        ->setCodMedioPago($data['detraccion_medio_pago']) // catalog. 59 : Depósito en cuenta
                        ->setCtaBanco($data['detraccion_cta_banco'])
                        ->setPercent($data['detraccion_porcentaje']) // tasa según catalog. 54
                        ->setMount(number_format($data['detraccion_monto'], 2, '.', ''))
                        ->setValueRef(1) // valor de S/.1
                );
            } else {
                $invoice->setTipoOperacion('0101'); // Catálogo 51: Venta interna Factura, Boletas
            }
            
            // Detalles
            if (!empty($data['detalle'])) {
                $items_gravado = [];
                $items_exonerado = [];
                $items_inafecto = [];
                $items_gravado_gratuito = [];
                $items_inafecto_gratuito = [];
                
                foreach($data['detalle'] as $item):
                    // gravado
                    if ($item['tipo_afectacion'] == 10) {
                        $items_gravado[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ') // servicio
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/.100 no exite
                            ->setMtoValorVenta(number_format($item['valor_venta'], 2, '.', '')) // S/.200
                            ->setMtoBaseIgv(number_format($item['valor_base_igv'], 2, '.', '')) // S/.200
                            ->setPorcentajeIgv(number_format($item['porcentaje_igv'], 2, '.', '')) // 18
                            ->setIgv(number_format($item['igv_venta'], 2, '.', '')) // 36
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                            ->setFactorIcbper(null)
                            ->setTotalImpuestos(number_format($item['igv_venta'], 2, '.', '')) // 36
                            ->setMtoPrecioUnitario(number_format($item['precio_unitario'], 2, '.', '')) // 118
                        ;
                    }
                    // exonerado
                    if ($item['tipo_afectacion'] == 20) {
                        $items_exonerado[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ') // servicio
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 50
                            ->setMtoValorVenta($item['valor_venta']) // S/. 100
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 100
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                            ->setIgv( $item['igv_venta']) // S/. 0
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                            ->setTotalImpuestos($item['igv_venta']) // S/.0
                            ->setMtoPrecioUnitario($item['precio_unitario']) // S/. 50
                        ;
                    }
                    // inafecto
                    if ($item['tipo_afectacion'] == 30) {
                        $items_inafecto[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ')
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 100
                            ->setMtoValorVenta($item['valor_venta']) // S/. 200
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 200
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                            ->setIgv( $item['igv_venta']) // S/. 0
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                            ->setTotalImpuestos($item['igv_venta']) // S/.0
                            ->setMtoPrecioUnitario($item['precio_unitario']) // S/.100
                        ;
                    }
                    
                    // gravado gratuito 
                    if ($item['tipo_afectacion'] == 13) {
                        $items_gravado_gratuito[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ')
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 1
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 0
                            ->setMtoValorGratuito($item['valor_gratuito'])
                            ->setMtoValorVenta($item['valor_venta']) // S/. 100
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 100
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 18
                            ->setIgv($item['igv_venta']) // S/. 18
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog 07: Gravado - Retiro,
                            ->setTotalImpuestos($item['igv_venta']) // 18
                            ->setMtoPrecioUnitario($item['precio_unitario']) // 0
                        ;
                    }

                    // inafecto gratuito
                    if ($item['tipo_afectacion'] == 32) {
                        $items_inafecto_gratuito[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ')
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 0
                            ->setMtoValorGratuito($item['valor_gratuito']) //  S/.100
                            ->setMtoValorVenta($item['valor_venta']) // S/. 200
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 200
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                            ->setIgv($item['igv_venta']) // S/. 0
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog 07: Inafecto - Retiro,
                            ->setTotalImpuestos($item['igv_venta']) // S/. 0
                            ->setMtoPrecioUnitario($item['precio_unitario']) // S/. 0
                        ;
                    }
                endforeach;
            }
            
            list($enteros, $decimales) = explode('.', $data['oferta']);
            $formatter_es = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
            $letras = $formatter_es->format($enteros);
            
            $legends[] = (new Legend())
            ->setCode('1000')
            ->setValue(mb_strtoupper($letras) . ' CON ' . $decimales.'/100 SOLES');

            if($data['subtotal'] > env('DETRACCION')) {
                $legends[] = (new Legend())
                    ->setCode('2006')
                    ->setValue('Operación sujeta a detracción');
            }

            // (new Legend())
            //     ->setCode('1002')
            //     ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE')

            // $items = array_merge($items_gravado, $items_exonerado, $items_inafecto, $items_gravado_gratuito, $items_inafecto_gratuito); 
            $items = $items_gravado;
            $invoice->setDetails($items)->setLegends($legends); 

            list($year, $month, $day) = explode('-', $data['emisor_fecha_documento_electronico']); // yyyy-mm-dd
            $folder = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
           
            $util = Util::getInstance();
            $see = $util->getSee(SunatEndpoints::FE_BETA);
            
            // Si solo desea enviar un XML ya generado utilice esta función
            // $res = $see->sendXml(get_class($invoice), $invoice->getName(), file_get_contents($ruta_XML));
            // $res = $see->sendXmlFile(file_get_contents($path . '/' . $documento_xml));
            $res = $see->send($invoice);
            $document['nombre_archivo'] = $invoice->getName();
            $document['xml'] = $util->writeXml($folder, $invoice, $see->getFactory()->getLastXml());            
            // $res = $see->sendXmlFile($contents);
            if (!$res->isSuccess()) {
                $document['error'] = 'SUNAT no ha podido recibir facura en XML<br>'. $util->getErrorResponse($res->getError());
                
            } else {
                $cdr = (array) $res->getCdrResponse();
                $cdr_descripcion = "";
                foreach($cdr as  $value) {
                    if (gettype($value) == 'string') {
                        $cdr_descripcion .= $value . '|';
                    }
                    if (gettype($value) == 'array') {
                        $notes = implode(',', $value);
                        $cdr_descripcion .= $notes . '|';
                    }
                }
                $document['cdr'] = $util->writeCdr($folder, $invoice, $res->getCdrZip());
                $document['cdr_descripcion'] = $cdr_descripcion;
            }
        else:
            $document['error'] = 'No se ha encontrado en la base de datos.';
        endif;
        return $document;
    }

    public function escribirXMLBoleta($encargo_id) {
        $data = Encargo::buscarBoleta($encargo_id);
        $document = ['xml'=> '', 'cdr'=> '', 'cdr_descripcion'=> '', 'nombre_archivo'=> '','error'=> ''];
        if ($data) :
            // IMPORTANTE: La factura electrónica deberá tener información de los por lo menos uno de siguientes 
            // campos definidos como opcionales: 18. Total valor de venta – operaciones gravadas, 
            // 19. Total valor de venta – operaciones inafectas o 20. Total valor de vento - operaciones exoneradas

            if (strlen($data['adquiriente_ruc_dni_ce']) === 11) {
                $adquirienteCatalogo6 = '6'; // 6:RUC, 1:DNI, 4:Carnet de extranjería, 0:NN
            } else if (strlen($data['adquiriente_ruc_dni_ce']) === 8) {
                $adquirienteCatalogo6 = '1'; // 6:RUC, 1:DNI, 4:Carnet de extranjería, 0:NN
            } else {
                $adquirienteCatalogo6 = '0'; // 6:RUC, 1:DNI, 4:Carnet de extranjería, 0:NN
            }

            $cliente = (new Client())
                ->setTipoDoc($adquirienteCatalogo6)
                ->setNumDoc($data['adquiriente_ruc_dni_ce'])
                ->setRznSocial($data['adquiriente_razon_social'])
                ->setAddress((new Address())
                    ->setDireccion($data['adquiriente_direccion']));
            
            $invoice = new Invoice();
            $invoice
                ->setUblVersion('2.1')
                // ->setFecVencimiento(new \DateTime())
                ->setTipoDoc('03') // boleta
                ->setSerie($data['emisor_serie_documento_electronico'])
                ->setCorrelativo($data['emisor_correlativo_documento_electronico'])
                ->setFechaEmision(new \DateTime($data['documento_fecha'].' '.$data['documento_hora']))
                ->setFormaPago(new FormaPagoContado())
                ->setTipoMoneda('PEN')
                ->setCompany(Util::getCompany())
                ->setClient($cliente);

            if($data['descuento'] > 0){
                $invoice->setDescuentos([
                    (new Charge())
                        ->setCodTipo('02') // Catalog. 53
                        ->setFactor(1)
                        ->setMontoBase(number_format($data['descuento'], 2, '.', ''))
                        ->setMonto(number_format($data['descuento'], 2, '.', ''))
                        ->setFactor(1)
                ]);
            }

            $gravadas = $data['monto_gravado'] - $data['descuento'];
            $invoice
                ->setMtoOperGravadas(number_format($gravadas, 2, '.', ''))
                // ->setMtoOperExoneradas(number_format($data['monto_exonerado'], 2, '.', ''))
                ->setMtoIGV(number_format(( ($gravadas) * (1 + env('IGV')) ) - $gravadas, 2, '.', '') )
                ->setTotalImpuestos(number_format(( ($gravadas) * (1 + env('IGV')) ) - $gravadas, 2, '.', '') )
                ->setValorVenta(number_format(($gravadas), 2, '.', ''))
                ->setSubTotal(number_format(($gravadas) * (1 + env('IGV')), 2, '.', ''))
                ->setMtoImpVenta(number_format(($gravadas) * (1 + env('IGV')), 2, '.', ''))
                ;

            if($data['subtotal'] > env('DETRACCION')){
                $invoice->setTipoOperacion('1001') // Catalogo 51: Operación Sujeta a Detracción Factura, Boletas
                ->setDetraccion(
                    (new Detraction())
                        ->setCodBienDetraccion($data['detraccion_codigo']) // catalog. 54 : Bien inmueble gravado con IGV
                        // Deposito en cuenta
                        ->setCodMedioPago($data['detraccion_medio_pago']) // catalog. 59 : Depósito en cuenta
                        ->setCtaBanco($data['detraccion_cta_banco'])
                        ->setPercent($data['detraccion_porcentaje']) // tasa según catalog. 54
                        ->setMount(number_format($data['detraccion_monto'], 2, '.', ''))
                        ->setValueRef(1) // valor de S/.1
                );
            } else {
                $invoice->setTipoOperacion('0101'); // Catalogo 51: Venta interna Factura, Boletas
            }

             // Detalles
            if (!empty($data['detalle'])) {
                $items_gravado = [];
                $items_exonerado = [];
                $items_inafecto = [];
                $items_gravado_gratuito = [];
                $items_inafecto_gratuito = [];
                
                foreach($data['detalle'] as $item):
                    // gravado
                    if ($item['tipo_afectacion'] == 10) {
                        $items_gravado[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ') // servicio
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/.100 no exite
                            ->setMtoValorVenta(number_format($item['valor_venta'], 2, '.', '')) // S/.200
                            ->setMtoBaseIgv(number_format($item['valor_base_igv'], 2, '.', '')) // S/.200
                            ->setPorcentajeIgv(number_format($item['porcentaje_igv'], 2, '.', '')) // 18
                            ->setIgv(number_format($item['igv_venta'], 2, '.', '')) // 36
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                            ->setFactorIcbper(null)
                            ->setTotalImpuestos(number_format($item['igv_venta'], 2, '.', '')) // 36
                            ->setMtoPrecioUnitario(number_format($item['precio_unitario'], 2, '.', '')) // 118
                        ;
                    }
                    // exonerado
                    if ($item['tipo_afectacion'] == 20) {
                        $items_exonerado[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ') // servicio
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 50
                            ->setMtoValorVenta($item['valor_venta']) // S/. 100
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 100
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                            ->setIgv( $item['igv_venta']) // S/. 0
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                            ->setTotalImpuestos($item['igv_venta']) // S/.0
                            ->setMtoPrecioUnitario($item['precio_unitario']) // S/. 50
                        ;
                    }
                    // inafecto
                    if ($item['tipo_afectacion'] == 30) {
                        $items_inafecto[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ')
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 100
                            ->setMtoValorVenta($item['valor_venta']) // S/. 200
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 200
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                            ->setIgv( $item['igv_venta']) // S/. 0
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                            ->setTotalImpuestos($item['igv_venta']) // S/.0
                            ->setMtoPrecioUnitario($item['precio_unitario']) // S/.100
                        ;
                    }
                    
                    // gravado gratuito 
                    if ($item['tipo_afectacion'] == 13) {
                        $items_gravado_gratuito[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ')
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 1
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 0
                            ->setMtoValorGratuito($item['valor_gratuito'])
                            ->setMtoValorVenta($item['valor_venta']) // S/. 100
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 100
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 18
                            ->setIgv($item['igv_venta']) // S/. 18
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog 07: Gravado - Retiro,
                            ->setTotalImpuestos($item['igv_venta']) // 18
                            ->setMtoPrecioUnitario($item['precio_unitario']) // 0
                        ;
                    }

                    // inafecto gratuito
                    if ($item['tipo_afectacion'] == 32) {
                        $items_inafecto_gratuito[] = (new SaleDetail())
                            ->setCodProducto($item['codigo_producto'])
                            ->setUnidad('ZZ')
                            ->setDescripcion($item['descripcion'])
                            ->setCantidad($item['cantidad_item']) // 2
                            ->setMtoValorUnitario($item['valor_unitario']) // S/. 0
                            ->setMtoValorGratuito($item['valor_gratuito']) //  S/.100
                            ->setMtoValorVenta($item['valor_venta']) // S/. 200
                            ->setMtoBaseIgv($item['valor_base_igv']) // S/. 200
                            ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                            ->setIgv($item['igv_venta']) // S/. 0
                            ->setTipAfeIgv($item['tipo_afectacion']) // Catalog 07: Inafecto - Retiro,
                            ->setTotalImpuestos($item['igv_venta']) // S/. 0
                            ->setMtoPrecioUnitario($item['precio_unitario']) // S/. 0
                        ;
                    }
                endforeach;
            }

            list($enteros, $decimales) = explode('.', $data['oferta']);
            $formatter_es = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
            $letras = $formatter_es->format($enteros);

            $legends[] = (new Legend())
            ->setCode('1000')
            ->setValue(mb_strtoupper($letras) . ' CON ' . $decimales.'/100 SOLES');

            if($data['subtotal'] > env('DETRACCION')) {
                $legends[] = (new Legend())
                    ->setCode('2006')
                    ->setValue('Operación sujeta a detracción');
            }

            // (new Legend())
            //     ->setCode('1002')
            //     ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE')

            // $items = array_merge($items_gravado, $items_exonerado, $items_inafecto, $items_gravado_gratuito, $items_inafecto_gratuito); 
            $items = $items_gravado;
            $invoice->setDetails($items)->setLegends($legends); 

            list($year, $month, $day) = explode('-', $data['emisor_fecha_documento_electronico']); // yyyy-mm-dd
            $folder = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
           
            $util = Util::getInstance();
            $see = $util->getSee(SunatEndpoints::FE_BETA);

            // Si solo desea enviar un XML ya generado utilice esta función
            // $res = $see->sendXml(get_class($invoice), $invoice->getName(), file_get_contents($ruta_XML));
            // $res = $see->sendXmlFile(file_get_contents($path . '/' . $documento_xml));
            $res = $see->send($invoice);
            $document['nombre_archivo'] = $invoice->getName();
            $document['xml'] = $util->writeXml($folder, $invoice, $see->getFactory()->getLastXml());            
            // $res = $see->sendXmlFile($contents);
            if (!$res->isSuccess()) {
                $document['error'] = 'SUNAT no ha podido recibir facura en XML<br>'. $util->getErrorResponse($res->getError());
                
            } else {
                $cdr = (array) $res->getCdrResponse();
                $cdr_descripcion = "";
                foreach($cdr as  $value) {
                    if (gettype($value) == 'string') {
                        $cdr_descripcion .= $value . '|';
                    }
                    if (gettype($value) == 'array') {
                        $notes = implode(',', $value);
                        $cdr_descripcion .= $notes . '|';
                    }
                }
                $document['cdr'] = $util->writeCdr($folder, $invoice, $res->getCdrZip());
                $document['cdr_descripcion'] = $cdr_descripcion;

            }

        else :
            $document['error'] = 'No se ha encontrado en la base de datos.';
        endif;
        return $document;
    }

    public function getQR($estructura, $data) {
        $hash = "";
        $dniruc = (isset($data['adquiriente_ruc']))? $data['adquiriente_ruc'] : $data['adquiriente_ruc_dni_ce'];
        $doc = (isset($data['adquiriente_ruc']))? '01': '03';
        $value = $data['emisor_ruc'].'|'.$doc.'|'.$data['emisor_numero_documento_electronico'].'|'.$data['importe_pagar_igv'].'|'.$data['oferta'].'|'.$data['emisor_fecha_documento_electronico'].'|1|'.$dniruc.'|'.$hash;
        $img = $estructura.'/qrcode.png';
        return ['value' => $value, 'img' => $img];
    }
}
