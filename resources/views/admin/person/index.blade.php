@extends('layouts/admin')

@section('content_header')
<div class="row">
  <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <h1 class="mb-2 mb-sm-0">Trabajadores</h1>
    <div class="d-flex flex-column flex-sm-row">
      <a href="{{ route('persons.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo trabajador</a>
    </div>
  </div>
</div>
@stop

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Listado</h5>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body table-responsive">
    <table id="workers-table" class="table table-hover">
      <thead>
        <tr>
          <th class="text-center align-middle">Dni</th>
          <th class="text-center align-middle">Nombre</th>
          <th class="text-center align-middle">Apellido</th>
          <th class="text-center align-middle">Oficina</th>
          <th class="text-center align-middle">Estado</th>
          <th class="text-center align-middle">Acción</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@include('admin.partials.session-message')

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
            const table = $('#workers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route("persons.index") !!}',
                },
                columns: [
                    { data: 'dni',      name: 'people.document_number', className: 'text-center align-middle' },
                    { data: 'nombre',   name: 'person_name',            className: 'text-center align-middle' },
                    { data: 'apellido', name: 'people.lastname',        className: 'text-center align-middle' },
                    { data: 'oficina',  name: 'office_name',            className: 'text-center align-middle' },
                    { data: 'estado',   name: 'active',                 orderable: false, searchable: false, className: 'text-center align-middle' },
                    { data: 'action',   name: 'action',                 orderable: false, searchable: false, className: 'text-center align-middle' },
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-sm btn-secondary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-sm btn-secondary'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Ver columnas',
                        className: 'btn btn-sm btn-secondary',
                    }
                ],
                initComplete: function () {
                    $('#workers-table thead tr').clone(true).appendTo('#workers-table thead');
                    const filterRow = $('#workers-table thead tr:eq(1)');
                    filterRow.find('th')
                        .removeClass('sorting sorting_asc sorting_desc')
                        .off('click')
                        .css('cursor', 'default');

                    filterRow.find('th').each(function (i) {
                        const title = $(this).text().trim();
                        let $container = $('<div class="d-flex align-items-center"></div>');

                        if (['Dni','Nombre','Apellido','Oficina'].includes(title)) {
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

            $('#workers-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();

                const button = $(this);
                const url    = button.data('url');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text:  "¡No podrás revertir esto!",
                    icon:  'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton:  'btn btn-secondary'
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
                                    $('#workers-table').DataTable().ajax.reload(null, false);

                                    Swal.fire({
                                        title: 'Eliminado',
                                        text:  'El registro ha sido eliminado.',
                                        icon:  'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Ha ocurrido un problema.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

