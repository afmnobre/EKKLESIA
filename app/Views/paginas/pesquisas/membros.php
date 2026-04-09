<div class="container-fluid mt-4">
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-ipb text-black d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 small-mobile"><i class="bi bi-search me-2"></i> Pesquisa de Membros</h5>

            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($membros)): ?>
                    <span class="badge bg-white text-dark border shadow-sm px-3 py-2">
                        <i class="bi bi-people-fill me-1 text-ipb"></i> <strong><?= $totalMembros ?></strong>
                    </span>
                    <button type="button" onclick="exportarMembrosExcel()" class="btn btn-sm btn-success fw-bold shadow-sm px-3">
                        <i class="bi bi-file-earmark-excel"></i> <span class="d-none d-md-inline">Excel</span>
                    </button>
                <?php endif; ?>

                <a href="<?= url('PesquisaMembro/index') ?>" class="btn btn-sm btn-light text-ipb fw-bold border shadow-sm px-3">
                    <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Limpar</span>
                </a>
            </div>
        </div>

        <div class="card-body bg-light py-2">
            <form method="GET" action="<?= url('PesquisaMembro/index') ?>" id="formPesquisa">
                <div class="row g-2 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label mb-0 small fw-bold">Nome do Membro</label>
                        <input type="text" name="nome" class="form-control form-control-sm" value="<?= $filtros['nome'] ?? '' ?>" placeholder="Nome ou ROL...">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0 small fw-bold">Gênero</label>
                        <select name="genero" class="form-select form-select-sm">
                            <option value="">Ambos</option>
                            <option value="Masculino" <?= ($filtros['genero'] ?? '') == 'Masculino' ? 'selected' : '' ?>>Masc.</option>
                            <option value="Feminino" <?= ($filtros['genero'] ?? '') == 'Feminino' ? 'selected' : '' ?>>Fem.</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label mb-0 small fw-bold text-success">Nascimento (Início/Fim)</label>
                        <div class="input-group input-group-sm">
                            <input type="date" name="nasc_ini" class="form-control" value="<?= $filtros['nasc_ini'] ?? '' ?>">
                            <input type="date" name="nasc_fim" class="form-control" value="<?= $filtros['nasc_fim'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-sm btn-ipb fw-bold shadow-sm">
                            <i class="bi bi-filter me-1"></i> FILTRAR
                        </button>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 small fw-bold text-ipb">Sociedade</label>
                        <select name="sociedade_id" class="form-select form-select-sm border-ipb">
                            <option value="">Todas</option>
                            <option value="sem_sociedade" <?= ($filtros['sociedade_id'] ?? '') == 'sem_sociedade' ? 'selected' : '' ?>>-- SEM SOCIEDADE --</option>
                            <?php foreach($sociedades as $s): ?>
                                <option value="<?= $s['sociedade_id'] ?>" <?= ($filtros['sociedade_id'] ?? '') == $s['sociedade_id'] ? 'selected' : '' ?>><?= $s['sociedade_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 small fw-bold text-danger">Classe EBD</label>
                        <select name="classe_id" class="form-select form-select-sm border-danger">
                            <option value="">Todas</option>
                            <option value="sem_classe" <?= ($filtros['classe_id'] ?? '') == 'sem_classe' ? 'selected' : '' ?>>-- NÃO MATRICULADO --</option>
                            <?php foreach($classes as $cl): ?>
                                <option value="<?= $cl['classe_id'] ?>" <?= ($filtros['classe_id'] ?? '') == $cl['classe_id'] ? 'selected' : '' ?>><?= $cl['classe_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 small fw-bold text-primary">Cargo</label>
                        <select name="cargo_id" class="form-select form-select-sm border-primary">
                            <option value="">Todos</option>
                            <option value="sem_cargo" <?= ($filtros['cargo_id'] ?? '') == 'sem_cargo' ? 'selected' : '' ?>>-- SEM CARGO --</option>
                            <?php foreach($cargos as $c): ?>
                                <option value="<?= $c['cargo_id'] ?>" <?= ($filtros['cargo_id'] ?? '') == $c['cargo_id'] ? 'selected' : '' ?>><?= $c['cargo_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 small fw-bold text-primary">Batismo (Período)</label>
                        <div class="input-group input-group-sm">
                            <input type="date" name="bat_ini" class="form-control border-primary" value="<?= $filtros['bat_ini'] ?? '' ?>">
                            <input type="date" name="bat_fim" class="form-control border-primary" value="<?= $filtros['bat_fim'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 small fw-bold">Cidade</label>
                        <select name="cidade" id="select-cidade" class="form-select form-select-sm">
                            <option value="">Todas as Cidades</option>
                            <?php foreach($cidades as $c): ?>
                                <option value="<?= $c['membro_endereco_cidade'] ?>" <?= ($filtros['cidade'] ?? '') == $c['membro_endereco_cidade'] ? 'selected' : '' ?>><?= $c['membro_endereco_cidade'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0 small fw-bold">Bairro</label>
                        <select name="bairro" id="select-bairro" class="form-select form-select-sm">
                            <option value="">Todos os Bairros</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label mb-0 small fw-bold text-secondary">Cadastro (Período)</label>
                        <div class="input-group input-group-sm">
                            <input type="date" name="cad_ini" class="form-control" value="<?= $filtros['cad_ini'] ?? '' ?>">
                            <input type="date" name="cad_fim" class="form-control" value="<?= $filtros['cad_fim'] ?? '' ?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($membros)): ?>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-ipb text-white text-uppercase small">
                    <tr>
                        <th class="ps-4">Membro</th>
                        <th>Eclesiástico (Cargo/Sociedade/EBD)</th>
                        <th>Contatos / Local</th>
                        <th>Datas Importantes</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($membros as $m): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?= $m['membro_nome'] ?></div>
                            <small class="text-muted"><i class="bi bi-hash"></i> ROL: <?= $m['membro_registro_interno'] ?></small>
                        </td>
                        <td>
                            <div class="small mb-1">
                                <span class="badge bg-dark text-ipb border border-ipb w-100 text-start">
                                    <i class="bi bi-person-badge me-1"></i> <?= !empty($m['cargos_nomes']) ? $m['cargos_nomes'] : 'Sem Cargo' ?>
                                </span>
                            </div>
                            <div class="small mb-1">
                                <span class="badge bg-light text-muted border w-100 text-start">
                                    <i class="bi bi-people me-1"></i> <?= !empty($m['sociedades_nomes']) ? $m['sociedades_nomes'] : 'Sem Sociedade' ?>
                                </span>
                            </div>
                            <div class="small">
                                <span class="badge bg-light text-danger border border-danger w-100 text-start">
                                    <i class="bi bi-book me-1"></i> <?= !empty($m['classes_nomes']) ? $m['classes_nomes'] : 'Não matriculado' ?>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="small"><i class="bi bi-whatsapp text-success"></i> <?= $m['membro_telefone'] ?></div>
                            <div class="small text-muted text-truncate" style="max-width: 150px;">
                                <i class="bi bi-geo-alt"></i> <?= $m['membro_endereco_cidade'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="small"><strong>Nasc:</strong> <?= date('d/m/Y', strtotime($m['membro_data_nascimento'])) ?></div>
                            <div class="small text-muted"><strong>Bat:</strong> <?= $m['membro_data_batismo'] ? date('d/m/Y', strtotime($m['membro_data_batismo'])) : '---' ?></div>
                        </td>
						<td class="text-center">
							<a href="<?= url('PesquisaMembro/perfil/' . $m['membro_id']) ?>"
								class="btn btn-sm btn-outline-secondary"
								title="Ver Ficha Completa">
								<i class="bi bi-person-bounding-box"></i>
							</a>
						</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif(isset($_GET['nome'])): ?>
        <div class="alert alert-warning text-center border-0 shadow-sm mt-3">
            <i class="bi bi-exclamation-triangle me-2"></i> Nenhum membro encontrado com os filtros aplicados.
        </div>
    <?php endif; ?>
</div>

<script>
// Lógica para carregamento dinâmico de Bairros
document.getElementById('select-cidade').addEventListener('change', function() {
    const cidade = this.value;
    const selectBairro = document.getElementById('select-bairro');
    selectBairro.innerHTML = '<option value="">Carregando...</option>';

    if (!cidade) {
        selectBairro.innerHTML = '<option value="">Todos os Bairros</option>';
        return;
    }

    fetch(`<?= url('PesquisaMembro/buscarBairros') ?>?cidade=${encodeURIComponent(cidade)}`)
        .then(response => response.json())
        .then(data => {
            selectBairro.innerHTML = '<option value="">Todos os Bairros</option>';
            data.forEach(item => {
                selectBairro.innerHTML += `<option value="${item.membro_endereco_bairro}">${item.membro_endereco_bairro}</option>`;
            });
        });
});

// Função de Exportação seguindo o padrão financeiro
function exportarMembrosExcel() {
    const campos = [
        'nome', 'genero', 'nasc_ini', 'nasc_fim',
        'sociedade_id', 'classe_id', 'cargo_id',
        'bat_ini', 'bat_fim', 'cidade', 'bairro',
        'cad_ini', 'cad_fim'
    ];

    let params = [];

    campos.forEach(campo => {
        const el = document.querySelector(`[name="${campo}"]`);
        if (el && el.value) {
            params.push(`${campo}=${encodeURIComponent(el.value)}`);
        }
    });

    const queryString = params.join('&');
    const baseUrl = "<?= url('PesquisaMembro/exportarExcel') ?>";

    // Redireciona para o download
    window.location.href = baseUrl + '?' + queryString;
}
</script>
