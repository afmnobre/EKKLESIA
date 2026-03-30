<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= url('sociedadeLider/index') ?>" class="btn btn-light rounded-circle shadow-sm">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0 text-dark">Cadastrar Evento</h4>
            <small class="text-muted"><?= $sociedade['sociedade_nome'] ?></small>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="<?= url('sociedadeLider/processarNovoEvento') ?>" method="POST">

                <div class="mb-3">
                    <label class="label-custom text-secondary mb-1">TÍTULO DO EVENTO</label>
                    <input type="text" name="titulo" class="form-control border-light bg-light" placeholder="Nome da atividade" required>
                </div>

                <div class="mb-3">
                    <label class="label-custom text-secondary mb-1">LOCAL</label>
                    <input type="text" name="local" class="form-control border-light bg-light" placeholder="Onde será realizado?">
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="label-custom text-secondary mb-1">INÍCIO</label>
                        <input type="datetime-local" name="data_inicio" class="form-control border-light bg-light" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="label-custom text-secondary mb-1">FIM (OPCIONAL)</label>
                        <input type="datetime-local" name="data_fim" class="form-control border-light bg-light">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="label-custom text-secondary mb-1">VALOR (SE HOUVER)</label>
                        <div class="input-group">
                            <span class="input-group-text border-light bg-light">R$</span>
                            <input type="text" name="valor" class="form-control border-light bg-light" placeholder="0,00">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="label-custom text-secondary mb-1">DESCRIÇÃO COMPLETA</label>
                    <textarea name="descricao" class="form-control border-light bg-light" rows="4" placeholder="Detalhes adicionais..."></textarea>
                </div>

                <button type="submit" class="btn btn-acesso w-100 py-3 shadow-sm" style="background-color: #003366; color: white; border-radius: 12px; border: none; font-weight: bold;">
                    SALVAR EVENTO <i class="bi bi-calendar-check ms-1"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .label-custom { font-size: 0.7rem; font-weight: 800; letter-spacing: 0.5px; display: block; }
    .form-control:focus { border-color: #b8860b; box-shadow: none; background-color: #fff; }
</style>
