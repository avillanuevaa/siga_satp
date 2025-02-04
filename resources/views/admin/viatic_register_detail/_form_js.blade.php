@push('js')
<script type="text/javascript">

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  const form = document.getElementById('registerForm');
  
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

  function onLoad(){

    const issueDescription = document.getElementById('issue_description');

    const taxedBase = document.getElementById('taxed_base');
    const untaxedBase = document.getElementById('untaxed_base');

    if("{{ isset($viaticRegisterDetail) }}"){
      let valueOriginal = "{{ old('issue_description', $viaticRegisterDetail->issue_description ?? '') }}";
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

    
    let formDisable = "{{ old('view', $viaticRegisterDetail->view ?? false) }}";
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

      if("{{ isset($viaticRegisterDetail) }}"){
        let valueOriginal = "{{ old('issue_description', $viaticRegisterDetail->issue_description ?? '') }}";
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