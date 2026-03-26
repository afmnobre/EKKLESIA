<div class="modal fade" id="modalQrCadastroMembro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-2"></i>Auto-Cadastro de Membros
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <p class="text-muted fw-bold mb-1">Ficha de Membro Online</p>
                <p class="text-muted small mb-4">Apresente este QR Code para que o novo membro preencha os dados pelo celular.</p>

                <div id="qrcode_cadastro" class="d-flex justify-content-center p-3 border rounded-3 bg-white shadow-inner mb-4" style="min-height: 200px;">
                </div>

                <div class="card bg-light border-0">
                    <div class="card-body py-2">
                        <small class="text-muted d-block fw-bold text-uppercase mb-1">Ou compartilhe o link:</small>
                        <code class="fw-bold text-success" style="word-break: break-all;">
                            <?= full_url('PortalMembro/cadastro/' . $_SESSION['usuario_igreja_id']) ?>
                        </code>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary px-4" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir QR
                </button>
            </div>
        </div>
    </div>
</div>
<script>
(function() {
    window.addEventListener('load', function() {
        const qrcodeContainer = document.getElementById('qrcode_cadastro');
        const modalElement = document.getElementById('modalQrCadastroMembro');

        // URL apontando para o Controller PortalMembro e o método cadastro
        const urlCadastroCompleta = "<?= full_url('PortalMembro/cadastro/' . $_SESSION['usuario_igreja_id']) ?>";

        if (modalElement && qrcodeContainer) {
            modalElement.addEventListener('show.bs.modal', function () {
                // Limpa o conteúdo anterior para não duplicar
                qrcodeContainer.innerHTML = '';

                if (typeof QRCode !== "undefined") {
                    try {
                        new QRCode(qrcodeContainer, {
                            text: urlCadastroCompleta,
                            width: 220,
                            height: 220,
                            colorDark : "#198754", // Cor verde (success) para diferenciar do Professor
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    } catch (e) {
                        console.error("Erro ao gerar QR Code de cadastro:", e);
                    }
                } else {
                    qrcodeContainer.innerHTML = '<p class="text-danger small">Biblioteca QRCode não carregada.</p>';
                }
            });
        }
    });
})();
</script>
