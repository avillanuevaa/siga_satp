@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Comprobante</legend>
  <div class="row">
    <div class="col-md-2">
      <x-adminlte-input-date name="issue_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Emisión*"
        value="{{ old('issue_date', isset($cashRegisterDetail->issue_date) ? \Carbon\Carbon::parse($cashRegisterDetail->issue_date)->format('d/m/Y') : '') }}" enable-old-suport >
        <x-slot name="appendSlot">
            <x-adminlte-button icon="fa fa-calendar" data-target="#issue_date" data-toggle="datetimepicker" />
        </x-slot>
      </x-adminlte-input-date>
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_type" label="Tipo" placeholder="Tipo" value="{{ $cashRegisterDetail->issue_type ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-4">
      <x-adminlte-select2 name="issue_description" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
        <x-adminlte-options :options="$paymentReceiptsTypes" :selected="isset($cashRegisterDetail) ? $cashRegisterDetail?->issue_type : null" empty-option />
      </x-adminlte-select2>
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_serie" label="Serie doc." placeholder="Serie doc." maxlength="4" value="{{ $cashRegisterDetail->issue_serie ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_number" label="Núm doc." placeholder="Núm doc." maxlength="11" value="{{ $cashRegisterDetail->issue_number ?? '' }}" enable-old-support />
    </div>
  </div>
</fieldset>

<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Proveedor / Colaborador</legend>
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-select2 name="supplier_type" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
        <x-adminlte-options :options="$identityCardTypes" :selected="isset($cashRegisterDetail) ? $cashRegisterDetail?->supplier_type : null" empty-option/>
      </x-adminlte-select2>
    </div>

    <div class="col-md-3">
      <x-adminlte-input name="supplier_number" label="DNI / Número RUC" placeholder="DNI / Número RUC" value="{{ $cashRegisterDetail->supplier_number ?? '' }}" enable-old-support maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)" >
        <x-slot name="appendSlot">
          <x-adminlte-button theme="info" icon="fa fa-search" onclick="onSearchDniRuc()"/>
        </x-slot>
      </x-adminlte-input>
    </div>

    <div class="col-md-6">
      <x-adminlte-input name="supplier_name" label="Razón Social y/o Nombres" placeholder="Razón Social y/o Nombres" value="{{ $cashRegisterDetail->supplier_name ?? '' }}" enable-old-support />
    </div>
  </div>
</fieldset>

<div class="row">
  <div class="col-md-2">
    <x-adminlte-input name="taxed_base" label="Base Gravada" placeholder="Base Gravada" value="{{ $cashRegisterDetail->taxed_base ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="igv" label="IGV" placeholder="IGV" value="{{ $cashRegisterDetail->igv ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="untaxed_base" label="Base No Gravada" placeholder="Base No Gravada" value="{{ $cashRegisterDetail->untaxed_base ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="impbp" label="IMPBP" placeholder="IMPBP" value="{{ $cashRegisterDetail->impbp ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="other_concepts" label="Otros Conceptos" placeholder="Otros Conceptos" value="{{ $cashRegisterDetail->other_concepts ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="total" label="Total" placeholder="Total" value="{{ $cashRegisterDetail->total ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Centro de Costos</legend>
      <div class="row">
        <div class="col-md-12">
          <x-adminlte-select2 name="cost_center_code" label="Código" data-placeholder="Seleccione opción" enable-old-support>
            <x-adminlte-options :options="$offices->pluck('name', 'value')->toArray()" :selected="isset($cashRegisterDetail) ? $cashRegisterDetail?->cost_center_code : null" empty-option/>
          </x-adminlte-select2>
        </div>

        <div class="col-md-12">
          <x-adminlte-input name="cost_center_description" label="Descripción" placeholder="Descripción" value="{{ $cashRegisterDetail->cost_center_description ?? '' }}" enable-old-support readonly />
        </div>

      </div>
    </fieldset>

  </div>
  <div class="col-md-6">

    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Meta</legend>
      <div class="row">
        <div class="col-md-12">
          <x-adminlte-input name="goal_code" label="Código" placeholder="Código" value="{{ $cashRegisterDetail->goal_code ?? '' }}" enable-old-support />
        </div>

        <div class="col-md-12">
          <x-adminlte-select2 name="goal_description" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
            <x-adminlte-options :options="$goals" :selected="isset($cashRegisterDetail) ? $cashRegisterDetail?->goal_description : null" empty-option/>
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
            @if(isset($cashRegisterDetail))
              <option value="{{ old('classifier_code', $cashRegisterDetail->classifier_code) }}">{{ $cashRegisterDetail->classifier_code }} -- {{ $cashRegisterDetail->classifier_descripcion }}</option>
            @endif
          </x-adminlte-select2>
        </div>

        <div class="col-md-5">
          <x-adminlte-input name="classifier_descripcion" label="Descripción" placeholder="Descripción" value="{{ $cashRegisterDetail->classifier_descripcion ?? '' }}" enable-old-support readonly />

        </div>

        <div class="col-md-2">
          <x-adminlte-input name="classifier_amount" label="Monto" placeholder="Monto" value="{{ $cashRegisterDetail->classifier_amount ?? '' }}" class="validateTwoDigitDecimalNumber" enable-old-support />

        </div>
      </div>
    </fieldset>

  </div>
  <div class="col-md-3">
    <x-adminlte-textarea name="expense_description" label="Descripción del Gasto" rows="5" maxlength="255" style="resize: none" enable-old-support >
      {{ $cashRegisterDetail->expense_description ?? '' }}
    </x-adminlte-textarea>
  </div>
</div>

<div class="row">
  <div class="col-md-2">
    <x-adminlte-input-switch name="enter_to_warehouse" label="¿Ingresa a Almacén?" data-on-text="Si" :checked="isset($cashRegisterDetail) ? ($cashRegisterDetail?->enter_to_warehouse ? true : false) : null" data-off-text="No" enable-old-support  />
  </div>

  <div class="col-md-10 warehouse-tbl" @if( !isset($cashRegisterDetail) || $cashRegisterDetail?->enter_to_warehouse == false ) hidden  @endif >
    <div class="row mb-4">
      <div class="col-12 text-right">
        @if( !isset($cashRegisterDetail) || !$cashRegisterDetail?->close )<x-adminlte-button type="button" label="Agregar Item" theme="info" icon="fas fa-plus"  data-toggle="modal" data-target="#onNewItemWarehouse" />@endif
      </div>
    </div>
    <div class="row">
      <div class="table-responsive " style="height: 155px;overflow-y: scroll;">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th class="text-center align-middle">Bien</th>
              <th class="text-center align-middle">Nombre</th>
              <th class="text-center align-middle">Detalle</th>
              <th class="text-center align-middle">Medida</th>
              <th class="text-center align-middle">Cantidad</th>
              <th class="text-center align-middle">Valor Unitario</th>
              <th class="text-center align-middle">Total</th>
              <th class="text-center align-middle">Bien Menor</th>
              <th class="text-center align-middle">#</th>
            </tr>
          </thead>
          <tbody id="tableBodyWarehouse">
              

          </tbody>
        </table>
        @if ($errors->has('warehouses'))
          <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $errors->first('warehouses') }}</strong>
          </span>
        @endif
      </div>
    </div>
    
  </div>

</div>

<input type="hidden" name="warehouses" value="{{ old('warehouses', isset($cashRegisterDetail) ? $cashRegisterDetail->warehouses : '[]' ) }}" id="warehouses">
