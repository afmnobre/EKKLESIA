<?php

/**
 * Retorna o número da semana do mês (1 a 5) baseado no dia atual
 * @return int
 */
function getNumeroSemanaAtual() {
    // j = Dia do mês sem zeros iniciais (1 a 31)
    $diaDoMes = (int)date('j');

    // Divide por 7 e arredonda para cima
    // Ex: Dia 1 -> 1/7 = 0.14 -> ceil = 1
    // Ex: Dia 8 -> 8/7 = 1.14 -> ceil = 2
    return (int)ceil($diaDoMes / 7);
}

$semana = getNumeroSemanaAtual();

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletim Semanal - <?= $liturgia['igreja_liturgia_tema'] ?? 'Ekklesia' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Padronização Ekklesia - Largura Expandida */
        body { background-color: #f0f2f5; font-family: 'Segoe UI', system-ui, sans-serif; color: #1a1a1a; margin: 0; padding: 0; }

        /* Container quase na largura total com margem pequena */
        .print-container {
            max-width: 98%;
            margin: 10px auto;
            background: white;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Títulos de Coluna */
        .col-titulo {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #0d6efd;
            display: flex;
            align-items: center;
        }

        /* Estilização Liturgia Lateral */
        .item-liturgia { border-bottom: 1px solid #f1f1f1; padding: 6px 0; }
        .liturgia-flex { display: flex; align-items: center; gap: 8px; }
        .badge-tipo {
            min-width: 65px; font-size: 0.55rem; text-align: center;
            padding: 3px 5px; text-transform: uppercase; font-weight: 700;
        }

        /* Mensagem Central */
        .mensagem-texto {
            font-family: 'Georgia', serif;
            font-size: 1.05rem;
            line-height: 1.6;
            text-align: justify;
            color: #333;
        }

        /* Liderança e Fotos */
        .foto-lider { width: 38px; height: 38px; object-fit: cover; border-radius: 50%; border: 1px solid #dee2e6; }
        .placeholder-avatar {
            width: 38px; height: 38px; display: flex; align-items: center;
            justify-content: center; background: #e9ecef; border-radius: 50%;
            color: #adb5bd; font-size: 1rem;
        }
        .lider-cargo { font-size: 0.6rem; font-weight: 800; color: #6c757d; text-transform: uppercase; margin: 0; }
        .lider-nome { font-size: 0.8rem; font-weight: 600; margin: 0; line-height: 1.2; }

        /* Agenda e Eventos */
        .card-evento { border-left: 4px solid #0d6efd !important; background: #fff; }
        .small-x { font-size: 0.7rem; }
        .fw-black { font-weight: 900; }

        /* Controles Flutuantes Padronizados */
        .btn-float { position: fixed; bottom: 20px; right: 20px; display: flex; gap: 10px; z-index: 1000; }

        @media print {
            body { background: white !important; padding: 0 !important; }
            .print-container { margin: 0 !important; padding: 15px !important; box-shadow: none !important; max-width: 100% !important; border: none !important; }
            .no-print { display: none !important; }
            .col-lg-3 { width: 22% !important; float: left; }
            .col-lg-6 { width: 56% !important; float: left; }
            .row { display: flex !important; flex-wrap: nowrap !important; }
            .bg-light { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="btn-float no-print">
    <button onclick="window.history.back();" class="btn btn-dark shadow"><i class="bi bi-arrow-left"></i></button>
    <button onclick="window.print();" class="btn btn-primary shadow"><i class="bi bi-printer"></i> Imprimir Boletim</button>
</div>

<div class="print-container">
	<div class="row align-items-center border-bottom pb-2 mb-4">
		<div class="col-3 col-md-2 order-1">
			<img src="<?= url('assets/img/logo_ipb_completo.png') ?>" style="height: 70px; width:110px;">
		</div>

		<div class="col-6 col-md-5 text-center order-2">
			<h6 class="fw-bold mb-0 text-primary small text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">
				<?= htmlspecialchars($liturgia['igreja_nome']) ?>
			</h6>
			<h2 class="fw-black mb-0" style="letter-spacing: -1.5px; font-weight: 900; font-size: 1.6rem; line-height: 1;">
				BOLETIM SEMANAL
			</h2>
			<span class="badge bg-dark mt-0 px-2" style="font-size: 0.7rem;">
				<?= date('d/m/Y', strtotime($liturgia['igreja_liturgia_data'] ?? 'now')) ?>
			</span>
		</div>

		<div class="col-3 col-md-1 text-end order-3">
			<?php if(!empty($liturgia['igreja_logo'])): ?>
				<img src="<?= url("assets/uploads/{$liturgia['igreja_liturgia_igreja_id']}/logo/{$liturgia['igreja_logo']}") ?>"
					 style="height: 100px; max-width: 100px; object-fit: contain;">
			<?php endif; ?>
		</div>

		<div class="col-12 col-md-4 order-4 order-md-3 mt-2 mt-md-0">
			<div class="d-flex justify-content-center justify-content-md-end">
				<div class="border rounded bg-light p-1 shadow-sm" style="min-width: 190px; font-size: 0.68rem; line-height: 1.1;">
					<div class="text-center border-bottom mb-1 pb-0">
						<strong class="text-primary text-uppercase" style="font-size: 0.6rem;">Nossa Agenda</strong>
					</div>
					<ul class="list-unstyled mb-0">
						<?php
						if(!empty($programacao)):
							foreach($programacao as $prog):
								$ehSantaCeia = ($prog['programacao_recorrencia_mensal'] == $semana);
								if($prog['programacao_recorrencia_mensal'] == 0 || $ehSantaCeia):
									$cor = $ehSantaCeia ? 'text-danger' : 'text-primary';
									$texto = $ehSantaCeia ? '<strong class="text-danger">SANTA CEIA</strong>' : htmlspecialchars($prog['programacao_titulo']);
						?>
									<li class="mb-0 border-bottom border-white pb-0 text-start">
										<span class="<?= $cor ?> fw-bold" style="font-size: 0.65rem;">
											<?= substr($prog['programacao_dia_semana'], 0, 3) ?> <?= date('H:i', strtotime($prog['programacao_hora'])) ?>h
										</span> - <?= $texto ?>
									</li>
						<?php
								endif;
							endforeach;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-lg-3 border-end">
			<h5 class="col-titulo"><i class="bi bi-collection me-2"></i>Liturgia</h5>
			<?php if ($liturgia): ?>
				<div class="mb-3 bg-light p-2 rounded border-start border-primary border-4">
					<strong class="d-block text-dark text-uppercase mb-2" style="font-size: 0.75rem;"><?= $liturgia['igreja_liturgia_tema'] ?></strong>
					<div class="row g-2 mb-2">
						<div class="col-6">
							<div class="d-flex align-items-center bg-white border rounded shadow-sm overflow-hidden" style="height: 55px;">
								<div style="width: 45px; height: 55px; flex-shrink: 0;">
									<?php if (!empty($dirigente_foto)): ?>
										<img src="<?= url($dirigente_foto) ?>" style="width: 100%; height: 100%; object-fit: cover;">
									<?php else: ?>
										<div class="bg-light d-flex align-items-center justify-content-center text-muted h-100"><i class="bi bi-person"></i></div>
									<?php endif; ?>
								</div>
								<div class="p-1 overflow-hidden">
									<small class="text-primary fw-bold d-block" style="font-size: 0.55rem;">DIRIGENTE</small>
									<small class="fw-bold text-dark d-block text-truncate" style="font-size: 0.7rem;"><?= $dirigente_nome ?></small>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="d-flex align-items-center bg-white border rounded shadow-sm overflow-hidden" style="height: 55px;">
								<div style="width: 45px; height: 55px; flex-shrink: 0;">
									<?php if (!empty($pregador_foto)): ?>
										<img src="<?= url($pregador_foto) ?>" style="width: 100%; height: 100%; object-fit: cover;">
									<?php else: ?>
										<div class="bg-light d-flex align-items-center justify-content-center text-muted h-100"><i class="bi bi-person-fill"></i></div>
									<?php endif; ?>
								</div>
								<div class="p-1 overflow-hidden">
									<small class="text-primary fw-bold d-block" style="font-size: 0.55rem;">PREGADOR</small>
									<small class="fw-bold text-dark d-block text-truncate" style="font-size: 0.7rem;"><?= $pregador_nome ?></small>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="mt-2">
					<?php if (!empty($liturgia['itens'])): foreach ($liturgia['itens'] as $index => $item):
						// Garantindo que as variáveis existam para cada item do loop
						$tipo        = $item['tipo'] ?? 'Item';
						$descricao   = $item['desc'] ?? '';
						$referencia  = $item['ref'] ?? '';
						$conteudo    = $item['conteudo'] ?? '';
						$tipoLower   = strtolower($tipo);
						$id_item     = $item['liturgia_item_id'] ?? $index;
					?>
						<div class="item-liturgia mb-2">
							<div class="liturgia-flex">
								<span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle badge-tipo">
									<?= strtoupper($tipo) ?>
								</span>
								<div class="fw-semibold small text-dark flex-grow-1" style="font-size: 0.85rem;">
									<?= htmlspecialchars($descricao) ?>
								</div>
							</div>

							<?php if(!empty($referencia)): ?>
								<div class="text-primary fw-bold mt-1" style="margin-left: 73px; font-size: 0.75rem;">
									<?= htmlspecialchars($referencia) ?>
								</div>
							<?php endif; ?>

							<?php if(($tipoLower == 'leitura' || $tipoLower == 'mensagem') && !empty($conteudo)):
								$id_collapse = "coll_" . $id_item;
							?>
								<div style="margin-left: 73px;" class="mt-1">
									<div class="text-muted small" style="font-size: 0.75rem; font-style: italic;">
										<?= mb_strimwidth(strip_tags($conteudo), 0, 85, "...") ?>
										<button class="btn btn-link p-0 text-primary fw-bold no-print" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $id_collapse ?>" style="font-size: 0.7rem; text-decoration: none;">
											[Ler texto]
										</button>
									</div>
									<div class="collapse mt-2" id="<?= $id_collapse ?>">
										<div class="card card-body p-3 bg-light border-0 shadow-sm" style="font-size: 0.85rem; white-space: pre-line; font-family: 'Georgia', serif; line-height: 1.5; color: #444;">
											<?= $conteudo ?>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<?php if($tipoLower == 'hino' && !empty($item['hino_letra'])):
								$id_hino = "hino_" . $id_item;
							?>
								<div style="margin-left: 73px;" class="mt-1">
									<div class="text-muted small" style="font-size: 0.75rem; font-style: italic;">
										<strong><?= htmlspecialchars($item['hino_titulo'] ?? 'Hino') ?></strong>
										<button class="btn btn-link p-0 text-success fw-bold no-print" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $id_hino ?>" style="font-size: 0.7rem; text-decoration: none;">
											[Ver letra do hino]
										</button>
									</div>
									<div class="collapse mt-2" id="<?= $id_hino ?>">
										<div class="card card-body p-3 bg-white border shadow-sm" style="font-size: 0.85rem; white-space: pre-wrap; font-family: 'Verdana', sans-serif; line-height: 1.5; color: #333; border-left: 4px solid #198754 !important;">
											<div class="mb-2 text-uppercase fw-bold text-success small border-bottom pb-1">
												<?= htmlspecialchars($item['hino_titulo'] ?? 'Hino') ?>
											</div>
											<div style="display: block;"><?= htmlspecialchars($item['hino_letra']) ?></div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; else: ?>
						<p class="text-muted small text-center p-3">Não cadastrada.</p>
					<?php endif; ?>
				</div>

				<div class="mt-4 pt-3 border-top no-print-break">
					<div class="bg-light rounded p-3 shadow-sm border text-center">
						<h6 class="fw-bold text-primary mb-2" style="font-size: 0.85rem;">
							<i class="bi bi-heart-fill me-1"></i> Contribua com a Igreja
						</h6>

						<div class="bg-white p-2 d-inline-block border rounded shadow-sm mb-2">
							<div id="qrcode-pix"></div>
						</div>

						<div class="bg-white border rounded p-1 mb-1">
							<small class="text-uppercase fw-bold text-secondary d-block" style="font-size: 0.6rem;">Chave PIX (CNPJ):</small>
							<span class="fw-bold text-dark" style="font-size: 0.85rem;">
								<?= !empty($liturgia['igreja_cnpj']) ? $liturgia['igreja_cnpj'] : 'Consulte a tesouraria' ?>
							</span>
						</div>
						<p class="text-muted mb-0" style="font-size: 0.65rem; line-height: 1.2;">
							Aponte a câmera do seu banco para o QR Code para dízimos e ofertas.
						</p>
					</div>
				</div>

			<?php else: ?>
				<div class="alert alert-warning small">Nenhuma liturgia disponível.</div>
			<?php endif; ?>
        </div>

        <div class="col-lg-6">
            <div class="mensagem-container mb-4">
                <h5 class="col-titulo justify-content-center"><i class="bi bi-journal-text me-2"></i>Palavra da Semana</h5>
                <?php if ($mensagem): ?>
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-1 text-dark"><?= $mensagem['igreja_mensagem_dominical_titulo'] ?></h3>
                        <small class="text-muted" style="font-size: 0.9rem;">Por: <?= $mensagem['nome_autor'] ?></small>
                    </div>
                    <div class="mensagem-texto px-2">
                        <?= nl2br($mensagem['igreja_mensagem_dominical_mensagem']) ?>
                    </div>
                <?php endif; ?>
            </div>

			<div class="pt-4 border-top">
				<h5 class="col-titulo"><i class="bi bi-stars me-2"></i>Celebrações de <?= $nomeMes ?></h5>
				<div class="row g-2">
					<div class="col-4 border-end px-2">
						<h6 class="small fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.65rem;"><i class="bi bi-cake2 me-1"></i> Nascimento</h6>
						<?php foreach($nascidos as $n): ?>
							<div class="d-flex align-items-center mb-2 pb-1 border-bottom border-light">
								<img src="<?= $n['foto'] ? url($n['foto']) : 'https://ui-avatars.com/api/?name='.urlencode($n['nome']).'&size=32' ?>" class="rounded-circle me-2 border" style="width: 24px; height: 24px; object-fit: cover;">
								<div class="flex-grow-1 overflow-hidden">
									<div class="small fw-semibold text-truncate" style="font-size: 0.75rem;"><?= $n['nome'] ?></div>
								</div>
								<span class="badge bg-light text-primary border" style="font-size: 0.6rem;">D<?= $n['dia'] ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="col-4 border-end px-2">
						<h6 class="small fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.65rem;"><i class="bi bi-water me-1"></i> Batismo</h6>
						<?php foreach($batizados as $b): ?>
							<div class="d-flex align-items-center mb-2 pb-1 border-bottom border-light">
								<img src="<?= $b['foto'] ? url($b['foto']) : 'https://ui-avatars.com/api/?name='.urlencode($b['nome']).'&size=32' ?>" class="rounded-circle me-2 border" style="width: 24px; height: 24px; object-fit: cover;">
								<div class="flex-grow-1 overflow-hidden">
									<div class="small fw-semibold text-truncate" style="font-size: 0.75rem;"><?= $b['nome'] ?></div>
									<div class="text-muted" style="font-size: 0.55rem;"><?= $b['anos'] ?>º ano</div>
								</div>
								<span class="badge bg-light text-info border" style="font-size: 0.6rem;">D<?= $b['dia'] ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="col-4 px-2">
						<h6 class="small fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.65rem;"><i class="bi bi-heart-fill me-1"></i> Casamento</h6>
						<?php foreach($casados as $c): ?>
							<div class="d-flex align-items-center mb-2 pb-1 border-bottom border-light">
								<img src="<?= $c['foto'] ? url($c['foto']) : 'https://ui-avatars.com/api/?name='.urlencode($c['nome']).'&size=32' ?>" class="rounded-circle me-2 border" style="width: 24px; height: 24px; object-fit: cover;">
								<div class="flex-grow-1 overflow-hidden">
									<div class="small fw-semibold text-truncate" style="font-size: 0.75rem;"><?= $c['nome'] ?></div>
									<div class="text-muted" style="font-size: 0.55rem;"><?= $c['anos'] ?>º ano</div>
								</div>
								<span class="badge bg-light text-danger border" style="font-size: 0.6rem;">D<?= $c['dia'] ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
        </div>

        <div class="col-lg-3 border-start bg-light bg-opacity-10">
			<div class="mb-4">
				<h5 class="col-titulo"><i class="bi bi-person-badge me-2"></i>Liderança</h5>

				<?php
					// Define o ID da igreja de forma segura para a View
					$idIgrejaAtual = $_SESSION['usuario_igreja_id'] ?? $_SESSION['membro_igreja_id'];
				?>

				<?php foreach ($lideranca as $lider): ?>
					<div class="d-flex align-items-center mb-2 p-2 bg-white border rounded shadow-sm">
						<div class="me-3">
							<?php if (!empty($lider['membro_foto_arquivo'])): ?>
								<?php
									// Caminho da foto utilizando a variável definida acima
									$caminhoFotoLider = "assets/uploads/{$idIgrejaAtual}/membros/{$lider['membro_registro_interno']}/{$lider['membro_foto_arquivo']}";
								?>
								<img src="<?= url($caminhoFotoLider) ?>" class="foto-lider">
							<?php else: ?>
								<div class="placeholder-avatar"><i class="bi bi-person"></i></div>
							<?php endif; ?>
						</div>
						<div class="overflow-hidden">
							<p class="lider-cargo"><?= $lider['cargo_nome'] ?></p>
							<p class="lider-nome text-truncate"><?= $lider['membro_nome'] ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

            <div>
                <h5 class="col-titulo"><i class="bi bi-calendar-event me-2"></i>Próximos Eventos</h5>
                <?php if(!empty($eventos)): ?>
                    <div class="row g-2">
                        <?php foreach($eventos as $ev): ?>
                            <div class="col-12">
                                <div class="card card-evento border-0 shadow-sm p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="<?= url($ev['logo']) ?>" class="rounded border me-2" style="width: 24px; height: 24px; object-fit: contain; background: #fff;" onerror="this.src='<?= url('assets/img/logo-placeholder.png') ?>';">
                                        <span class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;"><?= $ev['sociedade'] ?></span>
                                    </div>
                                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.9rem; line-height: 1.2;"><?= $ev['titulo'] ?></h6>
                                    <div class="pt-2 border-top border-light mt-2" style="font-size: 0.75rem;">
                                        <i class="bi bi-clock text-primary me-1"></i><?= $ev['data'] ?> às <?= $ev['hora'] ?><br>
                                        <i class="bi bi-geo-alt text-danger me-1"></i><?= $ev['local'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small text-center p-2">Sem eventos agendados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row mt-5 pt-3 border-top text-center opacity-75">
        <div class="col-12">
            <p class="small italic mb-1 fw-bold" style="font-size: 0.8rem;">"Pois de Ti, Senhor, vem a nossa força."</p>
            <div style="font-size: 0.65rem;" class="fw-bold text-uppercase">
                © <?= date('Y') ?> <?= $liturgia['igreja_nome'] ?> • Sistema Ekklesia
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Pegamos o CNPJ limpo do PHP
    const cnpjPix = "<?= preg_replace('/[^0-9]/', '', $liturgia['igreja_cnpj'] ?? '') ?>";
    const container = document.getElementById("qrcode-pix");

    if (cnpjPix && container) {
        new QRCode(container, {
            text: cnpjPix,
            width: 120, // Tamanho menor para caber na coluna lateral
            height: 120,
            colorDark : "#005a32", // Verde IPB (conforme sua referência)
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }
});
</script>


</body>
</html>
