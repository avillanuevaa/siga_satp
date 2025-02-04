@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

@if ($errors->has('settlement'))
  <div class="alert alert-danger" role="alert">
      {{ $errors->first('settlement') }}
  </div>
@endif

<input type="hidden" name="settlement" value="{{ old('settlement', $viaticRegister->settlement_id ?? '' ) }}" readonly />

<div class="row">
  <div class="col-md-6">
    <x-adminlte-input name="year" label="Año*" placeholder="Año" value="{{ $viaticRegister->year ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="responsible" label="Responsable" placeholder="Responsable" value="{{ $viaticRegister->responsible ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input-date name="opening_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Apertura*"
    value="{{ old('opening_date', isset($viaticRegister->opening_date) ? \Carbon\Carbon::parse($viaticRegister->opening_date)->format('d/m/Y') : '') }}" enable-old-suport >
      <x-slot name="appendSlot">
        <x-adminlte-button icon="fa fa-calendar" data-target="#opening_date" data-toggle="datetimepicker" />
      </x-slot>
    </x-adminlte-input-date>

    <x-adminlte-input name="number" label="Número Correlativo*" placeholder="Número Correlativo" value="{{ $viaticRegister->number ?? '' }}" enable-old-support readonly />

    <x-adminlte-input name="approved_amount" label="Monto Aprobado*" placeholder="Monto Aprobado" value="{{ $viaticRegister->approved_amount ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Documento de Autorización</legend>
      <div class="row justify-content-center">
        <div class="col-md-12">
          <x-adminlte-input name="authorization_date" label="Fecha Autorización" placeholder="Fecha Autorización" value="{{ isset($viaticRegister->authorization_date) ? \Carbon\Carbon::parse($viaticRegister->authorization_date)->format('d/m/Y') : '' }}" enable-old-support readonly />
        </div>
        <div class="col-md-12">
          <x-adminlte-input name="authorization_detail" label="Detalle" placeholder="Detalle" value="{{ $viaticRegister->authorization_detail ?? '' }}" enable-old-support readonly />
        </div>
      </div>
    </fieldset>
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="viatic_type" label="Tipo de Viático" placeholder="Tipo de Viático" value="{{ $viaticRegister->viatic_type ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="destination" label="Destino" placeholder="Destino" value="{{ $viaticRegister->destination ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="means_of_transport" label="Medio de Transporte" placeholder="Medio de Transporte" value="{{ $viaticRegister->means_of_transport ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="format_number_two" label="Formato 02 N" placeholder="Formato 02 N" value="{{ $viaticRegister->format_number_two ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="departure_date" label="Fecha de Salida" placeholder="Fecha de Salida" value="{{ $viaticRegister->departure_date ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="number_days" label="Número de días" placeholder="Número de días" value="{{ $viaticRegister->number_days ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-6">
    <x-adminlte-input name="return_date" label="Fecha de Retorno" placeholder="Fecha de Retorno" value="{{ $viaticRegister->return_date ?? '' }}" enable-old-support readonly />
  </div>

  <div class="col-md-12">
    <x-adminlte-input name="reason" label="Motivo" placeholder="Motivo" value="{{ $viaticRegister->reason ?? '' }}" enable-old-support readonly />
  </div>


  <div class="col-md-6">

    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Expediente SIAF</legend>
      <div class="row justify-content-center">
        <div class="col-md-12">
          <x-adminlte-input-date name="siaf_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha*"
          value="{{ old('siaf_date', isset($viaticRegister->siaf_date) ? \Carbon\Carbon::parse($viaticRegister->siaf_date)->format('d/m/Y') : '') }}" enable-old-suport >
            <x-slot name="appendSlot">
              <x-adminlte-button icon="fa fa-calendar" data-target="#siaf_date" data-toggle="datetimepicker" />
            </x-slot>
          </x-adminlte-input-date>
        </div>
        <div class="col-md-12">
          <x-adminlte-input name="siaf_number" label="Número" placeholder="Número" value="{{ $viaticRegister->siaf_number ?? '' }}" enable-old-support />
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
          value="{{ old('voucher_date', isset($viaticRegister->voucher_date) ? \Carbon\Carbon::parse($viaticRegister->voucher_date)->format('d/m/Y') : '') }}" enable-old-suport >
            <x-slot name="appendSlot">
              <x-adminlte-button icon="fa fa-calendar" data-target="#voucher_date" data-toggle="datetimepicker" />
            </x-slot>
          </x-adminlte-input-date>
        </div>
        <div class="col-md-12">
          <x-adminlte-input name="voucher_number" label="Número" placeholder="Número" value="{{ $viaticRegister->voucher_number ?? '' }}" enable-old-support />
        </div>
      </div>
    </fieldset>

  </div>

  <div class="col-md-6">
    <x-adminlte-input-date name="order_pay_electronic_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de orden de Pago Electronico*"
    value="{{ old('order_pay_electronic_date', isset($viaticRegister->order_pay_electronic_date) ? \Carbon\Carbon::parse($viaticRegister->order_pay_electronic_date)->format('d/m/Y') : '') }}" enable-old-suport >
      <x-slot name="appendSlot">
        <x-adminlte-button icon="fa fa-calendar" data-target="#order_pay_electronic_date" data-toggle="datetimepicker" />
      </x-slot>
    </x-adminlte-input-date>
  </div>

</div>