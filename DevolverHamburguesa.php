<?php
    require_once "Venta.php";
    require_once "Devoluciones.php";
    require_once "Cupones.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(
            isset($_POST["numeroPedido"]) &&
            isset($_POST["causa"]) &&
            isset($_FILES["foto"])
        ){
            $numeroPedido=$_POST["numeroPedido"];
            $causa=$_POST["causa"];
            $foto=$_FILES["foto"];

            $nombreArchivoVenta="Ventas.json";
            $nombreArchivoDevolucion="Devoluciones.json";
            $nombreArchivoCupon="Cupones.json";
            $arrayVentas=null;
            $arrayDevoluciones=null;
            $arrayCupones=null;
            $decodificado=Venta::LeerArchivo($nombreArchivoVenta);
            $arrayVentas=Venta::ArrayToObjectArrays($decodificado);
            $decodificado2=Devolucion::LeerArchivo($nombreArchivoDevolucion);
            $arrayDevoluciones=Devolucion::ArrayToObjectArrays($decodificado2);
            $decodificado3=Cupon::LeerArchivo($nombreArchivoCupon);
            $arrayCupones=Cupon::ArrayToObjectArrays($decodificado3);

            $imagenUrl=Devolucion::GuardarImagen($foto);
            $idDeDevolucion=Devolucion::AddDevolucion($arrayDevoluciones,$causa,$numeroPedido,$imagenUrl);
            Devolucion::GuardarArchivo($nombreArchivoDevolucion,$arrayDevoluciones);
            echo "Se guardo la devolución con exito\n";
            $fechaVencimiento=Cupon::EstablecerFechaDeVenciento(3);
            Cupon::AddCupon($arrayCupones,$idDeDevolucion,$fechaVencimiento,true,0.10);
            Cupon::GuardarArchivo($nombreArchivoCupon,$arrayCupones);
            echo "Se guardo el cupon con exito\n";
        }

    }
?>