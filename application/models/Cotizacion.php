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
class Model_Cotizacion Extends Zend_Db_Table{
    //put your code here
    protected $_name='cotizaciones';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacenar($data){
        $turnos_id = $data["turnos_id"];
        $dataInsert = array(            
            "turnos_id"=>$turnos_id,  
            "descuento"=>$data["descuento"],
            "iva"=>$data["iva"],            
            "subtotal"=>$data["subtotal"],
            "total"=>$data["total"],
            "clientes_id" => $data["clientes_id"],
            "comentarios" => $data["comentarios"],  
            "tipo_precio" => $data["tipo_precio"],
            "vigencia" => $data["vigencia"]  
        ); 
        $this->getAdapter()->beginTransaction();       
        try{
            $this->insert($dataInsert);
            $cotizaciones_id = $this->getAdapter()->lastInsertId();                                    
            $this->setFolio($cotizaciones_id,$turnos_id);
            $this->getAdapter()->commit();
            return $cotizaciones_id;
        }catch(Exception $e){  
            $this->getAdapter()->rollBack();                
            return null;
        }                
    }

    public function setFolio($id,$turnos_id){
       $folio = $turnos_id.$id;
       $dat = array(
        "folio"=>$folio
       );      
       try{
        $this->update($dat,"id = ".$id);
       }catch(Exception $e){
        return false;
       }
    } 
    
    public function getInfoCotizacion($cotizaciones_id){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);  
        $select->joinLeft("clientes","clientes.id = cotizaciones.clientes_id",array("nombre_cliente"=>"clientes.nombre"));     
        $select->joinInner("turnos","turnos.id = cotizaciones.turnos_id",array("usuarios_id"));
        $select->joinInner("usuarios","usuarios_id = usuarios.id",array("empleados_id"));
        $select->joinInner("empleados","empleados.id = empleados_id",array("empleado"=>"concat(empleados.nombre,' ',apellido_paterno,' ',apellido_materno)"));
        $select->where("cotizaciones.id=".$cotizaciones_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    } 
    public function getInfoCotizacionByFolio($cotizacion){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);  
        $select->joinLeft("clientes","clientes.id = cotizaciones.clientes_id",array("nombre_cliente"=>"clientes.nombre"));     
        $select->joinInner("turnos","turnos.id = cotizaciones.turnos_id",array("usuarios_id"));
        $select->joinInner("usuarios","usuarios_id = usuarios.id",array("empleados_id"));
        $select->joinInner("empleados","empleados.id = empleados_id",array("empleado"=>"concat(empleados.nombre,' ',apellido_paterno,' ',apellido_materno)"));
        $select->where("cotizaciones.folio='".$cotizacion."'");        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->toArray();
        else
            return null;
    }

    public function getCotizaciones(){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);  
        $select->joinLeft("clientes","clientes.id = cotizaciones.clientes_id",array("cliente"=>"clientes.nombre"));     
        $select->joinInner("turnos","turnos.id = cotizaciones.turnos_id",array("usuarios_id"));
        $select->joinInner("usuarios","usuarios_id = usuarios.id",array("empleados_id"));
        $select->joinInner("empleados","empleados.id = empleados_id",array("empleado"=>"concat(empleados.nombre,' ',apellido_paterno,' ',apellido_materno)"));        
        $result = $this->fetchAll($select);
        if(($result->count() > 0))
            return $result;
        else
            return null;
    } 
}
