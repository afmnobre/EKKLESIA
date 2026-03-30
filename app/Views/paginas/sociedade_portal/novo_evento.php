<?php $this->rawview('sociedade_portal/header', ['titulo' => 'Novo Evento', 'sociedade' => $sociedade , 'ativo' => 'eventos']); ?>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-operacional p-4 bg-white border-top border-4 border-primary">

                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-calendar-plus me-2 text-primary"></i>Cadastrar Novo Evento
                    </h5>
                </div>

                <form action="<?= url('sociedadeLider/processarNovoEvento') ?>" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="secao-titulo mb-2">Título do Evento</label>
                        <input type="text" name="titulo" class="form-control form-control-lg border-light bg-light"
                               placeholder="Ex: Noite do Pastel, Vigília, Congresso..." required>
                    </div>

                    <div class="mb-3">
                        <label class="secao-titulo mb-2">Local</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="local" class="form-control border-light bg-light" placeholder="Salão Social, Templo, Endereço...">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="secao-titulo mb-2">Início</label>
                            <input type="datetime-local" name="data_inicio" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="secao-titulo mb-2">Término (Opcional)</label>
                            <input type="datetime-local" name="data_fim" class="form-control border-light bg-light">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="secao-titulo mb-2">Valor/Investimento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-muted">R$</span>
                                <input type="number" step="0.01" name="valor" class="form-control border-light bg-light" placeholder="0,00">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="secao-titulo mb-2">Descrição / Informações Adicionais</label>
                        <textarea name="descricao" class="form-control border-light bg-light" rows="4"
                                  placeholder="Detalhes sobre o evento, o que levar, quem pode participar..."></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= url('sociedadeLider/dashboard') ?>" class="btn btn-light fw-bold px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-bold px-5 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Publicar Evento
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->rawview('sociedade_portal/footer'); ?>
