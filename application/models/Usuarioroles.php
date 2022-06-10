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
class Model_Usuarioroles Extends Zend_Db_Table{
    //put your code here
    protected $_name='usuario_roles';
    protected $_primary=array("usuarios_id","roles_id");
    
    public function init(){
        
    }
    
    public function almacena($data){
        $data_ = array(
            "usuarios_id"=>$data["usuarios_id"],
            "roles_id"=>$data["roles_id"]
        );        
        try{
            if($this->insert($data_))
                return true;
        }catch(Exception $e){
            print_r($e);          
            return false;
        }
    }
    
    
    
            
}
