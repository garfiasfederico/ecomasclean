<?php

class ProductosController extends Zend_Controller_Action
{

    private $path = null;    

    public function init()
    {      
      $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
      $this->view->path =  $this->path;
      $this->_helper->layout->setLayout("administracion");
      $this->varSession = new Zend_Session_Namespace("users");
      if(!isset($this->varSession->usuarios_id))
        $this->redirect("/Login");
    }

    public function indexAction()
    {        
      $ModelClaves = new Model_Claves();
      $ModelUnidades = new Model_Unidades();
      $categorias = $ModelClaves->getClaves();
      $unidades = $ModelUnidades->getUnidades();
      $this->view->categorias = $categorias;
      $this->view->unidades = $unidades;

      if($this->getRequest()->isPOST()){
      /*  $categoria = $this->getRequest()->getParam("categoria");
        $folder = "";
        switch($categoria){ 
          case 1:
            $folder="automotriz/";
            break;
          case 2:
            $folder="lavanderia/";
            break;
          case 3:
            $folder="hogar/";
            break;
          case 4:
            $folder="corporales/";
            break;
          case 5:
            $folder="especiales/";
              break;
        }*/
        //Realizamos el almacenamiento del archivo si existe
        $avatar = "";        
        if (isset($_FILES['fileavatar'])) {         
          if (!($_FILES['fileavatar']['name']=="")) {                                        
              $errors = array();
              $file_name = $_FILES['fileavatar']['name'];
              $file_size = $_FILES['fileavatar']['size'];
              $file_tmp = $_FILES['fileavatar']['tmp_name'];
              $file_type = $_FILES['fileavatar']['type'];
              
              if ($file_size > 2097152) {
                  $errors[] = 'El peso del archivo supera los 2MB permitidos';
              }
              if (empty($errors) == true) {
                  move_uploaded_file($file_tmp, "images/items/".$file_name);
                  $avatar = $file_name;
              } else {
                  $avatar = "";
              }
          }                     
      }      
      
      $data = $this->getRequest()->getParams();
      $MItems = new Model_Item();

      if($MItems->almacenar($data,$avatar))
        $this->view->result= true;
      else
        $this->view->result= false;
      }
      
    }    

    public function catalogoAction(){

      $MProductos = new Model_Item();
      $productos = $MProductos->getProductos();
      $this->view->productos = $productos;

    }

    public function editarAction(){
      if($this->getRequest()->isPOST()){
        $ModelClaves = new Model_Claves();
      $ModelUnidades = new Model_Unidades();
      $categorias = $ModelClaves->getClaves();
      $unidades = $ModelUnidades->getUnidades();
      $this->view->categorias = $categorias;
      $this->view->unidades = $unidades;
      
        $items_id = $this->getRequest()->getParam("items_id");
        $ModelItems = new Model_Item();
        
        $nombre = $this->getRequest()->getParam("nombre");
        if(isset($nombre)){
          if (isset($_FILES['fileavatar'])){
            $file_size = $_FILES['fileavatar']['size'];
            if($file_size>0){ //Almacenamos el nuevo avatar
              /*$categoria = $this->getRequest()->getParam("categoria");
              $folder = "";
              switch($categoria){
                case 1:
                  $folder="automotriz/";
                  break;
                case 2:
                  $folder="lavanderia/";
                  break;
                case 3:
                  $folder="hogar/";
                  break;
                case 4:
                  $folder="corporales/";
                  break;
                case 5:
                  $folder="especiales/";
                    break;
              }*/
              //Realizamos el almacenamiento del archivo si existe
              $avatar = "";
              $errors = array();
              $file_name = $_FILES['fileavatar']['name'];
              $file_size = $_FILES['fileavatar']['size'];
              $file_tmp = $_FILES['fileavatar']['tmp_name'];
              $file_type = $_FILES['fileavatar']['type'];
              
              if ($file_size > 2097152) {
                  $errors[] = 'El peso del archivo supera los 2MB permitidos';
              }
              if (empty($errors) == true) {
                  move_uploaded_file($file_tmp, "images/items/".$file_name);
                  $avatar = $file_name;
              } else {
                  $avatar = "";
              }
            }            
          }         
          $identificador = $this->getRequest()->getParam("identificador");
          $clave = $this->getRequest()->getParam("clave");

          $nombre = $this->getRequest()->getParam("nombre");
          $alias = $this->getRequest()->getParam("alias");
          $precio = $this->getRequest()->getParam("precio");
          $existencias = $this->getRequest()->getParam("existencias");
          $costo = $this->getRequest()->getParam("costo");
          $precio_mayoreo = $this->getRequest()->getParam("precio_mayoreo");
          $iva = $this->getRequest()->getParam("iva");          
          $unidad = $this->getRequest()->getParam("unidad");
          $precio_distribuidor = $this->getRequest()->getParam("precio_distribuidor");

          $dataUpdate = array(
            "id"=>$items_id,
            "alias"=>$alias,
            "identificador"=>$identificador,
            "clave"=>$clave,
            "nombre"=>$nombre,
            "alias"=>$alias,
            "precio_publico"=>$precio,
            "existencias"=>$existencias,
            "costo"=>$costo,
            "precio_mayoreo"=>$precio_mayoreo,
            "iva"=>$iva,            
            "unidad"=>$unidad,
            "precio_distribuidor"=>$precio_distribuidor
          );

          if($avatar!=""){
            $dataUpdate["avatar"]=$avatar;
          }

          //die(print_r($dataUpdate));

          $resultUpdate = $ModelItems->actualizarItem($dataUpdate);
          $this->view->resultUpdate = $resultUpdate;

        } 
                
        $infoItem = $ModelItems->getInfoItem($items_id);
        $this->view->infoItem = $infoItem; 
      }
       }

       public function eliminarAction(){
         if($this->getRequest()->isPOST()){
           $items_id = $this->getRequest()->getParam("items_id");          
           $ModelItems = new Model_Item();
           $infoItem = $ModelItems->getInfoItem($items_id);
           $this->view->infoItem = $infoItem;
           
          $confirmasi= $this->getRequest()->getParam("confirmasi");
          if(isset($confirmasi)){
            $resultado = $ModelItems->bajaItem($items_id);
            $this->view->resultado = $resultado;
          }


         }
       }
    
}

