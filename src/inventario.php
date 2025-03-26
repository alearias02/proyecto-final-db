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
require_once "../DAL/inventario.php";

$user_name = $_SESSION['usuario']['user_name']; 

// Lógica de paginación
$inventoryPage = isset($_GET['inventoryPage']) ? (int)$_GET['inventoryPage'] : 1; // Página actual (por defecto 1)
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
                  SELECT inventory_id, description, status_id,
                         created_by, created_on, modified_on, modified_by
                  FROM FIDE_SAMDESIGN.FIDE_INVENTORY_TB 
                  WHERE status_id = 1
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
                <h3>📦 Inventario de Productos</h3>
                <span><strong>Bienvenido,</strong> <?= htmlspecialchars($user_name); ?></span>
                <p>INVENTARIO A LA FECHA: <strong><?= date("d/m/Y"); ?></strong>.</p>
            </div>

            <!-- Sección derecha -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="text-center">Inventario</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAdd">+</button>
                    </div>
                    <div class="card-body cardAdmin">
                        <div class="table-responsive">
                            <table class="table table-striped" id="inventoryTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <a href=""><th>Nombre</th></a>
                                        <th>Ultimo restock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($oInventories)): ?>
                                        <?php foreach ($oInventories as $value): ?>
                                            <tr>
                                                <td><?= !empty($value['INVENTORY_ID']) ? $value['INVENTORY_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['DESCRIPTION']) ? $value['DESCRIPTION'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['LAST_RESTOCK']) ? $value['LAST_RESTOCK'] : 'N/A'; ?></td>
                                                <td>
                                                    <!-- Botón para Ver Detalles -->
                                                    <a href="inventoryLines.php?inventory_id=<?= urlencode($value['INVENTORY_ID']); ?>" class="btn btn-info" style="display: inline-block;">
                                                        <i class="fas fa-box-open"></i> Ver Detalles
                                                    </a>

                                                    <button class='btn btn-danger' onclick='eliminarInventario(<?= $value['INVENTORY_ID']; ?>, "<?= htmlspecialchars($user_name); ?>" )'>
                                                        <i class='fas fa-trash'></i> Eliminar
                                                    </button>
                                                    <a href="#" class="btn btn-success actualizarInventario"
                                                    data-inventory-id="<?= $value['INVENTORY_ID']; ?>">
                                                    <i class="fas fa-pencil"></i> Actualizar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No hay registros en el inventario.</td>
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

<!-- Offcanvas para agregar un inventario -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd" aria-labelledby="offcanvasAddLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasAddLabel3">Agregar un inventario</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <form id="addInventoryForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="action" value="insertar">
        <input type="hidden" name="created_by" value="<?= htmlspecialchars($user_name); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>
                <!-- description -->
                <div class="mb-3">
                    <label for="description">Nombre del inventario</label>
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

<!-- Offcanvas para actualizar un inventario -->
<div class="offcanvas offcanvas-end" id="offcanvasUpdate" aria-labelledby="offcanvasUpdateLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasUpdateLabel">Actualizar el inventario</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <form id="updateInventoryForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="INVENTORY_ID" id="INVENTORY_ID">
        <input type="hidden" name="action" value="actualizar">
        <input type="hidden" name="modified_by" value="<?= htmlspecialchars($user_name); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>
                <div class="mb-3">
                    <label for="description">Nombre del inventario</label>
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

    // Abrir y rellenar el offcanvas de ACTUALIZAR inventario
    $(document).on('click', '.actualizarInventario', function (e) {
        e.preventDefault();

        const inventoryID = $(this).data('inventory-id');

        if (!inventoryID) {
            console.error("No se proporcionó un ID de inventario.");
            return;
        }

        $.ajax({
            method: "POST",
            url: "../DAL/inventario.php",
            data: {
                action: "obtenerDetalles",
                inventory_id: inventoryID
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
                    alert("Error al cargar los datos del inventario.");
                }
            },
            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);
                alert("No se pudieron cargar los detalles del inventario.");
            }
        });
    });
});

//AGREGAR inventario
$('#addInventoryForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);

        $.ajax({
            url: '../DAL/inventario.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response from server:", response);

                if (response.includes("success")) {
                    alert("Inventario agregado correctamente.");
                    location.reload();
                } else {
                    alert("Error al agregar el inventario: " + response);
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
        console.log("Actualizando inventario con datos:", formData);

        $.ajax({
            url: '../DAL/inventario.php',
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log("Respuesta del servidor:", response);

                if (response.includes("success")) {
                    alert("Inventario actualizado correctamente.");
                    location.reload();
                } else {
                    alert("Error al actualizar: " + response);
                }
            },
            error: function (xhr) {
                console.error("Error al actualizar:", xhr.responseText);
                alert("Hubo un error al actualizar el inventario.");
            },
            complete: function () {
                submitBtn.prop('disabled', false);
            }
        });
    });

// Función para eliminar un INVENTARIO
function eliminarInventario(id, user) {
            console.log("Intentando eliminar inventario con ID:", id);
            if (confirm("¿Estás seguro de que deseas eliminar este inventario?")) {
                $.ajax({
                    method: "POST",
                    url: "../DAL/inventario.php",
                    data: {
                        action: "eliminar",
                        inventory_id: id,
                        modified_by: user
                    },
                    success: function (response) {
                        console.log("Respuesta de eliminación:", response);
                        if (response.includes("success")) {
                            alert("Inventario eliminado correctamente.");
                            location.reload(); // Refresca la página para reflejar los cambios
                        } else {
                            alert("No se pudo eliminar el Inventario: " + response);
                        }
                    },
                    error: function (xhr) {
                        console.error("Error al eliminar:", xhr.responseText);
                        alert("No se pudo eliminar el Inventario.");
                    }
                });
            }
        }
</script>

