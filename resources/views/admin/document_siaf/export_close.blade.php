@extends('layouts/admin')

@section('content_header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row">
  <div class="col-md-12">
    <h1>Exportar y cierre</h1>
  </div>
</div>
@stop

@section('content')
<div class="row">
  <div class="col-12 col-lg-10">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Export y cierre</h5>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <x-adminlte-select2 name="year" label="Seleccione Año" >
                @foreach ($years ?? [] as $year)
                  <option value="{{ $year->id }}" @if($year->id == now()->year) selected @endif >{{ $year->name }}</option>
                @endforeach
            </x-adminlte-select2>
          </div>
          <div class="col-md-4">
            <x-adminlte-select2 name="month" label="Seleccione Mes" >
                @foreach ($months ?? [] as $month)
                  <option value="{{ $month->id }}" @if($month->id == now()->month) selected @endif >{{ $month->name }}</option>
                @endforeach
            </x-adminlte-select2>
          </div>
          <div class="col-md-2">
            <x-adminlte-select2 name="type_report" label="Seleccione Tipo" >
                @foreach ($type_reports ?? [] as $type_report)
                  <option value="{{ $type_report->id }}" @if($type_report->id == 1) selected @endif >{{ $type_report->name }}</option>
                @endforeach
            </x-adminlte-select2>
          </div>

          <div class="col-auto d-flex align-items-end mb-3 new-template">
            <div class="icheck-primary d-inline">
              <input type="checkbox" id="new_template" name="new_template" checked />
              <label for="new_template"></label>
            </div>
            <label for="new_template" class="col-form-label">Nueva plantilla</label>
          </div>

        </div>
        <div class="row">
          <div class="col-md-12">
            <x-adminlte-button label="Exportar Excel" theme="success" icon="fa fa-file-excel" onclick="onGenerateExcel();" />
            <x-adminlte-button label="Exportar TXT" theme="primary" icon="fa fa-clipboard" onclick="onGenerateTxt();" />
            <x-adminlte-button label="Cerrar Mes" theme="danger" icon="fa fa-times-circle" onclick="onCloseSiafMonth();" />
            <x-adminlte-button label="Exportar Pendientes" theme="secondary" icon="fa fa-file-excel" onclick="onGeneratePending();" />
          </div>
        </div>
      </div>
    </div>
    </div>
</div>
@stop

@push('js')
<script>

function onGenerateExcel() {

  const yearElement = document.getElementById('year');
  const monthElement = document.getElementById('month');
  const typeReportElement = document.getElementById('type_report');

  const year = yearElement.options[yearElement.selectedIndex].value;
  const month = monthElement.options[monthElement.selectedIndex].value;
  const typeReport = typeReportElement.options[typeReportElement.selectedIndex].value;

  const objData = {
    month: month,
    year: year,
    type_report: typeReport
  };

  let queryParams = new URLSearchParams(objData).toString();

  fetch(`{{ route("documentSiafs.exportExcel") }}?${queryParams}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.status}`);
      }
      return response.blob();
    })
    .then(blob => {
      const month_name = monthElement.options[monthElement.selectedIndex].text;
      const type_name = typeReportElement.options[typeReportElement.selectedIndex].text;

      const file = new Blob([blob], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      });

      const a = document.createElement('a');
      a.style.display = 'none';
      document.body.appendChild(a);
      a.download = `excelSiaf_${month_name}_${year}_${type_name}.xlsx`;
      a.href = URL.createObjectURL(file);
      a.target = '_blank';
      a.click();
      document.body.removeChild(a);
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function onCloseSiafMonth() {
  swal
    .fire({
      title: '¿Estás seguro de cerrar el mes?',
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: 'Si, cerrar',
      denyButtonText: 'No, cancelar',
      customClass: {
        actions: 'my-actions',
        confirmButton: 'btn btn-primary',
        denyButton: 'btn btn-secondary',
      },
    })
    .then((result) => {
      if (result.isConfirmed) {

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const yearElement = document.getElementById('year');
        const monthElement = document.getElementById('month');
        const typeReportElement = document.getElementById('type_report');

        const year = yearElement.options[yearElement.selectedIndex].value;
        const month = monthElement.options[monthElement.selectedIndex].value;
        const typeReport = typeReportElement.options[typeReportElement.selectedIndex].value;

        const objData = {
          month: month,
          year: year,
          type_report: typeReport
        };

        fetch('{{ route("documentSiafs.closeSiafsByMonth") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(objData)
        })
        .then(response => {
          if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: data.message,
            timer: 3000
          });
        })
        .catch(error => {
          swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al cerrar el mes.',
            timer: 3000
          });
          console.log(error);
        });
      }
    });
}

