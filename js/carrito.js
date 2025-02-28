function agregarAlCarrito(codigo, imagen, descripcion, precio) {
    try {
        var carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        var productoExiste = carrito.find(function(producto) {
            return producto.descripcion === descripcion;
        });
        var talla = "0"; 
        if ($('#talla').length !== 0) {
            talla = $('#talla').val() || "0";
        }
        if (productoExiste) {
            productoExiste.cantidad++;
            productoExiste.subtotal = productoExiste.precio * productoExiste.cantidad;
        } else {
            var producto = {
                codigo: codigo,
                descripcion: descripcion,
                talla: talla,
                precio: precio,
                imagen: imagen,
                cantidad: 1,
                subtotal: precio
            };
            carrito.push(producto);
        }
        localStorage.setItem('carrito', JSON.stringify(carrito));
        Toastify({
            text: 'Producto agregado al carrito',
            duration: 3000,
            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
        }).showToast();
    } catch (error) {
        Toastify({
            text: 'Ocurrio un error al agregar el producto al carrito',
            duration: 3000,
            backgroundColor: "linear-gradient(to right,  #ff0000, #ff1a1a)"
        }).showToast();
        console.error('Error al agregar producto al carrito:', error);
    }
}





$(document).ready(function() {
    var carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    var divCarrito = document.getElementById('cart-container');
    var factura = document.getElementById('bill-payment');
    divCarrito.innerHTML = '';

    // Verifica si el carrito está vacío
    if (carrito.length === 0) {
        var cartNav = document.getElementById('cart-nav');
        cartNav.innerHTML = `  <div class="text-center">
                                    <h3>No hay productos en el carrito</h3>
                                </div>`;
        return; 
    }
    
    var cantidadTotal = 0; 
    var totalSuma = 0; 
    
    carrito.forEach(function(producto) {
        var productoHTML = '<div class="producto">';
        productoHTML += '<img class="producto-imagen" src="' + producto.imagen + '" alt="' + producto.descripcion + '">';
        productoHTML += '<div class="producto-item">';
        productoHTML += '<h3 class="producto-descripcion">' + producto.descripcion + '</h3>';
        productoHTML += '</div>';
        if (producto.talla !== "0") {
            productoHTML += '<div class="producto-item" >'
            productoHTML += '<small>Talla</small>';
            productoHTML += '<p>' + producto.talla + '</p>';
            productoHTML += '</div>';
        } 
        productoHTML += '<div class="producto-item">';
        productoHTML += '<small class="producto-titulo">Precio</small>';
        productoHTML += '<p>' + producto.precio + '</p>';
        productoHTML += '</div>';

        productoHTML += '<div class="producto-item">';
        productoHTML += '<small class="producto-titulo">Cantidad</small>';
        productoHTML += '<p>' + producto.cantidad + '</p>';
        productoHTML += '</div >';

        productoHTML += '<div class="producto-item">';
        productoHTML += '<small> Subtotal</small>';
        productoHTML += '<p>'+ producto.subtotal + '</p>';
        productoHTML += '</div>';
        productoHTML += '<button onclick="borrarProducto(\'' + producto.descripcion + '\')"><i class="fa-solid fa-trash"></i></button>';
        productoHTML += '</div>';
        
        divCarrito.innerHTML += productoHTML;
        
        cantidadTotal += parseInt(producto.cantidad);
        totalSuma += parseFloat(producto.subtotal);
    });
    factura.style.backgroundColor = "white";
    factura.innerHTML = `
        <p>Cantidad total de productos: ${cantidadTotal}</p>
        <p>Total: ${totalSuma}</p>
        <button id="buy-button" type="button" class="button-buy">Comprar</button>
    `;
    document.getElementById('buy-button').addEventListener('click', function() {
        window.location.href = 'facturar.php';
    });
});


function borrarProducto(descripcion) {
    var carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    var nuevoCarrito = carrito.filter(function(producto) {
        return producto.descripcion !== descripcion;
    });
    localStorage.setItem('carrito', JSON.stringify(nuevoCarrito));
    window.location.reload ();
}