<!-- Modal de Gestión de Permisos -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                        <i class="fas fa-shield-alt fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="permissionsModalLabel">Gestión de Permisos</h5>
                        <small class="opacity-75" id="userInfo">Configurar permisos de usuario</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Información del Usuario -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1" id="userName">Nombre del Usuario</h6>
                                        <p class="text-muted mb-0 small" id="userDetails">DNI: 12345678 | usuario@email.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body py-3">
                                <label for="roleType" class="form-label fw-semibold mb-2">
                                    <i class="fas fa-user-tag me-1"></i> Rol del Usuario
                                </label>
                                <select class="form-select form-select-sm border-0 shadow-sm" id="roleType">
                                    <option value="Administrador">👑 Administrador</option>
                                    <option value="Contador">📊 Contador</option>
                                    <option value="Usuario">👤 Usuario</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Permisos -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-key text-warning mr-2"></i>
                                Configuración de Permisos
                            </h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-success" id="enableAll">
                                    <i class="fas fa-check-double me-1"></i> Activar Todos
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="disableAll">
                                    <i class="fas fa-times me-1"></i> Desactivar Todos
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3">

                        <!-- Dashboard -->
                        <div class="permission-section mb-4">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <div class="permission-icon me-3">
                                        <i class="fas fa-tachometer-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Dashboard</h6>
                                        <small class="text-muted">Acceso al panel principal del sistema</small>
                                    </div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input permission-toggle" type="checkbox"
                                           id="perm_dashboard" data-permission="verDashboard">
                                </div>
                            </div>
                        </div>

                        <!-- Mantenimiento -->
                        <div class="permission-section mb-4">
                            <div class="permission-header p-3 bg-gradient-light rounded-top border-start border-4 border-info">
                                <h6 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-cogs text-info mr-2"></i>
                                    Módulo de Mantenimiento
                                </h6>
                            </div>

                            <div class="permission-items bg-white border border-top-0 rounded-bottom p-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-tags text-info mr-2"></i>
                                                <span class="fw-medium">Clasificadores</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" type="checkbox"
                                                       id="perm_clasificadores" data-permission="verMantenimientoClasificadores">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-users text-info mr-2"></i>
                                                <span class="fw-medium">Trabajadores</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" type="checkbox"
                                                       id="perm_trabajadores" data-permission="verMantenimientoTrabajadores">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-building text-info mr-2"></i>
                                                <span class="fw-medium">Oficinas</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" type="checkbox"
                                                       id="perm_oficinas" data-permission="verMantenimientoOficinas">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contabilidad -->
                        <div class="permission-section mb-4">
                            <div class="permission-header p-3 bg-gradient-light rounded-top border-start border-4 border-success">
                                <h6 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-calculator text-success mr-2"></i>
                                    Módulo Contable
                                </h6>
                            </div>

                            <div class="permission-items bg-white border border-top-0 rounded-bottom p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-invoice text-success mr-2"></i>
                                                <span class="fw-medium">Documentos SIAF</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" type="checkbox"
                                                       id="perm_documentos_siaf" data-permission="verContabilidadSiaf">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-download text-success mr-2"></i>
                                                <span class="fw-medium">Exportación y Cierre</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" type="checkbox"
                                                       id="perm_exportacion" data-permission="verContabilidadExportacion">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rendiciones -->
                        <div class="permission-section mb-4">
                            <div class="permission-header p-3 bg-gradient-light rounded-top border-start border-4 border-warning">
                                <h6 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-receipt text-warning mr-2"></i>
                                    Módulo de Rendiciones
                                </h6>
                            </div>

                            <div class="permission-items bg-white border border-top-0 rounded-bottom p-3">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-paper-plane text-warning mr-2"></i>
                                                <span class="fw-medium">Solicitudes</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" type="checkbox"
                                                       id="perm_solicitudes" data-permission="verRendicionesSolicitudes">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-balance-scale text-warning mr-2"></i>
                                                <span class="fw-medium">Liquidaciones</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" type="checkbox"
                                                       id="perm_liquidaciones" data-permission="verRendicionesLiquidaciones">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subpermisos de Rendiciones -->
                                <div class="bg-light rounded p-3">
                                    <h6 class="text-muted mb-3 fw-semibold">
                                        <i class="fas fa-sitemap me-1"></i>
                                        Tipos de Rendición
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg-white">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-cash-register text-success mr-2"></i>
                                                    <span class="fw-medium">Caja Chica</span>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input permission-toggle" type="checkbox"
                                                           id="perm_caja_chica" data-permission="verRendicionesCajaChica">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg-white">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-handshake text-primary mr-2"></i>
                                                    <span class="fw-medium">Encargos</span>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input permission-toggle" type="checkbox"
                                                           id="perm_encargos" data-permission="verRendicionesEncargos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="permission-item d-flex align-items-center justify-content-between p-2 rounded hover-bg-white">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-plane text-info mr-2"></i>
                                                    <span class="fw-medium">Viáticos</span>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input permission-toggle" type="checkbox"
                                                           id="perm_viaticos" data-permission="verRendicionesViaticos">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seguridad -->
                        <div class="permission-section mb-4">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded border-start border-4 border-danger">
                                <div class="d-flex align-items-center">
                                    <div class="permission-icon me-3">
                                        <i class="fas fa-shield-alt text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Módulo de Seguridad</h6>
                                        <small class="text-muted">Gestión de usuarios y permisos del sistema</small>
                                    </div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input permission-toggle" type="checkbox"
                                           id="perm_seguridad" data-permission="verSeguridad">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="modal-footer bg-light border-0 p-4">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Los cambios se aplicarán inmediatamente
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-success px-4" id="savePermissions">
                            <i class="fas fa-save me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados para el modal */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }

    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .permission-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 123, 255, 0.1);
        border-radius: 50%;
    }

    .permission-item {
        transition: all 0.2s ease;
    }

    .hover-bg:hover {
        background-color: rgba(0, 123, 255, 0.05) !important;
    }

    .hover-bg-white:hover {
        background-color: white !important;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .form-check-input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .permission-section {
        transition: all 0.3s ease;
    }

    .permission-section:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
    }

    .card {
        border-radius: 10px;
    }

    .btn {
        border-radius: 8px;
    }

    .form-select {
        border-radius: 8px;
    }

    /* Animación para los toggles */
    .permission-toggle {
        transform: scale(1.2);
        transition: all 0.2s ease;
    }

    .permission-toggle:hover {
        transform: scale(1.3);
    }

    /* Estilo para botones de acción rápida */
    #enableAll, #disableAll {
        transition: all 0.2s ease;
    }

    #enableAll:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    #disableAll:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentUserId = null;
        let currentUserData = null;

        // Configurar modal cuando se abre
        $('#permissionsModal').on('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            currentUserId = button.getAttribute('data-id');

            // Cargar datos del usuario
            loadUserPermissions(currentUserId);
        });

        // Función para cargar permisos del usuario
        function loadUserPermissions(userId) {
            // Mostrar loading
            showLoading();

            // Hacer petición AJAX para obtener datos del usuario
            $.ajax({
                url: '{{ route("permissions.show", ":id") }}'.replace(':id', userId),
                method: 'GET',
                success: function(response) {
                    currentUserData = response.user;
                    updateModalUserInfo(response.user);
                    updatePermissionStates(response.permissions);
                    hideLoading();
                },
                error: function(xhr) {
                    console.error('Error al cargar permisos:', xhr);
                    showError('Error al cargar los permisos del usuario');
                    hideLoading();
                }
            });
        }

        // Actualizar información del usuario en el modal
        function updateModalUserInfo(user) {
            $('#userName').text(`${user.person?.name || ''} ${user.person?.lastname || ''}`);
            $('#userDetails').text(`DNI: ${user.person?.document_number || 'N/A'} | ${user.email || 'N/A'}`);
            $('#userInfo').text(`Configurar permisos para ${user.username}`);
            $('#roleType').val(user.role?.name || 'Usuario');
        }

        // Actualizar estados de los permisos
        function updatePermissionStates(permissions) {
            console.log("permissionssssss");
            console.log(permissions);
            $('.permission-toggle').each(function() {
                const permission = $(this).data('permission');
                const isActive = permissions[permission] === 1;
                $(this).prop('checked', isActive);
            });
        }

        // Manejar cambios en los toggles
        $(document).on('change', '.permission-toggle', function() {
            const $toggle = $(this);
            const permission = $toggle.data('permission');
            const isActive = $toggle.is(':checked');

            // Añadir feedback visual
            $toggle.closest('.permission-item, .permission-section').addClass('updating');

            // Simular delay para mostrar el cambio
            setTimeout(() => {
                $toggle.closest('.permission-item, .permission-section').removeClass('updating');
            }, 300);

            console.log(`Permiso ${permission} cambiado a: ${isActive ? 'activo' : 'inactivo'}`);
        });

        // Activar todos los permisos
        $('#enableAll').on('click', function() {
            $('.permission-toggle').prop('checked', true).trigger('change');
            showSuccess('Todos los permisos han sido activados');
        });

        // Desactivar todos los permisos
        $('#disableAll').on('click', function() {
            $('.permission-toggle').prop('checked', false).trigger('change');
            showWarning('Todos los permisos han sido desactivados');
        });

        // Guardar cambios
        $('#savePermissions').on('click', function() {
            savePermissions();
        });

        // Función para guardar permisos
        function savePermissions() {
            if (!currentUserId) {
                showError('No se ha seleccionado un usuario');
                return;
            }

            const permissions = {};
            $('.permission-toggle').each(function() {
                const permission = $(this).data('permission');
                permissions[permission] = $(this).is(':checked') ? 1 : 0;
            });

            const data = {
                user_id: currentUserId,
                role: $('#roleType').val(),
                permissions: permissions,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            // Mostrar loading en el botón
            const $saveBtn = $('#savePermissions');
            const originalText = $saveBtn.html();
            $saveBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Guardando...').prop('disabled', true);

            $.ajax({
                url: '{{ route("permissions.updatePermissions") }}',
                method: 'POST',
                data: data,
                success: function(response) {
                    showSuccess('Permisos actualizados correctamente');
                    $('#permissionsModal').modal('hide');

                    // Recargar la tabla
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    console.error('Error al guardar permisos:', xhr);
                    showError('Error al guardar los permisos');
                },
                complete: function() {
                    $saveBtn.html(originalText).prop('disabled', false);
                }
            });
        }

        // Funciones auxiliares para mostrar mensajes
        function showSuccess(message) {
            // Implementar notificación de éxito (puedes usar SweetAlert2, Toastr, etc.)
            console.log('Éxito:', message);
        }

        function showError(message) {
            // Implementar notificación de error
            console.error('Error:', message);
        }

        function showWarning(message) {
            // Implementar notificación de advertencia
            console.warn('Advertencia:', message);
        }

        function showLoading() {
            // Mostrar indicador de carga
            $('.modal-body').append('<div id="loading-overlay" class="d-flex justify-content-center align-items-center position-absolute w-100 h-100" style="background: rgba(255,255,255,0.8); top: 0; left: 0; z-index: 1000;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div></div>');
        }

        function hideLoading() {
            $('#loading-overlay').remove();
        }
    });

    // CSS adicional para animaciones
    const additionalStyles = `
<style>
.updating {
    opacity: 0.7;
    transform: scale(0.98);
    transition: all 0.3s ease;
}

.permission-toggle {
    cursor: pointer;
}

.permission-item {
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.permission-item:hover {
    border-color: rgba(0, 123, 255, 0.2);
}

#loading-overlay {
    backdrop-filter: blur(2px);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
`;

    document.head.insertAdjacentHTML('beforeend', additionalStyles);
</script>
