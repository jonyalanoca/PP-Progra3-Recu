<?php
    require_once "Hamburguesa.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["nombre"]) && isset($_POST["tipo"])){
            $nombre=$_POST["nombre"];
            $tipo=$_POST["tipo"];;
            try{
                $auxValidadora=new Hamburguesa(0,$nombre,0,$tipo,"mayonesa",0,"-");
                
                $nombreArchivoHambur="Hamburgesas.json";
                $arrayHambur=null;

                $decodificado=Hamburguesa::LeerArchivo($nombreArchivoHambur);
                $arrayHambur=Hamburguesa::ArrayToObjectArrays($decodificado);

                $arrayAsociativoAtributos=array("nombre"=>$nombre, "tipo"=>$tipo);
                $atributosFaltantes=Hamburguesa::HallarAtributosInexistentes($arrayHambur,$arrayAsociativoAtributos);
                Hamburguesa::InformarContieneSiNo($atributosFaltantes);
            }
            catch(Exception $e)
            {echo $e->getMessage();}
        }
        else
        {echo "Los parametros recividos son incorrectos";}
    }
?>
