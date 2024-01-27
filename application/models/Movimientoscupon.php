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
class Model_Movimientoscupon Extends Zend_Db_Table{
    //put your code here
    protected $_name='movimientos_cupon';
    protected $_primary='id';
    
    public function init(){
        
    }
    

    public function almacena($data){
        $data_ = array(
            "codigo_cupon"=>$data["codigo_cupon"],
            "monto"=>$data["monto"],
            "ventas_id"=>$data["ventas_id"]            
        );
        try{            
            if($this->insert($data_))
            $cupones_mov_id = $this->getAdapter()->lastInsertId();
            //realizamos los movimientos correspondientes para el update del saldo del cupon
            $modelCupon = new Model_Cuponesdev();
            $saldo_final = $modelCupon->getSaldoFinal($data["codigo_cupon"]);
            $monto_restante = $saldo_final - $data["monto"];
            $modelCupon-> updateSaldo($data["codigo_cupon"],$monto_restante);
            return $cupones_mov_id;
        }catch(Exception $e){              
        return null;
        }
    }
    
    public function getmontopagado($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("monto_total"=>"sum(monto)"));
        $select->where ("ventas_id = ".$ventas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->monto_total;
        else
        return null;
    }
    
    public function getDisposicionesCuponByTurno($turnos_id){
        $select = $this->select();
        $select->from($this->_name,array("codigo_cupon","monto","ventas_id"));
        $select->where(" ventas_id  IN  (SELECT id FROM ventas WHERE turnos_id = ".$turnos_id.")");    
        $result = $this->fetchAll($select);        
        if($result->count()>0)
            return $result;
        else
            return null;
    }

}
