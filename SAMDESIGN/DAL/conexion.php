<?php

function conectar() {

    $user = "FIDE_SAM_DESIGN";
    $password = "SAMDESIGN";
    $host = "localhost/XE";
    // para conectar a la base de datos de oracle usamos el protocolo oci de php
    $conexion = oci_connect($user, $password, $host);

    if (!$conexion) {
        $m = oci_error();
        echo "<script>console.error('Database connection error: " . addslashes($m['message']) . "');</script>";
        exit;
     }
     else {
        // print "<script>console.log('Connected to Oracle!');</script>";//usamos sentencias de javascript para poder imprimir el mensaje  en consola
     }
    return $conexion;
}

function desconectar($conexion){
    oci_close($conexion);
}