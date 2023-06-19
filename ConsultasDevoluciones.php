<?php
    require_once "Venta.php";
    require_once "Devoluciones.php";
    require_once "Cupones.php";
    
    if($_SERVER["REQUEST_METHOD"]=="GET"){
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

        $devoluionesConCupon=array();
        foreach($arrayCupones as $cupon){
            $devolucion=Devolucion::ObtenerDevolucionPorId($arrayDevoluciones,$cupon->idDevolucion);
            if($devolucion!=null){
                array_push($devoluionesConCupon,$devolucion);
            }
        }

        $cuponesYEstados=[];
        foreach($arrayCupones as $cupon){
            $estado=($cupon->activo)?"Si":"No";
            array_push($cuponesYEstados,["Id Cupon"=>$cupon->id,"Activo"=>$estado]);
        }
        $CuponesYDevolucionEstado=[];
        foreach($arrayCupones as $cupon){
            $devolucion=Devolucion::ObtenerDevolucionPorId($arrayDevoluciones,$cupon->idDevolucion);
            if($devolucion!=null){
                $estado=($cupon->activo)?"Si":"No";
                array_push($CuponesYDevolucionEstado,["IdCupon"=>$cupon->id,"IdDevolucion"=>$cupon->idDevolucion,"Activo"=>$estado]);
            }
        }
        $respuesta=[
            "Devoluciones con Cupones"=>$devoluionesConCupon,
            "Cupones y estados"=>$cuponesYEstados,
            "Lista de decoluciones y sus cupones y estado"=>$CuponesYDevolucionEstado
        ];
        print_r($respuesta);
    }
?>