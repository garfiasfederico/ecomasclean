<?php

class IndexController extends Zend_Controller_Action {

    private $path;
    private $varSession;

    public function init() {
        $this->_helper->layout->setLayout("administracion");
        //$this->_helper->layout()->disableLayout();
        $this->varSession = new Zend_Session_Namespace("users");
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
        $this->view->path = $this->path;
        if(!isset($this->varSession->usuarios_id))
        $this->redirect("/Login");


    }

    public function indexAction() {
        
        
    }

    

}
