<div class="container-fluid py-4">
    <h4 class="mb-4 text-primary fw-bold">
        <i class="bi bi-collection-fill me-2"></i>Gestão de Empréstimos
    </h4>

    <?php if (!empty($emprestimos_agrupados)): ?>
        <?php foreach ($emprestimos_agrupados as $grupo):
            $dataSaida = date('d/m/Y', strtotime($grupo['data_saida']));
            $dataDevol = date('d/m/Y', strtotime($grupo['data_prevista']));

            // Geramos o JSON puro e usamos base64 para garantir que NENHUM caractere quebre o HTML
			$dadosJSON = json_encode([
				'membro_nome' => $grupo['membro_nome'],
				'membro_registro_interno' => $grupo['membro_registro_interno'], // <--- ADICIONAR ESTA LINHA
				'data_emprestimo' => $dataSaida,
				'data_devolucao' => $dataDevol,
				'livros' => $grupo['livros']
			]);
            $base64Recibo = base64_encode($dadosJSON);
        ?>

		<div class="card shadow-sm border-0 mb-4 border-start border-4 border-primary">
			<div class="card-header bg-light py-3 border-bottom">
				<div class="row align-items-center">
					<div class="col-auto">
						<?php
							$sessionIgrejaId = $_SESSION['usuario_igreja_id'];
							$fotoCaminho = (!empty($grupo['membro_foto']))
								? url("assets/uploads/{$sessionIgrejaId}/membros/{$grupo['membro_registro_interno']}/{$grupo['membro_foto']}")
								: url('assets/img/user-default.png');
						?>
						<img src="<?= $fotoCaminho ?>" class="rounded shadow-sm border bg-white"
							 style="width: 70px; height: 70px; object-fit: cover;">
					</div>

					<div class="col-md-4">
						<small class="text-muted d-block small fw-bold">LEITOR</small>
						<div class="d-flex align-items-center gap-2">
							<span class="h6 mb-0 text-uppercase fw-bold text-dark"><?= htmlspecialchars($grupo['membro_nome']) ?></span>

							<?php
								$telefoneLimpo = preg_replace('/\D/', '', $grupo['membro_telefone'] ?? '');
								$nomeIgreja = $igreja['igreja_nome'] ?? 'Igreja';
								$dataDev = date('d/m/Y', strtotime($grupo['data_prevista']));

								$livrosZap = [];
								foreach($grupo['livros'] as $l) {
									$livrosZap[] = "#" . $l['livro_id'] . " - " . mb_strtoupper($l['titulo'], 'UTF-8');
								}
								$jsonLivros = json_encode($livrosZap);
							?>

							<button type="button"
								onclick='enviarZap("<?= $telefoneLimpo ?>", "<?= htmlspecialchars($grupo['membro_nome']) ?>", "<?= htmlspecialchars($nomeIgreja) ?>", "<?= $dataDev ?>", <?= htmlspecialchars($jsonLivros) ?>)'
								class="btn btn-sm btn-success d-inline-flex align-items-center px-2 py-1"
								style="font-size: 11px; border-radius: 20px; font-weight: bold;">
								<i class="bi bi-whatsapp me-1"></i> Notificar
							</button>
						</div>
					</div>

					<div class="col-md-2">
						<small class="text-muted d-block small fw-bold text-uppercase">Data Saída</small>
						<span class="text-dark" style="font-size: 14px;"><?= date('d/m/Y', strtotime($grupo['data_saida'])) ?></span>
					</div>

					<div class="col-md-2">
						<small class="text-muted d-block small fw-bold text-uppercase">Devolução em</small>
						<span class="fw-bold text-danger" style="font-size: 14px;"><?= date('d/m/Y', strtotime($grupo['data_prevista'])) ?></span>
					</div>

					<div class="col text-end ms-auto">
						<small class="text-muted d-block small fw-bold text-uppercase">Qtd Livros</small>
						<span class="badge bg-dark rounded-pill" style="font-size: 14px; padding: 8px 15px;">
							<?= count($grupo['livros']) ?>
							<i class="bi bi-bookshelf ms-1"></i>
						</span>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
					<thead class="bg-white">
						<tr style="font-size: 0.70rem; text-transform: uppercase; color: #6c757d;">
							<th class="ps-3" style="width: 12%;">ID LIVRO</th>
							<th style="width: 48%;">NOME DO LIVRO</th>
							<th style="width: 25%;">CATEGORIA</th>
							<th class="text-end pe-3" style="width: 15%;">AÇÃO</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($grupo['livros'] as $livro): ?>
						<tr class="bg-white">
							<td class="ps-3 text-muted small">#<?= $livro['livro_id'] ?></td>
							<td class="fw-bold text-uppercase text-truncate" title="<?= htmlspecialchars($livro['titulo']) ?>" style="font-size: 0.85rem;">
								<?= htmlspecialchars($livro['titulo']) ?>
							</td>
							<td>
								<span class="badge bg-light text-dark border fw-normal">
									<i class="bi bi-tag me-1 text-muted"></i>
									<?= htmlspecialchars($livro['categoria'] ?? 'Geral') ?>
								</span>
							</td>
							<td class="text-end pe-3">
								<button type="button"
										class="btn btn-outline-success btn-sm px-3"
										style="font-size: 11px; font-weight: 600;"
										onclick="confirmarDevolucao('<?= $livro['emprestimo_id'] ?>', '<?= addslashes($livro['titulo']) ?>', 'parcial')">
									<i class="bi bi-check2"></i> PARCIAL
								</button>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="card-footer bg-light d-flex justify-content-end gap-2 py-3 border-top">
				<button type="button" class="btn btn-outline-dark btn-sm fw-bold btn-gerar-recibo" data-recibo="<?= $base64Recibo ?>">
					<i class="bi bi-printer me-1"></i> RECIBO
				</button>

				<?php $ids = implode(',', array_column($grupo['livros'], 'emprestimo_id')); ?>
				<button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow-sm"
						onclick="confirmarDevolucao('<?= $ids ?>', '<?= addslashes($grupo['membro_nome']) ?>', 'total')">
					<i class="bi bi-check-all me-1"></i> DEVOLUÇÃO TOTAL
				</button>
			</div>
		</div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center py-5">Nenhum empréstimo ativo.</div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalVisualizarRecibo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0">
                <div id="papelRecibo" style="background: #fff; color: #000; width: 100%;">
                    <div class="dashed-container" style="border: 2px dashed #000; padding: 10px; margin: 5px;">

                        <div class="text-center mb-3">
                            <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                                <img src="<?= url("assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}") ?>" style="max-height: 45px; width: auto;" alt="Logo Local">
                                <img src="<?= url('assets/img/logo_ipb.png') ?>" style="max-height: 45px; width: auto;" alt="IPB">
                            </div>
                            <h6 class="fw-bold mb-0 text-uppercase" style="font-size: 14px;"><?= mb_strtoupper($igreja['igreja_nome'] ?? 'BIBLIOTECA') ?></h6>
                            <small style="font-size: 10px; letter-spacing: 1px;">COMPROVANTE DE MOVIMENTAÇÃO</small>
                        </div>

                        <div class="mb-2" style="font-size: 11px; line-height: 1.2;">
                            <div><strong>LEITOR:</strong> <span id="reciboMembro" class="text-uppercase text-break"></span> (<span id="reciboRegistro"></span>)</div>
                            <div><strong>DATA RETIRADA:</strong> <span id="reciboData"></span></div>
                            <div><strong>PREVISÃO DEV.:</strong> <span id="reciboDevolucao" class="fw-bold"></span></div>
                        </div>

                        <div class="mb-3">
							<table class="table table-sm table-bordered border-dark mb-1" style="font-size: 10px; border-color: #000 !important;">
								<thead class="table-light">
									<tr class="text-center border-dark">
										<th id="thLivro" style="border-color: #000 !important;">LIVRO (ID/TÍTULO)</th>

										<th class="th-extra" style="width: 45px; border-color: #000 !important;">DEV.</th>
										<th class="th-extra" style="width: 60px; border-color: #000 !important;">VISTO</th>
									</tr>
								</thead>
								<tbody id="reciboItensTabela">
									</tbody>
							</table>
                            <div id="legendaDevolucaoParcial" style="font-size: 8px; text-align: center;">[ ] Marcar p/ Devolução Parcial</div>
                        </div>

                        <div class="mt-4 pt-2 border-top border-dark text-center">
                            <span class="fw-bold" style="font-size: 11px;">CONTROLE DE DEVOLUÇÃO TOTAL</span>
                            <div class="mt-4">
                                <div style="border-top: 1px solid #000; width: 90%; margin: 0 auto;"></div>
                                <small style="font-size: 9px;">DATA E ASSINATURA BIBLIOTECÁRIO</small>
                            </div>
                        </div>

                        <div class="text-center mt-3" style="font-size: 10px; font-family: sans-serif;">
                            ✨ Que a leitura edifique sua vida! ✨
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light d-flex gap-2">
                    <button class="btn btn-secondary w-100 fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                    <button class="btn btn-primary w-100 fw-bold" onclick="imprimirPapelRecibo()">
                        <i class="bi bi-printer-fill me-2"></i>IMPRIMIR (80mm)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmarDevolucao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="devolucao-modal-titulo">Confirmar Devolução</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="mb-1" id="devolucao-modal-label">Você está confirmando a devolução de:</p>
                <h5 id="devolucao-modal-item" class="fw-bold text-success mb-3">---</h5>
                <p class="text-muted small mb-0">Esta ação atualizará o status no acervo imediatamente.</p>
            </div>
            <div class="modal-footer bg-light border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <a id="btn-confirmar-devolucao-link" href="#" class="btn btn-success px-4 fw-bold">Confirmar Agora</a>
            </div>
        </div>
    </div>
