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
class Model_Item Extends Zend_Db_Table{
    //put your code here
    protected $_name='items';
    protected $_primary='id';
    
    public function init(){
        
    }
    
    public function getItemsByCategoria($categoria){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->where("categorias_id =".$categoria); 
        $select->where("status = 1 ");       
        $result = $this->fetchAll($select);
        if(!empty($result)){
            return $result;
        } else {
            return null;
        }
    }
    
    public function almacenar($data,$avatar){
        $data_i = array(
            "identificador" => $data["identificador"],
            "clave" => $data["clave"], 
            "nombre" => $data["nombre"],
            "alias" => $data["alias"],
            "precio_publico" => $data["precio"],
            "existencias" => $data["existencias"],
            "categorias_id" => $data["categoria"],
            "avatar" => $avatar,
            "costo" => $data["costo"],
            "precio_mayoreo" => $data["precio_mayoreo"],
            "precio_distribuidor" => $data["precio_distribuidor"],
            "iva" => $data["iva"],            
            "unidad" => $data["unidad"]
        );

        if($this->insert($data_i))
            return true;
        else
            return false;
    }

    public function getProductos(){
        $select = $this->select();
        $select->from($this->_name);
        $select->setIntegrityCheck(false);
        $select->joinInner("catalogo_claves_sat","catalogo_claves_sat.clave = items.categorias_id",array("categoria"=>"descripcion"));
        $select->where("status=1");
        $result = $this->fetchAll($select);
        if(!empty($result)){
            return $result;
        } else {
            return null;
        }
    }

    public function getInfoItem($items_id){
        $select = $this->select();
        $select->from($this->_name);
        $select->where("id = ".$items_id);
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result;
        else
            return null;
    }

    public function actualizarItem($dataUpdate){
        try{
            $this->update($dataUpdate,"id=".$dataUpdate["id"]);
            return true;

        }catch(Exception $e){
            return null;
        }
    }

    public function bajaItem($items_id){
        $data = array(
            "status"=>0
        );
        try{
            $this->update($data,"id=".$items_id);
            return true;
        }catch(Exception $e){
            return false;
        }

    }

    public function getProductoByIdentificador($identificador){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->setIntegrityCheck(false);
        $select->joinInner("catalogo_unidades_sat","catalogo_unidades_sat.clave=items.unidad",array("unidad_descripcion"=>"catalogo_unidades_sat.descripcion"));
        $select->where("identificador like '".$identificador."'");
        $result = $this->fetchRow($select);        
        if(!empty($result))
            return $result;
        else
            return null;
    }

    public function getExistencias($items_id){
        $select = $this->select();
        $select->from($this->_name,array("existencias"));
        $select->where("id = ".$items_id);        
        $result = $this->fetchRow($select);
        if(!empty($result))
            return $result->existencias;
        else
            return null;        
    }

    public function updateExistencias($items_id,$cantidad){
        $existencias = $this->getExistencias($items_id);
        if($existencias!=null){
            $existencias = $existencias - $cantidad;
            $data = array(
                "existencias" => $existencias
            );
            try{
                $this->update($data,"id = ".$items_id);
                return true;

            }catch(Exception $e){
                return false;
            }
        }
    }

    public function getProductosByBusqueda($busqueda,$limit=null){
        $select = $this->select();
        $select->from($this->_name,array("*"));
        $select->where("identificador like '%".$busqueda."%' OR nombre like '%".$busqueda."%'");
        $select->where("status=1");
        if($limit!=null)
            $select->limit($limit);
        $result = $this->fetchAll($select);        
        if($result->count()>0)
            return $result;
        else
            return null;

    }

    public function actualizaExistencias($items_id,$cantidad){
        $existencias = $this->getExistencias($items_id);
        $existencias = $existencias + $cantidad;
        $data = array(
            "existencias"=>$existencias
        );
        try{
            $this->update($data,"id = ".$items_id);
            return true;

        }catch(Exception $e){
            return false;
        }

    }
    
    
            
}
