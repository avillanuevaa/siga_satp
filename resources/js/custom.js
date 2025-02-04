const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

// $('select').each(function () {
//   $(this).select2({
//     theme: 'bootstrap4',
//     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
//     placeholder: $(this).data('placeholder'),
//     allowClear: Boolean($(this).data('allow-clear')),
//     closeOnSelect: !$(this).attr('multiple'),
//     maximumSelectionLength: $(this).attr('max-selected')
//   });
// });

function validateTwoDigitDecimalNumber(event) {
  const regex = /^[+-]?\d*\.?\d{0,2}$/g;
  const specialKeys = ['Backspace', 'Tab', 'End', 'Home', 'ArrowLeft', 'ArrowRight', 'Del', 'Delete'];

  if (specialKeys.indexOf(event.key) !== -1) {
      return;
  }

  const current = event.target.value;
  const position = event.target.selectionStart;
  const next = [current.slice(0, position), event.key === 'Decimal' ? '.' : event.key, current.slice(position)].join('');

  if (next && !String(next).match(regex)) {
      event.preventDefault();
  }
}



const fileInput = document.getElementById('file');
const fileLabel = document.querySelector('.custom-file-label');

if(fileInput && fileLabel){
  fileInput.addEventListener('change', function () {
      const fileName = this.files[0].name;
      fileLabel.textContent = fileName;
  });
}

function showSweertToastAlertError(message, icon) {
  Toast.fire({
      icon: icon,
      title: message
  });
}

document.addEventListener("DOMContentLoaded", function() {
  const sessionMessage = document.getElementById("session-message");
  if (sessionMessage) {
      const message = sessionMessage.getAttribute("data-mensaje");
      const icon = sessionMessage.getAttribute("data-icon");
      showSweertToastAlertError(message, icon);
  }


  const decimalInputs = document.querySelectorAll('.validateTwoDigitDecimalNumber');

  decimalInputs.forEach(input => {
      input.addEventListener('keydown', validateTwoDigitDecimalNumber);
  });


});

