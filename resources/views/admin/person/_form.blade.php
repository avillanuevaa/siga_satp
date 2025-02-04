

@include('admin.partials.validation-errors')
<form
  @if(isset($person))
    action="{{ route('persons.update', $person) }}"
    method="POST"
  @else
    action="{{ route('persons.store') }}"
    method="POST"
  @endif
>
  @csrf
  @if(isset($person))
    @method('patch')
  @endif

  <div class="row">
    <div class="col-12 col-md-6">
      <x-adminlte-input name="name" label="Nombres*" placeholder="Nombres" value="{{ $person->name ?? '' }}" enable-old-support />
    </div>
    <div class="col-12 col-md-6">
      <x-adminlte-input name="lastname" label="Apelidos*" placeholder="Apelidos" value="{{ $person->lastname ?? '' }}" enable-old-support />
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-md-6">
      <x-adminlte-select2 name="document_type_id" label="Tipo de documento*" data-placeholder="Seleccione opción" >
        <x-adminlte-options :options="$documentSupplierTypes" :selected="isset($person) ? $person->document_type_id : null" empty-option/>
      </x-adminlte-select2>
    </div>
    
    <div class="col-12 col-md-6">
      <x-adminlte-input name="document_number" label="N° documento*" placeholder="N° documento" value="{{ $person->document_number ?? '' }}" enable-old-support maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)" />
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-md-6">
      <x-adminlte-input name="address" label="Dirección*" placeholder="Dirección" value="{{ $person->address ?? '' }}" enable-old-support />
    </div>
    <div class="col-12 col-md-6">
      <x-adminlte-input name="phone" label="Teléfono*" placeholder="Teléfono" value="{{ $person->phone ?? '' }}" enable-old-support maxlength="9"  oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9)" />
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <x-adminlte-select2 id="office" name="office[]" label="Oficina" data-placeholder="Seleccione oficina"  multiple >
        <x-adminlte-options :options="$offices" :selected="isset($person) ? $person?->office->pluck('id')->toArray() : null" empty-option/>
      </x-adminlte-select2>
    </div>
  </div>

  <div class="ln_solid"></div>
  <div class="form-group row">
    <div class="col-lg-12 col-sm-4 col-xs-12 col-md-offset-2">
      <x-adminlte-button type="submit" label="Guardar" theme="success" />
      <a href="{{ route('persons.index') }}" class="btn btn-primary">Cancelar</a>
    </div>
  </div>
  
</form>