</div>

<script>
// Evento para capturar os cliques nos botões de recibo
document.querySelectorAll('.btn-gerar-recibo').forEach(button => {
    button.addEventListener('click', function() {
        const base64 = this.getAttribute('data-recibo');
        if (!base64) return;

        const dados = JSON.parse(atob(base64));

        // Preenchimento de dados básicos
        document.getElementById('reciboMembro').innerText = dados.membro_nome;
        document.getElementById('reciboRegistro').innerText = dados.membro_registro_interno || '---';
        document.getElementById('reciboData').innerText = dados.data_emprestimo;
        document.getElementById('reciboDevolucao').innerText = dados.data_devolucao;

        const tabela = document.getElementById('reciboItensTabela');
        const legenda = document.getElementById('legendaDevolucaoParcial');
        const cabecalhoLivro = document.getElementById('thLivro');
        const thExtras = document.querySelectorAll('.th-extra');

        tabela.innerHTML = '';
        const totalLivros = dados.livros.length;
        const isMultiplo = totalLivros > 1;

        // Ajuste Visual das Colunas (Verificação de segurança para não dar erro de 'null')
        if (cabecalhoLivro) {
            if (!isMultiplo) {
                cabecalhoLivro.setAttribute('colspan', '3');
                thExtras.forEach(el => el.style.display = 'none');
                if(legenda) legenda.style.display = 'none';
            } else {
                cabecalhoLivro.removeAttribute('colspan');
                thExtras.forEach(el => el.style.display = '');
                if(legenda) legenda.style.display = 'block';
            }
        }

        // Montagem das linhas da tabela
        dados.livros.forEach(l => {
            if (isMultiplo) {
                tabela.innerHTML += `
                    <tr class="border-dark">
                        <td class="text-uppercase" style="border-color: #000 !important; padding: 4px 2px;">
                            #<b>${l.livro_id}</b> - ${l.titulo}
                        </td>
                        <td class="text-center" style="border-color: #000 !important; font-size: 12px;">[ ]</td>
                        <td style="border-color: #000 !important;"></td>
                    </tr>`;
            } else {
                tabela.innerHTML += `
                    <tr class="border-dark">
                        <td colspan="3" class="text-uppercase" style="border-color: #000 !important; padding: 8px 4px;">
                            <i class="bi bi-book"></i> #<b>${l.livro_id}</b> - ${l.titulo}
                        </td>
                    </tr>`;
            }
        });

        // Abre o modal
        const meuModal = new bootstrap.Modal(document.getElementById('modalVisualizarRecibo'));
        meuModal.show();
    });
});

