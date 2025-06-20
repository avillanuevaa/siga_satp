@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
    <div class="row">
        <div class="col-12 d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center">
            <h1 class="mb-3 mb-lg-0">Documentos SIAF</h1>
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-lg-auto">
                <a href="{{ route('documentSiafs.importExcel') }}" class="btn btn-success">
                    <i class="fas fa-upload"></i>
                    <span class="d-none d-sm-inline">Importar Excel Siaf</span>
                    <span class="d-sm-none">Importar</span>
                </a>
                <a href="{{ route('documentSiafs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span class="d-none d-sm-inline">Nuevo registro manual</span>
                    <span class="d-sm-none">Nuevo</span>
                </a>
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
        <div class="card-header">
            <h5 class="card-title">Listado de Documentos SIAF</h5>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0 p-md-3">
            <!-- Contenedor de tabla responsiva -->
            <div class="table-responsive">
                <table id="siaf-table" class="table table-hover table-bordered w-100">
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
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colvis/1.1.2/css/dataTables.colVis.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .table-responsive {
            border-radius: 0.375rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 0.5rem !important;
            }

            .table {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                min-width: 80px;
            }

            .btn-sm {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
        }

        .date-filter-container {
            display: flex;
            align-items: center;
            gap: 3px;
            flex-wrap: wrap;
            min-width: 200px;
        }

        .date-filter-input {
            width: 80px;
            font-size: 11px;
            padding: 0.25rem;
        }

        .date-clear-btn {
            padding: 0.25rem 0.5rem;
            font-size: 10px;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .date-filter-container {
                flex-direction: column;
                gap: 2px;
                min-width: 120px;
            }

            .date-filter-input {
                width: 100%;
                font-size: 10px;
            }
        }

        .dataTables_wrapper {
            overflow-x: auto;
        }

        .dataTables_wrapper .dataTables_length {
            float: none;
            margin-bottom: 15px;
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

        .dataTables_wrapper .dataTables_filter {
            text-align: right;
            margin-bottom: 15px;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            max-width: 200px;
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
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .dataTables_wrapper .row {
                flex-direction: column;
                gap: 10px;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                text-align: center;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dt-buttons {
                text-align: center;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dt-buttons .btn {
                margin: 2px;
                font-size: 0.8rem;
            }

            .datatable-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .datatable-left-controls {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 576px) {
            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                max-width: none;
                margin-left: 0;
                margin-top: 5px;
            }

            .dataTables_wrapper .dataTables_filter label {
                display: block;
                text-align: center;
            }

            .dt-buttons {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 5px;
            }

            .dt-buttons .btn {
                flex: 1;
                min-width: 120px;
                font-size: 0.75rem;
            }
        }

        .dtr-details {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .dtr-details li {
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem 0;
        }

        .dtr-details li:last-child {
            border-bottom: none;
        }

        .dtr-title {
            font-weight: bold;
            color: #495057;
            min-width: 100px;
            display: inline-block;
        }

        .dtr-control {
            cursor: pointer;
            text-align: center;
        }

        .dtr-control:before {
            content: '+';
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            line-height: 20px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
        }

        .dtr-control.dtr-control-show:before {
            content: '-';
        }

        @media (max-width: 768px) {
            .form-control-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            .btn-sm {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
        }

        .dataTables_info {
            font-size: 0.875rem;
        }

        @media (max-width: 576px) {
            .dataTables_info {
                font-size: 0.75rem;
                text-align: center;
                margin-bottom: 10px;
            }

            .dataTables_paginate {
                text-align: center;
            }

            .dataTables_paginate .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .dataTables_paginate .page-link {
                font-size: 0.8rem;
                padding: 0.375rem 0.75rem;
            }
        }

        .dataTables_scrollBody {
            border-bottom: 1px solid #dee2e6;
        }

        .dataTables_scrollHead,
        .dataTables_scrollFoot {
            border-bottom: 1px solid #dee2e6;
        }

        .btn-group-vertical .btn {
            margin-bottom: 2px;
        }

        .btn-group-vertical .btn:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 576px) {
            .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                margin-bottom: 2px;
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
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
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6">>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                lengthChange: true,
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                                return col.hidden ?
                                    '<li data-dtr-index="' + col.columnIndex + '" data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                    '<span class="dtr-title">' + col.title + ':</span> ' +
                                    '<span class="dtr-data">' + col.data + '</span>' +
                                    '</li>' :
                                    '';
                            }).join('');

                            return data ? '<ul class="dtr-details">' + data + '</ul>' : false;
                        }
                    }
                },
                pageLength: 25,
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> <span class="d-none d-md-inline">Ver columnas</span>',
                        className: 'btn btn-sm btn-secondary',
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Excel</span>',
                        className: 'btn btn-sm btn-success',
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline">PDF</span>',
                        className: 'btn btn-sm btn-danger',
                        orientation: 'landscape',
                        pageSize: 'A4'
                    }
                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                columnDefs: [
                    {
                        targets: [0],
                        responsivePriority: 1,
                        className: 'text-center align-middle'
                    },
                    {
                        targets: [1],
                        responsivePriority: 2,
                        className: 'text-center align-middle'
                    },
                    {
                        targets: [4],
                        responsivePriority: 3,
                        className: 'text-center align-middle'
                    },
                    {
                        targets: [6],
                        responsivePriority: 4,
                        className: 'text-center align-middle'
                    },
                    {
                        targets: [9],
                        responsivePriority: 5,
                        className: 'text-center align-middle'
                    },
                    {
                        targets: [10],
                        responsivePriority: 1,
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle'
                    },
                    {
                        targets: [2, 3, 5, 7, 8],
                        responsivePriority: 10000,
                        className: 'text-center align-middle'
                    }
                ],
                columns: [
                    { data:'siaf', name:'siaf' },
                    { data:'fecha_emision', name:'fecha_emision' },
                    { data:'tipo_doc', name:'tipo_doc' },
                    { data:'serie_doc', name:'serie_doc' },
                    { data:'number', name:'number' },
                    { data:'ruc', name:'ruc' },
                    { data:'total', name:'total' },
                    { data:'fecha_pago', name:'fecha_pago' },
                    { data:'origen', name:'origen' },
                    { data:'estado', name:'estado' },
                    { data:'action', name:'action' },
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>',
                    lengthMenu: "Mostrar _MENU_",
                    responsive: {
                        details: {
                            display: {
                                modal: {
                                    header: function (row) {
                                        var data = row.data();
                                        return 'Detalles de SIAF: ' + data.siaf;
                                    }
                                }
                            }
                        }
                    }
                },
                initComplete: function () {
                    if ($(window).width() > 768) {
                        $('#siaf-table thead tr').clone(true).appendTo('#siaf-table thead');
                        const filterRow = $('#siaf-table thead tr:eq(1)');

                        filterRow.find('th')
                            .removeClass('sorting sorting_asc sorting_desc')
                            .off('click')
                            .css('cursor', 'default');

                        filterRow.find('th').each(function (i) {
                            const title = $(this).text().trim();
                            let container = $('<div class="d-flex align-items-center"></div>');

                            if (['Siaf','Num doc','RUC'].includes(title)) {
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
                }
            });

            table.on('column-visibility.dt', function (e, settings, column, state) {
                const filterRow = $('#siaf-table thead tr:eq(1)');
                if (filterRow.length) {
                    const filterCell = filterRow.find('th').eq(column);
                    if (state) {
                        filterCell.show();
                    } else {
                        filterCell.hide();
                    }
                }
            });

            $('#siaf-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: '¿Desea eliminar el siaf?',
                    text: 'Esta acción es irreversible',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'No',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ route('documentSiafs.destroy', ':id') }}".replace(':id', id),
                            data: {"_token": "{{ csrf_token() }}"},
                            success: function() {
                                table.ajax.reload();
                                Swal.fire({
                                    title: 'Eliminado!',
                                    text: 'El registro ha sido eliminado.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function() {
                                Swal.fire('¡Ups!', 'Algo ha ido mal.', 'error');
                            }
                        });
                    }
                });
            });

            // Actualizar filtros cuando cambia el tamaño de ventana
            $(window).on('resize', function() {
                table.columns.adjust().responsive.recalc();
            });
        });
    </script>
@endpush
