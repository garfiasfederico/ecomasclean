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

    protected function _initMail(){
        try {
          /*  $config = array(
                'auth' => 'login',
                'username' => 'garfias.federico@gmail.com',
                'password' => 'cnqj rsdq coms jecz',
                'ssl' => 'tls',
                'port' => 587
            );*/

            $config = array(
                'auth' => 'login',
                'username' => 'administracion@ecomasclean.com.mx',
                'password' => '3c0m45cl34n.',
                'ssl' => 'tls',
                'port' => 587
            );
    
            $mailTransport = new Zend_Mail_Transport_Smtp('smtp.titan.email', $config);
            Zend_Mail::setDefaultTransport($mailTransport);
        } catch (Zend_Exception $e){
            die($e);
        }
    }

}


