<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">📋 Membros Cadastrados</h3>

        <div class="d-flex gap-3">
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="buscaMembro" class="form-control border-start-0" placeholder="Buscar por nome...">
            </div>

            <a href="<?= url('membros/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Novo Membro
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelaMembros">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th>Id</th>
                            <th class="ps-4">Membro</th>
                            <th>Cargos / Funções</th>
                            <th>Contato</th>
                            <th>Status</th>
                            <th class="text-center">Ações Rápidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($membros as $membro): ?>
                        <tr class="linha-membro">
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= $membro['membro_registro_interno'] ?>
                                </span>
                            </td>
                            <td class="ps-4">
                                <span class="fw-bold d-block text-dark nome-membro"><?= htmlspecialchars($membro['membro_nome']) ?></span>
                                <small class="text-muted">Nasc: <?= date('d/m/Y', strtotime($membro['membro_data_nascimento'])) ?></small>
                            </td>
                            <td>
                                <?php if (!empty($membro['cargos_nomes'])): ?>
                                    <?php
                                        $cargos = explode(', ', $membro['cargos_nomes']);
                                        foreach ($cargos as $cargo):
                                    ?>
                                        <span class="badge bg-light text-primary border shadow-sm mb-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-tag-fill me-1"></i><?= mb_strtoupper($cargo) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted small italic">Membro Comum</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="d-block text-muted"><?= htmlspecialchars($membro['membro_telefone']) ?></small>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $membro['membro_status'] == 'Ativo' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= strtoupper($membro['membro_status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm border">
                                    <button class="btn btn-white btn-sm btn-gerar-carteirinha" data-id="<?= $membro['membro_id'] ?>" title="Imprimir Carteirinha">🆔</button>
                                    <?php if (!empty($membro['membro_data_batismo'])): ?>
                                    <button type="button" class="btn btn-sm btn-abrir-certificado"
                                        data-id="<?= $membro['membro_id'] ?>">
                                        🎓
                                    </button>
                                    <?php endif; ?>

                                    <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalEndereco<?= $membro['membro_id'] ?>" title="Endereço">📍</button>
                                    <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalFoto<?= $membro['membro_id'] ?>" title="Foto">📸</button>

                                    <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalCargos<?= $membro['membro_id'] ?>" title="Cargos e Funções">🏷️</button>

                                    <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalStatus<?= $membro['membro_id'] ?>" title="Status">🔄</button>
                                    <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalHistorico<?= $membro['membro_id'] ?>" title="Histórico">📜</button>
                                    <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalFicha<?= $membro['membro_id'] ?>" title="Ficha Completa">📄</button>
                                </div>
                                <a href="<?= url('membros/edit/' . $membro['membro_id']) ?>" class="btn btn-link btn-sm text-primary ms-2 text-decoration-none fw-bold">✏️</a>
                            </td>
                        </tr>

                        <?php include 'modais.php'; ?>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('buscaMembro').addEventListener('keyup', function() {
    let busca = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    let linhas = document.querySelectorAll('.linha-membro');

    linhas.forEach(linha => {
        let nomeElemento = linha.querySelector('.nome-membro');
        if (nomeElemento) {
            let nome = nomeElemento.innerText.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
            linha.style.display = nome.includes(busca) ? "" : "none";
        }
    });
});
</script>
