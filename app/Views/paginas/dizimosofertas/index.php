<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Conferência de Valores</h4>
                    <div class="text-end">
                        <small class="d-block">1º Conf: <?= $diacono1 ?></small>
                        <small class="d-block">2º Conf: <?= $diacono2 ?></small>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?= url('dizimosofertas/salvar') ?>" method="POST" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Categoria</label>
                            <select name="categoria_id" class="form-select" required>
                                <option value="1">Dízimo</option>
                                <option value="2">Oferta</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Doador / Observação</label>
                            <input type="text" name="descricao" class="form-control" placeholder="Ex: João Silva ou Oferta Geral">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Valor R$</label>
                            <input type="number" step="0.01" name="valor" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100">Registrar Entrada</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; foreach($lancamentos as $l): $total += $l['financeiro_conta_valor']; ?>
                                <tr>
                                    <td><?= $l['financeiro_conta_descricao'] ?></td>
                                    <td>R$ <?= number_format($l['financeiro_conta_valor'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="h5">
                                <tr class="table-warning">
                                    <td class="text-end">Total Conferido:</td>
                                    <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
