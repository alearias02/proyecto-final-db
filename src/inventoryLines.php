<style>
    .table {
        font-size: medium;
    }
    .custom-container {
        margin-left: 0 !important; /* Align container to the left */
        max-width: 100%; /* Ensure it spans full width */
    }
</style>

<?php
require_once "../include/templates/header.php";
require_once "../DAL/database.php";
require_once "../DAL/inventoryLines.php";

// Conexión
$connection = conectar();
if (!$connection) {
    die("Error de conexión: " . oci_error());
}

$user_name = $_SESSION['usuario']['user_name']; 
// Validar inventory_id
$inventory_id = isset($_GET['inventory_id']) ? intval($_GET['inventory_id']) : 0;
if ($inventory_id <= 0) {
    die("ID de inventario no válido.");
}

$sqlName = "SELECT DESCRIPTION FROM FIDE_SAMDESIGN.FIDE_INVENTORY_TB WHERE inventory_id = $inventory_id";

$resultados = fetchAll($connection, $sqlName);
$inventory_name = (!empty($resultados))
    ? htmlspecialchars($resultados[0]['DESCRIPTION'])
    : 'GENERAL';

// Lógica de paginación
$inventoryLinesPage = isset($_GET['inventoryLinesPage']) ? (int)$_GET['inventoryLinesPage'] : 1; // Página actual (por defecto 1)
$items_per_page = 10; // Número de filas por página
$inventoryLinesOffset = ($inventoryLinesPage - 1) * $items_per_page;


// Consulta paginada
$inventoryLinesQuery = "SELECT * FROM (
              SELECT a.*, ROWNUM rnum FROM (
                  SELECT inventory_lines_id, inventory_id, product_id, comments, quantity_stocked, quantity_reserved, quantity_threshold,
                   status_id, created_by, created_on, modified_on, modified_by
                  FROM FIDE_SAMDESIGN.FIDE_INVENTORY_LINES_TB
                  WHERE inventory_id = :inventory_id
              ) a WHERE ROWNUM <= :max_row
          ) WHERE rnum > :min_row";

$statement = oci_parse($connection, $inventoryLinesQuery);
if (!$statement) {
    die("Error en la preparación de la consulta: " . oci_error($connection));
}

$max_row = $inventoryLinesOffset + $items_per_page;
$min_row = $inventoryLinesOffset;
oci_bind_by_name($statement, ':inventory_id', $inventory_id);
oci_bind_by_name($statement, ':max_row', $max_row);
oci_bind_by_name($statement, ':min_row', $min_row);
oci_execute($statement);

// Obtener las reservaciones
$oInventories = [];
while ($row = oci_fetch_assoc($statement)) {
    $oInventories[] = $row;
}

// Consulta total para la paginación
$total_count_query = "SELECT COUNT(*) AS total FROM FIDE_SAMDESIGN.FIDE_INVENTORY_LINES_TB";
$total_stmt = oci_parse($connection, $total_count_query);
oci_execute($total_stmt);
$total_row = oci_fetch_assoc($total_stmt);
$total_items = $total_row['TOTAL'];
$inventoryLines_total_pages = ceil($total_items / $items_per_page);


// Liberar recursos
oci_free_statement($statement);
oci_free_statement($total_stmt);
oci_close($connection);

?>

