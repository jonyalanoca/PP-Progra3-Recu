<?php
    
    require_once "Hamburguesa.php";
    require_once "Venta.php";
    require_once "Cupones.php";

    if($_SERVER["REQUEST_METHOD"]=="PUT"){
        $data = json_decode(file_get_contents('php://input'), true);
        $numeroPedido = $data['numeroPedido'];
        $email= $data['email'];
        $nombre = $data['nombre'];
        $tipo = $data['tipo'];
        $aderezo = $data['aderezo'];
        $cantidad = $data['cantidad'];


        $nombreArchivoHambur="Hamburgesas.json";
        $nombreArchivoVenta="Ventas.json";
        $arrayHambur=null;
        $arrayVentas=null;

        $nombreArchivoCupon="Cupones.json";
        $arrayCupones=null;

        $decodificado=Hamburguesa::LeerArchivo($nombreArchivoHambur);
        $arrayHambur=Hamburguesa::ArrayToObjectArrays($decodificado);
        $decodificado=Venta::LeerArchivo($nombreArchivoVenta);
        $arrayVentas=Venta::ArrayToObjectArrays($decodificado);
        $decodificado3=Cupon::LeerArchivo($nombreArchivoCupon);
        $arrayCupones=Cupon::ArrayToObjectArrays($decodificado3);

        $parametros=["nombre"=>$nombre, "tipo"=>$tipo,"aderezo"=>$aderezo];
        $objetoEncontrado=Hamburguesa::EncontrarObjeto($arrayHambur,$parametros);
        $ventaEcontrada=Venta::EncontrarVentaPorId($arrayVentas,$numeroPedido);
        if($objetoEncontrado!=null && $ventaEcontrada!=null){
            if($objetoEncontrado->cantidad-$cantidad>=0){
                $hamburguesaAnterior=Hamburguesa::obtenerHamburguesaPorID($arrayHambur,$ventaEcontrada->idHamburguesa);
                $hamburguesaAnterior->cantidad+=$ventaEcontrada->cantidad;

                $objetoEncontrado->cantidad-=$cantidad;

                $ventaEcontrada->idHamburguesa=$objetoEncontrado->id;
                $ventaEcontrada->emailUsuario=$email;
                $ventaEcontrada->cantidad=$cantidad;

                if($ventaEcontrada->idCupon!=-1){
                    $cupon=Cupon::ObtenerCuporPorId($arrayCupones,$ventaEcontrada->idCupon);
                    $ventaEcontrada->total=Venta::ObtenerTotal($cupon,$objetoEncontrado,$cantidad);
                }else{
                    $ventaEcontrada->total=Venta::ObtenerTotal(null,$objetoEncontrado,$cantidad);
                }
                
                
                $nuevaImagen=Venta::GuardarImagenEnVentas($objetoEncontrado->imagen,$nombre,$tipo,$email);
                $ventaEcontrada->imagen=$nuevaImagen;

                Venta::GuardarArchivo($nombreArchivoVenta,$arrayVentas);
                Hamburguesa::GuardarArchivo($nombreArchivoHambur,$arrayHambur);
                echo "Se aplicaron los cambios existosamente";

            }else{
                echo "La hamburguesa nueva no tiene el stock suficiente.";
            }
        }else{
            echo "No se encontro la haburguesa con el nombre, tipo y aderezo o el numero de pedido no existe";
        }

    }

?>