<div class="modal-body p-4">
    <form id="formEnderecoMembro" method="POST" action="<?= url('membros/updateEndereco') ?>">

        <input type="hidden" name="membro_id" id="membro_id" value="<?= $membro['membro_id'] ?>">

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold text-secondary">CEP</label>
                <div class="input-group mb-3">
                    <input type="text" name="membro_cep" id="membro_cep" class="form-control"
                           placeholder="00000-000" maxlength="9" autofocus
                           value="<?= $membro['membro_endereco_cep'] ?? '' ?>">
                    <button class="btn btn-outline-primary" type="button" id="btnBuscarCep">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-8">
                <label class="form-label fw-bold text-secondary">Logradouro (Rua/Avenida)</label>
                <input type="text" name="membro_rua" id="membro_rua" class="form-control"
                       placeholder="Aguardando CEP..."
                       value="<?= $membro['membro_endereco_rua'] ?? '' ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold text-secondary">Número</label>
                <input type="text" name="membro_numero" id="membro_numero" class="form-control"
                       placeholder="Ex: 123"
                       value="<?= $membro['membro_endereco_numero'] ?? '' ?>">
            </div>
            <div class="col-md-9">
                <label class="form-label fw-bold text-secondary">Complemento</label>
                <input type="text" name="membro_complemento" id="membro_complemento" class="form-control"
                       placeholder="Apto, Bloco, Fundos..."
                       value="<?= $membro['membro_endereco_complemento'] ?? '' ?>">
            </div>

            <div class="col-md-5">
                <label class="form-label fw-bold text-secondary">Bairro</label>
                <input type="text" name="membro_bairro" id="membro_bairro" class="form-control"
                       value="<?= $membro['membro_endereco_bairro'] ?? '' ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold text-secondary">Cidade</label>
                <input type="text" name="membro_cidade" id="membro_cidade" class="form-control"
                       value="<?= $membro['membro_endereco_cidade'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold text-secondary">UF</label>
                <input type="text" name="membro_uf" id="membro_uf" class="form-control" maxlength="2"
                       value="<?= $membro['membro_endereco_estado'] ?? '' ?>">
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success fw-bold px-4">
                <i class="bi bi-save me-2"></i><?= empty($membro['membro_endereco_id']) ? 'SALVAR ENDEREÇO' : 'ATUALIZAR ENDEREÇO' ?>
            </button>
        </div>
    </form>
    </div>
