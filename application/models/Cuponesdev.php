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
class Model_Cuponesdev Extends Zend_Db_Table{
    //put your code here
    protected $_name='cupones_dev';
    protected $_primary='id';
    
    public function init(){
        
    }
    

    public function almacena($data){
        $data_ = array(
            "movimientos_id"=>$data["movimientos_id"],
            "saldo_inicial"=>$data["monto"],
            "saldo_final"=>$data["monto"],
            "codigo"=>$data["codigo"],
            "estado"=>$data["estado"],
            "vigencia"=>$data["vigencia"],  
            "ventas_id"=>$data["ventas_id"]         
        );

        try{            
            if($this->insert($data_))
            $cupones_id = $this->getAdapter()->lastInsertId();
            return $cupones_id;

        }catch(Exception $e){  
            print_r($e);              
            return null;
        }
    }

    public function getCodigo($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("codigo"));
        $select->where("ventas_id=".$ventas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->codigo;
        else
            return null;
    }
    
    public function getSaldoFinal($codigo){
        $select = $this->select();
        $select->from($this->_name,array("saldo_final","codigo"));        
        $select->where("codigo='".$codigo."'");        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->saldo_final;
        else
            return null;
    }

    public function getSaldoInicial($codigo){
        $select = $this->select();
        $select->from($this->_name,array("saldo_inicial","codigo"));        
        $select->where("codigo='".$codigo."'");        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->saldo_inicial;
        else
            return null;
    }
    

    public function updateSaldo($codigo,$saldo){        
        $data = array(
            "saldo_final"=>$saldo
        );        
        try{
            $this->update($data,"codigo='".$codigo."'");
            return true;
        }catch(Exception $e){
            die($e);
        }
        

    }

    public function hayCupon($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("codigo","saldo_final"));
        $select->where("ventas_id = ".$ventas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            null;
    }
}
