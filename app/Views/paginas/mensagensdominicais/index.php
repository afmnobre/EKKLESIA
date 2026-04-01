<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-secondary mb-0">📜 Mensagens Dominicais</h3>
            <p class="text-muted small">Gerencie o histórico de mensagens e avisos da igreja</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovaMensagem" id="btnNovaMensagem" data-pastor="<?= $pastorId ?>">
            <i class="bi bi-plus-lg"></i> Nova Mensagem
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 100px;">Nº</th>
                            <th style="width: 120px;">Data</th>
                            <th>Título da Mensagem</th>
                            <th>Autor</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mensagens)): foreach ($mensagens as $m): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#<?= $m['igreja_mensagem_dominical_num_historico'] ?></td>
                                <td><?= date('d/m/Y', strtotime($m['igreja_mensagem_dominical_data'])) ?></td>
                                <td>
                                    <span class="fw-bold d-block text-truncate" style="max-width: 300px;"><?= htmlspecialchars($m['igreja_mensagem_dominical_titulo']) ?></span>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                        <?= strip_tags($m['igreja_mensagem_dominical_mensagem']) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-person me-1"></i><?= htmlspecialchars($m['autor_nome'] ?? 'Não informado') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $m['igreja_mensagem_dominical_status'] == 'publicado' ? 'bg-success' : 'bg-warning' ?> rounded-pill">
                                        <?= ucfirst($m['igreja_mensagem_dominical_status']) ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary btn-editar-mensagem"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalNovaMensagem"
                                                data-id="<?= $m['igreja_mensagem_dominical_id'] ?>"
                                                data-num="<?= $m['igreja_mensagem_dominical_num_historico'] ?>"
                                                data-data="<?= $m['igreja_mensagem_dominical_data'] ?>"
                                                data-autor="<?= $m['igreja_mensagem_dominical_autor_id'] ?>"
                                                data-titulo="<?= htmlspecialchars($m['igreja_mensagem_dominical_titulo']) ?>"
                                                data-status="<?= $m['igreja_mensagem_dominical_status'] ?>"
                                                data-mensagem='<?= str_replace("'", "&#39;", $m['igreja_mensagem_dominical_mensagem']) ?>'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= url('mensagemDominical/imprimir/' . $m['igreja_mensagem_dominical_id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <a href="<?= url('mensagemDominical/excluir/' . $m['igreja_mensagem_dominical_id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir esta mensagem?')" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                                    Nenhuma mensagem dominical cadastrada até o momento.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovaMensagem" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form action="<?= url('mensagemDominical/salvar') ?>" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="igreja_mensagem_dominical_id" id="igreja_mensagem_dominical_id" value="">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitulo"><i class="bi bi-journal-plus me-2"></i> Cadastrar Nova Mensagem</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-muted text-uppercase">Número</label>
                        <input type="number" name="igreja_mensagem_dominical_num_historico" id="igreja_mensagem_dominical_num_historico" class="form-control" value="<?= $proximoNumero ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Data da Mensagem</label>
                        <input type="date" name="igreja_mensagem_dominical_data" id="igreja_mensagem_dominical_data" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Autor (Pastor/Membro)</label>
                        <select name="igreja_mensagem_dominical_autor_id" id="igreja_mensagem_dominical_autor_id" class="form-select" required>
                            <option value="">Selecione o autor...</option>
                            <?php foreach ($membros as $m): ?>
                                <option value="<?= $m['membro_id'] ?>"><?= htmlspecialchars($m['membro_nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                        <select name="igreja_mensagem_dominical_status" id="igreja_mensagem_dominical_status" class="form-select">
                            <option value="publicado">Publicado</option>
                            <option value="rascunho">Rascunho</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Título da Mensagem</label>
                        <input type="text" name="igreja_mensagem_dominical_titulo" id="igreja_mensagem_dominical_titulo" class="form-control" placeholder="Ex: Reflexão sobre a Graça" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Texto da Mensagem</label>
                        <textarea name="igreja_mensagem_dominical_mensagem" id="editor_mensagem"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 shadow-sm">Gravar Mensagem</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
    // Configuração do CKEditor
    CKEDITOR.config.versionCheck = false;
    CKEDITOR.replace('editor_mensagem', {
        language: 'pt-br',
        height: 400,
        removeButtons: 'Save,NewPage,ExportPdf,Preview,Print,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField',
        format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
        allowedContent: true,
        baseFloatZIndex: 10055
    });

    // Lógica para EDITAR
    document.querySelectorAll('.btn-editar-mensagem').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('modalTitulo').innerHTML = '<i class="bi bi-pencil-square me-2"></i> Editar Mensagem Dominical';

            document.getElementById('igreja_mensagem_dominical_id').value = this.dataset.id;
            document.getElementById('igreja_mensagem_dominical_num_historico').value = this.dataset.num;
            document.getElementById('igreja_mensagem_dominical_data').value = this.dataset.data;
            document.getElementById('igreja_mensagem_dominical_autor_id').value = this.dataset.autor;
            document.getElementById('igreja_mensagem_dominical_titulo').value = this.dataset.titulo;
            document.getElementById('igreja_mensagem_dominical_status').value = this.dataset.status;

            if (CKEDITOR.instances['editor_mensagem']) {
                CKEDITOR.instances['editor_mensagem'].setData(this.dataset.mensagem);
            }
        });
    });

    // Lógica para NOVO
    document.getElementById('btnNovaMensagem').addEventListener('click', function() {
        document.getElementById('modalTitulo').innerHTML = '<i class="bi bi-journal-plus me-2"></i> Cadastrar Nova Mensagem';

        document.getElementById('igreja_mensagem_dominical_id').value = "";
        document.getElementById('igreja_mensagem_dominical_titulo').value = "";
        document.getElementById('igreja_mensagem_dominical_num_historico').value = "<?= $proximoNumero ?>";
        document.getElementById('igreja_mensagem_dominical_data').value = "<?= date('Y-m-d') ?>";

        const pastorId = this.dataset.pastor;
        document.getElementById('igreja_mensagem_dominical_autor_id').value = pastorId;
        document.getElementById('igreja_mensagem_dominical_status').value = "publicado";

        if (CKEDITOR.instances['editor_mensagem']) {
            CKEDITOR.instances['editor_mensagem'].setData('');
        }
    });

    // Sincronização do CKEditor antes do Submit
    document.querySelector('form').onsubmit = function() {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    };
</script>

<style>
    .cke_combopanel { z-index: 10060 !important; }
    .cke_chrome { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
</style>
