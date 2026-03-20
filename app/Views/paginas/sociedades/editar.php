<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('sociedades') ?>">Sociedades</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Configurações da Sociedade</h5>
                </div>
                <form action="<?= url('sociedades/atualizar') ?>" method="POST" class="card-body p-4">
                    <input type="hidden" name="sociedade_id" value="<?= $sociedade['sociedade_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nome da Sociedade</label>
                        <input type="text" name="nome" class="form-control" value="<?= $sociedade['sociedade_nome'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Público-Alvo (Gênero)</label>
                        <select name="genero" class="form-select" required>
                            <option value="Ambos" <?= $sociedade['sociedade_genero'] == 'Ambos' ? 'selected' : '' ?>>Ambos (Misto)</option>
                            <option value="Masculino" <?= $sociedade['sociedade_genero'] == 'Masculino' ? 'selected' : '' ?>>Apenas Homens</option>
                            <option value="Feminino" <?= $sociedade['sociedade_genero'] == 'Feminino' ? 'selected' : '' ?>>Apenas Mulheres</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small">Idade Mínima</label>
                            <input type="number" name="idade_min" class="form-control" value="<?= $sociedade['sociedade_idade_min'] ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small">Idade Máxima</label>
                            <input type="number" name="idade_max" class="form-control" value="<?= $sociedade['sociedade_idade_max'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Status</label>
                        <select name="status" class="form-select">
                            <option value="Ativo" <?= $sociedade['sociedade_status'] == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="Inativo" <?= $sociedade['sociedade_status'] == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="<?= url('sociedades') ?>" class="btn btn-light border">Voltar</a>
                        <button type="submit" class="btn btn-primary px-4">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
