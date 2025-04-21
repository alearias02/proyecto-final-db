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
require_once "../DAL/address.php";

$customer_id = $_SESSION['usuario']['customer_id'] ?? null;
if (!$customer_id) {
    die("Error: Falta el ID del cliente.");
}

$user_name = $_SESSION['usuario']['user_name']; 

// Lógica de paginación
$addressPage = isset($_GET['addressPage']) ? (int)$_GET['addressPage'] : 1; // Página actual (por defecto 1)
$items_per_page = 10; // Número de filas por página
$addressOffset = ($addressPage - 1) * $items_per_page;

// Conexión
$connection = conectar();
if (!$connection) {
    die("Error de conexión: " . oci_error());
}

// Consulta paginada
$addressQuery = "SELECT * FROM (
              SELECT a.*, ROWNUM rnum FROM (
                  SELECT ad.ADDRESS_ID, ad.ADDRESS, ad.ZIP_CODE, s.DESCRIPTION, co.NAME AS COUNTRY, s.NAME AS STATE, c.NAME AS CITY
                    FROM FIDE_SAMDESIGN.FIDE_ADDRESS_TB ad
                    LEFT JOIN FIDE_SAMDESIGN.FIDE_COUNTRIES_TB co ON co.COUNTRY_ID = ad.ID_COUNTRY
                    LEFT JOIN FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB s ON s.STATE_ID = ad.ID_STATE
                    LEFT JOIN FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB c ON c.CITY_ID = ad.ID_CITY
                    LEFT JOIN FIDE_SAMDESIGN.FIDE_STATUS_TB s ON s.STATUS_ID = ad.STATUS_ID
                    WHERE ad.ID_CUSTOMER = :customer_id AND ad.STATUS_ID IN (1, 10, 11)
                    ORDER BY ad.ADDRESS_ID ASC
              ) a WHERE ROWNUM <= :max_row
          ) WHERE rnum > :min_row";


$statement = oci_parse($connection, $addressQuery);
if (!$statement) {
    die("Error en la preparación de la consulta: " . oci_error($connection));
}

$max_row = $addressOffset + $items_per_page;
$min_row = $addressOffset;
oci_bind_by_name($statement, ':max_row', $max_row);
oci_bind_by_name($statement, ':min_row', $min_row);
oci_bind_by_name($statement, ":customer_id", $customer_id);
oci_execute($statement);

// Obtener las reservaciones
$oAddys = [];
while ($row = oci_fetch_assoc($statement)) {
    $oAddys[] = $row;
}

// Consulta total para la paginación
$total_count_query = "SELECT COUNT(*) AS total FROM FIDE_SAMDESIGN.FIDE_ADDRESS_TB";
$total_stmt = oci_parse($connection, $total_count_query);
oci_execute($total_stmt);
$total_row = oci_fetch_assoc($total_stmt);
$total_items = $total_row['TOTAL'];
$address_total_pages = ceil($total_items / $items_per_page);

// Liberar recursos
oci_free_statement($statement);
oci_free_statement($total_stmt);
oci_close($connection);

// Mostrar advertencia si no hay datos
if (!is_array($oAddys) || empty($oAddys)) {
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
                <h3><i class="fa-solid fa-map-location-dot"></i> Listado de Direcciones</h3>
                <span>Bienvenido,<strong> <?= htmlspecialchars($user_name); ?></strong>,</span>
                <p>sus direcciones a la fecha: <strong><?= date("d/m/Y"); ?></strong>.</p>
            </div>

            <!-- Sección derecha -->
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="text-center">Direcciones</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAdd">+</button>
                    </div>
                    <div class="card-body cardAdmin">
                        <div class="table-responsive">
                            <table class="table table-striped" id="addressTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th># Direccion</th>
                                        <th>Señas</th>
                                        <th>Canton:</th>
                                        <th>Provincia</th>
                                        <th>Pais</th>
                                        <th>ZIP Code</th>
                                        <th>ESTADO</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($oAddys)): ?>
                                        <?php foreach ($oAddys as $value): ?>
                                            <tr>
                                                <td><?= !empty($value['ADDRESS_ID']) ? $value['ADDRESS_ID'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['ADDRESS']) ? $value['ADDRESS'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['CITY']) ? $value['CITY'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['STATE']) ? $value['STATE'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['COUNTRY']) ? $value['COUNTRY'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['ZIP_CODE']) ? $value['ZIP_CODE'] : 'N/A'; ?></td>
                                                <td><?= !empty($value['DESCRIPTION']) ? $value['DESCRIPTION'] : 'N/A'; ?></td>
                                                <td>
                                                    <button class='btn btn-danger' onclick='eliminarDireccion(<?= $value['ADDRESS_ID']; ?>, "<?= htmlspecialchars($user_name); ?>" )'>
                                                        <i class='fas fa-trash'></i> Eliminar
                                                    </button>
                                                    <a href="#" class="btn btn-success actualizarDireccion"
                                                    data-address-id="<?= $value['ADDRESS_ID']; ?>">
                                                    <i class="fas fa-pencil"></i> Actualizar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No hay registros en el address.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $address_total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $addressPage) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?addressPage=<?= $i; ?>"><?= $i; ?></a>
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
$countries = fetchAll($connection, "SELECT COUNTRY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_COUNTRIES_TB");
$provincias = fetchAll($connection, "SELECT STATE_ID, NAME FROM FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB");
$cantones = fetchAll($connection, "SELECT CITY_ID, NAME FROM FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB");

