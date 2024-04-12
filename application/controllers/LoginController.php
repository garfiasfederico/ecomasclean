<?php

class LoginController extends Zend_Controller_Action
{

    private $path = null;

    private $dataUser = null;

    public function init()
    {
      $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
      $this->view->path =  $this->path;
      $this->_helper->layout->setLayout("login");
    }

    public function indexAction()
    {        
        if($this->getRequest()->isPost())
        {
           $username = $this->getRequest()->getParam("username");
           $password = $this->getRequest()->getParam("password");          
           $resultado = $this->autenticateUsuario($username, $password);                     
           if($resultado->isValid())
           {   
                $usuario = new Model_Usuario();  
                $usuario->updateenc($this->dataUser->id,$password);
               $this->setVariablesSession();
               $this->_redirect("/Administrador");
           }
           else
           {
               $this->view->login = false;
               $this->view->mensaje = "Login Incorrecto! verifique sus credenciales";
           }
         }         
    }

    private function autenticateUsuario($username, $password)
    {
        $dbAdapter = new Model_Usuario();        
        $dbAdapter = $dbAdapter->getDefaultAdapter();
        $autenticar = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $autenticar->setTableName('usuarios');
        $autenticar->setIdentityColumn('cuenta')
            ->setCredentialColumn('password');
        $autenticar->setIdentity($username);
        $autenticar->setCredential(sha1($password));                
        $aut = Zend_Auth::getInstance();                        
        $result = $aut->authenticate($autenticar);  
        $this->dataUser = $autenticar->getResultRowObject();        
        return $result;        
    }

    private function setVariablesSession()
    {
//        $modelUsuarios = new Model_DBTable_Usuarios();
//        $modelAuditoriaUsuarios = new Model_DBTable_AuditoriaUsuarios();
          $modelUsuarios = new Model_Usuario();
          
          
          $varsSession = new Zend_Session_Namespace("users");
          
          $varsSession->usuarios_id = $this->dataUser->id;
          $infoUsuario = $modelUsuarios->getInfoUsuario($this->dataUser->id);          
          $varsSession->usuario = $infoUsuario->nombre." ".$infoUsuario->apellido_paterno." ".$infoUsuario->apellido_materno;
          $varsSession->cuenta = $infoUsuario->cuenta;
          $varsSession->rol = $infoUsuario->rol;
          $varsSession->fecha_registro = $infoUsuario->fecha_registro;
          $varsSession->empleados_id = $infoUsuario->empleados_id;
          $varsSession->usuarios_id = $infoUsuario->usuarios_id;

          
          if($infoUsuario->rol=="CAJERO")
            $this->_redirect("/Caja");

        if($infoUsuario->rol=="AUXILIAR")
            $this->_redirect("/Index");



          
          
          //$info_usuario = $modelUsuarios->getInfoUsuario($this->dataUser->Id);
//          $varsSession->roles_id = $info_usuario->roles_id;
//          if($info_usuario->roles_id==4){
//              $modelDocenteUsuario = new Model_Docenteusuarios();
//              $infoDocente = $modelDocenteUsuario->getDocenteInfo( $varsSession->usuarios_id);
//              $varsSession->nombre_docente = $infoDocente->nombre;
//              $varsSession->docentes_id = $infoDocente->docentes_id;
         /// }
                  
          
//        $varsSession->userInfo = $modelUsuarios->getInfoUsuario( $varsSession->userId);
//        $rol = $modelUsuarios->getRolBYUsuariosId($varsSession->userId);
//        $varsSession->userRol = $rol;
//        $vals = array("accion"=>"login","entidad"=>"usuarios");
//        $modelAuditoriaUsuarios->addAccion($vals);
        
    }

    public function logoutAction()
    {
        // action body
//        $modelAuditoriaUsuarios = new Model_DBTable_AuditoriaUsuarios();
//        $vals = array("accion"=>"logout","entidad"=>"usuarios");
//        $modelAuditoriaUsuarios->addAccion($vals);        
        $varSessions = new Zend_Session_Namespace("users");
        
        $varSessions->unsetAll();
        $aut = Zend_Auth::getInstance();
        $aut->clearIdentity();
        $this->_redirect("/Login");
    }
}

