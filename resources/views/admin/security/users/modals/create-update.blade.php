<!-- Modal de Usuario -->
<div class="modal fade" id="userModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">
                    <i class="fas fa-user-cog me-2"></i>Gestión de Usuario
                </h5>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="mb-3" id="select-trabajador-group" style="display:none;">
                        <label for="trabajador_id" class="form-label">
                            <i class="fas fa-user"></i>Seleccionar trabajador <span class="required">*</span>
                        </label>
                        <select class="form-control" id="trabajador_id" name="trabajador_id">
                            <option value="">Seleccione un trabajador</option>
                            <option value="1">Juan Pérez García</option>
                            <option value="2">María López Rodríguez</option>
                            <option value="3">Carlos Mendoza Silva</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>Nombres
                                </label>
                                <input type="text" class="form-control" id="nombres" readonly value="Juan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>Apellidos
                                </label>
                                <input type="text" class="form-control" id="apellidos" readonly value="Pérez García">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>DNI
                                </label>
                                <input type="text" class="form-control" id="dni" readonly value="12345678">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user-circle"></i>Username
                                </label>
                                <input type="text" class="form-control" id="username" readonly value="jperez">
                            </div>
                        </div>
                    </div>

                    <!-- Oficina (solo para crear) -->
                    <div class="mb-3" id="div-office" style="display:none;">
                        <label class="form-label">
                            <i class="fas fa-building"></i>Oficina
                        </label>
                        <input type="text" class="form-control" id="office" readonly value="Oficina Central">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i>Email <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="usuario@empresa.com">
                    </div>

                    <div class="mb-3 ml-2">
                        <div class="row">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i>Estado
                            </label>
                            <div class="d-flex align-items-center mt-2 ml-1 form-check form-switch">
                                <label class="toggle-switch">
                                    <input class="form-check-input" type="checkbox" id="active" name="active">
                                    <span class="toggle-slider mr-3"></span>
                                    <span class="toggle-label">Activo</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>Contraseña <span id="password-required" class="required">*</span>
                                </label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 6 caracteres">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>Confirmar Contraseña <span id="password-confirm-required" class="required">*</span>
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repetir contraseña">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2 mr-2"></i>Cancelar
                        </button>
                        <button type="submit" id="userSubmitBtn" class="btn btn-success ml-2">
                            <i class="fas fa-save me-2"></i>Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal principal */
    #userModal .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fb 100%);
        overflow: hidden;
    }

    #userModal .modal-header {
        background: linear-gradient(135deg, #002855 0%, #003d7a 100%);
        border: none;
    }

    #userModal .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    #userModal .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: white;
        margin: 0;
    }

    #userModal .btn-close {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        width: 35px;
        height: 35px;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    #userModal .btn-close:hover {
        background-color: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
        opacity: 1;
    }

    #userModal .modal-body {
        padding: 40px;
        background-color: #ffffff;
    }

    /* Formulario */
    #userForm .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
    }

    #userForm .form-label i {
        color: #0072ff;
        margin-right: 8px;
        width: 16px;
    }

    #userForm .form-control, #userForm select {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #fafbfc;
    }

    #userForm .form-control:focus, #userForm select:focus {
        border-color: #0072ff;
        box-shadow: 0 0 0 4px rgba(0, 114, 255, 0.1);
        background-color: #ffffff;
        transform: translateY(-1px);
    }

    #userForm .form-control:hover:not(:focus) {
        border-color: #ced4da;
        background-color: #ffffff;
    }

    #userForm .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 20px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 34px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 2px;
        background-color: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .toggle-switch input:checked + .toggle-slider {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .toggle-switch input:focus + .toggle-slider {
        box-shadow: 0 0 0 4px rgba(0, 114, 255, 0.1);
    }

    .toggle-label {
        margin-left: 50px;
        font-weight: 200;
        color: #2c3e50;
        vertical-align: middle;
        transition: color 0.3s ease;
    }

    .toggle-switch input:checked ~ .toggle-label {
        color: #28a745;
    }

    .toggle-switch input:not(:checked) ~ .toggle-label {
        color: #dc3545;
    }

    /* Botones */
    .btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }

    /* Grupos de campos */
    .mb-3 {
        margin-bottom: 24px !important;
    }

    .row .mb-3:last-child {
        margin-bottom: 24px !important;
    }

    /* Select2 personalizado */
    .select2-container--default .select2-selection--single {
        height: 50px !important;
        border: 2px solid #e9ecef !important;
        border-radius: 12px !important;
        background-color: #fafbfc !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 46px !important;
        padding-left: 16px !important;
        color: #2c3e50 !important;
        font-size: 0.95rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 12px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #0072ff !important;
        box-shadow: 0 0 0 4px rgba(0, 114, 255, 0.1) !important;
        background-color: #ffffff !important;
    }

    /* Animaciones */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #userModal .modal-dialog {
        animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Campo requerido */
    .required {
        color: #dc3545;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #userModal .modal-body {
            padding: 30px 20px;
        }

        #userModal .modal-header {
            padding: 20px 25px;
        }
    }
</style>
