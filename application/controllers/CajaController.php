<?php

class CajaController extends Zend_Controller_Action {

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

      if($this->varSession->rol == "CAJERO")
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
      
    }





    

}
