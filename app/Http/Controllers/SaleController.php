<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Business\Sede;
use App\Business\Carga;
use App\Business\Agencia;
use App\Business\Documento;
use App\Business\Encargo;
use App\Business\Adquiriente;
use MongoDB\BSON\ObjectId;
use \PHPQRCode\QRcode;
use PDF;

class SaleController extends Controller
{
    public function register(Request $request) {
        $data = $request->all();
        $var = function() {     
            $data = func_get_arg(0);
            $stack = [];
            foreach($data as $item) {
                $element = explode(",", $item);
                if ($element[0] !== "--"){
                    $peso = $element[1];
                    $cantidad = $element[2];
                    $precio = $element[3];
                    $carga = Carga::find($element[0]);
                    if ($carga) {
                        $total = number_format($cantidad*$precio*$peso, 2, '.', '');
                        array_push($stack, [
                            'carga' => $carga->id,
                            'descripcion' => $carga->nombre,
                            'cantidad' => $cantidad,
                            'precio' => $precio,
                            'peso' => $peso,
                            'total' => $total,
                        ]);
                    }
                }
            }
            return [$stack];
        };
        if (!$var($data['encargo'])) {
            return \response()->json(['result' => ['status' => 'fails', 'message' => 'No ha ingresado el detalle de la encomienda.']]);
        }

        $documento = Documento::find($data['documento']);

        // registrar o actualizar el adquiriente
        $documento_fecha = date('Y-m-d');
        if ($documento->alias === 'G') {
            // guía de remisión
            $insertAdquiriente = [
                'documento' => $data['docRecibe'],
                'razon_social' => $data['nombreRecibe'],
                'nombre_comercial' => $data['nombreComercialRecibe'],
                'direccion' => $data['direccionRecibe'],
            ];
        } else if ($documento->alias === 'B' || $documento->alias === 'F') {
            // boletas y facturas
            $insertAdquiriente = [
                'documento' => $data['docEnvia'],
                'razon_social' => $data['nombreEnvia'],
                'nombre_comercial' => $data['nombreComercialEnvia'],
                'direccion' => $data['direccionEnvia'],
            ];
        } else {
            // hey, no deberías estar aquí, el validador dice que hay campos obligatorios!
            $documento_fecha = '';
            $insertAdquiriente = [];
        }
        if (strlen($data['encargoId']) > 0) {
            $adquiriente = $data['adquiriente'];
        } else {
            $adquiriente = (Adquiriente::create($insertAdquiriente));
            $adquiriente = $adquiriente->id;
        }
        
        // registrar o actualizar el encargo
        $agenciaId = $data['agenciaOrigen']; // agencia que está en sesión. hacer luego
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

            // 'origen' => $data['origen'],
            'destino' => new ObjectId($data['destino']),
            'agencia_origen' => new ObjectId($data['agenciaOrigen']),
            'agencia_destino' => new ObjectId($data['agenciaDestino']),
            
            'agencia' => new ObjectId($agenciaId),
            'adquiriente' => new ObjectId($adquiriente),
            'medio_pago' => $data['medioPago'],
            'documento' => new ObjectId($data['documento']),
            'documento_serie' => $data['documentoSerie'],
            'documento_correlativo' => $data['documentoCorrelativo'],
            'documento_fecha' => $documento_fecha,
            'encargo' => $var($data['encargo'])[0],
            'subtotal' => number_format($data['subtotal'], 2, '.', ''),
            'importe_pagar' => number_format($data['importePagar'], 2, '.', ''),
        ];
        
        if (strlen($data['encargoId']) > 0) {
            $ObjectId = new ObjectId($data['encargoId']);
            $encargo = Encargo::where('_id', $ObjectId)->update($insertEncargo, ['upsert' => true]);
            if (!$encargo) {
                return \response()->json(['result' => ['status' => 'fails', 'message' => 'No se pudo grabar los cambios, inténtalo otra vez.']]);
            }
            $encargoId = $data['encargoId']; 
            $documentoCorrelativo = $data['documentoCorrelativo'];
        } else {
            $encargo = Encargo::create($insertEncargo);
            $encargoId = $encargo['id'];
            $ObjectId = new ObjectId($encargoId);
            $fechaEnvia = date('Y-m-d');
            $documentoCorrelativo = sprintf("%0".env('ZEROFILL', 8)."d",Encargo::getNextSequence($encargoId, $data['documentoSerie']));
            $encargo = Encargo::where('_id', $ObjectId)->update(['fecha_envia' => $fechaEnvia, 'documento_correlativo' => $documentoCorrelativo]);
        }

        // registrar o actualizar el PDF
        if ($documento->alias === 'G') {
            $this->writeRemition($encargoId);
            $this->writeXMLBill($encargoId);
        } else if ($documento->alias === 'B' || $documento->alias === 'F') {
            $this->writeBill($encargoId);
            $this->writeXMLBill($encargoId);
        } else {
            // no escribir PDF
        }
        // hacer la asociacion de PDF en la BD. hacer luego
        
