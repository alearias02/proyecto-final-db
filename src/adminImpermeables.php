<?php
session_start();
//Si no es ADMIN redirige a index
if (!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'ROLE_ADMIN')) {
    header("Location: index.php");
    exit();
}
session_abort();
?>

<?php
require_once "../include/templates/header.php";
require_once "../DAL/impermeables.php";

$query = "select * from impermeable";
$oImpermeables = getArray($query);
// If there are impermeables, display them in a table
if ($oImpermeables != null && !empty($oImpermeables)) {
    echo "<div class='content-container mt-4'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='card mb-4'>
                            <div class='card-header d-flex justify-content-between align-items-center'>
                                <h4 class='text-center'>Impermeables</h4>
                                <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalAdd' data-bs-whatever='@mdo'>+</button>
                            </div>
                            <div class='card-body cardAdmin'>
                                <div class='table-responsive'>
                                    <table class='table table-striped'>
                                        <thead class='table-dark'>
                                            <tr>
                                                <th>#</th>
                                                <th>Descripción</th>
                                                <th>Detalle</th>
                                                <th>Talla</th>
                                                <th>Precio</th>
                                                <th>Existencias</th>
                                                <th>Imagen</th>
                                                <th>Activo</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>";
    // Loop through each impermeable and create a row for it
    foreach ($oImpermeables as $value) {
        echo "<tr>
                <td>{$value['id_impermeable']}</td>
                <td>{$value['descripcion']}</td>
                <td>{$value['detalle']}</td>
                <td>{$value['talla']}</td>
                <td class='text-end'>{$value['precio']}</td>
                <td class='text-center'>{$value['existencias']}</td>
                <td class='text-end'><img src='{$value['ruta_imagen']}' style='width:40px; height:40px' alt='Ruta imagen'></td>
                <td><input type='checkbox' " . ($value['activo'] == 1 ? 'checked' : '') . " disabled></td>
                <td>
                    <button id=eliminar class='btn btn-danger' onclick='eliminarImpermeable({$value['id_impermeable']})'>
                        <i class='fas fa-trash'></i> Eliminar
                    </button>
                    <a href='#' class='btn btn-success' onclick='cargarFormularioActualizacion({$value['id_impermeable']})' data-bs-toggle='modal' data-bs-target='#modalUpdate' data-bs-whatever='@mdo'>
                        <i class='fas fa-pencil'></i> Actualizar
                    </a>
                </td>
            </tr>";
    }
    echo "</tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>
</div>";
} else {
    echo "<p>No hay productos</p>";
}

require_once "../include/templates/footer.php";
?>


<div id="modalAdd" class="modal fade" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #475A68;">
                <h5 style="margin-left: 300px">Agregar impermeable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" class="was-validated " enctype="multipart/form-data">
                <div class="modal-body text-center" style="background-color: #eee;">

                    <div class="mb-3 ">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="descripcion" required="true" />
                    </div>
                    <div class="mb-3">
                        <label for="detalle">Detalle</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="detalle" required="true" />
                    </div>
                    <div class="mb-3">
                        <label for="talla">Talla</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="talla" required="true" />
                    </div>
                    <div class="mb-3">
                        <label for="precio">Precio</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="precio" required="true" />
                    </div>
                    <div class="mb-3">
                        <label for="existencias">Existencias</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="existencias" required="true" />
                    </div>
                    <div class="mb-3">
                        <label for="activo">Activo</label>
                        <input class="form-check-input" type="checkbox" style="border: none; box-shadow: 2px 2px 2px 2px rgba(0, 0, 0,.2);" name="activo" id="activo" />
                    </div>
                    <div class="mb-3">
                        <label for="categoria">Categoria</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="categoria" id="categoria" value="7" />
                    </div>
                    <div class="mb-3">
                        <label for="imagen">Imagen del item</label>
                        <input class="form-control mb-3" id="imagen" style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" type="file" name="imagen" onchange="readURL(this);" />
                        <img id="imagen" src="#" alt="Image" height="200" />
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-primary boton-bs" onclick="mostrarMensajeModal()" type="submit">
                        Crear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="messageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="messageText"></p>
            </div>
        </div>
    </div>
</div>

