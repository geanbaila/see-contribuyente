<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Business\Sede;
use App\Business\Carga;
use App\Business\Agencia;
use App\Business\Documento;
use App\Business\Encargo;
use App\Business\Adquiriente;
use MongoDB\BSON\ObjectId;
use \Greenter\Model\Response\BillResult;
use \Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use \Greenter\Model\Sale\Invoice;
use \Greenter\Model\Sale\SaleDetail;
use \Greenter\Model\Sale\Legend;
use \Greenter\Ws\Services\SunatEndpoints;
use \Greenter\XMLSecLibs\Sunat\SignedXml;
use \Greenter\See;
use \Greenter\Model\Client\Client;
use \Greenter\Model\Company\Address;

use \PHPQRCode\QRcode;
use PDF;
use App\Http\Controllers\Util;


class SaleController extends Controller
{
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
                $total = 0;
                $monto_gravado = 0;
                $monto_exonerado = 0;
                $monto_inafecto = 0;

                foreach($prg as $item) {
                    if ($item['descripcion'] !== "--") {
                        $carga = Carga::find($item['descripcion']);
                        if ($carga) {
                            if ($carga->tipo_afectaciones->codigo == env('AFECTACION_GRAVADO')) {
                                array_push($gravado, [
                                    'carga' => $carga->id,
                                    'codigo_producto' => $carga->id,
                                    'descripcion' => $carga->nombre,
                                    'cantidad' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    
                                    'valor_unitario' => $item['valor_unitario'],
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => env('IGV') * 100,
                                    'igv_venta' => ($item['cantidad'] * $item['valor_unitario'] * env('IGV') * 100) / 100,
                                    'tipo_afectacion' => $carga->tipo_afectaciones->codigo,
                                    'precio_unitario' => $item['valor_unitario'] * (1 + env('IGV')),
                                ]);
                                $monto_gravado += $item['cantidad'] * $item['valor_unitario'];
                                $total += $item['cantidad'] * $item['valor_unitario'] * (1 + env('IGV'));
                            }
                            
                            if ($carga->tipo_afectaciones->codigo == env('AFECTACION_EXONERADO')) {
                                array_push($exonerado, [
                                    'carga' => $carga->id,
                                    'codigo_producto' => $carga->id,
                                    'descripcion' => $carga->nombre,
                                    'cantidad' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    
                                    'valor_unitario' => $item['valor_unitario'],
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => 0,
                                    'igv_venta' => 0,
                                    'tipo_afectacion' => $carga->tipo_afectaciones->codigo,
                                    'precio_unitario' => $item['valor_unitario'],
                                ]);
                                $monto_exonerado += $item['cantidad'] * $item['valor_unitario'];
                                $total += $item['cantidad'] * $item['valor_unitario'] * (1 + env('IGV'));
                            }
                            
                            if ($carga->tipo_afectaciones->codigo == env('AFECTACION_INAFECTO')) {
                                array_push($inafecto, [
                                    'carga' => $carga->id,
                                    'codigo_producto' => $carga->id,
                                    'descripcion' => $carga->nombre,
                                    'cantidad' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    
                                    'valor_unitario' => $item['valor_unitario'],
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => 0,
                                    'igv_venta' => 0,
                                    'tipo_afectacion' => $carga->tipo_afectaciones->codigo,
                                    'precio_unitario' => $item['valor_unitario'],
                                ]);
                                $monto_inafecto += $item['cantidad'] * $item['valor_unitario'];
                                $total += $item['cantidad'] * $item['valor_unitario'] * (1 + env('IGV'));
                            }

                            if ($carga->tipo_afectaciones->codigo == env('AFECTACION_GRAVADO_GRATUITO')) {
                                array_push($gravado_gratuito, [
                                    'carga' => $carga->id,
                                    'codigo_producto' => $carga->id,
                                    'descripcion' => $carga->nombre,
                                    'cantidad' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    
                                    'valor_unitario' => 0,
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_gratuito' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => env('IGV') * 100,
                                    'igv_venta' => ($item['cantidad'] * $item['valor_unitario'] * env('IGV') * 100) / 100,
                                    'tipo_afectacion' => $carga->tipo_afectaciones->codigo,
                                    'precio_unitario' => 0,
                                ]);
                                $total += $item['cantidad'] * 0;
                            }

                            if ($carga->tipo_afectaciones->codigo == env('AFECTACION_INAFECTO_GRATUITO')) {
                                array_push($inafecto_gratuito, [
                                    'carga' => $carga->id,
                                    'codigo_producto' => $carga->id,
                                    'descripcion' => $carga->nombre,
                                    'cantidad' => $item['cantidad'],
                                    'peso' => $item['peso'],
                                    
                                    'valor_unitario' => 0,
                                    'valor_venta' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_gratuito' => $item['cantidad'] * $item['valor_unitario'],
                                    'valor_base_igv' => $item['cantidad'] * $item['valor_unitario'],
                                    'porcentaje_igv' => 0,
                                    'igv_venta' => 0,
                                    'tipo_afectacion' => $carga->tipo_afectaciones->codigo,
                                    'precio_unitario' => 0,
                                ]);
                                $total += $item['cantidad'] * 0;
                            }
                        }
                    }
                }

