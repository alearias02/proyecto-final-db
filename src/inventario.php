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
require_once "../DAL/database.php";
require_once "../DAL/inventario.php";

// Lógica de paginación
$inventarioPage = isset($_GET['inventoryPage']) ? (int)$_GET['inventoryPage'] : 1; // Página actual (por defecto 1)
$items_per_page = 10; // Número de filas por página
$inventoryOffset = ($inventoryPage - 1) * $items_per_page;

// Conexión
$connection = conectar();
if (!$connection) {
    die("Error de conexión: " . oci_error());
}

// Consulta paginada
$inventoryQuery = "SELECT * FROM (
              SELECT a.*, ROWNUM rnum FROM (
                  SELECT i.inventory_id, i.product_id, p.description, i.quantity_stock, i.last_restock, i.comments, 
                         created_by, created_on, modified_on, modified_by
                  FROM FIDE_SAMDESIGN.FIDE_INVENTORY_TB i
                  INNER JOIN FIDE_SAMDESIGN.FIDE_PRODUCT_TB p ON p.product_id = i.product_id
                  ORDER BY inventory_id
              ) a WHERE ROWNUM <= :max_row
          ) WHERE rnum > :min_row";

$statement = oci_parse($connection, $inventoryQuery);
if (!$statement) {
    die("Error en la preparación de la consulta: " . oci_error($connection));
}

$max_row = $inventoryOffset + $items_per_page;
$min_row = $inventoryOffset;
oci_bind_by_name($statement, ':max_row', $max_row);
oci_bind_by_name($statement, ':min_row', $min_row);
oci_execute($statement);

// Obtener las reservaciones
$oInventories = [];
while ($row = oci_fetch_assoc($statement)) {
    $oInventories[] = $row;
}

// Consulta total para la paginación
$total_count_query = "SELECT COUNT(*) AS total FROM FIDE_SAMDESIGN.FIDE_INVENTORY_TB";
$total_stmt = oci_parse($connection, $total_count_query);
oci_execute($total_stmt);
$total_row = oci_fetch_assoc($total_stmt);
$total_items = $total_row['TOTAL'];
$inventory_total_pages = ceil($total_items / $items_per_page);

// Liberar recursos
oci_free_statement($statement);
oci_free_statement($total_stmt);
oci_close($connection);

// Mostrar advertencia si no hay datos
if (!is_array($oInventories) || empty($oInventories)) {
    echo "<div class='container mt-4'>
            <div class='alert alert-warning' role='alert'>
                No hay registros disponibles.
            </div>
          </div>";
    exit;
}
?>

<div class="content-container mt-4 mb-8">
    <div class="container custom-container">
        <div class="row">
            <!-- Sección izquierda: 3 columnas -->
            <div class="col-md-3">
                <h3>Sección de Inventario</h3>
                <p>INVENTARIO DE SAM DESIGN A LA FECHA: .</p>
            </div>

            <!-- Sección derecha: 9 columnas -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="text-center">Inventario</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd3" data-bs-whatever="@mdo">+</button>
                    </div>
                    <div class="card-body cardAdmin">
                        <div class="table-responsive">
                            <table class="table table-striped" id="inventoryTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Ultimo restock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($oInventories)): ?>
                                        <?php foreach ($oInventories as $value): ?>
                                            <tr>
                                                <td><?= !empty($value['INVENTORY_ID']) ? $value['INVENTORY_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['COMMENTS']) ? $value['COMMENTS'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['LAST_RESTOCK']) ? $value['LAST_RESTOCK'] : 'N/A'; ?></td>
                                                <td>
                                                    <button id="eliminar" class="btn btn-danger" onclick="eliminarInventario(<?= $value['INVENTORY_ID']; ?>)">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                    <a href="#" class="btn btn-success actualizarInventario" data-bs-toggle="modal" data-inventory-id="<?= $value['INVENTORY_ID']; ?>" data-bs-target="#modalUpdate3" data-bs-whatever="@mdo">
                                                        <i class="fas fa-pencil"></i> Actualizar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No hay Inventarios creados.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $inventory_total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $inventoryPage) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?inventoryPage=<?= $i; ?>"><?= $i; ?></a>
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

<?php
require_once "../DAL/database.php"; // Incluye la conexión a la base de datos

// Conectar a la base de datos
$connection = conectar();

// Función reutilizable para ejecutar consultas y obtener resultados

