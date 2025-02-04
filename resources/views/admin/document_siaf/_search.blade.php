<form id="search" name="search" action="{{ route('documentSiafs.index') }}" method="GET">
  <div class="row">
    <div class="col-12 col-md-3">
      <x-adminlte-input name="siaf" label="Ingrese el siaf:" placeholder="siaf" value="{{ $request->siaf ?? '' }}" enable-old-support fgroup-class="mb-0" required/>
    </div>
    <div class="col-auto d-flex align-items-end mt-3 mt-md-0 ">
      <div class="icheck-primary d-inline">
        <input type="checkbox" id="current_year" name="current_year" value="on" {{ (  $request->current_year ?? '') === 'on' || empty($request->current_year) ? 'checked' : '' }} />
        <label for="current_year"></label>
      </div>
      <label for="current_year" class="col-form-label">Año actual</label>
    </div>
    <div class="col-auto d-flex align-items-end mx-3 mt-3 mt-md-0 ">
      <x-adminlte-button type="submit" label="Buscar" theme="success" />
    </div>
  </div>
</form>