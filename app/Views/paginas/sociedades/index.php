<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="bi bi-people-fill me-2"></i>Sociedades Internas</h3>
        <button class="btn btn-primary shadow-sm" onclick="window.novaSociedade()">
            <i class="bi bi-plus-lg me-2"></i>Nova Sociedade
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Sociedade</th>
                            <th>Tipo</th>
                            <th>Público (Gênero)</th>
                            <th>Faixa Etária</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sociedades)): foreach ($sociedades as $soc): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= $soc['sociedade_nome'] ?></div>
                            </td>
                            <td><?= $soc['sociedade_tipo'] ?></td>
                            <td>
                                <span class="badge bg-outline-secondary border text-dark">
                                    <?= $soc['sociedade_genero'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border border-primary">
                                    <?= $soc['sociedade_idade_min'] ?> a <?= $soc['sociedade_idade_max'] ?> anos
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $soc['sociedade_status'] == 'Ativo' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $soc['sociedade_status'] ?>
                                </span>
                            </td>
							<td class="text-end pe-4">
                                <a href="<?= url('sociedades/gerenciar/' . $soc['sociedade_id']) ?>"
                                    class="btn btn-sm btn-info text-white shadow-sm"
                                    title="Gerenciar Sócios">
                                    <i class="bi bi-person-plus-fill"></i>
                                </a>

								<button class="btn btn-sm btn-warning text-dark shadow-sm"
										onclick="window.abrirModalLider(<?= $soc['sociedade_id'] ?>, '<?= $soc['sociedade_nome'] ?>')">
									<i class="bi bi-star-fill"></i>
								</button>

								<button class="btn btn-sm btn-light border shadow-sm"
										onclick='window.editarSociedade(<?= json_encode($soc) ?>)'>
									<i class="bi bi-pencil-square text-primary"></i>
								</button>
							</td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-4">Nenhuma sociedade cadastrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSociedade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formSociedade" action="<?= url('sociedades/salvar') ?>" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="sociedade_id" id="soc_id">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTitulo">Cadastrar Sociedade</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Nome da Sociedade (Ex: UCP, UPA...)</label>
                    <input type="text" name="nome" id="soc_nome" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Tipo de Sociedade</label>
                    <input type="text" name="tipo" id="soc_tipo" class="form-control" placeholder="Ex: Infantil, Jovens, Homens..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Público-Alvo (Gênero)</label>
                    <select name="genero" id="soc_genero" class="form-select" required>
                        <option value="Ambos">Ambos (Misto)</option>
                        <option value="Masculino">Apenas Homens</option>
                        <option value="Feminino">Apenas Mulheres</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold small">Idade Mínima</label>
                        <input type="number" name="idade_min" id="soc_min" class="form-control" value="0" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold small">Idade Máxima</label>
                        <input type="number" name="idade_max" id="soc_max" class="form-control" value="99" required>
                    </div>
                </div>

                <div class="mb-3 d-none" id="divStatus">
                    <label class="form-label fw-bold small">Status</label>
                    <select name="status" id="soc_status" class="form-select">
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar Dados</button>
            </div>
        </form>
    </div>
</div>

<!--<div class="modal fade" id="modalGerenciarSocios" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-search me-2"></i>Membros Aptos: <span id="nomeSociedadeTitulo"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?= url('sociedades/vincularLote') ?>" method="POST">
                <input type="hidden" name="sociedade_id" id="gerenciar_soc_id">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-filter"></i></span>
                            <input type="text" id="buscaMembroApto" class="form-control" placeholder="Filtrar por nome na lista..." onkeyup="window.filtrarMembrosAptos()">
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle">
                            <thead class="sticky-top bg-white border-bottom">
                                <tr>
                                    <th width="40px">Incluir</th>
                                    <th>Nome do Membro</th>
                                    <th>Idade</th>
                                    <th>Gênero</th>
                                </tr>
                            </thead>
                            <tbody id="listaAptosCorpo">
                                </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <span class="me-auto text-muted small" id="contadorAptos">0 membros encontrados</span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-info text-white fw-bold">Salvar na Sociedade</button>
                </div>
            </form>
        </div>
    </div>
</div>
-->
<div class="modal fade" id="modalDefinirLider" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Definir Líder: <span id="nomeSociedadeLider"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idSociedadeLider">
                <input type="hidden" id="idCargoLider">

                <p class="text-muted small">Selecione o membro que ocupará o cargo de liderança. Apenas um líder é permitido por sociedade.</p>

                <div class="list-group" id="listaMembrosLider" style="max-height: 400px; overflow-y: auto;">
                    <div class="text-center p-3">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="window.salvarLiderSociedade()">Salvar Líder</button>
            </div>
        </div>
    </div>
</div>


<script>
// --- FUNÇÕES DE SOCIEDADE (CADASTRO/EDIÇÃO) ---

window.novaSociedade = function() {
    const form = document.getElementById('formSociedade');
    if(form) form.reset();
    document.getElementById('soc_id').value = '';
    document.getElementById('modalTitulo').innerText = 'Cadastrar Sociedade';
    document.getElementById('divStatus').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('modalSociedade')).show();
};

