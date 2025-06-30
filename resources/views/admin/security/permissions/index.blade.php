@extends('adminlte::page')

@section('title', 'Gestión de Permisos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">
            <i class="fas fa-shield-alt text-primary"></i>
            Gestión de Permisos de Usuario
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <i class="fas fa-cog"></i> Seguridad
                </li>
                <li class="breadcrumb-item active">Permisos</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-info text-white border-0">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Información de Roles del Sistema
                    </h6>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3 bg-white rounded shadow-sm">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-1 mr-2">
                                    <i class="fa fa-crown text-danger"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Administrador</h6>
                                    <p class="text-muted mb-0 small">Acceso completo al sistema</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3 bg-white rounded shadow-sm">
                                <div class="bg-success bg-opacity-10 rounded-circle p-1 mr-2">
                                    <i class="fa fa-crown text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Contador</h6>
                                    <p class="text-muted mb-0 small">Acceso a módulos contables</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3 bg-white rounded shadow-sm">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-1 mr-2">
                                    <i class="fa fa-crown text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Usuario</h6>
                                    <p class="text-muted mb-0 small">Acceso básico al sistema</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-users text-primary me-2"></i>
                            Lista de Usuarios del Sistema
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" id="refreshTable">
                                <i class="fas fa-sync-alt me-1"></i> Actualizar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="permissions-table" class="table table-hover table-striped">
                            <thead class="table-dark">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">DNI</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Nombres</th>
                                <th class="text-center">Apellidos</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Rol</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Gestión de Permisos -->
    @include('admin.security.permissions.modals.manage')

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .table-dark th {
            border-color: #454d55;
            background-color: #343a40;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .btn {
            border-radius: 8px;
        }

        #permissions-table_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        #permissions-table_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
        }

        .breadcrumb-item a {
            text-decoration: none;
            color: #6c757d;
        }

        .breadcrumb-item a:hover {
            color: #007bff;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- SweetAlert2 para notificaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Configuración de DataTable
            const table = $('#permissions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{!! route("permissions.index") !!}',
                    error: function(xhr, error, thrown) {
                        console.error('Error en DataTable:', error);
                        showError('Error al cargar los datos de la tabla');
                    }
                },
                columns: [
                    {
                        data: 'id',
                        className: 'text-center align-middle fw-bold',
                        width: '5%'
                    },
                    {
                        data: 'dni',
                        className: 'text-center align-middle',
                        width: '10%'
                    },
                    {
                        data: 'user',
                        className: 'text-center align-middle fw-semibold',
                        width: '12%'
                    },
                    {
                        data: 'nombres',
                        className: 'text-center align-middle',
                        width: '15%'
                    },
                    {
                        data: 'apellidos',
                        className: 'text-center align-middle',
                        width: '15%'
                    },
                    {
                        data: 'email',
                        className: 'text-center align-middle',
                        width: '18%'
                    },
                    {
                        data: 'rol',
                        className: 'text-center align-middle',
                        width: '10%',
                        render: function(data, type, row) {
                            const badges = {
                                'Administrador': 'bg-danger',
                                'Contabilidad': 'bg-success',
                                'Usuario': 'bg-primary'
                            };
                            const badgeClass = badges[data] || 'bg-secondary';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'estado',
                        className: 'text-center align-middle',
                        width: '8%'
                    },
                    {
                        data: 'action',
                        className: 'text-center align-middle',
                        orderable: false,
                        searchable: false,
                        width: '7%'
                    }
                ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[0, 'desc']],
                drawCallback: function() {
                    // Reinicializar tooltips si los usas
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            // Botón actualizar tabla
            $('#refreshTable').on('click', function() {
                table.ajax.reload(null, false);
                showSuccess('Tabla actualizada correctamente');
            });

            // Configurar modal de permisos
            const modal = new bootstrap.Modal($('#permissionsModal')[0], {
                backdrop: 'static',
                keyboard: false
            });

            // Manejar clic en botón de ver permisos
            $('#permissions-table').on('click', '.btn-show', function(e) {
                e.preventDefault();
                const userId = $(this).data('id');

                if (userId) {
                    // El modal se abrirá automáticamente por el data-bs-target
                    // La carga de datos se maneja en el evento show.bs.modal del modal
                } else {
                    showError('ID de usuario no válido');
                }
            });

            // Event listeners para el modal (definidos en el modal)
            window.showSuccess = function(message) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            };

            window.showError = function(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#dc3545'
                });
            };

            window.showWarning = function(message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            };

            // Hacer la tabla disponible globalmente para el modal
            window.permissionsTable = table;
        });
    </script>
@stop
