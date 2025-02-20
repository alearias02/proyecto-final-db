<?php
require_once "../../DAL/impermeables.php";

function obtenerObjetos() {
    $query = "select * from impermeable where activo=true";
    $myArray = getArray($query);
    return json_encode($myArray, JSON_UNESCAPED_UNICODE);
}
echo obtenerObjetos();
?>