<!-- Encabezado -->
<div class='row'>
  <h2 class='text-center justify-content-center'>Impermeables</h2>
</div>

<!-- Contenedor dinámico -->
<div class='row row-cols-1 row-cols-md-3 m-3 g-4' id="impermeables-container"></div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Font Awesome para íconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-p6PzLUYvReF+c7ZbG4xA5fqGZyE8LFYuH+eFFVOQwMX0ibF5rOzt3Q4Yi2DCh25s64PGeJRo1s+IxMP2IcSdzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
$(document).ready(function() {
  $.ajax({
    url: '../include/functions/recogeImpermeable.php', 
    type: 'GET', 
    dataType: 'json', 
    success: function(data) {
      console.log("Datos recibidos:", data);

      if (data && data.length > 0) {
        $('#impermeables-container').empty();

        $.each(data, function(index, IMPERMEABLE) {
          const IMAGE_PATH = IMPERMEABLE.IMAGE_PATH ? IMPERMEABLE.IMAGE_PATH.replace('..', '../') : '../../img/default.jpg';
          const DESCRIPCION = IMPERMEABLE.DESCRIPTION || 'Sin descripción';
          const DETALLE = IMPERMEABLE.DETALLE || 'Detalles no disponibles';
          const PRECIO = IMPERMEABLE.PRECIO !== null ? `Precio: $${IMPERMEABLE.PRECIO}` : 'Precio no disponible';

          const impermeableHTML = `
            <div class="col">
              <div class="card text-dark bg-light h-100 shadow">
                <div class="image-container">
                  <img src="${IMAGE_PATH}" class="card-img-top" alt="Imagen Impermeable">
                </div>
                <div class="card-body d-flex flex-column m-auto align-items-center">
                  <h3 class="card-title text-center">${DESCRIPCION}</h3>
                  <p class="text-center">${DETALLE}</p>
                  <p class="text-center"><strong>${PRECIO}</strong></p>
                  <div class="btn-group mt-auto w-100 gap-2 d-flex flex-column flex-md-row">
                    <a href="../srcItem/mostrarImpermeable.php?PRODUCT_ID=${IMPERMEABLE.PRODUCT_ID}" class="btn btn-primary mt-auto">Ver más</a>
                    <button class="btn btn-success add-to-cart" data-id="${IMPERMEABLE.PRODUCT_ID}">
                      <i class="fas fa-cart-plus"></i> Agregar al carrito
                    </button>
                  </div>
                </div>
              </div>
            </div>`;

          $('#impermeables-container').append(impermeableHTML);
        });
      } else {
        $('#impermeables-container').html('<p>No hay productos disponibles</p>');
      }
    },
    error: function(xhr, status, error) {
      console.error('Error al obtener los impermeables:', error);
      $('#impermeables-container').html('<p>Ocurrió un error al obtener los productos</p>');
    }
  });
});

$(document).on('click', '.add-to-cart', function() {
  const productId = $(this).data('id');

  $.ajax({
    url: '../include/functions/addToCart.php', 
    type: 'POST',
    dataType: 'json',
    data: { product_id: productId },
    success: function(response) {
      if (response.success) {
        console.log('Producto asignado al carrito ', response)
        alert('Producto agregado al carrito 🛒');
      } else if (response.error) {
        alert('Error: ' + response.error);
      }
    },
    error: function(xhr, status, error) {
      console.log('Error AJAX:', error);
      alert('Hubo un error al agregar el producto.');
    }
  });
});
</script>

<style>
/* Contenedor de imagen ajustado */
.image-container {
  height: 250px;
  overflow: hidden;
}

.image-container img {
  object-fit: cover;
  width: 100%;
  height: 100%;
}

/* Alineación y espacio */
.card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-radius: 12px;
  overflow: hidden;
}

.card-body {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

/* Botón siempre abajo */
.btn {
  width: 100%;
  margin-top: auto;
}

.btn-group {
  width: 100%;
}

.btn-group .btn {
  flex: 1;
}

/* Agrega un efecto de sombra para mejorar el diseño */
.shadow {
  box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
</style>