<div id="modalUpdate" class="modal fade" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #475A68;">
                <h5 style="margin-left: 300px">Actualizar impermeable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdate" method="POST" class="was-validated" enctype="multipart/form-data">
                <div class="modal-body text-center" style="background-color: #eee;">
                    <input type="hidden" name="id_impermeable" id="id_impermeable" value='" .  intval($impermeable[' id_impermeable']) . "'> 
                    <div class='mb-3'>
                        <label for='descripcion'>Descripción</label>
                        <input type='text' class='form-control mt-2' style='border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);' name='descripcion' required='true' value='" . htmlspecialchars($impermeable[' descripcion']) . "' />
                    </div>
                    <div class='mb-3'>
                        <label for='detalle'>Detalle</label>
                        <input type='text' class='form-control mt-2' style='border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);' name='detalle' required='true' value='" . htmlspecialchars($impermeable['detalle']) . "' />
                    </div>
                    <div class=" mb-3">
                        <label for="talla">Talla</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="talla" required="true" value='" . htmlspecialchars($impermeable[' talla']) . "'  />
                    </div>
                    <div class=" mb-3">
                        <label for="precio">Precio</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="precio" required="true" value='" . floatval($impermeable[' precio']) . "' />
                    </div>
                    <div class=" mb-3">
                        <label for="existencias">Existencias</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="existencias" required="true" value='" . intval($impermeable[' existencias']) . "' />
                    </div>
                    <div class="mb-3">
                        <label for="activo">Activo</label>
                        <input class="form-check-input" type="checkbox" style="margin-top: 0.5rem;" name="activo" id="activoCheckbox" 
                            <?php echo (isset($impermeable) && $impermeable['activo'] == 1) ? 'checked' : ''; ?> />
                    </div>
                    <div class="mb-3">
                        <label for="categoria">Categoria</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="categoria" id="categoria" value='" . intval($impermeable[' existencias']) . "' />
                    </div>
                    <div class="mb-3">
                        <label for="imagen">Imagen del item</label>
                        <input class="form-control mb-3" id="imagenInput" style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" type="file" name="imagen" onchange="readURL(this);">
                        <img id="imagenDisplay" src="#" alt="Imagen del Impermeable" style="height: 200px;" />
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-success" type="submit">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function cargarFormularioActualizacion(id) {
        // Hacer una solicitud AJAX para obtener los detalles del impermeable a actualizar
        $.ajax({
            type: "POST",
            url: "../DAL/impermeables.php", // Ruta al archivo PHP que manejará la solicitud
            data: {
                id: id,
                action: 'obtenerDetalles'
            }, // Pasamos el ID y la acción para obtener los detalles del impermeable
            success: function(response) {
                // Cargar los detalles del impermeable en el formulario de actualización modal
                var data = JSON.parse(response);
                // Asegurarse de que el formulario en modalUpdate se rellena correctamente:
                    $('#modalUpdate [name="id_impermeable"]').val(data.id_impermeable);
                    $('#modalUpdate [name="descripcion"]').val(data.descripcion);
                    $('#modalUpdate [name="detalle"]').val(data.detalle);
                    $('#modalUpdate [name="talla"]').val(data.talla);
                    $('#modalUpdate [name="precio"]').val(data.precio);
                    $('#modalUpdate [name="existencias"]').val(data.existencias);
                    $('#modalUpdate [name="categoria"]').val(data.id_categoria);
                    $('#modalUpdate [name="activo"]').prop('checked', data.activo == 1);
                    $('#imagenDisplay').attr('src', '../img/' + data.ruta_imagen); // Asegúrate de que la ruta es correcta
                    $('#modalUpdate').modal('show');
            },
            error: function(xhr, status, error) {
                // Maneja errores
                console.error(xhr.responseText);
            }
        });
    }

    function actualizarImpermeable() {
        // Obtener los valores del formulario de actualización
        var formData = new FormData($('#formUpdate')[0]);

        // Hacer una solicitud AJAX para actualizar el impermeable
        $.ajax({
            type: "POST",
            url: "../DAL/impermeables.php", // Ruta al archivo PHP que manejará la actualización
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Recargar la página o actualizar la tabla de impermeables
                location.reload();
            },
            error: function(xhr, status, error) {
                // Maneja errores
                console.error(xhr.responseText);
            }
        });
    }
</script>


<?php
require_once "../DAL/impermeables.php";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // recibir los valores
    $descripcion = $_POST['descripcion'];
    $detalle = $_POST['detalle'];
    $talla = $_POST['talla'];
    $precio = $_POST['precio'];
    $existencias = $_POST['existencias'];
    $activo = isset($_POST['activo']) ? 1 : 0;
    $categoria = $_POST['categoria'];

    // sanitizar data 
    $descripcion = htmlspecialchars($descripcion);
    $detalle = htmlspecialchars($detalle);
    $talla = htmlspecialchars($talla);
    $precio = floatval($precio);
    $existencias = intval($existencias);
    $categoria = intval($categoria);

    // subida de imagen
    $folderIMG = "../img/"; // directorio a guardar la imagen 
    $img = $folderIMG . basename($_FILES["imagen"]["name"]); // Full path
    $imageFileType = strtolower(pathinfo($img, PATHINFO_EXTENSION)); // File extension

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if ($check !== false) {
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $img)) {
        } else {
            echo "Ocurrio un error al cargar la imagen.";
        }
    } else {
        echo "Solamente archivos tipo imagen.";
    }


    $dbImpermeable = IngresarImpermeable($categoria, $descripcion, $detalle, $talla, $precio, $existencias, $img, $activo);

    if ($dbImpermeable) {
        echo "<script>
                function mostrarMensajeModal() {
                    // Mostrar el mensaje en el modal
                    document.getElementById('messageText').innerHTML = 'Nuevo impermeable ha sido agregado';
                    $('#messageModal').modal('show');
                    // Cerrar el modal después de 5 segundos
                    setTimeout(function () {
                        $('#messageModal').modal('hide');
                    }, 5000);
                }
             </script>";
    } else {
        echo "Error: !";
    }
}
?>


<script>
    function eliminarImpermeable(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este impermeable?")) {
            // Envía una solicitud AJAX al backend para eliminar el impermeable
            $.ajax({
                type: "POST",
                url: "../DAL/impermeables.php", // Ruta al archivo PHP que contiene la función de eliminación
                data: {
                    action: 'eliminar',
                    id: id
                }, // Pasamos el ID y la acción a realizar
                success: function(response) {
                    // Recarga la página o actualiza la tabla de impermeables
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Maneja errores
                    console.error(xhr.responseText);
                }
            });
        }
    }
</script>