function onGenerateTxt() {

  const yearElement = document.getElementById('year');
  const monthElement = document.getElementById('month');
  const typeReportElement = document.getElementById('type_report');
  const newTemplateElement = document.getElementById('new_template');

  const year = parseInt(yearElement.options[yearElement.selectedIndex].value);
  const month = parseInt(monthElement.options[monthElement.selectedIndex].value);
  const typeReport = parseInt(typeReportElement.options[typeReportElement.selectedIndex].value);
  const newTemplate = newTemplateElement.checked;

  const objData = {
    month: month,
    year: year,
    type_report: typeReport,
    new_template: newTemplate
  };

  let queryParams = new URLSearchParams(objData).toString();

  if (typeReport === 1) {

    fetch(`{{ route("documentSiafs.exportTxtPle") }}?${queryParams}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`Network response was not ok: ${response.status}`);
        }
        return response.blob();
      })
      .then(event => {
        const date = new Date();
        const le = "LE";
        const ruc = "{{ config('constants.ruc_sat') }}";
        // const year = this.year;
        const mm = month.toString().padStart(2, '0');
        const dd = "00";
        const llll = newTemplate ? "080400" : "080100";
        const cc = newTemplate ? "02" : "00";
        const o = "1";
        const l = "1";
        const m = "1";
        const g = newTemplate ? "2" : "1";
        const name_file = `${le}${ruc}${year}${mm}${dd}${llll}${cc}${o}${l}${m}${g}.txt`;

        const file = new Blob([event], {
          type: 'text/csv',
        });

        const a = document.createElement('a');
        a.style.display = 'none';
        document.body.appendChild(a);
        a.download = name_file;
        a.href = URL.createObjectURL(file);
        a.target = '_blank';
        a.click();
        document.body.removeChild(a);
      })
      .catch(error => {
        swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ocurrió un error al cerrar el mes.',
          timer: 3000
        });
        console.log(error);
      });
  } else if (typeReport === 2) {

    fetch(`{{ route("documentSiafs.exportTxtPlameDetail") }}?${queryParams}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`Network response was not ok: ${response.status}`);
        }
        return response.blob();
      })
      .then(event => {
        const date = new Date();
        const ffff = "0601";
        const ruc = "{{ config('constants.ruc_sat') }}";
        // const year = this.year;
        const mm = month.toString().padStart(2, '0');
        const name_file = `${ffff}${year}${mm}${ruc}.4ta`;

        const file = new Blob([event], {
          type: 'text/csv',
        });

        const a = document.createElement('a');
        a.style.display = 'none';
        document.body.appendChild(a);
        a.download = name_file;
        a.href = URL.createObjectURL(file);
        a.target = '_blank';
        a.click();
        document.body.removeChild(a);

      })
      .catch(error => {
        swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ocurrió un error al cerrar el mes.',
          timer: 3000
        });
        console.log(error);
      });


    fetch(`{{ route("documentSiafs.exportTxtPlameProvidersName") }}?${queryParams}`)

      .then(response => {
        if (!response.ok) {
          throw new Error(`Network response was not ok: ${response.status}`);
        }
        return response.blob();
      })
      .then(event => {
        const date = new Date();
        const ffff = "0601";
        const ruc = "{{ config('constants.ruc_sat') }}";
        // const year = this.year;
        const mm = month.toString().padStart(2, '0');
        const name_file = `${ffff}${year}${mm}${ruc}.ps4`;

        const file = new Blob([event], {
          type: 'text/csv',
        });

        const a = document.createElement('a');
        a.style.display = 'none';
        document.body.appendChild(a);
        a.download = name_file;
        a.href = URL.createObjectURL(file);
        a.target = '_blank';
        a.click();
        document.body.removeChild(a);

      })
      .catch(error => {
        swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ocurrió un error al cerrar el mes.',
          timer: 3000
        });
        console.log(error);
      });
  }
}

function onGeneratePending() {

  const yearElement = document.getElementById('year');
  const monthElement = document.getElementById('month');
  const typeReportElement = document.getElementById('type_report');

  const year = yearElement.options[yearElement.selectedIndex].value;
  const month = monthElement.options[monthElement.selectedIndex].value;
  const typeReport = typeReportElement.options[typeReportElement.selectedIndex].value;

  const objData = {
    month: month,
    year: year,
    type_report: typeReport
  };

  let queryParams = new URLSearchParams(objData).toString();

  fetch(`{{ route("documentSiafs.exportExcelPending") }}?${queryParams}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.status}`);
      }
      return response.blob();
    })
    .then(blob => {

      const file = new Blob([blob], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      });

      const a = document.createElement('a');
      a.style.display = 'none';
      document.body.appendChild(a);
      a.download = `excelSiafPending.xlsx`;
      a.href = URL.createObjectURL(file);
      a.target = '_blank';
      a.click();
      document.body.removeChild(a);
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

$(document).ready(function () {
  $('#type_report').on('change', function() {
    const selectedValue = $(this).val();
    const newTemplateElement = document.querySelector('.new-template');

    if (selectedValue === '1') {
      newTemplateElement.style.removeProperty('display');
    } else if (selectedValue === '2') {
      newTemplateElement.style.setProperty('display', 'none', 'important');
    }
  });
});

</script>
@endpush


