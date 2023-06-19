<?php
    class Venta{
        public $numeroPedido;
        public $emailUsuario;
        public $idHamburguesa;
        public $fecha;
        public $cantidad;
        public $imagen;
        public $idCupon;
        public $total;
        public function __construct($numeroPedido,$emailUsuario,$idHamburguesa,$fecha,$cantidad,$imagen,$idCupon=null,$total){
            $this->setNumeroPedido($numeroPedido);
            $this->setEmailUsuario($emailUsuario);
            $this->setIdHamburguesa($idHamburguesa);
            $this->setFecha($fecha);
            $this->setCantidad($cantidad);
            $this->setIdCupon($idCupon);
            $this->setTotal($total);
            $this->imagen=$imagen;
        }
        public function setNumeroPedido($numeroPedido){
            if(is_numeric($numeroPedido)){
                $this->numeroPedido=intval($numeroPedido);
            }else{
                throw new Exception("El numero de pedido no es un numero");
            }
        }
        public function setEmailUsuario($emailUsuario){
            if (filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
                $this->emailUsuario=$emailUsuario;
            } else {
                throw new Exception("El email no tiene el formato correcto.");
            }
        }
        public function setIdHamburguesa($idHamburguesa){
            if(is_numeric($idHamburguesa)){
                $this->idHamburguesa=intval($idHamburguesa);
            }else{
                throw new Exception("El id  de la haburguesa no es un numero");
            }
        }
        public function setFecha($fecha){
            if (date("Y-m-d", strtotime($fecha)) == $fecha) {
                $this->fecha=$fecha;
            } else {
                throw new Exception("La fecha no tiene el formato correcto. Ejemplo: 2000-12-21");
            }
        }
        public function setCantidad($cantidad){
            if(is_numeric($cantidad)){
                $this->cantidad=intval($cantidad);
            }else{
                throw new Exception("La cantidad no es un numero");
            }
        }
        public function setIdCupon($idCupon){
            if(is_numeric($idCupon)){
                $this->idCupon=intval($idCupon);
            }else{
                throw new Exception("El id del cupon no es un numero");
            }
        }
        public function setTotal($total){
            if(is_numeric($total)){
                $this->total=floatval($total);
            }else{
                throw new Exception("El total no es un numero");
            }
        }

        //Metodos Alta
        public static function LeerArchivo($nombreArchivo){
            if(file_exists($nombreArchivo)){
                $archivo=fopen($nombreArchivo, "r");
                clearstatcache();
                $cadena=fread($archivo,filesize($nombreArchivo));
                fclose($archivo);
                $arrayDecodificado=json_decode($cadena,TRUE);
            }else{
                $arrayDecodificado=array();
            }
            return $arrayDecodificado;
        }
        public static function ArrayToObjectArrays($arrayDecodificado){
            $listaObjetos=array();
            foreach($arrayDecodificado as $elemento){
                array_push($listaObjetos,new Venta($elemento["numeroPedido"],$elemento["emailUsuario"],$elemento["idHamburguesa"],$elemento["fecha"],$elemento["cantidad"],$elemento["imagen"],$elemento["idCupon"], $elemento["total"]));
            }
            return $listaObjetos;
        }

         public static function AltaVenta(&$arrayVentas,$email,$idHamburguesa,$cantidad, $imagen, $idCupon, $totalVenta){
            $venta=new Venta(rand(2001,3000),$email,$idHamburguesa,date("Y-m-d"),$cantidad,$imagen, $idCupon, $totalVenta);
            array_push($arrayVentas,$venta);
            echo "Se guardo la venta con exito";
         }
         public static function GuardarImagenEnVentas($imagen,$nombre,$tipo,$email){
            $nombreCarpeta="ImagenesDeLaVenta/2023";
            if(!file_exists($nombreCarpeta)){
                mkdir($nombreCarpeta, 0777, true);
            }
            $emailName=explode("@",$email);
            $extensionArchivo=pathinfo($imagen, PATHINFO_EXTENSION);
            $fecha=date("Y-m-d");
            $imagenDestino=$nombreCarpeta."/".$tipo.$nombre.$emailName[0].$fecha.".".$extensionArchivo;
    
            copy($imagen,$imagenDestino);
            return $imagenDestino;
         }
         
         public static function GuardarArchivo($nombreArchivo,$arrayObjetos){
            $archivo=fopen($nombreArchivo,"w");
            $cadena=json_encode($arrayObjetos,JSON_PRETTY_PRINT);
            fwrite($archivo,$cadena);
            fclose($archivo);
        }
        //modificar
        public static function EncontrarVentaPorId($arrayVentas, $id){
            $encontrada=null;
            foreach($arrayVentas as $venta){
                if($venta->numeroPedido==$id){
                    $encontrada=$venta;
                    break;
                }
            }
            return $encontrada;
        }
        //borrar
        public static function EncontrarIndexVentaPorId($arrayVentas, $id){
            $encontrada=-1;
            for($i=0;$i<count($arrayVentas);$i++){
                if($arrayVentas[$i]->numeroPedido==$id){
                    $encontrada=$i;
                    break;
                }
            }
            return $encontrada;
        }
        public static function MoverArchivoDeRuta($imagen,$rutaNueva){
            
            if(!file_exists($rutaNueva)){
                mkdir($rutaNueva, 0777, true);
            }
            $nombreArchivo=basename($imagen);
            $imagenDestino=$rutaNueva."/".$nombreArchivo;
    
            copy($imagen,$imagenDestino);
            return $imagenDestino;
        }
        public static function ObtenerTotal($cupon,$objetoEncontrado,$cantidad){
            $total=$cantidad*$objetoEncontrado->precio;
            if($cupon!=null){
                $total=$total-($total*$cupon->descuento);
            }
            return $total;
        }
    }
?>