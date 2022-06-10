<?php

//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
//require_once('tcpdf_barcodes_1d.php');

class Reporte_Ticketventa {

    public function create($ventas_id) {
        
        $ModelVentas = new Model_Venta();
        $ModelVentaItems = new Model_Ventaitems();

        $infoVenta = $ModelVentas->getInfoVenta($ventas_id);
        $items = $ModelVentaItems->getItemsDespachados($ventas_id);

        $largoBase = "95";
        $largo = 0;
        if($items!=null){
                $factor = $items->count();
                $largo = $factor*3;
        }
        


        
        // create new PDF document
        $pdf = new TCPDF("P", "mm", array(80,$largoBase+$largo), true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ECO + Clean');
        $pdf->SetTitle('Venta '.$ventas_id);
        $pdf->SetSubject('Sistema ECO + Clean');
        $pdf->SetKeywords('');

// set default header data             
        $pdf->SetHeaderData(PDF_HEADER_LOGO, 20, "ECO+CLEAN PROVEEDORA DE LIMPIEZA", "R.F.C. AAVR810904E39, CURP AAVR810904MOCLSS07                            AV. PROLETARIADO MEXICANO, MANZANA “M” NUMERO 3 INFONAVIT PRIMERO DE MAYO, C.P. 68020, OAXACA, OAX.            “ESTA NOTA DE VENTA ES CONSIDERADA EN EL CFDI DE VENTAS AL PUBLICO EN GENERAL DEL MES”", array(0, 0, 0), array(0, 24, 128));
        $pdf->setFooterData(array(0, 0, 0), array(0, 64, 128));

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 4));//PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(5, 15, 5);
        $pdf->SetHeaderMargin(2);//PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(0);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/spa.php')) {
            require_once(dirname(__FILE__) . '/lang/spa.php');
            $pdf->setLanguageArray($l);
        }

// ---------------------------------------------------------
// set default font subsetting mode
        $pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

// set text shadow effect
        //$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
        


// Comenzamos con el procesamiento de la información de la manda

//$barcodeobj = new TCPDFBarcode($infoManda->id, 'C128');

// output the barcode as HTML object
//$codigo = $barcodeobj->getBarcodePNG(2, 30, array(0,0,0));

$itemsString="";
$contador=0;
$iva_total = 0;
$cantidad_total = 0;

