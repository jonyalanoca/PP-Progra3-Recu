<?php
    class Hamburguesa{
        public $id;
        public $nombre;
        public $precio;
        public $tipo;
        public $aderezo;
        public $cantidad;
        public $imagen;
        public function __construct($id, $nombre, $precio,$tipo,$aderezo, $cantidad , $imagen){
            $this->setId($id);
            $this->nombre=$nombre;
            $this->setPrecio($precio);
            $this->setTipo($tipo);
            $this->setAderezo($aderezo);
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
        public function setPrecio($precio){
            if(is_numeric($precio)){
                $this->precio=floatval($precio);
            }else{
                throw new Exception("El precio no es un numero");
            }
        }
        public function setTipo($tipo){
            if($tipo=="simple" || $tipo=="doble"){
                $this->tipo=$tipo;
            }else{
                throw new Exception("El el tipo no es  doble o simple");
            }
        }
        public function setAderezo($aderezo){
            if($aderezo=="mostaza" || $aderezo=="mayonesa" || $aderezo=="ketchup"){
                $this->aderezo=$aderezo;
            }else{
                throw new Exception("El el aderezo no es mostaza, mayonesa o ketchup ");
            }
        }
        public function setCantidad($cantidad){
            if(is_numeric($cantidad)){
                $this->cantidad=intval($cantidad);
            }else{
                throw new Exception("El cantidad no es un numero");
            }
        }
        ///Metodos Carga
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
                array_push($listaObjetos,new Hamburguesa($elemento["id"],$elemento["nombre"],$elemento["precio"],$elemento["tipo"],$elemento["aderezo"],$elemento["cantidad"],$elemento["imagen"]));
            }
            return $listaObjetos;
        }
        public static function GuardarImagen($imagen,$nombre,$tipo){
            $nombreCarpeta="ImagenesDeHamburguesas/2023";
            if(!file_exists($nombreCarpeta)){
                mkdir($nombreCarpeta, 0777, true);
            }
            $extensionArchivo=pathinfo($imagen["name"], PATHINFO_EXTENSION);
            $imagenUrl=$nombreCarpeta."/".$tipo.$nombre.".".$extensionArchivo;
    
            move_uploaded_file($imagen["tmp_name"],$imagenUrl);
            return $imagenUrl;
        }
        public static function AddHamburguesa(&$arrayObjetos,$nombre,$precio,$tipo,$aderezo,$cantidad,$imagen){
            $diccAtributos=["nombre"=>$nombre, "tipo"=>$tipo];
            $objetoEncontrado=Hamburguesa::EncontrarObjeto($arrayObjetos,$diccAtributos);
            if($objetoEncontrado==null){
                $nuevoElemento=new Hamburguesa(rand(1,1000),$nombre,$precio,$tipo,$aderezo,$cantidad,$imagen);
                array_push($arrayObjetos,$nuevoElemento);
                echo "Se agrego una nueva hamburguesa";
            }else{
                $objetoEncontrado->cantidad+=$cantidad;
            }
        }
        public static function EncontrarObjeto($arrayObjetos, $arrayAtributos){
            $objetoEncontrado=array_reduce($arrayObjetos,function($acumulador, $elemento)use ($arrayAtributos){
                $cantidadElementos=count($arrayAtributos);
                $check=0;
                foreach($arrayAtributos as $clave=>$valor){
                    if($elemento->$clave==$valor){
                        $check++;
                    }
                }
                if($check==$cantidadElementos){
                    return $elemento;
                }else{
                    return $acumulador;
                }
            },null);
            return $objetoEncontrado;
        }
        public static function GuardarArchivo($nombreArchivo,$arrayObjetos){
            $archivo=fopen($nombreArchivo,"w");
            $cadena=json_encode($arrayObjetos,JSON_PRETTY_PRINT);
            fwrite($archivo,$cadena);
            fclose($archivo);
        }
        //Metodos Consulta
        public static function HallarAtributosInexistentes($arrayObjetos,$arrayAtributos){//generico
            $diccNoContiene=array();   
            foreach($arrayAtributos as $clave=>$valor){
                $existe=false;
                foreach($arrayObjetos as $elemento){
                    if($elemento->$clave==$valor){
                        $existe=true;
                        break;
                    }
                }
                if($existe==false){
                    $diccNoContiene[$clave]=$valor;
                }
    
            }
            return  $diccNoContiene; 
        }
        public static function InformarContieneSiNo($diccAtributosQueNoContiene){//generico
            if(count($diccAtributosQueNoContiene)==0){
                echo "Si hay";
            }else{
                foreach($diccAtributosQueNoContiene as $clave=>$valor){
                    echo "No se econtro: ".$clave." - ".$valor."\n";
                }
            }
        }
        //Metodos Venta Hamburguesa
        
    }
?>