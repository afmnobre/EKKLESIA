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
        .step-block { transition: all 0.3s ease; }
        .step-block.disabled-step { opacity: 0.4; pointer-events: none; }
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

                    <!-- MENSAGEM DE ALERTA -->
                    <div id="alert-message" class="alert alert-danger small py-2 d-none">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <span id="alert-text"></span>
                    </div>

                    <input type="hidden" id="igreja_id" value="<?= $igreja['igreja_id'] ?>">

                    <!-- ETAPA 1: 1º USUÁRIO -->
                    <div id="step-1" class="step-block mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold small text-uppercase m-0">1º Diácono / Presbítero</label>
                            <a id="toggle-user1" class="toggle-type text-primary"><i class="bi bi-envelope"></i> Usar E-mail</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i id="icon-user1" class="bi bi-card-text"></i></span>
                            <input type="text" id="user1" class="form-control form-control-lg border-start-0" placeholder="000.000.000-00" required>
                        </div>
                        <input type="password" id="pass1" class="form-control form-control-lg mt-2" placeholder="Senha" required>

                        <div id="status-user1" class="text-success small fw-bold mt-2 d-none">
                            <i class="bi bi-check-circle-fill me-1"></i> Autenticado: <span id="nome-user1"></span>
                        </div>

                        <button type="button" id="btn-auth-1" class="btn btn-primary btn-lg shadow w-100 mt-3">
                            <i class="bi bi-shield-lock me-2"></i> Autenticar 1º Diácono
                        </button>
                    </div>

                    <!-- ETAPA 2: 2º USUÁRIO -->
                    <div id="step-2" class="step-block mb-4 border-top pt-4 disabled-step">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold small text-uppercase text-primary m-0">2º Diácono (Testemunha)</label>
                            <a id="toggle-user2" class="toggle-type text-primary"><i class="bi bi-envelope"></i> Usar E-mail</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i id="icon-user2" class="bi bi-card-text"></i></span>
                            <input type="text" id="user2" class="form-control form-control-lg border-start-0" placeholder="000.000.000-00" disabled required>
                        </div>
                        <input type="password" id="pass2" class="form-control form-control-lg mt-2" placeholder="Senha" disabled required>

                        <button type="button" id="btn-auth-2" class="btn btn-primary btn-lg shadow w-100 mt-3" disabled>
                            <i class="bi bi-box-arrow-in-right me-2"></i> Confirmar e Entrar
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?= url('canais') ?>" class="btn btn-link btn-sm text-decoration-none text-muted">Voltar aos Canais</a>
                    </div>

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

        let modoCPF = true;

        input.addEventListener("input", function(e) {
            if (modoCPF) {
                let value = e.target.value.replace(/\D/g, "");
                if (value.length > 11) value = value.slice(0, 11);
                e.target.value = aplicarMascaraCPF(value);
            }
        });

        toggle.addEventListener("click", function() {
            modoCPF = !modoCPF;
            input.value = "";

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

    configurarCampoMascara("user1", "toggle-user1", "icon-user1");
    configurarCampoMascara("user2", "toggle-user2", "icon-user2");

    // LÓGICA DE AUTENTICAÇÃO VIA AJAX
    const alertBox = document.getElementById("alert-message");
    const alertText = document.getElementById("alert-text");

    function showAlert(msg) {
        alertText.textContent = msg;
        alertBox.classList.remove("d-none");
    }

    function hideAlert() {
        alertBox.classList.add("d-none");
        alertText.textContent = "";
    }

    // AUTENTICAR 1º DIÁCONO
    document.getElementById("btn-auth-1").addEventListener("click", function() {
        hideAlert();
        const igrejaId = document.getElementById("igreja_id").value;
        const user1 = document.getElementById("user1").value;
        const pass1 = document.getElementById("pass1").value;

        if (!user1 || !pass1) {
            showAlert("Preencha o usuário e senha do 1º Diácono.");
            return;
        }

        const formData = new FormData();
        formData.append("igreja_id", igrejaId);
        formData.append("user1", user1);
        formData.append("pass1", pass1);

        fetch("<?= url('dizimoOferta/autenticarPrimeiro') ?>", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Bloqueia Etapa 1
                document.getElementById("user1").disabled = true;
                document.getElementById("pass1").disabled = true;
                document.getElementById("btn-auth-1").disabled = true;
                document.getElementById("btn-auth-1").classList.add("d-none");

                document.getElementById("nome-user1").textContent = data.nome;
                document.getElementById("status-user1").classList.remove("d-none");

                // Libera Etapa 2
                const step2 = document.getElementById("step-2");
                step2.classList.remove("disabled-step");
                document.getElementById("user2").disabled = false;
                document.getElementById("pass2").disabled = false;
                document.getElementById("btn-auth-2").disabled = false;
                document.getElementById("user2").focus();
            } else {
                showAlert(data.message);
            }
        })
        .catch(() => showAlert("Erro ao processar a requisição. Tente novamente."));
    });

    // AUTENTICAR 2º DIÁCONO
    document.getElementById("btn-auth-2").addEventListener("click", function() {
        hideAlert();
        const igrejaId = document.getElementById("igreja_id").value;
        const user2 = document.getElementById("user2").value;
        const pass2 = document.getElementById("pass2").value;

        if (!user2 || !pass2) {
            showAlert("Preencha o usuário e senha do 2º Diácono.");
            return;
        }

        const formData = new FormData();
        formData.append("igreja_id", igrejaId);
        formData.append("user2", user2);
        formData.append("pass2", pass2);

        fetch("<?= url('dizimoOferta/autenticarSegundo') ?>", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                showAlert(data.message);
            }
        })
        .catch(() => showAlert("Erro ao processar a requisição. Tente novamente."));
    });
});
</script>
</body>
</html>
