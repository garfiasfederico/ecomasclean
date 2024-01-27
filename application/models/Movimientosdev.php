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
        //Validamos que no se haya alcanzado el máximo de devoluciones con respecto a la cantidad del producto vendida.
        $modelVentaItems = new Model_Ventaitems();
        $cantidadIV = $modelVentaItems->getCanItemsByVenta($data["ventas_id"],$data["items_id"]);
        $cantidadIM = $this->getMovientosByVentaItem($data["ventas_id"],$data["items_id"]);
        if(($cantidadIM+$data["cantidad"]) > $cantidadIV)
            return -2;

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

    public function getMovientosByVentaItem($ventas_id,$items_id){
        $select = $this->select();
        $select->from($this->_name,array("cantidad"=>"sum(cantidad)"));
        $select->where("ventas_id = ".$ventas_id);
        $select->where("items_id = ".$items_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->cantidad;
        else
            return null;
    }

    public function deletec($movimientos_id){
    if($this->delete("id = ".$movimientos_id))
        return true;
    else
        return false;
    }
}
