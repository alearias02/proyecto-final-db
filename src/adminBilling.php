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
require_once "../DAL/billing.php";

$user_name = $_SESSION['usuario']['user_name']; 

// Lógica de paginación
$billingPage = isset($_GET['billingPage']) ? (int)$_GET['billingPage'] : 1; // Página actual (por defecto 1)
$items_per_page = 10; // Número de filas por página
$billingOffset = ($billingPage - 1) * $items_per_page;

// Conexión
$connection = conectar();
if (!$connection) {
    die("Error de conexión: " . oci_error());
}

// Consulta paginada
$billingQuery = "SELECT * FROM (
              SELECT a.*, ROWNUM rnum FROM (
                  SELECT b.billing_id, b.order_id, c.CUSTOMER_NAME, a.ADDRESS, TRUNC(b.BILLING_DATE) as  BILLING_DATE, b.total_amount, b.comments, s.description as STATUS, b.payment_method_id,
                         b.created_by, b.created_on, b.modified_on, b.modified_by
                  FROM FIDE_SAMDESIGN.FIDE_BILLING_TB b
                  LEFT JOIN FIDE_SAMDESIGN.FIDE_CUSTOMER_TB c ON c.CUSTOMER_ID = b.CUSTOMER_ID
                  LEFT JOIN FIDE_SAMDESIGN.FIDE_ADDRESS_TB a ON a.ID_CUSTOMER = b.CUSTOMER_ID
                  LEFT JOIN FIDE_SAMDESIGN.FIDE_STATUS_TB s ON s.status_id = b.status_id
                  WHERE b.status_id = 1
              ) a WHERE ROWNUM <= :max_row
          ) WHERE rnum > :min_row";

$statement = oci_parse($connection, $billingQuery);
if (!$statement) {
    die("Error en la preparación de la consulta: " . oci_error($connection));
}

$max_row = $billingOffset + $items_per_page;
$min_row = $billingOffset;
oci_bind_by_name($statement, ':max_row', $max_row);
oci_bind_by_name($statement, ':min_row', $min_row);
oci_execute($statement);

// Obtener las reservaciones
$oBills = [];
while ($row = oci_fetch_assoc($statement)) {
    $oBills[] = $row;
}

// Consulta total para la paginación
$total_count_query = "SELECT COUNT(*) AS total FROM FIDE_SAMDESIGN.FIDE_BILLING_TB";
$total_stmt = oci_parse($connection, $total_count_query);
oci_execute($total_stmt);
$total_row = oci_fetch_assoc($total_stmt);
$total_items = $total_row['TOTAL'];
$billing_total_pages = ceil($total_items / $items_per_page);

// Liberar recursos
oci_free_statement($statement);
oci_free_statement($total_stmt);
oci_close($connection);

// Mostrar advertencia si no hay datos
if (!is_array($oBills) || empty($oBills)) {
    // echo "<div class='container mt-4'>
    //         <div class='alert alert-warning' role='alert'>
    //             No hay registros disponibles.
    //         </div>
    //       </div>";
}
?>

<div class="d-flex justify-content-center mt-5 vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Sección izquierda -->
            <div class="col-md-3 text-center">
                <h3>🧾 Listado de Facturas</h3>
                <span><strong>Bienvenido,</strong> <?= htmlspecialchars($user_name); ?></span>
                <p>FACTURAS A LA FECHA: <strong><?= date("d/m/Y"); ?></strong>.</p>
            </div>

            <!-- Sección derecha -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="text-center">Facturas</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAdd">+</button>
                    </div>
                    <div class="card-body cardAdmin">
                        <div class="table-responsive">
                            <table class="table table-striped" id="billingTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th># Factura</th>
                                        <th># Orden</th>
                                        <th>A nombre de:</th>
                                        <th>Direccion</th>
                                        <th>Fecha</th>
                                        <th>Monto $</th>
                                        <th>Comentarios</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($oBills)): ?>
                                        <?php foreach ($oBills as $value): ?>
                                            <tr>
                                                <td><?= !empty($value['BILLING_ID']) ? $value['BILLING_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['ORDER_ID']) ? $value['ORDER_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['CUSTOMER_NAME']) ? $value['CUSTOMER_NAME'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['ADDRESS']) ? $value['ADDRESS'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['BILLING_DATE']) ? $value['BILLING_DATE'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['TOTAL_AMOUNT']) ? $value['TOTAL_AMOUNT'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['COMMENTS']) ? $value['COMMENTS'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['STATUS']) ? $value['STATUS'] : 'N/A'; ?></td>
                                                <td>
                                                    <!-- Botón para Ver Detalles -->
                                                    <a href="billingLines.php?billing_id=<?= urlencode($value['BILLING_ID']); ?>" class="btn btn-info" style="display: inline-block;">
                                                        <i class="fas fa-box-open"></i> Ver Detalles
                                                    </a>

                                                    <button class='btn btn-danger' onclick='eliminarFactura(<?= $value['BILLING_ID']; ?>, "<?= htmlspecialchars($user_name); ?>" )'>
                                                        <i class='fas fa-trash'></i> Eliminar
                                                    </button>
                                                    <a href="#" class="btn btn-success actualizarFactura"
                                                    data-billing-id="<?= $value['BILLING_ID']; ?>">
                                                    <i class="fas fa-pencil"></i> Actualizar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No hay registros en el billing.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $billing_total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $billingPage) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?billingPage=<?= $i; ?>"><?= $i; ?></a>
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
require_once "../DAL/conexion.php";
require_once "../DAL/database.php"; // Incluye la conexión a la base de datos

