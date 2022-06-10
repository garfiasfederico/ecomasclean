<?php

class AdministradorController extends Zend_Controller_Action
{

    private $path = null;    
    private $varSession = null;
    public function init()
    {      
      $this->varSession = new Zend_Session_Namespace("users");
      $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
      $this->view->path =  $this->path;
      $this->_helper->layout->setLayout("administracion");
      if(!isset($this->varSession->usuarios_id))
        $this->redirect("/Login");
        
    }

    public function indexAction()
    {        
 
      $ModelVentaItems = new Model_Ventaitems();
      $masvendidos = $ModelVentaItems->getMasVendidos(5);
      $this->view->masvendidos = $masvendidos;
    }    

    
    
}