                return [
                    'detalle_gravado' => $gravado,
                    'detalle_exonerado' => $exonerado,
                    'detalle_inafecto' => $inafecto,
                    'detalle_gravado_gratuito' => $gravado_gratuito,
                    'detalle_inafecto_gratuito' => $inafecto_gratuito,
                    
                    'monto_gravado' => number_format($monto_gravado, 2, '.', ''),
                    'monto_exonerado' => number_format($monto_exonerado, 2, '.', ''),
                    'monto_inafecto' => number_format($monto_inafecto, 2, '.', ''),
                    

                    'importe_pagar_con_igv' => number_format($total, 2, '.', ''),
                    'importe_pagar_sin_igv' => number_format($total / (1 + env('IGV')) , 2, '.', ''),
                    'importe_pagar_igv' => number_format($total - ($total / (1 + env('IGV'))), 2, '.', '')
                ];
            };

            if (!$encargo = $var($data['encargo'])) {
                return \response()->json([
                    'result' => [
                        'status' => 'fails', 
                        'message' => 'No ha ingresado el detalle de la encomienda.'
                    ]
                ]);
            }
            
            $documento = Documento::find($data['documento']);

            // registrar o actualizar datos del adquiriente
            if ($documento->alias === 'B') {
                // boletas
                $insert_adquiriente = [
                    'documento' => $data['doc_envia'],
                    'razon_social' => $data['nombre_envia'],
                    'nombre_comercial' => $data['nombre_comercial_envia'],
                    'direccion' => $data['direccion_envia'],
                ];
            } else if ($documento->alias === 'F') {
                // facturas
                $insert_adquiriente = [
                    'documento' => $data['doc_envia'],
                    'razon_social' => $data['nombre_envia'],
                    'nombre_comercial' => $data['nombre_comercial_envia'],
                    'direccion' => $data['direccion_envia'],
                ];
            } else if ($documento->alias === 'G') {
                // guía de remisión
                $insert_adquiriente = [
                    'documento' => $data['doc_recibe'],
                    'razon_social' => $data['nombre_recibe'],
                    'nombre_comercial' => $data['nombre_comercial_recibe'],
                    'direccion' => $data['direccion_recibe'],
                ];
            } else {
                return \response()->json([
                    'result' => [
                        'status' => 'fails', 
                        'message' => 'Ha ocurrido un error interno (100) en el sistema, contacta con el proveedor.'
                    ]
                ]);
            }

            if (strlen($data['encargo_id']) > 0) {
                $adquiriente = $data['adquiriente'];
            } else {
                $adquiriente = (Adquiriente::create($insert_adquiriente));
                $adquiriente = $adquiriente->id;
            }
            
            // registrar o actualizar el encargo
            $agencia_id = $data['agencia_origen']; // agencia que está en sesión. hacer luego
            $insert_encargo = array_merge([
                'doc_envia' => $data['doc_envia'],
                'nombre_envia' => $data['nombre_envia'],
                'celular_envia' => $data['celular_envia'],
                'email_envia' => $data['email_envia'],
                'fecha_envia' => $data['fecha_envia'],

                'doc_recibe' => $data['doc_recibe'],
                'nombre_recibe' => $data['nombre_recibe'],
                'celular_recibe' => $data['celular_recibe'],
                'email_recibe' => $data['email_recibe'],
                'fecha_recibe' => $data['fecha_recibe'],

                // 'origen' => $data['origen'],
                // 'destino' => new ObjectId($data['destino']),
                'agencia_origen' => new ObjectId($data['agencia_origen']),
                'agencia_destino' => new ObjectId($data['agencia_destino']),
                
                'agencia' => new ObjectId($agencia_id),
                'adquiriente' => new ObjectId($adquiriente),
                'medio_pago' => $data['medio_pago'],
                'documento' => new ObjectId($data['documento']),
                'documento_serie' => $data['documento_serie'],
                'documento_correlativo' => $data['documento_correlativo'],
                'documento_fecha' => date('Y-m-d'),
                'documento_hora' => date('H:i:s'),

                'subtotal' => number_format($data['subtotal'], 2, '.', ''),
                'oferta' => number_format($data['importe_pagar_con_descuento'], 2, '.', ''),
            ], $encargo);
            
            $encargo = null;
            if (strlen($data['encargo_id']) > 0) {
                //controlar duplicidad de registros
                $encargo_id = $data['encargo_id']; 
                $fecha_envia = $data['fecha_envia'];
                $documento_correlativo = $data['documento_correlativo'];
                
                // bloquear actualizar los registros
                $object_id = new ObjectId($data['encargo_id']);
                // $encargo = Encargo::where('_id', $object_id)->update($insert_encargo, ['upsert' => true]);
                // if (!$encargo) {
                //     return \response()->json(['result' => ['status' => 'fails', 'message' => 'No hubo ningún cambio.']]);
                // }
            } else {
                $encargo = Encargo::create($insert_encargo);
                $encargo_id = $encargo['id'];
                $object_id = new ObjectId($encargo_id);
                $fecha_envia = date('d-m-Y');
                $documento_correlativo = sprintf("%0".env('ZEROFILL', 8)."d", Encargo::getNextSequence($encargo_id, $data['documento_serie']));
                $encargo = Encargo::where('_id', $object_id)->update(['fecha_envia' => $fecha_envia, 'documento_correlativo' => $documento_correlativo]);
            }

            // registrar o actualizar el PDF
            if($documento->alias === 'B') {
                $url_documento_pdf = $this->escribirBoleta($encargo_id);
                $url_documento = $this->escribirXMLBoleta($encargo_id);
            } else if($documento->alias === 'F') {
                $url_documento_pdf = ''; // = $this->escribirFactura($encargo_id);
                $url_documento = $this->escribirXMLFactura($encargo_id);
                
            } else if ($documento->alias === 'G') {
                $url_documento_pdf = $this->escribirGuiaRemision($encargo_id);
                $url_documento = $this->escribirXMLGuiaRemision($encargo_id);
            } else {
                // no escribir PDF
                return \response()->json(['result' => ['status' => 'fails', 'message' => 'Ha ocurrido un error(200) en el sistema, contacta con el proveedor.']]);
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
            Encargo::where('_id', $object_id)->update([
                'url_documento_pdf' => $url_documento_pdf,
                'url_documento_xml' => $url_documento['xml'],
                'url_documento_cdr' => $url_documento['cdr'],
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
                    'adquiriente' => $adquiriente, 
                    'documento_correlativo' => $documento_correlativo,
                    'fecha_envia' => $fecha_envia,
                    'cdr_descripcion' => $cdr_descripcion .' <img src="'. asset('assets/media/check-circle.svg'). '" width="24" />',
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

    public function show() {
        $sede = Sede::all();
        $carga = Carga::orderBy('nombre','asc')->get();
        $agencia_origen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        return view('sale.show')->with([
            'agencia_origen' => $agencia_origen,
            'sede' => $sede,
            'documento' => $documento,
            'carga' => $carga
        ]);
    }

    public function edit($encargo_id) {
        $sede = Sede::all();
        $carga = Carga::all();
        $agencia_origen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        $encargo = Encargo::find($encargo_id);
        return view('sale.edit')->with([ 'agencia_origen' => $agencia_origen, 'sede' => $sede, 'documento' => $documento, 'carga' => $carga, 'encargo' => $encargo ]);
    }

    public function list() {
        $encargo = Encargo::all()->sortBy('oferta');
        return view('sale.list')->with([ 'encargo' => $encargo ]);
    }

    public function escribirBoleta($encargo_id) {
        $data = Encargo::buscarBoleta($encargo_id);
        if ($data) {
            $textodniruc = (strlen($data['adquiriente_ruc_dni_ce']) === 11) ? 'RUC: ': 'DNI/CE: ';
            $textodniruc .= $data['adquiriente_ruc_dni_ce'];
            
            PDF::SetTitle($data['titulo_documento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            $width = 50;
            
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
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisor_fecha_documento_electronico_pe'], 'B', 0, 'L', 0);
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
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $font_size_regular);
            PDF::Cell(24, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(14, $height, "CANTIDAD", '', 0, 'L', 1);
            PDF::Cell(12, $height, "PRECIO", '', 0, 'R', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            // $importeTotal = 0.00;
            PDF::SetFont('times', '', $font_size_regular);
            foreach($data['encargo_detalle'] as $encargo):
                // $importeTotal += $encargo['precioUnitarioConIGV'];
                PDF::MultiCell(24, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(14, $height, $encargo['cantidad'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(12, $height, $encargo['precio_unitario_con_igv'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, $encargo['precio_con_igv'], '', $align_center, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;
            
            
            $descuentos = number_format($data['oferta'] - $data['subtotal'], 2, '.', '');
         
            PDF::Cell(35, $height, "DESCUENTOS", 'T', 0, 'L', 1);
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

            PDF::Cell(35, $height, "IGV 18%", '', 0, 'L', 1);
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

            $year = substr($data['emisor_fecha_documento_electronico'], 0, 4);
            $month = substr($data['emisor_fecha_documento_electronico'], 5, 2);
            $tree = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
            $filename = $data['emisor_ruc'].'-03-'.$data['emisor_numero_documento_electronico'] . '.pdf';
            $estructura = base_path('public/'.$tree);
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
            
            $output = public_path($tree . "/" . $filename);
            PDF::Output($output, 'F');
            PDF::reset();
            return $tree . "/" . $filename;
        }
    }

    public function escribirFactura($encargo_id) {
        $data = Encargo::buscarFactura($encargo_id);
        if ($data) {
            $textodniruc = 'RUC: '.$data['adquiriente_ruc'];
            
            PDF::SetTitle($data['titulo_documento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            $width = 50;
            
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
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisor_fecha_documento_electronico_pe'], 'B', 0, 'L', 0);
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
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $font_size_regular);
            PDF::Cell(24, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(14, $height, "CANTIDAD", '', 0, 'L', 1);
            PDF::Cell(12, $height, "PRECIO", '', 0, 'R', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            // $importeTotal = 0.00;
            PDF::SetFont('times', '', $font_size_regular);
            foreach($data['encargo_detalle'] as $encargo):
                PDF::MultiCell(24, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(14, $height, $encargo['cantidad'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(12, $height, $encargo['precio_unitario_con_igv'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, $encargo['precio_con_igv'], '', $align_center, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;
            
            // $total_operacion_gravada = number_format($data['importe_pagar_sin_igv'], 2, '.', '');
            // $oferta_sin_igv = number_format($data['oferta']/1.18, 2, '.', '');
            // $porcentaje_descontado_operacion_gravada = number_format(($total_operacion_gravada-$oferta_sin_igv)/$total_operacion_gravada, 2, '.', '');
            // $descontado_operacion_gravada = number_format($porcentaje_descontado_operacion_gravada*$total_operacion_gravada, 2, '.', '');
                
            $descuentos = number_format($data['subtotal']-$data['oferta'], 2, '.', '');
         
            PDF::Cell(35, $height, "DESCUENTOS", 'T', 0, 'L', 1);
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

            PDF::Cell(35, $height, "IGV 18%", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, number_format($data['importe_pagar_igv'], 2, '.',''), '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::Cell(35, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell(20, $height, $data['oferta'], '', 0, 'R', 1);
            PDF::Ln();

            // PDF::SetFont('times', '', $font_size_grande);
            // PDF::Cell($width, $height, "hash SUNAT:", 'T', 1, 'L', 1);
            // PDF::Cell($width, $height, "1c7a92ae351d4e21ebdfb897508f59d6", '', 1, 'L', 1);

            $year = substr($data['emisor_fecha_documento_electronico'], 0, 4);
            $month = substr($data['emisor_fecha_documento_electronico'], 5, 2);
            $tree = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
            $filename = $data['emisor_ruc'].'-01-'.$data['emisor_numero_documento_electronico'] . '.pdf';
            $estructura = base_path('public/'.$tree);
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
            
            $output = public_path($tree . "/" . $filename);
            PDF::Output($output, 'F');
            PDF::reset();
            return $tree . "/" . $filename;
        }
    }

    public function escribirGuiaRemision($encargo_id) {
        $data = Encargo::buscarGuiaRemision($encargo_id);
        if ($data) {
            PDF::SetTitle($data['titulo_documento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            $width = 50;
            
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
            $img = base_path('public/assets/media/icons/sis/bus.png');
            PDF::Image($img, '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "OPERADOR: ", 'T', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisor_fecha_documento_electronico_pe'], 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: " . $data['emisor_hora_documento_electronico'], 'B', 1, 'R', 0);
            // -------------
            
            $dniruc = (strlen($data['adquirienteRUC']) === 8) ? 'DNI/CE' : 'RUC';
            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['adquiriente_razon_social'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, "$dniruc: " . $data['adquirienteRUC'], '', $align_left, 1, 0, $x, $y);
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
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $font_size_regular);
            PDF::Cell(24, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(14, $height, "CANTIDAD", '', 0, 'L', 1);
            PDF::Cell(12, $height, "PRECIO", '', 0, 'R', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            $importeTotal = 0.00;
            PDF::SetFont('times', '', $font_size_regular);
            foreach($data['encargo_detalle'] as $encargo):
                $importeTotal += $encargo['total'];
                PDF::MultiCell(24, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(14, $height, $encargo['cantidad'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(12, $height, $encargo['precio'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, $encargo['total'], '', $align_center, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;
            
            $importePagar = number_format($data['importePagar'], 2, '.', '');
            $igv = number_format($importePagar * env('IGV', 0.18), 2, '.', '');
            $subtotal = number_format($importePagar - $igv, 2, '.', '');
            $descuentos = number_format($importePagar - $importeTotal, 2, '.', '');
         
            PDF::Cell(35, $height, "DESCUENTOS", 'T', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", 'T', 0, 'C', 1);
            PDF::Cell(20, $height, $descuentos, 'T', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "SUBTOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, $subtotal, '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.GRAVADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, $subtotal, '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "OP.EXONERADA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0", '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::Cell(35, $height, "OP.GRATUITA", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, "0", '', 0, 'R', 1);
            PDF::Ln();

            PDF::Cell(35, $height, "IGV 18%", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::Cell(20, $height, $igv, '', 0, 'R', 1);
            PDF::Ln();
            
            PDF::SetFont('times', 'B', $font_size_grande);
            PDF::Cell(35, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $font_size_gigante);
            PDF::Cell(20, $height, $importePagar, '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $font_size_regular);
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
            
            $output = public_path($tree . "/" . $filename);
            PDF::Output($output, 'F');
            PDF::reset();
            return $tree . "/" . $filename;
        
        }
    }

    public function escribirXMLFactura($encargo_id) {
        $data = Encargo::buscarFactura($encargo_id);
        $document = ['xml'=> '', 'cdr'=> '', 'cdr_descripcion'=> '', 'error'=> ''];
        if ($data) {
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
                ->setTipoOperacion('0101')
                ->setTipoDoc('01')
                ->setSerie($data['emisor_serie_documento_electronico']) // F001
                ->setCorrelativo($data['emisor_correlativo_documento_electronico']) // 123
                ->setFechaEmision(new \DateTime($data['documento_fecha'].' '.$data['documento_hora']))
                ->setFormaPago(new FormaPagoContado())
                ->setTipoMoneda('PEN')
                ->setCompany(Util::getCompany())
                ->setClient($cliente)
                ->setMtoOperGravadas($data['monto_gravado'])
                ->setMtoOperExoneradas($data['monto_exonerado'])
                ->setMtoIGV($data['importe_pagar_igv'])
                ->setTotalImpuestos($data['importe_pagar_igv'])
                ->setValorVenta($data['importe_pagar_sin_igv'])
                ->setSubTotal($data['importe_pagar_con_igv'])
                ->setMtoImpVenta($data['importe_pagar_con_igv'])
            ;

            // Detalle gravado
            if (!empty($data['detalle_gravado'])) {
                foreach($data['detalle_gravado'] as $item):
                    $items[] = (new SaleDetail())
                        ->setCodProducto($item['codigo_producto'])
                        ->setUnidad('ZZ') // servicio
                        ->setDescripcion($item['descripcion'])
                        ->setCantidad($item['cantidad']) // 2
                        ->setMtoValorUnitario($item['valor_unitario']) // S/.100 no exite
                        ->setMtoValorVenta($item['valor_venta']) // S/.200
                        ->setMtoBaseIgv($item['valor_base_igv']) // S/.200
                        ->setPorcentajeIgv($item['porcentaje_igv']) // 18
                        ->setIgv($item['igv_venta']) // 36
                        ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                        ->setTotalImpuestos($item['igv_venta']) // 36
                        ->setMtoPrecioUnitario($item['precio_unitario']) // 118
                    ;
                endforeach;
            }

            // Detalle Exonerado
            if(!empty($data['detalle_exoneado'])) {
                foreach($data['detalle_exoneado'] as $item):
                    $items[] = (new SaleDetail())
                        ->setCodProducto($item['codigo_producto'])
                        ->setUnidad('ZZ') // servicio
                        ->setDescripcion($item['descripcion'])
                        ->setCantidad($item['cantidad']) // 2
                        ->setMtoValorUnitario($item['valor_unitario']) // S/. 50
                        ->setMtoValorVenta($item['valor_venta']) // S/. 100
                        ->setMtoBaseIgv($item['valor_base_igv']) // S/. 100
                        ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                        ->setIgv( $item['igv_venta']) // S/. 0
                        ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                        ->setTotalImpuestos($item['igv_venta']) // S/.0
                        ->setMtoPrecioUnitario($item['precio_unitario']) // S/. 50
                    ;
                endforeach;
            }
            
            // Inafecto
            if (!empty($data['detalle_inafecto'])) {
                foreach($data['detalle_inafecto'] as $item):
                    $items[] = (new SaleDetail())
                        ->setCodProducto($item['codigo_producto'])
                        ->setUnidad('ZZ')
                        ->setDescripcion($item['descripcion'])
                        ->setCantidad($item['cantidad']) // 2
                        ->setMtoValorUnitario($item['valor_unitario']) // S/. 100
                        ->setMtoValorVenta($item['valor_venta']) // S/. 200
                        ->setMtoBaseIgv($item['valor_base_igv']) // S/. 200
                        ->setPorcentajeIgv($item['porcentaje_igv']) // S/. 0
                        ->setIgv( $item['igv_venta']) // S/. 0
                        ->setTipAfeIgv($item['tipo_afectacion']) // Catalog: 07
                        ->setTotalImpuestos($item['igv_venta']) // S/.0
                        ->setMtoPrecioUnitario($item['precio_unitario']) // S/.100
                    ;
                endforeach;
            }
            
            // Gravado gratuito
            if (!empty($data['detalle_gravado_gratuito'])) {
                foreach($data['detalle_gravado_gratuito'] as $item):
                    $items[] = (new SaleDetail())
                        ->setCodProducto($item['codigo_producto'])
                        ->setUnidad('ZZ')
                        ->setDescripcion($item['descripcion'])
                        ->setCantidad($item['cantidad']) // 1
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
                endforeach;
            }

            // Inafecto gratuito
            if (!empty($data['detalle_inafecto_gratuito'])) {
                foreach($data['detalle_inafecto_gratuito'] as $item):
                    $items[] = (new SaleDetail())
                        ->setCodProducto($item['codigo_producto'])
                        ->setUnidad('ZZ')
                        ->setDescripcion($item['descripcion'])
                        ->setCantidad($item['cantidad']) // 2
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
                endforeach;
            }
            
            list($enteros, $decimales) = explode('.', $data['importe_pagar_con_igv']);
            $formatter_es = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
            $letras = $formatter_es->format($enteros);
                
            $invoice->setDetails($items)
            ->setLegends([
                (new Legend())
                    ->setCode('1000')
                    ->setValue(mb_strtoupper($letras) . ' CON ' . $decimales.'/100 SOLES'),
                (new Legend())
                    ->setCode('1002')
                    ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE')
            ]);         
            
            $year = substr($data['emisor_fecha_documento_electronico'], 0, 4);
            $month = substr($data['emisor_fecha_documento_electronico'], 5, 2);
            $folder = 'comprobantes/' . $year . '/' . $month . '/' . $encargo_id;
            // $path = base_path('public/' . $folder);
            // $documento_sin_firmar = 'documento_sin_firmar.xml';
            
            // @mkdir($path, 0777, true);
            // @unlink($path . '/' . $documento_sin_firmar);
            // $documento_xml = $data['emisor_ruc'] . '-01-' . $data['emisor_numero_documento_electronico'] . '.xml';
            // $documento_zip = $data['emisor_ruc'] . '-01-' . $data['emisor_numero_documento_electronico'] . '.zip';
            // $documento_cdr = 'R-' . $data['emisor_ruc'] . '-01-' . $data['emisor_numero_documento_electronico'] . '.zip';
            
            $util = Util::getInstance();
            $see = $util->getSee(SunatEndpoints::FE_BETA);
            // $see = $util->getSee(SunatEndpoints::FE_HOMOLOGACION);
            
            
            // Si solo desea enviar un XML ya generado utilice esta función
            // $res = $see->sendXml(get_class($invoice), $invoice->getName(), file_get_contents($ruta_XML));
            // $res = $see->sendXmlFile(file_get_contents($path . '/' . $documento_xml));
            $res = $see->send($invoice);
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
                $document['cdr_descripcion'] = $cdr_descripcion;
                $document['cdr'] = $util->writeCdr($folder, $invoice, $res->getCdrZip());
            }
        } else {
            $document['error'] = 'No se ha encontrado en la base de datos.';
        }
        return $document;
    }

    public function escribirXMLBoleta($encargo_id) {
        $data = Encargo::buscarBoleta($encargo_id);
        if ($data) {
            // IMPORTANTE: La factura electrónica deberá tener información de los por lo menos uno de siguientes campos definidos como opcionales: 18. Total valor de venta – operaciones gravadas, 19. Total valor de venta – operaciones inafectas o 20. Total valor de vento - operaciones exoneradas
            // variables
            $X509Certificate = 'MIIF9TCCBN2gAwIBAgIGAK0oRTg/MA0GCSqGSIb3DQEBCwUAMFkxCzAJBgNVBAYTAlRSMUowSAYDVQQDDEFNYWxpIE3DvGjDvHIgRWxla3Ryb25payBTZXJ0aWZpa2EgSGl6bWV0IFNhxJ9sYXnEsWPEsXPEsSAtIFRlc3QgMTAeFw0wOTEwMjAxMTM3MTJaFw0xNDEwMTkxMTM3MTJaMIGgMRowGAYDVQQLDBFHZW5lbCBNw7xkw7xybMO8azEUMBIGA1UEBRMLMTAwMDAwMDAwMDIxbDBqBgNVBAMMY0F5ZMSxbiBHcm91cCAtIFR1cml6bSDEsHRoYWxhdCDEsGhyYWNhdCBUZWtzdGlsIMSwbsWfYWF0IFBhemFyiMwtPnC2DRjdsyGv3bxwRZr9wXMRrMNwRjyFe9JPA7bSscEgaXwzDUG5FCvfS/PNT+XCce+VECAx6Q3R1ZRSA49fYz6tDB4Ia5HVBXZODmrCs26XisHF6kuS5N/yGg8E7VC1BRr/SmxXeLTdjQYAfo7lxCz4dT6wP5TOiBvF+lyWW1bi9nbliXyb/e5HjCp4k/ra9LTskjbY/Ukl5O8G9JEAViZkjvxDX7T0yVRHgMGiioIKVMwU6Lrtln607BNurLwED0OeoZ4wBgkBiB5vXofreXrfN2pHZ2=';
            $tipoMoneda = 'PEN';
            $n = 3;
            $firma = 'GreenterSign'; // Identificador de la firma
            if (strlen($data['adquiriente_ruc_dni_ce']) === 11) {
                $adquirienteCatalogo6 = '6'; // 6:RUC, 1:DNI, 4:Carnet de extranjería, 0:NN
            } else if (strlen($data['adquiriente_ruc_dni_ce']) === 8) {
                $adquirienteCatalogo6 = '1'; // 6:RUC, 1:DNI, 4:Carnet de extranjería, 0:NN
            } else {
                $adquirienteCatalogo6 = '0'; // 6:RUC, 1:DNI, 4:Carnet de extranjería, 0:NN
            }

            $boleta = '03';
            
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->xmlVersion = '1.0';
            $dom->formatOutput = true;

            $root = $dom->createElement('Invoice');
            $root->setAttributeNode(new \DOMAttr('xmlns','urn:oasis:names:specification:ubl:schema:xsd:Invoice-2'));
            $root->setAttributeNode(new \DOMAttr('xmlns:cac','urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'));
            $root->setAttributeNode(new \DOMAttr('xmlns:cbc','urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2'));
            $root->setAttributeNode(new \DOMAttr('xmlns:ccts','urn:un:unece:uncefact:documentation:2'));
            $root->setAttributeNode(new \DOMAttr('xmlns:ds','http://www.w3.org/2000/09/xmldsig#'));
            $root->setAttributeNode(new \DOMAttr('xmlns:ext','urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2'));
            $root->setAttributeNode(new \DOMAttr('xmlns:qdt','urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2'));
            $root->setAttributeNode(new \DOMAttr('xmlns:udt','urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2'));
            $root->setAttributeNode(new \DOMAttr('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance'));

            //begin 1
                $nodeUBLExtensions = $dom->createElement('ext:UBLExtensions');
                $root->appendChild($nodeUBLExtensions);

                    $nodeUBLExtensions1 = $dom->createElement('ext:UBLExtension');
                    $nodeUBLExtensions->appendChild($nodeUBLExtensions1);

                        $nodeExtensionContent = $dom->createElement('ext:ExtensionContent');
                        $nodeUBLExtensions1->appendChild($nodeExtensionContent);

                            $nodeSignature = $dom->createElement('ds:Signature');
                            $nodeSignature->setAttributeNode(new \DOMAttr('Id','SignatureSP'));
                            $nodeExtensionContent->appendChild($nodeSignature);

                                $nodeSignedInfo = $dom->createElement('ds:SignedInfo');
                                $nodeSignature->appendChild($nodeSignedInfo);

                                    $nodeCanonicalizationMethod = $dom->createElement('ds:CanonicalizationMethod');
                                    $nodeCanonicalizationMethod->setAttributeNode(new \DOMAttr('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315'));
                                    $nodeSignedInfo->appendChild($nodeCanonicalizationMethod);

                                    $nodeSignatureMethod = $dom->createElement('ds:SignatureMethod');
                                    $nodeSignatureMethod->setAttributeNode(new \DOMAttr('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1'));
                                    $nodeSignedInfo->appendChild($nodeSignatureMethod);

                                    $nodeReference = $dom->createElement('ds:Reference');
                                    $nodeReference->setAttributeNode(new \DOMAttr('URI', ''));
                                    $nodeSignedInfo->appendChild($nodeReference);

                                        $nodeTransforms = $dom->createElement('ds:Transforms');
                                        $nodeReference->appendChild($nodeTransforms);

                                            $nodeTransforms1 = $dom->createElement('ds:Transform');
                                            $nodeTransforms1->setAttributeNode(new \DOMAttr('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature'));
                                            $nodeTransforms->appendChild($nodeTransforms1);

                                        $nodeDigestMethod = $dom->createElement('ds:DigestMethod');
                                        $nodeDigestMethod->setAttributeNode(new \DOMAttr('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1'));
                                        $nodeReference->appendChild($nodeDigestMethod);

                                        $nodeDigestValue = $dom->createElement('ds:DigestValue', '+pruib33lOapq6GSw58GgQLR8VGIGqANloj4EqB1cb4=');
                                        $nodeReference->appendChild($nodeDigestValue);

                                $nodeSignatureValue = $dom->createElement('ds:SignatureValue', 'Oatv5xMfFInuGqiX9SoLDTy2yuLf0tTlMFkWtkdw1z/Ss6kiDz+vIgZhgKfIaxp+JbVy57GT52f10VLMLatdwPVRbrWmz1/NIy5CWp1xWMaM6fC/9SXV0O1Lqopk0UeX2I2yuf05QhmVfjgUu6GnS3m6o6zM9J36iDvMVZyj7vbJTwI8SfWjTSNqxXlqPQ==');
                                $nodeSignature->appendChild($nodeSignatureValue);

                                $nodeKeyInfo = $dom->createElement('ds:KeyInfo');
                                $nodeSignature->appendChild($nodeKeyInfo);

                                    $nodeX509Data = $dom->createElement('ds:X509Data');
                                    $nodeKeyInfo->appendChild($nodeX509Data);

                                        $nodeX509Certificate = $dom->createElement('ds:X509Certificate', $X509Certificate);
                                        $nodeX509Data->appendChild($nodeX509Certificate);
            // end 1

            // begin 2 - Versión del UB
                $nodeUBLVersionID = $dom->createElement('cbc:UBLVersionID', '2.1');
                $root->appendChild($nodeUBLVersionID);
            // end 2

            // begin 3 - Versión de la estructura del documento
                $nodeCustomizationID = $dom->createElement('cbc:CustomizationID', '2.0');
                $root->appendChild($nodeCustomizationID);
            // end 3

            // begin 4 - codificicación de tipo de operación **
                //  catálogo 51,
                // 0101: Venta interna Factura, Boletas
                // 0102: Venta Interna – Anticipos Factura, Boletas
                // 0103: Venta interna - Itinerante Factura, Boletas
                // 0110: Venta Interna - Sustenta Traslado de Mercadería - Remitente Factura, Boletas
                // 0111: Venta Interna - Sustenta Traslado de Mercadería - Transportista Factura, Boletas
                // 0112: Venta Interna - Sustenta Gastos Deducibles Persona Natural Factura
                // 0120: Venta Interna - Sujeta al IVAP Factura, Boletas
                // 0121: Venta Interna - Sujeta al FISE Todos
                // 0122: Venta Interna - Sujeta a otros impuestos Todos
                // 0130: Venta Interna - Realizadas al Estado Factura, Boletas
                // 0200: Exportación de Bienes Factura, Boletas
                // 0201: Exportación de Servicios – Prestación servicios realizados Factura, Boletas íntegramente en el país
                // 0202: Exportación de Servicios – Prestación de servicios de hospedaje No Domiciliado Factura, Boletas
                // 0203: Exportación de Servicios – Transporte de navieras Factura, Boletas
                // 0204: Exportación de Servicios – Servicios a naves y aeronaves de bandera extranjera Factura, Boletas
                // 0205: Exportación de Servicios - Servicios que conformen un Paquete Turístico Factura, Boletas
                // 0206: Exportación de Servicios – Servicios complementarios al transporte de carga Factura, Boletas
                // 0207: Exportación de Servicios – Suministro de energía eléctrica a favor de sujetos domiciliados en ZED Factura, Boletas
                // 0208: Exportación de Servicios – Prestación servicios realizados parcialmente en el extranjero Factura, Boletas
                // 0301: Operaciones con Carta de porte aéreo (emitidas en el ámbito nacional) Factura, Boletas
                // 0302: Operaciones de Transporte ferroviario de pasajeros Factura, Boletas
                // 0303: Operaciones de Pago de regalía petrolera Factura, Boletas
                // 1001: Operación Sujeta a Detracción Factura, Boletas
                // 1002: Operación Sujeta a Detracción- Recursos Hidrobiológicos Factura, Boletas
                
                $nodeProfileID = $dom->createElement('cbc:ProfileID', '0101'); // catálogo 51, 0101: Venta interna
                $nodeProfileID->setAttributeNode(new \DOMAttr('schemeName', 'SUNAT:Identificador de Tipo de Operación')); // Identificador de Código de tipo de operación
                $nodeProfileID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                $nodeProfileID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17')); // catálogo 51
                $root->appendChild($nodeProfileID);
            // end 4

            // begin 5 - Numeración, conformada por serie y número correlativo
                $nodeID = $dom->createElement('cbc:ID', $data['emisor_numero_documento_electronico']);
                $root->appendChild($nodeID);
            // end 5

            // begin 6 - fecha de emisión
                $facturaFechaEmision = $data['emisor_fecha_documento_electronico']; //yyyy-mm-dd
                $nodeIssueDate = $dom->createElement('cbc:IssueDate', $facturaFechaEmision);
                $root->appendChild($nodeIssueDate);
            // end 6

            // begin 7 - hora de emisión
                $facturaHoraEmision = $data['emisor_hora_documento_electronico']; //hh:ii:ss
                $nodeIssueTime = $dom->createElement('cbc:IssueTime', $facturaHoraEmision);
                $root->appendChild($nodeIssueTime);
            // end 7

            // begin 8 - fecha de vencimiento ** opcional    
                // $facturaFechaVence = 'yyyy-mm-dd';
                // $nodeDueDate = $dom->createElement('cbc:DueDate', $facturaFechaVence);
                // $root->appendChild($nodeDueDate);
            // end 8

            // begin 9 - tipo de documento (Factura) **
                // 01: Factura **
                // 03: Boleta de venta **
                // 06: Carta de porte aéreo
                // 07: Nota de crédito **
                // 08: Nota de débito
                // 09: Guia de remisión remitente **
                // 12: Ticket de maquina registradora
                // 13: Documento emitido por bancos, instituciones financieras, crediticias y de seguros que se encuentren
                // ba:jo el control de la superintendencia de banca y seguros
                // 14: Recibo de servicios públicos
                // 15: Boletos emitidos por el servicio de transporte terrestre regular urbano de pasajeros y el ferroviario
                // pú:blico de pasajeros prestado en vía férrea local.
                // 16: Boleto de viaje emitido por las empresas de transporte público interprovincial de pasajeros
                // 18: Documentos emitidos por las afp
                // 20: Comprobante de retencion
                // 21: Conocimiento de embarque por el servicio de transporte de carga marítima
                // 24: Certificado de pago de regalías emitidas por perupetro s.a.
                // 31: Guía de remisión transportista **
                // 37: Documentos que emitan los concesionarios del servicio de revisiones técnicas
                // 40: Comprobante de percepción
                // 41: Comprobante de percepción – venta interna (físico - formato impreso)
                // 43: Boleto de compañias de aviación transporte aéreo no regular
                // 45: Documentos emitidos por centros educativos y culturales, universidades, asociaciones y fundaciones.
                // 56: Comprobante de pago seae
                // 71: Guia de remisión remitente complementaria
                // 72: Guia de remisión transportista complementaria
                
                $nodeInvoiceTypeCode = $dom->createElement('cbc:InvoiceTypeCode', $boleta); // 03:Boleta
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listID', '0101')); // revisado en otras boletas y facturas
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Identificador de Tipo de Documento')); // Código de tipo de documento autorizado para efectos tributarios
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01'));
                $root->appendChild($nodeInvoiceTypeCode);
            // end 9

            // begin 10 - leyendas, catálogo 52 ++ OK ++
                //$payable = number_format($data['importe_pagar_con_igv']*(1-$porcentaje_descontado_operacion_gravada), 2, '.', '');
                $payable = number_format($data['oferta'], 2, '.', '');
                $importe_pagar_con_igv = explode('.', $payable);
                $formatterES = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
                $valorNumericoLetras = $formatterES->format($importe_pagar_con_igv[0]);
                $nodeNote = $dom->createElement('cbc:Note', htmlspecialchars('<![CDATA['.mb_strtoupper($valorNumericoLetras) .' CON '.$importe_pagar_con_igv[1].'/100 SOLES]]>', ENT_QUOTES));
                $nodeNote->setAttributeNode(new \DOMAttr('languageLocaleID', '1000'));
                $root->appendChild($nodeNote);

                if('DETRACCION'!='DETRACCION'):
                    $nodeNote = $dom->createElement('cbc:Note', 'Operación sujeta a detracción');
                    $nodeNote->setAttributeNode(new \DOMAttr('languageLocaleID', '2006'));
                    $root->appendChild($nodeNote);
                endif;

                if('ERP'!='ERP'):
                    $codigoInternoSoftware = 'este código viene de mi ERP';
                    $nodeNote1 = $dom->createElement('cbc:Note', $codigoInternoSoftware);
                    $nodeNote1->setAttributeNode(new \DOMAttr('languageLocaleID', '3000'));
                    $root->appendChild($nodeNote1);
                endif;
            // end 10
            
            // begin 11 - Tipo de moneda en la cual se emite la factura electrónica ++ OK ++
                $nodeDocumentCurrencyCode = $dom->createElement('cbc:DocumentCurrencyCode', $tipoMoneda);
                $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listID', 'ISO 4217 Alpha'));
                $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listName', 'Currency'));
                $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listAgencyName', 'United Nations Economic Commission for Europe'));
                $root->appendChild($nodeDocumentCurrencyCode);
            // end 11

            // Signature
                $nodeSignature = $dom->createElement('cac:Signature');
                $root->appendChild($nodeSignature);
                
                $nodeID = $dom->createElement('cbc:ID', $firma);
                $nodeSignature->appendChild($nodeID);
                
                $nodeSignatoryParty = $dom->createElement('cac:SignatoryParty');
                $nodeSignature->appendChild($nodeSignatoryParty);
                
                    $nodePartyIdentification = $dom->createElement('cac:PartyIdentification');
                    $nodeSignatoryParty->appendChild($nodePartyIdentification);
                    
                        $nodeID = $dom->createElement('cbc:ID', $data['emisor_ruc']);
                        $nodeID->setAttributeNode(new \DOMAttr('schemeID', '6'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Documento de Identidad'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                        $nodePartyIdentification->appendChild($nodeID);
                    
                    $nodePartyName = $dom->createElement('cac:PartyName');
                    $nodeSignatoryParty->appendChild($nodePartyName);
                    
                        $nodeName = $dom->createElement('cbc:Name', htmlspecialchars('<![CDATA['.$data['emisor_razon_social'].']]>', ENT_QUOTES));
                        $nodePartyName->appendChild($nodeName);

                $nodeDigitalSignatureAttachment = $dom->createElement('cac:DigitalSignatureAttachment');
                $nodeSignature->appendChild($nodeDigitalSignatureAttachment);
                
                    $nodeExternalReference = $dom->createElement('cac:ExternalReference');
                    $nodeDigitalSignatureAttachment->appendChild($nodeExternalReference);
                    
                    $nodeURI = $dom->createElement('cbc:URI','#SignatureSP');
                    $nodeDigitalSignatureAttachment->appendChild($nodeURI);
            // end Signature
            
            // begin 12 y 13 omitidos
                // qwe: requiero una explicación
                // 12 Tipo y número de la guía de remisión relacionada con la operación que se factura
                // <cac:DespatchDocumentReference>
                // <cbc:ID>031-002020</cbc:ID>
                // <cbc:DocumentTypeCode
                // listAgencyName="PE:SUNAT"
                // listName="SUNAT:Identificador de guía relacionada"
                // listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">09</cbc:DocumentTypeCode>
                // </cac:DespatchDocumentReference>
                // 13 Tipo y número de otro documento y código relacionado con la operación que se factura
                // <cac:AdditionalDocumentReference>
                // <cbc:ID>024099</cbc:ID>
                // <cbc:DocumentTypeCode
                // listAgencyName="PE:SUNAT"
                // listName="SUNAT: Identificador de documento relacionado"
                // listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12">99</cbc:DocumentTypeCode>
                // </cac:AdditionalDocumentReference>
            // end 12 y 13

            // begin 14-17 (emisor)
                $nodeAccountingSupplierParty = $dom->createElement('cac:AccountingSupplierParty');
                $root->appendChild($nodeAccountingSupplierParty);

                $nodeParty = $dom->createElement('cac:Party'); // OK
                $nodeAccountingSupplierParty->appendChild($nodeParty);
                
                    $nodePartyIdentification = $dom->createElement('cac:PartyIdentification'); // OK
                    $nodeParty->appendChild($nodePartyIdentification);
                    
                        $nodeID = $dom->createElement('cbc:ID', $data['emisor_ruc']); // OK
                        $nodeID->setAttributeNode(new \DOMAttr('schemeID', '6'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Documento de Identidad'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                        $nodePartyIdentification->appendChild($nodeID);

                if(strlen($data['emisor_nombre_comercial'])>0):
                    // Nombre Comercial del emisor
                    $nodePartyName = $dom->createElement('cac:PartyName'); // OK
                    $nodeParty->appendChild($nodePartyName);

                        $nodeName = $dom->createElement('cbc:Name', '<![CDATA['. $data['emisor_nombre_comercial'] .']]>');
                        $nodePartyName->appendChild($nodeName);
                endif;

                /* SUNAT NO LO CONSIDERA VÁLIDO
                    // Apellidos y nombres, denominación o razón social del emisor
                    $nodePartyTaxScheme = $dom->createElement('cac:PartyTaxScheme'); // OK
                    $nodeParty->appendChild($nodePartyTaxScheme);

                        $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', htmlspecialchars('<![CDATA['.$data['emisor_razon_social'].']]>', ENT_QUOTES)); // OK
                        $nodePartyTaxScheme->appendChild($nodeRegistrationName);

                        // Tipo y Número de RUC del emisor
                        $nodeCompanyID = $dom->createElement('cbc:CompanyID', $data['emisor_ruc']); // OK
                        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeID','6'));
                        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeName','SUNAT:Identificador de Documento de Identidad'));
                        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeAgencyName','PE:SUNAT'));
                        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeURI','urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                        $nodePartyTaxScheme->appendChild($nodeCompanyID);

                        $nodeTaxScheme = $dom->createElement('cac:TaxScheme'); // OK
                        $nodePartyTaxScheme->appendChild($nodeTaxScheme);

                            $nodeID = $dom->createElement('cbc:ID', $data['emisor_ruc']); // OK
                            $nodeID->setAttributeNode(new \DOMAttr('schemeID', '6'));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'SUNAT:Identificador de Documento de Identidad'));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                            $nodeTaxScheme->appendChild($nodeID);
                */
                    $nodePartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
                    $nodeParty->appendChild($nodePartyLegalEntity);

                        $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', htmlspecialchars('<![CDATA['.$data['emisor_razon_social'].']]>', ENT_QUOTES));
                        $nodePartyLegalEntity->appendChild($nodeRegistrationName);

                        $nodeRegistrationAddress = $dom->createElement('cac:RegistrationAddress');
                        $nodePartyLegalEntity->appendChild($nodeRegistrationAddress);
                    
                            $nodeID = $dom->createElement('cbc:ID', $data['emisor_ubigeo']);
                            $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Ubigeos'));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:INEI'));
                            $nodeRegistrationAddress->appendChild($nodeID);
                            
                            // Código del domicilio fiscal o de local anexo del emisor.
                            $nodeID = $dom->createElement('cbc:AddressTypeCode', '0000'); // 0000 en caso no se tenga
                            $nodeID->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                            $nodeID->setAttributeNode(new \DOMAttr('listName', 'Establecimientos anexos'));
                            $nodeRegistrationAddress->appendChild($nodeID);
                            
                            $nodeCityName = $dom->createElement('cbc:CityName', '<![CDATA['. $data['emisor_direccion_departamento'] .']]>');
                            $nodeRegistrationAddress->appendChild($nodeCityName);
                            
                            $nodeCountrySubentity = $dom->createElement('cbc:CountrySubentity', '<![CDATA[' . $data['emisor_direccion_provincia'] .']]>');
                            $nodeRegistrationAddress->appendChild($nodeCountrySubentity);

                            $nodeDistrict = $dom->createElement('cbc:District', '<![CDATA['. $data['emisor_direccion_distrito'] .']]>');
                            $nodeRegistrationAddress->appendChild($nodeDistrict);
                            
                            $nodeAddressLine = $dom->createElement('cac:AddressLine');
                            $nodeRegistrationAddress->appendChild($nodeAddressLine);
                            
                            $nodeLine = $dom->createElement('cbc:Line', '<![CDATA['. $data['emisor_direccion'] .']]>');
                            $nodeAddressLine->appendChild($nodeLine);
                            
                            $nodeCountry = $dom->createElement('cac:Country');
                            $nodeRegistrationAddress->appendChild($nodeCountry);
                    
                                $nodeIdentificationCode = $dom->createElement('cbc:IdentificationCode', $data['emisor_direccion_pais']);
                                $nodeIdentificationCode->setAttributeNode(new \DOMAttr('listID', 'ISO 3166-1'));
                                $nodeIdentificationCode->setAttributeNode(new \DOMAttr('listAgencyName', 'United Nations Economic Commission for Europe'));
                                $nodeIdentificationCode->setAttributeNode(new \DOMAttr('listName', 'Country'));
                                $nodeCountry->appendChild($nodeIdentificationCode);
            // end 14-17

            // begin 18-19 (adquiriente)
                $nodeAccountingCustomerParty = $dom->createElement('cac:AccountingCustomerParty');
                $root->appendChild($nodeAccountingCustomerParty);

                    $nodeParty = $dom->createElement('cac:Party');
                    $nodeAccountingCustomerParty->appendChild($nodeParty);

                        // homologacion con otros xmls
                        $nodePartyIdentification = $dom->createElement('cac:PartyIdentification');
                        $nodeParty->appendChild($nodePartyIdentification);

                            $nodeID = $dom->createElement('cbc:ID');
                            $nodeID->setAttributeNode(new \DOMAttr('schemeID', $adquirienteCatalogo6));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Documento de Identidad'));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                            $nodePartyIdentification->appendChild($nodeID);

                        $nodePartyName = $dom->createElement('cac:PartyName');
                        $nodeParty->appendChild($nodePartyName);

                            $nodeName = $dom->createElement('cbc:Name', htmlspecialchars('<![CDATA['.$data['adquiriente_razon_social'].']]>', ENT_QUOTES));
                            $nodePartyName->appendChild($nodeName);

                        $nodePartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
                        $nodeParty->appendChild($nodePartyLegalEntity);

                            $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', htmlspecialchars('<![CDATA['.$data['adquiriente_razon_social'].']]>', ENT_QUOTES));
                            $nodePartyLegalEntity->appendChild($nodeRegistrationName);
                        
                            $nodeRegistrationAddress = $dom->createElement('cac:RegistrationAddress');
                            $nodePartyLegalEntity->appendChild($nodeRegistrationAddress);
                            
                                $nodeCityName = $dom->createElement('cbc:CityName', htmlspecialchars('<![CDATA['.$data['adquiriente_direccion_departamento'].']]>', ENT_QUOTES));
                                $nodeRegistrationAddress->appendChild($nodeCityName);
                                
                                $nodeCitySubdivisionName = $dom->createElement('cbc:CountrySubentity', htmlspecialchars('<![CDATA['.$data['adquiriente_direccion_provincia'].']]>', ENT_QUOTES));
                                $nodeRegistrationAddress->appendChild($nodeCitySubdivisionName);
                                
                                $nodeDistrict = $dom->createElement('cbc:District', htmlspecialchars('<![CDATA['.$data['adquiriente_direccion_distrito'].']]>', ENT_QUOTES));
                                $nodeRegistrationAddress->appendChild($nodeDistrict);
                            
                                $nodeAddressLine = $dom->createElement('cac:AddressLine');
                                $nodeRegistrationAddress->appendChild($nodeAddressLine);
                                
                                    $nodeLine = $dom->createElement('cbc:Line', htmlspecialchars('<![CDATA['.$data['adquiriente_direccion'].']]>', ENT_QUOTES));
                                    $nodeAddressLine->appendChild($nodeLine);
                        
                                $nodeCountry = $dom->createElement('cac:Country');
                                $nodeRegistrationAddress->appendChild($nodeCountry);
                
                                    $nodeIdentificationCode = $dom->createElement('cbc:IdentificationCode', $data['adquiriente_direccion_pais']);
                                    $nodeIdentificationCode->setAttributeNode(new \DOMAttr('listID', 'ISO 3166-1'));
                                    $nodeIdentificationCode->setAttributeNode(new \DOMAttr('listAgencyName', 'United Nations Economic Commission for Europe'));
                                    $nodeIdentificationCode->setAttributeNode(new \DOMAttr('listName', 'Country'));
                                    $nodeCountry->appendChild($nodeIdentificationCode);

                        /* SUNAT NO LO CONSIDERA VÁLIDO
                        $nodePartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
                        $nodeParty->appendChild($nodePartyTaxScheme);
                
                            // Apellidos y nombres, denominación o razón social del adquirente o usuario
                            $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', htmlspecialchars('<![CDATA['.$data['adquiriente_razon_social'].']]>', ENT_QUOTES));
                            $nodePartyTaxScheme->appendChild($nodeRegistrationName);

                            // Tipo y número de documento de identidad del adquirente o usuario
                            $nodeCompanyID = $dom->createElement('cbc:CompanyID', $data['adquiriente_ruc_dni_ce']);
                            $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeID', $adquirienteCatalogo6));
                            $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeName', 'SUNAT:Identificador de Documento de Identidad')); // Tipo de Documento de Identificación
                            $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                            $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                            $nodePartyTaxScheme->appendChild($nodeCompanyID);

                            $nodeTaxScheme = $dom->createElement('cac:TaxScheme');
                            $nodePartyTaxScheme->appendChild($nodeTaxScheme);

                                $nodeID = $dom->createElement('cbc:ID', $data['adquiriente_ruc_dni_ce']);
                                $nodeID->setAttributeNode(new \DOMAttr('schemeID', $adquirienteCatalogo6));
                                $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'SUNAT:Identificador de Documento de Identidad'));
                                $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                                $nodeID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                                $nodeTaxScheme->appendChild($nodeID);
                        */
            // end 18-19

            // begin 21 - Información de descuentos Globales **opcional ++ OK ++
                $total_operacion_gravada = number_format($data['importe_pagar_sin_igv'], 2, '.', '');
                $oferta_sin_igv = number_format($data['oferta']/1.18, 2, '.', '');
                $porcentaje_descontado_operacion_gravada = number_format(($total_operacion_gravada-$oferta_sin_igv)/$total_operacion_gravada, 2, '.', '');
                $descontado_operacion_gravada = number_format($porcentaje_descontado_operacion_gravada*$total_operacion_gravada, 2, '.', '');
                
                $nodeAllowanceCharge = $dom->CreateElement('cac:AllowanceCharge');
                $root->appendChild($nodeAllowanceCharge);

                $nodeChargeIndicator = $dom->CreateElement('cbc:ChargeIndicator','false'); // originalmente decía False en la documentación
                $nodeAllowanceCharge->appendChild($nodeChargeIndicator);

                $nodeAllowanceChargeReasonCode = $dom->CreateElement('cbc:AllowanceChargeReasonCode','00'); // catálogo 53, 00: OTROS DESCUENTOS | 00:Descuentos que afectan la base imponible del IGV, 01:Descuentos que no afectan la base imponible del IGV
                $nodeAllowanceCharge->appendChild($nodeAllowanceChargeReasonCode);
                
                $nodeMultiplierFactorNumeric = $dom->CreateElement('cbc:MultiplierFactorNumeric', $porcentaje_descontado_operacion_gravada);
                $nodeAllowanceCharge->appendChild($nodeMultiplierFactorNumeric);

                $nodeAmount = $dom->CreateElement('cbc:Amount', $descontado_operacion_gravada);
                $nodeAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeAllowanceCharge->appendChild($nodeAmount);

                $nodeBaseAmount = $dom->CreateElement('cbc:BaseAmount', $total_operacion_gravada);
                $nodeBaseAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeAllowanceCharge->appendChild($nodeBaseAmount);
            // end 21

            // begin 22-29 ++ OK ++
                $nodeTaxTotal = $dom->CreateElement('cac:TaxTotal');
                $root->appendChild($nodeTaxTotal);
 
                // Monto total de impuestos **opcional
                $gravada_descontada = number_format($total_operacion_gravada-$descontado_operacion_gravada, 2, '.', '');
                $igv_descuentado = number_format($data['importe_pagar_igv']*(1-$porcentaje_descontado_operacion_gravada) , 2, '.', '');
                // $igv_descuentado = number_format($gravada_descontada*0.18, 2, '.', '');

                $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', $igv_descuentado);
                $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeTaxTotal->appendChild($nodeTaxAmount);

                // Monto las operaciones gravadas **opcional 
                $nodeTaxSubtotal = $dom->CreateElement('cac:TaxSubtotal');
                $nodeTaxTotal->appendChild($nodeTaxSubtotal);

                    $nodeTaxableAmount = $dom->CreateElement('cbc:TaxableAmount', $gravada_descontada);
                    $nodeTaxableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxableAmount);

                    $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', $igv_descuentado);
                    $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxAmount);

                    $nodeTaxCategory = $dom->CreateElement('cac:TaxCategory');
                    $nodeTaxSubtotal->appendChild($nodeTaxCategory);

                        $nodeID = $dom->CreateElement('cbc:ID', 'S'); // catalogo 5, S:IGV
                        $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Tax Category Identifier'));
                        $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'United Nations Economic Commission for Europe'));
                        $nodeTaxCategory->appendChild($nodeID);

                        $nodeTaxScheme = $dom->CreateElement('cac:TaxScheme');
                        $nodeTaxCategory->appendChild($nodeTaxScheme);

                            $nodeID = $dom->CreateElement('cbc:ID','1000'); // catálogo 5, 1000: Igv impuesto general a las ventas
                            $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                            $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyID', '6')); //qwe. indicado así en la guía de factura pero no especifica para boletas
                            $nodeTaxScheme->appendChild($nodeID);

                            $nodeName = $dom->CreateElement('cbc:Name','IGV'); // Nombre del Tributo
                            $nodeTaxScheme->appendChild($nodeName);

                            $nodeTaxTypeCode = $dom->CreateElement('cbc:TaxTypeCode','VAT'); // UN/ECE 5153- Duty or tax or fee type name code
                            $nodeTaxScheme->appendChild($nodeTaxTypeCode);

                // Monto las operaciones Exonerada **opcional , no aplica
                /*
                    $nodeTaxSubtotal = $dom->CreateElement('cac:TaxSubtotal');
                    $nodeTaxTotal->appendChild($nodeTaxSubtotal);
                    
                    $nodeTaxableAmount = $dom->CreateElement('cbc:TaxableAmount', '320.00');
                    $nodeTaxableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxableAmount);
                    
                    $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', '0.00');
                    $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxAmount);

                    $nodeTaxCategory = $dom->CreateElement('cac:TaxCategory');
                    $nodeTaxSubtotal->appendChild($nodeTaxCategory);
                    
                    $nodeID = $dom->CreateElement('cbc:ID', 'E');
                    $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                    $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Tax Category Identifier'));
                    $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'United Nations Economic Commission for Europe'));
                    $nodeTaxCategory->appendChild($nodeID);
                    
                    $nodeTaxScheme = $dom->CreateElement('cac:TaxScheme');
                    $nodeTaxCategory->appendChild($nodeTaxScheme);
                    
                    $nodeID = $dom->CreateElement('cbc:ID','9997'); // catálogo 5, 9997:Exonerado
                    $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                    $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyID', '6'));
                    $nodeTaxScheme->appendChild($nodeID);
                    
                    $nodeEXONERADO = $dom->CreateElement('cbc:Name','EXONERADO'); // Nombre del Tributo
                    $nodeTaxScheme->appendChild($nodeEXONERADO);
                    
                    $nodeTaxTypeCode = $dom->CreateElement('cbc:TaxTypeCode','VAT'); // UN/ECE 5153- Duty or tax or fee type name code
                    $nodeTaxScheme->appendChild($nodeTaxTypeCode);
                */
            
            // end 22-29
            
            // begin 30-34 **opcional ++ OK ++
                
                $nodeLegalMonetaryTotal = $dom->CreateElement('cac:LegalMonetaryTotal'); 
                $root->appendChild($nodeLegalMonetaryTotal);

                // Total valor de venta **opcional -- OK
                $nodeLineExtensionAmount = $dom->CreateElement('cbc:LineExtensionAmount', number_format($data['importe_pagar_sin_igv'], 2, '.', '')); 
                $nodeLineExtensionAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeLineExtensionAmount);

                // Total precio de venta (impuestos solamente) **opcional -- OK
                $nodeTaxExclusiveAmount = $dom->CreateElement('cbc:TaxExclusiveAmount', number_format($data['importe_pagar_igv'], 2, '.', '')); 
                $nodeTaxExclusiveAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeTaxExclusiveAmount);
                
                // Total precio de venta (incluye impuestos) **opcional -- OK
                $nodeTaxInclusiveAmount = $dom->CreateElement('cbc:TaxInclusiveAmount', $payable); 
                $nodeTaxInclusiveAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeTaxInclusiveAmount);

                // // Monto total de descuentos del comprobante **opcional ++ OK ++
                $nodeAllowanceTotalAmount = $dom->CreateElement('cbc:AllowanceTotalAmount', $gravada_descontada); 
                $nodeAllowanceTotalAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeAllowanceTotalAmount);

                // // Monto total de otros cargos del comprobante **opcional
                // $nodeChargeTotalAmount = $dom->CreateElement('cbc:ChargeTotalAmount', '320.00'); 
                // $nodeChargeTotalAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                // $nodeLegalMonetaryTotal->appendChild($nodeChargeTotalAmount);

                // // Monto total de otros cargos del comprobante **opcional
                // $nodePrepaidAmount = $dom->CreateElement('cbc:PrepaidAmount', '100.00'); 
                // $nodePrepaidAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                // $nodeLegalMonetaryTotal->appendChild($nodePrepaidAmount);

                // Importe total de la venta, cesión en uso o del servicio prestado  **opcional -- OK
                $nodePayableAmount = $dom->CreateElement('cbc:PayableAmount', $payable); 
                $nodePayableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodePayableAmount);
            // end 30-34

            // begin 35 - 38 ++ OK ++
                foreach($data['encargo_detalle'] as $i => $item):
                    $nodeInvoiceLine = $dom->CreateElement('cac:InvoiceLine'); 
                    $root->appendChild($nodeInvoiceLine);

                    // Número de orden del Ítem
                    $nodeID = $dom->CreateElement('cbc:ID', $i+1); 
                    $nodeInvoiceLine->appendChild($nodeID);

                    // Cantidad y Unidad de medida por ítem
                    $cantidadVenta = round($item['cantidad'], 2);
                    $nodeInvoicedQuantity = $dom->CreateElement('cbc:InvoicedQuantity', $cantidadVenta); // cantidad de eso
                    $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCode', 'ZZ')); // catálogo 3, NIU: UNIDAD (BIENES), ZZ: pieza o parte de algo
                    $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCodeListID', 'UN/ECE rec 20'));
                    $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCodeListAgencyName', 'United Nations Economic Commission for Europe'));
                    $nodeInvoiceLine->appendChild($nodeInvoicedQuantity);

                    // Valor de venta del ítem
                    $valorVenta = number_format($item['precio_sin_igv'], 2, '.', '');
                    $nodeLineExtensionAmount = $dom->CreateElement('cbc:LineExtensionAmount', $valorVenta);
                    $nodeLineExtensionAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeInvoiceLine->appendChild($nodeLineExtensionAmount);
                
                    $nodePricingReference = $dom->CreateElement('cac:PricingReference'); 
                    $nodeInvoiceLine->appendChild($nodePricingReference);
                    
                        $nodeAlternativeConditionPrice = $dom->CreateElement('cac:AlternativeConditionPrice'); 
                        $nodePricingReference->appendChild($nodeAlternativeConditionPrice);
                        
                            // Precio de venta unitario por item y código
                            $precioUnitario = number_format($item['precio_unitario_con_igv'], 2, '.',''); // precio individual de cada producto con IGV sin multiplicar con cantidad
                            $nodePriceAmount = $dom->CreateElement('cbc:PriceAmount', $precioUnitario); 
                            $nodePriceAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                            $nodeAlternativeConditionPrice->appendChild($nodePriceAmount);

                            // catálogo 16,
                            //     01: Precio unitario (incluye el IGV)
                            //     02: Valor referencial unitario en operaciones no onerosas
                            $nodePriceTypeCode = $dom->CreateElement('cbc:PriceTypeCode', '01'); // 01: Precio unitario (incluye el IGV)
                            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
                            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16'));
                            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
                            $nodeAlternativeConditionPrice->appendChild($nodePriceTypeCode);
                
            // end 35-38

            // begin 39 - Valor referencial unitario por ítem en operaciones no onerosas ** opcional - no aplica
            
                // $nodeInvoiceLine = $dom->CreateElement('cbc:InvoiceLine');
                // $root->appendChild($nodeInvoiceLine);

                // for($i = 0; $i < $n; $i++):
                //     $nodePricingReference = $dom->CreateElement('cac:PricingReference');
                //     $nodeInvoiceLine->appendChild($nodePricingReference);
                    
                //     $nodeAlternativeConditionPrice = $dom->CreateElement('cac:AlternativeConditionPrice');
                //     $nodePricingReference->appendChild($nodeAlternativeConditionPrice);
                    
                //     $nodePriceAmount = $dom->CreateElement('cbc:PriceAmount', 250.00);
                //     $nodePriceAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                //     $nodeAlternativeConditionPrice->appendChild($nodePriceAmount);

                //     $nodePriceTypeCode = $dom->CreateElement('cbc:PriceTypeCode', '02'); // 02: Valor referencial unitario en operaciones no onerosas
                //     $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
                //     $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                //     $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16'));
                //     $nodeAlternativeConditionPrice->appendChild($nodePriceTypeCode);
                // endfor;
            
            // end 39

            // begin 40 - descuentos por items **opcional - no tiene descuento directo -- no aplica
                // catálogo 53, 
                // 00: OTROS DESCUENTOS | Descuentos que afectan la base imponible del IGV Global e Item Todos
                // 01: Descuentos que no afectan la base imponible del IGV Global e Item Todos
                // 02: Descuentos globales que afectan la base imponible del IGV Global Todos
                // 03: Descuentos globales que no afectan la base imponible del IGV
                // 45: FISE Global Todos
                // 46: Recargo al consumo y/o propinas Global
                // 47: Cargos que afectan la base imponible del IGV

                // $nodeAllowanceCharge = $dom->CreateElement('cac:AllowanceCharge');
                // $nodeInvoiceLine->appendChild($nodeAllowanceCharge);

                // $nodeChargeIndicator = $dom->CreateElement('cbc:ChargeIndicator','false'); // true: Indicador del cargo, false: descuento del ítem 
                // $nodeAllowanceCharge->appendChild($nodeChargeIndicator);

                // $nodeMultiplierFactorNumeric = $dom->CreateElement('cbc:MultiplierFactorNumeric', $porcentaje_descontado_operacion_gravada);
                // $nodeAllowanceCharge->appendChild($nodeMultiplierFactorNumeric);

                // $nodeAllowanceChargeReasonCode = $dom->CreateElement('cbc:AllowanceChargeReasonCode','00'); // 00: OTROS DESCUENTOS | Descuentos que afectan la base imponible del IGV
                // $nodeAllowanceCharge->appendChild($nodeAllowanceChargeReasonCode);


                
                // $nodeAmount = $dom->CreateElement('cbc:Amount', $data['subtotal']*$porcentaje_descontado_operacion_gravada); // X%
                // $nodeAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda)); // catálogo 2
                // $nodeAllowanceCharge->appendChild($nodeAmount);
                
                // $nodeBaseAmount = $dom->CreateElement('cbc:BaseAmount', $data['subtotal']);
                // $nodeBaseAmount->setAttributeNode(new \DOMAttr('currencyID',$tipoMoneda)); // catálogo 2
                // $nodeAllowanceCharge->appendChild($nodeBaseAmount);
            // end 40
            
            // begin 41 - cargos por items **opcional - no aplica : consultar si hacen un incremento en la tarifa por días festivos, etc.
            /*
                $nodeInvoiceLine = $dom->CreateElement('cac:InvoiceLine');
                $root->appendChild($nodeInvoiceLine);
                
                $nodeAllowanceCharge = $dom->CreateElement('cac:AllowanceCharge');
                $nodeInvoiceLine->appendChild($nodeAllowanceCharge);

                $nodeChargeIndicator = $dom->CreateElement('cbc:ChargeIndicator', 'true');
                $nodeAllowanceCharge->appendChild($nodeChargeIndicator);
                
                $nodeAllowanceChargeReasonCode = $dom->CreateElement('cbc:AllowanceChargeReasonCode', '50'); // catálogo 53, 50: OTROS CARGOS
                $nodeAllowanceCharge->appendChild($nodeAllowanceChargeReasonCode);
                
                $nodeMultiplierFactorNumeric = $dom->CreateElement('cbc:MultiplierFactorNumeric', '0.10');
                $nodeAllowanceCharge->appendChild($nodeMultiplierFactorNumeric);
                
                $nodeAmount = $dom->CreateElement('cbc:Amount', '44.82'); // 10%
                $nodeAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeAllowanceCharge->appendChild($nodeAmount);
                
                $nodeBaseAmount = $dom->CreateElement('cbc:BaseAmount', '448.20');
                $nodeBaseAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeAllowanceCharge->appendChild($nodeBaseAmount);
            */
            // end 41
            
            // begin 42-48 - Afectación al IGV por ítem ++ OK ++
                // catálogo 7,
                // 10: Gravado - Operación Onerosa
                // 11: Gravado – Retiro por premio
                // 12: Gravado – Retiro por donación
                // 13: Gravado – Retiro
                // 14: Gravado – Retiro por publicidad
                // 15: Gravado – Bonificaciones
                // 16: Gravado – Retiro por entrega a trabajadores
                // 17: Gravado – IVAP
                // 20: Exonerado - Operación Onerosa
                // 21: Exonerado – Transferencia Gratuita
                // 30: Inafecto - Operación Onerosa
                // 31: Inafecto – Retiro por Bonificación
                // 32: Inafecto – Retiro
                // 33: Inafecto – Retiro por Muestras Médicas
                // 34: Inafecto - Retiro por Convenio Colectivo
                // 35: Inafecto – Retiro por premio
                // 36: Inafecto - Retiro por publicidad
                // 40: Exportación de bienes o servicios

                    $nodeTaxTotal = $dom->CreateElement('cac:TaxTotal');
                    $nodeInvoiceLine->appendChild($nodeTaxTotal);
                    
                        $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', $item['precio_igv']);
                        $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                        $nodeTaxTotal->appendChild($nodeTaxAmount);
                        
                        $nodeTaxSubtotal = $dom->CreateElement('cac:TaxSubtotal');
                        $nodeTaxTotal->appendChild($nodeTaxSubtotal);
                    
                            $nodeTaxableAmount = $dom->CreateElement('cbc:TaxableAmount', $item['precio_sin_igv']);
                            $nodeTaxableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                            $nodeTaxSubtotal->appendChild($nodeTaxableAmount);
                            
                            $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', $item['precio_igv']);
                            $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                            $nodeTaxSubtotal->appendChild($nodeTaxAmount);
                            
                            $nodeTaxCategory = $dom->CreateElement('cac:TaxCategory');
                            $nodeTaxSubtotal->appendChild($nodeTaxCategory);

                                $nodeID = $dom->CreateElement('cbc:ID', 'S'); // catálogo 5
                                $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                                $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Tax Category Identifier'));
                                $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'United Nations Economic Commission for Europe'));
                                $nodeTaxCategory->appendChild($nodeID);

                                $nodePercent = $dom->CreateElement('cbc:Percent', '18.00');
                                $nodeTaxCategory->appendChild($nodePercent);
                                
                                $nodeTaxExemptionReasonCode = $dom->CreateElement('cbc:TaxExemptionReasonCode', '10'); // catálogo 7, 10: Gravado - Operación Onerosa
                                $nodeTaxExemptionReasonCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                                $nodeTaxExemptionReasonCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Codigo de Tipo de Afectación del IGV'));
                                $nodeTaxExemptionReasonCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07'));
                                $nodeTaxCategory->appendChild($nodeTaxExemptionReasonCode);

                                $nodeTaxScheme = $dom->CreateElement('cac:TaxScheme');
                                $nodeTaxCategory->appendChild($nodeTaxScheme);
                    
                                    $nodeID = $dom->CreateElement('cbc:ID', '1000'); // catálogo 5, 1000: Igv impuesto general a las ventas
                                    $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5153'));
                                    $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Tax Scheme Identifier'));
                                    $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'United Nations Economic Commission for Europe'));
                                    $nodeTaxScheme->appendChild($nodeID);

                                    $nodeName = $dom->CreateElement('cbc:Name', 'IGV'); // catálogo 5, UN/ECE 5153- Duty or tax or fee type name code
                                    $nodeTaxScheme->appendChild($nodeName);

                                    $nodeTaxTypeCode = $dom->CreateElement('cbc:TaxTypeCode', 'VAT'); // catálogo 5, Nombre del Tributo
                                    $nodeTaxScheme->appendChild($nodeTaxTypeCode);
               
            
            // begin 43 - Afectación al ISC por ítem **opcional  - no aplica
            /*
                $nodeInvoiceLine = $dom->CreateElement('cac:InvoiceLine');
                $root->appendChild($nodeInvoiceLine);
                
                for($i = 0;$i < $n;$i++):
                    $nodeTaxTotal = $dom->CreateElement('cac:TaxTotal');
                    $nodeInvoiceLine->appendChild($nodeTaxTotal);

                    $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', '1750.52');
                    $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxTotal->appendChild($nodeTaxAmount);

                    $nodeTaxSubtotal = $dom->CreateElement('cac:TaxSubtotal');
                    $nodeTaxTotal->appendChild($nodeTaxSubtotal);

                    $nodeTaxableAmount = $dom->CreateElement('cbc:TaxableAmount', '8752.60');
                    $nodeTaxableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxableAmount);

                    $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', '1750.52');
                    $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxAmount);

                    $nodeTaxCategory = $dom->CreateElement('cac:TaxCategory');
                    $nodeTaxSubtotal->appendChild($nodeTaxCategory);

                    $nodeID = $dom->CreateElement('cbc:ID', 'S');
                    $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                    $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Tax Category Identifier'));
                    $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'United Nations Economic Commission for Europe'));
                    $nodeTaxCategory->appendChild($nodeID);

                    $nodePercent = $dom->CreateElement('cbc:Percent', '20.00');
                    $nodeTaxCategory->appendChild($nodePercent);

                    $nodeTaxExemptionReasonCode = $dom->CreateElement('cbc:TaxExemptionReasonCode', '10');
                    $nodeTaxExemptionReasonCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                    $nodeTaxExemptionReasonCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Codigo de Tipo de Afectación del IGV'));
                    $nodeTaxExemptionReasonCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07'));
                    $nodeTaxCategory->appendChild($nodeTaxExemptionReasonCode);

                    $nodeTierRange = $dom->CreateElement('cac:TierRange', '01');
                    $nodeTaxCategory->appendChild($nodeTierRange);
                    
                    $nodeTaxScheme = $dom->CreateElement('cac:TaxScheme');
                    $nodeTaxCategory->appendChild($nodeTaxScheme);
                    
                    $nodeID = $dom->CreateElement('cbc:ID','2000');
                    $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5153'));
                    $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Tax Scheme Identifier'));
                    $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'United Nations Economic Commission for Europe'));
                    $nodeTaxScheme->appendChild($nodeID); 
                    
                    $nodeName = $dom->CreateElement('cbc:Name','ISC');
                    $nodeTaxScheme->appendChild($nodeName);
                    
                    $nodeTaxTypeCode = $dom->CreateElement('cbc:TaxTypeCode','EXC');
                    $nodeTaxScheme->appendChild($nodeTaxTypeCode);
                endfor;
            */
            // end 43

                    // begin 44 - Descripción detallada del servicio prestado, bien vendido o cedido en uso, indicando las características
                    $nodeItem = $dom->CreateElement('cac:Item');
                    $nodeInvoiceLine->appendChild($nodeItem);
                    
                        $nodeDescription = $dom->CreateElement('cbc:Description','<![CDATA['.$item['descripcion'].']]>');
                        $nodeItem->appendChild($nodeDescription);

                    // 48 - Valor unitario del ítem
                    $nodePrice = $dom->CreateElement('cac:Price');
                    $nodeInvoiceLine->appendChild($nodePrice);

                        $precioUnitario = $item['precio_unitario_con_igv']/1.18; // no debe incluir el igv
                        $nodePriceAmount = $dom->CreateElement('cbc:PriceAmount', $precioUnitario); 
                        $nodePriceAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                        $nodePrice->appendChild($nodePriceAmount);
                endforeach;
            // end 42-48

            // begin 45 - Código de producto del Ítem **opcional -- no aplica
                /*
                $nodeInvoiceLine = $dom->CreateElement('cac:InvoiceLine');
                $root->appendChild($nodeInvoiceLine);
                
                $nodeItem = $dom->CreateElement('cac:Item');
                $nodeInvoiceLine->appendChild($nodeItem);
                
                for($i = 0;$i < $n;$i++):
                    $nodeSellersItemIdentification = $dom->CreateElement('cbc:SellersItemIdentification');
                    $nodeItem->appendChild($nodeSellersItemIdentification); 
                    
                    // Código de producto
                    $nodeID = $dom->CreateElement('ID','Cap-258963');
                    $nodeSellersItemIdentification->appendChild($nodeID);
                    
                    $nodeCommodityClassification = $dom->CreateElement('cac:CommodityClassification');
                    $nodeItem->appendChild($nodeCommodityClassification);
                    
                    // Código de producto SUNAT
                    $nodeItemClassificationCode = $dom->CreateElement('ItemClassificationCode', '51121703');
                    $nodeItemClassificationCode->setAttributeNode(new \DOMAttr('listID', 'UNSPSC'));
                    $nodeItemClassificationCode->setAttributeNode(new \DOMAttr('listAgencyName', 'GS1 US'));
                    $nodeItemClassificationCode->setAttributeNode(new \DOMAttr('listName', 'Item Classification'));
                    $nodeCommodityClassification->appendChild($nodeItemClassificationCode);
                endfor;
                */
            // end 45

            // 47 - Propiedades Adicionales del Ítem - no aplica
            
                     
            
            $dom->appendChild($root);

            $year = substr($data['emisor_fecha_documento_electronico'], 0, 4);
            $tree = "comprobantes/" . $year . "/" . $encargo_id;
            $filename = $data['emisor_ruc'].'-03-'.$data['emisor_numero_documento_electronico'].'.xml';
            $estructura = base_path('public/'.$tree);
            if(!@mkdir($estructura, 0777, true)) {
                if (file_exists($estructura . "/" . $filename)) { @unlink($estructura . "/" . $filename); }
            }
            $xml_file_name = $estructura.'/'.$filename;
            $dom->save($xml_file_name);
            $zip = new \ZipArchive();
            $zip->open(str_ireplace('xml','zip',$xml_file_name), \ZipArchive::CREATE);
            $zip->addFile($xml_file_name, $filename);
            $zip->close();
            file_get_contents($xml_file_name);
        }
    }

    public function getQR($estructura, $data) {
        $hash = "";
        $dniruc = (isset($data['adquiriente_ruc']))? $data['adquiriente_ruc'] : $data['adquiriente_ruc_dni_ce'];
        $doc = (isset($data['adquiriente_ruc']))? '01': '03';
        $value = $data['emisor_ruc'].'|'.$doc.'|'.$data['emisor_numero_documento_electronico'].'|IGV|'.$data['subtotal'].'|'.$data['emisor_fecha_documento_electronico'].'|1|'.$dniruc.'|'.$hash;
        $img = $estructura.'/qrcode.png';
        return ['value' => $value, 'img' => $img];
    }
}
