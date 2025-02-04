<script type="text/javascript">
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    const bsStepper = document.querySelector('.bs-stepper');
    if(bsStepper){
        window.stepper = new Stepper(bsStepper);
        document.getElementById('registerForm').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Evitar el envío del formulario
                nextStep(); // Llamar a la función para avanzar al Step 2
            }
        });
        
    }

  })

    const typeNewInput = document.getElementById('type_new');
    const siafInput = document.getElementById('siaf');
    const honorariosFieldset = document.querySelector('.honorarios-fieldset');
    const comprobanteModificaFieldset = document.querySelector('.comprobante-modifica-fieldset');
    const selectedValue = typeNewInput.value;
    const docCodeInput = "{{ old('doc_code', $documentSiaf->doc_code ?? '') }}";
    const statusSiaf = "{{ old('status', $documentSiaf->status ?? '') }}"
    
    const haveRetention = document.getElementById('have_retention');
    if (haveRetention.checked) {
            retention.readOnly = false;
    } else {
        retention.readOnly = true;
    }

    function onChangeCheckRetention() {
      const haveRetention = document.getElementById('have_retention');
      const retention = document.getElementById('retention');
          haveRetention.addEventListener('change', function() {
            if (this.checked) {
                retention.readOnly = false;
            } else {
                retention.readOnly = true;
            }
            totalHonorary();
          });

        
    }

    function onChangeValuesCalculate() {
      const taxableBasis = document.getElementById('taxable_basis');
      const untaxedBasis = document.getElementById('untaxed_basis');
      const impbp = document.getElementById('impbp');
      const otherConcepts = document.getElementById('other_concepts');

        [taxableBasis, untaxedBasis, impbp, otherConcepts].forEach(function(input) {
            input.addEventListener('change', totalCalculate);
        });
    }

    function totalHonorary() {
        const haveRetention = document.getElementById('have_retention');
        const retention = document.getElementById('retention');
        const totalHonorary = document.getElementById('total_honorary');
        const netHonorary = document.getElementById('net_honorary');
        if (haveRetention.checked) {
            const retentionValue = parseFloat(totalHonorary.value) * 0.08 * -1;
            retention.value = retentionValue.toFixed(2);
            netHonorary.value = (parseFloat(totalHonorary.value) + retentionValue).toFixed(2);
        } else {
            retention.value = '0.00';
            netHonorary.value = totalHonorary.value;
        }
    }

    function totalCalculate() {
        const taxableBasis = parseFloat(document.getElementById('taxable_basis').value) || 0;
        const untaxedBasis = parseFloat(document.getElementById('untaxed_basis').value) || 0;
        const impbp = parseFloat(document.getElementById('impbp').value) || 0;
        const otherConcepts = parseFloat(document.getElementById('other_concepts').value) || 0;
        const igv = taxableBasis * 0.18 || 0;
        const total = taxableBasis + igv + untaxedBasis + impbp + otherConcepts;

        document.getElementById('igv').value = igv.toFixed(2);
        document.getElementById('amount').value = total.toFixed(2);
    }
    
    if (!docCodeInput) {
        document.getElementById('doc_code').value = '14';
    }

    if ( !siafInput && (selectedValue === 'R' || selectedValue === 'N') ) {
        readOnlyTxt(true);
    }

    function readOnlyTxt(value){
        document.getElementById('taxable_basis').readOnly = value;
        document.getElementById('igv').readOnly = value;
        document.getElementById('untaxed_basis').readOnly = value;
        document.getElementById('impbp').readOnly = value;
        document.getElementById('other_concepts').readOnly = value;
        document.getElementById('amount').readOnly = value;
    }

    if (statusSiaf === "3") {
        const registerForm = document.getElementById('registerForm');
        for (let i = 0; i < registerForm.elements.length; i++) {
          registerForm.elements[i].disabled = true;
        }
    }

    function onSearchRuc() {
        const ruc = document.getElementById('ruc').value;
        fetch(`{{ route('documentSiafs.SearchSupplierByRuc') }}?ruc=${ruc}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('business_name').value = "";
                if (data.success) {
                    showSweertToastAlertError(
                        'Datos encontrados.',
                        'success'
                    );
                    document.getElementById('business_name').value = data.razonSocial;
                } else {
                    showSweertToastAlertError(
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                showSweertToastAlertError(
                    'Ocurrió un error al consultar.',
                    'error'
                );
            });
    }

    function showSweertToastAlertError(message, icon) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        Toast.fire({
            icon: icon,
            title: message
        });
    }

    onChangeCheckRetention();


    function applyStyles() {
        const selectedValue = typeNewInput.value;

        if (selectedValue === 'R' || selectedValue === 'N') {
            honorariosFieldset.style.display = 'block';
            comprobanteModificaFieldset.style.display = 'none';
            if (!siafInput){
                readOnlyTxt(true);
            }
        } else if (selectedValue === '07') {
            honorariosFieldset.style.display = 'none';
            comprobanteModificaFieldset.style.display = 'block';
            if (!siafInput){
                readOnlyTxt(false);
            }
        } else {
            honorariosFieldset.style.display = 'none';
            comprobanteModificaFieldset.style.display = 'none';
            if (!siafInput){
                readOnlyTxt(false);
            }
        }
    }

    typeNewInput.addEventListener('input', applyStyles);

    applyStyles();

    function nextStep() {
        const siaf = document.getElementById('siaf');
        const docCodeFirst = document.getElementById('doc_code_first');
        const numDocFirst = document.getElementById('num_doc_first');
        const ha1First = document.getElementById('ha_1_first');
        const ha2First = document.getElementById('ha_2_first');
        const ha3First = document.getElementById('ha_3_first');
        
        if (siaf.checkValidity() &&
            docCodeFirst.checkValidity() &&
            numDocFirst.checkValidity() &&
            ha1First.checkValidity() &&
            ha2First.checkValidity() &&
            ha3First.checkValidity()) {

                document.getElementById('doc_code').value = docCodeFirst.value;
                document.getElementById('num_doc').value = numDocFirst.value;
                document.getElementById('ha_1').value = ha1First.value;
                document.getElementById('ha_2').value = ha2First.value;
                document.getElementById('ha_3').value = ha3First.value;
                
                stepper.next();
        } else {
            showSweertToastAlertError(
                'Llene todos los datos',
                'error'
            );
        }
    }

</script>