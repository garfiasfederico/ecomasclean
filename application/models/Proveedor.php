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
class Model_Proveedor Extends Zend_Db_Table{
    //put your code here
    protected $_name='proveedores';
    protected $_primary='id';
    
    public function init(){
        
    }
    
   
    public function getProveedores($status = null){
        $select = $this->select();
        $select->from($this->_name);                
        if($status!=null)
            $select->where("proveedores.status = ".$status);
        $result = $this->fetchAll($select);    
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function almacena($data){
        $data_ = array(
            "nombre"=>$data["nombre"],
            "rfc"=>$data["rfc"],
            "tipo_persona"=>$data["tipo_persona"],
            "telefono_casa"=>$data["telefono_casa"],
            "telefono_celular"=>$data["telefono_celular"],            
            "correo_electronico"=>$data["correo_electronico"],
            "direcciones_id"=>$data["direcciones_id"],
        );

        try{
            
            if($this->insert($data_))
            $proveedores_id = $this->getAdapter()->lastInsertId();
            return $proveedores_id;

        }catch(Exception $e){            
            return null;
        }
    }

    public function getstatus($proveedores_id){
        $select = $this->select();
        $select->from($this->_name,array("status"));
        $select->where("id=".$proveedores_id);
        $result = $this->fetchRow($select);
        return $result->status;
    }

    public function updatestatus($proveedores_id, $status){
        $data = array(
            "status"=>$status
        );
        $this->update($data,"id=".$proveedores_id);
        $status = $this->getstatus($proveedores_id);
        return $status;
    }

    public function getInfo($proveedores_id){
        $select  = $this->select();
        $select->from($this->_name,array("*","proveedores_id"=>"proveedores.id"));
        $select->setIntegrityCheck(false);
        $select->joinInner("direcciones","direcciones.id = proveedores.direcciones_id",array("*"));
        //$select->joinLeft("empleado_acceso","empleado_acceso.empleados_id=empleados.id",array("*"));
        $select->where("proveedores.id = ".$proveedores_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }

    public function actualiza($data){
        $data_ = array(
            "nombre"=>$data["nombre"],
            "rfc"=>$data["rfc"],
            "tipo_persona"=>$data["tipo_persona"],
            "telefono_casa"=>$data["telefono_casa"],
            "telefono_celular"=>$data["telefono_celular"],            
            "correo_electronico"=>$data["correo_electronico"]  
        );
        try{
            
            $this->update($data_,"id = ".$data["proveedores_id"]);
            return true;

        }catch(Exception $e){            
            return false;
        }
    }
    
    
    
    
            
}
