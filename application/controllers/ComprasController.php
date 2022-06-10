<?php

class ComprasController extends Zend_Controller_Action {

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
        $ModelProveedor = new Model_Proveedor();
        $proveedores = $ModelProveedor->getProveedores(1);
        $this->view->proveedores = $proveedores;
    }

    public function listadoAction(){
        $ModelCompra = new Model_Compra();
        $compras = $ModelCompra->getCompras($this->varSession->usuarios_id);
        $this->view->compras = $compras;

        
    }

    }
