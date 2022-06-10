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
class Model_Venta Extends Zend_Db_Table{
    //put your code here
    protected $_name='ventas';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacenar($data){
        $dataInsert = array(            
            "turnos_id"=>$data["turnos_id"],
            "estado"=>$data["estado"],
            "descuento"=>$data["descuento"],
            "iva"=>$data["iva"],
            "ieps"=>$data["ieps"],
            "subtotal"=>$data["subtotal"],
            "total"=>$data["total"],
            "forma_pago"=>$data["forma_pago"],
            "pago"=>$data["pago"],
            "cambio"=>$data["cambio"]
        ); 
        $this->getAdapter()->beginTransaction();       
        try{
            $this->insert($dataInsert);
            $ventas_id = $this->getAdapter()->lastInsertId();                        
            $ModelTurno = new Model_Turno();
            $ModelTurno->updateSaldoFinal($data["turnos_id"],$data["total"]);
            $this->getAdapter()->commit();
            return $ventas_id;
        }catch(Exception $e){  
            $this->getAdapter()->rollBack();                
            return null;
        }                
    }

    public function actualizar($data){
        $dataInsert = array(            
            "empleados_id"=>$data["empleados_id"],
            "estado"=>$data["estado"]            
        );
        if($this->update($dataInsert,"id=".$data["ventas_id"]))
            return true;
        else
            return false;
    }

    public function getVentasByEmpleado($empleados_id){
        $fecha_actual = date("Y-m-d");
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->where("empleados_id = ".$empleados_id);
        $select->where("fecha_registro >= '".date("Y-m-d",strtotime($fecha_actual."- 1 days"))."'");
        $select->order("id DESC");
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function getInfoVenta($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);
        $select->joinInner("turnos","turnos.id = ventas.turnos_id",array("usuarios_id"));
        $select->joinInner("usuarios","usuarios_id = usuarios.id",array("empleados_id"));
        $select->joinInner("empleados","empleados.id = empleados_id",array("empleado"=>"concat(nombre,' ',apellido_paterno,' ',apellido_materno)"));
        $select->where("ventas.id=".$ventas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }  
    
    public function actualizaTotal($ventas_id){
        $MModelVentaItems = new Model_Ventaitems();
        $total = $MModelVentaItems->getTotalActualizado($ventas_id);
        $data=array(
            "total"=>$total
        );
        return $this->update($data,"ventas.id=".$ventas_id);
    }

    public function getEstado($ventas_id){
        $select = $this->select();
        $select->from($this->_name,array("estado"));
        $select->where("id =".$ventas_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->estado;
        else
            return null;
        

    }

    public function cerrarVenta($ventas_id){

        $estado = $this->getEstado($ventas_id);
        if($estado!=null){
        if($estado == "cerrada")
            return true;
        else{
       $data = array(
            "estado"=>"cerrada"
        );

        $ModelVentaItems = new Model_Ventaitems();
        $pendientes = $ModelVentaItems->pedidosPendientes($ventas_id);
        if(!$pendientes)
            return $this->update($data,"id = ".$ventas_id);
        else
            return false;
        }
    }
    }

    public function getAllVentasActivas(){
        $fecha_actual = date("Y-m-d");
        $select = $this->select();
        $select->from($this->_name,array("*"));   
        $select->setIntegrityCheck(false);
        $select->joinInner("empleados","empleados.id = ventas.empleados_id",array("empleado"=>"concat(nombre,' ',apellido_paterno,' ',apellido_materno)"));     
        $select->where("ventas.fecha_registro >= '".date("Y-m-d",strtotime($fecha_actual."- 1 days"))."'");
        $select->where("ventas.estado = 'iniciada' || ventas.estado='actualizada'");
        $select->order("ventas.id ASC");        
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }

    public function getAllVentascerradas(){
        $fecha_actual = date("Y-m-d");
        $select = $this->select();
        $select->from($this->_name,array("*"));   
        $select->setIntegrityCheck(false);
        $select->joinInner("empleados","empleados.id = ventas.empleados_id",array("empleado"=>"concat(nombre,' ',apellido_paterno,' ',apellido_materno)"));     
//        $select->where("mandas.fecha_registro >= '".date("Y-m-d",strtotime($fecha_actual."- 1 days"))."'");
        $select->where("ventas.estado = 'cerrada'");
        $select->order("ventas.id ASC");        
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }


    public function getAllVentasByUsuario($usuarios_id=null){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);
        $select->joinInner("turnos","turnos.id = ventas.turnos_id",array("estado_turno"=>"estado","saldo_inicial","saldo_final","saldo_final_manual","usuarios_id"));
        if($usuarios_id!=null)
            $select->where("usuarios_id = ".$usuarios_id);
        $select->order("ventas.id DESC");        
        $ventas = $this->fetchAll($select);
        if($ventas->count()>0)
            return $ventas;
        else
            return null;
    }

    public function getAllVentasByTurno($turnos_id){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->where("turnos_id = ".$turnos_id);
        $select->order("ventas.id DESC");        
        $ventas = $this->fetchAll($select);
        if($ventas->count()>0)
            return $ventas;
        else
            return null;

    }

    public function getResumenByForma($turnos_id,$forma){
        $select = $this->select();
        $select->from($this->_name,array("forma_pago","sum(total) as total"));
        $select->where("turnos_id = ".$turnos_id);
        $select->where("forma_pago= '".$forma."'");
        $select->group("forma_pago");        
        $resumen = $this->fetchRow($select);
        if(!empty($resumen))
            return $resumen;
        else
            return null;

    }
    public function getResumenVentas(){
        $select = $this->select();
        $select->from($this->_name,array("fecha"=>"DATE(fecha_registro)","total"=>"SUM(total)"));
        $select->group("DATE(fecha_registro)");
        $select->order("DATE(fecha_registro) ASC");        
        //$select-> imit(5);
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
    }
            
}
