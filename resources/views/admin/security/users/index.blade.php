@extends('layouts/admin')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Usuarios</h1>
        <button id="btnOpenCreateModal" type="button" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </button>
    </div>
@stop

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <table id="users-table" class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Usuario</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@include('admin.security.users.modals.create-update')

@push('css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colvis/1.1.2/css/dataTables.colVis.css" rel="stylesheet">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("users.index") !!}',
                columns: [
                    { data: 'id', className: 'text-center align-middle' },
                    { data: 'dni', className: 'text-center align-middle' },
                    { data: 'user', className: 'text-center align-middle' },
                    { data: 'nombres', className: 'text-center align-middle' },
                    { data: 'apellidos', className: 'text-center align-middle' },
                    { data: 'email', className: 'text-center align-middle' },
                    { data: 'telefono', className: 'text-center align-middle' },
                    { data: 'rol', className: 'text-center align-middle' },
                    { data: 'estado', className: 'text-center align-middle' },
                    { data: 'created_at', className: 'text-center align-middle' },
                    { data: 'action', className: 'text-center align-middle', orderable: false, searchable: false },
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-secondary btn-sm',
                        action: function(e, dt, node) {
                            const $btn = $(node);
                            const original = $btn.html();
                            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Copiando...');

                            fetch('{{ route("users.exportCopy") }}')
                                .then(r => r.text())
                                .then(text => navigator.clipboard.writeText(text))
                                .then(() => Swal.fire('Copiado', 'Registros copiados al portapapeles.', 'success'))
                                .catch(() => Swal.fire('Error', 'No se pudo copiar al portapapeles.', 'error'))
                                .finally(() => $btn.prop('disabled', false).html(original));
                        }
                    },
                    {
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-dark btn-sm',
                        action: function(e, dt, node) {
                            const $btn = $(node);
                            const original = $btn.html();
                            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
                            window.open('{{ route("users.exportPrint") }}', '_blank');
                            setTimeout(() => $btn.prop('disabled', false).html(original), 1000);
                        }
                    },
                    {
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        action: function(e, dt, node) {
                            const $btn = $(node);
                            const original = $btn.html();
                            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                            window.location.href = '{{ route('users.exportExcel') }}';
                            setTimeout(() => $btn.prop('disabled', false).html(original), 3000);
                        }
                    },
                    {
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info btn-sm',
                        action: function(e, dt, node) {
                            const $btn = $(node);
                            const original = $btn.html();
                            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                            window.location.href = '{{ route("users.exportCsv") }}';
                            setTimeout(() => $btn.prop('disabled', false).html(original), 3000);
                        }
                    },
                    {
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        action: function(e, dt, node) {
                            const $btn = $(node);
                            const original = $btn.html();
                            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
                            window.open('{{ route("users.exportPdf") }}', '_blank');
                            setTimeout(() => $btn.prop('disabled', false).html(original), 3000);
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columnas',
                        className: 'btn btn-warning btn-sm'
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            const modal = new bootstrap.Modal($('#userModal')[0], { backdrop: 'static', keyboard: false });
            const $form = $('#userForm');
            const $submitBtn = $('#userSubmitBtn');
            const $userId = $('#user_id');

            function clearErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            }

            // Función para mostrar errores
            function showErrors(errors) {
                clearErrors();
                Object.keys(errors).forEach(field => {
                    const $field = $(`#${field}`);
                    if ($field.length) {
                        $field.addClass('is-invalid');
                        $field.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }
                });
            }

            $('#trabajador_id').select2({
                dropdownParent: $('#userModal'),
                placeholder: 'Buscar trabajador por nombre o DNI',
                allowClear: true,
                ajax: {
                    url: '{{ route("persons.search") }}',
                    dataType: 'json',
                    delay: 300,
                    cache: true,
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.text
                            }))
                        };
                    }
                }
            });

            $('#trabajador_id').on('select2:select', function(e) {
                const id = e.params.data.id;

                $.get(`{{ route('persons.searchById') }}?id=${id}`)
                    .done(function(person) {
                        $('#nombres').val(person.name || '');
                        $('#apellidos').val(person.lastname || '');
                        $('#dni').val(person.document_number || '');
                        $('#username').val(person.document_number || '');
                        $('#office').val(person.office?.name || '');
                    })
                    .fail(function() {
                        Swal.fire('Error', 'No se pudo cargar la información del trabajador.', 'error');
                    });
            });

            $('#btnOpenCreateModal').on('click', function() {
                $form[0].reset();
                clearErrors();
                $userId.val('');

                // Configurar toggle para crear (activo por defecto)
                const toggleSwitch = document.getElementById('active');
                const toggleLabel = document.querySelector('.toggle-label');

                // Establecer activo por defecto para nuevo usuario
                toggleSwitch.checked = true;

                function updateToggleLabel() {
                    toggleLabel.textContent = toggleSwitch.checked ? 'Activo' : 'Inactivo';
                }

                toggleSwitch.addEventListener('change', updateToggleLabel);
                updateToggleLabel();

                $submitBtn.text('Guardar').removeClass('btn-info').addClass('btn-success');
                $('#userModalLabel').text('Nuevo Usuario');
                $('#select-trabajador-group, #div-office').show();
                $('#trabajador_id').val(null).trigger('change');
                $('#nombres, #apellidos, #dni, #username, #office').val('');

                modal.show();
            });

            $('#users-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');

                $.get(`{{ route('users.show', ':id') }}`.replace(':id', id))
                    .done(function(data) {
                        $form[0].reset();
                        clearErrors();
                        $userId.val(data.id);
                        $submitBtn.text('Actualizar').removeClass('btn-success').addClass('btn-info');
                        $('#userModalLabel').text('Editar Usuario');
                        $('#select-trabajador-group, #div-office').hide();
                        $('#nombres').val(data.person?.name || '');
                        $('#apellidos').val(data.person?.lastname || '');
                        $('#dni').val(data.person?.document_number || '');
                        $('#username').val(data.username || '');
                        $('#email').val(data.email || '');

                        // Configurar toggle correctamente para editar
                        const toggleSwitch = document.getElementById('active');
                        const toggleLabel = document.querySelector('.toggle-label');

                        // Convertir el valor a boolean y establecer el checkbox
                        toggleSwitch.checked = Boolean(data.active);

                        function updateToggleLabel() {
                            toggleLabel.textContent = toggleSwitch.checked ? 'Activo' : 'Inactivo';
                        }

                        // Remover listeners anteriores y agregar nuevo
                        toggleSwitch.removeEventListener('change', updateToggleLabel);
                        toggleSwitch.addEventListener('change', updateToggleLabel);
                        updateToggleLabel();

                        modal.show();
                    })
                    .fail(function() {
                        Swal.fire('Error', 'No se pudo cargar la información del usuario.', 'error');
                    });
            });

            $form.on('submit', function(e) {
                e.preventDefault();
                clearErrors();

                const isUpdate = !!$userId.val();
                const url = isUpdate
                    ? `{{ route('users.update', ':id') }}`.replace(':id', $userId.val())
                    : '{{ route("users.store") }}';
                const method = isUpdate ? 'PUT' : 'POST';

                // Obtener el estado del toggle correctamente
                const toggleSwitch = document.getElementById('active');

                const formData = {
                    _token: '{{ csrf_token() }}',
                    _method: method,
                    email: $('#email').val(),
                    active: toggleSwitch.checked ? 1 : 0, // Convertir boolean a entero
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                };

                if (!isUpdate) {
                    formData.trabajador_id = $('#trabajador_id').val();
                }

                $submitBtn.prop('disabled', true);
                $form.find('button, input, select').prop('disabled', true);
                const originalBtnHtml = $submitBtn.html();
                $submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        modal.hide();
                        table.ajax.reload(null, false);

                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            showErrors(errors);
                        } else {
                            Swal.fire('Error', 'Ocurrió un error inesperado.', 'error');
                        }
                    },
                    complete: function() {
                        $submitBtn.prop('disabled', false).html(originalBtnHtml);
                        $form.find('button, input, select').prop('disabled', false);
                    }
                });
            });

            $('#users-table').on('click', '.btn-delete', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "El usuario será desactivado.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('users.destroy', ':id') }}`.replace(':id', id),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Desactivado',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function() {
                                Swal.fire('Error', 'No se pudo desactivar el usuario.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
