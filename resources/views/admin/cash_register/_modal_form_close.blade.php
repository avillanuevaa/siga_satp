@php
$config_date = ['format' => 'DD/MM/YYYY', 'locale' => 'es', 'useCurrent' => false];
@endphp
<x-adminlte-modal id="onCloseCashRegister" title='Cerrar Caja'  static-backdrop>
  <form id="dataFormModalClose" name="dataFormModalClose">
    <x-adminlte-input type="hidden" name="cash_register_id"  />
    <div class="row">
      <div class="col-md-6">
        <x-adminlte-input name="year" label="Año" placeholder="Año" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 4)" readonly />
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="number" label="Número" placeholder="Número" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 4)" readonly />
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <x-adminlte-input name="responsible" label="Responsable" placeholder="Responsable" readonly />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <x-adminlte-input name="opening_date" label="Fecha de Apertura" placeholder="Fecha de Apertura" readonly />
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="amount" label="Monto Aprobado" placeholder="Monto Aprobado" readonly />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <x-adminlte-input-date name="closing_date" :config="$config_date" placeholder="Selecciona una fecha" label="Fecha de Cierre" >
          <x-slot name="appendSlot">
            <x-adminlte-button icon="fa fa-calendar" data-target="#closing_date" data-toggle="datetimepicker" />
          </x-slot>
        </x-adminlte-input-date>
      </div>

      <div class="col-md-6">
        <x-adminlte-input name="amount_to_pay" label="Monto a Rendir" placeholder="Monto a Rendir" class="validateTwoDigitDecimalNumber" />
      </div>
    </div>

    <div class="row">
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

    const cashRegisterId = document.getElementById("cash_register_id");
    const closingDate = document.getElementById("closing_date");
    const amountToPay = document.getElementById("amount_to_pay");
    const surrenderReport = document.getElementById("surrender_report");

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const objData = {
      cash_register_id: cashRegisterId.value,
      closing_date: closingDate.value,
      amount_to_pay: amountToPay.value,
      surrender_report: surrenderReport.value,
    }

    fetch("{{ route('cashRegisters.close') }}", {
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

        window.location.href = "{{ session('previous_url_cash_register') }}";

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
  
});

</script>
@endpush