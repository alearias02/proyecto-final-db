<?php
session_start();
if (!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'ROLE_ADMIN')) {
    header("Location: index.php");
    exit();
}
session_abort();

require_once "../include/templates/header.php";
require_once "../DAL/database.php";

$connection = conectar();
if (!$connection) {
    die("Error de conexión: " . oci_error());
}


$user_name = $_SESSION['usuario']['user_name']; 

// Variables para paginación
$productosPage = isset($_GET['productosPage']) ? (int)$_GET['productosPage'] : 1;
$items_per_page = 10;
$offset = ($productosPage - 1) * $items_per_page;

// Consulta paginada
$productosQuery = "SELECT * FROM (
              SELECT a.*, ROWNUM rnum FROM (
                  SELECT Product_ID, Description, Comments, Unit_price, Quantity_OnHand, Quantity_Lend, Total_Qty, Image_path, Status_ID
                  FROM FIDE_SAMDESIGN.FIDE_PRODUCT_TB
              ) a WHERE ROWNUM <= :max_row
          ) WHERE rnum > :min_row";

$statement = oci_parse($connection, $productosQuery);

$max_row = $offset + $items_per_page;
$min_row = $offset;

oci_bind_by_name($statement, ':max_row', $max_row);
oci_bind_by_name($statement, ':min_row', $min_row);
oci_execute($statement);

$productos = [];
while ($row = oci_fetch_assoc($statement)) {
    $productos[] = $row;
}

// Consulta para obtener el total de registros
$total_count_query = "SELECT COUNT(*) AS total FROM FIDE_SAMDESIGN.FIDE_PRODUCT_TB";
$total_stmt = oci_parse($connection, $total_count_query);
oci_execute($total_stmt);
$total_row = oci_fetch_assoc($total_stmt);
$total_items = $total_row['TOTAL'];
$total_pages = ceil($total_items / $items_per_page);

// Liberar recursos
oci_free_statement($statement);
oci_free_statement($total_stmt);
oci_close($connection);
?>

<div class='content-container mt-4'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-12'>
                <div class='card mb-4'>
                    <div class='card-header d-flex justify-content-between align-items-center'>
                        <h4 class='text-center'>Productos</h4>
                        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalAddProduct'>+</button>
                    </div>
                    <div class='card-body cardAdmin'>
                        <div class='table-responsive'>
                            <table class='table table-striped'>
                                <thead class='table-dark'>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Comentarios</th>
                                        <th>Precio Unitario</th>
                                        <th>Stock</th>
                                        <th>Prestado</th>
                                        <th>Stock Total</th>
                                        <th>Imagen</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($productos)): ?>
                                    <?php foreach ($productos as $producto): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($producto['PRODUCT_ID']); ?></td>
                                            <td><?= htmlspecialchars($producto['DESCRIPTION']); ?></td>
                                            <td><?= htmlspecialchars($producto['COMMENTS']); ?></td>
                                            <td class='text-end'><?= number_format($producto['UNIT_PRICE'], 2); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($producto['QUANTITY_ONHAND']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($producto['QUANTITY_LEND']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($producto['TOTAL_QTY']); ?></td>
                                            <td class='text-center'><img src='<?= htmlspecialchars($producto['IMAGE_PATH']); ?>' style='width:40px; height:40px' alt='Imagen Producto'></td>
                                            <td>
                                                <button class='btn btn-danger' onclick='eliminarProducto(<?= $producto['PRODUCT_ID']; ?>)'>
                                                    <i class='fas fa-trash'></i> Eliminar
                                                </button>
                                                <a href='#' class='btn btn-success' onclick='cargarFormularioActualizacion(<?= $producto['PRODUCT_ID']; ?>)' data-bs-toggle='modal' data-bs-target='#modalUpdate'>
                                                    <i class='fas fa-pencil'></i> Actualizar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan='9' class='text-center'>No hay productos disponibles</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $productosPage) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?productosPage=<?= $i; ?>"> <?= $i; ?> </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class=" text-white p-2 bg-dark" style="position: fixed; bottom: 0; left: 0; width: 100%;">
    <div class="container">
        <div class="col">
            <p class="lead text-center">
                &COPY;Derechos Reservados
            </p>
        </div>
    </div>
</footer>
<script src="../js/jquery-3.7.1.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/carrito.js"></script>
<script src="../js/camisas.js"></script>
<script src="../js/impermeable.js"></script>
<script src="../js/repuestos.js"></script>
</body>

<?php
require_once "../DAL/conexion.php";
require_once "../DAL/database.php"; // Incluye la conexión a la base de datos

// Conectar a la base de datos
$connection = conectar();
// Consultas
$categories = fetchAll($connection, "SELECT category_id, description FROM fide_samdesign.fide_category_type_tb");
// Cierra la conexión después de obtener los datos
oci_close($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['Image_path']) && $_FILES['Image_path']['error'] === UPLOAD_ERR_OK) {

    $folderIMG = "../img/";
    $img = $folderIMG . basename($_FILES["Image_path"]["name"]);
    $imageFileType = strtolower(pathinfo($img, PATHINFO_EXTENSION));

    // Verifica que sea una imagen real
    $check = getimagesize($_FILES["Image_path"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["Image_path"]["tmp_name"], $img)) {
            echo "La imagen ha sido subida correctamente.";
        } else {
            echo "Ocurrió un error al cargar la imagen.";
        }
    } else {
        echo "El archivo no es una imagen válida.";
    }
}

