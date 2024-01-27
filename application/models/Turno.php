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
class Model_Turno Extends Zend_Db_Table{
    //put your code here
    protected $_name='turnos';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function getTurnoActivo($usuarios_id){
        $select = $this->select();
        $select->from($this->_name);
        $select->where("usuarios_id = ".$usuarios_id);
        $select->where("estado = '1'");
        $select->order("id DESC");        
        $result = $this->fetchRow($select);
        if(!empty($result)){
            return $result->id;
        } else {
            return null;
        }
    }

    public function abreturno($data){
        $data_ = array(
            "usuarios_id" => $data["usuarios_id"],
            "fecha" => date("Y-m-d"),
            "estado" => "1",
            "saldo_inicial" => $data["saldo_inicial"],
            "saldo_final" => $data["saldo_inicial"]
        );
        try{
            $this->insert($data_);
            $turnos_id = $this->getAdapter()->lastInsertId();
            return $turnos_id;
        }catch(Exception $e){            
            return null;
        }
    }

    public function getTurnos($usuarios_id=null){
        $select = $this->select();
        $select->from($this->_name);
        $select->setIntegrityCheck(false);
        $select->joinInner("usuarios","usuarios.id = turnos.usuarios_id",array("usuarios.empleados_id"));
        $select->joinInner("empleados","empleados.id =  usuarios.empleados_id",array("empleado"=>"concat(empleados.nombre,' ',empleados.apellido_paterno,' ',empleados.apellido_materno)"));
        if($usuarios_id!=null)
            $select->where("usuarios_id = ".$usuarios_id);
        $select->order("turnos.id DESC");
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function cerrarTurno($turnos_id,$saldo_final_manual){
        $data = array(
            "estado"=>'2',
            "saldo_final_manual" => str_replace(",","",$saldo_final_manual) ,
            "fecha_cierre"=>date("Y-m-d H:i:s")
        );
        try{
            $this->update($data,"id = ".$turnos_id);
            return true;
        }catch(Exception $e){
            return false;
        }

    }

    public function getSaldoInicial($turnos_id){
        $select = $this->select();
        $select->from($this->_name,array("saldo_inicial"));
        $select->where("turnos.id = ".$turnos_id);
        $result = $this->fetchRow($select);
        return $result->saldo_inicial;
    }
    public function getSaldoFinal($turnos_id){
        $select = $this->select();
        $select->from($this->_name,array("saldo_final"));
        $select->where("turnos.id = ".$turnos_id);
        $result = $this->fetchRow($select);
        return $result->saldo_final;
    }

    public function updateSaldoFinal($turnos_id,$monto){
        $saldo_final = $this->getSaldoFinal($turnos_id);
        $saldo_final = $saldo_final + $monto;                      
        $data = array(
            "saldo_final"=> $saldo_final
        );
        try{
            $this->update($data,"turnos.id=".$turnos_id);
            return true;
        }catch(Exception $e){
            die(print_r($e));
            return false;
        }


    }

    public function getInfoTurno($turnos_id){
        $select = $this->select();
        $select->from($this->_name);
        $select->setIntegrityCheck(false);
        $select->joinInner("usuarios","usuarios.id = turnos.usuarios_id",array("usuarios.empleados_id"));
        $select->joinInner("empleados","empleados.id =  usuarios.empleados_id",array("empleado"=>"concat(empleados.nombre,' ',empleados.apellido_paterno,' ',empleados.apellido_materno)"));
        $select->where("turnos.id = ".$turnos_id);        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }

    public function actualizaSaldoFinal($turnos_id,$saldo_final){
        $data = array(
            "saldo_final"=>$saldo_final
        );
        try{
            $this->update($data,"id = ".$turnos_id);
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function getSaldoFinalByTurno($turnos_id){
        $select = $this->select();
        $select->from($this->_name,array("saldo_final"));
        $select->where("id = ".$turnos_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->saldo_final;
        else
            return null;
    }
    
    
    
            
}
