<?php

    $servidor = "localhost";
    $nombreusuario = "apablo";
    $password = "55891200";
    $db = "sistema_nomina";

    $conexion = new mysqli($servidor, $nombreusuario, $password, $db);
    

    if($conexion-> connect_error){
        die("Conexión fallida: " . $conexion-> connect_error);
    }


