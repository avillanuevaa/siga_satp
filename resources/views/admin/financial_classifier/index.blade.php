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
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Búscar:</h3>
  </div>
  <div class="card-body">
    @include('admin.financial_classifier._search')
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
      <caption>Listado de clasificadores en total: {{  $financialClassifiers->total() }}</caption>
      <thead>
        <tr>
          <th class="text-center align-middle">Tipo</th>
          <th class="text-center align-middle">Código</th>
          <th class="text-center align-middle">Nombre</th>
          <th class="text-center align-middle">Estado</th>
          <th class="text-center align-middle">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($financialClassifiers as $financialClassifier)

        <tr class="{{ $financialClassifier->active ? '' : 'bg-gradient-danger' }}">
          <td class="text-center align-middle">{{ $financialClassifier->type_name }}</td>
          <td class="text-center align-middle">{{ $financialClassifier->code }}</td>
          <td class="align-middle">{{ $financialClassifier->name }}</td>
          <td class="text-center align-middle">
            <span class="badge {{ $financialClassifier->active ? 'bg-success' : 'bg-danger' }}">{{ $financialClassifier->active ? 'Activo' : 'Inactivo' }}</span>

          </td>
          <td class="text-center align-middle">
            <div class="btn-group">
              <a href="{{ route('financialClassifiers.edit', $financialClassifier->id) }}" class="btn btn-success">
                <span class="fa fa-edit"></span>
              </a>
              <button class="btn btn-danger btn-delete" data-id="{{ $financialClassifier->id }}" >
                <span class="fa fa-trash-alt"></span>
              </button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-2">
      {{ $financialClassifiers->appends(request()->all())->onEachSide(1)->links('admin.partials.pagination') }}
    </div>
  </div>
</div>

@include('admin.partials.session-message')

<script type="text/javascript">
  document.querySelectorAll(".btn-delete").forEach(element =>
    element.addEventListener("click", function() {
      Swal.fire({
        title: 'Desea eliminar el clasificador?',
        text: 'Esta acción es irreversible',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'DELETE',
            url: "{{ route('financialClassifiers.destroy', ':id') }}".replace(':id', element.dataset.id),
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
              Swal.fire(
                'Eliminado!',
                'El clasificador ha sido eliminado.',
                'success'
              ).then(() => {
                window.location.href = "{{ route('financialClassifiers.index') }}";
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