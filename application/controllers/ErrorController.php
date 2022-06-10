<?php

class ErrorController extends Zend_Controller_Action
{
    private $path;

    public function init(){
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                //$this->_redirect("/Error/error404");
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                //$this->_redirect("/Error/error500");
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    public function error500Action(){
        $varsSession = new Zend_Session_Namespace("users");          
        if(isset($varsSession->usuarios_id)){
            $this->_helper->layout->setLayout("administracion");            
            $this->view->url = $this->path."/Administrador";
        }
        else
            $this->view->url = $this->path."/Mandas";
    }

    public function error404Action(){
        $varsSession = new Zend_Session_Namespace("users");          
        if(isset($varsSession->usuarios_id)){
            $this->_helper->layout->setLayout("administracion");
            $this->view->url = $this->path."/Administrador";
        }
        else
            $this->view->url = $this->path."/Mandas";
            
      
    }


}

