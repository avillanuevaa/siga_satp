@extends('layouts/admin')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Oficinas</h1>
        <div>
            <button id="btnOpenCreateModal" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOfficeModal">
                <i class="fas fa-plus"></i> Crear Oficina
            </button>
        </div>
    </div>
@stop

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <table id="offices-table" class="table table-hover table-bordered nowrap w-100">
                <thead>
                    <tr>
                        <th>Oficina</th>
                        <th>Descripcion</th>
                        <th>Telefono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@include('admin.offices.modals._create')
@include('admin.offices.modals._edit')

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
            const table = $('#offices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route("offices.index") !!}',
                },
                columns: [
                    { data: 'office',      name: 'name',        className: 'text-center align-middle' },
                    { data: 'description', name: 'description', className: 'text-center align-middle' },
                    { data: 'phone',       name: 'phone',       className: 'text-center align-middle' },
                    { data: 'status',      name: 'active',      className: 'text-center align-middle' },
                    { data: 'action',      name: 'action',      orderable: false, searchable: false, className: 'text-center align-middle' },
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Ver columnas',
                        className: 'btn btn-sm btn-secondary',
                    }
                ],
                initComplete: function () {
                    $('#offices-table thead tr').clone(true).appendTo('#offices-table thead');
                    const filterRow = $('#offices-table thead tr:eq(1)');
                    filterRow.find('th')
                        .removeClass('sorting sorting_asc sorting_desc')
                        .off('click')
                        .css('cursor', 'default');

                    filterRow.find('th').each(function (i) {
                        const title = $(this).text().trim();
                        let $container = $('<div class="d-flex align-items-center"></div>');

                        if (['Oficina'].includes(title)) {
                            const $input = $(
                                `<input type="text" class="form-control form-control-sm" placeholder="Buscar ${title}">`
                            );
                            const $btn = $(
                                `<button type="button" class="btn btn-sm btn-light ms-1" title="Limpiar">
                                    <i class="fas fa-times-circle"></i>
                                </button>`
                            );
                            $btn.on('click', () => {
                                $input.val('');
                                table.column(i).search('').draw();
                            });
                            $input.on('keyup change clear', function () {
                                if (table.column(i).search() !== this.value) {
                                    table.column(i).search(this.value).draw();
                                }
                            });
                            $container.append($input, $btn);
                            $(this).html($container);
                            return;
                        }else{
                            $(this).empty();
                        }
                        $(this).html('');
                    });
                }
            });

            // Manejar envío del formulario de crear
            $('#createOfficeForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '{!! route("offices.store") !!}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#createOfficeModal').modal('hide');
                            $('#createOfficeForm')[0].reset();
                            $('.form-control').removeClass('is-invalid');

                            table.ajax.reload(null, false);

                            Swal.fire({
                                title: 'Creado',
                                text: 'La oficina ha sido creada exitosamente.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').text('');

                            $.each(errors, function(field, messages) {
                                const input = $(`#create_${field}`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(messages[0]);
                            });
                        } else {
                            Swal.fire('Error', 'Ha ocurrido un problema al crear la oficina.', 'error');
                        }
                    }
                });
            });

            // Manejar clic en botón editar
            $('#offices-table').on('click', '.btn-edit', function(e) {
                e.preventDefault();

                const url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const office = response.office;

                            $('#edit_office_id').val(office.id);
                            $('#edit_name').val(office.name);
                            $('#edit_code_ue').val(office.code_ue);
                            $('#edit_description').val(office.description);
                            $('#edit_phone').val(office.phone);
                            $('#edit_code_office').val(office.code_office);
                            $('#edit_address').val(office.address);
                            $('#edit_annexed').val(office.annexed);
                            $('#edit_institution_id').val(office.institution_id);
                            $('#edit_goal').val(office.goal);
                            $('#edit_active').prop('checked', office.active == 1);

                            $('#editOfficeModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'No se pudo cargar la información de la oficina.', 'error');
                    }
                });
            });

            // Manejar envío del formulario de editar
            $('#editOfficeForm').on('submit', function(e) {
                e.preventDefault();

                const officeId = $('#edit_office_id').val();
                const formData = new FormData(this);

                $.ajax({
                    url: '{!! route("offices.update", ":id") !!}'.replace(':id', officeId),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#editOfficeModal').modal('hide');
                            $('.form-control').removeClass('is-invalid');

                            table.ajax.reload(null, false);

                            Swal.fire({
                                title: 'Actualizado',
                                text: 'La oficina ha sido actualizada exitosamente.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').text('');

                            $.each(errors, function(field, messages) {
                                const input = $(`#edit_${field}`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(messages[0]);
                            });
                        } else {
                            Swal.fire('Error', 'Ha ocurrido un problema al actualizar la oficina.', 'error');
                        }
                    }
                });
            });

            // Manejar eliminación
            $('#offices-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();

                const button = $(this);
                const url = button.data('url');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción deshabilitará la oficina",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, deshabilitar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.ajax.reload(null, false);

                                    Swal.fire({
                                        title: 'Deshabilitada',
                                        text: 'La oficina ha sido deshabilitada exitosamente.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire('Error', 'No se pudo deshabilitar la oficina.', 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Ha ocurrido un problema.', 'error');
                            }
                        });
                    }
                });
            });

            // Limpiar formularios al cerrar modales
            $('#createOfficeModal').on('hidden.bs.modal', function () {
                $('#createOfficeForm')[0].reset();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });

            $('#editOfficeModal').on('hidden.bs.modal', function () {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });
        });
    </script>
@endpush
