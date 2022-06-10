<?php

class FacturacionController extends Zend_Controller_Action {

    private $path;
    private $varSession = null;
    public function init() {
        $this->varSession = new Zend_Session_Namespace("users");
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
        $this->view->path = $this->path;
        $this->_helper->layout->setLayout("administracion");
        if(!isset($this->varSession->usuarios_id))
        $this->redirect("/Login");
        
    }

    public function indexAction() {

      

      if($this->getRequest()->isPost()){}
    }


    public function facturarAction(){
      $this->_helper->layout()->disableLayout();
      if($this->getRequest()->isXmlHttpRequest()){
        
        $ventas_id = $this->getRequest()->getParam("ventas_id");
        $ModelFacturacion = new Model_Facturacion();
        $facturada = $ModelFacturacion->facturada($ventas_id);
        if($facturada!=null){
          echo "<div class='alert alert-info'><i class='fa fa-check'></i> Esta Venta ya Fue Facturada ateriormente!</div>";
          return;
        }


        

        $rfc_receptor = $this->getRequest()->getParam("rfc");
        $nombre_receptor = $this->getRequest()->getParam("nombre");
        $usoCFDI = $this->getRequest()->getParam("usoCFDI");
        

        require (dirname(getcwd()) . '/library/WebServicesFacturacion.php');
        $debug = 1;
  
        // RFC utilizado para el ambiente de pruebas
        $rfc_emisor = "TCM970625MB1";
        
        //Datos de acceso al ambiente de pruebas
        $url_timbrado = "https://t1demo.facturacionmoderna.com/timbrado/wsdl";
        $user_id = "UsuarioPruebasWS";
        $user_password = "b9ec2afa3361a59af4b4d102d3f704eabdf097d4";
      
        // Generar y sellar un XML con los CSD de pruebas
        $cfdi = $this->generarLayout($rfc_emisor);        
      
        $parametros = array('emisorRFC' => $rfc_emisor,'UserID' => $user_id,'UserPass' => $user_password);
      
        $opciones = array();
        
        /**
        * Establecer el valor a true, si desea que el Web services genere el CBB en
        * formato PNG correspondiente.
        * Nota: Utilizar está opción deshabilita 'generarPDF'
        */     
        $opciones['generarCBB'] = true;
        
        /**
        * Establecer el valor a true, si desea que el Web services genere la
        * representación impresa del XML en formato PDF.
        * Nota: Utilizar está opción deshabilita 'generarCBB'
        */
        $opciones['generarPDF'] = true;
        
        /**
        * Establecer el valor a true, si desea que el servicio genere un archivo de
        * texto simple con los datos del Nodo: TimbreFiscalDigital
        */
        $opciones['generarTXT'] = true;
        
      
        $cliente = new WebServicesFacturacion($url_timbrado, $parametros, $debug);
      
        if($cliente->timbrar($cfdi, $opciones)){
          echo "<div class='alert alert-success'><i class='fa fa-check'></i> Timbrado exitoso!</div><center>";

          //Almacenanos en la raíz del proyecto los archivos generados.



          $fecha = date("Y_m_d");
          if(!is_dir("comprobantes/".$fecha)){
            mkdir("comprobantes/".$fecha);
          }

          $comprobante = 'comprobantes/'.$fecha."/".$cliente->UUID;
          
          if($cliente->xml){
            echo "<a target='blank_' href='$comprobante.xml'><button class='btn btn-info' style='width:50px;'><i class='fa fa-code'> </i></button></a>\n";        
            file_put_contents($comprobante.".xml", $cliente->xml);
          }
          if(isset($cliente->pdf)){
            echo "<a target='blank_' href='$comprobante.pdf'><button class='btn btn-info' style='width:50px;'><i class='fa fa-file-pdf'> </i></button></a>\n";
            file_put_contents($comprobante.".pdf", $cliente->pdf);
          }
          if(isset($cliente->png)){
            echo "<a target='blank_' href='$comprobante.png'><button class='btn btn-info' style='width:50px;'><i class='fa fa-image'> </i></button></a>\n";
            file_put_contents($comprobante.".png", $cliente->png);
          }
          
          echo "</center>";
          echo "<script>clearFac();</script>";
          $ModelFacturacion = new Model_Facturacion();
          $data = array(
            "ventas_id"=>$ventas_id,
            "soap" => $cliente->soap,
            "uuid"=> $cliente->UUID,
            "rfc_receptor" => $rfc_receptor,
            "nombre_receptor" => $nombre_receptor,
            "usoCFDI" => $usoCFDI
          );
          $facturas_id = $ModelFacturacion->almacena($data);          
        }else{
          echo "[".$cliente->ultimoCodigoError."] - ".$cliente->ultimoError."\n";
        }      
      }


    }

    public function listadoAction(){
      $ModelFacturacion = new Model_Facturacion();
      $facturas = $ModelFacturacion->listado();
      $this->view->listado = $facturas;
      
    }

    
    private function generarLayout($rfc_emisor){        
            $fecha_actual = substr( date('c'), 0, 19);
          
            /*
              Puedes encontrar más ejemplos y documentación sobre estos archivos aquí. (Factura, Nota de Crédito, Recibo de Nómina y más...)
              Link: https://github.com/facturacionmoderna/Comprobantes
              Nota: Si deseas información adicional contactanos en www.facturacionmoderna.com
           */
          
            $cfdi =<<<LAYOUT
[ComprobanteFiscalDigital]
Version=3.3
Serie=A
Folio=02
Fecha=$fecha_actual
FormaPago=03
NoCertificado=20001000000300022762
CondicionesDePago=CONTADO
SubTotal=1850
Descuento=175.00
Moneda=MXN
Total=1943.00
TipoDeComprobante=I
MetodoPago=PUE
LugarExpedicion=68050
   
[DatosAdicionales]
tipoDocumento=FACTURA
observaciones=Observaciones al documento versión 3.3
platillaPDF=clasic
      
[Emisor]
Rfc=$rfc_emisor
Nombre=Eco mas Clean S.A. de C.V.
RegimenFiscal=601
      
[Receptor]
Rfc=XAXX010101000
Nombre=PUBLICO EN GENERAL
UsoCFDI=G03
       
[Concepto#1]
ClaveProdServ=01010101
NoIdentificacion=AULOG001
Cantidad=5
ClaveUnidad=H87
Unidad=Pieza
Descripcion=Aurriculares USB Logitech
ValorUnitario=350.00
Importe=1750.00
Descuento=175.00
Impuestos.Traslados.Base=[1575.00]
Impuestos.Traslados.Impuesto=[002]
Impuestos.Traslados.TipoFactor=[Tasa]
Impuestos.Traslados.TasaOCuota=[0.160000]
Impuestos.Traslados.Importe=[252.00]
         
[Concepto#2]
ClaveProdServ=43201800
NoIdentificacion=USB
Cantidad=1
ClaveUnidad=H87
Unidad=Pieza
Descripcion=Memoria USB 32gb marca Kingston
ValorUnitario=100.00
Importe=100.00
Impuestos.Traslados.Base=[100.00]
Impuestos.Traslados.Impuesto=[002]
Impuestos.Traslados.TipoFactor=[Tasa]
Impuestos.Traslados.TasaOCuota=[0.160000]
Impuestos.Traslados.Importe=[16.00]
         
[Traslados]
TotalImpuestosTrasladados=268.00
Impuesto=[002]
TipoFactor=[Tasa]
TasaOCuota=[0.160000]
Importe=[268.00]
LAYOUT;
            return $cfdi;
          }
    


}
