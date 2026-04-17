<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Editar Patrimônio</h3>
        <div class="d-flex gap-2">
            <a href="<?= url('patrimonios/detalhes/'.$bem['patrimonio_bem_id']) ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Cancelar
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="<?= url('patrimonios/atualizar') ?>" method="POST">
                <input type="hidden" name="patrimonio_bem_id" value="<?= $bem['patrimonio_bem_id'] ?>">

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Nome do Patrimônio</label>
                        <input type="text" name="patrimonio_bem_nome" class="form-control shadow-none" value="<?= $bem['patrimonio_bem_nome'] ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Categoria</label>
                        <select name="patrimonio_bem_categoria_id" class="form-select shadow-none" required>
                            <option value="">Selecione...</option>
                            <?php foreach($categorias as $c): ?>
                                <?php $selected = ($bem['patrimonio_bem_categoria_id'] == $c['patrimonio_categoria_id']) ? 'selected' : ''; ?>
                                <option value="<?= $c['patrimonio_categoria_id'] ?>" <?= $selected ?>>
                                    <?= $c['patrimonio_categoria_nome'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Descrição / Detalhes</label>
                        <textarea name="patrimonio_bem_descricao" class="form-control shadow-none" rows="3"><?= $bem['patrimonio_bem_descricao'] ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Data de Aquisição</label>
                        <input type="date" name="patrimonio_bem_data_aquisicao" class="form-control shadow-none" value="<?= $bem['patrimonio_bem_data_aquisicao'] ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Valor (R$)</label>
                        <input type="number" step="0.01" name="patrimonio_bem_valor" class="form-control shadow-none" value="<?= $bem['patrimonio_bem_valor'] ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="patrimonio_bem_status" class="form-select shadow-none">
                            <?php
                            $status_opcoes = ['ativo', 'manutencao', 'danificado', 'baixado', 'extraviado'];
                            foreach($status_opcoes as $opt):
                                $selected = ($bem['patrimonio_bem_status'] == $opt) ? 'selected' : '';
                            ?>
                                <option value="<?= $opt ?>" <?= $selected ?>><?= ucfirst($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-5 fw-bold">
                        <i class="bi bi-check-lg me-1"></i> SALVAR ALTERAÇÕES
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
