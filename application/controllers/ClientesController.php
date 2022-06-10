<?php

class ClientesController extends Zend_Controller_Action {

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
            $ModelCliente = new Model_Cliente();
            //$ModelEmpleadoAcceso = new Model_Empleadoacceso();
            $resultado = true;

            $direcciones_id = $ModelDireccion->almacena($params);
            if($direcciones_id!=null){
                //Procedemos a Almacenar al Empleado
                $params["direcciones_id"]=$direcciones_id;
                $clientes_id = $ModelCliente->almacena($params);
                if($clientes_id==null){
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

        $ModelCliente = new Model_Cliente();
        $clientes = $ModelCliente->getClientes();
        $this->view->clientes = $clientes;
    }

    public function editarAction(){
        if($this->getRequest()->isPOST()){
            $clientes_id = $this->getRequest()->getParam("clientes_id");
            $ModelCliente = new Model_Cliente();

            $direcciones_id = $this->getRequest()->getParam("direcciones_id");
            if(isset($direcciones_id)){
                $params = $this->getRequest()->getParams();
                $resultado = true;
                $ModelDireccion = new Model_Direccion();                
                if(!$ModelDireccion->actualiza($params)){
                    $resultado = false;
                }

                if(!$ModelCliente->actualiza($params)){
                    $resultado = false;
                }

                // if(!$ModelEmpleadoAcceso->actualiza($params)){
                //     $resultado = false;
                // }
                $this->view->resultado = $resultado;
            }
            $infoCliente = $ModelCliente->getInfo($clientes_id);
            $this->view->infoCliente = $infoCliente;
        }
    }

    

}
