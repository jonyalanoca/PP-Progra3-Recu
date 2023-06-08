<?php
    require_once "Hamburguesa.php";
    require_once "Venta.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(
            isset($_POST["email"]) &&
            isset($_POST["nombre"]) &&
            isset($_POST["tipo"]) &&
            isset($_POST["aderezo"]) &&
            isset($_POST["cantidad"])
        ){
            $email=$_POST["email"];
            $nombre=$_POST["nombre"];
            $tipo=$_POST["tipo"];
            $aderezo=$_POST["aderezo"];
            $cantidad=$_POST["cantidad"];
            try{
                $auxValidadora=new Hamburguesa(0,$nombre,0,$tipo,$aderezo,0,"-");
                $auxValidadora=new Venta(0,$email,0,date("Y-m-d"), $cantidad,"-");

                $nombreArchivoHambur="Hamburgesas.json";
                $nombreArchivoVenta="Ventas.json";
                $arrayHambur=null;
                $arrayVentas=null;

                $decodificado=Hamburguesa::LeerArchivo($nombreArchivoHambur);
                $arrayHambur=Hamburguesa::ArrayToObjectArrays($decodificado);
                $decodificado=Venta::LeerArchivo($nombreArchivoVenta);
                $arrayVentas=Venta::ArrayToObjectArrays($decodificado);

                $diccAtributos=["nombre"=>$nombre, "tipo"=>$tipo, "aderezo"=>$aderezo];
                $objetoEncontrado=Hamburguesa::EncontrarObjeto($arrayHambur,$diccAtributos);

                if($objetoEncontrado==null){
                    echo "No se encontro el la hamburguesa";
                }else{
                    if($objetoEncontrado->cantidad>=$cantidad){
                        $objetoEncontrado->cantidad-=$cantidad;

                        $imagenUrl=Venta::GuardarImagenEnVentas($objetoEncontrado->imagen,$objetoEncontrado->nombre,$objetoEncontrado->tipo, $email);
                        Venta::AltaVenta($arrayVentas,$email,$objetoEncontrado->id,$cantidad,$imagenUrl);
                        Venta::GuardarArchivo($nombreArchivoVenta,$arrayVentas);
                        Hamburguesa::GuardarArchivo($nombreArchivoHambur,$arrayHambur);
                    }
                }
            }catch(Exception $e)
            {echo $e->getMessage();}
            
        }
        else
        {echo "Los parametros recividos son incorrectos";}
    }
?>