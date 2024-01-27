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

class MYTCPD extends TCPDF{
        public function Footer()
        {
                $this->SetY(-15);
                $this->Cell(0,10,'Página '.$this->getAliasNumPage()."/".$this->getAliasNbPages(),0,false,'C',0,'',false,'T','M');
        }
}

class Reporte_Cotizacion {
        
    public function create($cotizaciones_id) {
        
        $ModelCotizaciones = new Model_Cotizacion();
        $ModelCotizacionDetalles = new Model_Cotizacionitems();

        $infoCotizacion = $ModelCotizaciones->getInfoCotizacion($cotizaciones_id);
        $items = $ModelCotizacionDetalles->getItemsByCotizacion($cotizaciones_id);

        $largoBase = "110";
        $largo = 0;
        if($items!=null){
                $factor = $items->count();
                $largo = $factor*3;
        }
                
        // create new PDF document
        $pdf = new MYTCPD("P", "mm", PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ECO + Clean');
        $pdf->SetTitle('Cotizacion '.$cotizaciones_id);
        $pdf->SetSubject('Sistema ECO + Clean');
        $pdf->SetKeywords('');

// set default header data             
        $pdf->SetHeaderData(PDF_HEADER_LOGO, 30, "ECO+CLEAN PROVEEDORA DE LIMPIEZA", "R.F.C. AAVR810904E39, CURP AAVR810904MOCLSS07                                                                                                                 AV. PROLETARIADO MEXICANO, MANZANA “M” NUMERO 3 INFONAVIT PRIMERO DE MAYO, C.P. 68020, OAXACA, OAX.", array(0, 0, 0), array(0, 24, 128));
        $pdf->setFooterData(array(0, 0, 0), array(0, 64, 128));

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_DATA));//PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
        $pdf->SetHeaderMargin(5);//PDF_MARGIN_HEADER);
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
        $pdf->AddPage('P','LETTER');

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
$ban=true;

if($items!=null){
        foreach($items as $item){
                $ban=!$ban;
                $cantidad_total+=$item->cantidad;
                $contador++;
                
                $itemsString .= '<tr style="margin:5px;padding:5px;" class="'.($ban?"trcol":"").'">
                <td style="width:5%;text-align:left;border:solid 1px gray;margin:0px;padding:0px ">'.$contador.'</td>
                <td style="width:40%;text-align:left;border:solid 1px gray;margin:0px;padding:0px">'.$item->nombre.'</td>
                <td style="width:5%;text-align:center;border:solid 1px gray;margin:0px;padding:0px">'.$item->cantidad.'</td>
                <td style="width:10%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->precio,2).'</td>
                <td style="width:10%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->subtotal+$item->descuento,2).'</td>
                <td style="width:10%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->descuento,2).'</td>
                <td style="width:10%;text-align:right;border:solid 1px gray;margin:0px;padding:0px">'.number_format($item->iva_monto,2).'</td>
                <td style="width:10%;text-align:right;border:solid 1px gray;">'.number_format($item->total,2).'</td>
                </tr>';
                $iva_total += $item->iva_monto;
        }
}


     
        $now = date("Y-m-d H:i:s");
        if($items!=null)
                $total = number_format($infoCotizacion->total,2);
        else
                $total = 0;        

        require (dirname(getcwd()) . '/library/NumeroALetras.php');        
        $formatter = new NumeroALetras();
        $formatter->conector = 'Y';
        $TotalLetra =  $formatter->toMoney($infoCotizacion->total, 2, 'pesos', 'centavos')." M.N"; 
               
        $fecha_venta = date("d-m-Y",strtotime($infoCotizacion->fecha_creacion));

