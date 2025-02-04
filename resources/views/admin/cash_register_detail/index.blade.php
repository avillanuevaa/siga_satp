@extends('layouts/admin')

@section('content_header')
<div class="row">
  <div class="col-md-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <h1 class="mb-2 mb-sm-0">Caja chica detalle</h1>
    <div class="d-flex flex-column flex-sm-row">
      <a href="{{ route('cashRegisterDetails.create', ['cashRegister' => $cashRegister->id]) }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo registro</a>
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
      <caption>Listado de caja chica en total: {{  $items->total() }}</caption>
      <thead>
        <tr>
          <th class="text-center align-middle">#</th>
          <th class="text-center align-middle">Comp. Fecha</th>
          <th class="text-center align-middle">Comp. Tipo</th>
          <th class="text-center align-middle">Comp. Serie</th>
          <th class="text-center align-middle">Comp. Total</th>
          <th class="text-center align-middle">Proov. Número</th>
          <th class="text-center align-middle">Proov. Razón Social</th>
          <th class="text-center align-middle">Total</th>
          <th class="text-center align-middle">Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
        <tr>
          <td class="text-center align-middle">{{ $startIndex++ }}</td>
          <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->issue_date)->format('d/m/Y') }}</td>
          <td class="text-center align-middle">{{ $item->issue_type }}</td>
          <td class="text-center align-middle">{{ $item->issue_serie }}</td>
          <td class="text-center align-middle">{{ $item->issue_number }}</td>
          <td class="text-center align-middle">{{ $item->supplier_number }}</td>
          <td class="text-left align-middle">{{ $item->supplier_name }}</td>
          <td class="text-center text-nowrap align-middle">S/. {{ number_format($item->total, 2) }}</td>
          <td class="align-middle">
            <div class="btn-group">
              <a href="{{ route('cashRegisterDetails.show', ['cashRegister' => $cashRegister->id, 'cashRegisterDetail' => $item->id]) }}" class="btn btn-info"><span class="fa fa-eye"></span></a>
              @if(!$cashRegister?->closed)
                <a href="{{ route('cashRegisterDetails.edit', ['cashRegister' => $cashRegister->id, 'cashRegisterDetail' => $item->id]) }}" class="btn btn-success"><span class="fa fa-edit"></span></a>
                <button class="btn btn-danger btn-delete-detail" data-id="{{ $item->id }}" ><span class="fa fa-times"></span></button>
              @endif
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
  document.querySelectorAll(".btn-delete-detail").forEach(element =>
    element.addEventListener("click", function() {
      Swal.fire({
        title: 'Desea eliminar el detalle?',
        text: 'Esta acción es irreversible',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'DELETE',
            url: "{{ route('cashRegisterDetails.destroy', [$cashRegister, ':id']) }}".replace(':id', element.dataset.id),
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
              Swal.fire(
                'Eliminado!',
                'El detalle ha sido eliminado.',
                'success'
              ).then(() => {
                location.reload();
              });
            },
            error: function(error) {
              console.error(error);
              Swal.fire(
                'Uy!',
                'Algo ha ido mal. Por favor, verifique que no tenga items en almacen e inténtelo de nuevo',
                'error'
              );
            }
          });
        }
      });
    }));
</script>


@stop