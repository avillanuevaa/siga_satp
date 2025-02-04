<fieldset class="border p-3 mb-3">
  <legend class="w-auto font-weight-bold">Información del Documento {{ $documentSiaf->siaf ?? '' }}</legend>
  <div class="row">
    <div class="col-md-4">
      <x-adminlte-input name="siaf" type="number" label="SIAF" placeholder="SIAF" value="{{ $documentSiaf->siaf ?? '' }}" enable-old-support required />
    </div>
    <div class="col-md-8">
      <div class="form-group">
        <label for="">CP</label>
        <div class="row">
          <div class="col-md-6">
            <x-adminlte-input name="doc_code_first" type="number" placeholder="CP" value="{{ $documentSiaf->doc_code_first ?? '' }}" enable-old-support required />
          </div>
          <div class="col-md-6">
            <x-adminlte-input name="num_doc_first" type="number" placeholder="CP" value="{{ $documentSiaf->num_doc_first ?? '' }}" enable-old-support required />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="">H/A*</label>
        <div class="row">
          <div class="col-md-4">
            <x-adminlte-input name="ha_1_first" type="number" placeholder="H/A" value="{{ $documentSiaf->ha_1_first ?? '' }}" enable-old-support required />
          </div>
          <div class="col-md-4">
            <x-adminlte-input name="ha_2_first" type="number" placeholder="H/A" value="{{ $documentSiaf->ha_2_first ?? '' }}" enable-old-support required />
          </div>
          <div class="col-md-4">
            <x-adminlte-input name="ha_3_first" type="number" placeholder="H/A" value="{{ $documentSiaf->ha_3_first ?? '' }}" enable-old-support required />
          </div>
        </div>
      </div>
    </div>
  </div>

</fieldset>