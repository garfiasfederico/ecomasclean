<?php

class CajaController extends Zend_Controller_Action {

    private $path;
    private $varSession = null;
    private $file;
    private $turno;

    public function init() {
        $this->varSession = new Zend_Session_Namespace("users");
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
        $this->view->path = $this->path;
        $this->_helper->layout->setLayout("administracion");
        if(!isset($this->varSession->usuarios_id))
          $this->redirect("/Login");
        $ModelTurno = new Model_Turno();
        $this->turno = $ModelTurno->getTurnoActivo($this->varSession->usuarios_id);
        $this->file = fopen("logcotizaciones.txt","a") or die("No puede crearse el archivo de log");
    }

    public function indexAction() {
      $ModelTurno = new Model_Turno();
      if($this->getRequest()->isPOST()){
        $params = $this->getRequest()->getParams();
        $ModelTurno->abreturno($params);        
      }      
      $turno = $ModelTurno->getTurnoActivo($this->varSession->usuarios_id);
      if($turno!=null){
        $this->varSession->turno = $turno;
        $this->view->tieneturno = true;
        $this->view->turno = $turno;
      }else{
        $this->view->tieneturno = false;
      }

      $modalClientes = new Model_Cliente();
      $clientes = $modalClientes->getClientes();
      
      $this->view->clientes = $clientes;
      $this->view->rol = $this->varSession->rol;
      $this->view->usuario = $this->varSession->usuario;
      $this->view->usuarios_id = $this->varSession->usuarios_id;
    }

    public function turnosAction(){
      $ModelTurno = new Model_Turno();

      if($this->getRequest()->isPost()){
        //Intenta Cerrar turno
        $turnos_id = $this->getRequest()->getParam("turnos_id");
        $saldo_final_manual = $this->getRequest()->getParam("saldo_final_manual");
        $result = $ModelTurno->cerrarTurno($turnos_id,$saldo_final_manual);
        if($result)
          $this->view->mensaje = "<div id='mensaje' style='' class='alert alert-success'><i class='fa fa-check'></i> Turno ".$turnos_id." Cerrado correctamente!</div>";
        else
          $this->view->mensaje = "<div id='mensaje' style='' class='alert alert-warning'><i class='fa fa-close'></i> Ocurrió un Error al cerrar el turno ".$turnos_id." Itente más tarde</div>";
      }

      
      if($this->varSession->rol=="ADMINISTRADOR")
        $turnos = $ModelTurno->getTurnos();

      if($this->varSession->rol == "CAJERO" || $this->varSession->rol == "COORDINADOR")
        $turnos = $ModelTurno->getTurnos($this->varSession->usuarios_id);


      $this->view->turnos = $turnos;      
    }

    public function listadoAction(){
      $ModelVentas = new Model_Venta();
      if($this->varSession->rol=="CAJERO"){        
        $ventas = $ModelVentas->getAllVentasByUsuario($this->varSession->usuarios_id);
      }else{
        $ventas = $ModelVentas->getAllVentasByUsuario();
      }
      $this->view->ventas = $ventas;
    }

    public function imprimeturnoAction(){
      if($this->getRequest()->isGET() || $this->getRequest()->isPOST()){
        $this->_helper->layout()->disableLayout();              
        $turnos_id = $this->getRequest()->getParam("turno");
        require (dirname(getcwd()) . '/public/tcpdf/Reportes/Reporteturno.php');        
        $ReporteTurno= new Reporte_Reporteturno();        
        $ReporteTurno->create($turnos_id);
    }

    }

    public function retirosAction(){
      $ModelTurno = new Model_Turno();
      $ModelRetiro = new Model_Retiro();
      $turno = $ModelTurno->getTurnoActivo($this->varSession->usuarios_id);  
      $infoTurno = null;
      if($turno!=null)
        $infoTurno = $ModelTurno->getInfoTurno($turno);    

      if($infoTurno==null)
        $this->redirect("/Caja");

      $retiros = $ModelRetiro->getRetiros($turno);  
      $this->view->infoTurno = $infoTurno;
      $this->view->turno = $turno;
      $this->view->retiros = $retiros;

    }

    public function testAction(){
      require (dirname(getcwd()) . '/library/NumeroALetras.php');        
      $formatter = new NumeroALetras();
      $formatter->conector = 'Y';
      echo $formatter->toMoney(12345.10, 2, 'pesos', 'centavos')." M.N";
    }

    public function devolucionesAction(){
      $this->view->turno = $this->varSession->turno;
      
    }

    public function cotizacionAction(){
      $modelClientes = new Model_Cliente();
      $clientes = $modelClientes->getClientes();
      $ModelTurno = new Model_Turno();      
      $turno = $ModelTurno->getTurnoActivo($this->varSession->usuarios_id);
      if($turno!=null){
        $this->varSession->turno = $turno;
        $this->view->tieneturno = true;
        $this->view->turno = $turno;
      }else{
        $this->view->tieneturno = false;
      }
      $this->view->clientes = $clientes;
    }

