{{-- Custom --}}
<x-adminlte-modal id="onNewRegisterClassifier" title="Agregar Clasificador" v-centered static-backdrop scrollable>
  <!-- Formulario para ingresar los datos -->
  <form id="dataForm">
    <x-adminlte-select2 name="code_classify" label="Clasificador:" class="select2" ></x-adminlte-select2>
    <x-adminlte-input name="goal_one" label="Meta 01:" placeholder="Meta 01" class="validateTwoDigitDecimalNumber" />
    <x-adminlte-input name="goal_two" label="Meta 02:" placeholder="Meta 02" class="validateTwoDigitDecimalNumber" />
    <x-adminlte-input name="goal_three" label="Meta 03:" placeholder="Meta 03" class="validateTwoDigitDecimalNumber" />

  </form>
  
  <x-slot name="footerSlot">
    <x-adminlte-button id="addButton" theme="success" label="Aceptar"/>
    <x-adminlte-button theme="default" label="Cancelar" data-dismiss="modal"/>
  </x-slot>
</x-adminlte-modal>


@once
@push('css')
  <style type="text/css">
    /* Estilo para limitar el ancho del selector */
    .select2-container {
        max-width: 100%;
    }
    /* Estilo para las opciones */
    .select2-results__option {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        max-width: 100%;
    }
  </style>
@endpush
@endonce