// Conectar a la base de datos
$connection = conectar();
// Consultas
$statuses = fetchAll($connection, "SELECT STATUS_ID, DESCRIPTION FROM FIDE_SAMDESIGN.FIDE_STATUS_TB");
// Cierra la conexión después de obtener los datos
oci_close($connection);

?>

<!-- Offcanvas para agregar un billing -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd" aria-labelledby="offcanvasAddLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasAddLabel3">Agregar un billing</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <form id="addInventoryForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="action" value="insertar">
        <input type="hidden" name="created_by" value="<?= htmlspecialchars($user_name); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>
                <!-- description -->
                <div class="mb-3">
                    <label for="description">Nombre del billing</label>
                    <textarea class="form-control mt-2" name="description" id="description" rows="1" required></textarea>
                </div>
            <label for="STATUS_ID">Estado:</label>
            <select class="form-control mt-2" name="STATUS_ID" id="STATUS_ID" required>
                <option value="" disabled selected>Seleccione un estado</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['STATUS_ID']; ?>" 
                            <?= $status['STATUS_ID'] == 1 ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['DESCRIPTION']); ?>
                        </option>
                    <?php endforeach; ?>
            </select>
            </div>


            <div class="text-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </form>
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

<!-- Offcanvas para actualizar un billing -->
<div class="offcanvas offcanvas-end" id="offcanvasUpdate" aria-labelledby="offcanvasUpdateLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasUpdateLabel">Actualizar el billing</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <form id="updateInventoryForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="BILLING_ID" id="BILLING_ID">
        <input type="hidden" name="action" value="actualizar">
        <input type="hidden" name="modified_by" value="<?= htmlspecialchars($user_name); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>
                <div class="mb-3">
                    <label for="description">Nombre del billing</label>
                    <textarea class="form-control mt-2" name="DESCRIPTION" id="update_description" rows="1" required></textarea>
                </div>
                <label for="STATUS_ID">Estado:</label>
                <select class="form-control mt-2" name="STATUS_ID" id="STATUS_ID" required>
                    <option value="" disabled selected>Seleccione un estado</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['STATUS_ID']; ?>" selected><?= htmlspecialchars($status['DESCRIPTION']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>           

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </form>
</div>


<!-- Incluye jQuery desde CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    // Abrir y rellenar el offcanvas de ACTUALIZAR billing
    $(document).on('click', '.actualizarFactura', function (e) {
        e.preventDefault();

        const billingID = $(this).data('billing-id');

        if (!billingID) {
            console.error("No se proporcionó un ID de billing.");
            return;
        }

        $.ajax({
            method: "POST",
            url: "../DAL/billing.php",
            data: {
                action: "obtenerDetalles",
                billing_id: billingID
            },
            success: function (response) {
                try {
                    const data = JSON.parse(response);
                    console.log("Datos recibidos:", data);

                    // Llenar campos del formulario de actualización
                    $.each(data, function (key, value) {
                        const field = $(`#offcanvasUpdate [name="${key}"]`);
                        if (field.length > 0) {
                            field.val(value);
                        }
                    });
                    // Forzar asignación correcta a los selects específicos
                    $('#STATUS_ID').val(String(data.Status_ID));

                    // Mostrar el offcanvas manualmente (por si falla el data-bs-toggle)
                    const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasUpdate'));
                    offcanvas.show();

                } catch (error) {
                    console.error("Error al parsear JSON:", error);
                    alert("Error al cargar los datos del billing.");
                }
            },
            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);
                alert("No se pudieron cargar los detalles del billing.");
            }
        });
    });
});

//AGREGAR billing
$('#addInventoryForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);

        $.ajax({
            url: '../DAL/billing.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response from server:", response);

                if (response.includes("success")) {
                    alert("Factura agregado correctamente.");
                    location.reload();
                } else {
                    alert("Error al agregar el billing: " + response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error al enviar el formulario:", error);
                alert("Hubo un problema. Intenta de nuevo.");
            },
            complete: function () {
                submitButton.prop('disabled', false);
            }
        });
    });

//Enviar el formulario de ACTUALIZACIÓN
$('#updateInventoryForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        console.log("Actualizando billing con datos:", formData);

        $.ajax({
            url: '../DAL/billing.php',
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log("Respuesta del servidor:", response);

                if (response.includes("success")) {
                    alert("Factura actualizado correctamente.");
                    location.reload();
                } else {
                    alert("Error al actualizar: " + response);
                }
            },
            error: function (xhr) {
                console.error("Error al actualizar:", xhr.responseText);
                alert("Hubo un error al actualizar el billing.");
            },
            complete: function () {
                submitBtn.prop('disabled', false);
            }
        });
    });

// Función para eliminar un INVENTARIO
function eliminarFactura(id, user) {
            console.log("Intentando eliminar billing con ID:", id);
            if (confirm("¿Estás seguro de que deseas eliminar este billing?")) {
                $.ajax({
                    method: "POST",
                    url: "../DAL/billing.php",
                    data: {
                        action: "eliminar",
                        billing_id: id,
                        modified_by: user
                    },
                    success: function (response) {
                        console.log("Respuesta de eliminación:", response);
                        if (response.includes("success")) {
                            alert("Factura eliminado correctamente.");
                            location.reload(); // Refresca la página para reflejar los cambios
                        } else {
                            alert("No se pudo eliminar el Factura: " + response);
                        }
                    },
                    error: function (xhr) {
                        console.error("Error al eliminar:", xhr.responseText);
                        alert("No se pudo eliminar el Factura.");
                    }
                });
            }
        }
</script>

