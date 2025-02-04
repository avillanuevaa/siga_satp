@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Comprobante</legend>
  <div class="row">
    <div class="col-md-2">
      <x-adminlte-input-date name="issue_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Emisión*"
        value="{{ old('issue_date', isset($orderRegisterDetail->issue_date) ? \Carbon\Carbon::parse($orderRegisterDetail->issue_date)->format('d/m/Y') : '') }}" enable-old-suport >
        <x-slot name="appendSlot">
            <x-adminlte-button icon="fa fa-calendar" data-target="#issue_date" data-toggle="datetimepicker" />
        </x-slot>
      </x-adminlte-input-date>
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_type" label="Tipo" placeholder="Tipo" value="{{ $orderRegisterDetail->issue_type ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-4">
      <x-adminlte-select2 name="issue_description" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
        <x-adminlte-options :options="$paymentReceiptsTypes" :selected="isset($orderRegisterDetail) ? $orderRegisterDetail?->issue_type : null" empty-option />
      </x-adminlte-select2>
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_serie" label="Serie doc." placeholder="Serie doc." maxlength="4" value="{{ $orderRegisterDetail->issue_serie ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-2">
      <x-adminlte-input name="issue_number" label="Núm doc." placeholder="Núm doc." maxlength="11" value="{{ $orderRegisterDetail->issue_number ?? '' }}" enable-old-support />
    </div>
  </div>
</fieldset>

<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Proveedor / Colaborador</legend>
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-select2 name="supplier_type" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
        <x-adminlte-options :options="$identityCardTypes" :selected="isset($orderRegisterDetail) ? $orderRegisterDetail?->supplier_type : null" empty-option/>
      </x-adminlte-select2>
    </div>

    <div class="col-md-3">
      <x-adminlte-input name="supplier_number" label="DNI / Número RUC" placeholder="DNI / Número RUC" value="{{ $orderRegisterDetail->supplier_number ?? '' }}" enable-old-support maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)" >
        <x-slot name="appendSlot">
          <x-adminlte-button theme="info" icon="fa fa-search" onclick="onSearchDniRuc()"/>
        </x-slot>
      </x-adminlte-input>
    </div>

    <div class="col-md-6">
      <x-adminlte-input name="supplier_name" label="Razón Social y/o Nombres" placeholder="Razón Social y/o Nombres" value="{{ $orderRegisterDetail->supplier_name ?? '' }}" enable-old-support />
    </div>
  </div>
</fieldset>

<div class="row">
  <div class="col-md-2">
    <x-adminlte-input name="taxed_base" label="Base Gravada" placeholder="Base Gravada" value="{{ $orderRegisterDetail->taxed_base ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="igv" label="IGV" placeholder="IGV" value="{{ $orderRegisterDetail->igv ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="untaxed_base" label="Base No Gravada" placeholder="Base No Gravada" value="{{ $orderRegisterDetail->untaxed_base ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="impbp" label="IMPBP" placeholder="IMPBP" value="{{ $orderRegisterDetail->impbp ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="other_concepts" label="Otros Conceptos" placeholder="Otros Conceptos" value="{{ $orderRegisterDetail->other_concepts ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
  <div class="col-md-2">
    <x-adminlte-input name="total" label="Total" placeholder="Total" value="{{ $orderRegisterDetail->total ?? '0.00' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Centro de Costos</legend>
      <div class="row">
        <div class="col-md-12">
          <x-adminlte-select2 name="cost_center_code" label="Código" data-placeholder="Seleccione opción" enable-old-support>
            <x-adminlte-options :options="$offices->pluck('name', 'value')->toArray()" :selected="isset($orderRegisterDetail) ? $orderRegisterDetail?->cost_center_code : null" empty-option/>
          </x-adminlte-select2>
        </div>

        <div class="col-md-12">
          <x-adminlte-input name="cost_center_description" label="Descripción" placeholder="Descripción" value="{{ $orderRegisterDetail->cost_center_description ?? '' }}" enable-old-support readonly />
        </div>

      </div>
    </fieldset>

  </div>
  <div class="col-md-6">

    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Meta</legend>
      <div class="row">
        <div class="col-md-12">
          <x-adminlte-input name="goal_code" label="Código" placeholder="Código" value="{{ $orderRegisterDetail->goal_code ?? '' }}" enable-old-support />
        </div>

        <div class="col-md-12">
          <x-adminlte-select2 name="goal_description" label="Descripción" data-placeholder="Seleccione opción" enable-old-support>
            <x-adminlte-options :options="$goals" :selected="isset($orderRegisterDetail) ? $orderRegisterDetail?->goal_description : null" empty-option/>
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
            @if(isset($orderRegisterDetail))
              <option value="{{ old('classifier_code', $orderRegisterDetail->classifier_code) }}">{{ $orderRegisterDetail->classifier_code }} -- {{ $orderRegisterDetail->classifier_descripcion }}</option>
            @endif
          </x-adminlte-select2>
        </div>

        <div class="col-md-5">
          <x-adminlte-input name="classifier_descripcion" label="Descripción" placeholder="Descripción" value="{{ $orderRegisterDetail->classifier_descripcion ?? '' }}" enable-old-support readonly />

        </div>

        <div class="col-md-2">
          <x-adminlte-input name="classifier_amount" label="Monto" placeholder="Monto" value="{{ $orderRegisterDetail->classifier_amount ?? '' }}" class="validateTwoDigitDecimalNumber" enable-old-support />

        </div>
      </div>
    </fieldset>

  </div>
  <div class="col-md-3">
    <x-adminlte-textarea name="expense_description" label="Descripción del Gasto" rows="5" maxlength="255" style="resize: none" enable-old-support >
      {{ $orderRegisterDetail->expense_description ?? '' }}
    </x-adminlte-textarea>
  </div>
</div>

<div class="row">
  <div class="col-md-2">
    <x-adminlte-input-switch name="enter_to_warehouse" label="¿Ingresa a Almacén?" data-on-text="Si" :checked="isset($orderRegisterDetail) ? ($orderRegisterDetail?->enter_to_warehouse ? true : false) : null" data-off-text="No" enable-old-support  />
  </div>

  <div class="col-md-10 warehouse-tbl" @if( !isset($orderRegisterDetail) || $orderRegisterDetail?->enter_to_warehouse == false ) hidden  @endif >
    <div class="row mb-4">
      <div class="col-12 text-right">
        @if( !isset($orderRegisterDetail) || !$orderRegisterDetail?->close )<x-adminlte-button type="button" label="Agregar Item" theme="info" icon="fas fa-plus"  data-toggle="modal" data-target="#onNewItemWarehouse" />@endif
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

<input type="hidden" name="warehouses" value="{{ old('warehouses', isset($orderRegisterDetail) ? $orderRegisterDetail->warehouses : '[]' ) }}" id="warehouses">
