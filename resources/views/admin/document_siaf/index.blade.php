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
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Busqueda</h3>
  </div>
  <div class="card-body">
    @include('admin.document_siaf._search')
  </div>
</div>
@if(session('totalUploadedFiles'))
  <div class="alert alert-{{ explode('|', session('totalUploadedFiles'))[0] }}">
      {{ explode('|', session('totalUploadedFiles'))[1] }}
  </div>
@endif
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
      <caption>Listado de siaf en total: {{  $documentSiafs->total() ?? '' }}</caption>
      <thead>
        <tr>
          <th class="text-center align-middle">Fecha Emisión</th>
          <th class="text-center align-middle">Tipo Doc</th>
          <th class="text-center align-middle">Serie doc.</th>
          <th class="text-center align-middle">Núm. doc</th>
          <th class="text-center align-middle">RUC</th>
          <th class="text-center align-middle">Total</th>
          <th class="text-center align-middle">Fecha Pago</th>
          <th class="text-center align-middle">Origen</th>
          <th class="text-center align-middle">Estado</th>
          <th class="text-center align-middle">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($documentSiafs as $item)
        <tr>
          <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
          <td class="text-center align-middle">{{ $item->type_new }}</td>
          <td class="text-center align-middle">{{ $item->serie }}</td>
          <td class="text-center align-middle">{{ $item->number }}</td>
          <td class="text-center align-middle">{{ $item->ruc }}</td>
          <td class="text-center align-middle">{{ ($item->type_new != "R" && $item->type_new != "N") ? number_format($item->amount, 2) : number_format($item->total_honorary, 2) }}</td>
          <td class="text-center align-middle">{{ $item->payment_date ? \Carbon\Carbon::parse($item->payment_date)->format('d/m/Y') : '' }}</td>
          <td class="text-center align-middle">{{ $item->source === 1 ? 'Importado' : 'Manual' }}</td>
          <td class="text-center align-middle">
            <div @class(['badge', 'bg-danger' => $item->status === 1, 'bg-success' => $item->status === 2, 'bg-info' => $item->status === 3])>
              {{ $item->status == 1 ? 'Pendiente' : ($item->status == 2 ? 'Registrado' : 'Cerrado') }}
            </span>
          </td>
          <td class="text-center align-middle">
            <div class="btn-group">
              <a href="{{ route('documentSiafs.edit', $item->id) }}" class="btn btn-success text-nowrap">
                <span class="fa fa-edit"></span> Reg
              </a>
              <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" >
                <span class="fa fa-trash-alt"></span>
              </button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-2">
      {{ $documentSiafs->appends(request()->all())->onEachSide(2)->links('admin.partials.pagination') }}
    </div>
  </div>
</div>

@include('admin.partials.session-message')

<script type="text/javascript">
  document.querySelectorAll(".btn-delete").forEach(element =>
    element.addEventListener("click", function() {
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
            url: "{{ route('documentSiafs.destroy', ':id') }}".replace(':id', element.dataset.id),
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
              Swal.fire(
                'Eliminado!',
                'El documento ha sido eliminado.',
                'success'
              ).then(() => {
                location.reload();
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
    }));
</script>
@stop