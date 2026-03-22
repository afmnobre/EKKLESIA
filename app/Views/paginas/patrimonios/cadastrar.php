<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Cadastrar Novo Bem</h3>
        <a href="<?= url('patrimonios') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="<?= url('patrimonios/salvar') ?>" method="POST">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Nome do Patrimônio</label>
                        <input type="text" name="patrimonio_bem_nome" class="form-control shadow-none" placeholder="Ex: Projetor Epson, Cadeira de Alumínio..." required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Localização Inicial</label>
                        <select name="patrimonio_bem_local_id" class="form-select shadow-none" required>
                            <option value="">Selecione o Local...</option>
                            <?php foreach($locais as $l): ?>
                                <option value="<?= $l['patrimonio_local_id'] ?>"><?= $l['patrimonio_local_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Descrição / Detalhes</label>
                        <textarea name="patrimonio_bem_descricao" class="form-control shadow-none" rows="3" placeholder="Marca, modelo, número de série..."></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Data de Aquisição</label>
                        <input type="date" name="patrimonio_bem_data_aquisicao" class="form-control shadow-none">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Valor Estipulado (R$)</label>
                        <input type="number" step="0.01" name="patrimonio_bem_valor" class="form-control shadow-none" placeholder="0,00">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Status Inicial</label>
                        <select name="patrimonio_bem_status" class="form-select shadow-none">
                            <option value="ativo">Ativo (Em uso)</option>
                            <option value="manutencao">Em Manutenção</option>
                            <option value="danificado">Danificado</option>
                            <option value="baixado">Baixado</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> Salvar e Continuar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
