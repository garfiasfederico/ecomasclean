<?php

class InventariosController extends Zend_Controller_Action {

    private $path;

    public function init() {
        $this->varSession = new Zend_Session_Namespace("users");
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
        $this->view->path = $this->path;
        $this->_helper->layout->setLayout("administracion");
        if(!isset($this->varSession->usuarios_id))
        $this->redirect("/Login");


    }

    public function indexAction() {
        $ModelItems = new Model_Item();
        $items = $ModelItems->getProductos();
        $this->view->productos = $items;

        
    }

    public function historialAction(){

    }

    public function reporteinventarioAction(){
        $this->_helper->layout()->disableLayout();              
        require (dirname(getcwd()) . '/public/tcpdf/Reportes/Reporteinventario.php');
        $reporteInventario = new Reporte_Reporteinventario();
        $reporteInventario->create();
    }

    

}
