<style>
    .btn-gerenciar-alunos {
        cursor: pointer;
        width: 100%; /* Para manter o comportamento de bloco se necessário */
        text-align: left; /* Alinha o texto conforme o botão original */
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-0"><i class="bi bi-book me-2"></i>Escola Dominical</h2>
            <p class="text-muted">Gestão de classes e frequências</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovaClasse">
            <i class="bi bi-plus-lg me-2"></i>Nova Classe
        </button>
    </div>

    <div class="row g-4">
        <?php if (!empty($classes)): ?>
            <?php foreach ($classes as $classe): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-light-primary p-3 rounded-3">
                                    <i class="bi bi-people-fill fs-4 text-primary"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Editar</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Excluir</a></li>
                                    </ul>
                                </div>
                            </div>

                            <h5 class="card-title fw-bold mb-1"><?= $classe['classe_nome'] ?></h5>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-person-badge me-1"></i> Prof: <?= $classe['professor_nome'] ?? 'Não definido' ?>
                            </p>

                            <div class="mb-4">
                                <?php
                                    $faixa = "Geral";
                                    $cor = "bg-secondary";
                                    if(stripos($classe['classe_nome'], 'Cordeirinhos') !== false) { $faixa = "1 a 3 anos"; $cor = "bg-info"; }
                                    elseif(stripos($classe['classe_nome'], 'Arca') !== false) { $faixa = "4 a 6 anos"; $cor = "bg-success"; }
                                    elseif(stripos($classe['classe_nome'], 'Josias') !== false) { $faixa = "7 a 12 anos"; $cor = "bg-warning text-dark"; }
                                    elseif(stripos($classe['classe_nome'], 'Timóteo') !== false) { $faixa = "13 a 17 anos"; $cor = "bg-primary"; }
                                    elseif(stripos($classe['classe_nome'], 'Adultos') !== false) { $faixa = "18+ anos"; $cor = "bg-dark"; }
                                ?>
                                <span class="badge <?= $cor ?> rounded-pill px-3"><?= $faixa ?></span>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?= url('escolaDominical/chamada/' . $classe['classe_id']) ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-check2-square me-2"></i>Fazer Chamada
                                </a>
                                <button type="button"
                                    class="btn btn-light btn-gerenciar-alunos"
                                    data-id="<?= $classe['classe_id'] ?>">
                                    <i class="bi bi-person-plus me-2"></i>Gerenciar Alunos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <img src="<?= url('assets/img/logo_ipb.png') ?>" style="width: 200px; opacity: 0.5;">
                <p class="text-muted mt-3">Nenhuma classe cadastrada para esta igreja.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalNovaClasse" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Cadastrar Nova Classe</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('escolaDominical/cadastrar') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Nome da Classe</label>
						<select name="classe_nome" class="form-select" required id="select-config-classe">
							<option value="" disabled selected>Selecione uma opção...</option>

							<?php foreach($configuracoes as $conf): ?>
								<option value="<?= $conf['config_nome'] ?>"
										data-min="<?= $conf['config_idade_min'] ?>"
										data-max="<?= $conf['config_idade_max'] ?>">
									<?= $conf['config_nome'] ?> (<?= $conf['config_idade_min'] ?> a <?= $conf['config_idade_max'] ?> anos)
								</option>
							<?php endforeach; ?>

							<option value="Outra">Outra (Personalizada)</option>
						</select>
                        <input type="hidden" name="classe_idade_min" id="input_min">
                        <input type="hidden" name="classe_idade_max" id="input_max">
                    </div>

					<div class="mb-3">
						<label class="form-label fw-bold small text-uppercase text-muted">Professor Responsável</label>
						<select name="classe_professor_id" id="choices-professor" class="form-select" required>
							<option value="">Pesquise pelo nome do membro...</option>
							<?php foreach($membros as $m): ?>
								<option value="<?= $m['membro_id'] ?>"><?= $m['membro_nome'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Salvar Classe</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalNovaClasse" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Configurar Nova Classe</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('escolaDominical/cadastrarClasse') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Nome da Classe</label>
                        <input type="text" name="classe_nome" class="form-control" placeholder="Ex: Cordeirinhos, Adultos I..." required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Idade Mínima</label>
                            <input type="number" name="classe_idade_min" class="form-control" value="0" min="0" max="99" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Idade Máxima</label>
                            <input type="number" name="classe_idade_max" class="form-control" value="99" min="0" max="99" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Professor Responsável</label>
                        <select name="classe_professor_id" id="choices-professor" class="form-select" required>
                            <option value="">Pesquise pelo nome...</option>
                            <?php foreach($membros as $m): ?>
                                <option value="<?= $m['membro_id'] ?>"><?= $m['membro_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Criar Classe</button>
                </div>
            </form>
        </div>
    </div>
</div>



<style>
    .bg-light-primary { background-color: rgba(13, 110, 253, 0.1); }
    .transition { transition: all 0.3s ease; }
    .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('choices-professor');

    // Inicializa o Choices.js
    const choices = new Choices(element, {
        allowHTML: true,
        searchEnabled: true,
        searchPlaceholderValue: "Digite o nome...",
        noResultsText: 'Nenhum membro encontrado',
        itemSelectText: 'Clique para selecionar',
        placeholder: true,
        placeholderValue: 'Pesquise pelo nome do membro...',
        shouldSort: false, // Mantém a ordem vinda do banco (alfabética)
    });
});

// Ao mudar a opção, preenche os campos ocultos de idade automaticamente
document.getElementById('select-config-classe').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('input_min').value = selected.getAttribute('data-min') || 0;
    document.getElementById('input_max').value = selected.getAttribute('data-max') || 99;
});
</script>


