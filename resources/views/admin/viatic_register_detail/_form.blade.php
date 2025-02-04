@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Comprobante</legend>
  <div class="row">
    <div class="col-md-2">
      <x-adminlte-input-date name="issue_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Emisión*"
        value="{{ old('issue_date', isset($viaticRegisterDetail->issue_date) ? \Carbon\Carbon::parse($viaticRegisterDetail->issue_date)->format('d/m/Y') : '') }}" enable-old-suport >
        <x-slot name="appendSlot">
            <x-adminlte-button icon="fa fa-calendar" data-target="#issue_date" data-toggle="datetimepicker" />
        </x-slot>
      </x-adminlte-input-date>
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_type" label="Tipo" placeholder="Tipo" value="{{ $viaticRegisterDetail->issue_type ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-4">
      <x-adminlte-select2 name="issue_description" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
        <x-adminlte-options :options="$paymentReceiptsTypes" :selected="isset($viaticRegisterDetail) ? $viaticRegisterDetail?->issue_type : null" empty-option />
      </x-adminlte-select2>
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_serie" label="Serie doc." placeholder="Serie doc." maxlength="4" value="{{ $viaticRegisterDetail->issue_serie ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_number" label="Núm doc." placeholder="Núm doc." maxlength="11" value="{{ $viaticRegisterDetail->issue_number ?? '' }}" enable-old-support />
    </div>
  </div>
</fieldset>

<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Proveedor / Colaborador</legend>
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-select2 name="supplier_type" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
        <x-adminlte-options :options="$identityCardTypes" :selected="isset($viaticRegisterDetail) ? $viaticRegisterDetail?->supplier_type : null" empty-option/>
      </x-adminlte-select2>
    </div>

    <div class="col-md-3">
      <x-adminlte-input name="supplier_number" label="DNI / Número RUC" placeholder="DNI / Número RUC" value="{{ $viaticRegisterDetail->supplier_number ?? '' }}" enable-old-support maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)" >
        <x-slot name="appendSlot">
          <x-adminlte-button theme="info" icon="fa fa-search" onclick="onSearchDniRuc()"/>
        </x-slot>
      </x-adminlte-input>
    </div>

    <div class="col-md-6">
      <x-adminlte-input name="supplier_name" label="Razón Social y/o Nombres" placeholder="Razón Social y/o Nombres" value="{{ $viaticRegisterDetail->supplier_name ?? '' }}" enable-old-support />
    </div>
  </div>
</fieldset>

<div class="row">
  <div class="col-md-2">
    <x-adminlte-input name="taxed_base" label="Base Gravada" placeholder="Base Gravada" value="{{ $viaticRegisterDetail->taxed_base ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="igv" label="IGV" placeholder="IGV" value="{{ $viaticRegisterDetail->igv ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="untaxed_base" label="Base No Gravada" placeholder="Base No Gravada" value="{{ $viaticRegisterDetail->untaxed_base ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="impbp" label="IMPBP" placeholder="IMPBP" value="{{ $viaticRegisterDetail->impbp ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="other_concepts" label="Otros Conceptos" placeholder="Otros Conceptos" value="{{ $viaticRegisterDetail->other_concepts ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="total" label="Total" placeholder="Total" value="{{ $viaticRegisterDetail->total ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Centro de Costos</legend>
      <div class="row">
        <div class="col-md-12">
          <x-adminlte-select2 name="cost_center_code" label="Código" data-placeholder="Seleccione opción" enable-old-support>
            <x-adminlte-options :options="$offices->pluck('name', 'value')->toArray()" :selected="isset($viaticRegisterDetail) ? $viaticRegisterDetail?->cost_center_code : null" empty-option/>
          </x-adminlte-select2>
        </div>

        <div class="col-md-12">
          <x-adminlte-input name="cost_center_description" label="Descripción" placeholder="Descripción" value="{{ $viaticRegisterDetail->cost_center_description ?? '' }}" enable-old-support readonly />
        </div>

      </div>
    </fieldset>

  </div>
  <div class="col-md-6">

    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Meta</legend>
      <div class="row">
        <div class="col-md-12">
          <x-adminlte-input name="goal_code" label="Código" placeholder="Código" value="{{ $viaticRegisterDetail->goal_code ?? '' }}" enable-old-support />
        </div>

        <div class="col-md-12">
          <x-adminlte-select2 name="goal_description" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
            <x-adminlte-options :options="$goals" :selected="isset($viaticRegisterDetail) ? $viaticRegisterDetail?->goal_description : null" empty-option/>
          </x-adminlte-select2>
        </div>

      </div>
    </fieldset>

  </div>
</div>


<div class="row">
  <div class="col-md-9">

    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Partida Presupuestaria</legend>
      <div class="row">
        <div class="col-md-5">
          <x-adminlte-select2 name="classifier_code" label="Clasificador" enable-old-support>
            @if(isset($viaticRegisterDetail))
              <option value="{{ old('classifier_code', $viaticRegisterDetail->classifier_code) }}">{{ $viaticRegisterDetail->classifier_code }} -- {{ $viaticRegisterDetail->classifier_descripcion }}</option>
            @endif
          </x-adminlte-select2>
        </div>

        <div class="col-md-5">
          <x-adminlte-input name="classifier_descripcion" label="Descripción" placeholder="Descripción" value="{{ $viaticRegisterDetail->classifier_descripcion ?? '' }}" enable-old-support readonly />

        </div>

        <div class="col-md-2">
          <x-adminlte-input name="classifier_amount" label="Monto" placeholder="Monto" value="{{ $viaticRegisterDetail->classifier_amount ?? '' }}" class="validateTwoDigitDecimalNumber" enable-old-support />

        </div>
      </div>
    </fieldset>

  </div>
  <div class="col-md-3">
    <x-adminlte-textarea name="expense_description" label="Descripción del Gasto" rows="5" maxlength="255" style="resize: none" enable-old-support >
      {{ $viaticRegisterDetail->expense_description ?? '' }}
    </x-adminlte-textarea>
  </div>
</div>
