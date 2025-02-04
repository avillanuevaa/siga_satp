<form id="search" name="search" action="{{ route('requestFiles.index') }}" method="GET">
  <div class="row">
    <div class="col-12 col-md-6">
      <x-adminlte-input name="fullName" label="Solicitud:" placeholder="Nombres y Apellidos" value="{{ $request->fullName ?? '' }}" enable-old-support />
    </div>
    <div class="col-auto d-flex align-items-end form-group ">
      <x-adminlte-button type="submit" label="Buscar" theme="success" />
    </div>
  </div>
</form>