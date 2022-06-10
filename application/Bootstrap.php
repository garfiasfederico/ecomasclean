<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload(){
        $moduleLoader = new Zend_Application_Module_Autoloader(array('namespace'=>'','basePath'=>APPLICATION_PATH));
        return $moduleLoader;
    }        
    protected function _initRoutes(){
       $this->bootstrap('FrontController');
       $frontController= Zend_Controller_Front::getInstance();
       $auth= Zend_Auth::getInstance();
       $frontController->registerPlugin(new Plugin_AuthAcl($auth));
       date_default_timezone_set('America/Mexico_City');
       setlocale(LC_TIME, 'es_MX.utf-dd8'); 
    }

}


