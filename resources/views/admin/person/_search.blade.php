<form id="search" name="search" action="{{ route('persons.index') }}" method="GET">
  <div class="row">
    <div class="col-12 col-md-3">
      <x-adminlte-input name="document_number" label="Nro de documento" placeholder="Nro de documento" value="{{ $request->document_number ?? '' }}" enable-old-support />
    </div>
    <div class="col-12 col-md-3">
      <x-adminlte-input name="fullname" label="Nombres" placeholder="Nombres" value="{{ $request->fullname ?? '' }}" enable-old-support />
    </div>
    <div class="col-auto d-flex align-items-end form-group ">
      <x-adminlte-button type="submit" label="Buscar" theme="success" />
    </div>
  </div>
</form>