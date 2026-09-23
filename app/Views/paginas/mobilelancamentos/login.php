<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - Conferência de Dízimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 15px 0;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }
        .login-header {
            background: #212529;
            color: white;
            padding: 1.25rem 1rem;
            text-align: center;
        }
        .btn-primary {
            background: #212529;
            border: none;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            font-weight: 600;
        }
        .btn-primary:hover, .btn-primary:active {
            background: #343a40;
        }
        .form-control-lg {
            border-radius: 10px;
            font-size: 1rem;
            padding: 0.75rem 0.9rem;
        }
        .logo-login {
            height: 42px;
            object-fit: contain;
        }
        .toggle-type {
            font-size: 0.8rem;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            padding: 4px 0;
        }
        .step-block {
            transition: all 0.3s ease;
        }
        .step-block.disabled-step {
            opacity: 0.35;
            pointer-events: none;
            filter: grayscale(1);
        }
    </style>
</head>
<body>

<div class="container px-3">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-5 col-xl-4">
            <div class="card login-card shadow-lg">

                <!-- CABEÇALHO COMPACTO PARA TELA MOBILE -->
                <div class="login-header">
                    <div class="d-flex justify-content-center align-items-center gap-3 mb-2">
                        <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" class="logo-login">

                        <?php
                            $caminhoLogo = "assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}";
                            if(!empty($igreja['igreja_logo'])):
                        ?>
                            <img src="<?= url($caminhoLogo) ?>" alt="Logo Local" class="logo-login">
                        <?php endif; ?>
                    </div>

                    <h6 class="fw-bold mb-0 text-uppercase text-truncate px-2"><?= htmlspecialchars($igreja['igreja_nome']) ?></h6>
                    <p class="small text-white-50 mb-0" style="font-size: 0.8rem;">Conferência de Dízimos e Ofertas</p>
                </div>

                <div class="card-body p-3 p-sm-4">

                    <!-- MENSAGEM DE ALERTA -->
                    <div id="alert-message" class="alert alert-danger small py-2 px-3 mb-3 d-none">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <span id="alert-text"></span>
                    </div>

                    <input type="hidden" id="igreja_id" value="<?= $igreja['igreja_id'] ?>">

                    <!-- ETAPA 1: 1º USUÁRIO -->
                    <div id="step-1" class="step-block mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-uppercase m-0">1º Diácono / Presbítero</label>
                            <a id="toggle-user1" class="toggle-type text-primary"><i class="bi bi-envelope"></i> Usar E-mail</a>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 px-2.5"><i id="icon-user1" class="bi bi-card-text"></i></span>
                            <input type="text" id="user1" class="form-control form-control-lg border-start-0" placeholder="000.000.000-00" inputmode="numeric" autocomplete="off" required>
                        </div>

                        <input type="password" id="pass1" class="form-control form-control-lg mt-2" placeholder="Senha" required>

                        <div id="status-user1" class="alert alert-success py-2 px-3 small fw-bold mt-2 mb-0 d-none">
                            <i class="bi bi-check-circle-fill me-1"></i> Autenticado: <span id="nome-user1"></span>
                        </div>

                        <button type="button" id="btn-auth-1" class="btn btn-primary shadow-sm w-100 mt-3">
                            <i class="bi bi-shield-lock me-1"></i> Autenticar 1º Diácono
                        </button>
                    </div>

                    <!-- ETAPA 2: 2º USUÁRIO -->
                    <div id="step-2" class="step-block mb-2 border-top pt-3 disabled-step">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-uppercase text-primary m-0">2º Diácono (Testemunha)</label>
                            <a id="toggle-user2" class="toggle-type text-primary"><i class="bi bi-envelope"></i> Usar E-mail</a>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 px-2.5"><i id="icon-user2" class="bi bi-card-text"></i></span>
                            <input type="text" id="user2" class="form-control form-control-lg border-start-0" placeholder="000.000.000-00" inputmode="numeric" autocomplete="off" disabled required>
                        </div>

                        <input type="password" id="pass2" class="form-control form-control-lg mt-2" placeholder="Senha" disabled required>

                        <button type="button" id="btn-auth-2" class="btn btn-primary shadow-sm w-100 mt-3" disabled>
                            <i class="bi bi-box-arrow-in-right me-1"></i> Confirmar e Entrar
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?= url('canais') ?>" class="btn btn-link btn-sm text-decoration-none text-muted py-1">
                            <i class="bi bi-arrow-left me-1"></i> Voltar aos Canais
                        </a>
                    </div>

                </div>
            </div>

            <div class="text-center mt-3">
                <p class="text-muted small mb-0" style="font-size: 0.75rem;">EKKLESIA &bull; Gestão Ministerial</p>
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
                input.setAttribute("inputmode", "numeric");
                input.removeAttribute("autocapitalize");
                toggle.innerHTML = '<i class="bi bi-envelope"></i> Usar E-mail';
                icon.className = "bi bi-card-text";
            } else {
                input.placeholder = "exemplo@email.com";
                input.type = "email";
                input.setAttribute("inputmode", "email");
                input.setAttribute("autocapitalize", "none");
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
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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

        const btn1 = this;
        btn1.disabled = true;
        btn1.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verificando...';

        const formData = new FormData();
        formData.append("igreja_id", igrejaId);
        formData.append("user1", user1);
        formData.append("pass1", pass1);

        fetch("<?= url('mobileLancamento/autenticarPrimeiro') ?>", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Bloqueia Etapa 1
                document.getElementById("user1").disabled = true;
                document.getElementById("pass1").disabled = true;
                btn1.classList.add("d-none");

                document.getElementById("nome-user1").textContent = data.nome;
                document.getElementById("status-user1").classList.remove("d-none");

                // Libera Etapa 2 e foca no próximo campo
                const step2 = document.getElementById("step-2");
                step2.classList.remove("disabled-step");

                const user2Input = document.getElementById("user2");
                user2Input.disabled = false;
                document.getElementById("pass2").disabled = false;
                document.getElementById("btn-auth-2").disabled = false;

                setTimeout(() => {
                    user2Input.focus();
                    step2.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            } else {
                btn1.disabled = false;
                btn1.innerHTML = '<i class="bi bi-shield-lock me-1"></i> Autenticar 1º Diácono';
                showAlert(data.message);
            }
        })
        .catch(() => {
            btn1.disabled = false;
            btn1.innerHTML = '<i class="bi bi-shield-lock me-1"></i> Autenticar 1º Diácono';
            showAlert("Erro ao processar a requisição. Tente novamente.");
        });
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

        const btn2 = this;
        btn2.disabled = true;
        btn2.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Entrando...';

        const formData = new FormData();
        formData.append("igreja_id", igrejaId);
        formData.append("user2", user2);
        formData.append("pass2", pass2);

        fetch("<?= url('mobileLancamento/autenticarSegundo') ?>", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Linha 249: Redirecionamento com fallback para a view principal
                window.location.href = data.redirect || "<?= url('mobilelancamento') ?>";
            } else {
                btn2.disabled = false;
                btn2.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i> Confirmar e Entrar';
                showAlert(data.message);
            }
        })
        .catch(() => {
            btn2.disabled = false;
            btn2.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i> Confirmar e Entrar';
            showAlert("Erro ao processar a requisição. Tente novamente.");
        });
    });

    // Linhas 258 a 274: ATALHOS DA TECLA ENTER PARA SUBMISSÃO DOS FORMULÁRIOS
    ["user1", "pass1"].forEach(id => {
        document.getElementById(id).addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                const btn1 = document.getElementById("btn-auth-1");
                if (!btn1.disabled && !btn1.classList.contains("d-none")) {
                    btn1.click();
                }
            }
        });
    });

    ["user2", "pass2"].forEach(id => {
        document.getElementById(id).addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                const btn2 = document.getElementById("btn-auth-2");
                if (!btn2.disabled) {
                    btn2.click();
                }
            }
        });
    });
});
</script>
</body>
</html>
