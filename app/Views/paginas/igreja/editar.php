<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">⛪ Dados da Instituição</h3>
        <a href="<?= url('igreja') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">✏️ Atualizar Informações</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= url('igreja/atualizar/' . $igreja['igreja_id']) ?>">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nome da Igreja</label>
                                <input
                                    type="text"
                                    name="nome"
                                    class="form-control"
                                    value="<?= htmlspecialchars($igreja['igreja_nome'] ?? '') ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">CNPJ</label>
                                <input
                                    type="text"
                                    name="cnpj"
                                    class="form-control"
                                    value="<?= htmlspecialchars($igreja['igreja_cnpj'] ?? '') ?>"
                                >
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Endereço Completo</label>
                                <input
                                    type="text"
                                    name="endereco"
                                    class="form-control"
                                    value="<?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?>"
                                >
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-5">
                                <i class="bi bi-check-lg"></i> Salvar Alterações
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold">Dica</h6>
                    <p class="small text-muted mb-0">
                        Certifique-se de que o CNPJ e o Endereço estejam corretos, pois esses dados são utilizados na geração de relatórios e documentos oficiais da igreja.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
