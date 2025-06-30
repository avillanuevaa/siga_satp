@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
    <div class="row">
        <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h1 class="mb-2 mb-sm-0">Documentos SIAF</h1>
            <div class="d-flex flex-column flex-sm-row">
                <a href="{{ route('documentSiafs.importExcel') }}" class="btn btn-success mb-2 mb-sm-0 mr-sm-2"><i class="fas fa-upload"></i> Importar Excel Siaf</a>
                <a href="{{ route('documentSiafs.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo registro manual</a>
            </div>
        </div>
    </div>
@stop

@section('content')

    @if(session('totalUploadedFiles'))
        <div class="alert alert-{{ explode('|', session('totalUploadedFiles'))[0] }}">
            {{ explode('|', session('totalUploadedFiles'))[1] }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Listado de Documentos SIAF</h5>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="siaf-table" class="table table-hover table-bordered dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th class="text-center align-middle">Siaf</th>
                        <th class="text-center align-middle">Fecha Emisión</th>
                        <th class="text-center align-middle">Tipo Doc</th>
                        <th class="text-center align-middle">Serie doc</th>
                        <th class="text-center align-middle">Num doc</th>
                        <th class="text-center align-middle">RUC</th>
                        <th class="text-center align-middle">Total</th>
                        <th class="text-center align-middle">Fecha Pago</th>
                        <th class="text-center align-middle">Origen</th>
                        <th class="text-center align-middle">Estado</th>
                        <th class="text-center align-middle">Acción</th>
                    </tr>
                    </thead>
                </table>
            </div>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .date-filter-container {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .date-filter-input {
            width: 100px;
            font-size: 12px;
        }
        .date-clear-btn {
            padding: 2px 6px;
            font-size: 10px;
        }

        /* Estilos personalizados para DataTable */
        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 6.5 6.5L14 6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            height: calc(1.5em + 0.75rem + 2px);
            min-width: 70px;
            margin-right: 10px;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .dataTables_wrapper .dataTables_length label {
            font-weight: normal;
            text-align: left;
            white-space: nowrap;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Reorganizar la barra de herramientas */
        .dataTables_wrapper .row:first-child .col-sm-12.col-md-6:first-child {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dt-buttons {
            margin-left: 0;
        }

        /* Ajustar el contenedor de búsqueda */
        .dataTables_wrapper .dataTables_filter {
            text-align: right;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .datatable-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .datatable-left-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .datatable-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .datatable-left-controls {
                justify-content: space-between;
            }
        }

        .siaf-filter input,
        .siaf-filter select {
            width: 100%;
            max-width: 100px;
        }

        .date-clear-btn {
            padding: 0.25rem 0.5rem;
        }

        @media (max-width: 950px) {
            .siaf-filter input {
                max-width: 150px;
            }

            .siaf-filter .d-flex {
                flex-direction: column !important;
                align-items: flex-start;
            }

            .siaf-filter .btn {
                margin: 5px 0 0;
            }
        }

        .fixed-siaf {
            min-width: 100px;
            max-width: 100px;
            width: 150px;
            white-space: nowrap;
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#siaf-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route("documentSiafs.index") !!}',
                },
                dom: 'lBfrtip',
                lengthChange: true,
                responsive: true,
                pageLength: 25,
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Ver columnas',
                        className: 'btn btn-sm btn-secondary',
                    }
                ],
                lengthMenu: [[25,50,100], [25,50,100]],
                columnDefs: [
                    { targets: 0, className: 'siaf-filter text-center fixed-siaf', width: '150px' },
                    { targets: [4,5], className: 'siaf-filter text-center', width: '100px' },
                    { targets: 6, className: 'text-end', width: '80px' },
                    { targets: -1, orderable: false, searchable: false, width: '80px' }
                ],
                columns: [
                    { data:'siaf', name:'siaf', className: 'text-center align-middle' },
                    { data:'fecha_emision', name:'fecha_emision', className: 'text-center align-middle' },
                    { data:'tipo_doc', name:'tipo_doc', className: 'text-center align-middle' },
                    { data:'serie_doc', name:'serie_doc', className: 'text-center align-middle' },
                    { data:'number', name:'number', className: 'text-center align-middle' },
                    { data:'ruc', name:'ruc', className: 'text-center align-middle' },
                    { data:'total', name:'total', className: 'text-center align-middle' },
                    { data:'fecha_pago', name:'fecha_pago', className: 'text-center align-middle' },
                    { data:'origen', name:'origen', className: 'text-center align-middle' },
                    { data:'estado', name:'estado', className: 'text-center align-middle'},
                    { data:'action', name:'action', orderable:false, searchable:false },
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>',
                    lengthMenu: "Mostrar _MENU_"
                },
                initComplete: function () {
                    $('#siaf-table thead tr').clone(true).appendTo('#siaf-table thead');
                    const filterRow = $('#siaf-table thead tr:eq(1)');

                    filterRow.find('th')
                        .removeClass('sorting sorting_asc sorting_desc')
                        .off('click')
                        .css('cursor', 'default');

                    filterRow.find('th').each(function (i) {
                        const title = $(this).text().trim();
                        let container = $('<div class="d-flex align-items-center"></div>');

                        if (['Siaf', 'Tipo Doc', 'Num doc','RUC'].includes(title)) {
                            const $searchColumnInput = $(
                                `<input type="text" class="form-control form-control-sm" placeholder="Buscar">`
                            );
                            const $btnClean = $(
                                `<button type="button" class="btn btn-sm btn-light ms-1" title="Limpiar">
                                    <i class="fas fa-times-circle"></i>
                                </button>`
                            );

                            $btnClean.on('click', () => {
                                $searchColumnInput.val('');
                                table.column(i).search('').draw();
                            });

                            $searchColumnInput.on('keyup change clear', function () {
                                if (table.column(i).search() !== this.value) {
                                    table.column(i).search(this.value).draw();
                                }
                            });

                            container.append($searchColumnInput, $btnClean);
                            $(this).html(container);
                            return;
                        }

                        if (title === 'Fecha Emisión') {
                            const dateContainer = $('<div class="date-filter-container"></div>');
                            const $dateFromInput = $(
                                `<input
                                    type="text"
                                    class="form-control form-control-sm date-filter-input"
                                    placeholder="Desde" readonly
                                >`
                            );
                            const $dateToInput = $(
                                `<input
                                    type="text"
                                    class="form-control form-control-sm date-filter-input"
                                    placeholder="Hasta" readonly
                                >`
                            );
                            const $btnCleanDate = $(
                                `<button
                                    type="button"
                                    class="btn btn-sm btn-light date-clear-btn"
                                    title="Limpiar fechas">
                                    <i class="fas fa-times"></i>
                                </button>`
                            );

                            const fromPicker = flatpickr($dateFromInput[0], {
                                dateFormat: "d/m/Y",
                                locale: "es",
                                allowInput: false,
                                onChange: function(selectedDates, dateStr) {
                                    if (dateStr) {
                                        toPicker.set('minDate', selectedDates[0]);
                                    }
                                    filterByDateRange();
                                }
                            });

                            const toPicker = flatpickr($dateToInput[0], {
                                dateFormat: "d/m/Y",
                                locale: "es",
                                allowInput: false,
                                onChange: function(selectedDates, dateStr) {
                                    if (dateStr) {
                                        fromPicker.set('maxDate', selectedDates[0]);
                                    }
                                    filterByDateRange();
                                }
                            });

                            function filterByDateRange() {
                                const fromDate = $dateFromInput.val();
                                const toDate = $dateToInput.val();

                                let searchValue = '';
                                if (fromDate && toDate) {
                                    searchValue = fromDate + '|' + toDate;
                                } else if (fromDate) {
                                    searchValue = fromDate + '|';
                                } else if (toDate) {
                                    searchValue = '|' + toDate;
                                }

                                table.column(i).search(searchValue).draw();
                            }

                            $btnCleanDate.on('click', () => {
                                fromPicker.clear();
                                toPicker.clear();
                                fromPicker.set('minDate', null);
                                fromPicker.set('maxDate', null);
                                toPicker.set('minDate', null);
                                toPicker.set('maxDate', null);
                                table.column(i).search('').draw();
                            });

                            dateContainer.append($dateFromInput, $dateToInput, $btnCleanDate);
                            $(this).html(dateContainer);
                            return;
                        }

                        if (title === 'Origen') {
                            $(this).html(`
                                <select class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    <option value="1">Importado</option>
                                    <option value="2">Manual</option>
                                </select>
                            `).find('select')
                                .on('change', function () {
                                    table.column(8).search(this.value).draw();
                                });
                            return;
                        }

                        if (title === 'Estado') {
                            $(this).html(`
                                <select class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">Registrado</option>
                                    <option value="3">Cerrado</option>
                                </select>
                            `).find('select')
                                .on('change', function () {
                                    table.column(9).search(this.value).draw();
                                });
                            return;
                        }

                        $(this).html('');
                    });
                }
            });

            table.on('column-visibility.dt', function (e, settings, column, state) {
                const filterRow = $('#siaf-table thead tr:eq(1)');
                const filterCell = filterRow.find('th').eq(column);

                if (state) {
                    filterCell.show();
                } else {
                    filterCell.hide();
                }
            });

            $('#siaf-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Desea eliminar el siaf?',
                    text: 'Esta acción es irreversible',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, eliminar!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ route('documentSiafs.destroy', ':id') }}".replace(':id', id),
                            data: {"_token": "{{ csrf_token() }}"},
                            success: function() { location.reload(); },
                            error: function() { Swal.fire('Uy!', 'Algo ha ido mal.', 'error'); }
                        });
                    }
                });
            });
        });
    </script>
@endpush
