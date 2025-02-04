@push('js')
<script type="text/javascript">

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  const recordsArray = [];
  let selectedData = null;

  const row_viatic = document.querySelector('.pasajes-viaticos');

  
  const modal = new bootstrap.Modal(document.getElementById('onNewRegisterClassifier')); // Mover la creación del modal aquí

  const form = document.getElementById('registerForm');
  const recordsArrayInput = document.getElementById('requestFileClassifier');

  function addRecordToArray(record) {
      recordsArray.push(record);
      recordsArrayInput.value = JSON.stringify(recordsArray);
  }

  function updateTable(records) {
      const tableBody = document.getElementById('tableBody');
      tableBody.innerHTML = ''; // Limpia la tabla antes de actualizarla

      records.forEach( (record, index) => {
          const newRow = document.createElement('tr');
          newRow.innerHTML = `
              <td class="text-center align-middle">${record.code_classify}</td>
              <td class="text-center align-middle">${record.name_classify}</td>
              <td class="text-center text-nowrap align-middle">S/. ${parseFloat(record.goal_one).toFixed(2)} </td>
              <td class="text-center text-nowrap align-middle">S/. ${parseFloat(record.goal_two).toFixed(2)} </td>
              <td class="text-center text-nowrap align-middle">S/. ${parseFloat(record.goal_three).toFixed(2)} </td>
              @if( (isset($requestFile) && $requestFile?->approval) !== 1)
              <td class="text-center text-nowrap align-middle">
                <button class="btn btn-danger btn-delete" data-record-id="${record.financial_classifier_id}" >
                  <span class="fa fa-trash-alt"></span>
                </button>
              </td>
              @endif
          `;
          tableBody.appendChild(newRow);
      });

      const deleteButtons = document.querySelectorAll('.btn-delete');
      deleteButtons.forEach(button => {
          button.addEventListener('click', onDeleteClassifier);
      });
  }

  const addButton = document.getElementById('addButton');

  addButton.addEventListener('click', function () {

    const goalOneValue = document.getElementById('goal_one').value;
    const goalTwoValue = document.getElementById('goal_two').value;
    const goalThreeValue = document.getElementById('goal_three').value;

    const recordIndex = recordsArray.findIndex(record => record.financial_classifier_id === selectedData.id);

    if (recordIndex !== -1){
      Toast.fire({
          icon: "error",
          title: "Clasificador ya se encuentra agregado"
      });
      return;
    }

    if (selectedData && goalOneValue && goalTwoValue && goalThreeValue) {

      const newRecord = {
        financial_classifier_id: parseInt(selectedData.id),
        code_classify: selectedData.code,
        name_classify: selectedData.name,
        goal_one: parseFloat(document.getElementById('goal_one').value).toFixed(2),
        goal_two: parseFloat(document.getElementById('goal_two').value).toFixed(2),
        goal_three: parseFloat(document.getElementById('goal_three').value).toFixed(2),

      };

      addRecordToArray(newRecord);
      updateTable(recordsArray);

      // Resto del código para limpiar los campos y ocultar el modal
      $('#code_classify').val(null).trigger('change');
      document.getElementById('goal_one').value = '';
      document.getElementById('goal_two').value = '';
      document.getElementById('goal_three').value = '';

      selectedData = null; // Limpiar la variable de datos seleccionados

      modal.hide();

    } else {
      // Mostrar un mensaje de error o realizar alguna acción si no se cumplen las condiciones
      Toast.fire({
          icon: "error",
          title: "Llene todos los datos"
      });

    }
  });


    $(document).ready(function () {
      // Inicializar el campo de selección con Select2
      $('#code_classify').select2({
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

      $('#code_classify').on('select2:select', function (e) {
        selectedData = e.params.data.data; // Almacenar los datos completos
      });

    });


  function onDeleteClassifier(event) {
    event.preventDefault();
    const button = event.currentTarget;
    const recordId = parseInt(button.getAttribute('data-record-id'));
    Swal.fire({
      title: 'Desea eliminar la partida presupuestaria?',
      text: 'Esta acción es irreversible',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Si, eliminar!',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.isConfirmed) {
        // Buscar el registro por su identificador y eliminarlo del array
        const recordIndex = recordsArray.findIndex(record => record.financial_classifier_id === recordId);
        if (recordIndex !== -1) {
            recordsArray.splice(recordIndex, 1);
            // Actualizar la tabla
            recordsArrayInput.value = JSON.stringify(recordsArray);
            updateTable(recordsArray);
        }
      }
    });
  }

  // Cargar los datos de la relación en recordsArray
  const requestFileClassifier = JSON.parse(recordsArrayInput.value); //JSON.parse(recordsArrayInput.value);
  requestFileClassifier.forEach(record => {
      recordsArray.push({
          financial_classifier_id: parseInt(record.financial_classifier_id),
          code_classify: record.code_classify,
          name_classify: record.name_classify,
          goal_one: parseFloat(record.goal_one).toFixed(2),
          goal_two: parseFloat(record.goal_two).toFixed(2),
          goal_three: parseFloat(record.goal_three).toFixed(2)
      });
  });

  // Llamar a la función para actualizar la tabla
  updateTable(recordsArray);



  $('#request_type').on('change', function() {
    const selectedValue = $(this).val(); // Obtener el valor seleccionado en el select2

    if (selectedValue === '1') {
      row_viatic.classList.add('d-none');
    } else if (selectedValue === '2') {
      row_viatic.classList.remove('d-none');
    }
  });

  $('#request_type').trigger('change');

</script>
@endpush