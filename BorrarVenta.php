<?php
    require_once "Venta.php";

    if($_SERVER["REQUEST_METHOD"]=="DELETE"){
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['numeroPedido'])){
            $numeroPedido = $data['numeroPedido'];

            $nombreArchivoVenta="Ventas.json";
            $arrayVentas=null;
    
            $decodificado=Venta::LeerArchivo($nombreArchivoVenta);
            $arrayVentas=Venta::ArrayToObjectArrays($decodificado);
    
            $idEcontrado=Venta::EncontrarIndexVentaPorId($arrayVentas,$numeroPedido);
            if($idEcontrado>-1){
                Venta::MoverArchivoDeRuta($arrayVentas[$idEcontrado]->imagen,"BackUpVentas/2023");
                array_splice($arrayVentas,$idEcontrado,1);
                Venta::GuardarArchivo($nombreArchivoVenta,$arrayVentas);
                echo "Se borro la venta y se guardo la imagen en la carpeta backup";
            }else{
                echo "El Numero de pedido no existe";
            }
        }else{
            echo "Se requiere el parametro numeroPedido";
        }
    }

?>