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
    <h3 class="card-title">Búscar:</h3>
  </div>
  <div class="card-body">
    @include('admin.person._search')
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
      <caption>Listado de trabajadores en total: {{  $persons->total() }}</caption>
      <thead>
        <tr>
          <th class="text-center align-middle">N° Documento</th>
          <th class="text-center align-middle">Nombre</th>
          <th class="text-center align-middle">Apellido</th>
          <th class="text-center align-middle">Oficina</th>
          <th class="text-center align-middle">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($persons as $person)
        <tr>
          <td class="text-center align-middle">{{ $person->document_number }}</td>
          <td class="text-center align-middle">{{ $person->name }}</td>
          <td class="text-center align-middle">{{ $person->lastname }}</td>
          <td class="align-middle">
            @foreach ($person->office as $office)
              {{ $office->name }}<br />
            @endforeach
          </td>
          <td class="text-center align-middle">
            <div class="btn-group">
              <a href="{{ route('persons.edit', $person->id) }}" class="btn btn-success">
                <span class="fa fa-edit"></span>
              </a>
              <button class="btn btn-danger btn-delete" data-id="{{ $person->id }}" >
                <span class="fa fa-trash-alt"></span>
              </button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-2">
      {{ $persons->appends(request()->all())->onEachSide(2)->links('admin.partials.pagination') }}
    </div>
  </div>
</div>

@include('admin.partials.session-message')

<script type="text/javascript">
  document.querySelectorAll(".btn-delete").forEach(element =>
    element.addEventListener("click", function() {
      Swal.fire({
        title: 'Desea eliminar el trabajador?',
        text: 'Esta acción es irreversible',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'DELETE',
            url: "{{ route('persons.destroy', ':id') }}".replace(':id', element.dataset.id),
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
              Swal.fire(
                'Eliminado!',
                'El trabajador ha sido eliminado.',
                'success'
              ).then(() => {
                window.location.href = "{{ route('persons.index') }}";
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