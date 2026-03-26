<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="text-center mb-4">
        <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB Logo" style="max-width: 200px;" class="mb-3">
        <h2 class="fw-bold text-dark text-uppercase">Ficha de Membro</h2>
        <div class="badge bg-primary px-3 py-2 shadow-sm">
            <?= $igreja['igreja_nome'] ?>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <form action="<?= url('PortalMembro/salvar') ?>" id="formCadastro" method="POST" enctype="multipart/form-data" class="card shadow border-0 p-4" style="border-top: 5px solid #004a8d !important;">
                <input type="hidden" name="igreja_id" value="<?= $igreja['igreja_id'] ?>">

                <div class="d-flex align-items-center mb-3">
                    <img src="<?= url('assets/img/logo_ipb.png') ?>" style="width: 25px;" class="me-2">
                    <h6 class="mb-0 fw-bold text-primary text-uppercase">Onde você mora?</h6>
                </div>
                <hr class="mt-0 mb-3 opacity-10">

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-primary">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control form-control-lg border-primary shadow-sm" placeholder="00000-000" required>
                    </div>
                    <div class="col-6 d-flex align-items-end">
                        <small class="text-muted pb-2" id="status-cep"></small>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-bold">Rua / Logradouro</label>
                    <input type="text" name="rua" id="rua" class="form-control shadow-sm" required>
                </div>

                <div class="row mb-2">
                    <div class="col-4">
                        <label class="form-label small fw-bold">Número</label>
                        <input type="text" name="numero" class="form-control shadow-sm" required>
                    </div>
                    <div class="col-8">
                        <label class="form-label small fw-bold">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control shadow-sm" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-8">
                        <label class="form-label small fw-bold">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control shadow-sm" required readonly>
                    </div>
                    <div class="col-4">
                        <label class="form-label small fw-bold">UF</label>
                        <input type="text" name="estado" id="estado" class="form-control shadow-sm" maxlength="2" readonly required>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3 mt-4">
                    <img src="<?= url('assets/img/logo_ipb.png') ?>" style="width: 25px;" class="me-2">
                    <h6 class="mb-0 fw-bold text-primary text-uppercase">Seus Dados</h6>
                </div>
                <hr class="mt-0 mb-3 opacity-10">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Nome Completo</label>
                    <input type="text" name="nome" class="form-control text-uppercase shadow-sm" placeholder="DIGITE SEU NOME COMPLETO" required>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Nascimento</label>
                        <input type="date" name="data_nasc" class="form-control shadow-sm" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Batismo</label>
                        <input type="date" name="data_batismo" class="form-control shadow-sm">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Sexo</label>
                    <select name="sexo" class="form-select shadow-sm" required>
                        <option value="" selected disabled>Selecione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control shadow-sm" placeholder="exemplo@email.com" required>
                    </div>
                    <div class="form-text text-muted" style="font-size: 0.7rem;">Este será seu login de acesso futuramente.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Telefone / WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-success"><i class="fa fa-whatsapp"></i></span>
                        <input type="text" name="telefone" id="telefone" class="form-control shadow-sm" placeholder="(00) 00000-0000" required>
                    </div>
                </div>

                <div class="mb-3 bg-light p-3 rounded border border-danger shadow-sm">
                    <label class="form-label small text-danger fw-bold"><i class="fa fa-lock me-1"></i> Criar Senha</label>
                    <input type="password" name="senha" class="form-control shadow-sm" required>
                </div>

                <div class="mb-4 mt-4 text-center p-3 border rounded bg-light shadow-sm">
                    <label class="form-label d-block fw-bold text-primary">FOTO PARA CARTEIRINHA</label>
                    <input type="file" name="foto" class="form-control mb-2" accept="image/*" capture="user">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 shadow fw-bold py-3 text-uppercase">
                    <i class="fa fa-check-circle me-2"></i> Enviar Cadastro
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.2.0/vanilla-masker.min.js"></script>
<script>
    // Aplicando Máscaras
    VMasker(document.getElementById("cep")).maskPattern("99999-999");
    VMasker(document.getElementById("telefone")).maskPattern("(99) 99999-9999");

    // Lógica de Busca de CEP
    document.getElementById('cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        const status = document.getElementById('status-cep');

        if (cep.length === 8) {
            status.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Buscando...';

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('rua').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;
                        status.innerHTML = '<span class="text-success"><i class="fa fa-check"></i></span>';
                        document.getElementsByName('numero')[0].focus();
                    } else {
                        status.innerHTML = '<span class="text-danger small">CEP não encontrado.</span>';
                    }
                })
                .catch(() => {
                    status.innerHTML = '<span class="text-danger small">Erro ao buscar CEP.</span>';
                });
        }
    });
</script>

<style>
    body { background-color: #f8f9fa; }
    .text-primary { color: #004a8d !important; }
    .btn-primary { background-color: #004a8d; border: none; }
    .form-control-lg { font-size: 1.1rem; }
    input[readonly] { background-color: #e9ecef !important; cursor: not-allowed; }
    .form-select { height: 45px; } /* Ajuste de altura para combinar com os inputs */
</style>
