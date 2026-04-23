<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha | EKKLESIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; align-items: center; }
        .card-reset { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .btn-success { border-radius: 10px; padding: 12px; font-weight: bold; background: #005a32; }
    </style>

	<meta property="og:type" content="website">
	<meta property="og:title" content="Redefinição de Senha - EKKLESIA">
	<meta property="og:description" content="Clique aqui para criar sua nova senha de acesso no Portal do Membro.">
	<meta property="og:image" content="<?= full_url('assets/img/logo_ipb.png') ?>">
    <meta property="og:url" content="<?= full_url("PortalMembro/resetar_senha?token=" . $token) ?>">

</head>
<body>

<div class="container text-center">
    <div class="col-md-5 col-lg-4 mx-auto">
        <h4 class="fw-bold mb-3">Criar Nova Senha</h4>
        <div class="card card-reset text-start">
            <div class="card-body p-4">
                <form action="<?= url('PortalMembro/confirmar_nova_senha') ?>" method="POST">
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nova Senha</label>
                        <input type="password" name="membro_senha" class="form-control" placeholder="No mínimo 6 caracteres" required minlength="6">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Confirmar Nova Senha</label>
                        <input type="password" name="confirma_senha" class="form-control" placeholder="Repita a senha" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 shadow-sm">
                        ATUALIZAR SENHA
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
