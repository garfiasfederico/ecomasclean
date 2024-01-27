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
class Model_Direccion Extends Zend_Db_Table{
    //put your code here
    protected $_name='direcciones';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacena($data){
        $data_ = array(
            "calle"=>$data["calle"],
            "num_interno"=>$data["num_interno"],
            "num_externo"=>$data["num_externo"],
            "colonia"=>$data["colonia"],
            "localidad"=>$data["localidad"],
            "municipio"=>$data["municipio"],
            "estado"=>$data["estado"],
            "cp"=>$data["cp"],
            "referencia"=>$data["referencia"]
        );        
        try{
            if($this->insert($data_))
                return $this->getAdapter()->lastInsertId();
        }catch(Exception $e){
            //die(print_r($e));
            return null;
        }
    }

    public function actualiza($data){
        $data_ = array(
            "calle"=>$data["calle"],
            "num_interno"=>$data["num_interno"],
            "num_externo"=>$data["num_externo"],
            "colonia"=>$data["colonia"],
            "localidad"=>$data["localidad"],
            "municipio"=>$data["municipio"],
            "estado"=>$data["estado"],
            "cp"=>$data["cp"],
            "referencia"=>$data["referencia"],
        );        
        try{
            $this->update($data_,"id = ".$data["direcciones_id"]);
            return true;              
        }catch(Exception $e){
            //die(print_r($e));
            return false;
        }

    }
    
    
    
            
}
