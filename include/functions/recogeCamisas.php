<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../DAL/database.php";

function obtenerObjetos() {
    $query = "SELECT Product_ID, Description, 
                     Comments AS detalle, Unit_price AS precio, 
                     Image_path, Status_ID
              FROM FIDE_SAMDESIGN.FIDE_PRODUCT_TB
              WHERE Status_ID = 1 AND Category_Type_ID = 3";

    try {
        $myArray = getArray($query);
        if (empty($myArray)) {
            return json_encode(["message" => "No hay productos disponibles"]);
        }
        return json_encode($myArray, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        return json_encode(["error" => $e->getMessage()]);
    }
}

echo obtenerObjetos();
