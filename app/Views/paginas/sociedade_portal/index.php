<?php
// Carrega o topo e o menu. Passamos 'membros' como ativo para marcar o menu corretamente.
$this->rawview('sociedade_portal/header', [
    'titulo' => 'Membros da Sociedade',
    'ativo'  => 'membros',
    'sociedade' => $sociedade // Passamos os dados da sociedade para o header
]);
?>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-operacional p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-check me-2 text-primary"></i>Membros da Sociedade</h5>
                    <span class="badge bg-primary rounded-pill"><?= count($membros) ?> membros</span>
                </div>
                <div class="list-group list-group-flush">
                    <?php if(empty($membros)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-person-slash fs-1 text-muted opacity-25"></i>
                            <p class="text-muted mt-2">Nenhum membro vinculado ainda.</p>
                        </div>
                    <?php endif; ?>
					<?php foreach ($membros as $m): ?>
						<div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
							<div class="d-flex align-items-center gap-3">
								<?php
									// Montando o caminho exato conforme sua estrutura de pastas
									$fotoMembro = !empty($m['membro_foto_arquivo'])
										? url("assets/uploads/{$m['membro_igreja_id']}/membros/{$m['membro_registro_interno']}/{$m['membro_foto_arquivo']}")
										: null;
								?>

								<?php if ($fotoMembro): ?>
									<img src="<?= $fotoMembro ?>" class="avatar-membro shadow-sm" alt="Foto">
								<?php else: ?>
									<div class="bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
										<i class="bi bi-person text-secondary fs-4"></i>
									</div>
								<?php endif; ?>

								<div>
									<span class="fw-bold d-block mb-0 text-dark"><?= $m['membro_nome'] ?></span>
									<span class="badge bg-light text-primary border-0 p-0"><?= $m['sociedade_membro_funcao'] ?></span>
								</div>
							</div>

							<div class="d-flex gap-2">
								<button class="btn btn-outline-secondary btn-sm rounded-pill px-3"
										onclick="abrirModalObs(<?= $m['membro_id'] ?>, '<?= $m['membro_nome'] ?>')">
									<i class="bi bi-chat-left-text"></i>
								</button>
								<button class="btn btn-outline-danger btn-sm rounded-pill px-3"
										onclick="prepararAcao(<?= $m['membro_id'] ?>, 'desvincular', '<?= $m['membro_nome'] ?>')">
									<i class="bi bi-person-x"></i>
								</button>
							</div>
						</div>
					<?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-operacional p-3 bg-white border-top border-4 border-primary">
                <p class="secao-titulo mb-3"><i class="bi bi-search me-2"></i>Sugeridos para Vínculo</p>
                <div style="max-height: 500px; overflow-y: auto; padding-right: 5px;">
                    <?php foreach ($sugestoes as $s): ?>
                        <div class="d-flex justify-content-between align-items-center p-3 mb-2 bg-light rounded shadow-sm border-start border-3 border-primary">
                            <small class="fw-bold text-truncate" style="max-width: 160px;"><?= $s['membro_nome'] ?></small>
                            <button class="btn btn-primary btn-sm btn-add shadow-sm" onclick="prepararAcao(<?= $s['membro_id'] ?>, 'vincular', '<?= $s['membro_nome'] ?>')">
                                <i class="bi bi-plus-lg"></i> Vincular
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i id="confirmacaoIcone" class="bi bi-question-circle text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 id="confirmacaoTitulo" class="fw-bold">Confirmar?</h5>
                <p id="confirmacaoMensagem" class="text-muted small mb-4"></p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-100 fw-bold" data-bs-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-primary w-100 fw-bold" id="btnConfirmarAcao">Sim</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalObservacoes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="obsMembroNome">Observações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted">NOVA ANOTAÇÃO</label>
                    <textarea id="novaObservacao" class="form-control" rows="3" placeholder="Digite algo importante sobre este membro..."></textarea>
                    <button class="btn btn-primary btn-sm mt-2 w-100 fw-bold" id="btnSalvarObs">
                        <i class="bi bi-plus-lg me-1"></i> Adicionar ao Histórico
                    </button>
                </div>

                <hr>

                <label class="small fw-bold text-muted mb-2">HISTÓRICO RECENTE</label>
                <div id="listaHistorico" style="max-height: 250px; overflow-y: auto;">
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNotificacao" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div id="notificacaoIcone" class="mb-3"></div>
                <h5 id="notificacaoTitulo" class="fw-bold"></h5>
                <p id="notificacaoMensagem" class="text-muted small mb-4"></p>
                <button type="button" class="btn w-100 fw-bold" data-bs-dismiss="modal" id="notificacaoBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
// Variáveis globais para armazenar a ação pendente
let acaoPendente = { id: null, rota: null };

/**
 * Prepara o modal de confirmação com os dados específicos
 */
function prepararAcao(id, rota, nome) {
    acaoPendente.id = id;
    acaoPendente.rota = rota;

    const modalEl = document.getElementById('modalConfirmacao');
    const titulo = document.getElementById('confirmacaoTitulo');
    const msg = document.getElementById('confirmacaoMensagem');
    const icone = document.getElementById('confirmacaoIcone');
    const btnSim = document.getElementById('btnConfirmarAcao');

    if (rota === 'vincular') {
        titulo.innerText = "Vincular Membro";
        msg.innerText = `Deseja vincular ${nome} a esta sociedade?`;
        icone.className = "bi bi-person-plus-fill text-primary";
        btnSim.className = "btn btn-primary w-100 fw-bold";
    } else {
        titulo.innerText = "Remover Membro";
        msg.innerText = `Tem certeza que deseja desvincular ${nome}?`;
        icone.className = "bi bi-person-dash-fill text-danger";
        btnSim.className = "btn btn-danger w-100 fw-bold";
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

// Evento do botão "SIM" do modal de confirmação
document.getElementById('btnConfirmarAcao').addEventListener('click', function() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalConfirmacao'));
    modal.hide();

    executarAcaoMembro(acaoPendente.id, acaoPendente.rota);
});

/**
 * Executa o Fetch para o servidor
 */
function executarAcaoMembro(id, rota) {
    let fd = new FormData();
    fd.append('membro_id', id);

    // Certifique-se que essas rotas estão corretas no seu sistema
    const urlFinal = rota === 'vincular'
        ? '<?= url("sociedadeLider/vincularMembro") ?>'
        : '<?= url("sociedadeLider/desvincularMembro") ?>';

    fetch(urlFinal, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if(data.sucesso) {
            dispararModal("Sucesso!", "Operação realizada com sucesso!", "sucesso");
        } else {
            dispararModal("Erro", data.erro || "Falha na operação", "erro");
        }
    })
    .catch(() => dispararModal("Erro", "Falha de conexão com o servidor", "erro"));
}

/**
 * Exibe o Modal de Feedback (Sucesso/Erro)
 */
function dispararModal(titulo, mensagem, tipo = 'sucesso') {
    const modalEl = document.getElementById('modalNotificacao');
    const icone = document.getElementById('notificacaoIcone');
    const btn = document.getElementById('notificacaoBtn');

    document.getElementById('notificacaoTitulo').innerText = titulo;
    document.getElementById('notificacaoMensagem').innerText = mensagem;

    if (tipo === 'sucesso') {
        icone.innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>';
        btn.className = 'btn btn-success w-100 fw-bold';
        // Recarrega a página ao fechar o modal de sucesso
        modalEl.addEventListener('hidden.bs.modal', () => location.reload(), { once: true });
    } else {
        icone.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>';
        btn.className = 'btn btn-danger w-100 fw-bold';
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

// Bloqueia o alert nativo para garantir o uso dos modais
window.alert = function() { console.log("Alert nativo bloqueado em favor do modal."); };

let membroIdAtual = null;

function abrirModalObs(id, nome) {
    membroIdAtual = id;
    document.getElementById('obsMembroNome').innerText = `Obs: ${nome}`;
    document.getElementById('novaObservacao').value = '';

    carregarHistorico(id);

    const modal = new bootstrap.Modal(document.getElementById('modalObservacoes'));
    modal.show();
}

function carregarHistorico(id) {
    const lista = document.getElementById('listaHistorico');
    lista.innerHTML = '<p class="text-center small text-muted">Carregando...</p>';

    let fd = new FormData();
    fd.append('membro_id', id);

    fetch('<?= url("sociedadeLider/buscar_historico") ?>', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if(data.length === 0) {
            lista.innerHTML = '<p class="text-center small text-muted">Nenhuma observação registrada.</p>';
            return;
        }

        lista.innerHTML = data.map(h => `
            <div class="bg-light p-2 rounded mb-2 border-start border-3 border-secondary">
                <small class="d-block fw-bold text-primary" style="font-size: 0.7rem;">
                    ${new Date(h.membro_historico_data).toLocaleString('pt-BR')}
                </small>
                <p class="mb-0 small text-dark">${h.membro_historico_texto}</p>
            </div>
        `).join('');
    });
}

document.getElementById('btnSalvarObs').addEventListener('click', function() {
    const texto = document.getElementById('novaObservacao').value;
    if(!texto) return;

    let fd = new FormData();
    fd.append('membro_id', membroIdAtual);
    fd.append('texto', texto);

    fetch('<?= url("sociedadeLider/salvar_observacao") ?>', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if(data.sucesso) {
            document.getElementById('novaObservacao').value = '';
            carregarHistorico(membroIdAtual);
        } else {
            alert("Erro ao salvar observação.");
        }
    });
});

</script>

<?php $this->rawview('sociedade_portal/footer'); ?>
