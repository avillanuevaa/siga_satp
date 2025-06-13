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


    <script>
        $(document).ready(function() {
            const table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("users.index") !!}',
                columns: [
                    { data: 'id' },
                    { data: 'dni' },
                    { data: 'user' },
                    { data: 'nombres' },
                    { data: 'apellidos' },
                    { data: 'email' },
                    { data: 'telefono' },
                    { data: 'rol' },
                    { data: 'created_at' },
                    { data: 'action', orderable: false, searchable: false },
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: '<i class="fas fa-copy"></i> Copiar (todo)',
                        className: 'btn btn-secondary btn-sm',
                        action: function () {
                            const btnNode = this.node();
                            const originalText = btnNode.html();

                            btnNode.html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

                            fetch('{{ route("users.exportCopy") }}')
                                .then(response => response.text())
                                .then(text => {
                                    return navigator.clipboard.writeText(text);
                                })
                                .then(() => {
                                    alert('Todos los registros fueron copiados al portapapeles.');
                                })
                                .catch(() => {
                                    alert('Hubo un error al copiar los datos.');
                                })
                                .finally(() => {
                                    btnNode.html(originalText);
                                });
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir (todo)',
                        className: 'btn btn-dark btn-sm',
                        action: function() {
                            window.open('{{ route("users.exportPrint") }}', '_blank');
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columnas',
                        className: 'btn btn-warning btn-sm'
                    },
                    {
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success',
                        action: function () {
                            window.location.href = '{{ route('users.exportExcel') }}';
                        }
                    },
                    {
                        text: '<i class="fas fa-file-csv"></i> CSV (todo)',
                        className: 'btn btn-info btn-sm',
                        action: function() {
                            window.location.href = '{{ route("users.exportCsv") }}';
                        }
                    },
                    {
                        text: '<i class="fas fa-file-pdf"></i> PDF (todo)',
                        className: 'btn btn-danger btn-sm',
                        action: function() {
                            window.open('{{ route("users.exportPdf") }}', '_blank');
                        }
                    },
                ]
            });

            const modal = new bootstrap.Modal(
                document.getElementById('userModal'),
                { backdrop: 'static', keyboard: false }
            );

            // 1) Abrir en CREAR
            $('#btnOpenCreateModal').on('click', function () {
                $('#userForm')[0].reset();
                $('#user_id').val('');
                $('#userModalLabel').text('Nuevo Usuario');
                $('#select-trabajador-group').show();
                $('#div-office').show();
                $('#trabajador_id').val(null).trigger('change');
                modal.show();
            });

            // 2) Abrir en EDITAR
            $('#users-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const url = '{{ route("users.show", ":id") }}'.replace(':id', id);

                $.get(url, function(data) {
                    console.log(data);
                    $('#user_id').val(data.id);
                    $('#userModalLabel').text('Editar Usuario');
                    $('#select-trabajador-group').hide();
                    $('#nombres').val(data.person.name);
                    $('#apellidos').val(data.person.lastname);
                    $('#dni').val(data.person.document_number);
                    $('#div-office').hide();
                    $('#username').val(data.username);
                    $('#email').val(data.email);
                    $('#password').val('');
                    $('#password_confirmation').val('');

                    modal.show();
                }).fail(() => {
                    alert('No se encontró el usuario.');
                });
            });

            $('#trabajador_id').select2({
                dropdownParent: $('#userModal'),
                placeholder: 'Buscar trabajador',
                ajax: {
                    url: '{{ route("persons.search") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults(data) {
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
                console.log("El id seleccionado es: ", id);

                $.get(`{{ route('persons.searchById') }}?id=${id}`, function(person) {
                    $('#nombres').val(person.name);
                    $('#apellidos').val(person.lastname);
                    $('#dni').val(person.document_number);
                    $('#username').val(person.document_number);
                    $('#office').val(person.office?.name ?? '');
                });
            });

            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                const password = $('#password').val();
                const confirm = $('#password_confirmation').val();
                if (password !== confirm) {
                    alert('Las contraseñas no coinciden.');
                    return;
                }

                const formData = {
                    trabajador_id: $('#trabajador_id').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route("users.store") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        modal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            timer: 2500,
                            showConfirmButton: false
                        });
                        $('#users-table').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let messages = '';
                            for (let field in errors) {
                                messages += errors[field].join(', ') + '\n';
                            }
                            alert(messages);
                        } else {
                            alert('Ocurrió un error al guardar el usuario.');
                        }
                    }
                });

            });

            $('#users-table').on('click', '.btn-delete', function () {
                if (!confirm('¿Eliminar este usuario?')) return;
                let id = $(this).data('id');
                $.ajax({
                    url: `/admin/security/users/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success() { table.ajax.reload(); },
                    error()   { alert('No se pudo eliminar.'); }
                });
            });
        });
    </script>
@endpush
