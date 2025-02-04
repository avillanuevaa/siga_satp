@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
<h3>Aperturar Viáticos</h3>
@stop

@section('content')


@include('admin.partials.validation-errors')

<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Busqueda:</h3>
      </div>
      <div class="card-body">
        @include('admin.viatic_register._search_request')
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Formulario de registro:</h3>
      </div>
      <div class="card-body">
        <form id="registerForm" action="{{ route('viaticRegisters.store') }}" method="POST" disabled >
          @csrf
          @include('admin.viatic_register._form')
          <div class="ln_solid"></div>
          <div class="form-group row mt-4">
            <div class="col-lg-12 col-sm-4 col-xs-12 col-md-offset-2">
              <button type="submit" class="btn btn-success">Guardar</button>
              <a href="{{ route('viaticRegisters.index') }}" class="btn btn-primary">Cancelar</a>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>



@stop
@include('admin.partials.session-message')
@include('admin.viatic_register._form_js')