?>

<div id="modalAddProduct" class="modal fade" tabindex="-1" aria-labelledby="modalAddProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #475A68;">
                <h5 class="modal-title mx-auto">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProductForm" method="POST" class="was-validated" enctype="multipart/form-data">
                <div class="modal-body text-center" style="background-color: #eee;">
                    <input type="hidden" name="action" value="insertar">
                    <input type="hidden" name="created_by" value="<?= htmlspecialchars($user_name); ?>">
                    <div class="mb-3">
                        <label for="Description">Descripción</label>
                        <input type="text" class="form-control mt-2" name="Description" required />
                    </div>

                    <div class="mb-3">
                        <label for="Comments">Comentarios</label>
                        <input type="text" class="form-control mt-2" name="Comments" required />
                    </div>

                    <div class="mb-3">
                        <label for="Unit_price">Precio Unitario</label>
                        <input type="number" step="0.01" class="form-control mt-2" name="Unit_price" required />
                    </div>


                    <div class="mb-3">
                        <label for="Total_Qty">Cantidad Total</label>
                        <input type="number" class="form-control mt-2" name="Total_Qty" required />
                    </div>


                    <div class="mb-3">
                        <label for="category_id">Categoria del producto</label>
                        <select class="form-control mt-2" name="category_id" id="category_id" required>
                            <option value="" disabled selected>Seleccione una categoria</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['CATEGORY_ID']; ?>"><?= htmlspecialchars($category['DESCRIPTION']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="Image_path">Imagen del Producto</label>
                        <input class="form-control mb-3" type="file" name="Image_path" onchange="readURL(this);" required />
                        <img id="preview-image" src="#" alt="Imagen Producto" height="200" style="display:none;" />
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-primary" type="submit">
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

<!-- <div id="modalUpdate" class="modal fade" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #475A68;">
                <h5 style="margin-left: 300px">Actualizar repuesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdate" method="POST" class="was-validated" enctype="multipart/form-data">
                <div class="modal-body text-center" style="background-color: #eee;">
                    <input type="hidden" name="id_repuesto" id="id_repuesto" value='" .  intval($repuesto[' id_repuesto']) . "'> 
                    <div class='mb-3'>
                        <label for='descripcion'>Descripción</label>
                        <input type='text' class='form-control mt-2' style='border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);' name='descripcion' required='true' value='" . htmlspecialchars($repuesto[' descripcion']) . "' />
                    </div>
                    <div class='mb-3'>
                        <label for='detalle'>Detalle</label>
                        <input type='text' class='form-control mt-2' style='border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);' name='detalle' required='true' value='" . htmlspecialchars($repuesto['detalle']) . "' />
                    </div>
                    <div class=" mb-3">
                        <label for="precio">Precio</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="precio" required="true" value='" . floatval($repuesto[' precio']) . "' />
                    </div>
                    <div class=" mb-3">
                        <label for="existencias">Existencias</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="existencias" required="true" value='" . intval($repuesto[' existencias']) . "' />
                    </div>
                    <div class="mb-3">
                        <label for="activo">Activo</label>
                        <input class="form-check-input" type="checkbox" style="margin-top: 0.5rem;" name="activo" id="activoCheckbox" 
                            <?php echo (isset($repuesto) && $repuesto['activo'] == 1) ? 'checked' : ''; ?> />
                    </div>
                    <div class="mb-3">
                        <label for="categoria">Categoria</label>
                        <input type="text" class="form-control mt-2 " style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" name="categoria" id="categoria" value='" . intval($repuesto[' existencias']) . "' />
                    </div>
                    <div class="mb-3">
                        <label for="imagen">Imagen del item</label>
                        <input class="form-control mb-3" id="imagenInput" style="border: none; box-shadow: 5px 5px 2px 2px rgba(0, 0, 0,.2);" type="file" name="imagen" onchange="readURL(this);">
                        <img id="imagenDisplay" src="#" alt="Imagen del Repuesto" style="height: 200px;" />
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
</div> -->


