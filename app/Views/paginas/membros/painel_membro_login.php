<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login do Membro | EKKLESIA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; align-items: center; padding: 20px 0; }
        .card-login { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .btn-primary { border-radius: 10px; padding: 12px; font-weight: bold; background: #005a32; border: none; }
        .btn-primary:hover { background: #004426; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #dee2e6; }
        .logo-area { text-align: center; margin-bottom: 25px; }
        .logo-ipb { max-width: 180px; height: auto; margin-bottom: 15px; }
        .church-name { color: #005a32; font-size: 1.1rem; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="logo-area">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB Logo" class="logo-ipb">
                <h4 class="fw-bold mt-2 mb-0">Portal do Membro</h4>
                <p class="church-name fw-bold mb-0"><?= $igreja['igreja_nome'] ?></p>
                <p class="text-muted small">Acesse seus dados e sua família</p>
            </div>

            <div class="card card-login">
                <div class="card-body p-4">

                    <?php if(isset($_GET['erro'])): ?>
                        <div class="alert alert-danger small border-0 py-2">
                            <i class="bi bi-exclamation-triangle me-2"></i> Celular ou senha incorretos.
                        </div>
                    <?php endif; ?>

                    <form action="<?= url('PortalMembro/auth') ?>" method="POST">
                        <input type="hidden" name="igreja_id" value="<?= $igreja['igreja_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Seu Celular (WhatsApp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" name="membro_telefone" id="telefone" class="form-control border-start-0" placeholder="(00) 00000-0000" required autocomplete="off">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Sua Senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key"></i></span>
                                <input type="password" name="membro_senha" class="form-control border-start-0" placeholder="******" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            ACESSAR MEU PAINEL <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="small text-muted">Ainda não tem cadastro? <br>
                    <a href="<?= url('PortalMembro/cadastro/' . $igreja['igreja_id']) ?>" class="text-primary fw-bold text-decoration-none">
                        Clique aqui para se registrar
                    </a>
                </p>
                <hr class="mt-4 opacity-25">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Sarça" style="height: 30px; opacity: 0.6;">
            </div>

        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.2.0/vanilla-masker.min.js"></script>

<script>
    VMasker(document.getElementById("telefone")).maskPattern("(99) 99999-9999");
</script>

</body>
</html>
