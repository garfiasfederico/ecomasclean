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
class Model_Cliente Extends Zend_Db_Table{
    //put your code here
    protected $_name='clientes';
    protected $_primary='id';
    
    public function init(){
        
    }
    
   
    public function getClientes(){
        $select = $this->select();
        $select->from($this->_name);                
        $select->where("clientes.status=1");
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
            "ubicacion"=>$data["ubicacion"],
            "nombre_comercial"=>$data["nombre_comercial"],
            "tipo_cliente"=>$data["tipo_cliente"],
            "giro_comercio"=>$data["giro_comercio"],
            "direccion_entrega"=>$data["direccion_entrega"],
            "nombre_contacto"=>$data["nombre_contacto"],
            "telefono_casa_contacto"=>$data["telefono_contacto"],
            "celular_contacto"=>$data["celular_contacto"],
            "correo_electronico_contacto"=>$data["correo_electronico_contacto"],
            "confactura"=>($data["confactura"]=="on"?"1":"0"),
            "contacto"=>($data["contacto"]=="on"?"1":"0"),
            "regimenes_id"=>$data["regimen_fiscal"]

        );

        try{
            
            if($this->insert($data_))
            $clientes_id = $this->getAdapter()->lastInsertId();
            return $clientes_id;

        }catch(Exception $e){            
            return null;
        }
    }

    public function getstatus($clientes_id){
        $select = $this->select();
        $select->from($this->_name,array("status"));
        $select->where("id=".$clientes_id);
        $result = $this->fetchRow($select);
        return $result->status;
    }

    public function updatestatus($clientes_id, $status){
        $data = array(
            "status"=>$status
        );
        $this->update($data,"id=".$clientes_id);
        $status = $this->getstatus($clientes_id);
        return $status;
    }

    public function getInfo($clientes_id){
        $select  = $this->select();
        $select->from($this->_name,array("*","clientes_id"=>"clientes.id"));
        $select->setIntegrityCheck(false);
        $select->joinInner("direcciones","direcciones.id = clientes.direcciones_id",array("*"));
        //$select->joinLeft("empleado_acceso","empleado_acceso.empleados_id=empleados.id",array("*"));
        $select->where("clientes.id = ".$clientes_id);
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
            "correo_electronico"=>$data["correo_electronico"],
            "ubicacion"=>$data["ubicacion"],
            "nombre_comercial"=>$data["nombre_comercial"],
            "tipo_cliente"=>$data["tipo_cliente"],
            "giro_comercio"=>$data["giro_comercio"],
            "direccion_entrega"=>$data["direccion_entrega"],
            "nombre_contacto"=>$data["nombre_contacto"],
            "telefono_casa_contacto"=>$data["telefono_contacto"],
            "celular_contacto"=>$data["celular_contacto"],
            "correo_electronico_contacto"=>$data["correo_electronico_contacto"],
            "confactura"=>($data["confactura"]=="on"?"1":"0"),
            "contacto"=>($data["contacto"]=="on"?"1":"0"),
            "regimenes_id"=>$data["regimen_fiscal"]
        );
        try{
            
            $this->update($data_,"id = ".$data["clientes_id"]);
            return true;

        }catch(Exception $e){            
            return false;
        }
    }

    public function getClientesByRFC($rfc){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->where("rfc like '%".$rfc."%'");        
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;

    }
    
    
    
    
            
}
