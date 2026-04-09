<?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Ação realizada com sucesso!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Ocorreu um erro ao processar a solicitação.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container-fluid py-4">
 	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body py-3">
			<div class="row align-items-center">
				<div class="col-md-3 border-end">
					<label class="small fw-bold text-muted text-uppercase mb-1 d-block">Filtrar por Ano</label>
					<div class="input-group">
						<span class="input-group-text bg-white border-0"><i class="bi bi-calendar-event text-primary"></i></span>
						<select class="form-select border-0 bg-light fw-bold"
								onchange="location.href='?mes=<?= $mesSelecionado ?>&ano='+this.value">
							<?php
							// Se não vier do controller, define o ano atual
							$anoAtual = date('Y');
							$anos = $anosDisponiveis ?? [['ano' => $anoAtual], ['ano' => $anoAtual + 1]];
							foreach($anos as $a):
							?>
								<option value="<?= $a['ano'] ?>" <?= $a['ano'] == $anoSelecionado ? 'selected' : '' ?>>
									<?= $a['ano'] ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="col-md-9">
					<label class="small fw-bold text-muted text-uppercase mb-1 d-block text-center text-md-start ps-3">Mês do Calendário</label>
					<div class="nav nav-pills nav-fill bg-light p-1 rounded-pill mx-md-2">
						<?php
						$meses = [
							1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun',
							7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'
						];
						foreach($meses as $num => $nome):
							$active = ($num == $mesSelecionado) ? 'active shadow-sm' : 'text-muted';
						?>
							<div class="nav-item">
								<a class="nav-link py-1 rounded-pill <?= $active ?>"
								   href="?mes=<?= $num ?>&ano=<?= $anoSelecionado ?>">
									<?= $nome ?>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="bi bi-calendar-week me-2 text-primary"></i>Calendário de Sociedades</h3>
        <button class="btn btn-primary shadow-sm" onclick="window.novoEvento()">
            <i class="bi bi-plus-lg me-2"></i>Agendar Novo Evento
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Data/Hora</th>
                        <th>Sociedade</th>
                        <th>Evento</th>
                        <th>Local</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
					<tbody>
					<?php if (!empty($eventos)): foreach ($eventos as $ev):
						// Lógica simples para cores de status
						$status = strtolower($ev['sociedade_evento_status']);
						$badgeClass = 'bg-info'; // padrão
						if (strpos($status, 'conclu') !== false) $badgeClass = 'bg-success';
						if (strpos($status, 'cancela') !== false) $badgeClass = 'bg-danger';
						if (strpos($status, 'prog') !== false) $badgeClass = 'bg-primary';
					?>
					<tr>
						<td class="ps-4">
							<div class="fw-bold"><?= date('d/m/Y', strtotime($ev['sociedade_evento_data_hora_inicio'])) ?></div>
							<small class="text-muted"><?= date('H:i', strtotime($ev['sociedade_evento_data_hora_inicio'])) ?></small>
						</td>
						<td><span class="badge bg-secondary"><?= $ev['sociedade_nome'] ?></span></td>
						<td>
							<strong><?= $ev['sociedade_evento_titulo'] ?></strong>
							<?php if(!empty($ev['sociedade_evento_valor']) && $ev['sociedade_evento_valor'] > 0): ?>
								<span class="ms-1 text-success small"><i class="bi bi-cash"></i></span>
							<?php endif; ?>
						</td>
						<td><small class="text-muted"><?= $ev['sociedade_evento_local'] ?></small></td>
						<td><span class="badge rounded-pill <?= $badgeClass ?>"><?= $ev['sociedade_evento_status'] ?></span></td>
						<td class="text-end pe-4">
							<div class="btn-group shadow-sm" role="group">
								<button class="btn btn-sm btn-white border" title="Gerar Flyer" onclick='window.prepararFlyer(<?= json_encode($ev) ?>)'>
									<i class="bi bi-megaphone text-success"></i>
								</button>
								<button class="btn btn-sm btn-white border" title="Editar" onclick='window.editarEvento(<?= json_encode($ev) ?>)'>
									<i class="bi bi-pencil-square text-primary"></i>
								</button>
								<button class="btn btn-sm btn-white border" title="Excluir" onclick="window.excluirEvento(<?= $ev['sociedade_evento_id'] ?>)">
									<i class="bi bi-trash text-danger"></i>
								</button>
							</div>
						</td>
					</tr>
					<?php endforeach; else: ?>
					<tr>
						<td colspan="6" class="text-center py-5">
							<i class="bi bi-calendar-x d-block mb-2 text-muted" style="font-size: 2rem;"></i>
							<span class="text-muted">Nenhum evento agendado para <?= $meses[$mesSelecionado] ?> de <?= $anoSelecionado ?>.</span>
						</td>
					</tr>
					<?php endif; ?>
				</tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="formEvento" action="<?= url('SociedadesEventos/salvar') ?>" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="evento_id" id="ev_id">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEventoTitulo">Agendar Evento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-primary">Sociedade Responsável</label>
                        <select name="sociedade_id" id="ev_soc_id" class="form-select border-primary" required>
                            <option value="">Escolha uma sociedade...</option>
                            <?php foreach($sociedades as $s): ?>
                                <option value="<?= $s['sociedade_id'] ?>"><?= $s['sociedade_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Título do Evento</label>
                        <input type="text" name="titulo" id="ev_titulo" class="form-control" placeholder="Ex: Chá das Mulheres..." required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold small text-info">Sugestão de Local / Membro (Pesquise aqui)</label>
						<select id="select_local_preset" class="form-select" onchange="window.atualizarEnderecoEvento(this)" placeholder="Buscar nome...">
							<option value="">Buscar nome do membro ou endereço da igreja...</option>

							<option value="<?= $igreja['igreja_nome'] ?>" data-endereco="<?= $igreja['igreja_endereco'] ?>">
								📍 SEDE: <?= $igreja['igreja_nome'] ?>
							</option>

							<optgroup label="Residência de Membros">
								<?php foreach($membros as $m): ?>
									<option value="Residência de <?= $m['membro_nome'] ?>" data-endereco="<?= $m['membro_endereco'] ?>">
										🏠 <?= $m['membro_nome'] ?>
									</option>
								<?php endforeach; ?>
							</optgroup>
						</select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label fw-bold small">Data/Hora Início</label>
                        <input type="datetime-local" name="data_inicio" id="ev_inicio" class="form-control" required>
                    </div>
                    <div class="col-md-7 mb-3">
                        <label class="form-label fw-bold small">Local Confirmado / Endereço Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="local" id="ev_local" class="form-control border-info" placeholder="Selecione acima ou digite aqui..." required>
                        </div>
                    </div>
                </div>

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label fw-bold small">Data/Hora Fim (Opcional)</label>
						<input type="datetime-local" name="data_fim" id="ev_fim" class="form-control">
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label fw-bold small">Valor/Investimento (R$)</label>
						<input type="number" name="valor" id="ev_valor" class="form-control" step="0.01" placeholder="0,00">
					</div>
				</div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Descrição/Observações</label>
                    <textarea name="descricao" id="ev_desc" class="form-control" rows="3" placeholder="Detalhes do evento..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-4">Salvar Evento</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalFlyer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0 d-flex justify-content-center">
                <div id="captureFlyer" class="flyer-container shadow-lg">

                    <div class="flyer-logo-section text-center">
                        <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Logo IPB" class="flyer-logo-img">
                    </div>

                    <div class="flyer-title-section text-center text-uppercase">
                        <h2 class="m-0">Encontro da</h2>
                        <h1 id="flyerSociedadeNome" class="m-0 fw-bold gold-text"></h1>
                        <h3 id="flyerTitulo" class="m-0 fw-bold mt-2"></h3>
                        <div class="flyer-sub-header">
                            <span class="flyer-line"></span>
                            <span class="flyer-igreja-txt">IGREJA PRESBITERIANA DO JARDIM GIRASSOL</span>
                            <span class="flyer-line"></span>
                        </div>
                    </div>

                    <div class="flyer-invite-section text-center italic">
                        <p class="m-0">Você é nosso convidado especial!</p>
                        <h4 class="fw-bold mt-2 text-uppercase">Participe do nosso encontro!</h4>
                    </div>

                    <div class="flyer-info-section px-5">
                        <div class="flyer-info-row mb-4">
                            <div class="flyer-icon-circle"><i class="bi bi-calendar-check-fill"></i></div>
                            <div class="flyer-info-text">
                                <span id="flyerDataHoraFull" class="text-uppercase fw-bold gold-text"></span>
                            </div>
                        </div>

                        <div id="containerValor" class="text-center" style="display: none;">
                            <div class="flyer-valor-box">
                                <span class="flyer-valor-label">Investimento: </span>
                                <span id="flyerValor" class="flyer-valor-dinheiro"></span>
                            </div>
                        </div>

                        <div class="flyer-info-row">
                            <div class="flyer-icon-circle"><i class="bi bi-geo-alt-fill"></i></div>
                            <div class="flyer-info-text fw-normal">
                                <span id="flyerLocal"></span>
                            </div>
                        </div>

                        <div class="flyer-footer-section text-center italic">
                            <p id="flyerFraseDinamica">Venha participar conosco de uma tarde de<br>comunhão e crescimento espiritual.</p>
                            <h4 class="fw-bold">Esperamos por você!</h4>
                        </div>
                    </div>

                    <div class="flyer-igreja-address text-center">
                        Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha,<br> Guarulhos - SP, 07160-350
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                <button type="button"
                    id="btnDownloadFlyer"
                    class="btn btn-success btn-lg w-100"
                    onclick="window.baixarFlyerImagem(this)">
                    <i class="bi bi-download me-2"></i> Baixar Imagem para Compartilhar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="palcoCaptura" class="offscreen-flyer">
    <div class="flyer-container">
        <div class="flyer-logo-section text-center">
            <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Logo IPB" class="flyer-logo-img">
        </div>

        <div class="flyer-title-section text-center text-uppercase">
            <h2 class="m-0">Encontro da</h2>
            <h1 id="palcoSociedadeNome" class="m-0 fw-bold gold-text"></h1>
            <h3 id="palcoTitulo" class="m-0 fw-bold mt-2"></h3>
            <div class="flyer-sub-header">
                <span class="flyer-line"></span>
                <span class="flyer-igreja-txt">IGREJA PRESBITERIANA DO JARDIM GIRASSOL</span>
                <span class="flyer-line"></span>
            </div>
        </div>

        <div class="flyer-invite-section text-center italic">
            <p class="m-0"><br>Você é nosso convidado especial!</p>
            <h4 class="fw-bold mt-2 text-uppercase">Participe do nosso encontro!</h4>
        </div>

        <div class="flyer-info-section px-5">
            <div class="flyer-info-row mb-4">
                <div class="flyer-icon-circle"><i class="bi bi-calendar-check-fill"></i></div>
                <div class="flyer-info-text">
                    <span id="palcoDataHoraFull" class="text-uppercase fw-bold gold-text"></span>
                </div>
            </div>

            <div id="palcoContainerValor" class="text-center" style="display: none;">
                <div class="flyer-valor-box">
                    <span class="flyer-valor-label">Investimento: </span>
                    <span id="palcoValor" class="flyer-valor-dinheiro"></span>
                </div>
            </div>

            <div class="flyer-info-row">
                <div class="flyer-icon-circle"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="flyer-info-text fw-normal">
                    <span id="palcoLocal"></span>
                </div>
            </div>
        </div>

        <div class="flyer-footer-section text-center italic">
            <br>
            <p id="palcoFraseDinamica">Venha participar conosco de uma noite de<br> comunhão e crescimento espiritual.</p>
            <h4 class="fw-bold mt-3">Esperamos por você!</h4>
        </div>

        <div class="flyer-igreja-address text-center">
            Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha,<br> Guarulhos - SP, 07160-350
        </div>
    </div>
</div>

<style>
/* Reset Focado apenas nos containers do Flyer para não interferir no resto do site */
#modalFlyer .modal-body,
#captureFlyer,
#palcoCaptura,
#palcoCaptura .flyer-container {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Reset de elementos de texto apenas dentro do flyer */
#captureFlyer h1, #captureFlyer h2, #captureFlyer h3, #captureFlyer h4, #captureFlyer p,
#palcoCaptura h1, #palcoCaptura h2, #palcoCaptura h3, #palcoCaptura h4, #palcoCaptura p {
    margin: 0;
    padding: 0;
}

