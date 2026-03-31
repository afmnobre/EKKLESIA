<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="bi bi-people-fill me-2"></i>Sociedades Internas</h3>
        <button type="button" class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalQrLoginLider">
            <i class="bi bi-qr-code-scan me-2"></i> ACESSO DO LÍDER
        </button>
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
                            <th>Liderança</th>
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

							<td>
								<?php if (!empty($soc['nome_lider'])): ?>
									<span class="badge bg-light text-dark border">
										<i class="bi bi-person-badge text-primary me-1"></i>
										<?= $soc['nome_lider'] ?>
									</span>
								<?php else: ?>
									<span class="text-muted small italic">Sem líder definido</span>
								<?php endif; ?>
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
                                <a href="<?= url('sociedades/banner/' . $soc['sociedade_id']) ?>"
                                    class="btn btn-sm btn-dark shadow-sm"
                                    title="Gerar Banner/Flyer">
                                    <i class="bi bi-image"></i>
                                 </a>
                                <a href="<?= url('sociedades/gerenciar/' . $soc['sociedade_id']) ?>"
                                    class="btn btn-sm btn-info text-white shadow-sm"
                                    title="Gerenciar Sócios">
                                    <i class="bi bi-person-plus-fill"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-secondary shadow-sm"
                                    onclick="window.abrirModalLogo(<?= $soc['sociedade_id'] ?>, '<?= $soc['sociedade_nome'] ?>', '<?= $soc['sociedade_logo'] ?>')">
                                    <i class="bi bi-camera-fill"></i>
                                </button>
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

<div class="modal fade" id="modalDefinirLider" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-star-fill me-2"></i>Definir Líder: <span id="nomeSociedadeLider"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idSociedadeLider">
                <input type="hidden" id="idCargoLider">

                <p class="text-muted small">Selecione o membro que ocupará o cargo de liderança nesta sociedade.</p>

                <div class="input-group mb-3">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="buscaLiderModal" class="form-control border-start-0 ps-0"
                           placeholder="Digite o nome do membro..."
                           onkeyup="window.filtrarLideresModal()">
                </div>

                <div class="list-group shadow-sm" id="listaMembrosLider" style="max-height: 350px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.5rem;">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary px-4" onclick="window.processarSalvarLider()">
                    <i class="bi bi-check-lg me-1"></i> Confirmar Líder
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLogoSociedade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form action="<?= url('sociedades/salvarLogo') ?>" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <input type="hidden" name="sociedade_id" id="logo_soc_id">
            <div class="modal-header bg-secondary text-white">
                <h6 class="modal-title"><i class="bi bi-image me-2"></i>Logo: <span id="nomeSociedadeLogo"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="previewLogo" class="mb-3">
                    </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Selecione o arquivo (PNG/JPG)</label>
                    <input type="file" name="sociedade_logo" class="form-control form-control-sm" accept="image/*" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="submit" class="btn btn-primary btn-sm w-100">Fazer Upload</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalQrLoginLider" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-shield-lock me-2"></i>Acesso à Liderança
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB" class="img-fluid mb-4" style="max-height: 60px;">

                <p class="text-muted fw-bold mb-1 text-uppercase small" style="letter-spacing: 1px;">Portal das Sociedades</p>
                <p class="text-muted small mb-4">O líder deve escanear para gerenciar membros e eventos.</p>

                <div id="qrcode_lider" class="d-flex justify-content-center p-3 border rounded-4 bg-white shadow-sm mb-4" style="min-height: 220px;">
                </div>

                <div class="card bg-light border-0 rounded-4 mb-4">
                    <div class="card-body py-3">
                        <small class="text-muted d-block fw-bold text-uppercase mb-2" style="font-size: 0.65rem;">Link Direto:</small>
                        <input type="text" id="inputLinkLider" value="<?= full_url('sociedadeLider/login') ?>" style="position: absolute; left: -9999px;">

                        <div class="d-flex align-items-center justify-content-center">
                            <code class="fw-bold text-dark me-2" style="word-break: break-all;">
                                <?= full_url('sociedadeLider/login') ?>
                            </code>
                            <button class="btn btn-sm btn-outline-dark border-0" onclick="copyToClipboard('inputLinkLider', 'msgCopyLider')">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                        <div id="msgCopyLider" class="text-success small mt-1 fw-bold" style="display:none;">Copiado!</div>
                    </div>
                </div>

                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Sarça IPB" style="height: 40px; opacity: 0.5;">
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center rounded-bottom-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-outline-dark px-4 rounded-pill" onclick="downloadModalAsImage('modalQrLoginLider', 'Acesso_Portal_Liderança')">
                    <i class="bi bi-download me-2"></i>Salvar Imagem
                </button>
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
    const radioChecked = document.querySelector('input[name="membro_lider_id"]:checked');

    if(!radioChecked) return alert('Por favor, selecione um membro da lista.');

    const membroId = radioChecked.value;

    const fd = new FormData();
    fd.append('sociedade_id', socId);
    fd.append('cargo_id', cargoId);
    fd.append('membro_id', membroId);

    fetch(`<?= url('sociedades/salvarLider') ?>`, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if(res.success) {
                location.reload(); // Recarrega para mostrar o nome na tabela
            } else {
                alert('Erro ao salvar: ' + res.message);
            }
        })
        .catch(err => alert('Erro na requisição.'));
}


