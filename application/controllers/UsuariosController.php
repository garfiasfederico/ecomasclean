<?php

class UsuariosController extends Zend_Controller_Action
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
        if($this->varSession->rol!="ADMINISTRADOR"){
          $this->redirect("/Index/deny");
        }
        
    }

    public function indexAction()
    {        
      $ModelUsuario = new Model_Usuario();
      $usuarios = $ModelUsuario->getUsuarios();
      $this->view->usuarios = $usuarios;

      $ModelEmpleado = new Model_Empleado();
      $empleados = $ModelEmpleado->getEmpleados();
      $this->view->empleados = $empleados;

      $ModelRoles = new Model_Rol();
      $roles = $ModelRoles->getRoles();
      $this->view->roles = $roles;

    }    

    
    
}

