@extends('layouts/admin')
@section('plugins.TempusDominusBs4', true)
@section('content_header')
<div class="row">
  <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <h1 class="mb-2 mb-sm-0">Caja chica</h1>
    <div class="d-flex flex-column flex-sm-row">
      <a href="{{ route('cashRegisters.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Aperturar caja</a>
    </div>
  </div>
</div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Listado de Caja chica</h5>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <table id="cash-table" class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center align-middle">Año</th>
                        <th class="text-center align-middle">Número</th>
                        <th class="text-center align-middle">DNI</th>
                        <th class="text-center align-middle">Responsable</th>
                        <th class="text-center align-middle">Monto apertura</th>
                        <th class="text-center align-middle">Fecha apertura</th>
                        <th class="text-center align-middle">Fecha de cierre</th>
                        <th class="text-center align-middle">Estado</th>
                        <th class="text-center align-middle">Acción</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('admin.cash_register._modal_form_close')
    @include('admin.partials.session-message')
@stop

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
            const table = $('#cash-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route("cashRegisters.index") !!}',
                },
                columns: [
                    { data: 'year',             name: 'year',           className: 'text-center align-middle' },
                    { data: 'number',           name: 'number',         className: 'text-center align-middle' },
                    { data: 'document_number',  name: 'document_number',className: 'text-center align-middle' },
                    { data: 'responsible',      name: 'responsible',    className: 'text-center align-middle' },
                    { data: 'amount',           name: 'amount',         className: 'text-center align-middle' },
                    { data: 'opening_date',     name: 'opening_date',   className: 'text-center align-middle' },
                    { data: 'close_date',       name: 'closing_date',   className: 'text-center align-middle' },
                    { data: 'status',           name: 'status',         orderable: false, searchable: false, className: 'text-center align-middle' },
                    { data: 'action',           name: 'action',         orderable: false, searchable: false, className: 'text-center align-middle' },
                ],
                createdRow: function(row, data) {
                    if (data.active === 0) {
                        $(row).addClass('inactive-row');
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'colvis', text: '<i class="fas fa-columns"></i> Ver columnas', className: 'btn btn-sm btn-secondary' },
                ],
                initComplete: function () {
                    $('#cash-table thead tr').clone(false).appendTo('#cash-table thead');
                    const filterRow = $('#cash-table thead tr:eq(1)');
                    filterRow.find('th')
                        .removeClass('sorting sorting_asc sorting_desc')
                        .off('click')
                        .css('cursor', 'default');

                    filterRow.find('th').each(function (i) {
                        const title = $(this).text().trim();
                        if (['Año', 'DNI', 'Responsable'].includes(title)) {
                            const $input = $(`<input type="text" class="form-control form-control-sm" placeholder="Buscar ${title}">`);
                            const $btn   = $(`<button type="button" class="btn btn-sm btn-light ms-1" title="Limpiar"><i class="fas fa-times-circle"></i></button>`);

                            $btn.on('click', () => {
                                $input.val('');
                                table.column(i).search('').draw();
                            });
                            $input.on('keyup change clear', function () {
                                if (table.column(i).search() !== this.value) {
                                    table.column(i).search(this.value).draw();
                                }
                            });

                            $(this).html($('<div class="d-flex align-items-center"></div>').append($input, $btn));
                            return;
                        }

                        if (title === 'Estado') {
                            const $select = $(`
                                <select class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    <option value="0">Abierta</option>
                                    <option value="1">Cerrada</option>
                                </select>
                            `);

                            $select.on('change', function () {
                                table.column(7).search(this.value).draw();
                            });
                            $(this).html($select);
                            return;
                        }

                        // Resto sin filtro
                        $(this).html('');
                    });
                }
            });

            // Botón Eliminar (igual que antes)
            $('#cash-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const url = $(this).data('url');
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
                            url, type: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function(response) {
                                if (response.success) {
                                    table.ajax.reload(null, false);
                                    Swal.fire({ title: 'Eliminado', text: 'El registro ha sido eliminado.', icon: 'success', timer: 2000, showConfirmButton: false });
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                                }
                            },
                            error: function() { Swal.fire('Error', 'Ha ocurrido un problema.', 'error'); }
                        });
                    }
                });
            });

            // Delegación para el botón “Imprimir”
            $('#cash-table').on('click', '.btn-onPrint', function() {
                const $btn        = $(this);
                const originalHtml = $btn.html();

                // Mostrar spinner y deshabilitar
                $btn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i>');

                const id     = $btn.data('id');
                const number = $btn.data('number');
                const params = new URLSearchParams({ cash_register_id: id }).toString();

                fetch(`{{ route("reports.cashRegisterDetails") }}?${params}`)
                    .then(r => {
                        if (!r.ok) throw new Error(`Status ${r.status}`);
                        return r.blob();
                    })
                    .then(blob => {
                        const file = new Blob([blob], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        document.body.appendChild(a);
                        a.download = `excelCajaChica_${number}.xlsx`;
                        a.href = URL.createObjectURL(file);
                        a.click();
                        document.body.removeChild(a);
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'No se pudo generar el reporte.', 'error');
                    })
                    .finally(() => {
                        // Restaurar botón
                        $btn.prop('disabled', false)
                            .html(originalHtml);
                    });
            });

            // Modal Cerrar caja (igual que antes)
            $('#onCloseCashRegister').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const dataId = button.data('id');
                const form   = $(this).find('#dataFormModalClose').find('input').val('');

                fetch("{{ route('cashRegisters.show', ':id') }}".replace(':id', dataId))
                    .then(r => r.json())
                    .then(data => {
                        form.filter('#cash_register_id').val(data.cash_register_id);
                        form.filter('#year').val(data.year);
                        form.filter('#number').val(data.number);
                        form.filter('#responsible').val(data.responsible);
                        form.filter('#opening_date').val(data.opening_date);
                        form.filter('#amount').val(data.amount);
                    })
                    .catch(console.error);
            });

        });
    </script>

@endpush