/* ========================================================= */
/* 1. ESTILO MESTRE DO FLYER                                */
/* ========================================================= */
.flyer-container {
    width: 1024px;
    height: 1536px;
    background-image: url('<?= url("assets/img/background.png") ?>');
    background-size: 100% 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-color: #ffffff;
    position: relative;
    overflow: hidden;

    /* MARGEM INTERNA RIGOROSA */
    padding: 70px !important;

    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: 'Times New Roman', Times, serif;
    color: #1a3c34;
}

.gold-text { color: #a68b5a !important; }
.italic { font-style: italic; }

/* ========================================================= */
/* 2. AJUSTE DO MODAL (VISUALIZAÇÃO)                         */
/* ========================================================= */
#modalFlyer .modal-dialog { max-width: fit-content; }
#modalFlyer .modal-body {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 600px;
    padding: 0;
    overflow: visible;
    background-color: #f1f3f5;
}
#captureFlyer {
    display: flex;
    transform: scale(0.38);
    transform-origin: top center;
    margin-bottom: -950px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    #captureFlyer { transform: scale(0.28); margin-bottom: -1100px; }
    #modalFlyer .modal-body { min-height: 480px; }
}

/* ========================================================= */
/* 3. AJUSTE DO PALCO DE CAPTURA                             */
/* ========================================================= */
.offscreen-flyer {
    display: none;
    width: 1024px;
    height: 1536px;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -9999;
}

