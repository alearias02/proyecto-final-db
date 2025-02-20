<?php
require_once "../include/templates/header.php";


function GuardarArchivoTallas($nombre, $apellido1, $apellido2, $correo, $telefono, $cantidades, $tallas, $descripcion) {
    try {
        $archivo = fopen('../archivos/Solicitud_Mercaderia.txt', 'a');  //w escribe borrando desde el inicio el puntero. a hace un append al archivo

        $formatoTallas = '[';
        foreach ($tallas as $value) {
            $formatoTallas .= "$value;";
        }
        $formatoTallas .= ']';

        $datos = "$nombre, $apellido1, $apellido2, $correo, $telefono, $cantidades, $formatoTallas, $descripcion\n";
        fwrite($archivo, $datos);
    } catch (\Throwable $th) {
        echo $th;
        //Almacenamiento en bitacora //tarea bitacora de Apache
        //inicializar variables
    }finally{
        //Cerrar conexiones y archivos
        fclose($archivo);
    }
}

function leerArchivo($archivo) {
    try {
        $archivo = fopen($archivo, 'r');

        echo "<h2>Lectura de archivo</h2>";

        while (($linea = fgets($archivo)) != null) {
            $arregloValores = explode(',', $linea);

            echo "<ul>";
            foreach ($arregloValores as $value) {
                echo "<li>$value</li>";
            }
            echo "</ul>";

            echo "<h3>$linea</h3>";
        }

    } catch (\Throwable $th) {
        echo $th;
        //Almacenamiento en bitacora //tarea bitacora de Apache
        //inicializar variables
    }finally{
        //Cerrar conexiones y archivos
        fclose($archivo);
    }
}

require_once "../include/functions/recoge.php";

$nombreP = recogePost('nombre');
$apellido1P = recogePost('apellido1');
$apellido2P = recogePost('apellido2');
$correoP = recogePost('correo');
$telefonoP = '+506' . recogePost('telefono');
$cantidadesP = recogePost('cantidades');
$tallasP = recogePost('tallas');
$descripcionP = recogePost('descripcion');

GuardarArchivoTallas($nombreP, $apellido1P, $apellido2P, $correoP, $telefonoP, $cantidadesP, $tallasP, $descripcionP);
leerArchivo("../archivos/Solicitud_Mercaderia.txt");

require_once "../include/templates/footer.php";
?>