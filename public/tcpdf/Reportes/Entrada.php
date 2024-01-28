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

class Reporte_Entrada {

    public function create($entradas_id) {
        
        $ModelEntrada = new Model_Entrada();
        $ModelTurno = new Model_Turno();
        
        $infoEntrada = $ModelEntrada->getInfo($entradas_id);        
        $saldoActual = $ModelTurno->getSaldoFinal($infoEntrada->turnos_id);
        
        $saldoActual = number_format($saldoActual,2);

        
        //Obtenemos información de pago por cupón en caso de que hubiere.        
        // create new PDF document
        $pdf = new TCPDF("P", "mm", array(80,80), true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ECO + Clean');
        $pdf->SetTitle('Retiro: '.$entradas_id);
        $pdf->SetSubject('Sistema ECO + Clean');
        $pdf->SetKeywords('');

// set default header data             
        $pdf->SetHeaderData(PDF_HEADER_LOGO, 20, "ECO+CLEAN PROVEEDORA DE LIMPIEZA", "R.F.C. AAVR810904E39, CURP AAVR810904MOCLSS07                            AV. PROLETARIADO MEXICANO, MANZANA “M” NUMERO 3 INFONAVIT PRIMERO DE MAYO, C.P. 68020, OAXACA, OAX.", array(0, 0, 0), array(0, 24, 128));
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
$html = "<center><h3>Registro de Ingreso de Efectivo</h3></center>";
$entrada_f = number_format($infoEntrada->monto,2);
$html .= <<<EOD
        <table border="1" style="width:100%">
                <tr>
                        <th style="width:40%;background-color:gray;color:white">Id Entrada:</th>
                        <td style="text-align:left">$infoEntrada->id</td>
                </tr>
                <tr>
                        <th style="width:40%;background-color:gray;color:white">Turno:</th>
                        <td style="text-align:left">$infoEntrada->turnos_id</td>
                </tr>
                <tr>
                        <th style="width:40%;background-color:gray;color:white">Fecha:</th>
                        <td style="text-align:left">$infoEntrada->fecha</td>
                </tr>
                <tr>
                        <th style="width:40%;background-color:gray;color:white">Monto:</th>
                        <td style="text-align:left">$ $entrada_f</td>
                </tr>
                <tr>
                        <th style="width:40%;background-color:gray;color:white">Comentario:</th>
                        <td style="text-align:left">$infoEntrada->motivo</td>
                </tr>
                <tr>
                        <th style="width:40%;background-color:gray;color:white">Saldo Actual:</th>
                        <td style="text-align:left">$ $saldoActual</td>
                </tr>
        
        </table>
EOD;

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
        
        //$pdf->write1DBarcode($infoRetiro->id, 'C128', '25', '', '', 10, .5, $style, 'N');
        

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        ob_clean();        
        $pdf->Output('Retiro'.$entradas_id.'.pdf', 'I');        
        //die($html);

//============================================================+
// END OF FILE
//============================================================+
    }

}