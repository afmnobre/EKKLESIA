<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Conferência de Dízimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 15px; overflow: hidden; }
        .login-header { background: #212529; color: white; padding: 1.5rem; text-align: center; }
        .btn-primary { background: #212529; border: none; }
        .btn-primary:hover { background: #343a40; }
        .form-control-lg { border-radius: 10px; font-size: 1rem; }
        .logo-login { height: 50px; object-fit: contain; }
        .toggle-type { font-size: 0.75rem; cursor: pointer; text-decoration: none; font-weight: normal; text-transform: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card login-card shadow-lg">
                <div class="login-header">
                    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                        <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" class="logo-login">

                        <?php
                            $caminhoLogo = "assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}";
                            if(!empty($igreja['igreja_logo'])):
                        ?>
                            <img src="<?= url($caminhoLogo) ?>" alt="Logo Local" class="logo-login">
                        <?php else: ?>
                            <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" class="logo-login">
                        <?php endif; ?>
                    </div>

                    <h5 class="fw-bold mb-0 text-uppercase"><?= htmlspecialchars($igreja['igreja_nome']) ?></h5>
                    <p class="small text-white-50 mb-0">Portal de Dízimos e Ofertas</p>
                </div>

                <div class="card-body p-4">

                    <?php if(isset($_GET['erro'])): ?>
                        <div class="alert alert-danger small py-2">
                            <i class="bi bi-exclamation-triangle-fill"></i> Credenciais inválidas ou usuários iguais.
                        </div>
                    <?php endif; ?>

                    <form action="<?= url('dizimoOferta/autenticar') ?>" method="POST">
                        <input type="hidden" name="igreja_id" value="<?= $igreja['igreja_id'] ?>">

                        <!-- 1º USUÁRIO -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold small text-uppercase m-0">1º Diácono / Presbítero</label>
                                <a id="toggle-user1" class="toggle-type text-primary"><i class="bi bi-envelope"></i> Usar E-mail</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i id="icon-user1" class="bi bi-card-text"></i></span>
                                <input type="text" id="user1" name="user1" class="form-control form-control-lg border-start-0" placeholder="000.000.000-00" required>
                            </div>
                            <input type="password" name="pass1" class="form-control form-control-lg mt-2" placeholder="Senha" required>
                        </div>

                        <!-- 2º USUÁRIO -->
                        <div class="mb-4 border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold small text-uppercase text-primary m-0">2º Diácono (Testemunha)</label>
                                <a id="toggle-user2" class="toggle-type text-primary"><i class="bi bi-envelope"></i> Usar E-mail</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i id="icon-user2" class="bi bi-card-text"></i></span>
                                <input type="text" id="user2" name="user2" class="form-control form-control-lg border-start-0" placeholder="000.000.000-00" required>
                            </div>
                            <input type="password" name="pass2" class="form-control form-control-lg mt-2" placeholder="Senha" required>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Conferência
                            </button>
                            <a href="<?= url('canais') ?>" class="btn btn-link btn-sm text-decoration-none text-muted">Voltar aos Canais</a>
                        </div>
                    </form>

                </div>
            </div>
            <div class="text-center mt-4">
                <p class="text-muted small">EKKLESIA &bull; Gestão Ministerial</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    function aplicarMascaraCPF(value) {
        return value
            .replace(/\D/g, "")
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    }

    function configurarCampoMascara(inputId, toggleId, iconId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        const icon = document.getElementById(iconId);

        // Estado inicial: true = CPF, false = E-mail
        let modoCPF = true;

        // Monitora a digitação caso esteja em modo CPF
        input.addEventListener("input", function(e) {
            if (modoCPF) {
                let value = e.target.value.replace(/\D/g, "");
                if (value.length > 11) value = value.slice(0, 11);
                e.target.value = aplicarMascaraCPF(value);
            }
        });

        // Alternador do tipo de entrada
        toggle.addEventListener("click", function() {
            modoCPF = !modoCPF;
            input.value = ""; // Limpa para evitar conflito de formatos

            if (modoCPF) {
                input.placeholder = "000.000.000-00";
                input.type = "text";
                toggle.innerHTML = '<i class="bi bi-envelope"></i> Usar E-mail';
                icon.className = "bi bi-card-text";
            } else {
                input.placeholder = "exemplo@email.com";
                input.type = "email";
                toggle.innerHTML = '<i class="bi bi-vcard"></i> Usar CPF';
                icon.className = "bi bi-envelope";
            }
            input.focus();
        });
    }

    // Inicializa o comportamento nos dois blocos de login de forma independente
    configurarCampoMascara("user1", "toggle-user1", "icon-user1");
    configurarCampoMascara("user2", "toggle-user2", "icon-user2");
});
</script>
</body>
</html>