// Cierra la conexión después de obtener los datos
oci_close($connection);

?>

<!-- Offcanvas para agregar un address -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd" aria-labelledby="offcanvasAddLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasAddLabel3">Agregar una direccion</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <form id="addAddressForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="action" value="insertar">
        <input type="hidden" name="ID_CUSTOMER" value="<?= htmlspecialchars($customer_id); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>

            <div class="mb-3">
                    <label for="ADDRESS">Señas</label>
                    <textarea class="form-control mt-2" name="ADDRESS" id="ADDRESS" rows="1" required></textarea>
            </div>

            <label for="CITY_ID">Canton:</label>
            <select class="form-control mt-2" name="CITY_ID" id="CITY_ID" required>
                <option value="" disabled selected>Seleccione un canton</option>
                    <?php foreach ($cantones as $canton): ?>
                        <option value="<?= $canton['CITY_ID']; ?>" 
                            <?= $canton['CITY_ID'] == 1 ? 'selected' : '' ?>>
                            <?= htmlspecialchars($canton['NAME']); ?>
                        </option>
                    <?php endforeach;?>
            </select>

            <label for="STATE_ID">Provincia:</label>
            <select class="form-control mt-2" name="STATE_ID" id="STATE_ID" required>
                <option value="" disabled selected>Seleccione una provincia</option>
                    <?php foreach ($provincias as $provincia): ?>
                        <option value="<?= $provincia['STATE_ID']; ?>" 
                            <?= $provincia['STATE_ID'] == 1 ? 'selected' : '' ?>>
                            <?= htmlspecialchars($provincia['NAME']); ?>
                        </option>
                    <?php endforeach;?>
            </select>

            <label for="COUNTRY_ID">Pais:</label>
            <select class="form-control mt-2" name="COUNTRY_ID" id="COUNTRY_ID" required>
                <option value="" disabled selected>Seleccione un pais</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?= $country['COUNTRY_ID']; ?>" 
                            <?= $country['COUNTRY_ID'] == 1 ? 'selected' : '' ?>>
                            <?= htmlspecialchars($country['NAME']); ?>
                        </option>
                    <?php endforeach;?>
            </select>

            <div class="mb-3">
                    <label for="COUNTRY_ID">Codigo postal:</label>
                    <textarea class="form-control mt-2" name="ZIP_CODE" id="ZIP_CODE" rows="1" required></textarea>
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
                <button type="submit" class="btn btn-primary">+ Agregar...</button>
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