        return \response()->json([
            'result' => [
                'status' => 'OK', 
                'message' => 'Registro correctamente', 
                'encargoId' => $encargoId, 
                'adquiriente' => $adquiriente, 
                'documentoCorrelativo' => $documentoCorrelativo,
                'fechaEnvia' => $fechaEnvia,
            ]
        ]);
    }

    public function show() {
        $sede = Sede::all();
        $carga = Carga::orderBy('nombre','asc')->get();
        $agenciaOrigen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        return view('sale.show')->with([ 'agenciaOrigen' => $agenciaOrigen, 'sede' => $sede, 'documento' => $documento, 'carga' => $carga ]);
    }

    public function edit($encargoId) {
        $sede = Sede::all();
        $carga = Carga::all();
        $agenciaOrigen = Agencia::all(); // sacar los valores de la sesión del usuario según los perfiles que tenga asignado
        $documento = Documento::all();
        $encargo = Encargo::find($encargoId);
        return view('sale.edit')->with([ 'agenciaOrigen' => $agenciaOrigen, 'sede' => $sede, 'documento' => $documento, 'carga' => $carga, 'encargo' => $encargo ]);
    }

    public function list() {
        $encargo = Encargo::all();
        return view('sale.list')->with([ 'encargo' => $encargo ]);
    }

    public function writeBill($encargoId) {
        $data = Encargo::findBill($encargoId);
        if ($data) {
            PDF::SetTitle($data['tituloDocumento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            $width = 50;
            
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            $fontSizeGrande = 9;
            $fontSizeGigante = 10;
            $fontSizeRegular = 7;
            
            $border = 'B';
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::MultiCell($width, $height, $data['emisorNombreComercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['emisorRazonSocial'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['emisorDireccionFiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['emisorRUC'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::Cell($width, $height, "TELÉFONO: " . $data['emisorAgenciaTelefono'], 'T', 1, 'L', 0);
            PDF::MultiCell($width, $height, "TERMINAL: ".$data['emisorAgenciaDireccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------
            
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::Cell($width, $height, $data['emisorTipoDocumentoElectronico'], 'T', 1, 'C', 0);
            PDF::MultiCell($width, $height, $data['emisorNumeroDocumentoElectronico'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "OPERADOR: ".$data['adquirienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisorFechaDocumentoElectronico'], 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: 00:00:00" . $data['emisorHoraDocumentoElectronico'], 'B', 1, 'R', 0);
            // -------------
            
            $dniruc = (strlen($data['adquirienteRUC']) === 8) ? 'DNI/CE' : 'RUC';
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['adquirienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, "$dniruc: " . $data['adquirienteRUC'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $fontSizeGrande);
            foreach($data['consigna'] as $nombre):
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $fontSizeRegular);
            PDF::Cell(24, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(14, $height, "CANTIDAD", '', 0, 'L', 1);
            PDF::Cell(12, $height, "PRECIO", '', 0, 'R', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            $importeTotal = 0.00;
            PDF::SetFont('times', '', $fontSizeRegular);
            foreach($data['encargoDetalle'] as $encargo):
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
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::Cell(35, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::Cell(20, $height, $importePagar, '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeGrande);
            PDF::Cell($width, $height, "hash SUNAT:", 'T', 1, 'L', 1);
            PDF::Cell($width, $height, "1c7a92ae351d4e21ebdfb897508f59d6", '', 1, 'L', 1);

            $qr = $this->getQR($data);
            QRcode::png($qr['value'], $qr['img'], 'L', 4, 2);
            PDF::Image($qr['img'], '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "Representación impresa de ".$data['emisorTipoDocumentoElectronico'].". Puede descargarlo y/o consultarlo desde www.enlacesbus.com.pe/see", $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
            $year = substr($data['emisorFechaDocumentoElectronico'], 0, 4);
            $filename = "pruebas/" . $year . "/" . $encargoId . ".pdf";
            if (file_exists(base_path("public/" . $filename))) { unlink(base_path("public/" . $filename)); }
            PDF::Output(public_path($filename), 'F');
            PDF::reset();
        }
    }

    public function writeRemition($encargoId) {
        $data = Encargo::findRemition($encargoId);
        if ($data) {
            PDF::SetTitle($data['tituloDocumento']);
            PDF::setPrintHeader(false);
            PDF::setPrintFooter(false);
            PDF::AddPage();
            $width = 50;
            
            PDF::SetFillColor(255, 255, 255);
            PDF::SetTextColor(0);
            $fontSizeGrande = 9;
            $fontSizeGigante = 10;
            $fontSizeRegular = 7;
            
            $border = 'B';
            $align_center = 'C';
            $align_left = 'L';
            $align_right = 'R';
            $height = '';
            $y = '';
            $x = ''; // 5
            $width = 60;
            PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::MultiCell($width, $height, $data['emisorNombreComercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['emisorRazonSocial'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['emisorDireccionFiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['emisorRUC'], $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::Cell($width, $height, "TELÉFONO: " . $data['emisorAgenciaTelefono'], 'T', 1, 'L', 0);
            PDF::MultiCell($width, $height, "TERMINAL: ".$data['emisorAgenciaDireccion'], $border, $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------
            
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::Cell($width, $height, $data['emisorTipoDocumentoElectronico'], 'T', 1, 'C', 0);
            PDF::MultiCell($width, $height, $data['emisorNumeroDocumentoElectronico'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", '', 1, 'L', 1);
            $img = base_path('public/assets/media/icons/sis/bus.png');
            PDF::Image($img, '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "OPERADOR: ".$data['adquirienteRazonSocial'], 'T', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisorFechaDocumentoElectronico'], 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: 00:00:00" . $data['emisorHoraDocumentoElectronico'], 'B', 1, 'R', 0);
            // -------------
            
            $dniruc = (strlen($data['adquirienteRUC']) === 8) ? 'DNI/CE' : 'RUC';
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['adquirienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, "$dniruc: " . $data['adquirienteRUC'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $fontSizeGrande);
            foreach($data['consigna'] as $nombre):
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            endforeach;

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell(20, $height, "DESTINO:", '', $align_left, 1, 0, $x, $y);
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::MultiCell(40, $height, $data['destino'], $border, $align_right, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width, $height, "", 'T', 1, 'L', 0, '', 10);
            
            PDF::SetFont('times', 'B', $fontSizeRegular);
            PDF::Cell(24, $height, "DESCRIPCIÓN", '', 0, 'L', 1);
            PDF::Cell(14, $height, "CANTIDAD", '', 0, 'L', 1);
            PDF::Cell(12, $height, "PRECIO", '', 0, 'R', 1);
            PDF::Cell(10, $height, "TOTAL", '', 0, 'R', 1);
            PDF::Ln();

            $importeTotal = 0.00;
            PDF::SetFont('times', '', $fontSizeRegular);
            foreach($data['encargoDetalle'] as $encargo):
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
            
            PDF::SetFont('times', 'B', $fontSizeGrande);
            PDF::Cell(35, $height, "IMPORTE TOTAL", '', 0, 'L', 1);
            PDF::Cell(5, $height, "S/.", '', 0, 'C', 1);
            PDF::SetFont('times', 'B', $fontSizeGigante);
            PDF::Cell(20, $height, $importePagar, '', 0, 'R', 1);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
            $year = substr($data['emisorFechaDocumentoElectronico'], -4);
            $filename = "pruebas/" . $year . "/" . $encargoId . ".pdf";
            if (file_exists(base_path("public/" . $filename))) { unlink(base_path("public/" . $filename)); }
            PDF::Output(public_path($filename), 'F');
            PDF::reset();
        }
        
    }

    public function writeXMLBill($encargoId) {
        $data = Encargo::findBill($encargoId);
        if ($data) {
            // IMPORTANTE: La factura electrónica deberá tener información de los por lo menos uno de siguientes campos definidos como opcionales: 18. Total valor de venta – operaciones gravadas, 19. Total valor de venta – operaciones inafectas o 20. Total valor de vento - operaciones exoneradas
            // variables
            $X509Certificate = 'MIIF9TCCBN2gAwIBAgIGAK0oRTg/MA0GCSqGSIb3DQEBCwUAMFkxCzAJBgNVBAYTAlRSMUowSAYDVQQDDEFNYWxpIE3DvGjDvHIgRWxla3Ryb25payBTZXJ0aWZpa2EgSGl6bWV0IFNhxJ9sYXnEsWPEsXPEsSAtIFRlc3QgMTAeFw0wOTEwMjAxMTM3MTJaFw0xNDEwMTkxMTM3MTJaMIGgMRowGAYDVQQLDBFHZW5lbCBNw7xkw7xybMO8azEUMBIGA1UEBRMLMTAwMDAwMDAwMDIxbDBqBgNVBAMMY0F5ZMSxbiBHcm91cCAtIFR1cml6bSDEsHRoYWxhdCDEsGhyYWNhdCBUZWtzdGlsIMSwbsWfYWF0IFBhemFyiMwtPnC2DRjdsyGv3bxwRZr9wXMRrMNwRjyFe9JPA7bSscEgaXwzDUG5FCvfS/PNT+XCce+VECAx6Q3R1ZRSA49fYz6tDB4Ia5HVBXZODmrCs26XisHF6kuS5N/yGg8E7VC1BRr/SmxXeLTdjQYAfo7lxCz4dT6wP5TOiBvF+lyWW1bi9nbliXyb/e5HjCp4k/ra9LTskjbY/Ukl5O8G9JEAViZkjvxDX7T0yVRHgMGiioIKVMwU6Lrtln607BNurLwED0OeoZ4wBgkBiB5vXofreXrfN2pHZ2=';
            $tipoMoneda = 'PEN';
            $n = 3;

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

            // begin 1 -firma digital
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
                /* catálogo 51,
                0101: Venta interna Factura, Boletas
                0102: Venta Interna – Anticipos Factura, Boletas
                0103: Venta interna - Itinerante Factura, Boletas
                0110: Venta Interna - Sustenta Traslado de Mercadería - Remitente Factura, Boletas
                0111: Venta Interna - Sustenta Traslado de Mercadería - Transportista Factura, Boletas
                0112: Venta Interna - Sustenta Gastos Deducibles Persona Natural Factura
                0120: Venta Interna - Sujeta al IVAP Factura, Boletas
                0121: Venta Interna - Sujeta al FISE Todos
                0122: Venta Interna - Sujeta a otros impuestos Todos
                0130: Venta Interna - Realizadas al Estado Factura, Boletas
                0200: Exportación de Bienes Factura, Boletas
                0201: Exportación de Servicios – Prestación servicios realizados Factura, Boletas íntegramente en el país
                0202: Exportación de Servicios – Prestación de servicios de hospedaje No Domiciliado Factura, Boletas
                0203: Exportación de Servicios – Transporte de navieras Factura, Boletas
                0204: Exportación de Servicios – Servicios a naves y aeronaves de bandera extranjera Factura, Boletas
                0205: Exportación de Servicios - Servicios que conformen un Paquete Turístico Factura, Boletas
                0206: Exportación de Servicios – Servicios complementarios al transporte de carga Factura, Boletas
                0207: Exportación de Servicios – Suministro de energía eléctrica a favor de sujetos domiciliados en ZED Factura, Boletas
                0208: Exportación de Servicios – Prestación servicios realizados parcialmente en el extranjero Factura, Boletas
                0301: Operaciones con Carta de porte aéreo (emitidas en el ámbito nacional) Factura, Boletas
                0302: Operaciones de Transporte ferroviario de pasajeros Factura, Boletas
                0303: Operaciones de Pago de regalía petrolera Factura, Boletas
                1001: Operación Sujeta a Detracción Factura, Boletas
                1002: Operación Sujeta a Detracción- Recursos Hidrobiológicos Factura, Boletas
                */
                $nodeProfileID = $dom->createElement('cbc:ProfileID', '0101'); // catálogo 51, 0101: Venta interna
                $nodeProfileID->setAttributeNode(new \DOMAttr('schemeName', 'SUNAT:Identificador de Tipo de Operación')); // Identificador de Código de tipo de operación
                $nodeProfileID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                $nodeProfileID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17')); // catálogo 51
                $root->appendChild($nodeProfileID);
            // end 4

            // begin 5 - Numeración, conformada por serie y número correlativo
                $facturaCorrelativo = $data['emisorNumeroDocumentoElectronico'];
                $nodeID = $dom->createElement('cbc:ID', $facturaCorrelativo);
                $root->appendChild($nodeID);
            // end 5

            // begin 6 - fecha de emisión
                $facturaFechaEmision = $data['emisorFechaDocumentoElectronico']; //yyyy-mm-dd
                $nodeIssueDate = $dom->createElement('cbc:IssueDate', $facturaFechaEmision);
                $root->appendChild($nodeIssueDate);
            // end 6

            // begin 7 - hora de emisión
                $facturaHoraEmision = $data['emisorHoraDocumentoElectronico']; //hh:ii:ss
                $nodeIssueTime = $dom->createElement('cbc:IssueTime', $facturaHoraEmision);
                $root->appendChild($nodeIssueTime);
            // end 7

            // begin 8 - fecha de vencimiento ** opcional
                /* 
                $facturaFechaVence = '2017-05-28';
                $nodeDueDate = $dom->createElement('cbc:DueDate', $facturaFechaVence);
                $root->appendChild($nodeDueDate);
                */
            // end 8

            // begin 9 - tipo de documento (Factura) **
                /*
                01: Factura **
                03: Boleta de venta **
                06: Carta de porte aéreo
                07: Nota de crédito **
                08: Nota de débito
                09: Guia de remisión remitente **
                12: Ticket de maquina registradora
                13: Documento emitido por bancos, instituciones financieras, crediticias y de seguros que se encuentren
                ba:jo el control de la superintendencia de banca y seguros
                14: Recibo de servicios públicos
                15: Boletos emitidos por el servicio de transporte terrestre regular urbano de pasajeros y el ferroviario
                pú:blico de pasajeros prestado en vía férrea local.
                16: Boleto de viaje emitido por las empresas de transporte público interprovincial de pasajeros
                18: Documentos emitidos por las afp
                20: Comprobante de retencion
                21: Conocimiento de embarque por el servicio de transporte de carga marítima
                24: Certificado de pago de regalías emitidas por perupetro s.a.
                31: Guía de remisión transportista **
                37: Documentos que emitan los concesionarios del servicio de revisiones técnicas
                40: Comprobante de percepción
                41: Comprobante de percepción – venta interna (físico - formato impreso)
                43: Boleto de compañias de aviación transporte aéreo no regular
                45: Documentos emitidos por centros educativos y culturales, universidades, asociaciones y fundaciones.
                56: Comprobante de pago seae
                71: Guia de remisión remitente complementaria
                72: Guia de remisión transportista complementaria
                */
                $nodeInvoiceTypeCode = $dom->createElement('cbc:InvoiceTypeCode', '01'); // 01:Factura
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listID', '0101')); // revisado en otras boletas y facturas
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Identificador de Tipo de Documento')); // Código de tipo de documento autorizado para efectos tributarios
                $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01'));
                $root->appendChild($nodeInvoiceTypeCode);
            // end 9

            // begin 10 - leyendas, catálogo 52
                $subtotal = explode('.', $data['subtotal']);
                $formatterES = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
                $valorNumericoLetras = $formatterES->format($subtotal[0]);
                $nodeNote = $dom->createElement('cbc:Note', htmlspecialchars('<![CDATA['.mb_strtoupper($valorNumericoLetras) .' CON '.$subtotal[1].'/100 SOLES]]>', ENT_QUOTES));
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
            
            // begin 11 - Tipo de moneda en la cual se emite la factura electrónica
                $nodeDocumentCurrencyCode = $dom->createElement('cbc:DocumentCurrencyCode', $tipoMoneda);
                $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listID', 'ISO 4217 Alpha'));
                $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listName', 'Currency'));
                $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listAgencyName', 'United Nations Economic Commission for Europe'));
                $root->appendChild($nodeDocumentCurrencyCode);
            // end 11

            // firmando otra vez..
            $nodeSignature = $dom->createElement('cac:Signature');
            $root->appendChild($nodeSignature);
            
            $firma = 'SB209-128311'; // Identificador de la firma
            $nodeID = $dom->createElement('cbc:ID', $firma);
            $nodeSignature->appendChild($nodeID);
            
            $nodeSignatoryParty = $dom->createElement('cac:SignatoryParty');
            $nodeSignature->appendChild($nodeSignatoryParty);
            
            $nodePartyIdentification = $dom->createElement('cac:PartyIdentification');
            $nodeSignatoryParty->appendChild($nodePartyIdentification);
            
            $nodeID = $dom->createElement('cbc:ID', $data['emisorRUC']);
            $nodeID->setAttributeNode(new \DOMAttr('schemeID', '6'));
            $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Documento de Identidad'));
            $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
            $nodeID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
            $nodePartyIdentification->appendChild($nodeID);
            
            $nodePartyName = $dom->createElement('cac:PartyName');
            $nodeSignatoryParty->appendChild($nodePartyName);
            
            $nodeName = $dom->createElement('cbc:Name', htmlspecialchars('<![CDATA['.$data['emisorRazonSocial'].']]>', ENT_QUOTES));
            $nodePartyName->appendChild($nodeName);

            $nodeDigitalSignatureAttachment = $dom->createElement('cac:DigitalSignatureAttachment');
            $nodeSignature->appendChild($nodeDigitalSignatureAttachment);
            
            $nodeExternalReference = $dom->createElement('cac:ExternalReference');
            $nodeDigitalSignatureAttachment->appendChild($nodeExternalReference);
            
            $nodeURI = $dom->createElement('cbc:URI','#SignatureSP');
            $nodeDigitalSignatureAttachment->appendChild($nodeURI);
 
            /*
            12 y 13 omitidos
            qwe: requiero una explicación
            12 Tipo y número de la guía de remisión relacionada con la operación que se factura
            <cac:DespatchDocumentReference>
            <cbc:ID>031-002020</cbc:ID>
            <cbc:DocumentTypeCode
            listAgencyName="PE:SUNAT"
            listName="SUNAT:Identificador de guía relacionada"
            listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">09</cbc:DocumentTypeCode>
            </cac:DespatchDocumentReference>
            13 Tipo y número de otro documento y código relacionado con la operación que se factura
            <cac:AdditionalDocumentReference>
            <cbc:ID>024099</cbc:ID>
            <cbc:DocumentTypeCode
            listAgencyName="PE:SUNAT"
            listName="SUNAT: Identificador de documento relacionado"
            listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12">99</cbc:DocumentTypeCode>
            </cac:AdditionalDocumentReference>
            */

            // begin 14-17 (emisor)
                $nodeAccountingSupplierParty = $dom->createElement('cac:AccountingSupplierParty');
                $root->appendChild($nodeAccountingSupplierParty);

                $nodeParty = $dom->createElement('cac:Party');
                $nodeAccountingSupplierParty->appendChild($nodeParty);
                
                $nodePartyIdentification = $dom->createElement('cac:PartyIdentification');
                $nodeParty->appendChild($nodePartyIdentification);
                
                $d = '123'; // $data['emisorRUC'];
                $nodeID = $dom->createElement('cbc:ID', $d);
                $nodeID->setAttributeNode(new \DOMAttr('schemeID', '6'));
                $nodePartyIdentification->appendChild($nodeID);

                if($data['emisorNombreComercial']):
                    // Nombre Comercial del emisor
                    $nodePartyName = $dom->createElement('cac:PartyName');
                    $nodeParty->appendChild($nodePartyName);
                    $emisorNombreComersial = $data['emisorNombreComercial'];
                    $nodeName = $dom->createElement('cbc:Name', '<![CDATA['.$emisorNombreComersial.']]>');
                    $nodePartyName->appendChild($nodeName);
                endif;

                // Apellidos y nombres, denominación o razón social del emisor
                $nodePartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
                $nodeParty->appendChild($nodePartyTaxScheme);

                $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', htmlspecialchars('<![CDATA['.$data['emisorRazonSocial'].']]>', ENT_QUOTES));
                $nodePartyTaxScheme->appendChild($nodeRegistrationName);

                // Tipo y Número de RUC del emisor
                $emisorRUC = $data['emisorRUC'];
                $nodeCompanyID = $dom->createElement('CompanyID', $emisorRUC);
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeID','6'));
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeName','SUNAT:Identificador de Documento de Identidad'));
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeAgencyName','PE:SUNAT'));
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeURI','urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                $nodePartyTaxScheme->appendChild($nodeCompanyID);

                $nodeTaxScheme = $dom->createElement('cac:TaxScheme');
                $nodePartyTaxScheme->appendChild($nodeTaxScheme);

                $nodeID = $dom->createElement('cbc:ID','-');
                $nodeTaxScheme->appendChild($nodeID);

                // Código del domicilio fiscal o de local anexo del emisor ** opcional
                $nodeRegistrationAddress = $dom->createElement('cac:RegistrationAddress');
                $nodePartyTaxScheme->appendChild($nodeRegistrationAddress);

                $emisorUbigeo = '0001'; // 0000 en caso no se tenga
                $nodeAddressTypeCode = $dom->createElement('cbc:AddressTypeCode', $emisorUbigeo);
                $nodeRegistrationAddress->appendChild($nodeAddressTypeCode);
            // end 14-17

            // begin 18-19 (adquiriente)
                $nodeAccountingCustomerParty = $dom->createElement('cac:AccountingCustomerParty');
                $root->appendChild($nodeAccountingCustomerParty);

                $nodeParty = $dom->createElement('cac:Party');
                $nodeAccountingCustomerParty->appendChild($nodeParty);

                // homologacion con otros xmls
                $nodePartyIdentification = $dom->createElement('cac:PartyIdentification');
                $nodeParty->appendChild($nodePartyIdentification);
                
                $nodeID = $dom->createElement('cbc:ID', $data['adquirienteRUC']);
                $nodeID->setAttributeNode(new \DOMAttr('schemeID', '6')); // para RUC
                $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Documento de Identidad'));
                $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                $nodeID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                $nodePartyIdentification->appendChild($nodeID);

                $nodePartyName = $dom->createElement('cac:PartyName');
                $nodeParty->appendChild($nodePartyName);

                $nodePartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
                $nodeParty->appendChild($nodePartyLegalEntity);
                
                $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', htmlspecialchars('<![CDATA['.$data['adquirienteRazonSocial'].']]>', ENT_QUOTES));
                $nodePartyLegalEntity->appendChild($nodeRegistrationName);
                
                $adquirienteRazonSocial = $data['adquirienteRazonSocial'];
                $nodeName = $dom->createElement('cbc:Name','<![CDATA['.$adquirienteRazonSocial.']]>');
                $nodePartyName->appendChild($nodeName);

                $nodePartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
                $nodeParty->appendChild($nodePartyTaxScheme);
                
                // Apellidos y nombres, denominación o razón social del adquirente o usuario
                $adquirienteRazonSocial = $data['adquirienteRazonSocial']; // apellidos y nombres o denominación o razón social
                $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', '<![CDATA['.$adquirienteRazonSocial.']]>');
                $nodePartyTaxScheme->appendChild($nodeRegistrationName);

                // Tipo y número de documento de identidad del adquirente o usuario
                $adquirienteRUC = $data['adquirienteRUC'];
                $nodeCompanyID = $dom->createElement('cbc:CompanyID', '<![CDATA['.$adquirienteRUC.']]>');
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeID', '6')); // 6:RUC, 1:DNI, 4:Carnet de extranjería, 0:NN
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeName', 'SUNAT:Identificador de Documento de Identidad')); // Tipo de Documento de Identificación
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
                $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
                $nodePartyTaxScheme->appendChild($nodeCompanyID);

                $nodeTaxScheme = $dom->createElement('cac:TaxScheme');
                $nodePartyTaxScheme->appendChild($nodeTaxScheme);

                $nodeID = $dom->createElement('cbc:ID', '-');
                $nodeTaxScheme->appendChild($nodeID);
            // end 18-19

            // 20 omitidos ** opcional
            /* /Invoice/cac:DeliveryTerms/cac:DeliveryLocation/cac:Address
                <cac:DeliveryTerms> <cac:DeliveryLocation >
                <cac:Address>
                <cbc:StreetName>CALLE NEGOCIOS # 420</cbc:StreetName> <cbc:CitySubdivisionName/>
                <cbc:CityName>LIMA</cbc:CityName> <cbc:CountrySubentity>LIMA</cbc:CountrySubentity> <cbc:CountrySubentityCode>150141</cbc:CountrySubentityCode> <cbc:District>SURQUILLO</cbc:District>
                <cac:Country>
                <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode> </cac:Country>
                </cac:Address> </cac:DeliveryLocation >
                </cac:DeliveryTerms>
            */

            // begin 21 - Información de descuentos Globales **opcional
                $factor = 0.10;
                $baseAmount = $data['subtotal'];
                $amount = round($baseAmount*$factor, 2);

                $nodeAllowanceCharge = $dom->CreateElement('cac:AllowanceCharge');
                $root->appendChild($nodeAllowanceCharge);

                $nodeChargeIndicator = $dom->CreateElement('cbc:ChargeIndicator','false'); // originalmente decía False en la documentación
                $nodeAllowanceCharge->appendChild($nodeChargeIndicator);

                $nodeAllowanceChargeReasonCode = $dom->CreateElement('cbc:AllowanceChargeReasonCode','00'); // catálogo 53, 00: OTROS DESCUENTOS | 00:Descuentos que afectan la base imponible del IGV, 01:Descuentos que no afectan la base imponible del IGV
                $nodeAllowanceCharge->appendChild($nodeAllowanceChargeReasonCode);
                
                $nodeMultiplierFactorNumeric = $dom->CreateElement('cbc:MultiplierFactorNumeric', $factor);
                $nodeAllowanceCharge->appendChild($nodeMultiplierFactorNumeric);

                $nodeAmount = $dom->CreateElement('cbc:Amount', $amount);
                $nodeAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeAllowanceCharge->appendChild($nodeAmount);

                $nodeBaseAmount = $dom->CreateElement('cbc:BaseAmount', $baseAmount);
                $nodeBaseAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeAllowanceCharge->appendChild($nodeBaseAmount);
            // end 21

            // begin 22-29
                $nodeTaxTotal = $dom->CreateElement('cac:TaxTotal');
                $root->appendChild($nodeTaxTotal);

                $igp = 0.18;
                $montoTotal = $baseAmount;
                $montoTotalImpuesto = round($montoTotal*$igp, 2);
                // Monto total de impuestos **opcional
                $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount',$montoTotalImpuesto);
                $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeTaxTotal->appendChild($nodeTaxAmount);

                // Monto las operaciones gravadas **opcional 
                $nodeTaxSubtotal = $dom->CreateElement('cac:TaxSubtotal');
                $nodeTaxTotal->appendChild($nodeTaxSubtotal);

                $nodeTaxableAmount = $dom->CreateElement('cbc:TaxableAmount', $montoTotal);
                $nodeTaxableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeTaxSubtotal->appendChild($nodeTaxableAmount);

                $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', $montoTotalImpuesto);
                $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeTaxSubtotal->appendChild($nodeTaxAmount);

                $nodeTaxCategory = $dom->CreateElement('cac:TaxCategory');
                $nodeTaxSubtotal->appendChild($nodeTaxCategory);

                $nodeID = $dom->CreateElement('cbc:ID', 'S');
                $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                $nodeID->setAttributeNode(new \DOMAttr('schemeName', 'Tax Category Identifier'));
                $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'United Nations Economic Commission for Europe'));
                $nodeTaxCategory->appendChild($nodeID);

                $nodeTaxScheme = $dom->CreateElement('cac:TaxScheme');
                $nodeTaxCategory->appendChild($nodeTaxScheme);

                $nodeID = $dom->CreateElement('cbc:ID','1000'); // catálogo 5, 1000: Igv impuesto general a las ventas
                $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
                $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyID', '6'));
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
            
            // begin 30-34 **opcional
                $nodeLegalMonetaryTotal = $dom->CreateElement('cac:LegalMonetaryTotal'); 
                $root->appendChild($nodeLegalMonetaryTotal);

                // Total valor de venta **opcional -- OK
                $nodeLineExtensionAmount = $dom->CreateElement('cbc:LineExtensionAmount', $data['subtotal']*0.82); 
                $nodeLineExtensionAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeLineExtensionAmount);

                // Total precio de venta (incluye impuestos) **opcional -- OK
                $nodeTaxInclusiveAmount = $dom->CreateElement('cbc:TaxInclusiveAmount', $data['subtotal']); 
                $nodeTaxInclusiveAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeTaxInclusiveAmount);

                /*
                // Monto total de descuentos del comprobante **opcional -- podría usar este
                $nodeAllowanceTotalAmount = $dom->CreateElement('cbc:AllowanceTotalAmount', '60.00'); 
                $nodeAllowanceTotalAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeAllowanceTotalAmount);

                // Monto total de otros cargos del comprobante **opcional
                $nodeChargeTotalAmount = $dom->CreateElement('cbc:ChargeTotalAmount', '320.00'); 
                $nodeChargeTotalAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodeChargeTotalAmount);

                // Monto total de otros cargos del comprobante **opcional
                $nodePrepaidAmount = $dom->CreateElement('cbc:PrepaidAmount', '100.00'); 
                $nodePrepaidAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodePrepaidAmount);
                */

                // Importe total de la venta, cesión en uso o del servicio prestado  **opcional -- OK
                $nodePayableAmount = $dom->CreateElement('cbc:PayableAmount', $data['subtotal']); 
                $nodePayableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                $nodeLegalMonetaryTotal->appendChild($nodePayableAmount);
            // end 30-34

            // begin 35 - 37
                $nodeInvoiceLine = $dom->CreateElement('cac:InvoiceLine'); 
                $root->appendChild($nodeInvoiceLine);
                foreach($data['encargoDetalle'] as $i => $item):
                    // Número de orden del Ítem
                    $nodeID = $dom->CreateElement('cbc:ID', $i+1); 
                    $nodeInvoiceLine->appendChild($nodeID);

                    // Cantidad y Unidad de medida por ítem
                    $cantidadVenta = round($item['cantidad'], 2);
                    $nodeInvoicedQuantity = $dom->CreateElement('cbc:InvoicedQuantity', $cantidadVenta); // cantidad de eso
                    $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCode', 'NIU')); // catálogo 3, NIU: UNIDAD (BIENES)
                    $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCodeListID', 'UN/ECE rec 20'));
                    $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCodeListAgencyName', 'United Nations Economic Commission for Europe'));
                    $nodeInvoiceLine->appendChild($nodeInvoicedQuantity);

                    // Valor de venta del ítem
                    $valorVenta = round($item['total']*0.82, 2);
                    $nodeLineExtensionAmount = $dom->CreateElement('cbc:LineExtensionAmount', $valorVenta);
                    $nodeLineExtensionAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeInvoiceLine->appendChild($nodeLineExtensionAmount);
                endforeach;
            // end 35-37

            // begin 38 - Precio de venta unitario por item y código
                /* catálogo 16,
                    01: Precio unitario (incluye el IGV)
                    02: Valor referencial unitario en operaciones no onerosas
                */
                
                foreach($data['encargoDetalle'] as $i => $item):
                    $nodePricingReference = $dom->CreateElement('cac:PricingReference'); 
                    $nodeInvoiceLine->appendChild($nodePricingReference);

                    $nodeAlternativeConditionPrice = $dom->CreateElement('cac:AlternativeConditionPrice'); 
                    $nodePricingReference->appendChild($nodeAlternativeConditionPrice);

                    // Precio de venta unitario por item y código
                    $precioUnitario = $item['precio']; // creo que ponemos la suma agrupado por ítems
                    $nodePriceAmount = $dom->CreateElement('cbc:PriceAmount', $precioUnitario); 
                    $nodePriceAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeAlternativeConditionPrice->appendChild($nodePriceAmount);

                    $nodePriceTypeCode = $dom->CreateElement('cbc:PriceTypeCode', '01'); // 01: Precio unitario (incluye el IGV)
                    $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
                    $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                    $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16'));
                    $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
                    $nodeAlternativeConditionPrice->appendChild($nodePriceTypeCode);
                endforeach;
            // end 38

            // begin 39 - Valor referencial unitario por ítem en operaciones no onerosas ** opcional - no aplica
            /*
                $nodeInvoiceLine = $dom->CreateElement('cbc:InvoiceLine');
                $root->appendChild($nodeInvoiceLine);
                
                for($i = 0; $i < $n; $i++):
                    $nodePricingReference = $dom->CreateElement('cac:PricingReference');
                    $nodeInvoiceLine->appendChild($nodePricingReference);
                    
                    $nodeAlternativeConditionPrice = $dom->CreateElement('cac:AlternativeConditionPrice');
                    $nodePricingReference->appendChild($nodeAlternativeConditionPrice);
                    
                    $nodePriceAmount = $dom->CreateElement('cbc:PriceAmount', 250.00);
                    $nodePriceAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeAlternativeConditionPrice->appendChild($nodePriceAmount);

                    $nodePriceTypeCode = $dom->CreateElement('cbc:PriceTypeCode', '02'); // 02: Valor referencial unitario en operaciones no onerosas
                    $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
                    $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
                    $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16'));
                    $nodeAlternativeConditionPrice->appendChild($nodePriceTypeCode);
                endfor;
            */
            // end 39

            // begin 40 - descuentos por items **opcional
                /* catálogo 53, 
                00: OTROS DESCUENTOS | Descuentos que afectan la base imponible del IGV Global e Item Todos
                01: Descuentos que no afectan la base imponible del IGV Global e Item Todos
                02: Descuentos globales que afectan la base imponible del IGV Global Todos
                03: Descuentos globales que no afectan la base imponible del IGV
                45: FISE Global Todos
                46: Recargo al consumo y/o propinas Global
                47: Cargos que afectan la base imponible del IGV
                */
                

                $nodeAllowanceCharge = $dom->CreateElement('cac:AllowanceCharge');
                $nodeInvoiceLine->appendChild($nodeAllowanceCharge);

                $nodeChargeIndicator = $dom->CreateElement('cbc:ChargeIndicator','false'); // true: Indicador del cargo, false: descuento del ítem 
                $nodeAllowanceCharge->appendChild($nodeChargeIndicator);

                $nodeAllowanceChargeReasonCode = $dom->CreateElement('cbc:AllowanceChargeReasonCode','00'); // 00: OTROS DESCUENTOS | Descuentos que afectan la base imponible del IGV
                $nodeAllowanceCharge->appendChild($nodeAllowanceChargeReasonCode);

                // <cbc:MultiplierFactorNumeric>0.10</cbc:MultiplierFactorNumeric>
                
                $nodeAmount = $dom->CreateElement('cbc:Amount', $data['subtotal']*0.10); // 10%
                $nodeAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda)); // catálogo 2
                $nodeAllowanceCharge->appendChild($nodeAmount);
                
                $nodeBaseAmount = $dom->CreateElement('cbc:BaseAmount', $data['subtotal']);
                $nodeBaseAmount->setAttributeNode(new \DOMAttr('currencyID',$tipoMoneda)); // catálogo 2
                $nodeAllowanceCharge->appendChild($nodeBaseAmount);
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
            
            // begin 42 - Afectación al IGV por ítem
                /* catálogo 7,
                10: Gravado - Operación Onerosa
                11: Gravado – Retiro por premio
                12: Gravado – Retiro por donación
                13: Gravado – Retiro
                14: Gravado – Retiro por publicidad
                15: Gravado – Bonificaciones
                16: Gravado – Retiro por entrega a trabajadores
                17: Gravado – IVAP
                20: Exonerado - Operación Onerosa
                21: Exonerado – Transferencia Gratuita
                30: Inafecto - Operación Onerosa
                31: Inafecto – Retiro por Bonificación
                32: Inafecto – Retiro
                33: Inafecto – Retiro por Muestras Médicas
                34: Inafecto - Retiro por Convenio Colectivo
                35: Inafecto – Retiro por premio
                36: Inafecto - Retiro por publicidad
                40: Exportación de bienes o servicios
                */
                
                foreach($data['encargoDetalle'] as $i => $item):
                    $nodeTaxTotal = $dom->CreateElement('cac:TaxTotal');
                    $nodeInvoiceLine->appendChild($nodeTaxTotal);
                    
                    $nodeTaxAmount = $dom->CreateElement('cac:TaxAmount', $item['total']*0.18);
                    $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxTotal->appendChild($nodeTaxAmount);
                    
                    $nodeTaxSubtotal = $dom->CreateElement('cac:TaxSubtotal');
                    $nodeTaxTotal->appendChild($nodeTaxSubtotal);
                    
                    $nodeTaxableAmount = $dom->CreateElement('cbc:TaxableAmount', $item['total']*0.82);
                    $nodeTaxableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxableAmount);
                    
                    $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount', $item['total']*0.18);
                    $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
                    $nodeTaxSubtotal->appendChild($nodeTaxAmount);
                    
                    $nodeTaxCategory = $dom->CreateElement('cbc:TaxCategory');
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
                endforeach;
            // end 42

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

                    $nodeTaxCategory = $dom->CreateElement('cbc:TaxCategory');
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
                foreach($data['encargoDetalle'] as $i => $item):
                    $nodeItem = $dom->CreateElement('cac:Item');
                    $nodeInvoiceLine->appendChild($nodeItem);
                    
                    $nodeDescription = $dom->CreateElement('cbc:Description','<![CDATA['.$item['descripcion'].']]>');
                    $nodeItem->appendChild($nodeDescription);
                endforeach;
            // end 44

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
            
            // 48 - Valor unitario del ítem
                foreach($data['encargoDetalle'] as $i => $item):
                    $nodePrice = $dom->CreateElement('cac:Price');
                    $nodeInvoiceLine->appendChild($nodePrice);

                    $precioUnitario = $item['total']*0.82; // no debe incluir el igp
                    $nodePriceAmount = $dom->CreateElement('cac:PriceAmount', $precioUnitario); 
                    $nodePriceAmount->setAttributeNode(new \DOMAttr('CurrencyID', $tipoMoneda));
                    $nodePrice->appendChild($nodePriceAmount);
                endforeach;
            


            $dom->appendChild($root);

            $file = $data['emisorRUC'].'_01_'.$data['emisorNumeroDocumentoElectronico'].'.xml';
            $xml_file_name = base_path('public/pruebas/').$file;
            $dom->save($xml_file_name);
        }
    }

    public function getQR($data) {
        $hash = "";
        $value = $data['emisorRUC'].'|03|'.$data['emisorNumeroDocumentoElectronico'].'|IGV|'.$data['subtotal'].'|'.$data['emisorFechaDocumentoElectronico'].'|1|'.$data['adquirienteRUC'].'|'.$hash;
        $img = base_path('public/pruebas/qrcode.png');
        return ['value' => $value, 'img' => $img];
    }
}
