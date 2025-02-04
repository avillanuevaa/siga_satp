@include('admin.partials.validation-errors')
<form
  @if(isset($financialClassifier))
    action="{{ route('financialClassifiers.update', $financialClassifier) }}"
    method="POST"
  @else
    action="{{ route('financialClassifiers.store') }}"
    method="POST"
  @endif
>
  @csrf
  @if(isset($financialClassifier))
    @method('patch')
  @endif
 

  <div class="form-group row">
    <label for="type_id" class="col-12 col-sm-2 col-form-label">Tipo*</label>
    <div class="col-12 col-sm-9">
      <x-adminlte-select2 name="type_id" data-placeholder="Seleccione opción" enable-old-support >
        <x-adminlte-options :options="$classifierTypes" :selected="isset($financialClassifier) ? $financialClassifier->type_id : null" empty-option/>
      </x-adminlte-select2>
    </div>
  </div>

  <div class="form-group row">
    <label for="code" class="col-12 col-sm-2 col-form-label">Código*</label>
    <div class="col-12 col-sm-9">
      <x-adminlte-input name="code" value="{{ $financialClassifier->code ?? '' }}" enable-old-support />
    </div>
  </div>

  <div class="form-group row">
    <label for="name" class="col-12 col-sm-2 col-form-label">Nombre*</label>
    <div class="col-12 col-sm-9">
      <x-adminlte-input name="name" value="{{ $financialClassifier->name ?? '' }}" enable-old-support />
    </div>
  </div>

  {{-- Campo "Activate" --}}
  <div class="form-group row">
    <label for="name" class="col-3 col-sm-2 col-form-label">Activo*</label>
    <div class="col-9">
      <div class="col-auto d-flex align-items-end ">
        <div class="icheck-primary d-inline">
          <input type="checkbox" id="active" name="active" @if(old('active', $financialClassifier->active ?? '') == 1) checked @endif />
          <label for="active"></label>
        </div>
      </div>
    </div>
  </div>

  <div class="ln_solid"></div>
  <div class="form-group row">
    <div class="col-lg-12 col-sm-4 col-xs-12 col-md-offset-2">
      <x-adminlte-button type="submit" label="Guardar" theme="success" />
      <a href="{{ route('financialClassifiers.index') }}" class="btn btn-primary">Cancelar</a>
    </div>
  </div>
</form>



