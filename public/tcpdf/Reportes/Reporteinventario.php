<?php

require_once('tcpdf_include.php');
//require_once('tcpdf_barcodes_1d.php');

class Reporte_Reporteinventario {

    public function create() {
        
        require (dirname(getcwd()) . '/library/NumeroALetras.php');        

        $ModelItems = new Model_Item();
        $items = $ModelItems->getProductos();
        $rows = "";

        $bandera=false;
        foreach($items as $producto){
                $costototal+= $producto->existencias*$producto->costo;
                $costototalmenudeo += $producto->existencias*$producto->precio_publico;
                $costototalmayoreo += $producto->existencias*$producto->precio_mayoreo;
                if($bandera){
                        $color="#F5F5F5";
                        $bandera = !$bandera;
                }else{
                        $color="white";
                        $bandera = !$bandera;
                }



                $rows.= '
                        <tr style="background-color:'.$color.'">
                                <td style="width:5%;border:dotted 1px gray">'.$producto->id.'</td>
                                <td style="width:15%;border:dotted 1px gray">'.$producto->nombre.'</td>
                                <td style="width:10%;border:dotted 1px gray">'.$producto->unidad.'</td>
                                <td style="text-align:right;width:10%;border:dotted 1px gray">$ '.number_format($producto->costo,2).'</td>
                                <td style="text-align:right;width:10%;border:dotted 1px gray">$ '.number_format($producto->precio_publico,2).'</td>                                                    
                                <td style="text-align:right;width:10%;border:dotted 1px gray">$ '.number_format($producto->precio_mayoreo,2).'</td>  
                                <td style="text-align:right;width:10%;border:dotted 1px gray">'.number_format($producto->existencias,2).'</td>                                                                                                        
                                <td style="text-align:right;width:10%;border:dotted 1px gray">$ '.number_format(($producto->existencias*$producto->costo),2).'</td>                                                                                                        
                                <td style="text-align:right;width:10%;border:dotted 1px gray">$ '.number_format(($producto->existencias*$producto->precio_publico),2).'</td>                                                                                                        
                                <td style="text-align:right;width:10%;border:dotted 1px gray">$ '.number_format(($producto->existencias*$producto->precio_mayoreo),2).'</td>                                                                                                        
                        </tr>';
        }

        
                $html = '
                <h2 style="color:gray">Productos</h2>
                <table width="100%" cellspacing="0" lang="es" style="font-size:8pt;">
                                        <thead>
                                            <tr style="background-color:pink;color:black;">
                                                <th style="width:5%;text-weight:bold">Id</th>
                                                <th style="width:15%">Producto</th>                                                
                                                <th style="width:10%">Unidad</th>
                                                <th style="width:10%">Costo</th>
                                                <th style="width:10%">P. Menudeo</th>
                                                <th style="width:10%">P. Mayoreo</th>                                                
                                                <th style="width:10%">Existencias</th>
                                                <th style="width:10%">Costo Total</th>                                                                                                
                                                <th style="width:10%">C. Menudeo</th>                                                                                                
                                                <th style="width:10%">C. Mayoreo</th>                                                                                                                                                
                                            </tr>
                                        </thead>
                                        <tbody>                                          
                                        '.$rows.'
                                        </tbody>
                </table>
                <h2 style="color:gray">Resumen de Costos</h2>
                ';

                $html .= '
                        <table style="width:100%;font-size:8pt;">
                                    <thead>
                                                <tr style="text-align:center;background-color:pink">
                                                    <th>Costo Total de Inventario</th>
                                                    <th>Costo Total de Inventario Menudeo</th>
                                                    <th>Costo Total de Inventario Mayoreo</th>
                                                    <th style="background-color:#DCFFE5">Ganancia Neta Menudeo</th>
                                                    <th style="background-color:#DCFFE5">Ganancia Neta Mayoreo </th>
                                                </tr>
                                    </thead>
                                    <tbody>
                                                <tr style="text-align:center;font-size:2.0em;">
                                                    <td style="vertical-align:middle">$ '.number_format($costototal,2).'</td>
                                                    <td style="vertical-align:middle">$ '.number_format($costototalmenudeo,2).'</td>
                                                    <td style="vertical-align:middle">$ '.number_format($costototalmayoreo,2).'</td>
                                                    <td style="vertical-align:middle">$ '.number_format(($costototalmenudeo - $costototal),2).'</td>
                                                    <td style="vertical-align:middle">$ '.number_format(($costototalmayoreo - $costototal),2).'</td>                                                                                                        
                                                </tr>
                                    </tbody>
                                   </table>
                ';


        $formatter = new NumeroALetras();
        $formatter->conector = 'Y';
        //$TotalLetra =  $formatter->toMoney($total, 2, 'pesos', 'centavos')." M.N";         



        






        

        // $infoVenta = $ModelVentas->getInfoVenta($ventas_id);
        // $items = $ModelVentaItems->getItemsDespachados($ventas_id);

        
        
        // create new PDF document
        $pdf = new TCPDF("L", "mm", "LETTER", true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ECO + Clean');
        $pdf->SetTitle('Costo de Inventarios a la fecha  '.date("Y-m-d H:i:s"));
        $pdf->SetSubject('Sistema ECO + Clean');
        $pdf->SetKeywords('');

// set default header data        
        $pdf->SetHeaderData(PDF_HEADER_LOGO, 25, "ECO + Clean", " Costos de Inventario", array(0, 0, 0), array(0, 24, 128));
        $pdf->setFooterData(array(0, 0, 0), array(0, 64, 128));

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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


    
// Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        //$pdf->write1DBarcode($infoVenta->id, 'C128', '25', '', '', 10, .5, $style, 'N');

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        $pdf->Output('CostoInventario'.date("Hmi").'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
    }

}