// Consultas
$statuses = fetchAll($connection, "SELECT status_id, status_description FROM status_tb");
$hotels = fetchAll($connection, "SELECT hotel_id, hotel_name || ' - ' || hotel_branch_id as full_hotelName FROM hotel_tb");
$rooms = fetchAll($connection, "SELECT hr.room_id, hr.room_number || ' - ' || h.hotel_name AS room FROM hotel_rooms_tb hr LEFT JOIN hotel_tb h ON hr.hotel_id = h.hotel_id ORDER BY ROOM_ID ASC");
$customers = fetchAll($connection, "SELECT customer_id, customer_name FROM customer_tb where status_id = 1");
$payMethods = fetchAll($connection, "SELECT payment_method_id, payment_method_name FROM payment_method_tb");


// Cierra la conexión después de obtener los datos
oci_close($connection);
?>

<!-- Modal para agregar una reservación -->
<div id="modalAdd3" class="modal fade" tabindex="-1" aria-labelledby="modalAddLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #475A68;">
                <h5 class="modal-title" id="modalAddLabel3">Agregar una reservacion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addReservationForm" class="was-validated" enctype="multipart/form-data">
                <input type="hidden" name="action" value="insertar">
                <div class="modal-body text-center" style="background-color: #eee;">

                    <!-- Fecha de inicio -->
                    <div class="mb-3">
                        <label for="startDate">Fecha de inicio</label>
                        <input class="form-control" type="date" name="start_date" id="startDate" required />
                    </div>

                    <!-- Fecha de fin -->
                    <div class="mb-3">
                        <label for="endDate">Fecha final</label>
                        <input class="form-control" type="date" name="end_date" id="endDate" required />
                    </div>

                    <!-- Cantidad de noches -->
                    <div class="mb-3">
                        <label for="qty_nights">Cantidad de noches</label>
                        <input class="form-control mt-2" type="number" name="qty_nights" id="qty_nights" required>
                    </div>

                    <!-- Comentarios -->
                    <div class="mb-3">
                        <label for="comments">Comentarios</label>
                        <textarea class="form-control mt-2" name="comments" id="comments" rows="3" required></textarea>
                    </div>

                    <!-- CLIENTE -->
                    <div class="mb-3">
                        <label for="reservation_customer_id">Cliente de la reserva</label>
                        <select class="form-control mt-2" name="reservation_customer_id" id="reservation_customer_id" required>
                            <option value="" disabled selected>Seleccione un cliente</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['CUSTOMER_ID']; ?>"><?= htmlspecialchars($customer['CUSTOMER_NAME']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Habitación -->
                    <div class="mb-3">
                        <label for="room_id">Habitación</label>
                        <select class="form-control mt-2" name="room_id" id="room_id" required>
                            <option value="" disabled selected>Seleccione una habitación</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= $room['ROOM_ID']; ?>"><?= htmlspecialchars($room['ROOM']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- HOTEL -->
                    <div class="mb-3">
                        <label for="hotel_id">Hotel donde vacacionar</label>
                        <select class="form-control mt-2" name="hotel_id" id="hotel_id" required>
                            <option value="" disabled selected>Seleccione un hotel</option>
                            <?php foreach ($hotels as $hotel): ?>
                                <option value="<?= $hotel['HOTEL_ID']; ?>"><?= htmlspecialchars($hotel['FULL_HOTELNAME']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Estado de LA RESERVA -->
                    <div class="mb-3">
                        <label for="status_id">Estado de la reserva</label>
                        <select class="form-control mt-2" name="status_id" id="status_id" required>
                            <option value="" disabled selected>Seleccione un estado</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status['STATUS_ID']; ?>"><?= htmlspecialchars($status['STATUS_DESCRIPTION']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- tipo de pago -->
                    <div class="mb-3">
                        <label for="payment_method_id">Metodo de pago</label>
                        <select class="form-control mt-2" name="payment_method_id" id="payment_method_id" required>
                            <option value="" disabled selected>Seleccione un tipo de pago</option>
                            <?php foreach ($payMethods as $payMethod): ?>
                                <option value="<?= $payMethod['PAYMENT_METHOD_ID']; ?>"><?= htmlspecialchars($payMethod['PAYMENT_METHOD_NAME']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Creado por -->
                    <div class="mb-3">
                        <label for="created_by">Creado por</label>
                        <input type="text" class="form-control mt-2" name="created_by" id="created_by" placeholder="Ingrese su nombre" required />
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

<!-- Modal para actualizar una reservación -->
<div id="modalUpdate3" class="modal fade" tabindex="-1" aria-labelledby="modalUpdateLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #475A68;">
                <h5 class="modal-title" id="modalUpdateLabel3">Actualizar una reserva de Habitación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateReservation" class="was-validated" enctype="multipart/form-data">
                <div class="modal-body text-center" style="background-color: #eee;">
                    <input type="hidden" name="RESERVATION_ID" id="RESERVATION_ID" value=""> 
                    <input type="hidden" name="action" value="actualizar">

                    <!-- Fecha de inicio -->
                    <div class="mb-3">
                        <label for="START_DATE">Fecha de inicio</label>
                        <input class="form-control" type="date" name="START_DATE" id="START_DATE" required />
                    </div>

                    <!-- Fecha de fin -->
                    <div class="mb-3">
                        <label for="END_DATE">Fecha final</label>
                        <input class="form-control" type="date" name="END_DATE" id="END_DATE" required />
                    </div>

                    <!-- Cantidad de noches -->
                    <div class="mb-3">
                        <label for="QTY_NIGHTS">Cantidad de noches</label>
                        <input class="form-control mt-2" type="number" name="QTY_NIGHTS" id="QTY_NIGHTS" required>
                    </div>

                    <!-- Comentarios -->
                    <div class="mb-3">
                        <label for="COMMENTS">Comentarios</label>
                        <textarea class="form-control mt-2" name="COMMENTS" id="COMMENTS" rows="3" required></textarea>
                    </div>

                    <!-- CLIENTE -->
                    <div class="mb-3">
                        <label for="RESERVATION_CUSTOMER_ID">Cliente de la reserva</label>
                        <select class="form-control mt-2" name="RESERVATION_CUSTOMER_ID" id="RESERVATION_CUSTOMER_ID" required>
                            <option value="" disabled selected>Seleccione un cliente</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['CUSTOMER_ID']; ?>"><?= htmlspecialchars($customer['CUSTOMER_NAME']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Habitación -->
                    <div class="mb-3">
                        <label for="ROOM_ID">Habitación</label>
                        <select class="form-control mt-2" name="ROOM_ID" id="ROOM_ID" required>
                            <option value="" disabled selected>Seleccione una habitación</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= $room['ROOM_ID']; ?>"><?= htmlspecialchars($room['ROOM']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- HOTEL -->
                    <div class="mb-3">
                        <label for="HOTEL_ID">Hotel donde vacacionar</label>
                        <select class="form-control mt-2" name="HOTEL_ID" id="HOTEL_ID" required>
                            <option value="" disabled selected>Seleccione un hotel</option>
                            <?php foreach ($hotels as $hotel): ?>
                                <option value="<?= $hotel['HOTEL_ID']; ?>"><?= htmlspecialchars($hotel['FULL_HOTELNAME']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Estado de LA RESERVA -->
                    <div class="mb-3">
                        <label for="STATUS_ID">Estado de la reserva</label>
                        <select class="form-control mt-2" name="STATUS_ID" id="STATUS_ID" required>
                            <option value="" disabled selected>Seleccione un estado</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status['STATUS_ID']; ?>"><?= htmlspecialchars($status['STATUS_DESCRIPTION']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- tipo de pago -->
                    <div class="mb-3">
                        <label for="PAYMENT_METHOD_ID">Metodo de pago</label>
                        <select class="form-control mt-2" name="PAYMENT_METHOD_ID" id="PAYMENT_METHOD_ID" required>
                            <option value="" disabled selected>Seleccione un tipo de pago</option>
                            <?php foreach ($payMethods as $payMethod): ?>
                                <option value="<?= $payMethod['PAYMENT_METHOD_ID']; ?>"><?= htmlspecialchars($payMethod['PAYMENT_METHOD_NAME']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Modificado por -->
                    <div class="mb-3">
                        <label for="MODIFIED_BY">Modificado por</label>
                        <input type="text" class="form-control mt-2" name="MODIFIED_BY" id="MODIFIED_BY" placeholder="Ingrese su nombre" required />
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-success" type="submit">Actualizar</button>
                </div>
            </form>
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
        $('#addReservationForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            // Serialize the form data
            var formData = $(this).serialize();
            var submitButton = $(this).find('button[type="submit"]'); 

            // Deshabilitar el botón para evitar múltiples clics
            submitButton.prop('disabled', true);

            // Log the form data for debugging
            console.log("Data en el form:", formData);

            // Make the AJAX request
            $.ajax({
                url: '../DAL/reservationsHotel.php', 
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Log the server response for debugging
                    console.log("Response from server:", response);

                    // Check if the response indicates success
                    if (response.includes("success")) {
                        alert("Reservacion agregada correctamente."); // Notify the user
                        $('#modalAdd3').modal('hide'); // Close the modal
                        location.reload(); // Reload the page to reflect the new data
                    } else {
                        // Display an error message from the server
                        alert("Error al agregar la reservacion: " + response);
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
