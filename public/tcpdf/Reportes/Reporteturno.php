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

class Reporte_Reporteturno
{

        public function create($turnos_id)
        {

                $ModelVentas = new Model_Venta();
                $ModelVentaItems = new Model_Ventaitems();
                $ModelTurno = new Model_Turno();
                $ModelRetiros = new Model_Retiro();
                $ModelVentaCreditos = new Model_VentaCreditos();
                $ModelEntradas = new Model_Entrada();

                $totalAbonos = 0;

                $abonos = $ModelVentaCreditos->getAbonosByTurno($turnos_id);
                if ($abonos != null) {
                        foreach ($abonos as $abono) {
                                $totalAbonos += $abono->abono;
                        }
                }


                $infoTurno = $ModelTurno->getInfoTurno($turnos_id);
                $saldo_inicial = number_format($infoTurno->saldo_inicial, 2);
                $saldo_final = number_format($infoTurno->saldo_final, 2);
                $saldo_final_manual = number_format($infoTurno->saldo_final_manual, 2);
                $estado = $infoTurno->estado == "1" ? "Abierto" : "Cerrado";
                $fecha = date("Y-d-m", strtotime($infoTurno->fecha));
                $retiros = $ModelRetiros->getRetiros($turnos_id);
                $entradas = $ModelEntradas->getEntradas($turnos_id);
                $html = '
        <style>
        .encabezado_turno th{
                text-align:center;
                background-color:green;
                color:white;
                text-weight:bold;
                font-size:10pt;
        }
        .turno td{
                font-size:10pt;
        }
        .encabezado_venta th{
                background-color:#138496;
                color:white;
                text-weight:bold;
                font-size:10pt;
                text-align:center;
        }
        .encabezado_items th{
                background-color:gray;
                color:white;
                text-weight:bold;
                font-size:10pt;
                text-align:center;
        }


        </style>
                <h2 style="color:gray;">Información del Turno ' . $infoTurno->id . '</h2>
                <table style="font-size:8pt;"  cellpadding="5" border="1">
                        <thead>
                                <tr class="encabezado_turno">
                                        <th style="width:12%;">Fecha</th>
                                        <th style="width:12%;">Estado</th>
                                        <th style="width:31%;" >Encargado de Turno</th>
                                        <th style="width:15%;">Saldo Incial</th>
                                        <th style="width:15%;">Saldo Final</th>
                                        <th style="width:15%;">Saldo F. M.</th> 
                                </tr>
                        </thead>
                        <tbody>
                                <tr class="turno">
                                        <td style="width:12%; text-align:center">' . $fecha . '</td>
                                        <td style="width:12%; text-align:center">' . $estado . '</td>
                                        <td style="width:31%; ">' . $infoTurno->empleado . '</td>
                                        <td style="width:15%; text-align:right">$ ' . $saldo_inicial . '</td>
                                        <td style="width:15%; text-align:right">$ ' . $saldo_final . '</td>
                                        <td style="width:15%; text-align:right">$ ' . $saldo_final_manual . '</td>
                                </tr>
                        </tbody>
                </table>';

                $ventas = $ModelVentas->getAllVentasByTurno($turnos_id);

                if ($ventas != null) {

                        foreach ($ventas as $venta) {
                                $subtotal = number_format($venta->subtotal, 2);
                                $descuento = number_format($venta->descuento, 2);
                                $IVA = number_format($venta->iva, 2);
                                $IEPS = number_format($venta->ieps, 2);
                                $total = number_format($venta->total + $venta->descuento, 2);
                                $pago = number_format($venta->pago, 2);
                                $cambio = number_format($venta->cambio, 2);

                                $html .= '
                                <h3 style="color:gray;">Venta' . $venta->id . '</h3>
                                <table style="font-size:8pt;" border="1" cellpadding="5">
                                <thead>
                                        <tr class="encabezado_venta">
                                                <th style="width:10%;">Id</th>
                                                <th style="width:10%;">Estado</th>
                                                <th style="width:10%;" >Subtotal</th>
                                                <th style="width:10%;">Desc.</th>
                                                <th style="width:10%;">IVA</th>
                                                <th style="width:10%;">IEPS</th> 
                                                <th style="width:10%;">Total</th> 
                                                <th style="width:10%;">F. Pago</th> 
                                                <th style="width:10%;">Pago</th> 
                                                <th style="width:10%;">Cambio</th> 
                                        </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                                <td style="width:10%; text-align:center">' . $venta->id . '</td>
                                                <td style="width:10%; text-align:center">' . $venta->estado . '</td>
                                                <td style="width:10%; ">$ ' . $subtotal . '</td>
                                                <td style="width:10%; text-align:right">$ ' . $descuento . '</td>
                                                <td style="width:10%; text-align:right">$ ' . $IVA . '</td>
                                                <td style="width:10%; text-align:right">$ ' . $IEPS . '</td>
                                                <td style="width:10%; text-align:right">$ ' . $total . '</td>
                                                <td style="width:10%; text-align:center">' . $venta->forma_pago . '</td>
                                                <td style="width:10%; text-align:right">$ ' . $pago . '</td>
                                                <td style="width:10%; text-align:right">$ ' . $cambio . '</td>
                                        </tr>
                                </tbody>
                        </table>
                        <br/>';



                                $items = $ModelVentaItems->getItemsByVenta($venta->id);
                                //die(var_dump($items));

                                if ($items != null) {
                                        $items_string = "";
                                        foreach ($items as $item) {
                                                $precio = number_format($item->precio, 2);
                                                $cantidad = $item->cantidad;
                                                $total = number_format($item->precio * $item->cantidad, 2);
                                                $items_string .= '
                                                <tr>
                                                        <td style="width:10%; text-align:center">' . $item->id . '</td>
                                                        <td style="width:30%; text-align:left">' . $item->nombre . '</td>
                                                        <td style="width:10%; text-align:right">$ ' . $precio . '</td>
                                                        <td style="width:10%; text-align:center">' . $cantidad . '</td>
                                                        <td style="width:10%; text-align:right">$ ' . $total . '</td>                                                
                                                </tr>';
                                        }






                                        $html .= '
                           <table style="font-size:8pt;" border="1" cellpadding="5">
                                <thead>
                                        <tr class="encabezado_items">
                                                <th style="width:10%;">Id</th>
                                                <th style="width:30%;">Nombre</th>
                                                <th style="width:10%;" >Precio</th>
                                                <th style="width:10%;">Cantidad</th>
                                                <th style="width:10%;">Total</th>                                                
                                        </tr>
                                </thead>
                                <tbody>' .
                                                $items_string
                                                . '</tbody>
                                </table>

                           ';
                                }
                        }
                }

                //obtenemos los registros de disposición de cupones en las ventas
                $modelMovimientosCupon = new Model_Movimientoscupon();
                $movimientosCupones = $modelMovimientosCupon->getDisposicionesCuponByTurno($turnos_id);
                $sumCup = 0;
                if ($movimientosCupones != null) {
                        $html .= '<h3 style="color:gray;">Disposición de cupones</h3>
                        <table cellpadding="5" style="font-size:8pt;width:250" border="1">
                <thead>
                        <tr class="encabezado_items">
                                <th style="width:85px;">Cupón</th>
                                <th style="width:80px">Venta</th>
                                <th style="width:85px;">Monto</th>                        
                        </tr>
                </thead>';

                        foreach ($movimientosCupones as $cupon) {
                                $sumCup += $cupon->monto;
                                $html .= '        
                        <tr>    
                                <td style="text-align:center;">' . $cupon->codigo_cupon . '</td>
                                <td>' . $cupon->ventas_id . '</td>
                                <td style="text-align:right">$ ' . number_format($cupon->monto, 2) . '</td>
                        </tr>';
                        }
                        $html .= '        
                        <tr>    
                                <td colspan="2" style="text-align:right;">Total en Cupones</td>                        
                                <td style="text-align:right"><b>$ ' . number_format($sumCup, 2) . '</b></td>
                        </tr>';
                        $html .= "</table>";
                }



                //Aqui hacemos la integración de los retiros hechos en el turno
                if ($entradas != null) {
                        $html .= '<h3 style="color:green;">Entradas Registradas en este turno</h3>
                        <table cellpadding="5" style="font-size:8pt;width:1000" border="1">
                        <tr class="encabezado_items">
                                <th style="width:40px;">No.</th>
                                <th>Comentarios</th>
                                <th style="width:80px;">Monto</th>                        
                        </tr>';
                        $con = 1;
                        $sum = 0;
                        foreach ($entradas as $entrada) {
                                $sum += $entrada->monto;
                                $html .= '        
                        <tr>    
                                <td style="text-align:center;">' . $con++ . '</td>
                                <td>' . $entrada->motivo . '</td>
                                <td style="text-align:right">$ ' . number_format($entrada->monto, 2) . '</td>
                        </tr>';
                        }
                        $html .= '        
                        <tr>    
                                <td colspan="2" style="text-align:right;">Total en entradas</td>                        
                                <td style="text-align:right"><b>$ ' . number_format($sum, 2) . '</b></td>
                        </tr>';

                        $html .= "</table>";
                }

                if ($retiros != null) {
                        $html .= '<h3 style="color:gray;">Retiros registrados en este turno</h3>
                        <table cellpadding="5" style="font-size:8pt;width:1000" border="1">
                        <tr class="encabezado_items">
                                <th style="width:40px;">No.</th>
                                <th>Motivo del Retiro</th>
                                <th style="width:80px;">Monto</th>                        
                        </tr>';
                        $con = 1;
                        $sum = 0;
                        foreach ($retiros as $retiro) {
                                $sum += $retiro->monto;
                                $html .= '        
                        <tr>    
                                <td style="text-align:center;">' . $con++ . '</td>
                                <td>' . $retiro->motivo . '</td>
                                <td style="text-align:right">$ ' . number_format($retiro->monto, 2) . '</td>
                        </tr>';
                        }
                        $html .= '        
                        <tr>    
                                <td colspan="2" style="text-align:right;">Total en retiros</td>                        
                                <td style="text-align:right"><b>$ ' . number_format($sum, 2) . '</b></td>
                        </tr>';

                        $html .= "</table>";
                }




                $efectivo = $ModelVentas->getResumenByForma($turnos_id, "efectivo");
                $tarjeta = $ModelVentas->getResumenByForma($turnos_id, "tarjeta");
                $transferencia = $ModelVentas->getResumenByForma($turnos_id, "transferencia");
                $retiros = $ModelRetiros->getSumaRetirnos($turnos_id);
                $entradas = $ModelEntradas->getSumaEntradas($turnos_id);
                $credito = $ModelVentas->getResumenByForma($turnos_id, "credito");

                $total = 0;
                $total_venta = 0;
                if ($efectivo != null){
                        $total += $efectivo->total;
                        $total_venta += $efectivo->total;

                }
                        
                if ($tarjeta != null)
                        $total_venta += $tarjeta->total;
                if ($transferencia != null)
                        $total_venta += $transferencia->total;

                 //+ $sumCup;
                $total += $infoTurno->saldo_inicial;

                if($credito!=null){
                        $total_venta += $credito->total;
                }
                
                

                $total += $totalAbonos;

                if ($entradas != null)
                        $total += $entradas;
                        
                if ($retiros != null)
                        $total -= $retiros;

                if ($total < 0)
                        $total = 0;

                require(dirname(getcwd()) . '/library/NumeroALetras.php');
                $formatter = new NumeroALetras();
                $formatter->conector = 'Y';
                $TotalLetra =  $formatter->toMoney($total, 2, 'pesos', 'centavos') . " M.N";

                $html .= '
        <h3>Resumen de Operaciones</h3>
        <div>
                <table style="width:80%;border:dotter 1px gray;" cellpadding="5">
                <tr>
                                <td style="background-color:#138496;color:white">Saldo Inicial:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . number_format($infoTurno->saldo_inicial, 2) . '</b></td>
                        </tr>
                        <tr>
                                <td style="background-color:#138496;color:white">Efectivo:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . ($efectivo != null ? number_format($efectivo->total + $sumCup, 2) : "0.00") . '</b></td>
                        </tr>
                        <tr>
                                <td style="background-color:#138496;color:white">Tarjeta:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . ($tarjeta != null ? number_format($tarjeta->total, 2) : "0.00") . '</b></td>
                        </tr>
                        <tr>
                                <td style="background-color:#138496;color:white">Transferencia:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . ($transferencia != null ? number_format($transferencia->total, 2) : "0.00") . '</b></td>
                        </tr>
                        <tr>
                                <td style="background-color:#138496;color:white">Créditos:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . ($credito != null ? number_format($credito->total, 2) : "0.00") . '</b></td>
                        </tr>                        
                        <tr>
                                <td style="background-color:black;color:white">Venta del turno:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . number_format($total_venta, 2) . '</b></td>
                        </tr>
                        <tr>
                                <td style="background-color:white;color:black">Cupones:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . number_format($sumCup, 2) . '</b></td>
                        </tr>
                        <tr>
                                <td style="background-color:green;color:white">Abonos en el turno:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . ($totalAbonos != null ? number_format($totalAbonos, 2) : "0.00") . '</b></td>
                        </tr>
                        <tr>
                                <td style="background-color:green;color:white">Entradas:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . ($entradas != null ? number_format($entradas, 2) : "0.00") . '</b></td>
                        </tr>        
                        <tr>
                                <td style="background-color:gray;color:white">Retiros:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b>' . ($retiros != null ? number_format($retiros, 2) : "0.00") . '</b></td>
                        </tr>                                        
                        <tr>
                                <td style="background-color:black;color:white">Total de Efectivo a entregar:</td>
                                <td style="width:5%">$</td>
                                <td style="text-align:right;border:dotter 1px gray;"><b style="">' . number_format($total, 2) . '</b></td>
                                <td style="font-size:.6em"><b>(' . $TotalLetra . ')</b></td>
                        </tr>
                </table>
        </div>';








                // $infoVenta = $ModelVentas->getInfoVenta($ventas_id);
                // $items = $ModelVentaItems->getItemsDespachados($ventas_id);



                // create new PDF document
                $pdf = new TCPDF("P", "mm", "LETTER", true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('ECO + Clean');
                $pdf->SetTitle('Turno ' . $turnos_id);
                $pdf->SetSubject('Sistema ECO + Clean');
                $pdf->SetKeywords('');

                // set default header data        
                $pdf->SetHeaderData(PDF_HEADER_LOGO, 25, "ECO + Clean", "Reporte de Ventas por Turno y Detalle de Ventas", array(0, 0, 0), array(0, 24, 128));
                $pdf->setFooterData(array(0, 0, 0), array(0, 64, 128));

                // set header and footer fonts
                $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                // set margins
                $pdf->SetMargins(5, 18, 5);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(10);

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
                $pdf->SetFont('dejavusans', '', 14, '', true);

                // Add a page
                // This method has several options, check the source code documentation for more information.
                $pdf->AddPage();

                // set text shadow effect
                //$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));



                // Comenzamos con el procesamiento de la información de la manda

                //$barcodeobj = new TCPDFBarcode($infoManda->id, 'C128');

                // output the barcode as HTML object
                //$codigo = $barcodeobj->getBarcodePNG(2, 30, array(0,0,0));

                // $itemsString="";
                // $contador=0;

                // if($items!=null){
                //         foreach($items as $item){
                //                 $contador++;
                //                 $itemsString .= '<tr style="">
                //                 <td style="width:8%;text-align:left;border:solid 1px gray; ">'.$contador.'</td>
                //                 <td style="width:45%;text-align:left;border:solid 1px gray;">'.$item->nombre.'</td>
                //                 <td style="width:7%;text-align:center;border:solid 1px gray;">'.$item->cantidad.'</td>
                //                 <td style="width:20%;text-align:right;border:solid 1px gray;">'.number_format($item->precio_publico,2).'</td>
                //                 <td style="width:20%;text-align:right;border:solid 1px gray;">'.number_format($item->precio_publico*$item->cantidad,2).'</td>
                //                 </tr>';
                //         }
                // }



                //         $now = date("Y-m-d H:i:s");
                //         if($items!=null)
                //                 $total = number_format($infoVenta->total,2);
                //         else
                //                 $total = 0;
                //         $html = <<<EOD
                //         <table style="width:100%;font-size:8pt">
                //                 <tr>
                //                         <td style="width:30%"> Ticket: $infoVenta->id </td>
                //                         <td style="width:70%"> Fecha: $now <br></td>
                //                 </tr>
                //                 <tr>
                //                         <td colspan="2" style="text-align:center"><b>Productos</b></td>
                //                 </tr>
                //         </table>     
                //         <table style="font-size:6pt;width:100%;border:solid 1px gray;" border="">                
                //                         <tr style="font-weight:bold;">
                //                                 <td style="width:8%;text-align:center">#</td>
                //                                 <td style="width:45%;text-align:center">des.</td>
                //                                 <td style="width:7%;text-align:center">C.</td>
                //                                 <td style="width:20%;text-align:center">Pre.</td>
                //                                 <td style="width:20%;text-align:center">Tot.</td>
                //                         </tr>   
                //                         $itemsString             
                //         </table>
                //         <div></div>
                //         <table style="width:100%; font-size:8pt.;">
                //                 <tr>
                //                         <td style="text-align:right;width:60%">                        
                //                                 TOTAL:
                //                         </td>
                //                         <td style="text-align:right;width:40%;">
                //                         <b>$ $total</b>
                //                         </td>
                //                 </tr>
                //         </table>
                //         <div style="width:100%;font-size:7pt;text-align:justify">
                //            <br>
                //            Este Ticket no es un comprobante Fiscal, si requiere factura, favor de solicitarla en esta sucursal.                      
                //         </div>
                //         <br>
                // EOD;

                // // set the barcode content and type
                // $style = array(
                //         'position' => '',
                //         'align' => 'C',
                //         'stretch' => false,
                //         'fitwidth' => true,
                //         'cellfitalign' => '',
                //         'border' => false,
                //         'hpadding' => '.1',
                //         'vpadding' => '.1',
                //         'fgcolor' => array(0,0,0),
                //         'bgcolor' => false, //array(255,255,255),
                //         'text' => true,
                //         'font' => 'helvetica',
                //         'fontsize' => 4,
                //         'stretchtext' => 4
                //     );    

                // Print text using writeHTMLCell()
                $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                //$pdf->write1DBarcode($infoVenta->id, 'C128', '25', '', '', 10, .5, $style, 'N');

                // ---------------------------------------------------------
                // Close and output PDF document
                // This method has several options, check the source code documentation for more information.
                $pdf->Output('Turno' . $turnos_id . '.pdf', 'I');

                //============================================================+
                // END OF FILE
                //============================================================+
        }
}
