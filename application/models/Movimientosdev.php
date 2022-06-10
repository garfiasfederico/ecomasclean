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
class Model_Movimientosdev Extends Zend_Db_Table{
    //put your code here
    protected $_name='movimientos_dev';
    protected $_primary='id';
    
    public function init(){
        
    }
    
   
    public function getMovimientos($ventas_id){
        $select = $this->select();
        $select->from($this->_name);  
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = items_id",array("nombre","unidad"));          
        $select->where("ventas_id=".$ventas_id);
        $select->order("id DESC");
        $result = $this->fetchAll($select);    
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function almacena($data){
        $data_ = array(
            "tipo"=>$data["tipo"],
            "ventas_id"=>$data["ventas_id"],
            "items_id"=>$data["items_id"],
            "cantidad"=>$data["cantidad"],
            "precio"=>$data["precio"],            
            "total"=>$data["total"],
            "descuento"=>$data["descuento"],
            "iva"=>$data["iva"],
            "subtotal"=>$data["subtotal"],
            "iva_monto"=>$data["iva_monto"],            
        );

        try{            
            if($this->insert($data_))
            $movimientos_id = $this->getAdapter()->lastInsertId();
            return $movimientos_id;

        }catch(Exception $e){                
            return null;
        }
    }
}
