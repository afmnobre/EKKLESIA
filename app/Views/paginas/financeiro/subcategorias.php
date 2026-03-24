<?php
// Encontrar a categoria específica nos dados para pegar o nome e tipo
$cat_id_url = $_GET['cat_id'] ?? null;
$categoria_atual = null;

foreach($dados as $cat) {
    if ($cat['id'] == $cat_id_url) {
        $categoria_atual = $cat;
        break;
    }
}
?>

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="<?= url('financeiro/categorias') ?>" class="btn btn-link text-decoration-none p-0 mb-2">
            <i class="bi bi-arrow-left"></i> Voltar para Categorias
        </a>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-node-plus me-2 text-primary"></i>
                    Subcategorias de: <span class="text-primary"><?= $categoria_atual['nome'] ?? 'Não encontrada' ?></span>
                </h3>
                <span class="badge <?= ($categoria_atual['tipo'] ?? '') == 'entrada' ? 'bg-success' : 'bg-danger' ?> mt-1">
                    Fluxo de <?= ucfirst($categoria_atual['tipo'] ?? '') ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold py-3">
                    Nova Subcategoria
                </div>
                <div class="card-body">
                    <form action="<?= url('financeiro/salvar_subcategoria') ?>" method="POST">
                        <input type="hidden" name="categoria_id" value="<?= $cat_id_url ?>">

                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Nome da Subcategoria</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Conta de Luz, Aluguel..." required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-plus-lg"></i> Adicionar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold py-3 text-secondary">
                    Itens Cadastrados
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th class="ps-4">Nome da Subcategoria</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($categoria_atual['subs'])): ?>
                                    <?php foreach($categoria_atual['subs'] as $sub): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <i class="bi bi-dot text-primary fs-4"></i> <?= $sub['nome'] ?>
                                        </td>
                                        <td class="text-end pe-4">
											<a href="<?= url('financeiro/excluir_subcategoria/'.$sub['id'].'?cat_id='.$cat_id_url) ?>"
											   class="btn btn-sm btn-outline-danger border-0"
											   onclick="return confirm('Deseja realmente excluir esta subcategoria?')">
												<i class="bi bi-trash"></i>
											</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted small">
                                            Nenhuma subcategoria cadastrada para este grupo.<br>
                                            Use o formulário ao lado para começar.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
