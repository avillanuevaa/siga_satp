<div class="modal fade" id="editOfficeModal" tabindex="-1" aria-labelledby="editOfficeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOfficeModalLabel">Editar Oficina</h5>
            </div>
            <form id="editOfficeForm">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_office_id" name="office_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_code_ue" class="form-label">Código UE <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_code_ue" name="code_ue" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Descripción</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_code_office" class="form-label">Código Oficina</label>
                                <input type="text" class="form-control" id="edit_code_office" name="code_office">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_annexed" class="form-label">Anexo</label>
                                <input type="text" class="form-control" id="edit_annexed" name="annexed">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="edit_goal" class="form-label">Objetivo</label>
                                <textarea class="form-control" id="edit_goal" name="goal" rows="2"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_active" name="active" value="1">
                                    <label class="form-check-label" for="edit_active">
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
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
