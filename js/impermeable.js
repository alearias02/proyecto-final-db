$(document).ready(function() {
    $.ajax({
        url: '../../include/functions/recogeImpermeable.php', 
        type: 'GET', 
        dataType: 'json', 
        success: function(data) { 
            console.log(data)
            if (data && data.length > 0) {
                $.each(data, function(index, impermeable) {
                    var impermeableHTML = 
                   `<div class="col-md-4 mb-4">
                        <div class="card text-dark bg-light h-100">
                            <div style="height: 250px; overflow: hidden;">
                                <img src="${impermeable.ruta_imagen}" class="card-img-top" alt="Imagen Camisa" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item text-center justify-content-center"><h2 class="card-title">${impermeable.descripcion}</h5></li>
                                    <li class="list-group-item text-center justify-content-center">${impermeable.detalle}</li>  
                                    <li class="list-group-item">Precio: ${impermeable.precio}</li>
                                    <li class="list-group-item">Existencias: ${impermeable.existencias}</li>
                                </ul>
                                <a href="../srcItem/mostrarImpermeable.php?id_impermeable=${impermeable.id_impermeable}" class="btn btn-primary mt-3">Ver más</a>
                            </div>
                        </div>
                    </div>`;
                    
                    $('#impermeable-container').append(impermeableHTML);
                });
            } else {
                $('#impermeable-container').html('<p>No hay productos</p>');
            }
        },
        error: function(xhr, status, error) { 
            console.error('Error al obtener los impermeables:', error);
            $('#impermeable-container').html('<p>Ocurrió un error al obtener los productos</p>');
        }
    });
});
