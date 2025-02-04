@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
  <div class="row justify-content-center p-4">
      <div class="col-md-5">
          <img src="{{ asset('images/logo_institution.png') }}" alt="Logo SATP" class="img-fluid">
      </div>
  </div>
@stop

@section('css')
    <!-- Estilos CSS adicionales -->
@stop

@section('js')
    <!-- Scripts JS adicionales -->
@stop

@section('adminlte_js')
    <!-- Scripts JS específicos de AdminLTE -->
@stop

@section('adminlte_css')
    <!-- Estilos CSS específicos de AdminLTE -->
@stop