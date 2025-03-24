<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Función para obtener los detalles de una habitación
function obtenerDetallesProducto($product_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                product_id,  
                description, 
                category_type_id, 
                comments, 
                unit_price,
                quantity_on_hand,
                quantity_lend,
                total_qty,
                image_path, 
                status_id, 
                created_by,
                created_on,
                modified_on,
                modified_by 
            FROM FIDE_SAMDESIGN.fide_product_tb 
            WHERE product_id = :product_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":product_id", $product_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);
    if ($row === false) {
        return ['error' => 'No se encontró el producto con el ID proporcionado.'];
    }

    return $row;
}

// Insertar un producto
function IngresarProducto($description, $category_type_id, $comments, $unit_price, $total_qty, $image_path, $created_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_product_tb (description, category_type_id, comments, unit_price, total_qty, image_path, status_id, created_by)
                VALUES (:description, :category_type_id, :comments, :unit_price, :total_qty, :image_path, 1, :created_by)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":description", $description);
        oci_bind_by_name($stmt, ":category_type_id", $category_type_id);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":unit_price", $unit_price);
        oci_bind_by_name($stmt, ":total_qty", $total_qty);
        oci_bind_by_name($stmt, ":image_path", $image_path);
        oci_bind_by_name($stmt, ":created_by", $created_by);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }

    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}
// Eliminar un producto
function eliminarProducto($product_id) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "DELETE FROM FIDE_SAMDESIGN.fide_product_tb WHERE product_id = :product_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":product_id", $product_id);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }
    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}

// Actualizar un producto
function actualizarProducto($product_id, $description, $category_type_id, $comments, $unit_price, $total_qty, $image_path, $status_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_product_tb 
                SET description = :description, 
                    category_type_id = :category_type_id, 
                    comments = :comments,
                    unit_price = :unit_price, 
                    total_qty = :total_qty,
                    image_path = :image_path, 
                    modified_on = SYSDATE,
                    status_id = :status_id,
                    modified_by = :modified_by 
                WHERE product_id = :product_id";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":description", $description);
        oci_bind_by_name($stmt, ":category_type_id", $category_type_id);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":unit_price", $unit_price);
        oci_bind_by_name($stmt, ":total_qty", $total_qty);
        oci_bind_by_name($stmt, ":image_path", $image_path);
        oci_bind_by_name($stmt, ":status_id", $status_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);
        oci_bind_by_name($stmt, ":product_id", $product_id);

        // Ejecutar la consulta
        if (oci_execute($stmt)) {
            $retorno = true;
        }

    } catch (\Throwable $th) {
        echo $th;
    } finally {
        oci_free_statement($stmt);
        oci_close($oConexion);
    }

    return $retorno;
}

// Manejar acciones desde solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "eliminar" && isset($_POST["product_id"])) {
        $room_id = $_POST["product_id"];
        $eliminado = eliminarProducto($product_id);
        echo $eliminado ? "El producto ha sido eliminado exitosamente" : "Error al intentar eliminar la habitación";
    } elseif ($action == "obtenerDetalles" && isset($_POST["product_id"])) {
        $room_id = $_POST["product_id"];
        error_log("Obteniendo detalles para ID: " . $product_id); // Depuración

        $detalles = obtenerDetallesProducto($product_id);

        if (isset($detalles['error'])) {
            http_response_code(404); // Devuelve error si no se encuentra el servicio
        }
        
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["PRODUCT_ID"])) {
        $room_id = $_POST["PRODUCT_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos
        $required_fields = ["PRODUCT_ID", "DESCRIPTION", "TOTAL_QTY", "UNIT_PRICE", "STATUS_ID", "MODIFIED_BY"];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarProducto(
            $product_id,
            $_POST["DESCRIPTION"],
            $_POST["CATEGORY_TYPE_ID"],
            $_POST["COMMENTS"],
            $_POST["UNIT_PRICE"],
            $_POST["TOTAL_QTY"],
            $_POST["IMAGE_PATH"],
            $_POST["STATUS_ID"],
            $_POST["MODIFIED_BY"]
        );
    
        // Envía la respuesta adecuada
        if ($actualizado) {
            echo "success";
        } else {
            http_response_code(500); // Código de error 500: Error Interno
            echo "Error updating service. Check logs for details.";
        }
        exit;
    } elseif ($action == "insertar") {

        // Manejo correcto de la subida de imagen
        $image_path = "../img/";
        if (isset($_FILES['Image_path']) && $_FILES['Image_path']['error'] === UPLOAD_ERR_OK) {
            $folderIMG = "../img/";
            $image_path = $folderIMG . basename($_FILES["Image_path"]["name"]);
            if (!move_uploaded_file($_FILES["Image_path"]["tmp_name"], $image_path)) {
                echo "Error al cargar la imagen.";
                exit;
            }
        } else {
            echo "Imagen no proporcionada o error al subir.";
            exit;
        }

        // Luego usas $image_path en tu función de inserción:
        $insertado = IngresarProducto(
            $_POST["Description"],
            $_POST["category_id"],
            $_POST["Comments"],
            $_POST["Unit_price"],
            $_POST["Total_Qty"],
            $image_path,
            $_POST["created_by"]
        );
        echo $insertado ? "Producto insertado correctamente" : "Error al insertar el producto";
    } else {
        echo "Acción no válida";
    }
}
