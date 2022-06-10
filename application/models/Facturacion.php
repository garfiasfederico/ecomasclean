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
class Model_Facturacion Extends Zend_Db_Table{
    //put your code here
    protected $_name='facturacion';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacena($data){
        $data_ = array(
            "ventas_id" => $data["ventas_id"],
            "soap" => $data["soap"],
            "uuid" => $data["uuid"],
            "rfc_receptor" => $data["rfc_receptor"],
            "nombre_receptor" => $data["nombre_receptor"],
            "usoCFDI" => $data["usoCFDI"]
        );

        try{
            $this->insert($data_);
            return $this->getAdapter()->lastInsertId();

        }catch(Exception $e){
            return null;
        }       
    }

    public function facturada($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("id"));
        $select->where("ventas_id = ".$ventas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->id;
        else
            return null;
    }

    public function listado(){
        $select = $this->select();
        $select->from($this->_name,array("id","ventas_id","rfc_receptor","nombre_receptor","usoCFDI","fecha_registro","status","uuid"));
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }
    
    
    
            
}
