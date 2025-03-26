<?php

require_once "conexion.php"; // Archivo que maneja la conexión a Oracle


// Obtener detalles de un producto
function obtenerDetallesProducto($product_id) {
    $oConexion = conectar();
    $sql = "SELECT 
                PRODUCT_ID, DESCRIPTION, CATEGORY_TYPE_ID, COMMENTS, UNIT_PRICE, IMAGE_PATH, STATUS_ID
            FROM FIDE_SAMDESIGN.FIDE_PRODUCT_TB 
            WHERE PRODUCT_ID = :product_id";
    $stmt = oci_parse($oConexion, $sql);
    oci_bind_by_name($stmt, ":product_id", $product_id);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($oConexion);

    if ($row === false) {
        return ['error' => 'No se encontró el producto con el ID proporcionado.'];
    }

    return [
        "Product_ID" => $row["PRODUCT_ID"],
        "Description" => $row["DESCRIPTION"],
        "Comments" => $row["COMMENTS"],
        "Unit_price" => $row["UNIT_PRICE"],
        "Category_Type_ID" => $row["CATEGORY_TYPE_ID"],
        "Status_ID" => $row["STATUS_ID"],
        "Image_path" => $row["IMAGE_PATH"],
    ];
}

// Insertar un producto
function IngresarProducto($description, $category_type_id, $comments, $unit_price, $image_path, $created_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "INSERT INTO FIDE_SAMDESIGN.fide_product_tb (description, category_type_id, comments, unit_price, image_path, status_id, created_by, created_on)
                VALUES (:description, :category_type_id, :comments, :unit_price, :image_path, 1, :created_by, SYSDATE)";

        $stmt = oci_parse($oConexion, $sql);

        // Vincular parámetros
        oci_bind_by_name($stmt, ":description", $description);
        oci_bind_by_name($stmt, ":category_type_id", $category_type_id);
        oci_bind_by_name($stmt, ":comments", $comments);
        oci_bind_by_name($stmt, ":unit_price", $unit_price);
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
function eliminarProducto($product_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_product_tb
                SET status_id = 0,
                    modified_on = SYSDATE,
                    modified_by = :modified_by 
                WHERE product_id = :product_id";
        $stmt = oci_parse($oConexion, $sql);

        // Vincular el parámetro
        oci_bind_by_name($stmt, ":product_id", $product_id);
        oci_bind_by_name($stmt, ":modified_by", $modified_by);

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
function actualizarProducto($product_id, $description, $category_type_id, $comments, $unit_price, $image_path, $status_id, $modified_by) {
    $retorno = false;

    try {
        $oConexion = conectar();
        $sql = "UPDATE FIDE_SAMDESIGN.fide_product_tb 
                SET description = :description, 
                    category_type_id = :category_type_id, 
                    comments = :comments,
                    unit_price = :unit_price, 
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

// Acciones POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "eliminar" && isset($_POST["product_id"])) {
        $product_id = $_POST["product_id"];
        $modified_by = $_POST["modified_by"];
        $eliminado = eliminarProducto($product_id, $modified_by);
        if ($eliminado) {
            echo "success";
        } else {
            echo "Error al eliminar el producto";
        }
    } elseif ($action == "obtenerDetalles" && isset($_POST["product_id"])) {
        $product_id = $_POST["product_id"];
        $detalles = obtenerDetallesProducto($product_id);
        if (isset($detalles['error'])) {
            http_response_code(404);
        }
        echo json_encode($detalles);
    } elseif ($action == "actualizar" && isset($_POST["Product_ID"])) {
        $product_id = $_POST["PRODUCT_ID"];
    
        // Registrar todos los datos recibidos para depuración
        error_log("Datos recibidos para actualizar: " . json_encode($_POST));
    
        // Verifica parámetros requeridos 
        $required_fields = [
            "Product_ID", "Description", "category_type_id", 
            "Comments", "Unit_price", "STATUS_ID", "modified_by"
        ];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400); // Código de error 400: Solicitud Incorrecta
                echo "El campo $field es requerido.";
                exit;
            }
        }
       
        $image_path = null; // valor por defecto si no se carga imagen
        if (isset($_FILES['Image_path']) && $_FILES['Image_path']['error'] === UPLOAD_ERR_OK) {
            $folderIMG = "../img/";
            $image_path = $folderIMG . basename($_FILES["Image_path"]["name"]);
            if (!move_uploaded_file($_FILES["Image_path"]["tmp_name"], $image_path)) {
                echo "Error al cargar la imagen.";
                exit;
            }
        } else {
            // Si no hay imagen nueva, mantener la actual (Opcional pero recomendado)
            $detallesActuales = obtenerDetallesProducto($_POST["Product_ID"]);
            $image_path = $detallesActuales["Image_path"];
        }
    
        // Ejecuta la actualización
        $actualizado = actualizarProducto(
            $_POST["Product_ID"],
            $_POST["Description"],
            $_POST["category_type_id"], 
            $_POST["Comments"], 
            $_POST["Unit_price"], 
            $image_path, 
            $_POST["STATUS_ID"], 
            $_POST["modified_by"]
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

        $insertado = IngresarProducto(
            $_POST["Description"], $_POST["category_id"], $_POST["Comments"],
            $_POST["Unit_price"], $image_path, $_POST["created_by"]
        );
        if ($insertado) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Error al insertar el producto";
        }        
    } else {
        echo "Acción no válida";
    }
}
