@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)
@section('plugins.BootstrapSwitch', true)

@section('content_header')
<h3>Nuevo detalle</h3>
@stop

@section('content')


<div class="row">
  <div class="col-12 col-lg-10">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Editar detalle - Año: {{ date("Y") }} / Número: {{ $cashRegister->number }}</h3>
      </div>
      <div class="card-body">
        @include('admin.partials.validation-errors')
        <form id="registerForm" action="{{ route('cashRegisterDetails.store', ['cashRegister' => $cashRegister]) }}" method="POST" disabled >
          @csrf
          @include('admin.cash_register_detail._form')
          <div class="ln_solid"></div>
          <div class="form-group row mt-4">
            <div class="col-lg-12 col-sm-4 col-xs-12 col-md-offset-2">
              <button type="submit" class="btn btn-success">Guardar</button>
              <a href="{{ session('previous_url_cash_register_detail') }}" class="btn btn-primary">Cancelar</a>
            </div>
          </div>
        </form>
        @include('admin.cash_register_detail._modal_form')
      </div>
    </div>
  </div>
</div>

@stop
@include('admin.cash_register_detail._form_js')