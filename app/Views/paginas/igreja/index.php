<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">⛪ Informações da Instituição</h3>
        <a href="<?= url('igreja/editar') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-pencil"></i> Editar Dados
        </a>
    </div>

    <?php if (!empty($igreja)): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">📄 Ficha Cadastral</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="text-muted small fw-bold text-uppercase">Nome da Igreja</label>
                            <p class="fs-5 mb-0 text-dark"><?= htmlspecialchars($igreja['igreja_nome']) ?></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase">CNPJ</label>
                            <p class="mb-0 text-dark"><?= !empty($igreja['igreja_cnpj']) ? htmlspecialchars($igreja['igreja_cnpj']) : '<span class="text-muted italic">Não informado</span>' ?></p>
                        </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase">Pastor Titular</label>
                        <div class="d-flex align-items-center">
                            <p class="mb-0 text-dark fw-bold text-primary me-2"><?= htmlspecialchars($pastorNome ?? 'Não definido') ?></p>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0" data-bs-toggle="modal" data-bs-target="#modalPastor">
                                <i class="bi bi-person-badge"></i> Alterar
                            </button>
                        </div>
                    </div>

                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase">Data de Registro no Sistema</label>
                            <p class="mb-0 text-dark"><?= date('d/m/Y H:i', strtotime($igreja['igreja_data_criacao'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase">Endereço Sede</label>
                            <p class="mb-0 text-dark"><?= !empty($igreja['igreja_endereco']) ? htmlspecialchars($igreja['igreja_endereco']) : '<span class="text-muted italic">Não informado</span>' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">📱 Redes Sociais & Contatos</h5>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalRedeSocial">
                        <i class="bi bi-plus-lg"></i> Adicionar Rede
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Plataforma</th>
                                    <th>Usuário / Identificador</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($redes)): foreach ($redes as $rede): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold"><?= htmlspecialchars($rede['rede_nome']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($rede['rede_usuario']) ?></td>
                                        <td>
                                            <?php if ($rede['rede_status'] == 'ativo'): ?>
                                                <span class="badge rounded-pill bg-success-soft text-success border border-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-secondary-soft text-secondary border border-secondary">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="<?= url('igreja/excluirRedeSocial/' . $rede['rede_id']) ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Deseja realmente excluir esta rede social?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-info-circle me-1"></i> Nenhuma rede social cadastrada para esta igreja.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white mb-3">
                <div class="card-body text-center py-5">
                    <img src="<?= url('assets/img/logo_ipb.png') ?>"
                         alt="Logo IPB"
                         class="mb-3"
                         style="max-width: 150px; height: auto; filter: brightness(0) invert(1);">

                    <h4 class="mt-2 mb-0"><?= htmlspecialchars($igreja['igreja_nome']) ?></h4>
                    <p class="small opacity-75">Jurisdição IPB</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill text-info me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">Informação Importante</h6>
                            <p class="small text-muted mb-0">
                                As redes sociais marcadas como <strong>Ativas</strong> serão exibidas automaticamente no verso da carteirinha de membro e em documentos oficiais.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Dados da igreja não encontrados no banco de dados.
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalRedeSocial" tabindex="-1" aria-labelledby="modalRedeSocialLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('igreja/salvarRedeSocial') ?>" method="POST" style="width: 100%;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalRedeSocialLabel">🚀 Nova Rede Social / Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Plataforma</label>
                        <select name="rede_nome" class="form-select" required>
                            <option value="Instagram">Instagram</option>
                            <option value="WhatsApp">WhatsApp (Telefone)</option>
                            <option value="Facebook">Facebook</option>
                            <option value="YouTube">YouTube</option>
                            <option value="E-mail">E-mail</option>
                            <option value="Site">Site Oficial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Usuário ou Identificador</label>
                        <input type="text" name="rede_usuario" class="form-control" placeholder="Ex: @igreja ou (11) 99999-9999" required>
                        <div class="form-text">Como você deseja que apareça no documento.</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Exibir na Carteirinha?</label>
                        <select name="rede_status" class="form-select">
                            <option value="ativo">Sim, manter Ativo</option>
                            <option value="inativo">Não, manter Oculto</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Gravar Dados</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalPastor" tabindex="-1" aria-labelledby="modalPastorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('igreja/salvarPastor') ?>" method="POST" style="width: 100%;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalPastorLabel">👔 Definir Pastor Titular</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted">Selecione o membro que será o Pastor Titular. Esta alteração atualizará automaticamente os cargos de vínculo.</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Pesquisar por nome</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text"
                                   id="inputBuscaPastor"
                                   class="form-control border-start-0 shadow-none"
                                   placeholder="Digite parte do nome..."
                                   onkeyup="window.filtrarMembrosPastor(this.value)">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase text-muted">Selecione o Membro</label>
                        <select name="pastor_id"
                                id="selectMembrosPastor"
                                class="form-select shadow-none"
                                size="6"
                                required>
                            <option value="" class="text-muted italic">-- Selecione um Membro --</option>
                            <?php foreach ($membros as $m): ?>
                                <option value="<?= $m['membro_id'] ?>"
                                        data-nome="<?= mb_strtolower($m['membro_nome']) ?>"
                                        <?= ($igreja['igreja_pastor_id'] == $m['membro_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['membro_nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text mt-2"><i class="bi bi-info-circle"></i> A lista acima mostra apenas membros desta igreja.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Atualizar Pastor</button>
                </div>
            </div>
        </form>
    </div>
</div>


<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
</style>

<script>
/**
 * Filtra as opções do select de acordo com o texto digitado (Lógica %nome%)
 */
window.filtrarMembrosPastor = function(termo) {
    const busca = termo.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "").trim();
    const select = document.getElementById('selectMembrosPastor');
    const options = select.options;

    for (let i = 0; i < options.length; i++) {
        // Ignora a primeira opção "Selecione"
        if (options[i].value === "") continue;

        // Pega o nome do atributo data ou do texto, removendo acentos para facilitar a busca
        const nomeMembro = (options[i].getAttribute('data-nome') || options[i].text)
                           .toLowerCase()
                           .normalize('NFD')
                           .replace(/[\u0300-\u036f]/g, "");

        if (nomeMembro.includes(busca)) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
};

// Limpa a busca quando o modal for fechado para não travar a lista na próxima vez
document.getElementById('modalPastor').addEventListener('hidden.bs.modal', function () {
    const input = document.getElementById('inputBuscaPastor');
    if(input) {
        input.value = "";
        window.filtrarMembrosPastor("");
    }
});
</script>
