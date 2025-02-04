@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
<h3>Editar Liquidación</h3>
@stop

@section('content')


<div class="row">
  <div class="col-12 col-lg-10">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Editar liquidación:</h3>
      </div>
      <div class="card-body">
        @include('admin.partials.validation-errors')
        <form id="registerForm" action="{{ route('settlements.update', $settlement) }}" method="POST" >
          @csrf
          @method('patch')
          @include('admin.settlement._form')
          <div class="ln_solid"></div>
          <div class="form-group row mt-4">
            <div class="col-lg-12 col-sm-4 col-xs-12 col-md-offset-2">
              @if( ($settlement?->approval) !== 1)<button type="submit" class="btn btn-success">Guardar</button>@endif
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
@include('admin.settlement._form_js')