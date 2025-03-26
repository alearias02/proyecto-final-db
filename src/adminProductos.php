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
                  SELECT Product_ID, Description, Comments, Unit_price, Image_path, Status_ID
                  FROM FIDE_SAMDESIGN.FIDE_PRODUCT_TB
                  WHERE Status_ID = 1
                  ORDER BY Product_ID asc
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
                                            <td class='text-end'>$<?= number_format($producto['UNIT_PRICE'], 2); ?></td>
                                            <td class='text-center'><img src='<?= htmlspecialchars($producto['IMAGE_PATH']); ?>' style='width:40px; height:40px' alt='Imagen Producto'></td>
                                            <td>
                                            <button class='btn btn-danger' onclick='eliminarProducto(<?= $producto['PRODUCT_ID']; ?>, "<?= htmlspecialchars($user_name); ?>" )'>
                                                <i class='fas fa-trash'></i> Eliminar
                                            </button>
                                                <a href="#" class="btn btn-success modalUpdate" data-bs-toggle="modal" data-product_id="<?= $producto['PRODUCT_ID']; ?>" data-bs-target="#modalUpdate">
                                                    <i class="fas fa-pencil"></i> Actualizar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan='6' class='text-center'>No hay productos disponibles</td>
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
</body>

<?php
require_once "../DAL/conexion.php";
require_once "../DAL/database.php"; // Incluye la conexión a la base de datos

// Conectar a la base de datos
$connection = conectar();
// Consultas
$categories = fetchAll($connection, "SELECT category_id, description FROM fide_samdesign.fide_category_type_tb");
$statuses = fetchAll($connection, "SELECT STATUS_ID, DESCRIPTION FROM FIDE_SAMDESIGN.FIDE_STATUS_TB");
// Cierra la conexión después de obtener los datos
oci_close($connection);

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

