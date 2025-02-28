<?php
require_once "../../DAL/repuestos.php";

function obtenerObjetos() {
    $query = "select * from repuesto  where activo=true";
    $myArray = getArray($query);
    return json_encode($myArray, JSON_UNESCAPED_UNICODE);
}
echo obtenerObjetos();

?>