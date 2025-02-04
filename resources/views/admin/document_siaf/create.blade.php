@extends('layouts/admin')

@section('plugins.TempusDominusBs4', true)

@section('content_header')
<h3>Registrar siaf</h3>
@stop

@section('content')

<div class="row">
  <div class="col-12 col-lg-11">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Crear siaf:</h3>
      </div>
      <div class="card-body">
        @include('admin.partials.validation-errors')
        <form id="registerForm" action="{{ route('documentSiafs.store') }}" method="POST" >
          @csrf
          @include('admin.document_siaf._form_step')
        </form>
      </div>
    </div>
  </div>
</div>

@include('admin.document_siaf._form_js')

@stop