<div id="modalUpdate" class="modal fade" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #475A68;">
                <h5 class="modal-title mx-auto">Actualizar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateProduct" method="POST" class="was-validated" enctype="multipart/form-data">
                <div class="modal-body text-center" style="background-color: #eee;">
                    <input type="hidden" name="action" value="actualizar">
                    <input type="hidden" name="modified_by" value="<?= htmlspecialchars($user_name); ?>">
                    <input type="hidden" name="Product_ID" id="Product_ID">

                    <div class="mb-3">
                        <label for="Description">Descripción</label>
                        <input type="text" class="form-control mt-2" name="Description" id="Description" required />
                    </div>

                    <div class="mb-3">
                        <label for="Comments">Comentarios</label>
                        <input type="text" class="form-control mt-2" name="Comments" id="Comments" required />
                    </div>

                    <div class="mb-3">
                        <label for="Unit_price">Precio Unitario</label>
                        <input type="number" step="0.01" class="form-control mt-2" name="Unit_price" id="Unit_price" required />
                    </div>

                    <div class="mb-3">
                        <label for="category_type_id">Categoria del producto</label>
                        <select class="form-control mt-2" name="category_type_id" id="category_type_id" required>
                            <option value="" disabled selected>Seleccione una categoria</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['CATEGORY_ID']; ?>"><?= htmlspecialchars($category['DESCRIPTION']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <label for="STATUS_ID">Estado:</label>
                    <select class="form-control mt-2" name="STATUS_ID" id="STATUS_ID" required>
                        <option value="" disabled selected>Seleccione un estado</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['STATUS_ID']; ?>" selected><?= htmlspecialchars($status['DESCRIPTION']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <div class="mb-3">
                        <label for="Image_path">Imagen del Producto</label>
                        <input class="form-control mb-3" type="file" name="Image_path" id="Image_path" onchange="readURL(this);">
                        <img id="imagenDisplay" src="#" alt="Imagen Producto" height="200" style="display:none;" />
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
    $(document).on('click', '.modalUpdate', function (e) {
        e.preventDefault();
        const product_id = $(this).data('product_id');

        $.ajax({
            method: "POST",
            url: "../DAL/productos.php",
            data: {
                action: "obtenerDetalles",
                product_id: product_id
            },
            success: function (response) {
                try {
                    const data = JSON.parse(response);
                    console.log("Datos recibidos:", data);

                    // Llena inputs normales (excluyendo el input tipo file)
                    $.each(data, function (key, value) {
                        if (key !== "Image_path") { // EXCLUYE explícitamente la imagen
                            const field = $(`#modalUpdate [name="${key}"]`);
                            if (field.length > 0) {
                                field.val(value);
                            }
                        }
                    });

                    // Forzar asignación correcta a los selects específicos
                    $('#STATUS_ID').val(String(data.Status_ID));
                    $('#modalUpdate select[name="category_type_id"]').val(String(data.Category_Type_ID));

                    // Asignar imagen correctamente (solo al elemento img, no al input file)
                    if (data.Image_path) {
                        $('#imagenDisplay').attr('src', data.Image_path).show();
                    } else {
                        $('#imagenDisplay').hide();
                    }

                    $('#modalUpdate').modal('show');

                } catch (error) {
                    console.error("Error JSON:", error);
                    alert("Ocurrió un error al cargar detalles del producto.");
                }
            },
            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);
                alert("No se pudieron cargar los detalles del producto.");
            }
        });
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

    // Maneja el envío del formulario para actualizar un servicio
    $('#formUpdateProduct').on('submit', function (e) {
    e.preventDefault(); // Evita que el formulario recargue la página por defecto

    var formData = new FormData(this);    // Datos del formulario
    var submitButtonUpdate = $(this).find('button[type="submit"]');

    // Deshabilitar el botón para evitar múltiples clics
    submitButtonUpdate.prop('disabled', true);
    console.log("Actualizando productos con datos:", formData);

    // Validar que todos los campos requeridos estén presentes
    if (!formData.get('Product_ID') || 
        !formData.get('Description') || 
        !formData.get('Unit_price') || 
        !formData.get('category_type_id') ||
        !formData.get('STATUS_ID')) {
        console.error("Formulario incompleto. Datos enviados:", formData);
        showToast("Error", "Formulario incompleto. Por favor, revisa los campos.", "error");
        submitButtonUpdate.prop('disabled', false);
        return;
    }

    // Realiza la solicitud AJAX
    $.ajax({
        method: "POST",
        url: "../DAL/productos.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log("Respuesta del servidor (completa):", JSON.stringify(response));//log

            if (response.includes("success")) {
                showToast("Éxito", response, "success");
                $('#modalUpdate').modal('hide');
                location.reload(); // Recargar página
            } else {
                showToast("Error", "La actualización falló. Intenta nuevamente.", "error");
                submitButtonUpdate.prop('disabled', false);
            }
        },
        error: function (xhr) {
            console.error("Error al actualizar:", xhr.responseText);
            showToast("Error", "No se pudo actualizar el producto. Intenta de nuevo.", "error");
            $('#modalUpdate').modal('show');
            submitButtonUpdate.prop('disabled', false); // Habilitar botón para otro intento
        }
    });
});


  // Handle form submission for adding a new service
  $('#addProductForm').on('submit', function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    var submitButton = $(this).find('button[type="submit"]'); 
    submitButton.prop('disabled', true);

    $.ajax({
        url: '../DAL/productos.php', 
        type: 'POST',
        data: formData,
        processData: false,  
        contentType: false, 
        success: function (response) {
            console.log("Respuesta del servidor:", response);

            if (response.trim() === "success") {
                alert("Producto agregado correctamente.");
                $('#modalAddProduct').modal('hide'); 
                location.reload();
            } else {
                alert("Error al agregar el producto: " + response);
                submitButton.prop('disabled', false);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Hubo un problema al enviar el formulario. Inténtalo de nuevo.");
            submitButton.prop('disabled', false);
        }
    });
});


// Función para eliminar un PRODUCTO
function eliminarProducto(id, user) {
        console.log("Intentando eliminar producto con ID:", id);
        if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
            $.ajax({
                method: "POST",
                url: "../DAL/productos.php",
                data: {
                    action: "eliminar",
                    product_id: id,
                    modified_by: user
                },
                success: function (response) {
                    console.log("Respuesta de eliminación:", response);
                    if (response.includes("success")) {
                        alert("Producto eliminado correctamente.");
                        location.reload(); // Refresca la página para reflejar los cambios
                    } else {
                        alert("No se pudo eliminar el producto: " + response);
                    }
                },
                error: function (xhr) {
                    console.error("Error al eliminar:", xhr.responseText);
                    alert("No se pudo eliminar el producto.");
                }
            });
        }
    }
</script>

