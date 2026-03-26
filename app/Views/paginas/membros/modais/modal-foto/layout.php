<div class="modal-body text-center p-4">
    <form action="<?= url('membros/uploadFoto') ?>" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">
        <input type="hidden" name="membro_registro_interno" value="<?= $membro['membro_registro_interno'] ?>">

        <div class="mb-4">
            <?php
                // AJUSTADO: Agora inclui a subpasta /membros/ no caminho relativo
                $caminhoRelativo = "uploads/" . $membro['membro_igreja_id'] . "/membros/" . $membro['membro_registro_interno'] . "/";

                if(!empty($membro['membro_foto_arquivo'])):
                    $urlFoto = asset($caminhoRelativo . $membro['membro_foto_arquivo']);
            ?>
                <div class="position-relative d-inline-block">
                    <img src="<?= $urlFoto ?>"
                         class="img-thumbnail shadow mb-2"
                         style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid #f8f9fa;">
                </div>
                <p class="small text-muted mb-0">Foto atual do membro</p>
            <?php else: ?>
                <div class="bg-light mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle border shadow-sm"
                     style="width: 120px; height: 120px; background-color: #e9ecef !important;">
                    <i class="bi bi-person-bounding-box text-secondary" style="font-size: 3rem;"></i>
                </div>
                <p class="small text-muted italic">Nenhuma foto cadastrada</p>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label class="btn btn-outline-primary w-100 py-3 shadow-sm"
                   style="border-style: dashed !important; border-width: 2px;"
                   id="label_foto">
                <i class="bi bi-image-fill me-2"></i>
                <span class="fw-bold" id="status_texto">Selecionar Nova Imagem</span>
                <input type="file" name="foto" class="d-none" accept="image/*" required
                       onchange="atualizarPreview(this)">
            </label>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i>SALVAR FOTO
            </button>
            <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">
                Cancelar
            </button>
        </div>
    </form>
</div>

<script>
/**
 * Pequena função para dar feedback visual imediato ao selecionar a foto
 */
function atualizarPreview(input) {
    const label = document.getElementById('label_foto');
    const texto = document.getElementById('status_texto');

    if (input.files && input.files[0]) {
        texto.innerText = "Imagem selecionada!";
        label.classList.replace('btn-outline-primary', 'btn-success');
        label.classList.add('text-white');
    }
}
</script>
