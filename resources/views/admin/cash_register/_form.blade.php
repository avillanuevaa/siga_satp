@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

<div class="row">
  <div class="col-12">
    <x-adminlte-input name="year" label="Año*" placeholder="Año" value="{{ date('Y'); }}" enable-old-support readonly />
    @php
      $responsibleFullName = ($responsible->person->document_number ?? '') . ' - ' . ($responsible->person->name ?? '') . ' ' . ($responsible->person->lastname ?? '');
    @endphp

    <x-adminlte-input name="fullname_manager" label="Responsable*" placeholder="Responsable" value="{{ trim($responsibleFullName) ?: '' }}" enable-old-support readonly />

    <x-adminlte-input-date name="opening_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Apertura*"
      value="{{ old('opening_date', '') }}" enable-old-suport >
      <x-slot name="appendSlot">
        <x-adminlte-button icon="fa fa-calendar" data-target="#opening_date" data-toggle="datetimepicker" />
      </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input name="amount" label="Total*" placeholder="0.00" enable-old-support class="validateTwoDigitDecimalNumber" />

  </div>
</div>

  