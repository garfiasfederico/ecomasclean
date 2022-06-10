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
class Model_Proveedorproductos Extends Zend_Db_Table{
    //put your code here
    protected $_name='proveedor_productos';
    protected $_primary=array('proveedores_id','items_id');
    
    public function init(){
        
    }

    public function almacena($data){
        $data_ = array(
            "proveedores_id"=>$data["proveedores_id"],
            "items_id"=>$data["items_id"]            
        );
        try{            
            if($this->insert($data_))            
            return true;
        }catch(Exception $e){                        
            return false;
        }
    }

   
    public function getAsociados($proveedores_id){
        $select  = $this->select();
        $select->from($this->_name);
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = proveedor_productos.items_id",array("id","nombre","existencias"));        
        $select->where("proveedores_id = ".$proveedores_id);
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }
    
    public function limpiaAsociados($proveedores_id){
        try{
            $this->delete("proveedores_id = ".$proveedores_id);
            return true;
        }catch(Exception $e){
            print_r($e);
            return false;
        }
        
    }
}
