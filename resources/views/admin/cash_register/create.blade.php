@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
<h3>Aperturar caja</h3>
@stop

@section('content')

<div class="row">
  <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Formulario de registro:</h3>
      </div>
      <div class="card-body">
        @include('admin.partials.validation-errors')
        <form id="registerForm" action="{{ route('cashRegisters.store') }}" method="POST" >
          @csrf
          @include('admin.cash_register._form')
          <div class="ln_solid"></div>
          <div class="form-group row mt-4">
            <div class="col-lg-12 col-sm-4 col-xs-12 col-md-offset-2">
              <button type="submit" class="btn btn-success">Guardar</button>
              <a href="{{ session('previous_url_cash_register') }}" class="btn btn-primary">Cancelar</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


@stop
@include('admin.cash_register._form_js')