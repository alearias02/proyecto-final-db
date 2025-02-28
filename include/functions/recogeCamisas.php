<?php
require_once "../../DAL/camisas.php";


function obtenerObjetos() {
    $query = "select * from camisa where activo=true";
    $myArray = getArray($query);
    return json_encode($myArray, JSON_UNESCAPED_UNICODE);
}
echo obtenerObjetos();

?>