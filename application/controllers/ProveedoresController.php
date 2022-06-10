<?php

class ProveedoresController extends Zend_Controller_Action {

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
            $ModelProveedor = new Model_Proveedor();
            //$ModelEmpleadoAcceso = new Model_Empleadoacceso();
            $resultado = true;

            $direcciones_id = $ModelDireccion->almacena($params);
            if($direcciones_id!=null){
                //Procedemos a Almacenar al Empleado
                $params["direcciones_id"]=$direcciones_id;
                $proveedores_id = $ModelProveedor->almacena($params);
                if($proveedores_id==null){
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

        $ModelProveedor = new Model_Proveedor();
        $proveedores = $ModelProveedor->getProveedores();
        $this->view->proveedores = $proveedores;
    }

    public function editarAction(){
        if($this->getRequest()->isPOST()){
            $proveedores_id = $this->getRequest()->getParam("proveedores_id");
            $ModelProveedor = new Model_Proveedor();

            $direcciones_id = $this->getRequest()->getParam("direcciones_id");
            if(isset($direcciones_id)){
                $params = $this->getRequest()->getParams();
                $resultado = true;
                $ModelDireccion = new Model_Direccion();                
                if(!$ModelDireccion->actualiza($params)){
                    $resultado = false;
                }

                if(!$ModelProveedor->actualiza($params)){
                    $resultado = false;
                }

                // if(!$ModelEmpleadoAcceso->actualiza($params)){
                //     $resultado = false;
                // }
                $this->view->resultado = $resultado;
            }
            $infoProveedor = $ModelProveedor->getInfo($proveedores_id);
            $this->view->infoProveedor = $infoProveedor;
        }
    }

    public function asociarAction(){

        $ModelProveedor = new Model_Proveedor();
        $proveedores = $ModelProveedor->getProveedores(1);
        $this->view->proveedores = $proveedores;

    }

    

}
