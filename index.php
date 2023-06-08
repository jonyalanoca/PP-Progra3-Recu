<?php

    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            include_once "ConsultasVentas.php";
            break;
        case "POST":
            if(isset($_POST["nombre"]) && isset($_POST["precio"]) && isset($_POST["tipo"]) && isset($_POST["aderezo"]) && isset($_POST["cantidad"]) && isset($_FILES["imagen"])){
                include_once "HamburguesaCarga.php";
            }elseif(isset($_POST["email"]) && isset($_POST["nombre"]) && isset($_POST["tipo"]) && isset($_POST["aderezo"]) && isset($_POST["cantidad"])){
                include_once "AltaVenta.php";
            }elseif(isset($_POST["nombre"]) && isset($_POST["tipo"])){
                include_once "HamburguesaConsulta.php";
            }
            break;
        case "PUT":
            include_once "ModificarVenta.php";
            break;
    }
?>  
