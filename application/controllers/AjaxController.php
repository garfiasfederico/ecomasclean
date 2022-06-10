<?php

class AjaxController extends Zend_Controller_Action {

    private $path;
    private $file;

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->disableLayout();
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
        $this->view->path = $this->path;
        $this->file = fopen("logmandas.txt","a") or die("No puede crearse el archivo de log");
        $this->fileCancelaCompra = fopen("logcancelacompras.txt","a") or die("No puede crearse el archivo de log");
    }

    public function indexAction() {
        // action body
    }

    

    public function showimagetempAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            if (isset($_FILES['file'])) {
                $files = glob('images/items/temp/*'); //obtenemos todos los nombres de los ficheros
                foreach($files as $file){
                    if(is_file($file))
                    unlink($file); //elimino el fichero
                }
                //die($_FILES['archivo']['name']==""?"Nada":"Algo");
                if (!($_FILES['file']['name']=="")) {                                        
                    $errors = array();
                    $file_name = $_FILES['file']['name'];
                    $file_size = $_FILES['file']['size'];
                    $file_tmp = $_FILES['file']['tmp_name'];
                    $file_type = $_FILES['file']['type'];

                    //  $file_ext=strtolower(end(explode('.',$_FILES['archivo']['name'])));
                    // $extensions= array("jpeg","jpg","png");
                    // if(in_array($file_ext,$extensions)=== false){
                    //    $errors[]="Extensión no permitida, por favor elija una imagen JPG o PNG.";
                    // }

                    if ($file_size > 2097152) {
                        $errors[] = 'El peso del archivo supera los 2MB permitidos';
                    }

                    if (empty($errors) == true) {
                        move_uploaded_file($file_tmp, "images/items/temp/" . $file_name);
                        $mensaje = "images/items/temp/" . $file_name;
                    } else {
                        print_r($errors);
                    }
                }                     
            }
            $this->view->mensaje=$mensaje;
        }

    }

    public function almacenaventaAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $varsSessions = new Zend_Session_Namespace("users");

            $items = $this->getRequest()->getParam("items");
            $precios = $this->getRequest()->getParam("precios");        
            $empleados_id = $varsSessions->empleados_id;
            $estado = "iniciada";
            $total = 0;                        
                        
            $precios_a = explode("|",$precios);
            
            foreach($precios_a as $precio)
                $total += doubleval($precio);                            

            $dataManda = array(
                "mesa" => $mesa,
                "empleados_id" => $empleados_id,
                "estado" => $estado,
                "total" => $total
            );
            $MManda = new Model_Manda();
            $mandas_id = $MManda->almacenar($dataManda);

            //Procedemos a almacenar los items de la manda

            $precios_ = explode("|",$precios);
            $items_ = explode("|",$items);
            $bandera="".$mandas_id." |";
            if($mandas_id!=null){
                $MMandaItems = new Model_Mandaitems();
                for($x=0; $x<count($precios_)-1;$x++){
                    $dataItem = array(
                        "mandas_id"=>$mandas_id,
                        "items_id"=>$items_[$x],
                        "estado"=>"1",
                        "precio"=>$precios_[$x]
                    );
                    $bandera.=$MMandaItems->almacenar($dataItem)."_";
                }
            }

            fwrite($this->file,$bandera."\n");
            fclose($this->file);

            if($mandas_id!=null){
                $result = true;
            }

            $this->view->resultado = $result;
        }
    } 

    public function actualizaestatusempleadoAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $empleados_id = $this->getRequest()->getParam("empleados_id");
            $status = $this->getRequest()->getParam("status");
            $ModelEmpleado = new Model_Empleado();
            $status = $ModelEmpleado->updatestatus($empleados_id,$status);
            $this->view->status = $status;            
        }
    }
    public function actualizaestatusclienteAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $clientes_id = $this->getRequest()->getParam("clientes_id");
            $status = $this->getRequest()->getParam("status");
            $ModelCliente = new Model_Cliente();
            $status = $ModelCliente->updatestatus($clientes_id,$status);
            $this->view->status = $status;            
        }
    }
    public function actualizaestatusproveedorAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $proveedores_id = $this->getRequest()->getParam("proveedores_id");
            $status = $this->getRequest()->getParam("status");
            $ModelProveedor = new Model_Proveedor();
            $status = $ModelProveedor->updatestatus($proveedores_id,$status);
            $this->view->status = $status;            
        }
    }


    public function actualizaestatususuarioAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $usuarios_id = $this->getRequest()->getParam("usuarios_id");
            $status = $this->getRequest()->getParam("status");
            $ModelUsuario = new Model_Usuario();
            $status = $ModelUsuario->updatestatus($usuarios_id,$status);
            $this->view->status = $status;            
        }
    }

    public function actualizapasswordAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $usuarios_id = $this->getRequest()->getParam("usuarios_id");
            $password_actual = $this->getRequest()->getParam("password_actual");
            $password_nueva = $this->getRequest()->getParam("password_nueva");
            $password_confirma = $this->getRequest()->getParam("password_confirma");
            $modelUsuario = new Model_Usuario();
            $existe = $modelUsuario->getUsuario($usuarios_id,$password_actual);
            if($existe){
                $actualizada = $modelUsuario->actualizaPassword($usuarios_id,$password_nueva);
                if($actualizada)
                    $this->view->resultado = "actualizada";
                else
                    $this->view->resultado = "error";

            }else{
                $this->view->resultado = "incorrecta";
            }
        }
    }

    public function agregausuarioAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $modelUsuario = new Model_Usuario();
            $resultado = $modelUsuario->almacenaUsuario($params);
            $this->view->resultado = $resultado;
            $this->view->cuenta = $params["cuenta"];
        }
    }

    public function getrowproductoAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $busquedaproducto = $this->getRequest()->getParam("busquedaproducto");
            $modelItems = new Model_Item();
            $rowItem = $modelItems->getProductoByIdentificador($busquedaproducto);
            $this->view->rowItem = $rowItem;            
        }
    }

    public function buscaproductosAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $busqueda = $this->getRequest()->getParam("busqueda");
            $ModelProducto = new Model_Item();
            $productos = $ModelProducto->getProductosByBusqueda($busqueda);
            $this->view->productos = $productos;

        }
        
    }

    public function almacenaasociadosAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $proveedores_id = $this->getRequest()->getParam("proveedores_id");
            $items_id = $this->getRequest()->getParam("items");
            $items = explode("|",$items_id);  
            array_pop($items);                    
            $ModelProveedorProductos = new Model_Proveedorproductos();
            $ModelProveedorProductos->limpiaAsociados($proveedores_id);
            $result = true;     
            $resultados = "";       
            foreach($items as $item){
                if(strlen($item)>0){
                $data = array(
                    "proveedores_id"=>$proveedores_id,
                    "items_id"=>$item
                );
                if(!$ModelProveedorProductos->almacena($data)){                    
                    $result = false;
                }
                    
                }
            }            
            $this->view->resultado = $result;
        }
    }

    public function loadasociadosAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $proveedores_id = $this->getRequest()->getParam("proveedores_id");
            $ModelProveedorProductos = new Model_Proveedorproductos();
            $asociados = $ModelProveedorProductos->getAsociados($proveedores_id);
            $this->view->asociados = $asociados;
        }
    }
    
    public function loadasociadoscomprasAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $proveedores_id = $this->getRequest()->getParam("proveedores_id");
            $ModelProveedorProductos = new Model_Proveedorproductos();
            $asociados = $ModelProveedorProductos->getAsociados($proveedores_id);
            $this->view->asociados = $asociados;
        }
    }

    public function efectuaretiroAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            // $turnos_id = $this->getRequest()->getParam("turnos_id");
            // $monto_retiro = $this->getRequest()->getParam("monto_retiro");
            // $saldo_nuevo = $this->getRequest()->getParam("saldo_nuevo");
            $ModelRetiro = new Model_Retiro();
            $retiros_id = $ModelRetiro->almacena($this->getRequest()->getParams());
            $this->view->retiros_id = $retiros_id;            
        }
    }

    public function cancelaretiroAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $retiros_id = $this->getRequest()->getParam("retiros_id");
            $ModelRetiro = new Model_Retiro();
            $resultado = $ModelRetiro->cancelaRetiro($retiros_id);
            $this->view->resultado = $resultado;
        }
    }

    public function getdetallesventaAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $ventas_id = $this->getRequest()->getParam("ventas_id");
            $ModelVentaItems = new Model_Ventaitems();
            $detalles = $ModelVentaItems->getItemsByVenta($ventas_id);
            $this->view->detalles = $detalles;
        }
    }
    
    public function getdetallesventafacAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $ventas_id = $this->getRequest()->getParam("ventas_id");
            $ModelVentaItems = new Model_Ventaitems();
            $detalles = $ModelVentaItems->getItemsByVenta($ventas_id);
            $this->view->detalles = $detalles;
        }
    }

    public function almacenacompraAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $varsSessions = new Zend_Session_Namespace("users");
            $params = $this->getRequest()->getParams();
            $params["usuarios_id"] = $varsSessions->usuarios_id;
            $ModelCompra = new Model_Compra();
            $compras_id = $ModelCompra->almacena($params);
            if($compras_id!=null){
                //Procedemos a almacenar los detalles de la compra
                $items = $params["items_id"];
                $cantidades = $params["cantidades"];
                $arrayItems = explode("|",$items);
                $arrayCantidades = explode("|",$cantidades);
                array_pop($arrayItems);
                $contador = -1;
                $ModelCompraDetalles = new Model_Compradetalles();  
                $ExistenciasActualizadas = "";              
                foreach($arrayItems as $item){
                    $contador++;
                    if($arrayCantidades[$contador]>0){
                        $dataD = array(
                            "compras_id"=>$compras_id,
                            "items_id"=>$item,
                            "cantidad"=>$arrayCantidades[$contador]
                        );
                        $compra_detalles_id = $ModelCompraDetalles->almacena($dataD);
                        if($compra_detalles_id!=null)
                            $ExistenciasActualizadas .= $item.", ";
                    }
                }

            }

            $this->view->actualizados = $ExistenciasActualizadas;
            $this->view->compras_id = $compras_id;
        }
    }

    public function getdetallescompraAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $compras_id = $this->getRequest()->getParam("compras_id");
            $ModelCompraDetalles = new Model_Compradetalles();
            $detalles = $ModelCompraDetalles->getDetalles($compras_id);
            $this->view->detalles = $detalles;
        }
    }

    public function eliminacompraAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $compras_id = $this->getRequest()->getParam("compras_id");
            $ModelItems = new Model_Item();
            $ModelCompra = new Model_Compra();
            $ModelCompraDetalles = new Model_Compradetalles();
            $items = $ModelCompraDetalles->getDetalles($compras_id);
            $logActualización = "";
            if($items!=null){
                foreach($items as $item){                    
                    $cantidad = ($item->cantidad * -1);
                    $existencias = $ModelItems->getExistencias($item->items_id);
                    if($ModelItems->actualizaExistencias($item->items_id,$cantidad))                   {
                        $existenciasActuales = $ModelItems->getExistencias($item->items_id);
                        $logActualizacion ="Compra: ".$compras_id.", Item ".$item->items_id." Con Existencias: $existencias Se actualizo con ".$cantidad." quedando inventario: $existenciasActuales";                        
                        fwrite($this->fileCancelaCompra,$logActualizacion."\n");                        
                    }     
                }
                fclose($this->fileCancelaCompra);
            }            
            $this->view->resultado = $ModelCompra->actualizaStatus($compras_id,0);

        }
    }

    public function loadgraficaventasAction(){
        $ModelVentas = new Model_Venta();
        $ventas = $ModelVentas->getResumenVentas();
        $this->view->ventas = $ventas;
    }

    public function getclientesAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $rfc = $this->getRequest()->getParam("rfc");
            $ModelCliente = new Model_Cliente();
            $clientes = $ModelCliente->getClientesByRFC($rfc);
            $this->view->clientes = $clientes;
        }
    }

    public function loadproductosvAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $descripcion = $this->getRequest()->getParam("descripcion");
            $ModelProducto = new Model_Item();
            $productos = $ModelProducto->getProductosByBusqueda($descripcion,5);
            $this->view->productos = $productos;
        }
    }

    public function registramovdevAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $datos = $this->getRequest()->getParams();
            $Modelmovimientosdev = new Model_Movimientosdev();
            $movimientos_id = $Modelmovimientosdev->almacena($datos);
            if($movimientos_id!=null && $datos["tipo"]=="C"){                
                $modelCupon = new Model_Cuponesdev();            
                $codigocup = $datos["ventas_id"].date("md");
                $vigencia = date("Y-m-d",strtotime(date("Y-m-d")."+ 30 days"));
                $data=array(
                    "movimientos_id"=>$movimientos_id,
                    "monto"=>$datos["total"],
                    "codigo"=>$codigocup,
                    "estado"=>"A",
                    "vigencia"=>$vigencia
                );
                $cupones_id = $modelCupon->almacena($data);
            }
            $this->view->movimientos_id = $movimientos_id;
        }
    }

    public function getmovimientosdevAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $ventas_id = $this->getRequest()->getParam("ventas_id");
            $Modelmovimientosdev = new Model_Movimientosdev();
            $movimientos = $Modelmovimientosdev->getMovimientos($ventas_id);
            $this->view->movimientos = $movimientos;
        }
        
    }

    public function getdetailcuponAction(){
        if($this->getRequest()->isXmlHttpRequest()){
            $cupon = $this->getRequest()->getParam("cupon");        
            $modelCupon = new Model_Cuponesdev();
            $info = $modelCupon->getSaldoInicial($cupon);          
            $this->view->info = $info;
        }
    }
}