<!-- Incluye jQuery desde CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#preview-image').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}


// Función para cargar datos en el formulario de actualización
    $(document).ready(function () {
        $(document).on('click', '.actualizarReservations', function (e) {
            e.preventDefault(); // Evita que el formulario recargue la página por defecto

            var reservationID = $(this).data('reservation-id'); // Obtiene el ID de la habitacion desde el botón clickeado
            if (!reservationID) {
                console.error("No se proporcionó un ID de reservacion.");
                return;
            }

            // Realiza una solicitud AJAX para obtener los datos de la habitacion seleccionado
            $.ajax({
                method: "POST",
                url: "../DAL/reservationsHotel.php",
                data: {
                    action: "obtenerDetalles", // Acción para identificar la solicitud en el backend
                    reservation_id: reservationID
                },
                success: function (response) {
                    try {
                        var data = JSON.parse(response); // Convierte la respuesta JSON a un objeto
                        console.log("Datos recibidos:", data); // Depuración: Muestra los datos recibidos

                        // Llena los campos del modal con los datos recibidos
                        $.each(data, function (Key, value) {
                            const field = $(`#modalUpdate3 [name="${Key}"]`);
                            if (field.length > 0) {
                                field.val(value);
                            }
                        });
                        // Muestra el modal después de llenar los campos
                        $('#modalUpdate3').modal('show');
                    } catch (error) {
                        console.error("Error al analizar la respuesta JSON:", error);
                        alert("Ocurrió un error al cargar los detalles de la reservacion.");
                    }
                },
                error: function (xhr) {
                    console.error("Error AJAX:", xhr.responseText);
                    alert("No se pudieron cargar los detalles de la reservacion.");
                }
            });
        });

          // Handle form submission for adding a new service
        $('#addProductForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            // Serialize the form data
            var formData = $(this).serialize();
            console.log("Data enviada desde el formulario:", formData); 
            var submitButton = $(this).find('button[type="submit"]'); 

            // Deshabilitar el botón para evitar múltiples clics
            submitButton.prop('disabled', true);

            // Log the form data for debugging
            console.log("Data en el form:", formData);

            // Make the AJAX request
            $.ajax({
                url: '../DAL/productos.php', 
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Log the server response for debugging
                    console.log("Response from server:", response);

                    // Check if the response indicates success
                    if (response.includes("success")) {
                        alert("Producto agregado correctamente."); // Notify the user
                        $('#modalAddProduct').modal('hide'); // Close the modal
                        location.reload(); // Reload the page to reflect the new data
                    } else {
                        // Display an error message from the server
                        alert("Error al agregar el producto " + response);
                    }
                },
                error: function (xhr, status, error) {
                    // Log AJAX errors for debugging
                    console.error("Error al enviar el formulario:", error);
                    alert("Hubo un problema al enviar el formulario. Por favor, inténtelo de nuevo.");
                }
            });
        });

    // Maneja el envío del formulario para actualizar un servicio
    $('#formUpdateReservation').on('submit', function (e) {
    e.preventDefault(); // Evita que el formulario recargue la página por defecto

    var formData = $(this).serialize(); // Serializa los datos del formulario
    var submitButtonUpdate = $(this).find('button[type="submit"]');

    // Deshabilitar el botón para evitar múltiples clics
    submitButtonUpdate.prop('disabled', true);
    console.log("Actualizando reserva en hotel con datos:", formData);

    // Validar que todos los campos requeridos estén presentes
    if (!formData.includes('RESERVATION_CUSTOMER_ID') || !formData.includes('ROOM_ID') || !formData.includes('QTY_NIGHTS') || !formData.includes('HOTEL_ID') || !formData.includes('MODIFIED_BY')) {
        console.error("Formulario incompleto. Datos enviados:", formData);
        showToast("Error", "Formulario incompleto. Por favor, revisa los campos.", "error");
        submitButtonUpdate.prop('disabled', false);
        return;
    }

    // Realiza la solicitud AJAX
    $.ajax({
        method: "POST",
        url: "../DAL/reservationsHotel.php",
        data: formData,
        success: function (response) {
            console.log("Respuesta del servidor (completa):", JSON.stringify(response));//log

            if (response.includes("success")) {
                showToast("Éxito", response, "success");
                $('#modalUpdate3').modal('hide');
                location.reload(); // Recargar página
            } else {
                showToast("Error", "La actualización falló. Intenta nuevamente.", "error");
                submitButtonUpdate.prop('disabled', false);
            }
        },
        error: function (xhr) {
            console.error("Error al actualizar:", xhr.responseText);
            showToast("Error", "No se pudo actualizar la reservacion. Intenta de nuevo.", "error");
            $('#modalUpdate3').modal('show');
            submitButtonUpdate.prop('disabled', false); // Habilitar botón para otro intento
        }
    });
});
    // Función para mostrar un mensaje tipo toast con estilo
    function showToast(title, message, type) {
        const toast = document.createElement("div");
        toast.className = `toast toast-${type}`; // Aplica la clase según el tipo (p. ej., éxito, error)
        toast.innerHTML = `<strong>${title}</strong><p>${message}</p>`;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add("fade-out");
            toast.addEventListener("transitionend", () => toast.remove());
        }, 3000);
    }

    // Función para actualizar solo la fila modificada en la tabla
    function loadUpdatedRow(reservationID) {
        $.ajax({
            url: '../DAL/reservationsHotel.php',
            method: 'POST',
            data: {
                action: "obtenerDetalles",
                reservation_id: reservationID
            },
            success: function (response) {
                try {
                    var updatedData = JSON.parse(response);

                    // Actualiza la fila específica en la tabla
                    const row = $(`#inventoryTable tr`).filter(function () {
                        return $(this).find('td:first').text() === updatedData.RESERVATION_ID;
                    });

                    if (row.length > 0) {
                        row.find('td').eq(1).text(updatedData.START_DATE);
                        row.find('td').eq(2).text(updatedData.END_DATE);
                        row.find('td').eq(3).text(updatedData.QTY_NIGHTS);
                        row.find('td').eq(4).text(updatedData.COMMENTS);
                        row.find('td').eq(5).text(updatedData.RESERVATION_CUSTOMER_ID);
                        row.find('td').eq(6).text(updatedData.ROOM_ID);
                        row.find('td').eq(7).text(updatedData.HOTEL_ID);
                        row.find('td').eq(8).text(updatedData.STATUS_ID);
                        row.find('td').eq(9).text(updatedData.TOTAL_AMOUNT);
                    }
                } catch (error) {
                    console.error("Error al actualizar la fila de la tabla:", error);
                }
            },
            error: function (xhr) {
                console.error("Error al obtener la fila actualizada:", xhr.responseText);
            }
        });
    }
});

// Función para eliminar una habitacion
function eliminarReservacion(id) {
        console.log("Intentando eliminar habitacion con ID:", id);
        if (confirm("¿Estás seguro de que deseas eliminar esta reservacion?")) {
            $.ajax({
                method: "POST",
                url: "../DAL/reservationsHotel.php",
                data: {
                    action: "eliminar",
                    reservation_id: id
                },
                success: function (response) {
                    console.log("Respuesta de eliminación:", response);
                    if (response.includes("Éxito")) {
                        alert("Reserva de habitacion eliminada correctamente.");
                        location.reload(); // Refresca la página para reflejar los cambios
                    } else {
                        alert("No se pudo eliminar la reserva: " + response);
                    }
                },
                error: function (xhr) {
                    console.error("Error al eliminar:", xhr.responseText);
                    alert("No se pudo eliminar la habitacion.");
                }
            });
        }
    }
</script>

