@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
<div class="row">
  <div class="col-md-12">
    <h1>Importar Excel Siaf</h1>
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
  <div class="card-body">
    <form method="post" action="{{ route('documentSiafs.uploadExcel') }}" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-12 col-md-6">
          <label for="file">Seleccione el archivo excel a importar</label>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="file" name="file">
                <label class="custom-file-label" for="file">Seleccionar archivo</label>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <button type="submit" class="btn btn-primary">Subir archivo</button>
        </div>
      </div>
    </form>
  </div>
</div>

@stop
