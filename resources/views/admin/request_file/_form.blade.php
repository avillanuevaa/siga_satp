@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp

<div class="row">
  <div class="col-12 col-lg-5">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title font-weight-bold">Información General</h3>
      </div>
      <div class="card-body">
        <x-adminlte-select2 name="request_type" label="Solicitud" data-placeholder="Seleccione opción" enable-old-support>
          <x-adminlte-options :options="$requestsTypes" :selected="isset($requestFile) ? $requestFile->request_type : null" empty-option/>
        </x-adminlte-select2>
        <x-adminlte-input name="year" label="Año" placeholder="Año" value="{{ $requestFile->year ?? date('Y') }}" enable-old-support />
        <x-adminlte-input-date name="request_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha"
          value="{{ \Carbon\Carbon::parse($requestFile->request_date ?? '')->format('d/m/Y') }}" enable-old-suport >
          <x-slot name="appendSlot">
              <x-adminlte-button  icon="fa fa-calendar" data-target="#request_date" data-toggle="datetimepicker" />
          </x-slot>
        </x-adminlte-input-date>
        <x-adminlte-input name="request_amount" label="Monto Solicitado" placeholder="Monto Solicitado" value="{{ $requestFile->request_amount ?? '' }}" enable-old-support />
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title font-weight-bold">Responsable</h3>
      </div>
      <div class="card-body">
        @php
          $fullName = ($requestFile->person->name ?? '') . ' ' . ($requestFile->person->lastname ?? '');
          $responsibleFullName = ($responsible->person->name ?? '') . ' ' . ($responsible->person->lastname ?? '');
        @endphp
        <x-adminlte-input name="document_number_manager" label="DNI" placeholder="DNI" value="{{ $requestFile->person->document_number ?? $responsible->person->document_number ?? '' }}" enable-old-support readonly />
        <x-adminlte-input name="fullname_manager" label="Nombres y Apellidos" placeholder="Nombres y Apellidos" value="{{ trim($fullName) ?: trim($responsibleFullName) ?: '' }}" enable-old-support readonly />
        <x-adminlte-input name="position_manager" label="Cargo" placeholder="Cargo" value="{{ $requestFile->person->user->role->name ?? $responsible->role->name ?? ''  }}" enable-old-support readonly />
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <x-adminlte-input name="reference_document" label="Documento de Referencia" placeholder="Documento de Referencia" value="{{ $requestFile->reference_document ?? '' }}" enable-old-support />
      </div>
    </div>
  </div>
</div>



<div class="row">
  <div class="col-12 ">
    <div class="card">
      <div class="card-body">

        <x-adminlte-input name="purpose" label="Finalidad" placeholder="Finalidad" value="{{ $requestFile->purpose ?? '' }}" enable-old-support />
        <x-adminlte-input name="justification" label="Justificación" placeholder="Justificación" value="{{ $requestFile->justification ?? '' }}" enable-old-support />

        <div class="pasajes-viaticos d-none">
          
          <div class="row">
            <div class="col-md-6">
              <x-adminlte-select2 name="viatic_type" label="Tipo de Viático" data-placeholder="Seleccione opción" enable-old-support>
                <x-adminlte-options :options="$viaticsTypes" :selected="isset($requestFile) ? $requestFile->viatic_type : null" empty-option />
              </x-adminlte-select2>
            </div>
            <div class="col-md-6">
              <x-adminlte-input name="destination" label="Destino" placeholder="Destino" value="{{ $requestFile->destination ?? '' }}" enable-old-support />
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <x-adminlte-select2 name="means_of_transport" label="Medio de Transporte" data-placeholder="Seleccione opción" enable-old-support>
                <x-adminlte-options :options="$transportationsMeans" :selected="isset($requestFile) ? $requestFile->means_of_transport : null" empty-option/>
              </x-adminlte-select2>
            </div>
            <div class="col-md-6">
              <x-adminlte-input name="number_days" label="N° Días" placeholder="N° Días" value="{{ $requestFile->number_days ?? '' }}" enable-old-support oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <x-adminlte-input-date name="departure_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Salida"
                value="{{ old('departure_date', isset($documentSiaf->departure_date) ? \Carbon\Carbon::parse($documentSiaf->departure_date)->format('d/m/Y') : '') }}" enable-old-suport >
                <x-slot name="appendSlot">
                    <x-adminlte-button  icon="fa fa-calendar" data-target="#departure_date" data-toggle="datetimepicker" />
                </x-slot>
              </x-adminlte-input-date>

            </div>
            <div class="col-md-6">
              <x-adminlte-input-date name="return_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Retorno"
                value="{{ old('return_date', isset($documentSiaf->return_date) ? \Carbon\Carbon::parse($documentSiaf->return_date)->format('d/m/Y') : '') }}" enable-old-suport >
                <x-slot name="appendSlot">
                    <x-adminlte-button  icon="fa fa-calendar" data-target="#return_date" data-toggle="datetimepicker" />
                </x-slot>
              </x-adminlte-input-date>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-12 ">
    <div class="card">
      <div class="card-body">

        <div class="row mb-4">
          <div class="col-12 text-right">
            @if( (isset($requestFile) && $requestFile?->approval) !== 1 )<x-adminlte-button type="button" label="Agregar Partida" theme="info" icon="fas fa-plus"  data-toggle="modal" data-target="#onNewRegisterClassifier" />@endif
          </div>
        </div>

        <div class="row">
          <div class="col-12 ">
            <table class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                    <th class="text-center align-middle" scope="col" colspan="2">Partidas Presupuestarias</th>
                    <th class="text-center align-middle" scope="col" colspan="3">Meta</th>
                    @if( (isset($requestFile) && $requestFile?->approval) !== 1)<th class="text-center align-middle" scope="col" rowspan="2">Opciones</th>@endif
                </tr>
                <tr>
                    <th class="text-center" scope="col">Clasificador</th>
                    <th class="text-center" scope="col">Descripción</th>
                    <th class="text-center" scope="col">01</th>
                    <th class="text-center" scope="col">02</th>
                    <th class="text-center" scope="col">03</th>
                </tr>
              </thead>
              <tbody id="tableBody">
                

              </tbody>
            </table>
            @if ($errors->has('requestFileClassifier'))
              <span class="invalid-feedback d-block" role="alert">
                  <strong>{{ $errors->first('requestFileClassifier') }}</strong>
              </span>
            @endif
            
          </div>
        </div>

      </div>
    </div>
  </div>
</div>



<input type="hidden" name="requestFileClassifier" value="{{ old('requestFileClassifier', isset($requestFile) ? $requestFile->requestFileClassifier : '[]' ) }}" id="requestFileClassifier">