/* ========================================================= */
/* 4. ESTILOS INTERNOS (COMPACTADOS)                         */
/* ========================================================= */

/* Seção do Logo */
.flyer-logo-section {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 10px; /* <diminuído aqui> */
}
.flyer-logo-img { height: 160px; width: auto; object-fit: contain; }

/* Títulos */
.flyer-title-section { text-align: center; width: 100%; }
.flyer-title-section h2 { font-size: 28px; letter-spacing: 5px; text-transform: uppercase; margin-bottom: 5px; }
.flyer-title-section h1 { font-size: 50px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
.flyer-title-section h3 { font-size: 40px; font-weight: bold; color: #1a3c34; text-transform: uppercase; margin-bottom: 15px; } /* <diminuído aqui> */

.flyer-sub-header { display: flex; align-items: center; justify-content: center; gap: 15px; margin-top: 5px; }
.flyer-line { height: 2px; width: 100px; background-color: #a68b5a; }
.flyer-igreja-txt { font-size: 24px; color: #a68b5a; font-weight: bold; }

/* Convite */
.flyer-invite-section { margin-top: 10px; text-align: center; width: 100%; } /* <diminuído aqui> */
.flyer-invite-section p { font-size: 38px; line-height: 1.1; }
.flyer-invite-section h4 { font-size: 34px; color: #1a3c34; text-transform: uppercase; font-weight: bold; margin-top: 10px; }

/* Informações (Data, Local, Endereço) */
.flyer-info-section {
    width: 100%;
    margin-top: 15px; /* <diminuído aqui> */
    margin-bottom: 15px;
    padding: 25px 0; /* <diminuído aqui> */
    border-top: 2px solid rgba(166, 139, 90, 0.2);
    border-bottom: 2px solid rgba(166, 139, 90, 0.2);
}
.flyer-info-row { display: flex; align-items: center; justify-content: center; gap: 25px; margin-bottom: 15px; } /* <diminuído aqui> */
.flyer-info-row:last-child { margin-bottom: 0; }
.flyer-icon-circle { font-size: 45px; color: #1a3c34; }
.flyer-info-text { font-size: 32px; font-weight: bold; text-align: left; line-height: 1.1; }

/* Valor */
#containerValor, #palcoContainerValor { width: 100%; text-align: center; margin-top: 5px; margin-bottom: 15px; }
.flyer-valor-box { display: inline-block; padding: 10px 40px; border: 2px solid #a68b5a; border-radius: 12px; background-color: rgba(255, 255, 255, 0.8); }
.flyer-valor-label { font-size: 26px; color: #1a3c34; text-transform: uppercase; font-weight: bold; }
.flyer-valor-dinheiro { font-size: 38px; font-weight: bold; color: #a68b5a; }

/* Rodapé agora posicionado logo abaixo do local */
.flyer-footer-section {
    width: 90%; /* Ajustado para não colar nas bordas laterais */
    text-align: center;
    margin: 15px auto; /* Centraliza e dá um espaçamento vertical */
    padding: 20px;

    /* Fundo suave para destacar o texto sobre a floresta */
    background-color: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.flyer-footer-section p {
    font-size: 30px;
    line-height: 1.3;
    color: #1a3c34;
}

.flyer-footer-section h4 {
    font-size: 36px;
    margin-top: 10px;
    color: #1a3c34;
}

/* Garante que o endereço da igreja fique no final absoluto */
.flyer-igreja-address {
    margin-top: auto; /* Agora este é quem empurra para o fundo */
    width: 100%;
    text-align: center;
    font-size: 18px;
    padding-top: 15px;
    padding-bottom: 5px;
    border-top: 1px solid rgba(26, 60, 52, 0.1);
}
</style>

<script>
/**
 * VARIÁVEIS GLOBAIS E INICIALIZAÇÃO
 */
let choicesLocal;

document.addEventListener('DOMContentLoaded', function() {
    // Inicialização do Choices.js para o campo de local
    const element = document.getElementById('select_local_preset');
    if (element) {
        choicesLocal = new Choices(element, {
            searchEnabled: true,
            itemSelectText: 'Selecionar',
            noResultsText: 'Membro não encontrado',
            placeholder: true,
            placeholderValue: 'Digite para buscar um membro ou local...',
            searchPlaceholderValue: 'Pesquisar pelo nome...',
            shouldSort: false
        });

        element.addEventListener('change', function() {
            window.atualizarEnderecoEvento(element);
        });
    }
});

/**
 * FUNÇÕES DO CRUD (EVENTOS)
 */

// Abre o modal para um novo registro
window.novoEvento = function() {
    const form = document.getElementById('formEvento');
    if(form) form.reset();

    if(choicesLocal) {
        choicesLocal.setChoiceByValue('');
    }

    document.getElementById('ev_id').value = '';
    document.getElementById('modalEventoTitulo').innerText = 'Agendar Evento';
    document.getElementById('ev_local').value = '';

    const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
    modal.show();
};

// Abre o modal preenchido para edição
window.editarEvento = function(dados) {
    document.getElementById('ev_id').value = dados.sociedade_evento_id;
    document.getElementById('ev_soc_id').value = dados.sociedade_evento_sociedade_id;
    document.getElementById('ev_titulo').value = dados.sociedade_evento_titulo;
    document.getElementById('ev_local').value = dados.sociedade_evento_local;
    document.getElementById('ev_desc').value = dados.sociedade_evento_descricao;

    // Carregar Fim e Valor
    document.getElementById('ev_fim').value = dados.sociedade_evento_data_hora_fim ? dados.sociedade_evento_data_hora_fim.replace(" ", "T").substring(0, 16) : '';
    document.getElementById('ev_valor').value = dados.sociedade_evento_valor || '';

    if(dados.sociedade_evento_data_hora_inicio) {
        document.getElementById('ev_inicio').value = dados.sociedade_evento_data_hora_inicio.replace(" ", "T").substring(0, 16);
    }

    document.getElementById('modalEventoTitulo').innerText = 'Editar Evento';
    new bootstrap.Modal(document.getElementById('modalEvento')).show();
};

// Disparado ao selecionar um local no combo de sugestões para preencher o endereço
window.atualizarEnderecoEvento = function(select) {
    const selectedOption = select.options[select.selectedIndex];
    if (!selectedOption || select.value === "") return;

    const endereco = selectedOption.getAttribute('data-endereco');
    const nomeLocal = select.value;
    const campoLocal = document.getElementById('ev_local');

    if (endereco && endereco !== "null") {
        campoLocal.value = `${nomeLocal} - ${endereco}`;
    } else {
        campoLocal.value = nomeLocal;
    }
};

// Exclusão de evento
window.excluirEvento = function(id) {
    if (confirm('Tem certeza que deseja excluir este evento?')) {
        window.location.href = `<?= url('SociedadesEventos/excluir/') ?>${id}`;
    }
};

/**
 * Prepara e exibe o modal do Flyer, preenchendo os dados nos dois layouts
 */
window.prepararFlyer = function(dados) {
    const preencherSeExistir = (id, valor) => {
        const el = document.getElementById(id);
        if (el) el.innerText = valor;
    };

    // --- PREENCHER DADOS NOS DOIS LAYOUTS ---

    // 1. Títulos e Sociedade
    const nomeSociedade = dados.sociedade_nome || 'Sociedade';
    preencherSeExistir('flyerSociedadeNome', nomeSociedade);
    preencherSeExistir('palcoSociedadeNome', nomeSociedade);

    preencherSeExistir('flyerTitulo', dados.sociedade_evento_titulo);
    preencherSeExistir('palcoTitulo', dados.sociedade_evento_titulo);

    // 2. Local/Endereço
    preencherSeExistir('flyerLocal', dados.sociedade_evento_local || 'Local não informado');
    preencherSeExistir('palcoLocal', dados.sociedade_evento_local || 'Local não informado');

    // 3. Lógica de Valor (Investimento)
    const valorEvento = dados.sociedade_evento_valor;
    const containerV = document.getElementById('containerValor');
    const palcoContainerV = document.getElementById('palcoContainerValor');

    if (valorEvento && parseFloat(valorEvento) > 0) {
        const valorFormatado = parseFloat(valorEvento).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });

        preencherSeExistir('flyerValor', valorFormatado);
        preencherSeExistir('palcoValor', valorFormatado);

        if (containerV) containerV.style.display = 'block';
        if (palcoContainerV) palcoContainerV.style.display = 'block';
    } else {
        if (containerV) containerV.style.display = 'none';
        if (palcoContainerV) palcoContainerV.style.display = 'none';
    }

    // 4. Lógica de Data, Hora e Período (Frase Dinâmica)
    if (dados.sociedade_evento_data_hora_inicio) {
        const dataObj = new Date(dados.sociedade_evento_data_hora_inicio.replace(" ", "T"));
        const hora = dataObj.getHours();

        // Definir o período (Manhã, Tarde ou Noite)
        let periodoTexto = "uma noite";
        if (hora >= 5 && hora < 12) {
            periodoTexto = "uma manhã";
        } else if (hora >= 12 && hora < 18) {
            periodoTexto = "uma tarde";
        } else {
            periodoTexto = "uma noite";
        }

        const fraseDinamica = `Venha participar conosco de ${periodoTexto} de comunhão e crescimento espiritual.`;
        preencherSeExistir('flyerFraseDinamica', fraseDinamica);
        preencherSeExistir('palcoFraseDinamica', fraseDinamica);

        const opcoes = { weekday: 'long', day: '2-digit', month: 'long' };
        const dataFormatada = dataObj.toLocaleDateString('pt-BR', opcoes).toUpperCase();
        const horaFormatada = dataObj.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        const dataFinal = `${dataFormatada} ÀS ${horaFormatada}`;

        preencherSeExistir('flyerDataHoraFull', dataFinal);
        preencherSeExistir('palcoDataHoraFull', dataFinal);
    }

    // --- ABRIR MODAL ---
    const modalElement = document.getElementById('modalFlyer');
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.show();
    }

    // --- RESET DO BOTÃO DE DOWNLOAD ---
    const btnDownload = document.getElementById('btnDownloadFlyer');
    if (btnDownload) {
        btnDownload.innerHTML = '<i class="bi bi-download me-2"></i> Baixar Imagem para Compartilhar';
        btnDownload.classList.remove('disabled');
        btnDownload.classList.add('btn-success');
    }
};

/**
 * Evento de Clique para Download (Solução Definitiva para o Corte no Topo)
 */
/**
 * Função Global para Download do Flyer
 * Garante que o palco (1024x1536) respeite o CSS de 70px e Flexbox
 */
window.baixarFlyerImagem = function(btn) {
    // UI Feedback
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Gerando...';
    btn.classList.add('disabled');

    const palcoElement = document.getElementById('palcoCaptura');
    const nomeEvento = document.getElementById('palcoTitulo') ? document.getElementById('palcoTitulo').innerText : 'Evento';

    // 1. Salva o estado original para restaurar depois
    const originalStyle = palcoElement.style.cssText;

    // 2. FORÇAR LAYOUT NO PALCO (Garante que o html2canvas "enxergue" o Flexbox e os 70px)
    // O segredo aqui é o 'display: flex' e 'position: absolute' no topo 0.
    palcoElement.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        display: flex !important;
        visibility: visible;
        z-index: -9999;
        width: 1024px;
        height: 1536px;
        margin: 0;
        padding: 0;
        overflow: visible !important;
    `;

    // 3. Forçar o container interno (a div que tem a classe .flyer-container)
    const containerInterno = palcoElement.querySelector('.flyer-container');
    if (containerInterno) {
        containerInterno.style.display = 'flex';
        containerInterno.style.flexDirection = 'column';
        containerInterno.style.alignItems = 'center';
        containerInterno.style.padding = '70px'; // Força a margem de segurança que você ajustou
        containerInterno.style.width = '1024px';
        containerInterno.style.height = '1536px';
        containerInterno.style.boxSizing = 'border-box';
    }

    // 4. Pequeno delay para o navegador processar os estilos injetados acima
    setTimeout(() => {
        html2canvas(palcoElement, {
            scale: 1,
            useCORS: true,
            allowTaint: true,
            backgroundColor: "#ffffff",
            width: 1024,
            height: 1536,
            windowWidth: 1024,
            windowHeight: 1536,
            x: 0,
            y: 0,
            scrollX: 0,
            scrollY: 0
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/jpeg', 0.95);

            const link = document.createElement('a');
            link.href = imgData;
            link.download = `Flyer_${nomeEvento}.jpg`;
            link.click();

            // Restaurar estado original
            palcoElement.style.cssText = originalStyle;
            btn.innerHTML = originalContent;
            btn.classList.remove('disabled');
        }).catch(err => {
            console.error("Erro ao gerar imagem:", err);
            palcoElement.style.cssText = originalStyle;
            btn.innerHTML = "Erro ao baixar";
            btn.classList.remove('disabled');
        });
    }, 250);
};
</script>

