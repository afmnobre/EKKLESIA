<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mural de Comunhão & Oração - EKKLESIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        .avatar-container {
            width: 85px;
            height: 85px;
            margin: 0 auto 12px;
            position: relative;
        }
        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .avatar-icon-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid #fff;
            background-color: #e9ecef;
            color: #6c757d;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-icon-placeholder i {
            font-size: 3rem;
            line-height: 1;
        }
        .card-membro {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            border: none;
            background-color: #ffffff;
        }
        .card-membro:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.08);
        }
        .modal-body-scroll {
            max-height: 380px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

<div class="container my-5" style="max-width: 1000px;">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark"><i class="bi bi-heart-pulse-fill text-danger me-2"></i>Mural da Família da Fé</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">
            Clique sobre o avatar de um irmão ou irmã para ler as mensagens deixadas, enviar uma palavra de encorajamento ou registrar um pedido de oração.
        </p>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 text-center shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Sua mensagem foi registrada e enviada com sucesso ao mural!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
        <?php if (!empty($membros)): foreach ($membros as $m):
            $totalMsgs = count($m['mensagens']);

            $temFotoValida = false;
            $urlFotoExibicao = '';

            // Baseado exatamente no seu helper funcional extraído do seu ambiente:
            if (!empty($m['membro_foto_arquivo']) && !empty($m['membro_registro_interno'])) {
                $caminhoRelativo = "uploads/" . $igreja_id . "/membros/" . $m['membro_registro_interno'] . "/" . $m['membro_foto_arquivo'];
                $urlFotoExibicao = asset($caminhoRelativo);
                $temFotoValida = true;
            }
        ?>
        <div class="col">
            <div class="card h-100 text-center p-3 card-membro shadow-sm" onclick="abrirMuralMembro(<?= htmlspecialchars(json_encode($m), ENT_QUOTES, 'UTF-8') ?>, '<?= $temFotoValida ? $urlFotoExibicao : 'padrao' ?>')">
                <div class="avatar-container">
                    <?php if ($temFotoValida): ?>
                        <img src="<?= $urlFotoExibicao ?>" class="avatar-img" alt="<?= htmlspecialchars($m['membro_nome']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="avatar-icon-placeholder">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    <?php endif; ?>

                    <?php if ($totalMsgs > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm">
                            <?= $totalMsgs ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($m['membro_nome']) ?>">
                    <?= htmlspecialchars($m['membro_nome']) ?>
                </h6>

                <div class="mb-2 d-flex flex-wrap justify-content-center gap-1">
                    <?php if (!empty($m['cargos_nomes'])): ?>
                        <span class="badge bg-primary text-white" style="font-size: 0.65rem; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($m['cargos_nomes']) ?>">
                            <?= htmlspecialchars($m['cargos_nomes']) ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($m['sociedades_siglas'])): ?>
                        <span class="badge bg-dark text-white" style="font-size: 0.65rem;">
                            <?= htmlspecialchars($m['sociedades_siglas']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <small class="text-muted mt-auto pt-1 d-block" style="font-size: 0.72rem;"><i class="bi bi-chat-left-text me-1"></i> Interagir</small>
            </div>
        </div>
        <?php endforeach; else: ?>
            <div class="col-12 text-center py-5">
                <div class="p-4 bg-white rounded border shadow-sm">
                    <i class="bi bi-people text-muted fs-1 mb-2 d-block"></i>
                    <p class="text-muted mb-0">Nenhum membro ativo disponível para o mural no momento.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalMural" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-dark text-white border-0">
                <div class="d-flex align-items-center">
                    <div class="me-3" id="modal_avatar_container" style="width: 45px; height: 45px;">
                        </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="modal_nome_membro">Nome do Membro</h6>
                        <small class="text-white-50" style="font-size: 0.75rem;" id="modal_info_membro">Mural de Interações</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light modal-body-scroll" id="lista_recados_membro">
                </div>

            <form action="<?= full_url('muralPublico/enviarMensagem') ?>" method="POST" class="modal-footer d-block border-0 bg-white p-4 shadow-sm">
                <input type="hidden" name="membro_id" id="form_membro_id">
                <input type="hidden" name="igreja_id" value="<?= htmlspecialchars($igreja_id) ?>">

                <h6 class="fw-bold text-dark small mb-3 text-uppercase border-bottom pb-1">
                    <i class="bi bg-light p-1 border rounded bi-pencil-square me-1 text-primary"></i> Deixe seu Recado / Oração
                </h6>

                <div class="row g-2 mb-2">
                    <div class="col-7">
                        <input type="text" name="autor" class="form-control form-control-sm text-dark" placeholder="Seu Nome / Família" required maxlength="100">
                    </div>
                    <div class="col-5">
                        <select name="msg_tipo" class="form-select form-select-sm text-dark">
                            <option value="Mensagem">💬 Apenas Recado</option>
                            <option value="Oracao">🙏 Pedido Oração</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <textarea name="texto" class="form-control form-control-sm text-dark" rows="2" placeholder="Escreva aqui uma mensagem edificante..." required maxlength="500"></textarea>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill me-1" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">Enviar ao Mural</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalMuralInstance = null;

document.addEventListener("DOMContentLoaded", function() {
    modalMuralInstance = new bootstrap.Modal(document.getElementById('modalMural'));
});

function abrirMuralMembro(membro, urlFoto) {
    document.getElementById('form_membro_id').value = membro.membro_id;
    document.getElementById('modal_nome_membro').innerText = membro.membro_nome;

    let extras = [];
    if(membro.cargos_nomes) extras.push(membro.cargos_nomes);
    if(membro.sociedades_siglas) extras.push(membro.sociedades_siglas);
    document.getElementById('modal_info_membro').innerText = extras.length > 0 ? extras.join(' | ') : 'Membro';

    const containerAvatarModal = document.getElementById('modal_avatar_container');
    if (urlFoto !== 'padrao') {
        containerAvatarModal.innerHTML = `<img src="${urlFoto}" class="rounded-circle border border-2 border-white" style="width:100%; height:100%; object-fit:cover;">`;
    } else {
        containerAvatarModal.innerHTML = `
            <div class="rounded-circle border border-2 border-white d-flex align-items-center justify-content-center bg-secondary text-white" style="width:100%; height:100%;">
                <i class="bi bi-person-circle fs-4"></i>
            </div>`;
    }

    const containerRecados = document.getElementById('lista_recados_membro');
    containerRecados.innerHTML = "";

// Arquivo: app/Views/publico/mural_membros.php
// Linha: Localize a função abrirMuralMembro no bloco <script> final e substitua o laço forEach por este:

    if (!membro.mensagens || membro.mensagens.length === 0) {
        containerRecados.innerHTML = `
            <div class="text-center py-4 text-muted small">
                <i class="bi bi-chat-square-text d-block fs-3 mb-2 opacity-50"></i>
                Nenhuma mensagem registrada. Seja o primeiro a abençoar este irmão!
            </div>`;
    } else {
        membro.mensagens.forEach(msg => {
            const isOracao = msg.msg_tipo === 'Oracao';
            const badge = isOracao
                ? '<span class="badge bg-warning text-dark me-2"><i class="bi bi-heart-fill me-1 text-danger"></i>Oração</span>'
                : '<span class="badge bg-info text-white me-2">💬 Recado</span>';

            const autorLimpo = msg.msg_autor.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            const textoLimpo = msg.msg_texto.replace(/</g, "&lt;").replace(/>/g, "&gt;");

            // Alterado: Injeção direta do PHP <?= $igreja_id ?> no campo oculto 'igreja_id'
            const botaoDeletar = `
                <form action="<?= full_url('muralPublico/deletarMensagem') ?>" method="POST" class="d-inline m-0" onsubmit="return confirm('Deseja realmente apagar esta mensagem?')">
                    <input type="hidden" name="mensagem_id" value="${msg.mensagem_id}">
                    <input type="hidden" name="igreja_id" value="<?= $igreja_id ?>">
                    <button type="submit" class="btn btn-link text-danger p-0 ms-2 lh-1" title="Excluir">
                        <i class="bi bi-trash fs-6"></i>
                    </button>
                </form>
            `;

            const itemHtml = `
                <div class="card border-0 shadow-sm p-3 mb-2 rounded-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center">
                            <strong class="text-dark small">${autorLimpo}</strong>
                            ${botaoDeletar}
                        </div>
                        ${badge}
                    </div>
                    <p class="mb-0 text-secondary small" style="white-space: pre-wrap;">${textoLimpo}</p>
                </div>`;
            containerRecados.insertAdjacentHTML('beforeend', itemHtml);
        });
    }

    if (modalMuralInstance) {
        modalMuralInstance.show();
    }
}
</script>
</body>
</html>
