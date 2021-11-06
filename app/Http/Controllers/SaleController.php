<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Business\Sede;
use App\Business\Carga;
use App\Business\Agencia;
use App\Business\Documento;
use App\Business\Encargo;
use App\Business\Cliente;
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
                $element = explode(",",$item);
                if ($element[0] !== "--"){
                    $peso = $element[1];
                    $cantidad = $element[2];
                    $precio = $element[3];
                    $carga = Carga::find($element[0]);
                    if ($carga) {
                        $total = number_format($cantidad*$precio*$peso, 2, '.', '');
                        array_push($stack, [
                            'carga_id' => $carga->id,
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

        // registrar o actualizar el cliente
        $documento_fecha = date('Y-m-d');
        if ($documento->alias === 'G') {
            // guía de remisión
            $insertCliente = [
                'documento' => $data['docRecibe'],
                'razon_social' => $data['nombreRecibe'],
                'direccion' => '',
            ];
        } else if ($documento->alias === 'B' || $documento->alias === 'F') {
            // boletas y facturas
            $insertCliente = [
                'documento' => $data['docEnvia'],
                'razon_social' => $data['nombreEnvia'],
                'direccion' => '',
            ];
        } else {
            // hey, no deberías estar aquí, el validador dice que hay campos obligatorios!
            $documento_fecha = "";
            $insertCliente = [];
        }
        if (strlen($data['encargoId']) > 0) {
            $clienteId = $data['clienteId'];
        } else {
            $cliente = (Cliente::create($insertCliente));
            $clienteId = $cliente->id;
        }
        
        // registrar o actualizar el encargo
        $emisorId = $data['agenciaOrigen']; // agencia que está en sesión. hacer luego
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
            
            'emisor' => new ObjectId($emisorId),
            'cliente_id' => new ObjectId($clienteId),
            'medio_pago' => $data['medioPago'],
            'documento_id' => new ObjectId($data['documento']),
            'documento_serie' => $data['documentoSerie'],
            'documento_numero' => $data['documentoNumero'],
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
            $documentoNumero = $data['documentoNumero'];
        } else {
            $encargo = Encargo::create($insertEncargo);
            $encargoId = $encargo['id'];
            $ObjectId = new ObjectId($encargoId);
            $documentoNumero = sprintf("%0".env('ZEROFILL',6)."d",Encargo::getNextSequence($encargoId, $data['documentoSerie']));
            $encargo = Encargo::where('_id', $ObjectId)->update(['documento_numero' => $documentoNumero]);
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
                'clienteId' => $clienteId, 
                'documentoNumero' => $documentoNumero
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
            PDF::MultiCell($width, $height, $data['empresaComercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRazonSocial'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaDireccionFiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRuc'], $border, $align_center, 1, 0, $x, $y);
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
            PDF::MultiCell($width, $height, "OPERADOR: ".$data['clienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisorFechaDocumentoElectronico'], 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: 00:00:00" . $data['emisorHoraDocumentoElectronico'], 'B', 1, 'R', 0);
            // -------------
            
            $dniruc = (strlen($data['clienteDocumento']) === 8) ? 'DNI/CE' : 'RUC';
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['clienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, "$dniruc: " . $data['clienteDocumento'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $fontSizeGrande);
            foreach($data['consigna'] as $nombre) {
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            }

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
            foreach($data['encargoDetalle'] as $encargo){
                $importeTotal += $encargo['total'];
                PDF::MultiCell(24, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(14, $height, $encargo['cantidad'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(12, $height, $encargo['precio'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, $encargo['total'], '', $align_center, 1, 0, $x, $y);
                PDF::Ln();
            }
            
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

            $qr = base_path('public/pruebas/qrcode.png');
            QRcode::png("Hola mundo!", $qr, 'L', 4, 2);
            PDF::Image($qr, '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "Representación impresa de ".$data['emisorTipoDocumentoElectronico'].". Puede descargarlo y/o consultarlo desde www.enlacesbus.com.pe/see", $border, $align_center, 1, 0, $x, $y);
            PDF::Ln();
            PDF::MultiCell($width, $height, env('EMPRESA_DISCLAIMER',''), '', $align_left, 1, 0, $x, $y);
            $year = substr($data['emisorFechaDocumentoElectronico'], -4);
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
            PDF::MultiCell($width, $height, $data['empresaComercial'], '', $align_center,  1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRazonSocial'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaDireccionFiscal'], '', $align_center, 1, 0, $x, $y);
            PDF::Ln();

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, $data['empresaRuc'], $border, $align_center, 1, 0, $x, $y);
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
            $qr = base_path('public/pruebas/qrcode.png');
            QRcode::png("Hola mundo!", $qr, 'L', 4, 2);
            PDF::Image($qr, '30', '', 20, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            PDF::Ln(20);

            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "OPERADOR: ".$data['clienteRazonSocial'], 'T', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            PDF::Cell($width/2, $height, "FECHA: " . $data['emisorFechaDocumentoElectronico'], 'B', 0, 'L', 0);
            PDF::Cell($width/2, $height, "HORA: 00:00:00" . $data['emisorHoraDocumentoElectronico'], 'B', 1, 'R', 0);
            // -------------
            
            $dniruc = (strlen($data['clienteDocumento']) === 8) ? 'DNI/CE' : 'RUC';
            PDF::SetFont('times', '', $fontSizeRegular);
            PDF::MultiCell($width, $height, "CLIENTE: ".$data['clienteRazonSocial'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();

            PDF::MultiCell($width, $height, "$dniruc: " . $data['clienteDocumento'], '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            
            PDF::MultiCell($width, $height, "CONSIGNA:", '', $align_left, 1, 0, $x, $y);
            PDF::Ln();
            // -------------

            PDF::SetFont('times', 'B', $fontSizeGrande);
            foreach($data['consigna'] as $nombre) {
                PDF::MultiCell($width, $height, $nombre, '', $align_left, 1, 0, $x, $y);
                PDF::Ln();
            }

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
            foreach($data['encargoDetalle'] as $encargo){
                $importeTotal += $encargo['total'];
                PDF::MultiCell(24, $height, $encargo['descripcion'], '', $align_left, 1, 0, $x, $y);
                PDF::MultiCell(14, $height, $encargo['cantidad'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(12, $height, $encargo['precio'], '', $align_center, 1, 0, $x, $y);
                PDF::MultiCell(10, $height, $encargo['total'], '', $align_center, 1, 0, $x, $y);
                PDF::Ln();
            }
            
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
        // variables
        $X509Certificate = 'MIIF9TCCBN2gAwIBAgIGAK0oRTg/MA0GCSqGSIb3DQEBCwUAMFkxCzAJBgNVBAYTAlRSMUowSAYDVQQDDEFNYWxpIE3DvGjDvHIgRWxla3Ryb25payBTZXJ0aWZpa2EgSGl6bWV0IFNhxJ9sYXnEsWPEsXPEsSAtIFRlc3QgMTAeFw0wOTEwMjAxMTM3MTJaFw0xNDEwMTkxMTM3MTJaMIGgMRowGAYDVQQLDBFHZW5lbCBNw7xkw7xybMO8azEUMBIGA1UEBRMLMTAwMDAwMDAwMDIxbDBqBgNVBAMMY0F5ZMSxbiBHcm91cCAtIFR1cml6bSDEsHRoYWxhdCDEsGhyYWNhdCBUZWtzdGlsIMSwbsWfYWF0IFBhemFyiMwtPnC2DRjdsyGv3bxwRZr9wXMRrMNwRjyFe9JPA7bSscEgaXwzDUG5FCvfS/PNT+XCce+VECAx6Q3R1ZRSA49fYz6tDB4Ia5HVBXZODmrCs26XisHF6kuS5N/yGg8E7VC1BRr/SmxXeLTdjQYAfo7lxCz4dT6wP5TOiBvF+lyWW1bi9nbliXyb/e5HjCp4k/ra9LTskjbY/Ukl5O8G9JEAViZkjvxDX7T0yVRHgMGiioIKVMwU6Lrtln607BNurLwED0OeoZ4wBgkBiB5vXofreXrfN2pHZ2=';

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

        // firma digital
        $nodeUBLExtensions = $dom->createElement('ext:UBLExtensions');
        $root->appendChild($nodeUBLExtensions);

        $nodeUBLExtensions1 = $dom->createElement('ext:UBLExtensions');
        $nodeUBLExtensions->appendChild($nodeUBLExtensions1);

        $nodeExtensionContent = $dom->createElement('ext:ExtensionContent');
        $nodeUBLExtensions1->appendChild($nodeExtensionContent);

        $nodeSignature = $dom->createElement('ds:Signature');
        $nodeSignature->setAttributeNode(new \DOMAttr('Id','signatureKG'));
        $nodeExtensionContent->appendChild($nodeSignature);

        $nodeSignedInfo = $dom->createElement('ds:SignedInfo');
        $nodeSignature->appendChild($nodeSignedInfo);

        $nodeCanonicalizationMethod = $dom->createElement('ds:CanonicalizationMethod');
        $nodeCanonicalizationMethod->setAttributeNode(new \DOMAttr('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n20010315#WithComments'));
        $nodeSignedInfo->appendChild($nodeCanonicalizationMethod);

        $nodeSignatureMethod = $dom->createElement('ds:SignatureMethod');
        $nodeSignatureMethod->setAttributeNode(new \DOMAttr('Algorithm', 'http://www.w3.org/2000/09/xmldsig#dsa-sha1'));
        $nodeSignedInfo->appendChild($nodeSignatureMethod);

        $nodeReference = $dom->createElement('ds:Reference');
        $nodeReference->setAttributeNode(new \DOMAttr('URI', ''));
        $nodeSignedInfo->appendChild($nodeReference);

        $nodeTransforms = $dom->createElement('ds:Transforms');
        $nodeReference->appendChild($nodeTransforms);

        $nodeTransforms1 = $dom->createElement('ds:Transforms');
        $nodeTransforms1->setAttributeNode(new \DOMAttr('Algorithm', 'http://www.w3.org/2000/09/xmldsig#envelopedsignature'));
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

        // UBL
        $nodeUBLVersionID = $dom->createElement('cbc:UBLVersionID', '2.1');
        $root->appendChild($nodeUBLVersionID);
        $nodeCustomizationID = $dom->createElement('cbc:CustomizationID', '2.0');
        $root->appendChild($nodeCustomizationID);

        // codificicación de tipo de operación **
        $tipoOperacionCodigo = '0101';
        $tipoOperacionCatalogo = 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17';
        $tipoOperacionLeyenda = 'SUNAT:Identificador de Tipo de Operación';
        $nodeProfileID = $dom->createElement('cbc:ProfileID', $tipoOperacionCodigo);
        $nodeProfileID->setAttributeNode(new \DOMAttr('schemeName', $tipoOperacionLeyenda));
        $nodeProfileID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
        $nodeProfileID->setAttributeNode(new \DOMAttr('schemeURI', $tipoOperacionCatalogo));
        $root->appendChild($nodeProfileID);

        // serie del documento
        $facturaCorrelativo = 'F002-10';
        $nodeID = $dom->createElement('cbc:ID', $facturaCorrelativo);
        $root->appendChild($nodeID);

        // fecha de emisión
        $facturaFechaEmision = '2017-04-28';
        $nodeIssueDate = $dom->createElement('cbc:IssueDate', $facturaFechaEmision);
        $root->appendChild($nodeIssueDate);

        // hora de emisión
        $facturaHoraEmision = '11:40:21';
        $nodeIssueTime = $dom->createElement('cbc:IssueTime', $facturaHoraEmision);
        $root->appendChild($nodeIssueTime);

        // hora de emisión
        $facturaFechaVence = '2017-05-28';
        $nodeDueDate = $dom->createElement('cbc:DueDate', $facturaFechaVence);
        $root->appendChild($nodeDueDate);

        // hora de emisión
        $facturaFechaVence = '2017-05-28';
        $nodeDueDate = $dom->createElement('cbc:DueDate', $facturaFechaVence);
        $root->appendChild($nodeDueDate);

        // tipo de documento (Factura) **
        $nodeInvoiceTypeCode = $dom->createElement('cbc:InvoiceTypeCode', '01');
        $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
        $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Identificador de Tipo de Documento'));
        $nodeInvoiceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01'));
        $root->appendChild($nodeInvoiceTypeCode);

        // leyendas
        $n = 5;
        for($i = 0; $i < $n; $i++):
            $formatterES = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
            $valorNumericoLetras = $formatterES->format(123); // 123.45
            $nodeNote = $dom->createElement('cbc:Note', mb_strtoupper($valorNumericoLetras) ." CON 45/100 SOLES");
            $nodeNote->setAttributeNode(new \DOMAttr('languageLocaleID', '1000'));
            $root->appendChild($nodeNote);
        endfor;

        $codigoInternoSoftware = 'este código viene de mi ERP';
        $nodeNote1 = $dom->createElement('cbc:Note', $codigoInternoSoftware);
        $nodeNote1->setAttributeNode(new \DOMAttr('languageLocaleID', '3000'));
        $root->appendChild($nodeNote1);
        
        // tipo de moneda
        $tipoMoneda = 'PEN';
        $nodeDocumentCurrencyCode = $dom->createElement('cbc:DocumentCurrencyCode', $tipoMoneda);
        $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listID', 'ISO 4217 Alpha'));
        $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listName', 'Currency'));
        $nodeDocumentCurrencyCode->setAttributeNode(new \DOMAttr('listAgencyName', 'United Nations Economic Commission for Europe'));
        $root->appendChild($nodeDocumentCurrencyCode);

        /*
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

        // EL EMISOR
        $nodeAccountingSupplierParty = $dom->createElement('cac:AccountingSupplierParty');
        $root->appendChild($nodeAccountingSupplierParty);

        $nodeParty = $dom->createElement('cac:Party');
        $nodeAccountingSupplierParty->appendChild($nodeParty);

        $nodePartyName = $dom->createElement('cac:PartyName');
        $nodeParty->appendChild($nodePartyName);

        // Nombre Comercial del emisor
        $emisorNombreComersial = 'Plaza Vea';
        $nodeName = $dom->createElement('cbc:Name', '<![CDATA['.$emisorNombreComersial.']]>');
        $nodePartyName->appendChild($nodeName);

        $nodePartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
        $nodeParty->appendChild($nodePartyTaxScheme);

        // Apellidos y nombres, denominación o razón social del emisor
        $emisorRazonSocial = 'Mercados Peruanos SAC.';
        $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', '<![CDATA['.$emisorRazonSocial.']]>');
        $nodePartyTaxScheme->appendChild($nodeRegistrationName);

        // Tipo y Número de RUC del emisor
        $emisorRUC = '20100113612';
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

        $nodeRegistrationAddress = $dom->createElement('cac:RegistrationAddress');
        $nodePartyTaxScheme->appendChild($nodeRegistrationAddress);

        // Código del domicilio fiscal o de local anexo del emisor
        $emisorUbigeo = '0001';
        $nodeAddressTypeCode = $dom->createElement('cbc:AddressTypeCode', $emisorUbigeo);
        $nodeRegistrationAddress->appendChild($nodeAddressTypeCode);

        // EL ADQUIRIENTE
        $nodeAccountingCustomerParty = $dom->createElement('cac:AccountingCustomerParty');
        $root->appendChild($nodeAccountingCustomerParty);

        $nodeParty = $dom->createElement('cac:Party');
        $nodeAccountingCustomerParty->appendChild($nodeParty);

        $nodePartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
        $nodeParty->appendChild($nodePartyTaxScheme);

        // Apellidos y nombres, denominación o razón social del adquirente o usuario
        $adquirienteRazonSocial = 'CECI FARMA IMPORT S.R.L.';
        $nodeRegistrationName = $dom->createElement('cbc:RegistrationName', '<![CDATA['.$adquirienteRazonSocial.']]>');
        $nodePartyTaxScheme->appendChild($nodeRegistrationName);

        // Tipo y número de documento de identidad del adquirente o usuario
        $adquirienteRUC = '20102420706';
        $nodeCompanyID = $dom->createElement('cbc:CompanyID', '<![CDATA['.$adquirienteRUC.']]>');
        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeID', '6'));
        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeName', 'SUNAT:Identificador de Documento de Identidad'));
        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeAgencyName', 'PE:SUNAT'));
        $nodeCompanyID->setAttributeNode(new \DOMAttr('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06'));
        $nodePartyTaxScheme->appendChild($nodeCompanyID);

        $nodeTaxScheme = $dom->createElement('cac:TaxScheme');
        $nodePartyTaxScheme->appendChild($nodeTaxScheme);

        $nodeID = $dom->createElement('cbc:ID', '-');
        $nodeTaxScheme->appendChild($nodeID);

        // 20, 21 omitidos


        $nodeTaxTotal = $dom->CreateElement('cac:TaxTotal');
        $root->appendChild($nodeTaxTotal);

        $montoTotal = 1439.48;
        $montoTotalImpuesto = round($montoTotal * 0.18, 2);
        $nodeTaxAmount = $dom->CreateElement('cbc:TaxAmount',$montoTotalImpuesto);
        $nodeTaxAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
        $nodeTaxTotal->appendChild($nodeTaxAmount);

        for($i = 0; $i < $n; $i++):
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

            $nodeID = $dom->CreateElement('cbc:ID','1000');
            $nodeID->setAttributeNode(new \DOMAttr('schemeID', 'UN/ECE 5305'));
            $nodeID->setAttributeNode(new \DOMAttr('schemeAgencyID', '6'));
            $nodeTaxCategory->appendChild($nodeID);

            $nodeName = $dom->CreateElement('cbc:Name','IGV'); 
            $nodeTaxCategory->appendChild($nodeName);

            $nodeTaxTypeCode = $dom->CreateElement('cbc:TaxTypeCode','VAT'); 
            $nodeTaxCategory->appendChild($nodeTaxTypeCode);
            // revisar exonerados
        endfor;

        $nodeLegalMonetaryTotal = $dom->CreateElement('cac:LegalMonetaryTotal'); 
        $root->appendChild($nodeLegalMonetaryTotal);

        // Total valor de venta
        $nodeLineExtensionAmount = $dom->CreateElement('cbc:LineExtensionAmount', '1439.48'); 
        $nodeLineExtensionAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
        $nodeLegalMonetaryTotal->appendChild($nodeLineExtensionAmount);

        // Total precio de venta (incluye impuestos)
        $nodeTaxInclusiveAmount = $dom->CreateElement('cbc:TaxInclusiveAmount', '1698.59'); 
        $nodeTaxInclusiveAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
        $nodeLegalMonetaryTotal->appendChild($nodeTaxInclusiveAmount);

        // Monto total de descuentos del comprobante
        $nodeAllowanceTotalAmount = $dom->CreateElement('cbc:AllowanceTotalAmount', '60.00'); 
        $nodeAllowanceTotalAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
        $nodeLegalMonetaryTotal->appendChild($nodeAllowanceTotalAmount);

        // Monto total de otros cargos del comprobante
        $nodeChargeTotalAmount = $dom->CreateElement('cbc:ChargeTotalAmount', '320.00'); 
        $nodeChargeTotalAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
        $nodeLegalMonetaryTotal->appendChild($nodeChargeTotalAmount);

        // Monto total de otros cargos del comprobante
        $nodePrepaidAmount = $dom->CreateElement('cbc:PrepaidAmount', '100.00'); 
        $nodePrepaidAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
        $nodeLegalMonetaryTotal->appendChild($nodePrepaidAmount);

        // Importe total de la venta, cesión en uso o del servicio prestado
        $nodePayableAmount = $dom->CreateElement('cbc:PayableAmount', '1858.59'); 
        $nodePayableAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
        $nodeLegalMonetaryTotal->appendChild($nodePayableAmount);

        // 35 - 37
        $nodeInvoiceLine = $dom->CreateElement('cac:InvoiceLine'); 
        $root->appendChild($nodeInvoiceLine);
        for($i = 0; $i < $n; $i++):
            // Número de orden del Ítem
            $nodeID = $dom->CreateElement('cbc:ID', '1'); 
            $nodeInvoiceLine->appendChild($nodeID);

            // Cantidad y Unidad de medida por ítem
            $nodeInvoicedQuantity = $dom->CreateElement('cbc:InvoicedQuantity', '50');
            $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCode', 'CS'));
            $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCodeListID', 'UN/ECE rec 20'));
            $nodeInvoicedQuantity->setAttributeNode(new \DOMAttr('unitCodeListAgencyName', 'United Nations Economic Commission for Europe'));
            $nodeInvoiceLine->appendChild($nodeInvoicedQuantity);

            // Valor de venta del ítem
            $nodeLineExtensionAmount = $dom->CreateElement('cbc:LineExtensionAmount', '1439.48');
            $nodeLineExtensionAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
            $nodeInvoiceLine->appendChild($nodeLineExtensionAmount);
        endfor;

        // Precio de venta unitario por item y código
        $nodeInvoiceLine = $dom->CreateElement('cac:InvoiceLine'); 
        $root->appendChild($nodeInvoiceLine);
        for($i = 0; $i < $n; $i++):
            $nodePricingReference = $dom->CreateElement('cac:PricingReference'); 
            $nodeInvoiceLine->appendChild($nodePricingReference);

            $nodeAlternativeConditionPrice = $dom->CreateElement('cac:AlternativeConditionPrice'); 
            $nodeInvoiceLine->appendChild($nodeAlternativeConditionPrice);

            // Precio de venta unitario por item y código
            $nodePriceAmount = $dom->CreateElement('cbc:PriceAmount', '34.99'); 
            $nodePriceAmount->setAttributeNode(new \DOMAttr('currencyID', $tipoMoneda));
            $nodeAlternativeConditionPrice->appendChild($nodePriceAmount);

            $nodePriceTypeCode = $dom->CreateElement('cbc:PriceTypeCode', '01'); 
            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listAgencyName', 'PE:SUNAT'));
            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16'));
            $nodePriceTypeCode->setAttributeNode(new \DOMAttr('listName', 'SUNAT:Indicador de Tipo de Precio'));
            $nodeAlternativeConditionPrice->appendChild($nodePriceTypeCode);
        endfor;

        // escribir para 39

        $dom->appendChild($root);

        $xml_file_name = base_path('public/pruebas/factura.xml');
        $dom->save($xml_file_name);
    }
}
