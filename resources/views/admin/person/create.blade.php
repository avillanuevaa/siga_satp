@extends('layouts/admin')

@section('content_header')
<h3>Registrar trabajador</h3>
@stop

@section('content')

<div class="row">
  <div class="col-12 col-lg-9">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Crear trabajador:</h3>
      </div>
      <div class="card-body">
        @include('admin.person._form')
      </div>
    </div>
  </div>
</div>

@stop