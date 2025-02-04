<div id="stepper" class="bs-stepper">
  <div class="bs-stepper-header" role="tablist">
    <!-- your steps here -->
    <div class="step" data-target="#siaf-part">
      <button type="button" class="step-trigger" role="tab" aria-controls="siaf-part" id="siaf-part-trigger">
        <span class="bs-stepper-circle">1</span>
        <span class="bs-stepper-label">Registro SIAF</span>
      </button>
    </div>
    <div class="line"></div>
    <div class="step" data-target="#detail-part">
      <button type="button" class="step-trigger" role="tab" aria-controls="detail-part" id="detail-part-trigger">
        <span class="bs-stepper-circle">2</span>
        <span class="bs-stepper-label">Detalle SIAF</span>
      </button>
    </div>
  </div>
  <div class="bs-stepper-content">
    <!-- your steps content here -->
    <div id="siaf-part" class="content" role="tabpanel" aria-labelledby="siaf-part-trigger">
      @include('admin.document_siaf._form_step_1')
      <a class="btn btn-primary" onclick="nextStep();">Siguiente</a>
    </div>
    <div id="detail-part" class="content" role="tabpanel" aria-labelledby="detail-part-trigger">
      @include('admin.document_siaf._form_step_2')
      <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
      @if( ($documentSiaf->status ?? '') !== 3)<button type="submit" class="btn btn-success">Guardar</button>@endif
    </div>
  </div>
</div>