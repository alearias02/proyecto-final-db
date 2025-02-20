<?php

function conectar() {

    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "sam_design";


    $conexion = mysqli_connect($server, $user, $password, $database);

    if(!$conexion){
        echo("Ocurrio un error conectando a la base de datos: ". mysqli_connect_error());
    }
    return $conexion;
}

function desconectar($conexion){
    mysqli_close($conexion);
}