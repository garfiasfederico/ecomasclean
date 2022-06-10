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
class Model_Compradetalles Extends Zend_Db_Table{
    //put your code here
    protected $_name='compra_detalles';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacena($data){
        $data_ = array(
            "compras_id"=>$data["compras_id"],
            "items_id"=>$data["items_id"],
            "cantidad"=>$data["cantidad"]        
        );        
        try{
            $this->insert($data_);
            $compra_detalles_id = $this->getAdapter()->lastInsertId();  
            //Realizamos la actualización de existencias por cada registro
             $ModelItem = new Model_Item();
             $ModelItem->actualizaExistencias($data["items_id"],$data["cantidad"]);
            return $compra_detalles_id;        
        }catch(Exception $e){                        
            return null;
        }       
    }

    public function getDetalles($compras_id){
        $select = $this->select();
        $select->from($this->_name);
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = compra_detalles.items_id",array("nombre","unidad","existencias"));
        $select->where("compras_id = ".$compras_id);
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }
    
    
    
            
}
