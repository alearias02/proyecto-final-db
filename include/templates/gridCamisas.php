<div class='row'>
    <h2 class='text-center justify-content-center'>Camisas</h2>
</div>
<div class='row row-cols-1 row-cols-md-3 m-3 g-4' id="camisas-container">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajax({
        url: '../include/functions/recogeCamisas.php', 
        type: 'GET', 
        dataType: 'json', 
        success: function(data) { 
            console.log("Datos recibidos:", data);

            if (data && data.length > 0) {
                $('#camisas-container').empty();

                $.each(data, function(index, CAMISA) {
                    console.log("Camisa:", CAMISA); // Agregado para depuración

                    const IMAGE_PATH = CAMISA.IMAGE_PATH ? CAMISA.IMAGE_PATH.replace('..', '../') : '../../img/default.jpg';
                    const DESCRIPCION = CAMISA.DESCRIPCION || 'Sin descripción';
                    const DETALLE = CAMISA.DETALLE || 'Detalles no disponibles';
                    const PRECIO = CAMISA.PRECIO !== null ? `Precio: $${CAMISA.PRECIO}` : 'Precio no disponible';
                    
                    var camisaHTML = `<div class="col-md-4 mb-4">
                                            <div class="card text-dark bg-light h-100">
                                                <div style="height: 250px; overflow: hidden;">
                                                    <img src="${IMAGE_PATH}" class="card-img-top" alt="Imagen Camisa" style="object-fit: cover; width: 100%; height: 100%;">
                                                </div>
                                                <div class="card-body d-flex flex-column justify-content-between">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item text-center"><h5 class="card-title">${DESCRIPCION}</h5></li>
                                                        <li class="list-group-item text-center">${DETALLE}</li>  
                                                        <li class="list-group-item">${PRECIO}</li>
                                                    </ul>
                                                    <a href="../srcItem/mostrarCamisa.php?ID_CAMISA=${CAMISA.ID_CAMISA}" class="btn btn-primary mt-3">Ver más</a>
                                                </div>
                                            </div>
                                        </div>`;

                    $('#camisas-container').append(camisaHTML);
                });
            } else {
                $('#camisas-container').html('<p>No hay productos disponibles</p>');
            }
        },
        error: function(xhr, status, error) { 
            console.error('Error al obtener las camisas:', error);
            console.log("xhr:",xhr);
            console.log("status:",status);
            console.log("error:",error);
            $('#camisas-container').html('<p>Ocurrió un error al obtener los productos</p>');
        }
    });
});
</script>