// Função para filtrar dinamicamente (chamada pelo onkeyup)
window.filtrarLideresModal = function() {
    const termo = document.getElementById('buscaLiderModal').value.toLowerCase();
    const itens = document.querySelectorAll('#listaMembrosLider .list-group-item');

    itens.forEach(item => {
        // Pega o texto do nome do membro dentro da label
        const nomeMembro = item.textContent.toLowerCase();

        if (nomeMembro.includes(termo)) {
            item.classList.remove('d-none'); // Mostra
            item.classList.add('d-flex');    // Mantém o layout flex
        } else {
            item.classList.add('d-none');    // Esconde
            item.classList.remove('d-flex');
        }
    });
};

// Atualize sua função de abrir o modal para limpar a busca anterior
const originalAbrirModalLider = window.abrirModalLider;
window.abrirModalLider = function(idSociedade, nomeSociedade) {
    // Limpa o campo de busca ao abrir
    const campoBusca = document.getElementById('buscaLiderModal');
    if(campoBusca) campoBusca.value = '';

    // Chama a função original que já existia
    originalAbrirModalLider(idSociedade, nomeSociedade);
};

window.abrirModalLogo = function(id, nome, logoAtual) {
    document.getElementById('logo_soc_id').value = id;
    document.getElementById('nomeSociedadeLogo').innerText = nome;

    const preview = document.getElementById('previewLogo');
    if (logoAtual && logoAtual !== 'null' && logoAtual !== '') {
        // Assume que o caminho salvo no banco já considera a estrutura de uploads
        preview.innerHTML = `<img src="<?= url('assets/uploads/') ?>${logoAtual}" class="img-thumbnail" style="max-height: 120px;">`;
    } else {
        preview.innerHTML = `<div class="py-3 border rounded bg-light text-muted small">Sem logo cadastrada</div>`;
    }

    new bootstrap.Modal(document.getElementById('modalLogoSociedade')).show();
};

document.addEventListener("DOMContentLoaded", function() {
    // AJUSTE AQUI: Mudamos de 'sociedade/login' para 'sociedadeLider/login'
    const urlLider = "<?= full_url('sociedadeLider/login') ?>";

    const modalLider = document.getElementById('modalQrLoginLider');
    const containerLider = document.getElementById('qrcode_lider');

    if (modalLider && containerLider) {
        // Usamos 'shown.bs.modal' para garantir que o modal terminou de abrir
        modalLider.addEventListener('shown.bs.modal', function() {

            containerLider.innerHTML = ''; // Limpa antes de gerar

            if (typeof QRCode !== "undefined") {
                // Pequeno delay para garantir que o container está visível
                setTimeout(function() {
                    new QRCode(containerLider, {
                        text: urlLider,
                        width: 220,
                        height: 220,
                        colorDark : "#003366",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                }, 100);
            } else {
                containerLider.innerHTML = '<p class="text-danger small mt-5">Biblioteca QRCode não encontrada!</p>';
            }
        });
    }
});


//Copiar parao clipboard()
window.copyToClipboard = function(inputId, msgId) {
    const copyText = document.getElementById(inputId);
    const msgElement = document.getElementById(msgId);

    // Tenta usar a API moderna primeiro
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(copyText.value).then(() => {
            exibirSucesso();
        });
    } else {
        // Fallback para navegadores antigos ou contextos não seguros (HTTP)
        copyText.select();
        copyText.setSelectionRange(0, 99999); // Para dispositivos móveis
        try {
            document.execCommand("copy");
            exibirSucesso();
        } catch (err) {
            alert("Erro ao copiar link.");
        }
    }

    function exibirSucesso() {
        if (msgElement) {
            msgElement.style.display = 'block';
            setTimeout(() => {
                msgElement.style.display = 'none';
            }, 2000);
        }
    }
};

</script>