<!-- Offcanvas para actualizar un address -->
<div class="offcanvas offcanvas-end" id="offcanvasUpdate" aria-labelledby="offcanvasUpdateLabel">
    <div class="offcanvas-header text-white" style="background-color: #475A68;">
        <h5 id="offcanvasUpdateLabel">Actualizar Dirección</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <form id="updateAddressForm" class="was-validated h-100 d-flex flex-column" enctype="multipart/form-data">
        <input type="hidden" name="action" value="actualizar">
        <input type="hidden" name="modified_by" value="<?= htmlspecialchars($user_name); ?>">

        <div class="offcanvas-body flex-grow-1 d-flex flex-column justify-content-between" style="background-color: #eee;">
            <div>
                <label for="ADDRESS_ID">ID Dirección:</label>
                <input type="text" class="form-control mt-2" name="ADDRESS_ID" id="ADDRESS_ID" readonly required>

                <input type="hidden" name="ID_CUSTOMER" value="<?= htmlspecialchars($customer_id); ?>">

                <div class="mb-3">
                    <label for="ADDRESS">Señas</label>
                    <textarea class="form-control mt-2" name="ADDRESS" id="UPDATE_ADDRESS" rows="1" required></textarea>
                </div>

                <label for="CITY_ID">Cantón:</label>
                <select class="form-control mt-2" name="ID_CITY" id="UPDATE_CITY_ID" required>
                    <option value="" disabled selected>Seleccione un cantón</option>
                    <?php foreach ($cantones as $canton): ?>
                        <option value="<?= $canton['CITY_ID']; ?>"><?= htmlspecialchars($canton['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="STATE_ID">Provincia:</label>
                <select class="form-control mt-2" name="ID_STATE" id="UPDATE_STATE_ID" required>
                    <option value="" disabled selected>Seleccione una provincia</option>
                    <?php foreach ($provincias as $provincia): ?>
                        <option value="<?= $provincia['STATE_ID']; ?>"><?= htmlspecialchars($provincia['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="COUNTRY_ID">País:</label>
                <select class="form-control mt-2" name="ID_COUNTRY" id="UPDATE_COUNTRY_ID" required>
                    <option value="" disabled selected>Seleccione un país</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?= $country['COUNTRY_ID']; ?>"><?= htmlspecialchars($country['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="mb-3">
                    <label for="ZIP_CODE">Código Postal:</label>
                    <textarea class="form-control mt-2" name="ZIP_CODE" id="UPDATE_ZIP_CODE" rows="1" required></textarea>
                </div>

                <label for="STATUS_ID">Estado:</label>
                <select class="form-control mt-2" name="STATUS_ID" id="UPDATE_STATUS_ID" required>
                    <option value="" disabled selected>Seleccione un estado</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['STATUS_ID']; ?>"><?= htmlspecialchars($status['DESCRIPTION']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </form>
</div>




<!-- Incluye jQuery desde CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    // Abrir y rellenar el offcanvas de ACTUALIZAR address
    $(document).on('click', '.actualizarDireccion', function (e) {
        e.preventDefault();

        const addressID = $(this).data('address-id');

        if (!addressID) {
            console.error("No se proporcionó un ID de address.");
            return;
        }

        $.ajax({
            method: "POST",
            url: "../DAL/address.php",
            data: {
                action: "obtenerDetalles",
                address_id: addressID
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
                    $('#STATE_ID').val(String(data.State_ID));
                    $('#CITY_ID').val(String(data.City_ID));
                    $('#COUNTRY_ID').val(String(data.Country_ID));

                    $.ajax({
                        method: 'POST',
                        url: '../DAL/address.php',
                        data: {
                            action: 'getAddressesByCustomer',
                            customer_id: data.CUSTOMER_ID
                        },
                        success: function (response) {
                            try {
                                const addresses = JSON.parse(response);
                                const addressSelect = $('#UPDATE_ADDRESS_ID');
                                addressSelect.empty();
                                addressSelect.append('<option disabled>Seleccione una dirección</option>');

                                addresses.forEach(function (addr) {
                                    const selected = addr.ADDRESS_ID == data.ADDRESS_ID ? 'selected' : '';
                                    addressSelect.append(`<option value="${addr.ADDRESS_ID}" ${selected}>${addr.ADDRESS}</option>`);
                                });
                            } catch (e) {
                                console.error("Error al parsear direcciones:", e);
                            }
                        },
                        error: function () {
                            alert("No se pudieron cargar las direcciones del cliente.");
                        }
                    });

                    // Mostrar el offcanvas manualmente (por si falla el data-bs-toggle)
                    const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasUpdate'));
                    offcanvas.show();

                } catch (error) {
                    console.error("Error al parsear JSON:", error);
                    alert("Error al cargar los datos del address.");
                }
            },
            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);
                alert("No se pudieron cargar los detalles del address.");
            }
        });
    });

});


$('#CUSTOMER_ID').on('change', function () {
    const customerId = $(this).val();

    $.ajax({
        method: 'POST',
        url: '../DAL/address.php',
        data: {
            action: 'getAddressesByCustomer',
            customer_id: customerId
        },
        success: function (response) {
            try {
                const addresses = JSON.parse(response);
                const addressSelect = $('#ADDRESS_ID');
                addressSelect.empty();
                addressSelect.append('<option disabled selected>Seleccione una dirección</option>');

                addresses.forEach(function (addr) {
                    addressSelect.append(`<option value="${addr.ADDRESS_ID}">${addr.ADDRESS}</option>`);
                });
            } catch (e) {
                console.error("Error al parsear direcciones:", e);
            }
        },
        error: function () {
            alert("No se pudieron cargar las direcciones.");
        }
    });
});


//AGREGAR address
$('#addAddressForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);

        $.ajax({
            url: '../DAL/address.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response from server:", response);

                if (response.includes("success")) {
                    alert("Direccion agregado correctamente.");
                    location.reload();
                } else {
                    alert("Error al agregar el address: " + response);
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
$('#updateAddressForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        console.log("Actualizando address con datos:", formData);

        $.ajax({
            url: '../DAL/address.php',
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log("Respuesta del servidor:", response);

                if (response.includes("success")) {
                    alert("Direccion actualizado correctamente.");
                    location.reload();
                } else {
                    alert("Error al actualizar: " + response);
                }
            },
            error: function (xhr) {
                console.error("Error al actualizar:", xhr.responseText);
                alert("Hubo un error al actualizar el address.");
            },
            complete: function () {
                submitBtn.prop('disabled', false);
            }
        });
    });

// Función para eliminar un INVENTARIO
function eliminarDireccion(id, user) {
            console.log("Intentando eliminar address con ID:", id);
            if (confirm("¿Estás seguro de que deseas eliminar este address?")) {
                $.ajax({
                    method: "POST",
                    url: "../DAL/address.php",
                    data: {
                        action: "eliminar",
                        address_id: id,
                        modified_by: user
                    },
                    success: function (response) {
                        console.log("Respuesta de eliminación:", response);
                        if (response.includes("success")) {
                            alert("Direccion eliminado correctamente.");
                            location.reload(); // Refresca la página para reflejar los cambios
                        } else {
                            alert("No se pudo eliminar el Direccion: " + response);
                        }
                    },
                    error: function (xhr) {
                        console.error("Error al eliminar:", xhr.responseText);
                        alert("No se pudo eliminar el Direccion.");
                    }
                });
            }
        }
</script>

