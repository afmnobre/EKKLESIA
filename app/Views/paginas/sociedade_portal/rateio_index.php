<?php
$this->rawview('sociedade_portal/header', [
    'titulo' => 'Criar Lista de Evento',
    'ativo'  => 'rateio',
    'sociedade' => $sociedade
]);
?>

<div class="container pb-5">
    <div class="card card-operacional p-4 mb-4">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-journal-plus me-2 text-primary"></i>Nova Lista de Alimentos / Ingredientes</h5>
        <form action="<?= url('sociedadeLider/rateioCadastrar') ?>" method="POST">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-muted">Título do Evento</label>
                    <input type="text" name="titulo" class="form-control text-dark" placeholder="Ex: Lista EBF 2026" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Entregar Até</label>
                    <input type="date" name="data_limite" class="form-control text-dark" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold text-muted">Texto Informativo no Topo (PIX, avisos, horários, etc.)</label>
                    <textarea name="descricao" class="form-control text-dark" rows="3" placeholder="Queridos irmãos. Pedimos contribuição de alimentos... Doação via PIX:..."></textarea>
                </div>
            </div>

            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Itens e Quantidade de Cotas (Linhas)</h6>
            <div id="container-itens">
                <div class="row g-2 mb-2 item-linha">
                    <div class="col-8">
                        <input type="text" name="item_descricao[]" class="form-control form-control-sm text-dark" placeholder="Ex: 2 pacotes de pão de forma 500gr" required>
                    </div>
                    <div class="col-3">
                        <input type="number" name="item_cotas[]" class="form-control form-control-sm text-dark" placeholder="Nº de Linhas (Cotas)" value="1" min="1" required>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.closest('.item-linha').remove()"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill mt-2" id="btn-add-item">
                <i class="bi bi-plus-lg me-1"></i> Adicionar Mais Itens na Configuração
            </button>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Gerar Lista e Link do WhatsApp</button>
            </div>
        </form>
    </div>

    <div class="card card-operacional p-3">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-list-stars me-2 text-primary"></i>Listas Ativas</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Evento</th>
                        <th>Cotas Preenchidas</th>
                        <th>Link do WhatsApp</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($items)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Nenhuma lista gerada ainda.</td></tr>
                    <?php endif; foreach($items as $i):
                        $linkPublico = full_url("sociedadeLider/rateioParticipar/{$i['rateio_token']}");
                    ?>
                    <tr>
                        <td><span class="fw-bold text-dark"><?= htmlspecialchars($i['rateio_titulo']) ?></span><br><small class="text-muted">Limite: <?= date('d/m/Y', strtotime($i['rateio_data_limite'])) ?></small></td>
                        <td>
                            <span class="badge bg-success"><?= $i['cotas_preenchidas'] ?> / <?= $i['total_cotas'] ?> preenchidas</span>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <input type="text" class="form-control" value="<?= $linkPublico ?>" readonly>
                                <button class="btn btn-outline-primary" type="button" onclick="navigator.clipboard.writeText('<?= $linkPublico ?>'); alert('Link copiado!');"><i class="bi bi-copy"></i></button>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="<?= url('sociedadeLider/rateioExcluir/'.$i['rateio_id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Excluir esta lista e todas as assinaturas?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-add-item').addEventListener('click', function() {
    const html = `
    <div class="row g-2 mb-2 item-linha">
        <div class="col-8">
            <input type="text" name="item_descricao[]" class="form-control form-control-sm text-dark" placeholder="Ex: Salsicha 1 kg" required>
        </div>
        <div class="col-3">
            <input type="number" name="item_cotas[]" class="form-control form-control-sm text-dark" placeholder="Nº de Linhas (Cotas)" value="1" min="1" required>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.closest('.item-linha').remove()"><i class="bi bi-trash"></i></button>
        </div>
    </div>`;
    document.getElementById('container-itens').insertAdjacentHTML('beforeend', html);
});
</script>

<?php $this->rawview('sociedade_portal/footer'); ?>