function imprimirPapelRecibo() {
    const conteudo = document.getElementById('papelRecibo').innerHTML;
    const tela = window.open('', '', 'width=450,height=800');

    tela.document.write(`
        <html>
            <head>
                <title>Recibo Térmico</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
                <style>
                    @page { margin: 0; size: 80mm auto; }
                    body {
                        font-family: 'Courier New', Courier, monospace;
                        margin: 0; padding: 0; width: 80mm;
                        background: #fff;
                    }
                    #papelRecibo { width: 80mm; margin: 0; padding: 0; }
                    .dashed-container { border: 2px dashed #000; padding: 10px; margin: 5px; }
                    table { border-collapse: collapse !important; width: 100%; }
                    th, td { border: 1px solid #000 !important; color: #000 !important; vertical-align: middle; }
                    img { max-height: 50px; filter: grayscale(100%); }
                    .text-uppercase { text-transform: uppercase; }
                    .fw-bold { font-weight: bold; }
                    .text-center { text-align: center; }
                </style>
            </head>
            <body onload="setTimeout(() => { window.print(); window.close(); }, 800)">
                <div id="papelRecibo">${conteudo}</div>
            </body>
        </html>
    `);
    tela.document.close();
}

function enviarZap(telefone, nomeMembro, nomeIgreja, dataDevolucao, livros) {
    // Montamos a mensagem no JS para evitar erro de encoding (UTF-8 nativo)
    let saudacao = `Olá, *${nomeMembro}*! 📚\n`;
    saudacao += `Seguem os detalhes dos livros retirados na Biblioteca da *${nomeIgreja}*:\n\n`;

    let lista = "";
    livros.forEach(titulo => {
        lista += `📖 *${titulo}*\n`;
    });

    let prazo = `\n📅 *Data para Devolução: ${dataDevolucao}*`;
    let rodape = `\n\n_Por favor, apresente o recibo impresso no momento da devolução. Deus abençoe!_`;

    let mensagemFinal = saudacao + lista + prazo + rodape;

    // encodeURIComponent é o método padrão ouro para URLs no navegador
    let link = `https://api.whatsapp.com/send?phone=55${telefone}&text=${encodeURIComponent(mensagemFinal)}`;

    window.open(link, '_blank');
}

function confirmarDevolucao(ids, nome, tipo) {
    const tituloModal = document.getElementById('devolucao-modal-titulo');
    const labelModal = document.getElementById('devolucao-modal-label');
    const itemModal = document.getElementById('devolucao-modal-item');
    const btnLink = document.getElementById('btn-confirmar-devolucao-link');

    // Configura os textos baseados no tipo
    if (tipo === 'total') {
        tituloModal.innerHTML = '<i class="bi bi-check-all me-2"></i>Devolução Total';
        labelModal.textContent = 'Você está confirmando a devolução de todos os livros de:';
        itemModal.textContent = nome; // Nome do Leitor
        btnLink.setAttribute('href', "<?= url('biblioteca/processarDevolucaoTotal/') ?>" + ids);
    } else {
        tituloModal.innerHTML = '<i class="bi bi-check2 me-2"></i>Devolução Parcial';
        labelModal.textContent = 'Você está confirmando a devolução do livro:';
        itemModal.textContent = nome; // Título do Livro
        btnLink.setAttribute('href', "<?= url('biblioteca/processarDevolucao/') ?>" + ids);
    }

    // Abre o modal
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmarDevolucao'));
    modal.show();
}
</script>
