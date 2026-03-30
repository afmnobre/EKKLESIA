<style>
    :root {
        --azul-ipb: #003366;
        --dourado-ipb: #b8860b;
    }
    /* Garante que o card não encoste nas bordas do celular */
    .login-container {
        padding: 15px;
    }
    /* Altura otimizada para toque (Touch Target) */
    .form-control-lg {
        height: 60px !important;
        font-size: 1.1rem !important;
    }
    .input-group-text {
        padding-left: 20px;
        padding-right: 15px;
        font-size: 1.2rem;
    }
    /* Estilo do botão de entrada */
    .btn-entrar {
        background-color: var(--azul-ipb);
        height: 65px;
        font-size: 1.2rem !important;
        border-radius: 12px;
        transition: transform 0.1s active;
    }
    .btn-entrar:active {
        transform: scale(0.98);
    }
</style>

<div class="container d-flex align-items-center justify-content-center login-container" style="min-height: 95vh;">
    <div class="card border-0 shadow-lg" style="max-width: 400px; width: 100%; border-radius: 20px; border-bottom: 6px solid var(--dourado-ipb);">
        <div class="card-body p-4 p-md-5 text-center">

            <div class="mb-5">
                <div class="mb-3">
                    <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Logo IPB" style="height: 90px; width: auto;">
                </div>
                <h3 class="fw-bold mt-2 text-dark">Chamada EBD</h3>
                <p class="text-muted">Acesse sua classe no celular</p>
            </div>

            <?php if(isset($_GET['erro'])): ?>
                <div class="alert alert-danger border-0 py-3 mb-4 shadow-sm" style="border-radius: 10px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Celular ou senha incorretos.
                </div>
            <?php endif; ?>

            <form action="<?= url('professor/autenticar') ?>" method="POST">
                <div class="mb-3 text-start">
                    <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Número do Celular</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                            <i class="bi bi-phone"></i>
                        </span>
                        <input type="tel" name="celular" id="inputCelular" class="form-control form-control-lg bg-light border-start-0"
                               placeholder="(00) 00000-0000" required autofocus maxlength="15"
                               style="border-radius: 0 12px 12px 0;">
                    </div>
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Senha da Classe</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="senha" class="form-control form-control-lg bg-light border-start-0"
                               placeholder="****" required
                               style="border-radius: 0 12px 12px 0;">
                    </div>
                </div>

                <button type="submit" class="btn btn-lg w-100 fw-bold shadow text-white mt-2 btn-entrar">
                    ENTRAR <i class="bi bi-box-arrow-in-right ms-2"></i>
                </button>
            </form>

            <div class="mt-4">
                <small class="text-muted italic">Igreja Presbiteriana do Brasil</small>
            </div>
        </div>
    </div>
</div>

<script>
    /* Script para Máscara de Celular Dinâmica */
    const handlePhone = (event) => {
        let input = event.target;
        input.value = phoneMask(input.value);
    }

    const phoneMask = (value) => {
        if (!value) return "";
        value = value.replace(/\D/g, ''); // Remove tudo que não é dígito
        value = value.replace(/(\d{2})(\d)/, "($1) $2"); // Coloca parênteses no DDD
        value = value.replace(/(\d{5})(\d)/, "$1-$2"); // Coloca o hífen (formato celular 9 dígitos)
        return value;
    }

    document.getElementById('inputCelular').addEventListener('keyup', handlePhone);
</script>