window.editarSociedade = function(dados) {
    document.getElementById('soc_id').value = dados.sociedade_id;
    document.getElementById('soc_nome').value = dados.sociedade_nome;
    document.getElementById('soc_tipo').value = dados.sociedade_tipo;
    document.getElementById('soc_genero').value = dados.sociedade_genero;
    document.getElementById('soc_min').value = dados.sociedade_idade_min;
    document.getElementById('soc_max').value = dados.sociedade_idade_max;
    document.getElementById('soc_status').value = dados.sociedade_status;
    document.getElementById('modalTitulo').innerText = 'Editar Sociedade';
    document.getElementById('divStatus').classList.remove('d-none');
    new bootstrap.Modal(document.getElementById('modalSociedade')).show();
};

// --- FUNÇÕES DO GERENCIADOR DE SÓCIOS ---

window.abrirGerenciador = function(sociedade) {
    document.getElementById('nomeSociedadeTitulo').innerText = sociedade.sociedade_nome;
    document.getElementById('gerenciar_soc_id').value = sociedade.sociedade_id;
    const corpoTabela = document.getElementById('listaAptosCorpo');
    const contador = document.getElementById('contadorAptos');

    corpoTabela.innerHTML = '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border spinner-border-sm text-info"></div> Carregando...</td></tr>';

    fetch(`<?= url('sociedades/buscarAptos/') ?>${sociedade.sociedade_id}`)
        .then(response => response.json())
        .then(membros => {
            corpoTabela.innerHTML = '';
            contador.innerText = `${membros.length} membros aptos encontrados`;
            if (membros.length === 0) {
                corpoTabela.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Nenhum membro apto.</td></tr>';
                return;
            }
            membros.forEach(m => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-center"><input type="checkbox" name="membros_ids[]" value="${m.membro_id}" class="form-check-input" ${m.ja_pertence ? 'checked' : ''}></td>
                    <td><div class="fw-bold">${m.membro_nome}</div></td>
                    <td>${m.idade} anos</td>
                    <td><span class="badge bg-light text-dark border">${m.membro_genero}</span></td>
                `;
                corpoTabela.appendChild(tr);
            });
        });

    new bootstrap.Modal(document.getElementById('modalGerenciarSocios')).show();
};

window.filtrarMembrosAptos = function() {
    let busca = document.getElementById('buscaMembroApto').value.toLowerCase();
    let linhas = document.querySelectorAll('#listaAptosCorpo tr');
    linhas.forEach(linha => {
        linha.style.display = linha.innerText.toLowerCase().includes(busca) ? '' : 'none';
    });
};

window.abrirModalLider = function(idSociedade, nomeSociedade) {
    const container = document.getElementById('listaMembrosLider');
    document.getElementById('nomeSociedadeLider').innerText = nomeSociedade;
    document.getElementById('idSociedadeLider').value = idSociedade;

    container.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>';

    const modalLider = new bootstrap.Modal(document.getElementById('modalDefinirLider'));
    modalLider.show();

    fetch(`<?= url('sociedades/buscarLideranca/') ?>${idSociedade}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('idCargoLider').value = data.cargo_id;
            container.innerHTML = '';

            data.membros.forEach(m => {
                const checked = m.tem_vinculo > 0 ? 'checked' : '';
                container.innerHTML += `
                    <label class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <input class="form-check-input me-2" type="radio" name="membro_lider_id" value="${m.membro_id}" ${checked}>
                            ${m.membro_nome}
                        </div>
                    </label>
                `;
            });
        });
};

window.processarSalvarLider = function() {
    const socId = document.getElementById('idSociedadeLider').value;
    const cargoId = document.getElementById('idCargoLider').value;
    const membroId = document.querySelector('input[name="membro_lider_id"]:checked')?.value;

    if(!membroId) return alert('Selecione um membro.');

    const fd = new FormData();
    fd.append('sociedade_id', socId);
    fd.append('cargo_id', cargoId);
    fd.append('membro_id', membroId);

    fetch(`<?= url('sociedades/salvarLider') ?>`, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if(res.success) location.reload();
            else alert(res.message);
        });
}
</script>
