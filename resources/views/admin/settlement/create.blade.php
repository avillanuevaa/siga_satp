@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
<h3>Registrar Solicitud</h3>
@stop

@section('content')

<div class="row">
  <div class="col-12 col-lg-10">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Busqueda de solicitud:</h3>
      </div>
      <div class="card-body">
        @include('admin.settlement._search_request')
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-12 col-lg-10">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Formulario de registro:</h3>
      </div>
      <div class="card-body">
        <form id="registerForm" action="{{ route('settlements.store') }}" method="POST" disabled >
          @csrf
          @include('admin.settlement._form')
          <div class="ln_solid"></div>
          <div class="form-group row mt-4">
            <div class="col-lg-12 col-sm-4 col-xs-12 col-md-offset-2">
              <button type="submit" class="btn btn-success">Guardar</button>
              <a href="{{ route('settlements.index') }}" class="btn btn-primary">Cancelar</a>
            </div>
          </div>
        </form>
        @include('admin.settlement._modal_form')
      </div>
    </div>
  </div>
</div>



@stop
@include('admin.partials.session-message')
@include('admin.settlement._form_js')