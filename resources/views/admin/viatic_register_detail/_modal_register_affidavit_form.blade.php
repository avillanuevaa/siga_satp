<form id="dataForm" name="dataForm" action="{{ route('viaticRegisters.update', $viaticRegister) }}" method="POST">
  <x-adminlte-modal id="registerAffidavit" title='Declaración Jurada - Año: {{ date("Y") }} / Número: {{ $viaticRegister->number }}' size="lg" v-centered static-backdrop scrollable>
    @csrf
    @method('patch')
    <fieldset class="border p-3 mb-3">
      <legend class="w-auto font-weight-bold">Declaración Jurada - Extravío de Documentos</legend>
      <div class="row">
        <div class="col-md-10">
          <x-adminlte-textarea name="affidavit_description_lost_documents" rows="5" maxlength="255" style="resize: none" enable-old-support >
            {{ $viaticRegister->affidavit_description_lost_documents ?? '' }}
          </x-adminlte-textarea>
        </div>

        <div class="col-md-2">
          <x-adminlte-input name="affidavit_amount_lost_documents" value="{{ $viaticRegister->affidavit_amount_lost_documents ?? '' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
        </div>
      </div>
    </fieldset>

    <div class="row">
      <label for="code" class="col-auto col-form-label mr-2">Declaración Jurada – Gastos No Documentados</label>
      <x-adminlte-input name="affidavit_amount_undocumented_expenses" value="{{ $viaticRegister->affidavit_amount_undocumented_expenses ?? '' }}" class="validateTwoDigitDecimalNumber" enable-old-support />
    </div>


    <x-slot name="footerSlot">
      <x-adminlte-button type="submit" theme="success" label="Guardar"/>
      <x-adminlte-button theme="default" label="Cancelar" data-dismiss="modal"/>
    </x-slot>
  </x-adminlte-modal>
</form>