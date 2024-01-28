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
class Model_Entrada Extends Zend_Db_Table{
    //put your code here
    protected $_name='entradas';
    protected $_primary='id';
    
    public function init(){
        
    }
    
       
    public function almacena($data){
        $data_ = array(
            "turnos_id"=>$data["turnos_id"],
            "monto"=>$data["monto_entrada"],            
            "nuevo_saldo"=>$data["saldo_nuevo"],
            "motivo"=>$data["motivo"]            
        );

        try{            
            $this->getAdapter()->beginTransaction();
            if($this->insert($data_))
            $entradas_id = $this->getAdapter()->lastInsertId();
            $ModelTurno = new Model_Turno();
            $ModelTurno->actualizaSaldoFinal($data["turnos_id"],$data["saldo_nuevo"]);            
            $this->getAdapter()->commit();
            return $entradas_id;
        }catch(Exception $e){            
            $this->getAdapter()->rollBack();
            return null;
        }
    }

    public function getSumaEntradas($turnos_id){
        $select = $this->select();
        $select->from($this->_name,array("monto_entradas"=>"sum(monto)"));
        $select->where("status = 1");
        $select->where("turnos_id = ".$turnos_id);        
        $result = $this->fetchRow($select);
        if(!empty($result)){
            return $result->monto_entradas;
        }
        else{
            return null;
        }
    }

    public function getEntradas($turnos_id){
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

    public function getInfo($entradas_id){
        $select = $this->select();
        $select->from($this->_name);
        $select->where("id = ".$entradas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }

    public function cancelaEntrada($entradas_id){
        $infoEntrada = $this->getInfo($entradas_id);
        $modelTurno = new Model_Turno();
        if($infoEntrada!=null){
            $saldoFinal = $modelTurno->getSaldoFinal($infoEntrada->turnos_id);
            $nuevoSaldo = $saldoFinal - $infoEntrada->monto;
            if($modelTurno->actualizaSaldoFinal($infoEntrada->turnos_id,$nuevoSaldo)){
                $data=array(
                    "status"=>0
                );
                try{
                    $this->update($data,"id = ".$entradas_id);
                    return true;
                }catch(Exception $e){
                    return false;
                }
            }
            else
                return false;            
        }
    }            
}