<style>
    /* Ajuste para o Choices não ficar "cortado" dentro do Modal do Bootstrap */
    .choices { margin-bottom: 0; }
    .choices__inner {
        background-color: #fff;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        min-height: 45px;
    }
    .choices__list--dropdown { z-index: 2000; } /* Garante que a lista apareça sobre o modal */
</style>


<div class="modal fade" id="modalGerenciarAlunos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="bi bi-people-fill me-2"></i>Gerenciar Alunos: <span id="nomeClasseModal"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="card border border-primary-subtle bg-light-primary mb-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-plus me-1"></i>Matricular Novo Aluno</h6>
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <select id="choices-sugestoes" class="form-select">
                                    <option value="">Carregando sugestões por idade...</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" id="btnAdicionarAluno">
                                <i class="bi bi-plus-lg"></i> Adicionar
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">A lista mostra membros ativos na faixa etária da classe que não estão em nenhuma outra classe.</small>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Alunos Matriculados (<span id="cntMatriculados">0</span>)</h6>
                <div class="table-responsive" style="min-height: 200px;">
                    <table class="table table-hover align-middle" id="tabelaMatriculados">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>Nome do Aluno</th>
                                <th>Registro</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                    <div id="loadingAlunos" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-2">Carregando lista...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para Choices dentro do modal gerenciador */
    #modalGerenciarAlunos .choices__inner { background-color: #fff; min-height: 45px; border-radius: 0.375rem; }
    #modalGerenciarAlunos .choices__list--dropdown { z-index: 3000; }
    .bg-light-primary { background-color: rgba(13, 110, 253, 0.05); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    let choicesSugestoes = null;
    let classeIdAtual = null;
    const modalGerenciar = new bootstrap.Modal(document.getElementById('modalGerenciarAlunos'));

    // 1. Ouvir clique no botão "Gerenciar Alunos" dos cards
    document.querySelectorAll('.btn-gerenciar-alunos').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            classeIdAtual = this.getAttribute('data-id');
            abrirModalGerenciamento(classeIdAtual);
        });
    });

    // 2. Função principal para abrir o modal e carregar dados via AJAX
    function abrirModalGerenciamento(classeId) {
        // Resetar UI
        document.getElementById('nomeClasseModal').innerText = 'Carregando...';
        document.getElementById('tabelaMatriculados').querySelector('tbody').innerHTML = '';
        document.getElementById('loadingAlunos').classList.remove('d-none');

        // Destruir Choices anterior se existir para não duplicar
        if (choicesSugestoes) choicesSugestoes.destroy();
        document.getElementById('choices-sugestoes').innerHTML = '<option value="">Carregando sugestões...</option>';

        modalGerenciar.show();

        // Busca dados via AJAX
        fetch(`<?= url('escolaDominical/getDadosGerenciamentoAjax/') ?>${classeId}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('loadingAlunos').classList.add('d-none');

                // Preenche cabeçalho
                document.getElementById('nomeClasseModal').innerText = `${data.classe.classe_nome} (${data.classe.classe_idade_min} a ${data.classe.classe_idade_max} anos)`;

                // Preenche Tabela de Matriculados
                preencherTabelaMatriculados(data.matriculados);

                // Preenche Select de Sugestões e inicializa Choices
                preencherSelectSugestoes(data.sugestoes);
            });
    }

    // 3. Auxiliar: Renderiza a tabela de alunos atuais
    function preencherTabelaMatriculados(alunos) {
        const tbody = document.getElementById('tabelaMatriculados').querySelector('tbody');
        tbody.innerHTML = '';
        document.getElementById('cntMatriculados').innerText = alunos.length;

        if (alunos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-muted">Nenhum aluno matriculado nesta classe.</td></tr>';
            return;
        }

        alunos.forEach(aluno => {
            tbody.innerHTML += `
                <tr>
                    <td class="fw-bold text-dark">${aluno.membro_nome}</td>
                    <td><span class="badge bg-light text-secondary border">${aluno.membro_registro_interno || 'N/A'}</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-danger border-0 btn-remover-aluno" data-id="${aluno.classe_membro_id}">
                            <i class="bi bi-person-x-fill"></i> Remover
                        </button>
                    </td>
                </tr>
            `;
        });

        // Ativa eventos de remoção
        atidvarEventosRemocao();
    }

    // 4. Auxiliar: Preenche o select e ativa Choices.js
    function preencherSelectSugestoes(sugestoes) {
        const select = document.getElementById('choices-sugestoes');
        select.innerHTML = '<option value="">Pesquise um aluno compatível...</option>';

        sugestoes.forEach(sug => {
            select.innerHTML += `<option value="${sug.membro_id}">${sug.membro_nome} (${sug.idade} anos)</option>`;
        });

        // Inicializa Choices.js
        choicesSugestoes = new Choices(select, {
            searchEnabled: true,
            itemSelectText: 'Clique para adicionar',
            noResultsText: 'Nenhum aluno compatível encontrado',
        });
    }

    // 5. Ação: Adicionar Aluno (Matricular)
    document.getElementById('btnAdicionarAluno').addEventListener('click', function() {
        const membroId = choicesSugestoes.getValue(true); // Pega apenas o ID

        if (!membroId || !classeIdAtual) {
            alert('Selecione um aluno válido.');
            return;
        }

        const formData = new FormData();
        formData.append('classe_id', classeIdAtual);
        formData.append('membro_id', membroId);

        fetch(`<?= url('escolaDominical/matricularAlunoAjax') ?>`, {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Atualiza o modal sem fechar (recarrega os dados AJAX)
                abrirModalGerenciamento(classeIdAtual);
            } else {
                alert('Erro ao matricular aluno.');
            }
        });
    });

    // 6. Ação: Remover Aluno
    function atidvarEventosRemocao() {
        document.querySelectorAll('.btn-remover-aluno').forEach(btn => {
            btn.addEventListener('click', function() {
                const classeMembroId = this.getAttribute('data-id');

                if (!confirm('Tem certeza que deseja remover este aluno da classe?')) return;

                const formData = new FormData();
                formData.append('classe_membro_id', classeMembroId);

                fetch(`<?= url('escolaDominical/removerAlunoAjax') ?>`, {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza o modal
                        abrirModalGerenciamento(classeIdAtual);
                    } else {
                        alert('Erro ao remover aluno.');
                    }
                });
            });
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectConfig = document.getElementById('select-config-classe');
    const inputMin = document.getElementById('input_min');
    const inputMax = document.getElementById('input_max');

    if (selectConfig) {
        selectConfig.addEventListener('change', function() {
            // Pega a opção selecionada
            const selectedOption = this.options[this.selectedIndex];

            // Extrai os dados dos atributos data-min e data-max
            const min = selectedOption.getAttribute('data-min');
            const max = selectedOption.getAttribute('data-max');

            // Preenche os inputs hidden
            inputMin.value = min || 0;
            inputMax.value = max || 99;
        });
    }
});
</script>
