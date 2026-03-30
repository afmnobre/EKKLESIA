<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - EKKLESIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Verde Oficial IPB e Tons Auxiliares */
        :root {
            --ipb-verde: #006437;
            --ipb-verde-escuro: #004d2a;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .card-login {
            width: 380px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header-ipb {
            background-color: white;
            padding: 30px 20px 10px;
            text-align: center;
        }

        .logo-sarca {
            width: 80px;
            margin-bottom: 15px;
        }

        .logo-completo {
            max-width: 220px;
            height: auto;
        }

        .btn-ipb {
            background-color: var(--ipb-verde);
            border-color: var(--ipb-verde);
            color: white;
            font-weight: 600;
            padding: 12px;
            transition: all 0.3s;
        }

        .btn-ipb:hover {
            background-color: var(--ipb-verde-escuro);
            border-color: var(--ipb-verde-escuro);
            color: white;
            transform: translateY(-1px);
        }

        .form-control:focus {
            border-color: var(--ipb-verde);
            box-shadow: 0 0 0 0.25rem rgba(0, 100, 55, 0.25);
        }

        .ekklesia-title {
            color: var(--ipb-verde);
            letter-spacing: 2px;
            font-weight: 800;
            font-size: 1.2rem;
            margin-top: 10px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="card card-login">
    <div class="card-header-ipb">
        <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Sarça IPB" class="logo-sarca d-block mx-auto">

        <h3 class="ekklesia-title mb-3">EKKLESIA</h3>

        <hr class="mx-5 opacity-25">
    </div>

    <div class="card-body p-4 pt-2">
        <?php if (!empty($_SESSION['erro'])): ?>
            <div class="alert alert-danger py-2 small">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('auth/autenticar') ?>">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">E-mail</label>
                <input type="email" name="email" class="form-control form-control-lg" placeholder="exemplo@ipb.org.br" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Senha</label>
                <input type="password" name="senha" class="form-control form-control-lg" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-ipb w-100 shadow-sm">
                ACESSAR PAINEL
            </button>
        </form>
    </div>

    <div class="card-footer bg-white border-0 pb-4 text-center">
        <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Igreja Presbiteriana do Brasil" class="logo-completo opacity-75">
    </div>
</div>

</body>
</html>
