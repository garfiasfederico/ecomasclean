<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EjePED
 *
 * @author garfiaspro
 */
class Model_Empleado Extends Zend_Db_Table{
    //put your code here
    protected $_name='empleados';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function getEmpleadoByCodigo($codigo){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);
        $select->joinInner("empleado_acceso","empleados_id=empleados.id",array("*"));
        $select->where("codigo_acceso = '".$codigo."'");   
        $select->where("status = 1");   
        $result = $this->fetchRow($select);
        if(!empty($result)){
            return $result;
        } else {
            return null;
        }
    }

    public function getEmpleados(){
        $select = $this->select();
        $select->from($this->_name);                
        $select->where("empleados.status=1");
        $result = $this->fetchAll($select);    
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function almacena($data){
        $data_ = array(
            "nombre"=>$data["nombre"],
            "apellido_paterno"=>$data["apellido_paterno"],
            "apellido_materno"=>$data["apellido_materno"],
            "curp"=>$data["curp"],
            "direcciones_id"=>$data["direcciones_id"],            
            "telefono_casa"=>$data["telefono_casa"],
            "telefono_celular"=>$data["telefono_celular"],
            "sexo"=>$data["sexo"],
            "correo_electronico"=>$data["correo_electronico"]
        );

        try{
            
            if($this->insert($data_))
            $empleados_id = $this->getAdapter()->lastInsertId();
            return $empleados_id;

        }catch(Exception $e){            
            return null;
        }
    }

    public function getstatus($empleados_id){
        $select = $this->select();
        $select->from($this->_name,array("status"));
        $select->where("id=".$empleados_id);
        $result = $this->fetchRow($select);
        return $result->status;
    }

    public function updatestatus($empleados_id, $status){
        $data = array(
            "status"=>$status
        );
        $this->update($data,"id=".$empleados_id);
        $status = $this->getstatus($empleados_id);
        return $status;
    }

    public function getInfo($empleados_id){
        $select  = $this->select();
        $select->from($this->_name,array("*","empleados_id"=>"empleados.id"));
        $select->setIntegrityCheck(false);
        $select->joinInner("direcciones","direcciones.id = empleados.direcciones_id",array("*"));
        //$select->joinLeft("empleado_acceso","empleado_acceso.empleados_id=empleados.id",array("*"));
        $select->where("empleados.id = ".$empleados_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }

    public function actualiza($data){
        $data_ = array(
            "nombre"=>$data["nombre"],
            "apellido_paterno"=>$data["apellido_paterno"],
            "apellido_materno"=>$data["apellido_materno"],
            "curp"=>$data["curp"],
            "direcciones_id"=>$data["direcciones_id"],            
            "telefono_casa"=>$data["telefono_casa"],
            "telefono_celular"=>$data["telefono_celular"],
            "sexo"=>$data["sexo"],
            "correo_electronico"=>$data["correo_electronico"]
        );
        try{
            
            $this->update($data_,"id = ".$data["empleados_id"]);
            return true;

        }catch(Exception $e){            
            return false;
        }
    }
    
    
    
    
            
}