<div class="d-flex justify-content-center mt-5 vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Sección izquierda -->
            <div class="col-md-3 text-center">
                <h3>📦 Inventario de Productos</h3>
                <span><strong>Bienvenido,</strong> <?= htmlspecialchars($user_name); ?></span>
                <p>INVENTARIO DE <?= $inventory_name; ?> A LA FECHA: <strong><?= date("d/m/Y"); ?></strong>.</p>
            </div>

            <!-- Sección derecha -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <a href="inventario.php"><button type="button" class="btn" style="color: var(--primarioOscuro) !important;"><</button></a>
                        <h4 class="text-center">Inventario  <?= $inventory_name; ?></h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd3">+</button>
                    </div>
                    <div class="card-body cardAdmin">
                        <div class="table-responsive">
                            <table class="table table-striped" id="inventoryLinesTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Inventario</th>
                                        <th>Producto</th>
                                        <th>Comentarios</th>
                                        <th>Stock</th>
                                        <th>Stock Reservado</th>
                                        <th>Limite para pedido</th>
                                        <th>Ultimo restock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($oInventories)): ?>
                                        <?php foreach ($oInventories as $value): ?>
                                            <tr>
                                                <td><?= !empty($value['INVENTORY_LINES_ID']) ? $value['INVENTORY_LINES_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['INVENTORY_ID']) ? $value['INVENTORY_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['COMMENTS']) ? $value['COMMENTS'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['QUANTITY_STOCKED']) ? $value['QUANTITY_STOCKED'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['QUANTITY_RESERVED']) ? $value['QUANTITY_RESERVED'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['QUANTITY_THRESHOLD']) ? $value['QUANTITY_THRESHOLD'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['LAST_RESTOCK']) ? $value['LAST_RESTOCK'] : 'N/A'; ?></td>
                                                <td>
                                                     <!-- Botón para Ver Detalles -->
                                                    <a href="inventoryLines.php?inventory_id=<?= urlencode($value['INVENTORY_LINES_ID']); ?>" class="btn btn-info" style="display: inline-block;">
                                                        <i class="fas fa-box-open"></i> Ver Detalles
                                                    </a>
                                                    <!-- Botón para eliminar -->
                                                    <button id="eliminar" class="btn btn-danger" onclick="eliminarInventario(<?= $value['INVENTORY_LINES_ID']; ?>)">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>

                                                    <!-- Botón para actualizar -->
                                                    <a href="#" class="btn btn-success actualizarInventario" data-bs-toggle="modal" 
                                                    data-inventory-id="<?= $value['INVENTORY_LINES_ID']; ?>" 
                                                    data-bs-target="#modalUpdate3">
                                                        <i class="fas fa-pencil"></i> Actualizar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No hay registros en el inventario.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $inventoryLines_total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $inventoryLinesPage) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?inventoryLinesPage=<?= $i; ?>"><?= $i; ?></a>
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
<!-- <script src="../js/carrito.js"></script> -->
</body>

</html>

<?php
require_once "../DAL/database.php"; 

// Conectar
$connection = conectar();

// Consultas
$products = fetchAll($connection, "SELECT product_id, description FROM fide_samdesign.fide_product_tb");



oci_close($connection);
?>



<!-- Modal para agregar un producto al inventario -->
<div id="modalAdd3" class="modal fade" tabindex="-1" aria-labelledby="modalAddLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #475A68;">
                <h5 class="modal-title" id="modalAddLabel3">Agregar un Producto al Inventario <?= $inventory_name; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addInventoryForm" class="was-validated" enctype="multipart/form-data">
                <input type="hidden" name="action" value="insertar">
                <div class="modal-body text-center" style="background-color: #eee;">
                    <!-- Agregar el usuario que está creando el inventario -->
                    <input type="hidden" name="created_by" value="<?= htmlspecialchars($user_name); ?>">
                    <!-- Comentarios -->
                    <div class="mb-3">
                        <label for="description">Nombre del inventario</label>
                        <textarea class="form-control mt-2" name="description" id="description" rows="1" required></textarea>
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary">Crear</button>
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



<!-- Incluye jQuery desde CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
        $('#addInventoryForm').on('submit', function (e) {
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
                url: '../DAL/inventoryLines.php', 
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Log the server response for debugging
                    console.log("Response from server:", response);

                    // Check if the response indicates success
                    if (response.includes("success")) {
                        alert("Linea de inventario agregada correctamente."); // Notify the user
                        $('#modalAdd3').modal('hide'); // Close the modal
                        location.reload(); // Reload the page to reflect the new data
                    } else {
                        // Display an error message from the server
                        alert("Error al agregar la linea de inventario " + response);
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
                    const row = $(`#inventoryLinesTable tr`).filter(function () {
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
