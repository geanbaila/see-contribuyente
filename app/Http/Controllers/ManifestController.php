<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Salida;
use App\Business\Sede;
use App\Business\Agencia;
use App\Business\Encargo;
use App\Business\Manifiesto;
use MongoDB\BSON\ObjectId;
use PDF;

class ManifestController extends Controller
{
    public function list2() {
        $w = date('w');
        $ahora = date('H:i');
        $columna = $this->getDay($w, 0);
        $salida = Salida::all();
        $sede = Sede::all();
        $data = [
            'salida' => $salida,
            'sede' => $sede,
            'columna' => $columna,
            'ahora' => $ahora,
            'menu_manifiesto_active' => 'active',
        ];
        return view('manifest.list')->with($data);
    }
    
    public function list() {
        $en_manifiesto = new ObjectId('61af909ad3f9efe2cb27e8be');
        $encargo = Encargo::where('estado', '!=', $en_manifiesto)->get()->sortBy(['documento_fecha','documento_hora']);
        $manifiesto = Manifiesto::all()->sortBy('created_at');
        $agencia = Agencia::all();
        $data = [
            'origen' => $agencia,
            'encargo' => $encargo, 
            'manifiesto' => $manifiesto,
            'menu_manifiesto_active' => 'active',
        ];
        return view('manifest.list')->with($data);
    }

    public function escribirPDF($manifiesto) {
        PDF::SetTitle($manifiesto->nombre_archivo);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        PDF::AddPage();
        
        
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0);
        $font_size_grande = 9;
        $font_size_gigante = 10;
        $font_size_regular = 9;
        
        $border = '1';
        $align_center = 'C';
        $align_left = 'L';
        $align_right = 'R';
        $height = '';
        $y = '';
        $x = ''; // 5
        PDF::SetFont('times', '', $font_size_regular);
        PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');

        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        PDF::Cell(24+15+15+40+45+40+10, $height, 'MANIFIESTO DE ENCOMIENDAS Y CARGAS', '', 0, 'C', 0);
        PDF::Ln();
        PDF::Ln();
        
        PDF::Cell(24, $height, 'Despachador:', '', 0, 'R', 1);
        PDF::Cell(15+15+40, $height, ' José María Vargas López ', '', 0, 'L', 1);
        PDF::Cell(40+45+10, $height, 'Fecha: ' . $manifiesto->fecha .' '. $manifiesto->hora, '', 0, 'R', 1);
        PDF::Ln();
        PDF::Ln();

        PDF::Cell(24, $height, "CPE", $border, 0, 'L', 1);
        PDF::Cell(15, $height, "Por pagar", $border, 0, 'L', 1);
        PDF::Cell(15, $height, "Pagado", $border, 0, 'L', 1);
        PDF::Cell(40, $height, "Consigna", $border, 0, 'L', 1);
        PDF::Cell(40, $height, "Destino", $border, 0, 'L', 1);
        PDF::Cell(45, $height, "Descripcion", $border, 0, 'L', 1);
        PDF::Cell(10, $height, "Ítems", $border, 0, 'L', 1);
        PDF::Ln();

        $por_pagar = 0;
        $pagado = 0;
        foreach($manifiesto->detalles as $item):
            $por_pagar += 0;
            $pagado += $item['oferta'];
            PDF::Cell(24, $height, $item['documento_serie'] . '-' . $item['documento_correlativo'], '', 0, 'L', 1);
            PDF::Cell(15, $height, number_format(0, 2, '.', ''), '', 0, 'L', 1);
            PDF::Cell(15, $height, number_format($item['oferta'], 2, '.', ''), '', 0, 'L', 1);
            PDF::Cell(40, $height, "-", '', 0, 'L', 1, '', 0);
            PDF::Cell(40, $height, $item['agencia_destino'], '', 0, 'L', 1);
            PDF::Cell(45, $height, "Descripcion", '', 0, 'L', 1);
            PDF::Cell(10, $height, $item['cantidad_item'], '', 0, 'L', 1);
            PDF::Ln();
        endforeach;

        PDF::Cell(24+15+15+40+45+40+10, $height, '', 'T', 0, 'L', 1);
        PDF::Ln();
        PDF::Cell(24, $height, 'Subtotal:', '', 0, 'R', 1);
        PDF::Cell(15, $height, number_format($por_pagar, 2, '.', ''), '', 0, 'L', 1);
        PDF::Cell(15, $height, number_format($pagado, 2, '.', ''), '', 0, 'L', 1);
        PDF::Cell(40, $height, '', '', 0, 'L', 1, '', 0);
        PDF::Cell(40, $height, '', '', 0, 'L', 1);
        PDF::Cell(45, $height, 'Total de CPE enviados:', '', 0, 'R', 1);
        PDF::Cell(10, $height, '21', '', 0, 'L', 1);
        PDF::Ln();

        PDF::Cell(24, $height, 'Total general:', '', 0, 'R', 1);
        PDF::Cell(15, $height, number_format($por_pagar + $pagado, 2, '.', ''), '', 0, 'L', 1);
        PDF::Cell(15, $height, '', '', 0, 'L', 1);
        PDF::Cell(40, $height, '', '', 0, 'L', 1, '', 0);
        PDF::Cell(40, $height, '', '', 0, 'L', 1);
        PDF::Cell(45, $height, '', '', 0, 'L', 1);
        PDF::Cell(10, $height, '', '', 0, 'L', 1);
        PDF::Ln();
        
        PDF::Cell(24, $height, 'Resumen:', '', 0, 'R', 1);
        PDF::Ln();

        PDF::Cell(24, $height, 50, '', 0, 'R', 1);
        PDF::Cell(45, $height, 'XXXXXX', '', 0, 'L', 1);
        PDF::Ln();
        PDF::Cell(24, $height, 50, '', 0, 'R', 1);
        PDF::Cell(45, $height, 'XXXXXX', '', 0, 'L', 1);
        PDF::Ln();
        PDF::Cell(24, $height, 50, '', 0, 'R', 1);
        PDF::Cell(45, $height, 'XXXXXX', '', 0, 'L', 1);
        PDF::Ln();

        PDF::SetY(10);
        PDF::SetFont('times', 'I', $font_size_regular);
        PDF::Cell(0, 10, 'pág. '.PDF::getAliasNumPage().'/'.PDF::getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    
        list($year, $month, $day) = explode('-', $manifiesto->fecha); // yyyy-mm-dd
        $tree = 'resources/manifiesto/' . $year . '/' . $month . '/' . $day;
        $estructura = base_path('public/'.$tree);
        if(!@mkdir($estructura, 0777, true)) {
            if (file_exists($estructura . "/" . $manifiesto->nombre_archivo)) { @unlink($estructura . "/" . $manifiesto->nombre_archivo); }
        }

        $output = public_path($manifiesto->url_documento_pdf);
        PDF::Output($output, 'F');
        PDF::reset();
        
    }
}
