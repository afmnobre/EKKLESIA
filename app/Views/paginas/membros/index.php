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

        <div class="d-flex gap-3">
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="buscaMembro" class="form-control border-start-0" placeholder="Buscar por nome...">
            </div>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalQrLoginMembro">
    <i class="bi bi-person-lock me-2"></i> LINK DO PORTAL
</button>

			<a href="<?= url('membros/pendentes') ?>" class="btn btn-warning shadow-sm position-relative fw-bold me-3">
				<i class="bi bi-person-plus-fill me-1"></i>
				Aprovações Pendentes

				<?php if ($totalPendentes > 0): ?>
					<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow border border-light animate-pulse">
						<?= $totalPendentes ?>
					</span>
				<?php endif; ?>
			</a>


            <button type="button" class="btn btn-outline-success shadow-sm"
                    id="btnQrCadastroMembro"
                    data-bs-toggle="modal"
                    data-bs-target="#modalQrCadastroMembro">
                 <i class="bi bi-qr-code-scan me-2"></i>Link de Auto-Cadastro
            </button>
            <a href="<?= url('membros/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Novo Membro
            </a>
        </div>
    </div>

    <div class="btn-toolbar mb-3 justify-content-center" role="toolbar">
        <div class="btn-group flex-wrap shadow-sm">
            <?php foreach(range('A', 'Z') as $char): ?>
                <button type="button"
                        class="btn btn-white border btn-letra <?= $char === 'A' ? 'active btn-primary text-white' : '' ?>"
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
    /* Esconde tudo na página */
    body * {
        visibility: hidden;
    }

    /* Exibe apenas o conteúdo do modal ativo e seus filhos */
    .modal.show, .modal.show * {
        visibility: visible;
    }

    /* Posiciona o conteúdo no topo da página impressa */
    .modal.show {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    /* ESCONDE OS BOTÕES E O X DE FECHAR NA IMPRESSÃO */
    .modal-footer, .btn-close, .btn, .card button {
        display: none !important;
    }

    /* Remove sombras e bordas coloridas para economizar tinta e focar no QR */
    .modal-content {
        border: none !important;
        shadow: none !important;
    }

    .card {
        border: 1px solid #ccc !important;
    }
}
    /* Garante que o logo da sarça no rodapé fique centralizado e com tamanho bom */
    .logo-footer-modal {
        max-height: 40px;
        width: auto;
        opacity: 0.8;
        display: block;
        margin: 0 auto;
    }
</style>

<div class="modal fade" id="modalQrCadastroMembro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i>Auto-Cadastro
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB" class="img-fluid mb-4" style="max-height: 60px;">

                <p class="text-muted fw-bold mb-1">Ficha de Membro Online</p>
                <p class="text-muted small mb-4">Apresente este QR Code para novos membros preencherem os dados.</p>

                <div id="qrcode_cadastro" class="d-flex justify-content-center p-3 border rounded-4 bg-white shadow-sm mb-4" style="min-height: 220px;">
                </div>

                <div class="card bg-light border-0 rounded-4 mb-4">
                    <div class="card-body py-3">
                        <small class="text-muted d-block fw-bold text-uppercase mb-2" style="font-size: 0.65rem;">Link de Cadastro:</small>
                        <input type="text" id="inputLinkCadastro" value="<?= full_url('PortalMembro/cadastro/' . $_SESSION['usuario_igreja_id']) ?>" style="position: absolute; left: -9999px;">
                        <div class="d-flex align-items-center justify-content-center">
                            <code class="fw-bold text-success me-2" style="word-break: break-all;">
                                <?= full_url('PortalMembro/cadastro/' . $_SESSION['usuario_igreja_id']) ?>
                            </code>
                            <button class="btn btn-sm btn-outline-success border-0" onclick="copyToClipboard('inputLinkCadastro', 'msgCopyCad')">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                        <div id="msgCopyCad" class="text-success small mt-1 fw-bold" style="display:none;">Copiado!</div>
                    </div>
                </div>

                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Sarça IPB" class="logo-footer-modal">
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center rounded-bottom-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-outline-success px-4 rounded-pill" onclick="downloadModalAsImage('modalQrCadastroMembro', 'Ficha_Cadastro_Membro')">
                    <i class="bi bi-download me-2"></i>Baixar Foto
                </button>
                <button type="button" class="btn btn-success px-4 rounded-pill" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalQrLoginMembro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-lock me-2"></i>Acesso ao Painel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB" class="img-fluid mb-4" style="max-height: 60px;">

                <p class="text-muted fw-bold mb-1">Login do Membro</p>
                <p class="text-muted small mb-4">Apresente este QR Code para que o membro acesse o portal.</p>

                <div id="qrcode_login" class="d-flex justify-content-center p-3 border rounded-4 bg-white shadow-sm mb-4" style="min-height: 220px;">
                </div>

                <div class="card bg-light border-0 rounded-4 mb-4">
                    <div class="card-body py-3">
                        <small class="text-muted d-block fw-bold text-uppercase mb-2" style="font-size: 0.65rem;">Link de Login:</small>
                        <input type="text" id="inputLinkLogin" value="<?= full_url('PortalMembro/login/' . $_SESSION['usuario_igreja_id']) ?>" style="position: absolute; left: -9999px;">
                        <div class="d-flex align-items-center justify-content-center">
                            <code class="fw-bold text-primary me-2" style="word-break: break-all;">
                                <?= full_url('PortalMembro/login/' . $_SESSION['usuario_igreja_id']) ?>
                            </code>
                            <button class="btn btn-sm btn-outline-primary border-0" onclick="copyToClipboard('inputLinkLogin', 'msgCopyLog')">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                        <div id="msgCopyLog" class="text-primary small mt-1 fw-bold" style="display:none;">Copiado!</div>
                    </div>
                </div>

                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Sarça IPB" class="logo-footer-modal">
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center rounded-bottom-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-outline-primary px-4 rounded-pill" onclick="downloadModalAsImage('modalQrLoginMembro', 'Acesso_Painel_Membro')">
                    <i class="bi bi-download me-2"></i>Baixar Foto
                </button>
                <button type="button" class="btn btn-primary px-4 rounded-pill" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

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

