@extends('layouts/admin')

@section('content_header')
<div class="row">
  <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <h1 class="mb-2 mb-sm-0">Clasificadores</h1>
    <div class="d-flex flex-column flex-sm-row">
      <a href="{{ route('financialClassifiers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo clasificador</a>
    </div>
  </div>
</div>
@stop

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <table id="classifiers-table" class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th class="text-center align-middle">Tipo</th>
                    <th class="text-center align-middle">Código</th>
                    <th class="text-center align-middle">Nombre</th>
                    <th class="text-center align-middle">Estado</th>
                    <th class="text-center align-middle">Acción</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@include('admin.partials.session-message')

@push('css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colvis/1.1.2/css/dataTables.colVis.css" rel="stylesheet">
    <style>
        .inactive-row {
            background-color: #ffe6e6 !important;
        }
    </style>
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
            const table = $('#classifiers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route("financialClassifiers.index") !!}',
                    data: function (d) {
                        d.name = $('#search-name').val();
                        d.code = $('#search-code').val();
                    }
                },
                columns: [
                    { data: 'tipo',   name: 'T2.cParNombre', className: 'text-center align-middle' },
                    { data: 'codigo', name: 'T1.code',       className: 'text-center align-middle' },
                    { data: 'nombre', name: 'T1.name',       className: 'text-center align-middle' },
                    { data: 'estado', name: 'active',        orderable: false, searchable: false, className: 'text-center align-middle' },
                    { data: 'action', name: 'action',        orderable: false, searchable: false, className: 'text-center align-middle' },
                    { data: 'active', name: 'active',        visible: false }
                ],
                createdRow: function(row, data) {
                    if (data.active === 0) {
                        $(row).addClass('inactive-row');
                    }
                },
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
                    $('#classifiers-table thead tr').clone(true).appendTo('#classifiers-table thead');
                    const filterRow = $('#classifiers-table thead tr:eq(1)');
                    filterRow.find('th')
                        .removeClass('sorting sorting_asc sorting_desc')
                        .off('click')
                        .css('cursor', 'default');

                    filterRow.find('th').each(function (i) {
                        const title = $(this).text().trim();
                        let $container = $('<div class="d-flex align-items-center"></div>');

                        if (['Tipo','Código','Nombre'].includes(title)) {
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
                        }

                        if (title === 'Estado') {
                            $(this).html(`
                                <select class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            `).find('select')
                                .on('change', function () {
                                    table.column(5).search(this.value).draw();
                                });
                            return;
                        }

                        $(this).html('');
                    });
                }
            });
        });
    </script>
@endpush
