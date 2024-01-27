<?php

class VentasController extends Zend_Controller_Action
{

    private $path = null;    
    private $varSession = null;
    private $file;
    public function init()
    {      
      $this->file = fopen("logmandas.txt","a") or die("No puede crearse el archivo de log");
      $this->varSession = new Zend_Session_Namespace("users");
      $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
      $this->view->path =  $this->path;
      $this->_helper->layout->setLayout("administracion");
      if(!isset($this->varSession->usuarios_id))
        $this->redirect("/Login");
        
    }

    public function indexAction()
    {        
      $ModelMandas = new Model_Manda();
      $mandas = $ModelMandas->getAllMandasCerradas();
      $this->view->mandas = $mandas; 
    }
    
    public function almacenaventaAction(){
      $this->_helper->layout()->disableLayout();
      if($this->getRequest()->isXmlHttpRequest()){          
          $dataVenta = $this->getRequest()->getParams();          
          $ModelVentas = new Model_Venta();
          $ventas_id = $ModelVentas->almacenar($dataVenta);
          

          //Procedemos a almacenar los items de la manda
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


          $bandera="".$ventas_id." |";
          if($ventas_id!=null){
              $Modeloventaitems = new Model_Ventaitems();
              for($x=0; $x<count($precios_)-1;$x++){
                  $dataItem = array(
                      "ventas_id"=>$ventas_id,
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
                  $bandera.=$Modeloventaitems->almacenar($dataItem)."_";
              }
              //Verificamos si existe 
              if($this->getRequest()->getParam("codigo_cupon")){
                $monto_cupon = $this->getRequest()->getParam("monto_cupon");
                if($monto_cupon>0){
                  $data_cupon= array(
                    "codigo_cupon"=> $this->getRequest()->getParam("codigo_cupon"),
                    "monto" => $monto_cupon,
                    "ventas_id" => $ventas_id
                  );
                  $modelMovimientosCupon = new Model_Movimientoscupon();
                  $mov_cupones_id = $modelMovimientosCupon->almacena($data_cupon);
                  $bandera.="_resultado-cupon: ".$mov_cupones_id;
              }

              }

          }

          fwrite($this->file,$bandera."\n");
          fclose($this->file);

          if($ventas_id!=null){
              $result = true;
          }

          $this->view->resultado = $ventas_id;
      }
  }

  public function imprimirventaAction(){    
    if($this->getRequest()->isGET() || $this->getRequest()->isPOST()){
        $this->_helper->layout()->disableLayout();              
        $ventas_id = $this->getRequest()->getParam("vnt");
        require (dirname(getcwd()) . '/public/tcpdf/Reportes/Ticketventa.php');        
        $Ticket= new Reporte_Ticketventa();        
        $Ticket->create($ventas_id);
    }
  
  }

    
    
}

