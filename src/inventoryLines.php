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
                  SELECT i.inventory_lines_id, e.description AS INVENTARIO, p.description AS PRODUCTO, i.comments, i.quantity_stocked, i.quantity_reserved,
                   i.status_id, i.last_restocked, i.created_by, i.created_on, i.modified_on, i.modified_by
                  FROM FIDE_SAMDESIGN.FIDE_INVENTORY_LINES_TB i
                  INNER JOIN FIDE_SAMDESIGN.FIDE_INVENTORY_TB e ON e.inventory_id = i.inventory_id
                  INNER JOIN FIDE_SAMDESIGN.FIDE_PRODUCT_TB p ON p.product_id = i.product_id
                  WHERE i.inventory_id = :inventory_id AND i.status_id = 1
                  ORDER BY inventory_lines_id ASC
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
                        <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddInventoryLine">   +</button>
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
                                        <th>Ultimo restock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($oInventories)): ?>
                                        <?php foreach ($oInventories as $value): ?>
                                            <tr>
                                                <td><?= !empty($value['INVENTORY_LINES_ID']) ? $value['INVENTORY_LINES_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['INVENTARIO']) ? $value['INVENTARIO'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['PRODUCTO']) ? $value['PRODUCTO'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['COMMENTS']) ? $value['COMMENTS'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['QUANTITY_STOCKED']) ? $value['QUANTITY_STOCKED'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['QUANTITY_RESERVED']) ? $value['QUANTITY_RESERVED'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['LAST_RESTOCKED']) ? $value['LAST_RESTOCKED'] : 'N/A'; ?></td>
                                                <td>
                                                     <!-- Botón para Ver Detalles -->
                                                    <a href="inventoryLines.php?inventory_id=<?= urlencode($value['INVENTORY_LINES_ID']); ?>" class="btn btn-info" style="display: inline-block;">
                                                        <i class="fas fa-box-open"></i> Ver Detalles
                                                    </a>
                                                    <!-- Botón para eliminar -->
                                                    <button class='btn btn-danger' onclick='eliminarLineaInventario(<?= $value['INVENTORY_LINES_ID']; ?>, "<?= htmlspecialchars($user_name); ?>" )'>
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>

                                                    <!-- Botón para actualizar -->
                                                    <a href="#" class="btn btn-success actualizarLineaInventario"
                                                    data-inventory_lines-id="<?= $value['INVENTORY_LINES_ID']; ?>">
                                                        <i class="fas fa-pencil"></i> Actualizar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No hay registros en el inventario.</td>
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
$products = fetchAll($connection, "SELECT product_id, description FROM fide_samdesign.fide_product_tb where status_id = 1");
$inventarios = fetchAll($connection, "SELECT inventory_id, description FROM fide_samdesign.fide_inventory_tb where status_id = 1");
$statuses = fetchAll($connection, "SELECT STATUS_ID, DESCRIPTION FROM FIDE_SAMDESIGN.FIDE_STATUS_TB");



oci_close($connection);
?>



<!-- Offcanvas para agregar un producto al inventario -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddInventoryLine" aria-labelledby="offcanvasAddInventoryLineLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasAddInventoryLineLabel">Agregar un Producto al Inventario <?= $inventory_name; ?></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <form id="addInventoryLineForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="action" value="insertar">
        <input type="hidden" name="inventory_id" id="inventory_id" value="<?= $inventory_id; ?>">
        <input type="hidden" name="created_by" value="<?= htmlspecialchars($user_name); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>
                <!-- Producto -->
                <div class="mb-3">
                    <label for="PRODUCT_ID">Producto:</label>
                    <select class="form-control mt-2" name="PRODUCT_ID" id="PRODUCT_ID" required>
                        <option value="" disabled selected>Seleccione un producto</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['PRODUCT_ID']; ?>">
                                <?= htmlspecialchars($product['DESCRIPTION']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Stock -->
                <div class="mb-3">
                    <label for="quantity_stocked">Stock total:</label>
                    <input type="number" class="form-control mt-2" name="quantity_stocked" id="quantity_stocked" required />
                </div>

                <!-- Comentarios -->
                <div class="mb-3">
                    <label for="comments">Comentarios:</label>
                    <textarea class="form-control mt-2" name="comments" id="comments" rows="2" required></textarea>
                </div>
            </div>

            <!-- Botón de Crear -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Crear</button>
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

<!-- Offcanvas para actualizar lineas inventario -->
<div class="offcanvas offcanvas-end" id="offcanvasUpdate" aria-labelledby="offcanvasUpdateLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasUpdateLabel">Actualizar el inventario</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <form id="updateInventoryLinesForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="inventory_lines_id" id="inventory_lines_id">
        <input type="hidden" name="action" value="actualizar">
        <input type="hidden" name="modified_by" value="<?= htmlspecialchars($user_name); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>
                <!-- inventario -->
                <div class="mb-3">
                    <label for="INVENTORY_ID">Inventario:</label>
                    <select class="form-control mt-2" name="INVENTORY_ID" id="INVENTORY_SELECT_ID" required>
                        <option value="" disabled selected>Seleccione un inventario</option>
                        <?php foreach ($inventarios as $inventario): ?>
                            <option value="<?= $inventario['INVENTORY_ID']; ?>">
                                <?= htmlspecialchars($inventario['DESCRIPTION']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <!-- Producto -->
                <div class="mb-3">
                    <label for="PRODUCT_ID">Producto:</label>
                    <select class="form-control mt-2" name="PRODUCT_ID" id="PRODUCT_ID" required>
                        <option value="" disabled selected>Seleccione un producto</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['PRODUCT_ID']; ?>" selected>
                                <?= htmlspecialchars($product['DESCRIPTION']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                            
                 <!-- Stock -->
                 <div class="mb-3">
                    <label for="QUANTITY_STOCKED">Stock total:</label>
                    <input type="number" class="form-control mt-2" name="QUANTITY_STOCKED" id="QUANTITY_STOCKED" required />
                </div>

                 <!-- Stock Reservado -->
                 <div class="mb-3">
                    <label for="QUANTITY_RESERVED">Stock en RESERVA:</label>
                    <input type="number" class="form-control mt-2" name="QUANTITY_RESERVED" id="QUANTITY_RESERVED" required />
                </div>

                <!-- Comentarios -->
                <div class="mb-3">
                    <label for="COMMENTS">Comentarios:</label>
                    <textarea class="form-control mt-2" name="COMMENTS" id="COMMENTS" rows="2" required></textarea>
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

//AGREGAR inventario
$('#addInventoryLineForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);

        $.ajax({
            url: '../DAL/inventoryLines.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response from server:", response);

                if (response.includes("success")) {
                    alert("Linea de Inventario agregado correctamente.");
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

    $(document).ready(function () {

// Abrir y rellenar el offcanvas de ACTUALIZAR linea inventario
$(document).on('click', '.actualizarLineaInventario', function (e) {
    e.preventDefault();

    const inventoryLinesID = $(this).data('inventory_lines-id');
    const inventoryID = $('#INVENTORY_SELECT_ID').val();
    
    if (!inventoryLinesID) {
        console.error("No se proporcionó un ID de inventario.");
        return;
    }
    const productID = $('#PRODUCT_ID').val();
    
    $('#inventory_lines_id').val(inventoryLinesID); 

    $.ajax({
        method: "POST",
        url: "../DAL/inventoryLines.php",
        data: {
            action: "obtenerDetalles",
            inventory_lines_id: inventoryLinesID
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


//Enviar el formulario de ACTUALIZACIÓN
$('#updateInventoryLinesForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        console.log("Actualizando inventario con datos:", formData);

        $.ajax({
            url: '../DAL/inventoryLines.php',
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


// Función para eliminar una linea inventario
function eliminarLineaInventario(id, user) {
            console.log("Intentando eliminar linea de inventario con ID:", id);
            if (confirm("¿Estás seguro de que deseas eliminar este producto del inventario?")) {
                $.ajax({
                    method: "POST",
                    url: "../DAL/inventoryLines.php",
                    data: {
                        action: "eliminar",
                        inventory_lines_id: id,
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
