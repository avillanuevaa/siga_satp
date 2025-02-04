@push('js')
<script type="text/javascript">

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  const recordsArray = [];
  const recordsArrayInput = document.getElementById('warehouses');
  const modal = new bootstrap.Modal(document.getElementById('onNewItemWarehouse')); // Mover la creación del modal aquí

  const form = document.getElementById('registerForm');

  function addRecordToArray(record) {
      recordsArray.push(record);
      recordsArrayInput.value = JSON.stringify(recordsArray);
  }

  function updateTable(records) {
      const tableBody = document.getElementById('tableBodyWarehouse');
      tableBody.innerHTML = ''; // Limpia la tabla antes de actualizarla

      records.forEach( (record, index) => {
          const newRow = document.createElement('tr');
          newRow.innerHTML = `
              <td class="text-center align-middle">${record.package}</td>
              <td class="text-center align-middle">${record.package_text}</td>
              <td class="text-center align-middle">${record.detail}</td>
              <td class="text-center align-middle">${record.measure}</td>
              <td class="text-center align-middle">${parseFloat(record.quantity)}</td>
              <td class="text-center text-nowrap align-middle">S/. ${parseFloat(record.unit_value).toFixed(2)} </td>
              <td class="text-center text-nowrap align-middle">S/. ${parseFloat(record.total).toFixed(2)} </td>
              <td class="text-center align-middle">${record.lesser_package == 1 ? 'Si' : 'No'}</td>
              @if( (isset($cashRegister) && !$cashRegister?->closed)  )
              <td class="text-center text-nowrap align-middle">
                <button class="btn btn-danger btn-delete" data-record-id="${record.package}" >
                  <span class="fa fa-trash-alt"></span>
                </button>
              </td>
              @endif
          `;
          tableBody.appendChild(newRow);
      });

      const deleteButtons = document.querySelectorAll('.btn-delete');
      deleteButtons.forEach(button => {
          button.addEventListener('click', onDeleteWarehouse);
      });
  }

  const addButton = document.getElementById('addButton');

  addButton.addEventListener('click', function () {

    const packageValue = $("#package").val();
    const packageText = $("#package option:selected").text().trim();
    const detail = document.getElementById('detail').value;
    const measureText = $("#measure option:selected").text().trim();
    const quantity = document.getElementById('quantity').value;
    const unitValue = document.getElementById('unit_value').value;
    const lesserPackage = document.getElementById('lesser_package').checked;

    if (packageValue && detail && measureText && quantity && unitValue ) {

      const newRecord = {
        package: packageValue,
        package_text: packageText,
        detail: detail,
        measure: measureText,
        quantity: parseFloat(quantity).toFixed(2),
        unit_value: parseFloat(unitValue).toFixed(2),
        total: parseFloat( quantity * unitValue ).toFixed(2),
        lesser_package: lesserPackage,

      };

      addRecordToArray(newRecord);
      updateTable(recordsArray);

      // Resto del código para limpiar los campos y ocultar el modal
      $('#package').val(null).trigger('change');
      $('#measure').val(null).trigger('change');

      document.getElementById('detail').value = '';
      document.getElementById('quantity').value = '';
      document.getElementById('unit_value').value = '';
      document.getElementById('lesser_package').value = '';
      document.getElementById('observation').value = '';

      modal.hide();

    } else {
      // Mostrar un mensaje de error o realizar alguna acción si no se cumplen las condiciones
      Toast.fire({
          icon: "error",
          title: "Llene todos los datos"
      });

    }
  });
  
  function onSearchDniRuc() {
    const documentType = document.getElementById('supplier_type').value;
    const documentNumber = document.getElementById('supplier_number').value;

    if (documentNumber.length == 0) return;

    if (documentType == 1) {
      fetch(`{{ route('persons.searchByDni') }}?dni=${documentNumber}`)
        .then(response => response.json())
        .then(data => {
          const supplierNameInput = document.getElementById('supplier_name');
          const issueTypeInput = document.getElementById('issue_type');

          supplierNameInput.value = '';

          if (Object.keys(data).length === 0) return;

          supplierNameInput.value = data.name + ' ' + data.lastname;

          if (!data.office || issueTypeInput.value != '89' || data.office.length == 0) return;

          const center = findCenterByCodeUE(data.office[0].code_ue);
          const costCenterCodeInput = document.getElementById('cost_center_code');
          costCenterCodeInput.value = center;
        })
        .catch(error => {
          console.error(error);
          Toast.fire({
            icon: 'error',
            title: 'Ocurrio un error al consultar'
          });

        });
    }else if (documentType == 6) {
      fetch(`{{ route('documentSiafs.SearchSupplierByRuc') }}?ruc=${documentNumber}`)
        .then(response => response.json())
        .then(data => {
          const supplierNameInput = document.getElementById('supplier_name');
          supplierNameInput.value = ""
          if(data.success){
            supplierNameInput.value = data.razonSocial;
            Toast.fire({
              icon: 'success',
              title: "Datos encontrados."
            });
          }else{
            Toast.fire({
              icon: 'error',
              title: data.message
            });
          }
        })
        .catch(error => {
          console.error(error);
          Toast.fire({
            icon: 'error',
            title: 'Ocurrio un error al consultar'
          });
        });
    }else{
      Toast.fire({
            icon: 'error',
            title: 'Seleccione una opción valida (RUC / DNI)'
          });
    }
  }


  function onDeleteWarehouse(event) {
    event.preventDefault();
    const button = event.currentTarget;
    const recordId = button.getAttribute('data-record-id');
    Swal.fire({
      title: 'Desea eliminar el item?',
      text: 'Esta acción es irreversible',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Si, eliminar!',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.isConfirmed) {
        // Buscar el registro por su identificador y eliminarlo del array
        const recordIndex = recordsArray.findIndex(record => record.package === recordId);
        if (recordIndex !== -1) {
            recordsArray.splice(recordIndex, 1);
            // Actualizar la tabla
            recordsArrayInput.value = JSON.stringify(recordsArray);
            updateTable(recordsArray);
        }
      }
    });
  }

  function onLoad(){

    const issueDescription = document.getElementById('issue_description');

    const taxedBase = document.getElementById('taxed_base');
    const untaxedBase = document.getElementById('untaxed_base');

    if("{{ isset($cashRegisterDetail) }}"){
      let valueOriginal = "{{ old('issue_description', $cashRegisterDetail->issue_description ?? '') }}";
      if (valueOriginal == issueDescription.value){
        if (issueDescription.value === '01') {
          taxedBase.readOnly = false; //.disabled = false;
          untaxedBase.readOnly = true; //.disabled = true;
          untaxedBase.value = 0;
          
        } else {
          taxedBase.readOnly = true; //.disabled = true;
          taxedBase.value = 0;
          untaxedBase.readOnly = false; //.disabled = false;
        }
      }
    }



    const warehouse = JSON.parse(recordsArrayInput.value); //JSON.parse(recordsArrayInput.value);
    warehouse.forEach(record => {
      recordsArray.push({
          package: record.package,
          package_text: record.package_text,
          detail: record.detail,
          measure: record.measure,
          quantity: parseFloat(record.quantity).toFixed(2),
          unit_value: parseFloat(record.unit_value).toFixed(2),
          total: parseFloat(record.total).toFixed(2),
          lesser_package: record.lesser_package,
      });
    });

    updateTable(recordsArray);

    let formDisable = "{{ old('view', $cashRegisterDetail->view ?? false) }}";
    if (formDisable == true) {
        const registerForm = document.getElementById('registerForm');
        for (let i = 0; i < registerForm.elements.length; i++) {
          registerForm.elements[i].disabled = true;
        }
    }

  }

  // onBlurIssueType
  function onBlurIssueType() {

    const issueTypeInput = document.getElementById('issue_type');

    issueTypeInput.addEventListener('blur', function() {
      $('#issue_description').val(issueTypeInput.value).trigger('change');
    });
    
  }


  // onChangeTaxedBase
  function onChangeTaxedBase() {
    const taxedBase = document.getElementById('taxed_base');

    taxedBase.addEventListener('input', function () {
      const value = parseFloat(taxedBase.value);
      if (isNaN(value)) return;
      const igv = value * 0.18;
      document.getElementById('igv').value = igv.toFixed(2);
      onCalculateTotal();
    });
  }

  // onChangeDocumentTypes
  function onChangeDocumentTypes(){

    $('#issue_description').on('change', function() {
      
      const value = $(this).val(); // Obtener el valor seleccionado en el select2
      
      if (value === null) return;
      const issueType = document.getElementById('issue_type');
      const taxedBase = document.getElementById('taxed_base');
      const untaxedBase = document.getElementById('untaxed_base');

      issueType.value = value;

      $('#supplier_type').val(value === '89' ? '1' : null).trigger('change');


      if (value === '01') {
        taxedBase.readOnly = false; //.disabled = false;
        untaxedBase.readOnly = true; //.disabled = true;
        untaxedBase.value = 0;
      } else {
        taxedBase.readOnly = true; //.disabled = true;
        taxedBase.value = 0;
        untaxedBase.readOnly = false; //.disabled = false;
      }

      if("{{ isset($cashRegisterDetail) }}"){
        let valueOriginal = "{{ old('issue_description', $cashRegisterDetail->issue_description ?? '') }}";
        if (valueOriginal != value){
          onClearAmountsFields();
        }
      }
      
    });

  }

  // onChangeUnTaxedBase
  function onChangeUnTaxedBase() {
    const untaxedBase = document.getElementById('untaxed_base');

    untaxedBase.addEventListener('input', function () {
      const value = parseFloat(untaxedBase.value);
      if (isNaN(value)) return;
      onCalculateTotal();
    });
  }

  // onChangeImpbp
  function onChangeImpbp() {
    const impbp = document.getElementById('impbp');

    impbp.addEventListener('input', function () {
      const value = parseFloat(impbp.value);
      if (isNaN(value)) return;
      onCalculateTotal();
    });
  }

  // onChangeOtherConcepts
  function onChangeOtherConcepts() {
    const otherConcepts = document.getElementById('other_concepts');

    otherConcepts.addEventListener('input', function () {
      const value = parseFloat(otherConcepts.value);
      if (isNaN(value)) return;
      onCalculateTotal();
    });
  }

  // onChangeIgv
  function onChangeIgv() {
    const igv = document.getElementById('igv');

    igv.addEventListener('input', function () {
      const value = parseFloat(igv.value);
      if (isNaN(value)) return;
      onCalculateTotal();
    });
  }

  // onChangeCostCenter
  function onChangeCostCenter() {

    $('#cost_center_code').on('change', function() {
      const value = $(this).val(); // Obtener el valor seleccionado en el select2
      
      if (!value) return;

      const objData = {
        code_ue: value
      }

      let queryParams = new URLSearchParams(objData).toString();

      fetch(`{{ route("offices.getOfficeAndParent") }}?${queryParams}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
        })
        .then(response => response.json())
        .then(data => {
          const selectedOffice = data.office;
          const father = data.father;

          if (selectedOffice) {
            // Actualizar los campos en la página con los datos obtenidos
            document.getElementById('cost_center_description').value = father ? father.description : selectedOffice.description; // Supongo que 'cost_center_description' es un campo de texto

            $('#goal_description').val(selectedOffice.goal).trigger('change');
          }
        })
        .catch(error => {
          console.log(error);
        });

    });

    $('#cost_center_code').trigger('change');

  }

  function onChangeGoalDescription() {

    $('#goal_description').on('change', function() {
      const value = $(this).val(); // Obtener el valor seleccionado en el select2

      if (value === null) return;

      document.getElementById('goal_code').value = value;

    });

    $('#goal_description').trigger('change');

  }

  // onCalculateTotal
  function onCalculateTotal() {
    const taxedBase = parseFloat(document.getElementById('taxed_base').value) || 0;
    const igv = parseFloat(document.getElementById('igv').value) || 0;
    const untaxedBase = parseFloat(document.getElementById('untaxed_base').value) || 0;
    const impbp = parseFloat(document.getElementById('impbp').value) || 0;
    const otherConcepts = parseFloat(document.getElementById('other_concepts').value) || 0;

    const total = taxedBase + igv + untaxedBase + impbp + otherConcepts;

    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('classifier_amount').value = total.toFixed(2);
  }

  // onClearAmountsFields
  function onClearAmountsFields() {
    document.getElementById('taxed_base').value = 0;
    document.getElementById('igv').value = 0;
    document.getElementById('untaxed_base').value = 0;
    document.getElementById('impbp').value = 0;
    document.getElementById('other_concepts').value = 0;
    document.getElementById('total').value = 0;
  }

  function onChangeEnterToWarehouseSwitch(){
    const tableWareHouse = document.querySelector('.warehouse-tbl');
    $('#enter_to_warehouse').on('switchChange.bootstrapSwitch', function(event, state) {
      if(state){
        tableWareHouse.removeAttribute('hidden');
      }else{
        tableWareHouse.setAttribute('hidden', 'hidden');
      }

    });

  }


  $(document).ready(function () {
    // Inicializar el campo de selección con Select2
    $('#classifier_code').select2({
      placeholder: 'Buscar clasificadores',
      minimumInputLength: 3, // Número mínimo de caracteres antes de hacer la búsqueda
      theme: 'bootstrap4',
      ajax: {
        url: `{{ route('financialClassifiers.search') }}`, // URL de tu API para obtener datos
        dataType: 'json',
        delay: 250, // Retraso antes de enviar la solicitud (milisegundos)
        data: function (params) {
          return {
            term: params.term
          };
        },
        processResults: function (data) {
          return {
                  results: data.map(function (item) {
                      return {
                          id: item.code,
                          text: `${item.code} -- ${item.name}`,
                          data: item
                      };
                  })
              };
        },
        cache: true
      },
      
    });

    $('#classifier_code').on('select2:select', function (e) {
      let selectedData = e.params.data.data; // Almacenar los datos completos
      document.getElementById('classifier_descripcion').value = selectedData.name;
    });


    $('#onNewItemWarehouse').on('show.bs.modal', function (event) {
      const selectedClassifier = $('#classifier_code').val();

      $('#package').empty();
      $('#package').append(new Option('', ''));

      // Realizar una solicitud AJAX para cargar los datos en #package
      $.ajax({
        url: `{{ route('assets.getListByClassifierCode') }}`, // Reemplaza con la URL de tu API para obtener datos de paquetes
        method: 'GET',
        dataType: 'json',
        data: {
          classifier_code: selectedClassifier
        },
        success: function(response) {
          // Llena #package con los datos obtenidos
          $.each(response, function(index, item) {
            $('#package').append(new Option(item.detail, item.number));
          });

          // Actualiza el select2 de #package
          $('#package').trigger('change');
        },
        error: function(error) {
          console.error(error);
        }
      });
    });

  });

  // Llamar a las funciones
  onChangeTaxedBase();
  onChangeDocumentTypes();
  onChangeUnTaxedBase();
  onChangeImpbp();
  onChangeOtherConcepts();
  onChangeIgv();
  onChangeCostCenter();
  onChangeGoalDescription();
  onBlurIssueType();
  onLoad();
  onChangeEnterToWarehouseSwitch();

</script>
@endpush