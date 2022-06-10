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
class Model_Compra Extends Zend_Db_Table{
    //put your code here
    protected $_name='compras';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function almacena($data){
        $data_ = array(
            "identificador"=>$data["identificador"],
            "fecha_compra"=>$data["fecha_compra"],
            "proveedoreS_id"=>$data["proveedores_id"],
            "total_compra"=>$data["total_compra"],
            "usuarios_id"=>$data["usuarios_id"],
            "tipo_documento"=>$data["tipo_documento"],
            "origen_pago"=>$data["origen_pago"]
        );
        $this->getAdapter()->beginTransaction();
        try{
            $this->insert($data_);
            $compras_id = $this->getAdapter()->lastInsertId();                            
            $this->getAdapter()->commit();
            return $compras_id;
        }catch(Exception $e){
            $this->getAdapter()->rollBack();
            return null;
        }       
    }

    public function getCompras($usuarios_id = null){
        $select = $this->select();
        $select->from($this->_name);
        $select->setIntegrityCheck(false);
        $select->joinInner("usuarios","usuarios.id = compras.usuarios_id",array("empleados_id"));
        $select->joinInner("empleados","empleados.id = usuarios.empleados_id",array("empleado"=>"concat(empleados.nombre,' ',empleados.apellido_paterno,' ',empleados.apellido_materno)"));
        $select->joinInner("proveedores","proveedores.id = compras.proveedores_id",array("proveedor"=>"proveedores.nombre"));
        $select->where("compras.status = 1");
        if($usuarios_id!=null)
            $select->where("usuarios_id = ".$usuarios_id);
        $result = $this->fetchAll($select);
        if($result->count()>0)
            return $result;
        else
            return null;
        
    }

    public function actualizaStatus($compras_id,$status){
        $data = array(
            "status"=>$status
        );
        try{

            $this->update($data,"id = ".$compras_id);
            return true;            
        }catch(Exception $e){
            return false;
        }
    }
    
    
    
            
}
