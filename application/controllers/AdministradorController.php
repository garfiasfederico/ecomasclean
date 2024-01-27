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
      $cadena = ".jpeg,image/jpeg| 
                 .png,image/png|
                 .gif,image/gif| 
                 .tiff,image/tiff| 
                 .tif,image/tif|
                 .pdf,application/pdf|
                 .zip,application/x-zip-compressed|
                 .docx,application/msword|
                 .xsl,application/vnd.ms-excel|
                 .xslsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|
                 .rar,application/x-rar-compressed|
                 .pptx,application/vnd.openxmlformats-officedocument.presentationml.presentation|
                 .csv,text/csv|";
      $array = explode("|",$cadena);
      array_pop($array);
      foreach($array as $par){
        $val = explode(",",$par);
        $extensiones[]=$val[0];
        $tipos[]=$val[1];
      }

//      die(print_r($tipos));

      $this->view->masvendidos = $masvendidos;      
    }    

    
    
}

