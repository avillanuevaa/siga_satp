<form id="search" name="search" action="{{ route('viaticRegisters.create') }}" method="GET" >
  <div class="row">
    <div class="col-md-4">
      <x-adminlte-input name="correlative_search" label="Número Correlativo" placeholder="Número correlativo" value="{{ $searchRequest->correlative_search ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-4">
      <x-adminlte-input name="year_search" label="Año" placeholder="Año" value="{{ $searchRequest->year_search ?? '' }}" enable-old-support />
    </div>
    <div class="col-auto d-flex align-items-end form-group ">
      <x-adminlte-button type="submit" label="Buscar" theme="success" />
    </div>
  </div>
  @if(isset($notFound) && $notFound)
    <div class="alert alert-danger col-12 col-md-8">
        <strong>Liquidación no encontrada.</strong>
    </div>
    @endif
</form>
