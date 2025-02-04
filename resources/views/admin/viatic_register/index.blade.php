@extends('layouts/admin')
@section('plugins.TempusDominusBs4', true)
@section('content_header')
<div class="row">
  <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <h1 class="mb-2 mb-sm-0">Viaticos</h1>
    <div class="d-flex flex-column flex-sm-row">
      <a href="{{ route('viaticRegisters.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Aperturar Viaticos</a>
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
  <!-- /.card-header -->
  <div class="card-body table-responsive">
    <table class="table table-striped table-hover">
      <caption>Listado de viaticos en total: {{  $items->total() }}</caption>
      <thead>
        <tr>
          <th class="text-center align-middle">Año</th>
          <th class="text-center align-middle">Número</th>
          <th class="text-center align-middle">RGA</th>
          <th class="text-center align-middle">DNI</th>
          <th class="text-center align-middle">Responsable</th>
          <th class="text-center align-middle">Monto apertura</th>
          <th class="text-center align-middle">Fecha apertura</th>
          <th class="text-center align-middle">Fecha de cierre</th>
          <th class="text-center align-middle">Estado</th>
          <th class="text-center align-middle">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
        <tr>
          <td class="text-center align-middle">{{ $item->year }}</td>
          <td class="text-center align-middle">{{ $item->number }}</td>
          <td class="text-left align-middle">{{ $item->settlement?->authorization_detail }}</td>
          <td class="text-center align-middle">{{ $item->user?->person?->document_number }}</td>
          <td style="vertical-align:middle">{{ $item->user?->person?->name }} {{ $item->user?->person?->lastname }}</td>
          <td class="text-center text-nowrap  align-middle">S/. {{ number_format($item->settlement?->approved_amount, 2) }}</td>
          <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->opening_date)->format('d/m/Y') }}</td>
          <td class="text-center align-middle">{{ $item->closing_date ? \Carbon\Carbon::parse($item->closing_date)->format('d/m/Y') : '' }}</td>
          <td class="text-center align-middle">
            <span @class(['badge', 'bg-danger' => $item->closed === 1, 'bg-success' => $item->closed === 0])>
              <span class="px-3">{{ $item->closed == 1 ? 'Cerrada' : ($item->closed == 0 ? 'Abierta' : '') }}</span>
            </span>
          </td>
          <td class="align-middle">
            <div class="btn-group">
              <a href="{{ route('viaticRegisterDetails.index', $item->id) }}" class="btn btn-info"><span class="fa fa-arrow-right"></span></a>
              @if($item?->closed != 1) <x-adminlte-button type="button" theme="danger" icon="fa fa-times"  data-toggle="modal" data-id="{{ $item->id }}" data-target="#onCloseViaticRegister" /> @endif
              <button type="button" class="btn btn-warning btn-onPrint" data-id="{{ $item->id }}" data-number="{{ $item->number }}" "><i class="fa fa-print"></i></button>
              <button type="button" class="btn btn-info btn-onPrintFinal" data-id="{{ $item->id }}" data-number="{{ $item->number }}" "><i class="fa fa-print"></i></button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-2">
      {{ $items->appends(request()->all())->onEachSide(2)->links('admin.partials.pagination') }}
    </div>
  </div>
</div>
@include('admin.viatic_register._modal_form_close')
@include('admin.partials.session-message')
@stop

@push('js')
<script type="text/javascript">
  document.querySelectorAll(".btn-onPrint").forEach(element =>
    element.addEventListener("click", function() {

      const objData = {
        viatic_register_id: element.dataset.id
      };

      let queryParams = new URLSearchParams(objData).toString();

      fetch(`{{ route("reports.viaticRegisterDetails") }}?${queryParams}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.status}`);
          }
          return response.blob();
        })
        .then(blob => {
          const number = element.dataset.number;

          const file = new Blob([blob], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          });

          const a = document.createElement('a');
          a.style.display = 'none';
          document.body.appendChild(a);
          a.download = `excelViatico_${number}.xlsx`;
          a.href = URL.createObjectURL(file);
          a.target = '_blank';
          a.click();
          document.body.removeChild(a);
        })
        .catch(error => {
          console.error('Error:', error);
        });

    })
  );

  document.querySelectorAll(".btn-onPrintFinal").forEach(element =>
    element.addEventListener("click", function() {

      const objData = {
        viatic_register_id: element.dataset.id
      };

      let queryParams = new URLSearchParams(objData).toString();

      fetch(`{{ route("reports.viaticRegisterReport") }}?${queryParams}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.status}`);
          }
          return response.blob();
        })
        .then(blob => {
          const number = element.dataset.number;

          const file = new Blob([blob], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          });

          const a = document.createElement('a');
          a.style.display = 'none';
          document.body.appendChild(a);
          a.download = `informeFinalViatico_${number}.xlsx`;
          a.href = URL.createObjectURL(file);
          a.target = '_blank';
          a.click();
          document.body.removeChild(a);
        })
        .catch(error => {
          console.error('Error:', error);
        });

    })
  );


  $('#onCloseViaticRegister').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const dataId = button.data('id');

    const formulario = $(this).find('#dataFormModalClose');
    formulario.find('input').val('');

    fetch("{{ route('viaticRegisters.show', ':id') }}".replace(':id', dataId))
      .then(response => response.json())
      .then(data => {
        formulario.find('#viatic_register_id').val(data.viatic_register_id);
        formulario.find('#year').val(data.year);
        formulario.find('#responsible').val(data.responsible);
        formulario.find('#opening_date').val(data.opening_date);
        formulario.find('#number').val(data.number);
        formulario.find('#approved_amount').val(data.approved_amount);
        formulario.find('#authorization_date').val(data.authorization_date);
        formulario.find('#authorization_detail').val(data.authorization_detail);
        formulario.find('#reason').val(data.reason);
        formulario.find('#siaf_date').val(data.siaf_date);
        formulario.find('#siaf_number').val(data.siaf_number);
        formulario.find('#voucher_date').val(data.voucher_date);
        formulario.find('#voucher_number').val(data.voucher_number);
        formulario.find('#order_pay_electronic_date').val(data.order_pay_electronic_date);
        formulario.find('#viatic_type').val(data.viatic_type);
        formulario.find('#destination').val(data.destination);
        formulario.find('#means_of_transport').val(data.means_of_transport);
        formulario.find('#format_number_two').val(data.format_number_two);
        formulario.find('#departure_date').val(data.departure_date);
        formulario.find('#number_days').val(data.number_days);
        formulario.find('#return_date').val(data.return_date);

      })
      .catch(error => {
        console.error('Error al obtener los datos:', error);
      });
  });
</script>
@endpush