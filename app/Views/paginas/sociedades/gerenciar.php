<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="<?= url('sociedades') ?>" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <h3 class="fw-bold text-dark">
                <i class="bi bi-person-gear me-2 text-primary"></i>Gerenciar Sócios: <?= $sociedade['sociedade_nome'] ?>
            </h3>
            <p class="text-muted small mb-0">
                Público: <strong><?= $sociedade['sociedade_genero'] ?></strong> |
                Faixa Etária: <strong><?= $sociedade['sociedade_idade_min'] ?> a <?= $sociedade['sociedade_idade_max'] ?> anos</strong>
            </p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="buscaMembroApto" class="form-control border-start-0"
                               placeholder="Pesquisar nome na lista de membros aptos..." onkeyup="window.filtrarMembros()">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-info-subtle text-info p-2">
                        <i class="bi bi-info-circle me-1"></i> Apenas membros ativos e dentro do perfil aparecem aqui.
                    </span>
                </div>
            </div>
        </div>

        <form action="<?= url('sociedades/vincularLote') ?>" method="POST">
            <input type="hidden" name="sociedade_id" value="<?= $sociedade['sociedade_id'] ?>">

            <div class="table-responsive" style="max-height: 600px;">
                <table class="table table-hover align-middle mb-0" id="tabelaAptos">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="ps-4" width="80px">Selecionar</th>
                            <th>Nome do Membro</th>
                            <th>Idade</th>
                            <th>Gênero</th>
                            <th class="text-center">Status Atual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($membrosAptos)): foreach ($membrosAptos as $m): ?>
                        <tr>
                            <td class="ps-4 text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="membros_ids[]"
                                           value="<?= $m['membro_id'] ?>" <?= $m['ja_pertence'] ? 'checked' : '' ?>>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= $m['membro_nome'] ?></div>
                                <small class="text-muted">Cod: #<?= $m['membro_id'] ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border"><?= $m['idade'] ?> anos</span>
                            </td>
                            <td>
                                <span class="text-muted small"><?= $m['membro_genero'] ?></span>
                            </td>
                            <td class="text-center">
                                <?php if($m['ja_pertence']): ?>
                                    <span class="badge bg-success-subtle text-success">Já é Sócio</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">Não vinculado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-emoji-frown fs-2 d-block mb-2"></i>
                                Nenhum membro ativo encontrado para os requisitos desta sociedade.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white p-4 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Total de membros aptos: <strong><?= count($membrosAptos) ?></strong>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            <i class="bi bi-check-all me-2"></i>Salvar Alterações de Sócios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Filtro de pesquisa em tempo real
 */
window.filtrarMembros = function() {
    let input = document.getElementById('buscaMembroApto');
    let filter = input.value.toLowerCase();
    let table = document.getElementById('tabelaAptos');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let tdNome = tr[i].getElementsByTagName('td')[1]; // Coluna do Nome
        if (tdNome) {
            let txtValue = tdNome.textContent || tdNome.innerText;
            tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
};
</script>


<script>
/**
 * Abre o gerenciador de sócios e busca membros aptos via AJAX
 */
window.abrirGerenciador = function(sociedade) {
    // 1. Prepara a interface do Modal
    document.getElementById('nomeSociedadeTitulo').innerText = sociedade.sociedade_nome;
    document.getElementById('gerenciar_soc_id').value = sociedade.sociedade_id;

    const corpoTabela = document.getElementById('listaAptosCorpo');
    const contador = document.getElementById('contadorAptos');

    // Feedback visual de carregamento
    corpoTabela.innerHTML = '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border spinner-border-sm text-info"></div> Carregando membros aptos...</td></tr>';
    contador.innerText = "Buscando...";

    // 2. Busca os dados no Controller
    // A rota deve ser: sociedades/buscarAptos/{id}
    fetch(`<?= url('sociedades/buscarAptos/') ?>${sociedade.sociedade_id}`)
        .then(response => response.json())
        .then(membros => {
            corpoTabela.innerHTML = '';
            contador.innerText = `${membros.length} membros atendem aos requisitos`;

            if (membros.length === 0) {
                corpoTabela.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Nenhum membro ativo se encaixa no perfil desta sociedade (Idade/Gênero).</td></tr>';
                return;
            }

            // 3. Renderiza a lista de checkboxes
            membros.forEach(m => {
                const tr = document.createElement('tr');
                const checked = m.ja_pertence ? 'checked' : '';

                tr.innerHTML = `
                    <td class="text-center">
                        <input type="checkbox" name="membros_ids[]" value="${m.membro_id}" class="form-check-input shadow-sm" ${checked}>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">${m.membro_nome}</div>
                        <small class="text-muted">ID: ${m.membro_id}</small>
                    </td>
                    <td><span class="badge bg-light text-primary border">${m.idade} anos</span></td>
                    <td><span class="badge bg-light text-dark border">${m.membro_genero}</span></td>
                `;
                corpoTabela.appendChild(tr);
            });
        })
        .catch(error => {
            corpoTabela.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Erro ao carregar lista.</td></tr>';
            console.error('Erro:', error);
        });

    // 4. Exibe o Modal do Bootstrap
    const modalElement = document.getElementById('modalGerenciarSocios');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
};

/**
 * Função de busca em tempo real dentro da lista de aptos
 */
window.filtrarMembrosAptos = function() {
    let busca = document.getElementById('buscaMembroApto').value.toLowerCase();
    let linhas = document.querySelectorAll('#listaAptosCorpo tr');

    linhas.forEach(linha => {
        // Verifica se o texto da linha contém o que foi digitado
        let textoLinha = linha.innerText.toLowerCase();
        linha.style.display = textoLinha.includes(busca) ? '' : 'none';
    });
};

// Event listener para a busca (opcional, mas recomendado para fluidez)
document.getElementById('buscaMembroApto').addEventListener('keyup', window.filtrarMembrosAptos);
</script>
