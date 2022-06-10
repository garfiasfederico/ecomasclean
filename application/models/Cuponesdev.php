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
            "monto"=>$data["monto"],
            "codigo"=>$data["codigo"],
            "estado"=>$data["estado"],
            "vigencia"=>$data["vigencia"]            
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

    public function getCodigo($movimientos_id){
        $select = $this->select();
        $select->from($this->_name,array("codigo"));
        $select->where("movimientos_id=".$movimientos_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->codigo;
        else
            return null;
    }
    
    public function getSaldoInicial($codigo){
        $select = $this->select();
        $select->from($this->_name,array("saldo_inicial"=>"sum(monto)","codigo"));        
        $select->where("codigo='".$codigo."'");
        $select->group("codigo");
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->saldo_inicial;
        else
            return null;
    }
}
