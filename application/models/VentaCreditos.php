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
class Model_VentaCreditos Extends Zend_Db_Table{
    //put your code here
    protected $_name='venta_creditos';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacenar($data){
        $dataInsert = array(            
            "ventas_id"=>$data["ventas_id"],
            "abono"=>$data["abono"],
            "forma"=>$data["forma"],
            "turnos_id"=>$data["turnos_id"]            
        ); 
            $this->insert($dataInsert);
            $abonos_id = $this->getAdapter()->lastInsertId();                                    
            return $abonos_id;                             
    }

    public function getAbonos($ventas_id){
        $select = $this->select();
        $select->where("ventas_id = ".$ventas_id);
        $select->where("status = 1");
        $result = $this->fetchAll($select);
        if($result->count()>0){
            return $result;
        }else{
            return null;
        }
    }

    public function getAbonosByTurno($turnos_id){
        $select = $this->select();
        $select->where("turnos_id = ".$turnos_id);
        $select->where("status = 1");
        $result = $this->fetchAll($select);
        if($result->count()>0){
            return $result;
        }else{
            return null;
        }
    }

    public function updateStatus($abonos_id,$status){
        $dataupdate = array(            
            "status"=>$status            
        );
        if($this->update($dataupdate,"id=".$abonos_id))
            return true;
        else
            return false;
    }

    
            
}
