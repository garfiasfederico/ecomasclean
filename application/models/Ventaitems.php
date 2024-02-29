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
class Model_Ventaitems Extends Zend_Db_Table{
    //put your code here
    protected $_name='venta_items';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacenar($data){               

        try{
            $this->insert($data);
            $id = $this->getAdapter()->lastInsertId();
            $ModelItems = new Model_Item();
            $ModelItems->updateExistencias($data["items_id"],$data["cantidad"]);
            return $id;
        }catch(Exception $e){            
            return null;
        }
    }

    public function getVentaId($ventaItemsId){
        $select = $this->select();
        $select->from($this->_name,array("ventas_id"));
        $select->where("id = ".$ventaItemsId);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->ventas_id;
        else
            return null;
    }

    public function getItemsByVenta($ventas_id){
        $select = $this->select();        
        $select->from($this->_name,array("*","venta_items_id"=>"venta_items.id"));
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = venta_items.items_id",array("nombre","unidad"));    
        $select->where("ventas_id = ".$ventas_id);
        $select->where("venta_items.status = 1");
        $select->order("venta_items.id");                
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;

    }

    public function getTotalActualizado($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("total_actualizado"=>"sum(precio)"));
        $select->where("estado<>3");
        $select->where("ventas_id = ".$ventas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->total_actualizado;
        else
            return null;
    }

    public function actualizaEstadoItem($ventaItemsId,$estado){ 
        
        $estado_actual = $this->getEstado($ventaItemsId);
        if($estado_actual!=null){
            if($estado_actual!=2 && $estado_actual!=4){
                $data = array(
                    "estado" => $estado
                );
                $result = $this->update($data,"id = ".$ventaItemsId);
                return $result;
            }else
            return false;            
        }
        return false;
        
    }

    public function getEstado($ventaItemsId){
        $select = $this->select();
        $select->from($this->_name,array("estado"));
        $select->where("id = ".$ventaItemsId);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->estado;
        else
            return null;
    }

 

    public function getItemsActivos($ventas_id,$tipo){
        $select = $this->select();
        $select->from($this->_name,array("venta_items_id"=>"venta_items.id","estado","fecha_registro"));
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = venta_items.items_id", array("*"));
        $select->joinInner("centas","ventas.id = venta_items.ventas_id",array("estado_venta"=>"ventas.estado"));       
        $select->where("ventas_id = ".$ventas_id);
        
        if($tipo==1){
            $select->where("items.categorias_id = 3 || items.categorias_id = ".$tipo);
        }else{
            $select->where("items.categorias_id = ".$tipo);
        } 

        $select->where("venta_items.estado = 1  || venta_items.estado = 4 || venta_items.estado = 2");          
        $select->where("ventas.estado = 'iniciada' || ventas.estado = 'actualizada'");              
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }

   


    public function getItemsDespachados($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("venta_items_id"=>"venta_items.id","estado","fecha_registro","cantidad","precio","iva_monto","total","descuento","subtotal"));
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = venta_items.items_id", array("nombre"));
        $select->joinInner("ventas","ventas.id = venta_items.ventas_id",array("estado_venta"=>"ventas.estado"));       
        $select->where("ventas_id = ".$ventas_id);                
        $select->where("ventas.estado = 'cobrada' OR ventas.estado = 'credito'");                
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function getMasVendidos($limit=null){        
        $select = $this->select();
        $select->from($this->_name,array("sumatoria"=>"sum(cantidad)","items_id"));
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = venta_items.items_id",array("nombre","unidad","existencias","categorias_id","avatar"));
        $select->joinLeft("categorias","categorias.id = items.categorias_id",array("descripcion"));
        $select->group("items_id");
        $select->order("sumatoria DESC");
        $select->limit($limit);        
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }


    public function getCanItemsByVenta($ventas_id,$items_id){
        $select = $this->select();
        $select->from($this->_name,array("cantidad"));
        $select->where("ventas_id = ".$ventas_id);
        $select->where("items_id = ".$items_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->cantidad;
        else
            return null;



    }
    
    
    
            
}