    public function almacenacotizacionAction(){
      $this->_helper->layout()->disableLayout();
      if($this->getRequest()->isXmlHttpRequest()){          
          $dataCotizacion = $this->getRequest()->getParams();          
          $ModelCotizaciones = new Model_Cotizacion();
          $cotizaciones_id = $ModelCotizaciones->almacenar($dataCotizacion);
//          die($cotizaciones_id);

          //Procedemos a almacenar los items de la cotizacion
          $precios = $this->getRequest()->getParam("precios");
          $cantidades = $this->getRequest()->getParam("cantidades");
          $items = $this->getRequest()->getParam("items");
          $ivas = $this->getRequest()->getParam("ivas");
          $subtotales = $this->getRequest()->getParam("subtotales");
          $ivamontos = $this->getRequest()->getParam("ivamontos");
          $descuentos = $this->getRequest()->getParam("descuentos");
          $totales = $this->getRequest()->getParam("totales");






          $precios_ = explode("|",$precios);
          $items_ = explode("|",$items);
          $cantidades_ = explode("|",$cantidades);
          $ivas_ = explode("|",$ivas);
          $subtotales_ = explode("|",$subtotales);
          $ivamontos_ = explode("|",$ivamontos);
          $descuentos_ = explode("|",$descuentos);
          $totales_ = explode("|",$totales);


          $bandera="".$cotizaciones_id." |";
          if($cotizaciones_id!=null){
              $ModelCotizacionItems = new Model_Cotizacionitems();
              $ModelCotizacionItems->deleteItemsFromCotizacion($cotizaciones_id);
              for($x=0; $x<count($precios_)-1;$x++){
                  $dataItem = array(
                      "cotizaciones_id"=>$cotizaciones_id,
                      "items_id"=>$items_[$x],
                      "estado"=>"1",
                      "cantidad"=>$cantidades_[$x],
                      "precio"=>$precios_[$x],
                      "iva"=>$ivas_[$x],
                      "subtotal"=>$subtotales_[$x],
                      "iva_monto"=>$ivamontos_[$x],
                      "descuento"=>$descuentos_[$x],
                      "total"=>$totales_[$x],                      

                  );
                  $bandera.=$ModelCotizacionItems->almacenar($dataItem)."_";
              }              
              }

          }

          fwrite($this->file,$bandera."\n");
          fclose($this->file);

          if($cotizaciones_id!=null){
              $result = true;
          }

          $this->view->resultado = $cotizaciones_id;    
        }

  public function imprimecotizacionAction(){
        $this->_helper->layout()->disableLayout();              
        $cotizaciones_id = $this->getRequest()->getParam("ctz");
        require (dirname(getcwd()) . '/public/tcpdf/Reportes/Cotizacion.php');        
        $Ticket= new Reporte_Cotizacion();        
        $Ticket->create($cotizaciones_id);
  }

  public function listacotizacionesAction(){
    $this->_helper->layout()->disableLayout();              
    $modelCotizaciones = new Model_Cotizacion();
    $listacotizaciones = $modelCotizaciones->getCotizaciones();
    $this->view->listacotizaciones = $listacotizaciones;

  }

  public function creditosAction(){
    $this->view->turnos_id = $this->turno;
    
  }

  public function entradasAction(){
    $ModelTurno = new Model_Turno();
    $ModelEntradas = new Model_Entrada();
    $turno = $ModelTurno->getTurnoActivo($this->varSession->usuarios_id);  
    $infoTurno = null;
    if($turno!=null)
      $infoTurno = $ModelTurno->getInfoTurno($turno);    

    if($infoTurno==null)
      $this->redirect("/Caja");

    $entradas = $ModelEntradas->getEntradas($turno);  
    $this->view->infoTurno = $infoTurno;
    $this->view->turno = $turno;
    $this->view->entradas = $entradas;

  }

  public function imprimeretiroAction(){    
    $this->_helper->layout()->disableLayout(); 
    if($this->getRequest()->isGET()){                     
        $retiros_id = $this->getRequest()->getParam("retiro");
        require (dirname(getcwd()) . '/public/tcpdf/Reportes/Retiro.php');        
        $Retiro= new Reporte_Retiro();                
        $Retiro->create($retiros_id);
    }
  }
  public function imprimeentradaAction(){    
    $this->_helper->layout()->disableLayout(); 
    if($this->getRequest()->isGET()){                     
        $entradas_id = $this->getRequest()->getParam("entrada");
        require (dirname(getcwd()) . '/public/tcpdf/Reportes/Entrada.php');        
        $Entrada= new Reporte_Entrada();                
        $Entrada->create($entradas_id);
    }
  }
}
