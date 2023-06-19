<?php
    require_once "Hamburguesa.php";
    require_once "Venta.php";
    require_once "Cupones.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(
            isset($_POST["email"]) &&
            isset($_POST["nombre"]) &&
            isset($_POST["tipo"]) &&
            isset($_POST["aderezo"]) &&
            isset($_POST["cantidad"]) &&
            isset($_POST["idCupon"])
        ){
            $email=$_POST["email"];
            $nombre=$_POST["nombre"];
            $tipo=$_POST["tipo"];
            $aderezo=$_POST["aderezo"];
            $cantidad=$_POST["cantidad"];
            $idCupon=$_POST["idCupon"];
            try{
                $auxValidadora=new Hamburguesa(0,$nombre,0,$tipo,$aderezo,0,"-");
                $auxValidadora=new Venta(0,$email,0,date("Y-m-d"), $cantidad,"-",1,2);

                $nombreArchivoHambur="Hamburgesas.json";
                $nombreArchivoVenta="Ventas.json";
                $nombreArchivoCupon="Cupones.json";
                $arrayHambur=null;
                $arrayVentas=null;
                $arrayCupones=null;

                $decodificado=Hamburguesa::LeerArchivo($nombreArchivoHambur);
                $arrayHambur=Hamburguesa::ArrayToObjectArrays($decodificado);
                $decodificado=Venta::LeerArchivo($nombreArchivoVenta);
                $arrayVentas=Venta::ArrayToObjectArrays($decodificado);
                
                $decodificado3=Cupon::LeerArchivo($nombreArchivoCupon);
                $arrayCupones=Cupon::ArrayToObjectArrays($decodificado3);
                
                $diccAtributos=["nombre"=>$nombre, "tipo"=>$tipo, "aderezo"=>$aderezo];
                $objetoEncontrado=Hamburguesa::EncontrarObjeto($arrayHambur,$diccAtributos);

                if($objetoEncontrado==null){
                    echo "No se encontro el la hamburguesa";
                }else{
                    if($objetoEncontrado->cantidad>=$cantidad){
                        $objetoEncontrado->cantidad-=$cantidad;

                        $cupon=Cupon::ObtenerCuporPorId($arrayCupones,$idCupon);
 
                        if(Cupon::ValidarCupon($cupon)==true){
                            $totalDeLaVenta=Venta::ObtenerTotal($cupon,$objetoEncontrado,$cantidad);
                            echo "Se aplico un descuento del ".($cupon->descuento*100)."%\n";
                            $cupon->activo=false;
                            Cupon::GuardarArchivo($nombreArchivoCupon,$arrayCupones);
                        }else{
                            $idCupon=-1;
                            $totalDeLaVenta=Venta::ObtenerTotal(null,$objetoEncontrado,$cantidad);
                        }
                        

                        $imagenUrl=Venta::GuardarImagenEnVentas($objetoEncontrado->imagen,$objetoEncontrado->nombre,$objetoEncontrado->tipo, $email);
                        Venta::AltaVenta($arrayVentas,$email,$objetoEncontrado->id,$cantidad,$imagenUrl, $idCupon, $totalDeLaVenta );//aca me quede
                        //verificar que funcione correctamente el alta venta con los nuevos atributos
                        Venta::GuardarArchivo($nombreArchivoVenta,$arrayVentas);
                        Hamburguesa::GuardarArchivo($nombreArchivoHambur,$arrayHambur);
                    }
                }
            }catch(Exception $e)
            {echo $e->getMessage();}
            
        }
        else
        {echo "Los parametros recividos son incorrectos ";}
    }
?>