if($items!=null){
        foreach($items as $item){
                $cantidad_total+=$item->cantidad;
                $contador++;
                $itemsString .= '<tr style="margin:0px;padding:0px;">
                <td style="width:5%;text-align:left;border:solid 1px gray;margin:0px;padding:0px ">'.$contador.'</td>
                <td style="width:30%;text-align:left;border:solid 1px gray;margin:0px;padding:0px">'.$item->nombre.'</td>
                <td style="width:7%;text-align:center;border:solid 1px gray;margin:0px;padding:0px">'.$item->cantidad.'</td>
                <td style="width:12%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->precio,2).'</td>
                <td style="width:14%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->subtotal+$item->descuento,2).'</td>
                <td style="width:10%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->descuento,2).'</td>
                <td style="width:13%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->iva_monto,2).'</td>
                <td style="width:14%;text-align:right;border:solid 1px gray;">'.number_format($item->total,2).'</td>
                </tr>';
                $iva_total += $item->iva_monto;
        }
}


     
        $now = date("Y-m-d H:i:s");
        if($items!=null)
                $total = number_format($infoVenta->total,2);
        else
                $total = 0;

        require (dirname(getcwd()) . '/library/NumeroALetras.php');        
        $formatter = new NumeroALetras();
        $formatter->conector = 'Y';
        $TotalLetra =  $formatter->toMoney($infoVenta->total, 2, 'pesos', 'centavos')." M.N"; 
               
        $fecha_venta = date("Y-m-d",strtotime($infoVenta->fecha_registro));
        $hora_venta = date("H:i:s",strtotime($infoVenta->fecha_registro));
        $cajero = $infoVenta->empleado;    
        $forma_pago = strtoupper($infoVenta->forma_pago);
        $pago = number_format($infoVenta->pago,2);
        $cambio = number_format($infoVenta->cambio,2);    
        $iva_total = number_format($iva_total,2);
        $html = <<<EOD
        <table style="width:100%;font-size:4pt">
                <tr>
                        <td style="width:40%;border:dotted 1px gray;font-size:4.5pt">EXPEDIDO EN, AV. PROLETARIADO MEXICANO MANZANA “M” NUMERO 3 INFONAVIT PRIMERO DE MAYO, C.P. 68020 OAXACA, OAX. </td>
                        <td style="width:60%;border:dotted 1px gray;"> 
                        <table style="width:100%">
                                <tr>
                                        <td style="width:30%">CAJERO</td><td style="width:70%"><b>$cajero</b></td>
                                </tr>
                                <tr>
                                        <td style="width:30%">CAJA</td><td style="width:70%"><b>1</b></td>
                                </tr>
                                <tr>
                                        <td style="width:30'%">FOLIO</td><td style="width:70%"><b>$infoVenta->id</b></td>
                                </tr>
                                <tr>
                                        <td style="width:30%">FECHA</td><td style="width:70%"><b>$fecha_venta</b></td>
                                </tr>
                                <tr>
                                        <td style="width:30%">HORA</td><td style="width:70%"><b>$hora_venta</b></td>                                                
                                </tr>
                        </table>                        
                        </td>
                </tr>
                <tr>
                        <td colspan="2" style="text-align:center;font-size:8px;color:gray"><div></div><b>Artículos</b></td>
                </tr>
        </table>     
        <table style="font-size:4pt;width:100%;border:solid 1px gray;margin-left:-5px;" border="">                
                        <tr style="font-weight:bold;">
                                <td style="width:5%;text-align:center">#</td>
                                <td style="width:30%;text-align:center">Descripcion.</td>
                                <td style="width:8%;text-align:center">Can</td>
                                <td style="width:12%;text-align:center">P.U.</td>  
                                <td style="width:14%;text-align:center">Sub.</td>  
                                <td style="width:10%;text-align:center">Des.</td>
                                <td style="width:12%;text-align:center">IVA</td>
                                <td style="width:14%;text-align:center">Total</td>
                        </tr>   
                        $itemsString             
        </table>
        <div></div>
        <table style="width:100%; font-size:8pt.;">
                <tr>
                        <td style="text-align:right;width:60%">                        
                                TOTAL:
                        </td>
                        <td style="text-align:right;width:40%;">
                        <b>$ $total</b>
                        </td>                        
                </tr>
                <tr>
                        <td style="text-align:right;width:60%">                        
                                $forma_pago:
                        </td>
                        <td style="text-align:right;width:40%;">
                        <b>$ $pago</b>
                        </td>                        
                </tr>
                <tr>
                        <td style="text-align:right;width:60%">                        
                                CAMBIO:
                        </td>
                        <td style="text-align:right;width:40%;">
                        <b>$ $cambio</b>
                        </td>                        
                </tr>                
        </table>
        <div></div>
        <table style="width:100%;border:dotted .5px gray;font-size:5pt;">
        <tr>
                <td colspan="2" style="text-align:center;width:100%;">
                
                <b>($TotalLetra)</b>
                </td>
        </tr>
        <tr>
                <td colspan="1" style="text-align:center;width:50%;">
                Total de Artículos: <b>$cantidad_total </b>
                </td>
                <td colspan="1" style="text-align:center;width:50%;">
                IVA: <b> $ $iva_total</b>
                </td>
        </tr>
        </table>
        <div style="width:100%;font-size:5pt;text-align:justify">
           <br>PARA REFACTURAR SU COMPROBANTE FISCAL INGRESE A: <a href="http://www.ecomasclean.com.mx">http://www.ecomasclean.com.mx</a> SELECCIONA LA OPCIÓN FATURACION (DENTRO DE LOS 30 DÍAS NATURALES)
        </div>
        <br>
EOD;

// set the barcode content and type
$style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => '.1',
        'vpadding' => '.1',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 4,
        'stretchtext' => 4
    );    
    
// Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->write1DBarcode($infoVenta->id, 'C128', '25', '', '', 10, .5, $style, 'N');

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        $pdf->Output('Venta'.$ventas_id.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
    }

}