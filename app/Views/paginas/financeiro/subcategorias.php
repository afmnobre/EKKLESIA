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
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Oferta de Missões, Dízimos..." required autofocus>
                        </div>

                        <!-- Novo campo para definir a chave do sistema caso seja Entrada -->
                        <?php if (($categoria_atual['tipo'] ?? '') === 'entrada'): ?>
                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Tipo de Agrupamento (Relatórios)</label>
                            <select name="chave_sistema" class="form-select">
                                <option value="">-- Padrão (Sem Agrupamento) --</option>
                                <option value="DIZIMO">DÍZIMO</option>
                                <option value="OFERTA">OFERTA</option>
                            </select>
                            <div class="form-text text-muted small">
                                Subcategorias marcadas como Dízimo ou Oferta serão agrupadas por dia nos relatórios gerais.
                            </div>
                        </div>
                        <?php endif; ?>

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
                                    <th>Agrupamento Especial</th>
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
                                        <td>
                                            <?php
                                                $chave = $sub['chave_sistema'] ?? null;
                                                if ($chave === 'DIZIMO'):
                                            ?>
                                                <span class="badge bg-info text-dark"><i class="bi bi-tag-fill me-1"></i> Dízimo</span>
                                            <?php elseif ($chave === 'OFERTA'): ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-tag-fill me-1"></i> Oferta</span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <!-- Botão Editar -->
                                            <button type="button" class="btn btn-sm btn-outline-primary border-0 me-1"
                                                onclick="abrirModalEdicao(<?= $sub['id'] ?>, '<?= htmlspecialchars($sub['nome'], ENT_QUOTES) ?>', '<?= $sub['chave_sistema'] ?? '' ?>')">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Botão Excluir -->
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
                                        <td colspan="3" class="text-center py-5 text-muted small">
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

<!-- Modal de Edição de Subcategoria -->
<div class="modal fade" id="modalEditarSubcategoria" tabindex="-1" aria-labelledby="modalEditarSubcategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalEditarSubcategoriaLabel">Editar Subcategoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('financeiro/atualizar_subcategoria') ?>" method="POST">
                <div class="modal-body">
                    <!-- Campos Ocultos -->
                    <input type="hidden" name="categoria_id" value="<?= $cat_id_url ?>">
                    <input type="hidden" name="subcategoria_id" id="edit_subcategoria_id">

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Nome da Subcategoria</label>
                        <input type="text" name="nome" id="edit_nome" class="form-control" required>
                    </div>

                    <?php if (($categoria_atual['tipo'] ?? '') === 'entrada'): ?>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Tipo de Agrupamento (Relatórios)</label>
                        <select name="chave_sistema" id="edit_chave_sistema" class="form-select">
                            <option value="">-- Padrão (Sem Agrupamento) --</option>
                            <option value="DIZIMO">DÍZIMO</option>
                            <option value="OFERTA">OFERTA</option>
                        </select>
                        <div class="form-text text-muted small">
                            Altere a chave para agrupar ou deixe padrão para remover do agrupamento especial.
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalEdicao(id, nome, chaveSistema) {
    // Preenche os campos do modal
    document.getElementById('edit_subcategoria_id').value = id;
    document.getElementById('edit_nome').value = nome;

    // Se o select de chave do sistema existir na DOM (tipo entrada), seleciona a opção correta
    let selectChave = document.getElementById('edit_chave_sistema');
    if (selectChave) {
        selectChave.value = chaveSistema || "";
    }

    // Abre o modal usando o Bootstrap
    var myModal = new bootstrap.Modal(document.getElementById('modalEditarSubcategoria'));
    myModal.show();
}
</script>
