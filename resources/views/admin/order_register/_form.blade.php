@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

@if ($errors->has('settlement'))
  <div class="alert alert-danger" role="alert">
      {{ $errors->first('settlement') }}
  </div>
@endif

<input type="hidden" name="settlement" value="{{ old('settlement', $orderRegister->settlement_id ?? '' ) }}" readonly />

<div class="row">
  <div class="col-md-6">
    <x-adminlte-input name="year" label="Año*" placeholder="Año" value="{{ $orderRegister->year ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="responsible" label="Responsable" placeholder="Responsable" value="{{ $orderRegister->responsible ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input-date name="opening_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Apertura*"
    value="{{ old('opening_date', isset($orderRegister->opening_date) ? \Carbon\Carbon::parse($orderRegister->opening_date)->format('d/m/Y') : '') }}" enable-old-suport >
      <x-slot name="appendSlot">
        <x-adminlte-button icon="fa fa-calendar" data-target="#opening_date" data-toggle="datetimepicker" />
      </x-slot>
    </x-adminlte-input-date>

    <x-adminlte-input name="number" label="Número Correlativo*" placeholder="Número Correlativo" value="{{ $orderRegister->number ?? '' }}" enable-old-support readonly />

    <x-adminlte-input name="approved_amount" label="Monto Aprobado*" placeholder="Monto Aprobado" value="{{ $orderRegister->approved_amount ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Documento de Autorización</legend>
      <div class="row justify-content-center">
        <div class="col-md-12">
          <x-adminlte-input name="authorization_date" label="Fecha Autorización" placeholder="Fecha Autorización" value="{{ isset($orderRegister->authorization_date) ? \Carbon\Carbon::parse($orderRegister->authorization_date)->format('d/m/Y') : '' }}" enable-old-support readonly />
        </div>
        <div class="col-md-12">
          <x-adminlte-input name="authorization_detail" label="Detalle" placeholder="Detalle" value="{{ $orderRegister->authorization_detail ?? '' }}" enable-old-support readonly />
        </div>
      </div>
    </fieldset>

  </div>

  <div class="col-md-12">
    <x-adminlte-input name="reason" label="Motivo" placeholder="Motivo" value="{{ $orderRegister->reason ?? '' }}" enable-old-support readonly />
  </div>


  <div class="col-md-6">

    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Expediente SIAF</legend>
      <div class="row justify-content-center">
        <div class="col-md-12">
          <x-adminlte-input-date name="siaf_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha*"
          value="{{ old('siaf_date', isset($orderRegister->siaf_date) ? \Carbon\Carbon::parse($orderRegister->siaf_date)->format('d/m/Y') : '') }}" enable-old-suport >
            <x-slot name="appendSlot">
              <x-adminlte-button icon="fa fa-calendar" data-target="#siaf_date" data-toggle="datetimepicker" />
            </x-slot>
          </x-adminlte-input-date>
        </div>
        <div class="col-md-12">
          <x-adminlte-input name="siaf_number" label="Número" placeholder="Número" value="{{ $orderRegister->siaf_number ?? '' }}" enable-old-support />
        </div>
      </div>
    </fieldset>

  </div>

  <div class="col-md-6">

    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Comprobante de Pago</legend>
      <div class="row justify-content-center">
        <div class="col-md-12">
          <x-adminlte-input-date name="voucher_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha*"
          value="{{ old('voucher_date', isset($orderRegister->voucher_date) ? \Carbon\Carbon::parse($orderRegister->voucher_date)->format('d/m/Y') : '') }}" enable-old-suport >
            <x-slot name="appendSlot">
              <x-adminlte-button icon="fa fa-calendar" data-target="#voucher_date" data-toggle="datetimepicker" />
            </x-slot>
          </x-adminlte-input-date>
        </div>
        <div class="col-md-12">
          <x-adminlte-input name="voucher_number" label="Número" placeholder="Número" value="{{ $orderRegister->voucher_number ?? '' }}" enable-old-support />
        </div>
      </div>
    </fieldset>

  </div>

  <div class="col-md-6">
    <x-adminlte-input-date name="order_pay_electronic_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de orden de Pago Electronico*"
    value="{{ old('order_pay_electronic_date', isset($orderRegister->order_pay_electronic_date) ? \Carbon\Carbon::parse($orderRegister->order_pay_electronic_date)->format('d/m/Y') : '') }}" enable-old-suport >
      <x-slot name="appendSlot">
        <x-adminlte-button icon="fa fa-calendar" data-target="#order_pay_electronic_date" data-toggle="datetimepicker" />
      </x-slot>
    </x-adminlte-input-date>
  </div>

</div>