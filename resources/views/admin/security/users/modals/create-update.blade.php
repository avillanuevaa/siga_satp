<!-- Modal -->
<div class="modal fade" id="userModal" data-backdrop="static" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0" style="background-color:#f4f4f9;">
            <div class="modal-header text-white" style="background-color:#002855;">
                <h5 class="modal-title" id="userModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    @csrf
                    <div class="mb-3" id="select-trabajador-group" style="display:none;">
                        <label>Seleccionar trabajador</label>
                        <select class="form-select" id="trabajador_id" name="trabajador_id" style="width: 100%;"></select>
                    </div>
                    <div class="mb-2">
                        <label>Nombres</label>
                        <input type="text" class="form-control" id="nombres" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" readonly>
                    </div>
                    <div class="mb-2">
                        <label>DNI</label>
                        <input type="text" class="form-control" id="dni" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Username</label>
                        <input type="text" class="form-control" id="username" readonly>
                    </div>
                    <div class="mb-2" id="div-office" style="display:none;">
                        <label>Oficina</label>
                        <input type="text" class="form-control" id="office" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-2">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-2">
                        <label>Confirmar Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
