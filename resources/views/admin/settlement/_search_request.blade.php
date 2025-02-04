<form id="search" name="search" action="{{ route('requestFiles.searchRequestByCorrelativeAndRequestTypeAndYear') }}" method="GET">
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-input name="correlative_search" label="Número Correlativo" placeholder="Número correlativo" value="{{ $searchRequest->correlative_search ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-3">
      <x-adminlte-select2 name="request_type_search" label="Liquidación" data-placeholder="Seleccione opción" enable-old-support>
        <x-adminlte-options :options="$requestsTypes" :selected="isset($searchRequest) ? $searchRequest->request_type_search : null" empty-option/>
      </x-adminlte-select2>
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="year_search" label="Año" placeholder="Año" value="{{ $searchRequest->year_search ?? '' }}" enable-old-support />
    </div>
    <div class="col-auto d-flex align-items-end form-group ">
      <x-adminlte-button type="submit" label="Buscar" theme="success" />
    </div>
  </div>
</form>