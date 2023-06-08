<?php
    class Venta{
        public $id;
        public $emailUsuario;
        public $numeroPedido;
        public $fecha;
        public $cantidad;
        public $imagen;
        public function __construct($id,$emailUsuario,$numeroPedido,$fecha,$cantidad,$imagen){
            $this->setId($id);
            $this->setEmailUsuario($emailUsuario);
            $this->setNumeroPedido($numeroPedido);
            $this->setFecha($fecha);
            $this->setCantidad($cantidad);
            $this->imagen=$imagen;
        }
        public function setId($id){
            if(is_numeric($id)){
                $this->id=intval($id);
            }else{
                throw new Exception("El id no es un numero");
            }
        }
        public function setEmailUsuario($emailUsuario){
            if (filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
                $this->emailUsuario=$emailUsuario;
            } else {
                throw new Exception("El email no tiene el formato correcto.");
            }
        }
        public function setNumeroPedido($numeroPedido){
            if(is_numeric($numeroPedido)){
                $this->numeroPedido=intval($numeroPedido);
            }else{
                throw new Exception("El numeroPedido no es un numero");
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
                array_push($listaObjetos,new Venta($elemento["id"],$elemento["emailUsuario"],$elemento["numeroPedido"],$elemento["fecha"],$elemento["cantidad"],$elemento["imagen"]));
            }
            return $listaObjetos;
        }

         public static function AltaVenta(&$arrayVentas,$email,$numeroPedido,$cantidad, $imagen){
            $venta=new Venta(rand(2001,3000),$email,$numeroPedido,date("Y-d-m"),$cantidad,$imagen);
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
    }
?>