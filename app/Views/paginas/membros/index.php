<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'senha_atualizada'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Sucesso!</strong> A senha do membro foi atualizada com segurança.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<style>
@keyframes pulse-red {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 5px rgba(220, 53, 69, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
.animate-pulse {
    animation: pulse-red 2s infinite;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">📋 Membros Cadastrados</h3>

		<div class="d-flex gap-2 align-items-center">
            <div class="input-group shadow-sm" style="width: 250px;">
				<span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
				<input type="text" id="buscaMembro" class="form-control form-control-sm border-start-0" placeholder="Buscar por nome...">
			</div>

			<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalQrLoginMembro" style="font-size: 0.85rem;">
				<i class="bi bi-person-lock me-1"></i> LINK DO PORTAL
			</button>

			<a href="<?= url('membros/pendentes') ?>" class="btn btn-sm btn-warning shadow-sm position-relative fw-bold" style="font-size: 0.85rem;">
				<i class="bi bi-person-plus-fill me-1"></i>
				Aprovações
				<?php if ($totalPendentes > 0): ?>
					<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
						<?= $totalPendentes ?>
					</span>
				<?php endif; ?>
			</a>

			<button type="button" class="btn btn-sm btn-outline-success shadow-sm"
					id="btnQrCadastroMembro"
					data-bs-toggle="modal"
					data-bs-target="#modalQrCadastroMembro"
					style="font-size: 0.85rem;">
				 <i class="bi bi-qr-code-scan me-1"></i>Auto-Cadastro
			</button>

			<a href="<?= url('Membros/fichaCadastroManual') ?>" target="_blank" class="btn btn-sm btn-outline-secondary shadow-sm" style="font-size: 0.85rem;">
				<i class="bi bi-printer me-1"></i>Ficha Manual
			</a>

			<a href="<?= url('membros/create') ?>" class="btn btn-sm btn-primary" style="font-size: 0.85rem;">
				<i class="bi bi-plus-circle me-1"></i> Novo Membro
			</a>
		</div>
    </div>

    <div class="btn-toolbar mb-3 justify-content-center" role="toolbar">
        <div class="btn-group flex-wrap shadow-sm">
            <?php foreach(range('A', 'Z') as $char): ?>
                <button type="button"
                        class="btn btn-white border btn-letra"
                        data-letra="<?= $char ?>">
                    <?= $char ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelaMembros">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th>Id</th>
                            <th class="ps-4">Membro</th>
                            <th>Cargos / Funções</th>
                            <th>Contato</th>
                            <th>Status</th>
                            <th class="text-center">Ações Rápidas</th>
                        </tr>
                    </thead>
                    <tbody id="corpoTabelaMembros">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDinamicoMembros" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalDialogTamanho">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-secondary" id="tituloModalDinamico">Informações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="conteudoModalDinamico">
                </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .modal.show, .modal.show * { visibility: visible; }
    .modal.show { position: absolute; left: 0; top: 0; width: 100%; }
    .modal-footer, .btn-close, .btn, .card button { display: none !important; }
    .modal-content { border: none !important; box-shadow: none !important; }
    .card { border: 1px solid #ccc !important; }
}
.logo-footer-modal {
    max-height: 40px; width: auto; opacity: 0.8; display: block; margin: 0 auto;
}
</style>

<div class="modal fade" id="modalQrCadastroMembro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Auto-Cadastro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB" class="img-fluid mb-4" style="max-height: 60px;">
                <p class="text-muted fw-bold mb-1">Ficha de Membro Online</p>
                <p class="text-muted small mb-4">Apresente este QR Code para novos membros preencherem os dados.</p>
                <div id="qrcode_cadastro" class="d-flex justify-content-center p-3 border rounded-4 bg-white shadow-sm mb-4" style="min-height: 220px;"></div>
                <div class="card bg-light border-0 rounded-4 mb-4">
                    <div class="card-body py-3">
                        <small class="text-muted d-block fw-bold text-uppercase mb-2" style="font-size: 0.65rem;">Link de Cadastro:</small>
                        <input type="text" id="inputLinkCadastro" value="<?= full_url('PortalMembro/cadastro/' . $_SESSION['usuario_igreja_id']) ?>" style="position: absolute; left: -9999px;">
                        <div class="d-flex align-items-center justify-content-center">
                            <code class="fw-bold text-success me-2" style="word-break: break-all;"><?= full_url('PortalMembro/cadastro/' . $_SESSION['usuario_igreja_id']) ?></code>
                            <button class="btn btn-sm btn-outline-success border-0" onclick="copyToClipboard('inputLinkCadastro', 'msgCopyCad')"><i class="bi bi-copy"></i></button>
                        </div>
                        <div id="msgCopyCad" class="text-success small mt-1 fw-bold" style="display:none;">Copiado!</div>
                    </div>
                </div>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Sarça IPB" class="logo-footer-modal">
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center rounded-bottom-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-outline-success px-4 rounded-pill" onclick="downloadModalAsImage('modalQrCadastroMembro', 'Ficha_Cadastro_Membro')"><i class="bi bi-download me-2"></i>Baixar Foto</button>
                <button type="button" class="btn btn-success px-4 rounded-pill" onclick="window.print()"><i class="bi bi-printer me-2"></i>Imprimir</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalQrLoginMembro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-lock me-2"></i>Acesso ao Painel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB" class="img-fluid mb-4" style="max-height: 60px;">
                <p class="text-muted fw-bold mb-1">Login do Membro</p>
                <p class="text-muted small mb-4">Apresente este QR Code para que o membro acesse o portal.</p>
                <div id="qrcode_login" class="d-flex justify-content-center p-3 border rounded-4 bg-white shadow-sm mb-4" style="min-height: 220px;"></div>
                <div class="card bg-light border-0 rounded-4 mb-4">
                    <div class="card-body py-3">
                        <small class="text-muted d-block fw-bold text-uppercase mb-2" style="font-size: 0.65rem;">Link de Login:</small>
                        <input type="text" id="inputLinkLogin" value="<?= full_url('PortalMembro/login/' . $_SESSION['usuario_igreja_id']) ?>" style="position: absolute; left: -9999px;">
                        <div class="d-flex align-items-center justify-content-center">
                            <code class="fw-bold text-primary me-2" style="word-break: break-all;"><?= full_url('PortalMembro/login/' . $_SESSION['usuario_igreja_id']) ?></code>
                            <button class="btn btn-sm btn-outline-primary border-0" onclick="copyToClipboard('inputLinkLogin', 'msgCopyLog')"><i class="bi bi-copy"></i></button>
                        </div>
                        <div id="msgCopyLog" class="text-primary small mt-1 fw-bold" style="display:none;">Copiado!</div>
                    </div>
                </div>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Sarça IPB" class="logo-footer-modal">
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center rounded-bottom-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-outline-primary px-4 rounded-pill" onclick="downloadModalAsImage('modalQrLoginMembro', 'Acesso_Painel_Membro')"><i class="bi bi-download me-2"></i>Baixar Foto</button>
                <button type="button" class="btn btn-primary px-4 rounded-pill" onclick="window.print()"><i class="bi bi-printer me-2"></i>Imprimir</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// FUNÇÃO UNIVERSAL DE CÓPIA
window.copyToClipboard = function(inputId, msgId) {
    var copyText = document.getElementById(inputId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    var msg = document.getElementById(msgId);
    msg.style.display = "block";
    setTimeout(function() { msg.style.display = "none"; }, 2000);
};

// DOWNLOAD MODAL COMO IMAGEM
window.downloadModalAsImage = function(modalId, fileName) {
    const modalContent = document.querySelector('#' + modalId + ' .modal-content');
    if (!modalContent) return;
    const style = document.createElement('style');
    style.id = 'temp-print-style';
    style.innerHTML = `#${modalId} .modal-footer, #${modalId} .btn-close, #${modalId} .card button { display: none !important; }`;
    document.head.appendChild(style);

    html2canvas(modalContent, { backgroundColor: "#ffffff", scale: 2, useCORS: true }).then(function(canvas) {
        document.getElementById('temp-print-style').remove();
        const link = document.createElement('a');
        link.href = canvas.toDataURL("image/png");
        link.download = fileName + '.png';
        link.click();
    });
};

// CONTROLE DE TABELA E PERSISTÊNCIA DE LETRA
let letraAtual = localStorage.getItem('membros_ultima_letra') || 'A';
let modalInstancia = null;

function carregarTabela(letra = '', busca = '') {
    // Se recebeu uma letra, atualiza a global e o localStorage
    if (letra !== '') {
        letraAtual = letra;
        localStorage.setItem('membros_ultima_letra', letra);

        // Atualiza visualmente os botões
        document.querySelectorAll('.btn-letra').forEach(btn => {
            if(btn.dataset.letra === letra) {
                btn.classList.add('active', 'btn-primary', 'text-white');
            } else {
                btn.classList.remove('active', 'btn-primary', 'text-white');
            }
        });
    }

    const corpo = document.getElementById('corpoTabelaMembros');
    corpo.innerHTML = '<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary"></div><br><small>Buscando...</small></td></tr>';

    const url = `<?= url('membros/filtrar') ?>?letra=${letraAtual}&busca=${encodeURIComponent(busca)}`;

    fetch(url)
        .then(res => res.text())
        .then(html => {
            corpo.innerHTML = html;
        })
        .catch(err => {
            corpo.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erro de conexão.</td></tr>';
        });
}

// INICIALIZAÇÃO
document.addEventListener('DOMContentLoaded', function() {
    // 1. Carrega a tabela na letra salva
    carregarTabela(letraAtual);

    // 2. Configura botões de letras
    document.querySelectorAll('.btn-letra').forEach(btn => {
        btn.onclick = function() {
            document.getElementById('buscaMembro').value = '';
            carregarTabela(this.dataset.letra);
        };
    });

    // 3. Configura Busca com Debounce
    let timeoutBusca = null;
    document.getElementById('buscaMembro').addEventListener('input', function() {
        clearTimeout(timeoutBusca);
        timeoutBusca = setTimeout(() => {
            carregarTabela('', this.value);
        }, 500);
    });

    // 4. Gerador de QR Codes
    const igrejaId = "<?= $_SESSION['usuario_igreja_id'] ?>";
    const urlBase = "<?= full_url('PortalMembro/') ?>";

    document.getElementById('modalQrLoginMembro').addEventListener('show.bs.modal', function() {
        const container = document.getElementById('qrcode_login');
        container.innerHTML = '';
        if (typeof QRCode !== "undefined") {
            new QRCode(container, { text: urlBase + "login/" + igrejaId, width: 220, height: 220, colorDark : "#0d6efd" });
        }
    });

    document.getElementById('modalQrCadastroMembro').addEventListener('show.bs.modal', function() {
        const container = document.getElementById('qrcode_cadastro');
        container.innerHTML = '';
        if (typeof QRCode !== "undefined") {
            new QRCode(container, { text: urlBase + "cadastro/" + igrejaId, width: 220, height: 220, colorDark : "#198754" });
        }
    });
});

// DELEGAÇÃO DE EVENTOS PARA MODAIS DINÂMICOS
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-acao-dinamica');
    if (btn) {
        e.preventDefault();
        const id = btn.getAttribute('data-id');
        const acao = btn.getAttribute('data-acao');
        const titulo = btn.title || "Detalhes";
        const modalEl = document.getElementById('modalDinamicoMembros');
        const modalDialog = document.getElementById('modalDialogTamanho');

        // Tamanho do Modal
        modalDialog.className = (acao === 'certificado') ? 'modal-dialog modal-xl' : 'modal-dialog modal-lg';

        document.getElementById('tituloModalDinamico').innerText = titulo;
        document.getElementById('conteudoModalDinamico').innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>';

        if (!modalInstancia) modalInstancia = new bootstrap.Modal(modalEl);
        modalInstancia.show();

        fetch(`<?= url('membros/getModalContent') ?>/${acao}/${id}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById('conteudoModalDinamico').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('conteudoModalDinamico').innerHTML = '<div class="alert alert-danger m-3">Erro ao carregar.</div>';
            });
    }
});

// FUNÇÕES DE UTILIDADE (Senha, CEP, Carteirinha)
function toggleSenha() {
    const input = document.getElementById('inputNovaSenha');
    const icon = document.getElementById('iconEye');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = "password";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

// Escutador global para CEP
document.addEventListener('keyup', function(event) {
    if (event.target && event.target.id === 'membro_cep') {
        let cep = event.target.value.replace(/\D/g, '');
        if (cep.length > 5) event.target.value = cep.slice(0, 5) + '-' + cep.slice(5, 8);
        if (cep.length === 8) {
            const campos = ['rua', 'bairro', 'cidade', 'uf'];
            campos.forEach(c => { if(document.getElementById('membro_'+c)) document.getElementById('membro_'+c).value = '...'; });
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(r => r.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('membro_rua').value = data.logradouro;
                        document.getElementById('membro_bairro').value = data.bairro;
                        document.getElementById('membro_cidade').value = data.localidade;
                        document.getElementById('membro_uf').value = data.uf;
                        document.getElementById('membro_numero').focus();
                    }
                });
        }
    }
});
</script>
