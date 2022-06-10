<?php

class Plugin_AuthAcl extends Zend_Controller_Plugin_Abstract {

    private $_auth;

    public function __construct(Zend_Auth $auth) {
        $this->_auth = $auth;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        if ($request->getActionName() == "listareportes" || $request->getActionName() == "editcuestionario" || $request->getActionName() == "actualizareporte" || $request->getActionName() == "logout") {
            if (!$this->_auth->hasIdentity()) {//-->>Si esta logueado         
//            if(!empty ($this->_varsSession->userId)){
//                //if(($action != 'out' && $controller !='login' ) && ($action != 'acces' && $controller!='login') && $controller != 'error' && ($controller != 'cuenta' && $action != 'activacion')){
//                if( $controller != 'navegacion' && ($controller != 'cuenta' && $action != 'activacion') && ($controller != 'login' && $action != 'out')&& ($controller != 'periodos' && $action != 'error')&& ($controller != 'periodos' && $action != 'warning')){
//                    //die('Si entra '.$controller.' '.$action);
//                    if(!empty($this->_varsSession->activarUsuario) && $this->_varsSession->activarUsuario == true){
//                        $request->setControllerName('Cuenta');
//                        $request->setActionName('activacion');
//                    }
//                    else{
//                        if(isset($this->_varsSession->peridoEscolarExistente) && $this->_varsSession->peridoEscolarExistente == false){
//                            $request->setControllerName('Periodos');
//                            $request->setActionName('error');
//                        }
//                    }
//                }
//            }                  
                $request->setControllerName('Login');
                $request->setActionName("Index");
            } else {

                $varsSession = new Zend_Session_Namespace("users");
                $controllerName = strtolower($request->getControllerName());
                $actionName = $request->getActionName();
//         $controllersAllowed = array();
//         array_push($controllersAllowed,"ajax");
//         array_push($controllersAllowed,"error");
//         array_push($controllersAllowed,"directorio");
//         array_push($controllersAllowed,"index");
//         array_push($controllersAllowed,"login");
//         array_push($controllersAllowed,"listaexcel");
//         switch($varsSession->userRol){
//             case "control":
//                 array_push($controllersAllowed,"controlpresupuestal");                
//                 break;
//             case "seguimiento":
//                 array_push($controllersAllowed,"revisioninformes");
//                 array_push($controllersAllowed,"seguimientoyevaluacion");
//                 break;
//             case "planeacion":
//                 array_push($controllersAllowed,"asistencias");
//                 array_push($controllersAllowed,"capacitaciones");
//                 array_push($controllersAllowed,"cursos");
//                 array_push($controllersAllowed,"generales");
//                 array_push($controllersAllowed,"participantes");                 
//                 break;
//         }
//         
//         if ($controllerName == "index" && $actionName == "index" && ($varsSession->userRol != "auditor" && $varsSession->userRol != "planeacion" && $varsSession->userRol != "seguimiento" && $varsSession->userRol != "subauditor_planeacion" && $varsSession->userRol != "control" && $varsSession->userRol != "seguimiento_auditor")){             
//             $request->setControllerName('Index');                     
//         }

                if ($controllerName == 'login' && $actionName != "logout") {
                    $request->setControllerName('Index');
                    $request->setActionName("listareportes");
                }
            }
        }
    }

}

?>
