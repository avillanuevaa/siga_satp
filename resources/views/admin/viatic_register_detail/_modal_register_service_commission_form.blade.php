@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

<form id="dataForm" name="dataForm" action="{{ route('viaticRegisters.update', $viaticRegister) }}" method="POST">
  <x-adminlte-modal id="registerServiceCommission" title='Informe de Comisión de Servicio - Año: {{ date("Y") }} / Número: {{ $viaticRegister->number }}'  v-centered static-backdrop scrollable>
    @csrf
    @method('patch')

    <div class="row">
      <label for="service_commission_a" class="col-md-3 col-form-label">A:</label>
      <div class="col-12 col-sm-9">
        <x-adminlte-input name="service_commission_a" value="{{ $viaticRegister->service_commission_a ?? '' }}" enable-old-support />
      </div>
    </div>

    <div class="row">
      <label for="service_commission_from" class="col-md-3 col-form-label">De:</label>
      <div class="col-12 col-sm-9">
        <x-adminlte-input name="service_commission_from" value="{{ $viaticRegister->service_commission_from ?? '' }}" enable-old-support />
      </div>
    </div>

    <div class="row">
      <label for="service_commission_date" class="col-md-3 col-form-label">Fecha:</label>
      <div class="col-12 col-sm-9">
        <x-adminlte-input-date name="service_commission_date" :config="$config_date" placeholder="Selecciona una fecha"
        value="{{ old('service_commission_date', isset($viaticRegister->service_commission_date) ? \Carbon\Carbon::parse($viaticRegister->service_commission_date)->format('d/m/Y') : '') }}" enable-old-suport >
          <x-slot name="appendSlot">
            <x-adminlte-button icon="fa fa-calendar" data-target="#service_commission_date" data-toggle="datetimepicker" />
          </x-slot>
        </x-adminlte-input-date>
      </div>
    </div>
    <div class="row">
      <label for="service_commission_activities_performed" class="col-md-3 col-form-label">Actividades Realizadas:</label>
      <div class="col-12 col-sm-9">
        <x-adminlte-textarea name="service_commission_activities_performed" rows="5" maxlength="255" style="resize: none" enable-old-support >
          {{ $viaticRegister->service_commission_activities_performed ?? '' }}
        </x-adminlte-textarea>
      </div>
    </div>
    <div class="row">
      <label for="service_commission_results_obtained" class="col-md-3 col-form-label">Resultados Obtenidos:</label>
      <div class="col-12 col-sm-9">
        <x-adminlte-textarea name="service_commission_results_obtained" rows="5" maxlength="255" style="resize: none" enable-old-support >
          {{ $viaticRegister->service_commission_results_obtained ?? '' }}
        </x-adminlte-textarea>
      </div>
    </div>


    <x-slot name="footerSlot">
      <x-adminlte-button type="submit" theme="success" label="Guardar"/>
      <x-adminlte-button theme="default" label="Cancelar" data-dismiss="modal"/>
    </x-slot>
  </x-adminlte-modal>
</form>