// FUNÇÃO PARA BAIXAR O CONTEÚDO DO MODAL COMO IMAGEM (PNG)
window.downloadModalAsImage = function(modalId, fileName) {
    // 1. Seleciona o elemento que contém todo o visual do modal (modal-content)
    const modalContent = document.querySelector('#' + modalId + ' .modal-content');

    if (!modalContent) {
        console.error("Erro: Conteúdo do modal não encontrado.");
        return;
    }

    // 2. Cria um estilo temporário para esconder os botões ANTES da captura
    const style = document.createElement('style');
    style.id = 'temp-print-style';
    style.innerHTML = `
        #${modalId} .modal-footer,
        #${modalId} .btn-close,
        #${modalId} .card button {
            display: none !important;
        }
    `;
    document.head.appendChild(style);

    // 3. Usa a biblioteca html2canvas para renderizar o elemento em imagem
    html2canvas(modalContent, {
        backgroundColor: "#ffffff", // Garante fundo branco (essencial para o QR Code)
        scale: 2, // Aumenta a qualidade da imagem (DPI)
        useCORS: true, // Permite carregar imagens de domínios externos (se houver)
        logging: false, // Desativa logs no console
    }).then(function(canvas) {
        // 4. Remove o estilo temporário para reexibir os botões na tela
        document.getElementById('temp-print-style').remove();

        // 5. Converte o canvas gerado para uma URL de imagem PNG
        const image = canvas.toDataURL("image/png");

        // 6. Cria um link temporário para o download automático
        const link = document.createElement('a');
        link.href = image;
        link.download = fileName + '.png';

        // Simula o clique para iniciar o download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    }).catch(function(error) {
        console.error("Erro ao gerar a imagem do modal:", error);
        // Remove o estilo mesmo se der erro para não travar a tela
        if(document.getElementById('temp-print-style')) {
            document.getElementById('temp-print-style').remove();
        }
    });
};

