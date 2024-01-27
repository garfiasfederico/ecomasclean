<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author garfiaspro
 */
class Model_Retiro Extends Zend_Db_Table{
    //put your code here
    protected $_name='retiros';
    protected $_primary='id';
    
    public function init(){
        
    }
    
       
    public function almacena($data){
        $data_ = array(
            "turnos_id"=>$data["turnos_id"],
            "monto"=>$data["monto_retiro"],            
            "nuevo_saldo"=>$data["saldo_nuevo"],
            "motivo"=>$data["motivo"], 
            "tipo"=>$data["tipo"] 

        );

        try{            
            $this->getAdapter()->beginTransaction();
            if($this->insert($data_))
            $retiros_id = $this->getAdapter()->lastInsertId();
            $ModelTurno = new Model_Turno();
            $ModelTurno->actualizaSaldoFinal($data["turnos_id"],$data["saldo_nuevo"]);            
            $this->getAdapter()->commit();
            return $retiros_id;
        }catch(Exception $e){            
            $this->getAdapter()->rollBack();
            return null;
        }
    }

    public function getSumaRetirnos($turnos_id){
        $select = $this->select();
        $select->from($this->_name,array("monto_retiros"=>"sum(monto)"));
        $select->where("status = 1");
        $select->where("turnos_id = ".$turnos_id);        
        $result = $this->fetchRow($select);
        if(!empty($result)){
            return $result->monto_retiros;
        }
        else{
            return null;
        }
    }

    public function getRetiros($turnos_id){
        $select = $this->select();
        $select->from($this->_name);
        $select->where("turnos_id = ".$turnos_id);
        $select->where("status = 1");
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function getInfo($retiros_id){
        $select = $this->select();
        $select->from($this->_name);
        $select->where("id = ".$retiros_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }

    public function cancelaRetiro($retiros_id){
        $infoRetiro = $this->getInfo($retiros_id);
        $modelTurno = new Model_Turno();
        if($infoRetiro!=null){
            $saldoFinal = $modelTurno->getSaldoFinal($infoRetiro->turnos_id);
            $nuevoSaldo = $saldoFinal + $infoRetiro->monto;
            if($modelTurno->actualizaSaldoFinal($infoRetiro->turnos_id,$nuevoSaldo)){
                $data=array(
                    "status"=>0
                );
                try{
                    $this->update($data,"id = ".$retiros_id);
                    return true;
                }catch(Exception $e){
                    return false;
                }
            }
            else
                return false;            
        }


    }

    public function getIdByTipo($tipo){
        $select = $this->select();
        $select->from($this->_name,array("id"));
        $select->where("tipo = '".$tipo."'");        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->id;
        else
            return null;
    }

    
    
    
    
    
            
}
