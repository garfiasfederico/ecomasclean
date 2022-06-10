<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paciente
 *
 * @author federicogarfias
 */
class Model_Usuario extends Zend_Db_Table{
    protected $_name = "usuarios";
    protected $_primary = "id";
    
    public function getInfoUsuario($usuarios_id){
        $select = $this->select();
        $select->from($this->_name,array("*")); 
        $select->setIntegrityCheck(false);
        $select->joinInner("empleados","empleados.id=usuarios.empleados_id",array("*")); 
        $select->joinInner("usuario_roles","usuario_roles.usuarios_id = usuarios.id",array("*"));
        $select->joinInner("roles","usuario_roles.roles_id = roles.id",array("rol"=>"roles.descripcion"));
        $select->where("usuarios.id = ".$usuarios_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }
    
    public function getPassword($nombre){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->where("nombre='".$nombre."'");        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;                
    }

    public function getUsuarios(){
        $select = $this->select();
        $select->from($this->_name);
        $select->setIntegrityCheck(false);
        $select->joinInner("empleados","empleados.id = usuarios.empleados_id",array("nombre_empleado"=>"concat(nombre,' ',apellido_paterno,' ',apellido_materno)"));
        $select->joinInner("usuario_roles","usuario_roles.usuarios_id=usuarios.id",array("*"));
        $select->joinInner("roles","roles.id=usuario_roles.roles_id",array("rol"=>"descripcion"));
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function getUsuario($usuarios_id,$password){
        $select = $this->select();
        $select->from($this->_name);
        $select->where("id = ".$usuarios_id);
        $select->where("password = '".sha1($password)."'");        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return true;
        else
            return false;    
    }

    public function actualizaPassword($usuarios_id,$password){
        $data = array(
            "password"=>sha1($password)
        );
        try{
            $this->update($data,"id = ".$usuarios_id);
            return true;

        }catch(Exception $e){
            return false;
        }

    }

    public function almacenaUsuario($data){
        $data_ = array(
            "cuenta"=>$data["cuenta"],
            "password"=>sha1($data["password"]),
            "empleados_id"=>$data["empleados_id"]
        );

        $this->getAdapter()->beginTransaction();
        try{
            if($this->insert($data_)){
              $usuarios_id = $this->getAdapter()->lastInsertId();
              $data["usuarios_id"] = $usuarios_id;
              $ModelUsuarioRoles = new Model_Usuarioroles();
              $resultado = $ModelUsuarioRoles->almacena($data);              
              $this->getAdapter()->commit();
              return $resultado;              
            }else{
                return false;
            }
        }catch(Exception $e){
            print_r($e);
            $this->getAdapter()->rollBack();
            return false;
        }
        

    }

    public function getstatus($usuarios_id){
        $select = $this->select();
        $select->from($this->_name,array("status"));
        $select->where("id=".$usuarios_id);
        $result = $this->fetchRow($select);
        return $result->status;
    }

    public function updatestatus($usuarios_id, $status){
        $data = array(
            "status"=>$status
        );
        $this->update($data,"id=".$usuarios_id);
        $status = $this->getstatus($usuarios_id);
        return $status;
    }
    
    
        
}
