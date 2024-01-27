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
class Model_Cotizacionitems Extends Zend_Db_Table{
    //put your code here
    protected $_name='cotizacion_detalles';
    protected $_primary = array("cotizaciones_id","items_id");
    
    public function init(){
        
    }
    
    public function almacenar($data){               

        try{
            $this->insert($data);
            $id = $this->getAdapter()->lastInsertId();
            return $id;
        }catch(Exception $e){            
            return null;
        }
    }

    public function getItemsByCotizacion($cotizaciones_id){
        $select = $this->select();        
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);
        $select->joinInner("items","items.id = cotizacion_detalles.items_id",array("nombre","unidad","clave"));    
        $select->where("cotizaciones_id = ".$cotizaciones_id);        
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;

    }   
    

}