document.addEventListener("DOMContentLoaded", function() {
    const igrejaId = "<?= $_SESSION['usuario_igreja_id'] ?>";
    const urlBase = "<?= full_url('PortalMembro/') ?>";

    // GERAÇÃO DO QR CODE DE LOGIN
    const modalLogin = document.getElementById('modalQrLoginMembro');
    const containerLogin = document.getElementById('qrcode_login');

    modalLogin.addEventListener('show.bs.modal', function() {
        containerLogin.innerHTML = '';
        if (typeof QRCode !== "undefined") {
            new QRCode(containerLogin, {
                text: urlBase + "login/" + igrejaId,
                width: 220,
                height: 220,
                colorDark : "#0d6efd",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });

    // GERAÇÃO DO QR CODE DE CADASTRO
    const modalCadastro = document.getElementById('modalQrCadastroMembro');
    const containerCadastro = document.getElementById('qrcode_cadastro');

    modalCadastro.addEventListener('show.bs.modal', function() {
        containerCadastro.innerHTML = '';
        if (typeof QRCode !== "undefined") {
            new QRCode(containerCadastro, {
                text: urlBase + "cadastro/" + igrejaId,
                width: 220,
                height: 220,
                colorDark : "#198754",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });
});
</script>

<script>
// Variável global para a instância do modal
let modalInstancia = null;

// 1. Função de Carregamento da Tabela
function carregarTabela(letra = 'A', busca = '') {
    const corpo = document.getElementById('corpoTabelaMembros');
    corpo.innerHTML = '<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary"></div><br><small>Buscando...</small></td></tr>';

    const url = `<?= url('membros/filtrar') ?>?letra=${letra}&busca=${encodeURIComponent(busca)}`;

    fetch(url)
        .then(res => res.text())
        .then(html => {
            corpo.innerHTML = html;
            // Não precisamos mais chamar vincularEventos aqui,
            // pois usaremos delegação de eventos abaixo.
        })
        .catch(err => {
            corpo.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erro de conexão.</td></tr>';
        });
}

// 2. DELEGAÇÃO DE EVENTOS (O SEGREDO PARA AJAX)
// Escutamos o clique no DOCUMENTO, assim funciona mesmo para botões que ainda não foram criados
document.addEventListener('click', function(e) {
    // Verifica se o elemento clicado (ou o pai dele, caso clique no emoji) tem a classe
    const btn = e.target.closest('.btn-acao-dinamica');

    if (btn) {
        e.preventDefault();

        const id = btn.getAttribute('data-id');
        const acao = btn.getAttribute('data-acao');
        const titulo = btn.title || "Detalhes";
        const modalEl = document.getElementById('modalDinamicoMembros');
        const modalDialog = document.getElementById('modalDialogTamanho');

        // Ajuste de tamanho
        if (acao === 'certificado') {
            modalDialog.classList.remove('modal-lg');
            modalDialog.classList.add('modal-xl');
        } else {
            modalDialog.classList.remove('modal-xl');
            modalDialog.classList.add('modal-lg');
        }

        document.getElementById('tituloModalDinamico').innerText = titulo;
        document.getElementById('conteudoModalDinamico').innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>';

        // Inicializa o modal se ainda não existir
        if (!modalInstancia) {
            modalInstancia = new bootstrap.Modal(modalEl);
        }

        modalInstancia.show();

        // Busca o conteúdo
        fetch(`<?= url('membros/getModalContent') ?>/${acao}/${id}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById('conteudoModalDinamico').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('conteudoModalDinamico').innerHTML = '<div class="alert alert-danger m-3">Erro ao carregar.</div>';
            });
    }

});

document.addEventListener('click', function(e) {
    // 1. Verifica se o clique foi em um botão que abre modal (ajuste a classe se necessário)
    const btn = e.target.closest('[data-acao]');
    if (!btn) return;

    const acao = btn.getAttribute('data-acao');
    const id   = btn.getAttribute('data-id');

    if (acao && id) {
        // Exibe um loader enquanto carrega
        document.getElementById('conteudoModalDinamico').innerHTML = '<div class="p-5 text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>';

        // 2. Busca o conteúdo via Fetch
        fetch(`<?= url('membros/getModalContent') ?>/${acao}/${id}`)
            .then(res => res.text())
            .then(html => {
                const container = document.getElementById('conteudoModalDinamico');

                // Se for carteirinha, garantimos o wrapper para o CSS/JS
                if (acao === 'carteirinha') {
                    container.innerHTML = `<div id="conteudoModalCarteirinha">${html}</div>`;
                } else {
                    container.innerHTML = html;
                }

                // 3. Abre o modal manualmente (Bootstrap 5)
                const modalElement = document.getElementById('modalPrincipal'); // Verifique o ID do seu modal
                if (modalElement) {
                    const modalInstancia = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modalInstancia.show();
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('conteudoModalDinamico').innerHTML = '<div class="alert alert-danger m-3">Erro ao carregar conteúdo.</div>';
            });
    }
});

// 3. Filtro Alfabético
document.querySelectorAll('.btn-letra').forEach(btn => {
    btn.onclick = function() {
        document.querySelectorAll('.btn-letra').forEach(b => b.classList.remove('active', 'btn-primary', 'text-white'));
        this.classList.add('active', 'btn-primary', 'text-white');
        document.getElementById('buscaMembro').value = '';
        carregarTabela(this.dataset.letra);
    };
});

// 4. Busca com Debounce
let timeoutBusca = null;
document.getElementById('buscaMembro').addEventListener('input', function() {
    clearTimeout(timeoutBusca);
    timeoutBusca = setTimeout(() => {
        carregarTabela('', this.value);
    }, 500);
});

// 5. Inicialização
document.addEventListener('DOMContentLoaded', () => carregarTabela('A'));

// CERTIFICADO
// Adicione a biblioteca no arquivo principal para garantir que ela exista
const scriptHtml2Canvas = document.createElement('script');
scriptHtml2Canvas.src = "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js";
document.head.appendChild(scriptHtml2Canvas);

// Escutador Global de Cliques
document.addEventListener('click', function(event) {
    // Verifica se o elemento clicado é o botão de baixar (pela classe ou ID)
    if (event.target && (event.target.id === 'btnBaixarCertificado' || event.target.closest('#btnBaixarCertificado'))) {

        const elemento = document.getElementById('capturaCertificado');
        if (!elemento) {
            console.error("Elemento de captura não encontrado no DOM.");
            return;
        }

        const btn = event.target.closest('#btnBaixarCertificado');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Gerando...';
        btn.disabled = true;

        html2canvas(elemento, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff'
        }).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png', 1.0);
            link.download = 'Certificado_Batismo.png';
            link.click();

            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }).catch(err => {
            console.error(err);
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        });
    }
});

//CORREIOS
// Escutador global para elementos do modal de endereço
document.addEventListener('keyup', function(event) {
    if (event.target && event.target.id === 'membro_cep') {
        let cep = event.target.value.replace(/\D/g, '');

        // Aplica máscara visual 00000-000
        if (cep.length > 5) {
            event.target.value = cep.slice(0, 5) + '-' + cep.slice(5, 8);
        }

        // Quando atingir 8 números, busca automaticamente
        if (cep.length === 8) {
            consultarViaCep(cep);
        }
    }
});

document.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'btnBuscarCep' || event.target.closest('#btnBuscarCep')) {
        const cep = document.getElementById('membro_cep').value.replace(/\D/g, '');
        consultarViaCep(cep);
    }
});

function consultarViaCep(cep) {
    if (cep.length !== 8) return;

    // Sinaliza carregamento nos campos
    const campos = ['rua', 'bairro', 'cidade', 'uf'];
    campos.forEach(c => document.getElementById('membro_' + c).value = '...');

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                alert("CEP não encontrado!");
                campos.forEach(c => document.getElementById('membro_' + c).value = '');
                return;
            }
            // Preenche os campos com o retorno da API
            document.getElementById('membro_rua').value = data.logradouro;
            document.getElementById('membro_bairro').value = data.bairro;
            document.getElementById('membro_cidade').value = data.localidade;
            document.getElementById('membro_uf').value = data.uf;

            // Foca no número para agilizar o preenchimento
            document.getElementById('membro_numero').focus();
        })
        .catch(err => {
            console.error("Erro na busca do CEP:", err);
            alert("Erro ao conectar com o serviço de CEP.");
        });
}

document.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'btnSalvarEndereco') {

        const btn = event.target;
        const dados = {
            membro_id: document.getElementById('membro_id').value,
            cep: document.getElementById('membro_cep').value,
            rua: document.getElementById('membro_rua').value,
            cidade: document.getElementById('membro_cidade').value,
            estado: document.getElementById('membro_uf').value
        };

        // Validação simples
        if (!dados.cep || !dados.rua) {
            alert("Por favor, preencha ao menos o CEP e a Rua.");
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';

        // Envia para o seu arquivo de processamento PHP
        fetch('membros/salvar_endereco.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        })
        .then(res => res.json())
        .then(retorno => {
            if (retorno.success) {
                alert("Endereço salvo com sucesso!");
                $('#modalDinamico').modal('hide'); // Fecha o modal (se usar JQuery)
            } else {
                alert("Erro ao salvar: " + retorno.message);
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-save me-2"></i> SALVAR ENDEREÇO';
        });
    }
});

//CARTEIRINHA
// Delegar o evento de clique para o corpo do documento (funciona com AJAX)
$(document).on('click', '#btn-gerar-img-carteirinha', function() {
    const btn = $(this);
    const entrada = document.getElementById('printArea');

    if (!entrada) {
        alert("Erro: Área de impressão não encontrada.");
        return;
    }

    btn.html('<i class="fa fa-spinner fa-spin"></i> Processando...').prop('disabled', true);
    entrada.classList.add('is-capturing');

    html2canvas(entrada, {
        scale: 4,
        useCORS: true,
        logging: false,
        backgroundColor: "#ffffff"
    }).then(canvas => {
        const link = document.createElement('a');
        // Pega o nome do membro do display oculto se existir ou usa um padrão
        const nomeMembro = document.querySelector('.info-valor') ? document.querySelector('.info-valor').innerText.replace(/\s+/g, '_') : 'Carteirinha';

        link.download = `Carteirinha_${nomeMembro}.png`;
        link.href = canvas.toDataURL("image/png", 1.0);
        link.click();

        entrada.classList.remove('is-capturing');
        btn.html('<i class="fa fa-download"></i> Baixar Imagem (PNG)').prop('disabled', false);
    }).catch(err => {
        console.error("Erro html2canvas:", err);
        entrada.classList.remove('is-capturing');
        btn.prop('disabled', false);
    });
});

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

</script>
