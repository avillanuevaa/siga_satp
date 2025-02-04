@php
    $config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Información del SIAF: {{ $documentSiaf->siaf ?? '' }}</legend>
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-input-date name="date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Emisión*"
        value="{{ old('date', isset($documentSiaf->date) ? \Carbon\Carbon::parse($documentSiaf->date)->format('d/m/Y') : '') }}" enable-old-suport >
        <x-slot name="appendSlot">
            <x-adminlte-button icon="fa fa-calendar" data-target="#date" data-toggle="datetimepicker" />
        </x-slot>
      </x-adminlte-input-date>
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="type_new" label="Tipo Doc*" placeholder="Tipo de documento" value="{{ $documentSiaf->type_new ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="serie" label="Serie Doc.*" placeholder="Serie de documento" value="{{ $documentSiaf->serie ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="number" label="Núm Doc.*" placeholder="Número de documento" value="{{ $documentSiaf->number ?? '' }}" enable-old-support />
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-input name="ruc" label="RUC.*" placeholder="RUC" value="{{ $documentSiaf->ruc ?? '' }}" enable-old-support maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)" >
        <x-slot name="appendSlot">
          <x-adminlte-button theme="info" icon="fa fa-search" onclick="onSearchRuc()"/>
        </x-slot>
      </x-adminlte-input>
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="business_name" label="Razón Social*" placeholder="Razón Social" value="{{ $documentSiaf->business_name ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="taxable_basis" label="Base Gravada*" placeholder="0.00" value="{{ $documentSiaf->taxable_basis ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" oninput="totalCalculate()" />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="igv" label="IGV*" placeholder="0.00" value="{{ $documentSiaf->igv ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" />
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-input name="untaxed_basis" label="Base No Gravada*" placeholder="0.00" value="{{ $documentSiaf->untaxed_basis ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" oninput="totalCalculate()" />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="impbp" label="IMPBP*" placeholder="0.00" value="{{ $documentSiaf->impbp ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" oninput="totalCalculate()" />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="other_concepts" label="Otros Conceptos*" placeholder="0.00" value="{{ $documentSiaf->other_concepts ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" oninput="totalCalculate()" />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="amount" label="Total*" placeholder="0.00" value="{{ $documentSiaf->amount ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" />
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <x-adminlte-input-date name="payment_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Pago*"
        value="{{ old('payment_date', isset($documentSiaf->payment_date) ? \Carbon\Carbon::parse($documentSiaf->payment_date)->format('d/m/Y') : '') }}" enable-old-suport >
        <x-slot name="appendSlot">
            <x-adminlte-button  icon="fa fa-calendar" data-target="#payment_date" data-toggle="datetimepicker" />
        </x-slot>
      </x-adminlte-input-date>
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="doc_code" label="Cod Doc." placeholder="Cod Doc." value="{{ $documentSiaf->doc_code ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="num_doc" label="Núm Doc." placeholder="Núm Doc." value="{{ $documentSiaf->num_doc ?? '' }}" enable-old-support />
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="">H/A*</label>
        <div class="row">
          <div class="col-md-4">
            <x-adminlte-input name="ha_1" type="number" placeholder="H/A" value="{{ $documentSiaf->ha_1 ?? '' }}" enable-old-support />
          </div>
          <div class="col-md-4">
            <x-adminlte-input name="ha_2" type="number" placeholder="H/A" value="{{ $documentSiaf->ha_2 ?? '' }}" enable-old-support />
          </div>
          <div class="col-md-4">
            <x-adminlte-input name="ha_3" type="number" placeholder="H/A" value="{{ $documentSiaf->ha_3 ?? '' }}" enable-old-support />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <x-adminlte-input-date name="detraction_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha Detracción"
        value="{{ old('detraction_date', isset($documentSiaf->detraction_date) ? \Carbon\Carbon::parse($documentSiaf->detraction_date)->format('d/m/Y') : '') }}" enable-old-suport >
        <x-slot name="appendSlot">
            <x-adminlte-button  icon="fa fa-calendar" data-target="#detraction_date" data-toggle="datetimepicker" />
        </x-slot>
      </x-adminlte-input-date>
    </div>
    <div class="col-md-3">
      <x-adminlte-input name="num_operation" label="Núm operación" placeholder="Núm operación" value="{{ $documentSiaf->num_operation ?? '' }}" enable-old-support />
    </div>
  </div>

  <fieldset class="border p-3 mb-3 comprobante-modifica-fieldset">
    <legend class="w-auto font-weight-bold">Comprobante que modifica</legend>
    <div class="row">
      <div class="col-md-3">
        <x-adminlte-input-date name="doc_modify_date_of_issue" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Emisión*"
          value="{{ old('doc_modify_date_of_issue', isset($documentSiaf->doc_modify_date_of_issue) ? \Carbon\Carbon::parse($documentSiaf->doc_modify_date_of_issue)->format('d/m/Y') : '') }}" enable-old-suport >
          <x-slot name="appendSlot">
              <x-adminlte-button  icon="fa fa-calendar" data-target="#doc_modify_date_of_issue" data-toggle="datetimepicker" />
          </x-slot>
        </x-adminlte-input-date>
      </div>
      <div class="col-md-3">
        <x-adminlte-input name="doc_modify_type" label="Tipo Doc." placeholder="Tipo Doc." value="{{ $documentSiaf->doc_modify_type ?? '' }}" enable-old-support />
      </div>
      <div class="col-md-3">
        <x-adminlte-input name="doc_modify_serie" label="Serie Doc." placeholder="Serie Doc." value="{{ $documentSiaf->doc_modify_serie ?? '' }}" enable-old-support />
      </div>
      <div class="col-md-3">
        <x-adminlte-input name="doc_modify_number" label="Núm Doc." placeholder="Núm Doc." value="{{ $documentSiaf->doc_modify_number ?? '' }}" enable-old-support />
      </div>
    </div>
  </fieldset>

  <fieldset  class="border p-3 mb-3 honorarios-fieldset">
    <legend class="w-auto font-weight-bold">Honorarios</legend>
    <div class="row">
      <div class="col-md-3">
        <x-adminlte-input name="last_name" label="Ap. Paterno" placeholder="Apellido paterno" value="{{ $documentSiaf->last_name ?? '' }}" enable-old-support />
      </div>
      <div class="col-md-3">
        <x-adminlte-input name="mother_last_name" label="Ap. Materno" placeholder="Apellido materno" value="{{ $documentSiaf->mother_last_name ?? '' }}" enable-old-support />
      </div>
      <div class="col-md-3">
        <x-adminlte-input name="name" label="Nombres" placeholder="Nombres" value="{{ $documentSiaf->name ?? '' }}" enable-old-support />
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <x-adminlte-input name="total_honorary" label="Total por Honorario" placeholder="0.00" value="{{ $documentSiaf->total_honorary ?? '' }}" enable-old-support  class="validateTwoDigitDecimalNumber" oninput="totalHonorary()" />
      </div>
      <div class="col-md-3 align-self-center">
        <div class="form-group">
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="have_retention" name="have_retention" @if(in_array(old('have_retention', $documentSiaf->have_retention ?? ''), [1, 'on'])) checked @endif />
            <label for="have_retention" class="custom-control-label">Retención</label>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <x-adminlte-input name="retention" label="Valor Retención" placeholder="0.00" value="{{ $documentSiaf->retention ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" />
      </div>
      <div class="col-md-3">
        <x-adminlte-input name="net_honorary" label="Neto Recibido" placeholder="0.00" value="{{ $documentSiaf->net_honorary ?? '' }}" enable-old-support class="validateTwoDigitDecimalNumber" />
      </div>
    </div>
  </fieldset>
</fieldset>