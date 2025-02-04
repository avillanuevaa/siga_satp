@extends('layouts/admin')

@section('content_header')
<div class="row">
  <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <h1 class="mb-2 mb-sm-0">Solicitudes</h1>
    <div class="d-flex flex-column flex-sm-row">
      <a href="{{ route('requestFiles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva Solicitud</a>
    </div>
  </div>
</div>
@stop

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Búscar:</h3>
  </div>
  <div class="card-body">
    @include('admin.request_file._search')
  </div>
</div>
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
      <caption>Listado de solicitudes en total: {{  $items->total() }}</caption>
      <thead>
        <tr>
          <th class="text-center align-middle">Año</th>
          <th class="text-center align-middle">DNI</th>
          <th class="text-center align-middle">Nombres y Apellidos</th>
          <th class="text-center align-middle">Número</th>
          <th class="text-center align-middle">Tipo</th>
          <th class="text-center align-middle">Fecha</th>
          <th class="text-center align-middle">Rendición <br/>(VB)</th>
          <th class="text-center align-middle">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
        <tr>
          <td class="text-center align-middle">{{ $item->year }}</td>
          <td class="text-center align-middle">{{ $item->person?->document_number }}</td>
          <td style="vertical-align:middle">{{ $item->person?->name }} {{ $item->person?->lastname }}</td>
          <td class="text-center align-middle">{{ $item->number_correlative }}</td>
          <td class="text-center align-middle">{{ $item->requestType->cParNombre }}</td>
          <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->request_date)->format('d/m/Y') }}</td>
          <td class="text-center align-middle">
            <span @class(['badge', 'bg-success' => $item->approval === 1, 'bg-danger' => $item->approval === 0])>
              <span class="px-3">{{ $item->approval == 1 ? 'Si' : ($item->approval == 0 ? 'No' : '') }}</span>
            </span>
          </td>
          <td class="align-middle">
            <div class="btn-group">
              <a href="{{ route('requestFiles.edit', $item->id) }}" class="btn btn-info"><span class="fa fa-edit"></span></a>
              <button type="button" class="btn btn-warning btn-onPrint" data-id="{{ $item->id }}" data-type="{{ ucfirst(strtolower($item->requestType->cParNombre)) }}" data-correlative="{{ $item->number_correlative }}" "><i class="fa fa-print"></i></button>
              @if($item?->approval != 1) <button class="btn btn-success btn-approval" data-id="{{ $item->id }}" ><span class="fa fa-check-circle"></span></button> @endif
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

@include('admin.partials.session-message')

<script type="text/javascript">
  document.querySelectorAll(".btn-approval").forEach(element =>
    element.addEventListener("click", function() {
      Swal.fire({
        title: 'Desea aprobar la solicitud?',
        text: 'Esta acción es irreversible',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, aprobar!',
        cancelButtonText: 'No'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'POST',
            url: "{{ route('requestFiles.updateApproval', ':id' ) }}".replace(':id', element.dataset.id),
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
              Swal.fire(
                'Actualizado!',
                'Solicitud aprobada.',
                'success'
              ).then(() => {
                window.location.href = "{{ route('requestFiles.index') }}";
              });
            },
            error: function(response) {
              Swal.fire(
                'Uy!',
                'Algo ha ido mal. Por favor, inténtelo de nuevo',
                'error'
              );
            }
          });
        }
      });
    })
  );

  document.querySelectorAll(".btn-onPrint").forEach(element =>
    element.addEventListener("click", function() {

      const objData = {
        request_id: element.dataset.id
      };

      let queryParams = new URLSearchParams(objData).toString();

      fetch(`{{ route("reports.requestFileDetails") }}?${queryParams}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.status}`);
          }
          return response.blob();
        })
        .then(blob => {
          const type_name = element.dataset.type;
          const correlative = element.dataset.correlative;

          const file = new Blob([blob], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          });

          const a = document.createElement('a');
          a.style.display = 'none';
          document.body.appendChild(a);
          a.download = `excelSolicitud_${type_name}_${correlative}.xlsx`;
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

</script>
@stop