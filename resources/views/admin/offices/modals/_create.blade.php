<div class="modal fade" id="createOfficeModal" tabindex="-1" aria-labelledby="createOfficeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createOfficeModalLabel">Crear Nueva Oficina</h5>
            </div>
            <form id="createOfficeForm">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_code_ue" class="form-label">Código UE <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_code_ue" name="code_ue" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="create_description" class="form-label">Descripción</label>
                                <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="create_phone" name="phone">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_code_office" class="form-label">Código Oficina</label>
                                <input type="text" class="form-control" id="create_code_office" name="code_office">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="create_annexed" class="form-label">Anexo</label>
                                <input type="text" class="form-control" id="create_annexed" name="annexed">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="create_goal" class="form-label">Objetivo</label>
                                <textarea class="form-control" id="create_goal" name="goal" rows="2"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="create_active" name="active" value="1" checked>
                                    <label class="form-check-label" for="create_active">
                                        Activo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
