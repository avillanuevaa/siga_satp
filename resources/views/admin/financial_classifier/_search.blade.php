<form id="search" name="search" action="{{ route('financialClassifiers.index') }}" method="GET">
  <div class="row">
    <div class="col-12 col-md-3">
      <x-adminlte-input name="code" label="Código" placeholder="Código" value="{{ $request->code ?? '' }}" enable-old-support />
    </div>
    <div class="col-12 col-md-3">
      <x-adminlte-input name="name" label="Nombre" placeholder="Nombre" value="{{ $request->name ?? '' }}" enable-old-support />
    </div>
    <div class="col-auto d-flex align-items-end form-group ">
      <x-adminlte-button type="submit" label="Buscar" theme="success" />
    </div>
  </div>
</form>