<?php
$page = "mostrar";
require_once "../include/templates/header.php";
require_once "../include/functions/recoge.php";
require_once "../DAL/repuestos.php";

$errores = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = recogeGet("id_repuesto");
    //sanitización
    $elSQL = "select id_repuesto, id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo from repuesto where id_repuesto = $id";
    $oRepuesto = getObject($elSQL);

    if($oRepuesto == null)
    {
        $errores[] = "Repuesto no encontrado";
    }
    //echo json_encode($oRepuesto, JSON_UNESCAPED_UNICODE);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = recogePost("id_repuesto");

    if ($id == "") {
        $errores[] = "Identificador de repuesto incorrecto";
    }

    if (empty($errores)) {
        // operación de eliminación en base de datos
        if (EliminarRepuesto($id)) {
            header("Location: gridRepuestos.php");
        } else {
            $errores[] = "Ocurrió un error al intentar eliminar el estudiante en base de datos";
        }
    }
}

echo "<section class=my-3>
            <div class=row>
                <div class='col-sm-6 mb-3 mb-sm-0'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$oRepuesto['descripcion']}</h5>
                            <img class='align-items-center justify-content-center' src={$oRepuesto['ruta_imagen']} alt='Camisa a la venta'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-6 mb-3 mb-sm-0'>
                    <div class='card'>
                        <div class='card-body'>
                            <p class='card-text'>{$oRepuesto['precio']} . . {$oRepuesto['existencias']}</p>
                            <p>
                                {$oRepuesto['detalle']} <br /><br />
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum neque id libero faucibus, at congue tortor aliquet. Nulla vel malesuada sapien.
                                In tempus eros et massa porta, ut venenatis lacus suscipit.</p>
                        </div>
                        <input class='formulario__submit rounded' id='addCar' name='addCar' onclick='agregarAlCarrito(\"{$oRepuesto['id_repuesto']}\",
                                                                                                                        \"{$oRepuesto['ruta_imagen']}\",
                                                                                                                        \"{$oRepuesto['descripcion']}\",
                                                                                                                        \"{$oRepuesto['precio']}\")' type='button' value='Agregar al carrito'>
                    </div>
                </div>
            </div>
        </section>";


require_once "../include/templates/footer.php";
?>