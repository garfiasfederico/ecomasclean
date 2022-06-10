<?php

class EmpleadosController extends Zend_Controller_Action {

    private $path;
    private $varSession = null;
    public function init() {
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
        $this->view->path = $this->path;
        $this->_helper->layout->setLayout("administracion");
        $this->varSession = new Zend_Session_Namespace("users");
        if(!isset($this->varSession->usuarios_id))
        $this->redirect("/Login");

    }

    public function indexAction() {    
        
        if($this->getRequest()->isPOST()){
            $params = $this->getRequest()->getParams();
            $ModelDireccion = new Model_Direccion();
            $ModelEmpleado = new Model_Empleado();
            //$ModelEmpleadoAcceso = new Model_Empleadoacceso();
            $resultado = true;

            $direcciones_id = $ModelDireccion->almacena($params);
            if($direcciones_id!=null){
                //Procedemos a Almacenar al Empleado
                $params["direcciones_id"]=$direcciones_id;
                $empleados_id = $ModelEmpleado->almacena($params);
                if($empleados_id==null){
                //Procedemos a Almacenar el acceso del empleado
                //     $dat = array(
                //         "empleados_id"=>$empleados_id,
                //         "codigo_acceso"=>$params["codigo_acceso"],
                //         "turno"=>$params["turno"]
                //     );
                //     if($ModelEmpleadoAcceso->almacena($dat)==null)
                //         $resultado = false;
                // }else{
                    $resultado = false;
                }

            }else
            {
                $resultado = false;
            }

            $this->view->resultado = $resultado;


        }
        
    }
    public function listadoAction(){

        $ModelEmpleado = new Model_Empleado();
        $empleados = $ModelEmpleado->getEmpleados();
        $this->view->empleados = $empleados;
    }

    public function editarAction(){
        if($this->getRequest()->isPOST()){
            $empleados_id = $this->getRequest()->getParam("empleados_id");
            $ModelEmpleado = new Model_Empleado();

            $direcciones_id = $this->getRequest()->getParam("direcciones_id");
            if(isset($direcciones_id)){
                $params = $this->getRequest()->getParams();
                $resultado = true;
                $ModelDireccion = new Model_Direccion();            
                if(!$ModelDireccion->actualiza($params)){
                    $resultado = false;
                }

                if(!$ModelEmpleado->actualiza($params)){
                    $resultado = false;
                }

                // if(!$ModelEmpleadoAcceso->actualiza($params)){
                //     $resultado = false;
                // }
                $this->view->resultado = $resultado;
            }
            $infoEmpleado = $ModelEmpleado->getInfo($empleados_id);
            $this->view->infoEmpleado = $infoEmpleado;
        }
    }

    

}
