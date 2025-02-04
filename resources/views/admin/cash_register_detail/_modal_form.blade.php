<x-adminlte-modal id="onNewItemWarehouse" title="Nuevo Registro" v-centered static-backdrop scrollable>
  <!-- Formulario para ingresar los datos -->
  <form id="dataForm">
    <x-adminlte-select2 name="package" label="Bien" data-placeholder="Seleccione opción" enable-old-support>
    </x-adminlte-select2>
    <x-adminlte-input name="detail" label="Detalle" placeholder="Detalle" />
    <div class="row">
      <div class="col-md-6">
        <x-adminlte-select2 name="measure" label="Medida" data-placeholder="Seleccione opción" enable-old-support>
          <x-adminlte-options :options="$measures" empty-option/>
        </x-adminlte-select2>
      </div>
      <div class="col-md-6">
        <x-adminlte-input name="quantity" label="Cantidad" placeholder="Cantidad" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <x-adminlte-input name="unit_value" label="Valor Unitario" placeholder="Valor Unitario" class="validateTwoDigitDecimalNumber" />
      </div>
      <div class="col-md-6">
        <x-adminlte-input-switch name="lesser_package" label="Bien Menor" data-on-text="Si"  data-off-text="No" enable-old-support  />
      </div>
    </div>

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