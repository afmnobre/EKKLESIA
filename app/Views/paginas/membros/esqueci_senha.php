<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha | <?= $igreja['igreja_nome'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; align-items: center; }
        .card-recovery { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .btn-success { border-radius: 10px; padding: 12px; font-weight: bold; background: #25d366; border: none; }
        .btn-success:hover { background: #128c7e; }
        .logo-ipb { max-width: 150px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4 text-center">

            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB" class="logo-ipb">
            <h4 class="fw-bold">Recuperar Senha</h4>
            <p class="text-muted small mb-4">Insira seu WhatsApp cadastrado para receber o link.</p>

            <?php if(isset($_GET['erro'])): ?>
                <div class="alert alert-danger small py-2">Membro não encontrado com este telefone ou data de nascimento.</div>
            <?php endif; ?>

            <div class="card card-recovery text-start">
                <div class="card-body p-4">
                    <form action="<?= url('PortalMembro/processar_esqueci_senha') ?>" method="POST">
                        <input type="hidden" name="igreja_id" value="<?= $igreja['igreja_id'] ?>">

						<div class="mb-3">
							<label class="form-label small fw-bold text-muted">WhatsApp Cadastrado</label>
							<div class="input-group">
								<span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-whatsapp"></i></span>
								<input type="text" name="membro_telefone" id="telefone" class="form-control border-start-0" placeholder="(00) 00000-0000" required>
							</div>
						</div>

						<div class="mb-4">
							<label class="form-label small fw-bold text-muted">Data de Nascimento</label>
							<div class="input-group">
								<span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
								<input type="date" name="membro_nascimento" class="form-control border-start-0" required>
							</div>
						</div>

                        <button type="submit" class="btn btn-success w-100 shadow-sm">
                            <i class="bi bi-whatsapp"></i> ENVIAR VIA WHATSAPP
                        </button>

                        <a href="<?= url('PortalMembro/login/' . $igreja['igreja_id']) ?>" class="btn btn-link w-100 mt-2 text-muted text-decoration-none small">
                            <i class="bi bi-chevron-left"></i> Voltar ao login
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('telefone').addEventListener('input', function (e) {
    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
    e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
});
</script>

</body>
</html>
