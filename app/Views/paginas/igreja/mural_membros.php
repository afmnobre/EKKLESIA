<!-- Arquivo: app/Views/publico/mural_membros.php -->
<!-- Substitua todo o código do arquivo por esta versão atualizada -->

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
            background-color: #1e293b;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* Ambiente do Quadro Negro / Lousa */
        .mural-quadro {
            background-color: #24372c; /* Cor clássica de lousa escolar verde */
            background-image: radial-gradient(rgba(255,255,255,0.04) 15%, transparent 15%);
            background-size: 4px 4px;
            border: 12px solid #5c4033; /* Moldura de madeira */
            border-radius: 8px;
            box-shadow: inset 0 0 25px rgba(0,0,0,0.7), 0 10px 25px rgba(0,0,0,0.4);
            padding: 40px 25px;
        }

        /* Efeito Giz nos Títulos */
        .titulo-giz {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 0 0 4px rgba(255,255,255,0.4), 1px 1px 2px rgba(0,0,0,0.6);
            letter-spacing: 1px;
        }

        /* Card Estilo Colagem Polaroid (Foto Aumentada) */
        .card-membro-polaroid {
            background: #ffffff;
            padding: 12px 12px 18px 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            transform: rotate(var(--rotacao-polaroid, 0deg));
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: none;
            position: relative;
        }

        .card-membro-polaroid:hover {
            transform: scale(1.06) rotate(0deg) !important;
            box-shadow: 0 12px 22px rgba(0, 0, 0, 0.6);
            z-index: 5;
        }

        /* Fita Adesiva/Durex segurando a colagem */
        .card-membro-polaroid::before {
            content: "";
            position: absolute;
            top: -12px;
            left: 35%;
            width: 30%;
            height: 24px;
            background-color: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(1px);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transform: rotate(-2deg);
        }

        /* Foto do Membro Quadrada Perfeita e Expandida */
        .avatar-container-quadrado {
            width: 100%;
            aspect-ratio: 1 / 1;
            margin-bottom: 12px;
            position: relative;
            overflow: hidden;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }

        .avatar-img-quadrada {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder-quadrado {
            width: 100%;
            height: 100%;
            background-color: #e9ecef;
            color: #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-placeholder-quadrado i {
            font-size: 4.5rem;
        }

        /* Mensagem de Giz abaixo da Colagem */
        .container-mensagem-giz {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            color: #f8f9fa;
            font-size: 0.85rem;
            line-height: 1.3;
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px dashed rgba(255, 255, 255, 0.15);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .modal-body-scroll {
            max-height: 380px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

<div class="container my-5" style="max-width: 1100px;">

    <div class="text-center mb-4">
        <h2 class="titulo-giz fw-bold"><i class="bi bi-heart-pulse-fill text-danger me-2"></i>Mural da Família da Fé</h2>
        <p class="text-white-50 mx-auto mb-0" style="max-width: 650px;">
            Clique sobre a foto de um irmão ou irmã para ler as mensagens deixadas, enviar uma palavra de encorajamento ou registrar um pedido de oração.
        </p>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 text-center shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Sua mensagem foi registrada e enviada com sucesso ao mural!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Início do Quadro Negro -->
    <div class="mural-quadro">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-5 justify-content-center">
            <?php if (!empty($membros)): $index = 0; foreach ($membros as $m):
                $totalMsgs = count($m['mensagens']);
                $temFotoValida = false;
                $urlFotoExibicao = '';

                if (!empty($m['membro_foto_arquivo']) && !empty($m['membro_registro_interno'])) {
                    $caminhoRelativo = "uploads/" . $igreja_id . "/membros/" . $m['membro_registro_interno'] . "/" . $m['membro_foto_arquivo'];
                    $urlFotoExibicao = asset($caminhoRelativo);
                    $temFotoValida = true;
                }

                // Variantes de rotação controladas para o efeito de colagem manual descolada
                $rotacoes = ['-2.5deg', '2deg', '-1.5deg', '3deg', '-3deg', '1.5deg'];
                $rotacaoAtual = $rotacoes[$index % count($rotacoes)];
                $index++;
            ?>
            <div class="col d-flex flex-column justify-content-between align-items-center">

                <!-- Card Estilo Polaroid -->
                <div class="card card-membro-polaroid w-100 shadow text-center"
                     style="--rotacao-polaroid: <?= $rotacaoAtual ?>;"
                     onclick="abrirMuralMembro(<?= htmlspecialchars(json_encode($m), ENT_QUOTES, 'UTF-8') ?>, '<?= $temFotoValida ? $urlFotoExibicao : 'padrao' ?>')">

                    <!-- Container da Foto Expandida e Quadrada -->
                    <div class="avatar-container-quadrado">
                        <?php if ($temFotoValida): ?>
                            <img src="<?= $urlFotoExibicao ?>" class="avatar-img-quadrada" alt="<?= htmlspecialchars($m['membro_nome']) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="avatar-placeholder-quadrado">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Contador de mensagens no topo da foto -->
                        <?php if ($totalMsgs > 0): ?>
                            <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-danger shadow-sm fs-7">
                                <?= $totalMsgs ?> <?= $totalMsgs === 1 ? 'recado' : 'recados' ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Dados dentro do papel fotográfico -->
                    <h6 class="fw-bold text-dark mb-1 text-truncate px-1" title="<?= htmlspecialchars($m['membro_nome']) ?>">
                        <?= htmlspecialchars($m['membro_nome']) ?>
                    </h6>

                    <div class="d-flex flex-wrap justify-content-center gap-1 px-1">
                        <?php if (!empty($m['cargos_nomes'])): ?>
                            <span class="badge bg-primary text-white" style="font-size: 0.65rem; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($m['cargos_nomes']) ?>">
                                <?= htmlspecialchars($m['cargos_nomes']) ?>
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($m['sociedades_siglas'])): ?>
                            <span class="badge bg-dark text-white" style="font-size: 0.65rem;">
                                <?= htmlspecialchars($m['sociedades_siglas']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- MENSAGENS ABAIXO DA FOTO (Efeito Lousa / Giz) -->
                <div class="container-mensagem-giz text-center w-100 px-2">
                    <?php if ($totalMsgs > 0):
                        // Pega o último recado enviado para exibir diretamente abaixo no quadro
                        $ultimaMsg = end($m['mensagens']);
                        $prefixoIcone = $ultimaMsg['msg_tipo'] === 'Oracao' ? '🙏' : '💬';
                    ?>
                        <span class="d-block text-truncate-2" title="<?= htmlspecialchars($ultimaMsg['msg_texto']) ?>">
                            <?= $prefixoIcone ?> "<?= htmlspecialchars($ultimaMsg['msg_texto']) ?>"
                        </span>
                        <small class="text-white-50 d-block mt-1 text-end" style="font-size: 0.7rem;">
                            - Por: <?= htmlspecialchars($ultimaMsg['msg_autor']) ?>
                        </small>
                    <?php else: ?>
                        <span class="text-white-50 opacity-75 small"><em><i class="bi bi-chat-left text-white-50 me-1"></i> Sem recados ainda</em></span>
                    <?php endif; ?>
                </div>

            </div>
            <?php endforeach; else: ?>
                <div class="col-12 text-center py-5">
                    <p class="titulo-giz fs-4">Nenhum membro ativo disponível para o mural no momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Detalhes e Envio de Mensagens -->
<div class="modal fade" id="modalMural" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-dark text-white border-0">
                <div class="d-flex align-items-center">
                    <div class="me-3" id="modal_avatar_container" style="width: 45px; height: 45px;"></div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="modal_nome_membro">Nome do Membro</h6>
                        <small class="text-white-50" style="font-size: 0.75rem;" id="modal_info_membro">Mural de Interações</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light modal-body-scroll" id="lista_recados_membro"></div>

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
