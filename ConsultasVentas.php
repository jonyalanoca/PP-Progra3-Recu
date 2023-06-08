<?php
        require_once "Hamburguesa.php";
        require_once "Venta.php";

        if($_SERVER["REQUEST_METHOD"]=="GET"){
            $auxHamb=new Hamburguesa(0,"",0,"simple","mayonesa",0,"-");
            $auxVenta=new Venta(0,"a@d.c",0,"2000-12-11", 0,"-");

            $nombreArchivoHambur="Hamburgesas.json";
            $nombreArchivoVenta="Ventas.json";
            $arrayHambur=null;
            $arrayVentas=null;

            $decodificado=Hamburguesa::LeerArchivo($nombreArchivoHambur);
            $arrayHambur=Hamburguesa::ArrayToObjectArrays($decodificado);
            $decodificado=Venta::LeerArchivo($nombreArchivoVenta);
            $arrayVentas=Venta::ArrayToObjectArrays($decodificado);

            $resultadoConsulta=array();
            if(isset($_GET["email"])){
                try{
                    $auxVenta->setEmailUsuario($_GET["email"]);
                    
                    $resultadoConsulta=array_filter($arrayVentas,function($venta){
                        return $venta->emailUsuario==$_GET["email"];
                    });
                }catch(Exception $e)
                {echo  $e->getMessage();}
            }elseif(isset($_GET["aderezo"])){
                try{
                    $auxHamb->setAderezo($_GET["aderezo"]);
                    
                    $resultadoConsulta=array_filter($arrayVentas,function($venta)use($arrayHambur){
                        $hamburguesa=Hamburguesa::obtenerHamburguesaPorID($arrayHambur,$venta->idHamburguesa);
                        return $hamburguesa->aderezo==$_GET["aderezo"];
                    });
                }catch(Exception $e)
                {echo  $e->getMessage();}
            }elseif(isset($_GET["tipo"])){
                try{
                    $auxHamb->setTipo($_GET["tipo"]);
                    
                    $resultadoConsulta=array_filter($arrayVentas,function($venta)use($arrayHambur){
                        $hamburguesa=Hamburguesa::obtenerHamburguesaPorID($arrayHambur,$venta->idHamburguesa);
                        return $hamburguesa->tipo==$_GET["tipo"];
                    });
                }catch(Exception $e)
                {echo  $e->getMessage();}
            }elseif(isset($_GET["fecha1"]) && isset($_GET["fecha2"])){
                try{
                    $auxVenta->setFecha($_GET["fecha1"]);
                    $auxVenta->setFecha($_GET["fecha2"]);

                    $resultadoConsulta=array_filter($arrayVentas,function($venta){
                        $fechaInicio=(new DateTime($_GET["fecha1"]))->format("Y-m-d");
                        $fechaFin=(new DateTime($_GET["fecha2"]))->format("Y-m-d");
                        $fecha=(new DateTime($venta->fecha))->format("Y-m-d");
                        return $fecha>=$fechaInicio && $fecha<=$fechaFin;
                    });

                }catch(Exception $e)
                {echo  $e->getMessage();}
            }elseif(isset($_GET["fecha1"])){
                try{
                    $auxVenta->setFecha($_GET["fecha1"]);
                    
                    $resultadoConsulta=array_filter($arrayVentas,function($venta){
                        return $venta->fecha==$_GET["fecha1"];
                    });
                }catch(Exception $e)
                {echo  $e->getMessage();}
            }elseif(count($_GET) == 0){
                
                $resultadoConsulta=array_filter($arrayVentas,function($venta){
                    $hoy=date("Y-m-d");
                    $ayer=(new dateTime($hoy))->modify("-1 day");
                    return $venta->fecha==$ayer->format("Y-m-d");
                });
            }
            print_r($resultadoConsulta);
        }
?>