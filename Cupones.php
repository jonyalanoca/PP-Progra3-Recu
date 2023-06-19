<?php
    class Cupon{
        public $id;
        public $idDevolucion;
        public $fechaVencimiento;
        public $activo;
        public $descuento;
    
        public function __construct($id,$idDevolucion,$fechaVencimiento,$activo=false,$descuento){
            $this->setId($id);
            $this->setIdDevolucion($idDevolucion);
            $this->setActivo($activo);
            $this->setFecha($fechaVencimiento);
            $this->setDescuento($descuento);
        }
        public function setId($id){
            if(is_numeric($id) ){
                $this->id=intval($id);
            }else{
                throw new Exception("El id no es un numero");
            }
        }
        public function setIdDevolucion($idDevolucion){
            if(is_numeric($idDevolucion)){
                $this->idDevolucion=intval($idDevolucion);
            }else{
                throw new Exception("El id de la devolucion no es un numero");
            }
        }
        public function setFecha($fecha){
            if (date("Y-m-d", strtotime($fecha)) == $fecha) {
                $this->fechaVencimiento=$fecha;
            } else {
                throw new Exception("La fecha no tiene el formato correcto. Ejemplo: 2000-12-21");
            }
        }
        public function setActivo($activo){
            if($activo==true || $activo==false){
                $this->activo=$activo;
            }else{
                throw new Exception("El activo solo acepta un booleano (true/false)");
            }
        }
        public function setDescuento($descuento){
            if(is_numeric($descuento)){
                $this->descuento=floatval($descuento);
            }else{
                throw new Exception("El decuento no tiene le formato correcto. Ejemplo: 0.22 (22%)");
            }
        }
        public static function EstablecerFechaDeVenciento($dias){
            $hoy=Date("Y-m-d");
            $vencimiento=(new dateTime($hoy))->modify("+".$dias." day");
            return $vencimiento->format("Y-m-d");
        }
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
                array_push($listaObjetos,new Cupon($elemento["id"],$elemento["idDevolucion"],$elemento["fechaVencimiento"],$elemento["activo"],$elemento["descuento"]));
            }
            return $listaObjetos;
        }
        public static function AddCupon(&$arrayDevoluciones,$idDevolucion,$fechaVencimiento,$activo,$descuento){
            $nuevoElemento=new Cupon(rand(5001,6000),$idDevolucion,$fechaVencimiento,$activo,$descuento);
            array_push($arrayDevoluciones,$nuevoElemento);
            echo "Se agrego un nuevo  descuento\n";
        }
        public static function GuardarArchivo($nombreArchivo,$arrayObjetos){
            $archivo=fopen($nombreArchivo,"w");
            $cadena=json_encode($arrayObjetos,JSON_PRETTY_PRINT);
            fwrite($archivo,$cadena);
            fclose($archivo);
        }
        public static function ObtenerCuporPorId($arrayCupones, $id){
            $objetoEncontrado=null;
            foreach($arrayCupones as $cupon){
                if($cupon->id==$id){
                    $objetoEncontrado=$cupon;
                    break;
                }
            }
            return $objetoEncontrado;
        }
        public static function ValidarCupon($cupon)
        {
            if($cupon==null){
                echo "No se encontro el cupon. No se aplico el descuento\n";
                return false;
            }
            if($cupon->activo==false){
                echo "El cupon no esta habilitado. No se aplico el descuento\n";
                return false;
            }
            $vencimiento=new DateTime($cupon->fechaVencimiento);
            $hoy=new DateTime(date("Y-m-d"));
            if($vencimiento<$hoy){
                echo "El cupon esta vencido. No se aplico el descuento\n";
                return false;
            }
            return true;
        }
    }
?>  