<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $titulo ?? 'Login - Portal de Sociedades' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root { --cor-ipb: #003366; --cor-detalhe: #b8860b; }
        body {
            background: radial-gradient(circle at center, #004080 0%, var(--cor-ipb) 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px 0;
        }
        .login-container { width: 100%; max-width: 450px; padding: 20px; }

        .card-login {
            background: white; border-radius: 25px; padding: 35px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            border-bottom: 8px solid var(--cor-detalhe); position: relative;
        }

        /* Estilo para os Logos das Sociedades */
        .grid-sociedades {
            display: flex; flex-wrap: wrap; justify-content: center;
            gap: 12px; margin-bottom: 25px;
        }
        .img-sociedade-login {
            width: 45px; height: 45px; object-fit: cover;
            border-radius: 50%; border: 2px solid #eee;
            transition: transform 0.3s;
        }
        .img-sociedade-login:hover { transform: scale(1.2); border-color: var(--cor-detalhe); }

        .logo-ipb-topo { height: 50px; margin-bottom: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); }
        .btn-acesso {
            background-color: var(--cor-ipb); color: white; border: none;
            padding: 14px; border-radius: 12px; font-weight: 700;
            transition: all 0.3s;
        }
        .btn-acesso:hover { background-color: #002244; transform: translateY(-2px); color: white; }
        .label-custom { font-size: 0.75rem; font-weight: 800; color: #666; letter-spacing: 1px; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }

		/* Container flexível para alinhar verticalmente ao centro */
        .header-login-topo {
            width: 100%;
        }

        .logo-ipb-topo {
            height: 60px; /* Aumentei um pouco para dar mais destaque */
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            display: block; /* Garante que se comporte como bloco para o flex */
        }

        .nome-igreja-topo {
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            line-height: 1.2;
            max-width: 90%; /* Evita que o nome da igreja encoste nas bordas em telas pequenas */
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="header-login-topo d-flex flex-column align-items-center mb-4">
        <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb-topo mb-2" alt="IPB">

        <div class="nome-igreja-topo text-center">
            <?= htmlspecialchars($nomeIgreja ?? 'Igreja Presbiteriana') ?>
        </div>
    </div>
    <div class="card-login text-center">
        <h4 class="fw-bold mb-1">Portal do Líder</h4>

        <div class="grid-sociedades">
            <?php if(!empty($sociedades)): foreach($sociedades as $soc): ?>
                <?php
                    $imgSoc = !empty($soc['sociedade_logo'])
                        ? url("assets/uploads/" . $soc['sociedade_logo'])
                        : url("assets/img/default_sociedade.png");
                ?>
                <img src="<?= $imgSoc ?>" class="img-sociedade-login shadow-sm" title="<?= $soc['sociedade_nome'] ?>">
            <?php endforeach; endif; ?>
        </div>

        <?php if(isset($_GET['erro'])): ?>
            <div class="alert alert-danger py-2 small" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Acesso negado.
            </div>
        <?php endif; ?>

        <form action="<?= url('sociedadeLider/autenticar') ?>" method="POST">
            <div class="mb-3 text-start">
                <label class="label-custom">WHATSAPP DO LÍDER</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp"></i></span>
                    <input type="tel" name="celular" id="celular" class="form-control bg-light border-start-0" placeholder="(00) 00000-0000" required autofocus>
                </div>
            </div>

            <div class="mb-4 text-start">
                <label class="label-custom">SENHA DA SOCIEDADE</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                    <input type="password" name="senha" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-acesso w-100 shadow-sm">
                ENTRAR NO PAINEL <i class="bi bi-arrow-right-short fs-5"></i>
            </button>
        </form>

        <div class="divider"></div>
        <img src="<?= url('assets/img/logo_ipb.png') ?>" style="height: 30px; opacity: 0.5;" alt="IPB Rodapé">
    </div>

    <p class="text-center text-white-50 mt-4 small">
        &copy; <?= date('Y') ?> EKKLESIA - Gestão Eclesiástica
    </p>
</div>

<script>
document.getElementById('celular').addEventListener('input', function (e) {
    let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
    if (!x[2]) e.target.value = x[1];
    else e.target.value = '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
});
</script>
</body>
</html>
