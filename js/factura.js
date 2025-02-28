var totalGeneral;
function cargaFactura() {
    var carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    var factura = document.getElementById('facturaGeneral');

    var tablaHTML = `
        <table class="factura-content">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr >
            </thead>
            <tbody>
    `;
    var cantidadTotal = 0; 
    var totalSuma = 0; 
    carrito.forEach(function(producto) {
        tablaHTML += `
            <tr>
                <td>${producto.descripcion}</td>
                <td>${producto.cantidad}</td>
                <td>${producto.subtotal}</td>
            </tr class="linea">
        `;
        cantidadTotal += parseInt(producto.cantidad);
        totalSuma += parseFloat(producto.subtotal);
    });
    //cierre
    tablaHTML += `
            </tbody>
        </table>
    `;
    tablaHTML += `
        <table class="factura-content-final">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr >
            </thead>
            <tbody>
            <tr>
            <td>${cantidadTotal}</td>
            <td id="total">${totalSuma}</td>
            </tr class="linea">
    `;
    factura.innerHTML = tablaHTML;
    totalGeneral = totalSuma;
}

function enviarDatos() {
    localStorage.removeItem('carrito');
}
$(document).ready(function() { 

    cargaFactura ();
});


$(document).ready(function() {
    $("#button-pay").click(function() {

        var idCliente = $("#idCliente").val();
        var total = totalGeneral;
        console.log(idCliente);
        console.log(total);

        // Obtener los datos del carrito 
        var carrito = JSON.parse(localStorage.getItem('carrito'));

        $.ajax({
            url: "../DAL/facturas.php",
            method: "POST",
            data: { idCliente: idCliente, total: total },
            success: function(response) {
                console.log('Datos:' + response);

                if (response !== null && !isNaN(response)) {
                    var idFactura = parseInt(response);

                    $.ajax({
                        url: "../DAL/ventas.php",
                        method: "POST",
                        dataType: "text",
                        data: {
                            idFactura: idFactura,
                            carrito: JSON.stringify(carrito)
                        },
                        success: function(response) {
                            var mainFactura = document.getElementById('factura-contenido');
                            mainFactura.innerHTML = '';
                            localStorage.removeItem('carrito');
                            var mainHtml = `
                            <h1>Gracias por su compra</h1>
                            <p>Se comunicará vía correo o teléfono para finalizar su compra</p>
                            `;
                            mainFactura.innerHTML += mainHtml;
                            setTimeout(function() {
                                window.location.href = "index.php";
                            }, 3000); //Tiempo de carga de 3 segundos
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al insertar ventas:", error);
                        }
                    });
                } else {
                    console.error("Error al crear la factura. ID de factura inválido:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al crear la factura:", error);
            }
        });
    });
});
