<?php
    class Devolucion{
        public $id;
        public $causa;
        public $numeroPedido;
        public $foto;    
        public function __construct($id,$causa,$numeroPedido,$foto){
            $this->setId($id);
            $this->causa=$causa;
            $this->setNumeroPedido($numeroPedido);
            $this->foto=$foto;

        }
        public function setId($id){
            if(is_numeric($id)){
                $this->id=intval($id);
            }else{
                throw new Exception("El id no es un numero");
            }
        }
        public function setNumeroPedido($numeroPedido){
            if(is_numeric($numeroPedido)){
                $this->numeroPedido=intval($numeroPedido);
            }else{
                throw new Exception("El numero de pedido no es un numero");
            }
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
                array_push($listaObjetos,new Devolucion($elemento["id"],$elemento["causa"],$elemento["numeroPedido"],$elemento["foto"]));
            }
            return $listaObjetos;
        }
        public static function AddDevolucion(&$arrayDevoluciones,$causa,$numeroPedido,$foto){
            $nuevoElemento=new Devolucion(rand(3001,4000),$causa,$numeroPedido,$foto);
            array_push($arrayDevoluciones,$nuevoElemento);
            echo "Se agrego una nueva devolución\n";
            return $nuevoElemento->id;
        }
        public static function GuardarImagen($imagen){
            $nombreCarpeta="ImagenesDeDevolucion/2023";
            if(!file_exists($nombreCarpeta)){
                mkdir($nombreCarpeta, 0777, true);
            }
            $imagenUrl=$nombreCarpeta."/".$imagen["name"];
            move_uploaded_file($imagen["tmp_name"],$imagenUrl);
            return $imagenUrl;
        }
        public static function GuardarArchivo($nombreArchivo,$arrayObjetos){
            $archivo=fopen($nombreArchivo,"w");
            $cadena=json_encode($arrayObjetos,JSON_PRETTY_PRINT);
            fwrite($archivo,$cadena);
            fclose($archivo);
        }
    }
?>  