        $fecha_vigencia = date("d-m-Y",strtotime('+'.$infoCotizacion->vigencia.' day',strtotime($infoCotizacion->fecha_creacion)));
        $hora_venta = date("H:i:s",strtotime($infoCotizacion->fecha_creacion));
        $cajero = $infoCotizacion->empleado;                    
        $iva_total = number_format($iva_total,2);
        $html = <<<EOD
        <style>
        .trcol{
                background-color:#F6F6F6;
        }
        </style>
        <h1 style="text-align:center;color:gray">Cotización</h1>
        <table style="width:100%;">
                <tr>
                        <td style="width:50%;border:dotted 1px gray;text-align:center;">COTIZACIÓN No: <b>$infoCotizacion->folio</b></td>
                        <td style="width:50%;border:dotted 1px gray;text-align:center;"> 
                        FECHA: <b>$fecha_venta</b>                       
                        </td>
                </tr>
                <tr>
                        <td colspan="2" style="text-align:left;font-size:1em;color:gray;border:dotted 1px gray;">Cliente: <b>$infoCotizacion->nombre_cliente</b></td>
                </tr>
                <tr>
                        <td colspan="2" style="text-align:left;font-size:1em;color:gray;border:dotted 1px gray;">Vigencia: <b>$fecha_vigencia</b></td>
                </tr>
                <tr>
                        <td colspan="2" style="text-align:center;color:gray"><div></div><h2>Artículos<br/></h2></td>
                </tr>
        </table>     
        <table style="border:solid 1px gray;margin-left:-5px;" border="">                
                        <tr style="font-weight:bold;background-color:#138496;color:white;font-size:10pt">
                                <td style="width:5%;text-align:center;"><br/><br/>#</td>
                                <td style="width:40%;text-align:center"><br/><br/>Descripcion.</td>
                                <td style="width:5%;text-align:center"><br/><br/>Can</td>
                                <td style="width:10%;text-align:center"><br/><br/>P.U.</td>  
                                <td style="width:10%;text-align:center"><br/><br/>Sub.</td>  
                                <td style="width:10%;text-align:center"><br/><br/>Des.</td>
                                <td style="width:10%;text-align:center"><br/><br/>IVA</td>
                                <td style="width:10%;text-align:center"><br/><br/>Total<br/></td>
                        </tr>   
                        $itemsString             
        </table>
        <div></div>
        <table style="width:100%; font-size:8pt.;">
                <tr>
                        <td style="text-align:right;width:60%">                        
                        <br/>
                                TOTAL:
                        </td>
                        <td style="text-align:right;width:40%;">                        
                        <b>$ $total</b>
                        </td>                        
                </tr>                                
        </table>
        <div></div>
        <table style="width:100%;border:dotted .5px gray;">
        <tr>
                <td colspan="2" style="text-align:center;width:100%;">
                <br/>
                <br/>
                <b>($TotalLetra)</b>
                <br/>                
                </td>
        </tr>
        <tr>
                <td colspan="1" style="text-align:center;width:50%;">
                Total de Artículos: <b>$cantidad_total </b>
                </td>
                <td colspan="1" style="text-align:center;width:50%;">
                IVA: <b> $ $iva_total</b>
                <br/>
                </td>
        </tr>
        </table>  
        <br/>
        <h3 style="color:gray">Comentarios:</h3>
        <div style="width:100%; border:dotted 1px gray; height:300pt">        
        $infoCotizacion->comentarios
        <br/>
        </div>
        <div style="width:100%;">   
        <br/>     
        <table>
         <tr>
           <td style="width:10%">
           <span style="color:grey">Elaboró:</span>
           </td>
           <td style="width:80%;border-bottom:solid 1px gray;">
                $infoCotizacion->empleado
           </td>
                </tr>
        </table>        
        </div>
        <br/>
        <div style="width:100%;text-align:justify">
           <br/>
           Nota: Esta Cotización tiene una vigencia de $infoCotizacion->vigencia dias después de su emisión. 
           <br/>
        </div>
EOD;

// set the barcode content and type
$style = array(
        'position' => '',
        'align' => 'R',
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
        'fontsize' => 8,
        'stretchtext' => 8
    );    
    
// Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->write1DBarcode($infoCotizacion->folio, 'C128', '175', '', '30', 15, .5, $style, 'N');

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        ob_clean();
        $pdf->Output('Cotizacion'.$infoCotizacion->folio.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
    }

}