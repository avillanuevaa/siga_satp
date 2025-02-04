@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp
<x-adminlte-modal id="onCloseOrderRegister" title='Cerrar Encargo'  static-backdrop>
  <form id="dataFormModalClose" name="dataFormModalClose">
    <x-adminlte-input type="hidden" name="order_register_id"  />
    <div class="row">
      <div class="col-md-6">
        <x-adminlte-input name="year" label="Año" placeholder="Año" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 4)" readonly />
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="responsible" label="Responsable" placeholder="Responsable" readonly />
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="opening_date" label="Fecha de Apertura" placeholder="Fecha de Apertura" readonly />

        <x-adminlte-input name="number" label="Número" placeholder="Número" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 4)" readonly />

        <x-adminlte-input name="approved_amount" label="Monto Aprobado" placeholder="Monto Aprobado" readonly />
      </div>

      <div class="col-md-6">
        <fieldset class="border p-3 mb-3">
          <legend class="w-auto font-weight-bold">Documento de Autorización</legend>
          <div class="row justify-content-center">
            <div class="col-md-12">
              <x-adminlte-input name="authorization_date" label="Fecha Autorización" placeholder="Fecha Autorización" readonly />
            </div>
            <div class="col-md-12">
              <x-adminlte-input name="authorization_detail" label="Detalle" placeholder="Detalle" readonly />
            </div>
          </div>
        </fieldset>
      </div>

      <div class="col-md-12">
        <x-adminlte-input name="reason" label="Motivo" placeholder="Motivo" readonly />
      </div>

      <div class="col-md-6">
        <fieldset class="border p-3 mb-3">
          <legend class="w-auto font-weight-bold">Expediente SIAF</legend>
          <div class="row justify-content-center">
            <div class="col-md-12">
              <x-adminlte-input name="siaf_date" label="Fecha" placeholder="Fecha" readonly />
            </div>
            <div class="col-md-12">
              <x-adminlte-input name="siaf_number" label="Número" placeholder="Número" readonly/>
            </div>
          </div>
        </fieldset>
      </div>

      <div class="col-md-6">
        <fieldset class="border p-3 mb-3">
          <legend class="w-auto font-weight-bold">Comprobante de Pago</legend>
          <div class="row justify-content-center">
            <div class="col-md-12">
              <x-adminlte-input name="voucher_date" label="Fecha" placeholder="Fecha" readonly />
            </div>
            <div class="col-md-12">
              <x-adminlte-input name="voucher_number" label="Número" placeholder="Número" readonly />
            </div>
          </div>
        </fieldset>
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="order_pay_electronic_date" label="Fecha de orden de Pago Electronica" placeholder="Fecha de orden de Pago Electronica" readonly />
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="amount_to_pay" label="Monto a Rendir" placeholder="Monto a Rendir" class="validateTwoDigitDecimalNumber" />
      </div>

      <div class="col-md-6">
        <x-adminlte-input-date name="closing_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Cierre" >
          <x-slot name="appendSlot">
            <x-adminlte-button icon="fa fa-calendar" data-target="#closing_date" data-toggle="datetimepicker" />
          </x-slot>
        </x-adminlte-input-date>
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="amount_to_returned" label="Monto a Devolver" placeholder="Monto a Devolver" class="validateTwoDigitDecimalNumber" />
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="surrender_report" label="INF. de Rendición" placeholder="INF. de Rendición" />
      </div>

    </div>
  </form>

  <x-slot name="footerSlot">
    <x-adminlte-button type="button" id="onSaveClose" theme="success" label="Guardar"/>
    <x-adminlte-button theme="default" label="Cancelar" data-dismiss="modal"/>
  </x-slot>

</x-adminlte-modal>

@push('js')
<script type="text/javascript">

$(document).ready(function() {

  $("#onSaveClose").click(function() {

    const orderRegisterId = document.getElementById("order_register_id");
    const closingDate = document.getElementById("closing_date");
    const amountToPay = document.getElementById("amount_to_pay");
    const amountToReturned = document.getElementById("amount_to_returned");
    const surrenderReport = document.getElementById("surrender_report");

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const objData = {
      order_register_id: orderRegisterId.value,
      closing_date: closingDate.value,
      amount_to_pay: amountToPay.value,
      amount_to_returned: amountToReturned.value,
      surrender_report: surrenderReport.value,
    }

    fetch("{{ route('orderRegisters.close') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(objData)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success){

        window.location.href = "{{ session('previous_url_order_register') }}";

      }else if (data.error) {

        cleanInputErrors();

        Object.keys(data.errors).forEach(fieldName => {
          const inputField = document.getElementById(fieldName);
          const errorMessages = data.errors[fieldName];
          const inputGroup = inputField.parentNode;
          
          const errorContainer = document.createElement('span');
          errorContainer.classList.add('invalid-feedback', 'd-block');
          errorContainer.setAttribute('role', 'alert');

          errorMessages.forEach(errorMessage => {
            const errorElement = document.createElement('strong');
            errorElement.textContent = errorMessage;
            errorContainer.appendChild(errorElement);
          });

          inputField.classList.add('is-invalid');
          inputGroup.classList.add('adminlte-invalid-igroup');

          inputGroup.parentNode.insertBefore(errorContainer, inputGroup.nextSibling);

        });
      } else {

        console.log(data);
      }
    })
    .catch(error => {

      console.error('Error:', error);
    });

    $("#miModal").modal("hide");
  });

  function cleanInputErrors() {
    const form = document.getElementById('dataFormModalClose');

    const inputs = form.querySelectorAll('input');
    inputs.forEach(inputField => {
      const inputGroup = inputField.parentNode;
      inputGroup.classList.remove('adminlte-invalid-igroup');

      const errorContainer = inputField.parentNode.nextElementSibling;
      if (errorContainer && errorContainer.classList.contains('invalid-feedback') && errorContainer.classList.contains('d-block')) {
          inputField.classList.remove('is-invalid');
          errorContainer.remove();
      }
    });
    
  }

  function onChangeAmountToPay() {
    const amountToPayInput = document.getElementById('amount_to_pay');
    const approvedAmountInput = document.getElementById('approved_amount');
    const amountReturnedInput = document.getElementById('amount_to_returned');

    amountToPayInput.addEventListener('input', () => {
        const value = amountToPayInput.value;
        const amountToPay = value ? parseFloat(value) : 0;
        const approvedAmount = parseFloat(approvedAmountInput.value);
        const amountReturned = approvedAmount - amountToPay;
        amountReturnedInput.value = amountReturned;
    });
  }

  // Llama a la función para habilitar la escucha de eventos
  onChangeAmountToPay();
  
});

</script>
@endpush