<?php
    include_once "Hamburguesa.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(
            isset($_POST["nombre"]) &&
            isset($_POST["precio"]) &&
            isset($_POST["tipo"]) &&
            isset($_POST["aderezo"]) && 
            isset($_POST["cantidad"]) &&
            isset($_FILES["imagen"])
        ){
            $nombre=$_POST["nombre"];
            $precio=$_POST["precio"];
            $tipo=$_POST["tipo"];
            $aderezo=$_POST["aderezo"];
            $cantidad=$_POST["cantidad"];
            $imagen=$_FILES["imagen"];
            try{
                $auxValidadora=new Hamburguesa(0,$nombre,$precio,$tipo,$aderezo,$cantidad,"-");
                
                $nombreArchivoHambur="Hamburgesas.json";
                $arrayHamburguesas=null;

                $decodificado=Hamburguesa::LeerArchivo($nombreArchivoHambur);
                $arrayHambur=Hamburguesa::ArrayToObjectArrays($decodificado);

                $imagenUrl=Hamburguesa::GuardarImagen($imagen,$nombre,$tipo);
                Hamburguesa::AddHamburguesa($arrayHambur,$nombre,$precio,$tipo,$aderezo,$cantidad,$imagenUrl);
                Hamburguesa::GuardarArchivo($nombreArchivoHambur,$arrayHambur);
            }
            catch(Exception $e)
            {echo $e->getMessage();}
        }
        else
        {echo "Los parametros recividos son incorrectos";}
    }
?>