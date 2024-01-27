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
class Model_Subcategoria Extends Zend_Db_Table{
    //put your code here
    protected $_name='subcategorias';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function getSubcategorias(){
        $select = $this->select();
        $select->from($this->_name);
        $result = $this->fetchAll();
        if(!empty($result)){
            return $result;
        } else {
            return null;
        }
    }
    
    
    
            
}
