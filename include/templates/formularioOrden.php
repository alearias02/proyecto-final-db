<div id="formularioOrden" class="modal fade" tabindex="-1" aria-labelledby="formNuevaOrden" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="formCrearOrden" method="POST">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title mx-auto">Nueva Orden</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
  
            <div class="mb-3">
              <label for="customer_id">Cliente</label>
              <select name="customer_id" id="customer_id" class="form-control" required>
                <option value="" disabled selected>Seleccione un cliente</option>
                <!-- Aquí puedes hacer un foreach en adminOrdenes.php si quieres traer los clientes -->
              </select>
            </div>
  
            <div class="mb-3">
              <label for="order_date">Fecha de Orden</label>
              <input type="date" class="form-control" name="order_date" value="<?= date('Y-m-d') ?>" required>
            </div>
  
            <div class="mb-3">
              <label for="order_amount">Monto Total</label>
              <input type="number" class="form-control" name="order_amount" required>
            </div>
  
            <div class="mb-3">
              <label for="comments">Comentarios</label>
              <textarea name="comments" class="form-control" rows="3"></textarea>
            </div>
  
            <div class="mb-3">
              <label for="payment_method_id">Método de Pago</label>
              <select name="payment_method_id" class="form-control" required>
                <option value="" disabled selected>Seleccione un método</option>
                <!-- Trae los métodos desde la DB -->
              </select>
            </div>
  
            <input type="hidden" name="created_by" value="<?= $_SESSION['usuario']['user_name'] ?>">
            <input type="hidden" name="action" value="insertar">
  
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Generar Orden</button>
          </div>
        </form>
      </div>
    </div>
  </div>