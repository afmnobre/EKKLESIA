<style>
    .book-shelf { background: #f0f0f0; padding: 20px; border-radius: 8px; box-shadow: inset 0 10px 10px -10px rgba(0,0,0,0.1); }
    .book-card {
        width: 160px; transition: transform 0.3s; border: none; background: none; margin-bottom: 30px;
    }
    .book-card:hover { transform: translateY(-10px); }
    .book-cover {
        height: 230px; width: 100%; object-fit: cover; border-radius: 4px 8px 8px 4px;
        box-shadow: 5px 5px 15px rgba(0,0,0,0.3); border-left: 4px solid rgba(0,0,0,0.1);
    }
    .book-title { font-size: 0.9rem; margin-top: 10px; line-height: 1.2; height: 35px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .nav-tabs-custom .nav-link { color: #555; font-weight: bold; border: none; padding: 10px 15px; }
    .nav-tabs-custom .nav-link.active { color: #000; border-bottom: 3px solid #212529; background: none; }

    /* Estilo para o placeholder quando não há imagem */
    .book-cover-placeholder {
        height: 230px;
        width: 100%;
        background: #e9ecef;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 4px 8px 8px 4px;
        box-shadow: 5px 5px 15px rgba(0,0,0,0.2);
        border-left: 4px solid #dee2e6;
        color: #adb5bd;
        text-align: center;
        padding: 10px;
    }
    .book-cover-placeholder i { font-size: 3.5rem; margin-bottom: 10px; }
    .book-cover-placeholder span { font-size: 0.7rem; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }

	/* Ajuste para o Choices.js combinar com o estilo do sistema */
	.choices__inner {
		background-color: #fff;
		border-radius: 0.375rem;
		border: 1px solid #dee2e6;
		min-height: 38px;
		padding: 2px 10px;
	}

	.choices__list--dropdown {
		z-index: 2000; /* Garante que o dropdown apareça acima do modal */
	}

	.choices[data-type*="select-one"]::after {
		border-color: #999 transparent transparent transparent;
    }

	/* Pode colocar dentro de uma tag <style> no seu arquivo */
	#reader {
		position: relative;
		background: #000;
	}

	/* Linha guia vermelha para ajudar a centralizar o código de barras */
	#reader::after {
		content: "";
		position: absolute;
		top: 50%;
		left: 5%;
		right: 5%;
		height: 2px;
		background: rgba(255, 0, 0, 0.6);
		box-shadow: 0 0 8px red;
		pointer-events: none;
		z-index: 10;
	}

</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-bookshelf me-2"></i>Biblioteca Digital</h3>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalNovoLivro">Novo Livro</button>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom mb-4">
        <div class="nav-tabs-custom d-flex flex-wrap">
            <?php
            $letras = array_merge(['0-9'], range('A', 'Z'));
            $letraAtiva = $_GET['letra'] ?? 'A';
            $catQuery = !empty($_GET['categoria']) ? '&categoria=' . urlencode($_GET['categoria']) : '';

            foreach($letras as $l): ?>
                <a href="?letra=<?= $l . $catQuery ?>"
                   class="nav-link <?= $letraAtiva == $l ? 'active' : '' ?>"><?= $l ?></a>
            <?php endforeach; ?>
        </div>

        <div class="pb-2">
            <select class="form-select form-select-sm shadow-sm" id="filtroCategoria" style="min-width: 200px;">
                <option value="">Todas as Categorias</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['categoria_nome'] ?>" <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['categoria_nome']) ? 'selected' : '' ?>>
                        <?= $cat['categoria_nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="book-shelf">
        <div class="row row-cols-auto justify-content-center justify-content-md-start">
            <?php if(empty($livros)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search mb-3 d-block text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted">Nenhum livro encontrado com a letra <b><?= htmlspecialchars($letraAtiva) ?></b>
                    <?php if(!empty($_GET['categoria'])): ?>
                        na categoria <b><?= htmlspecialchars($_GET['categoria']) ?></b>
                    <?php endif; ?>
                    </p>
                    <a href="<?= url('biblioteca') ?>" class="btn btn-sm btn-outline-dark">Limpar Filtros</a>
                </div>
            <?php endif; ?>

			<?php foreach($livros as $l):
				// Cálculo de disponibilidade
				$qtdTotal = (int)$l['livro_quantidade'];
				$qtdEmprestada = (int)$l['total_emprestados'];
				$disponiveis = $qtdTotal - $qtdEmprestada;

				// Define a cor baseada na disponibilidade real
				if ($disponiveis > 0) {
					$statusLabel = "Disponível ($disponiveis)";
					$statusColor = 'bg-success';
					$podeEmprestar = true;
				} else {
					$statusLabel = "Indisponível";
					$statusColor = 'bg-danger';
					$podeEmprestar = false;
				}
			?>
			<div class="col">
				<div class="book-card card h-100 p-2 border-0 shadow-sm">
					<?php if (!empty($l['livro_capa'])): ?>
						<img src="<?= url("assets/uploads/{$igreja['igreja_id']}/biblioteca/{$l['livro_capa']}") ?>"
							 class="book-cover rounded shadow-sm"
							 alt="<?= $l['livro_titulo'] ?>"
							 style="height: 200px; object-fit: cover;">
					<?php else: ?>
						<div class="book-cover-placeholder d-flex flex-column align-items-center justify-content-center rounded bg-light border-start border-4 shadow-sm"
							 style="height: 200px; color: #adb5bd; border-color: #dee2e6 !important;">
							<i class="bi bi-book-half" style="font-size: 3rem;"></i>
							<span class="small fw-bold mt-2" style="font-size: 0.6rem; letter-spacing: 1px; text-transform: uppercase;">Sem Capa</span>
						</div>
					<?php endif; ?>

					<div class="book-title fw-bold text-center px-1 mt-2 text-truncate" title="<?= $l['livro_titulo'] ?>" style="font-size: 0.9rem;">
						<?= $l['livro_titulo'] ?>
					</div>

					<div class="text-center mt-1">
						<span class="badge <?= $statusColor ?>" style="font-size: 0.6rem;">
							<?= $statusLabel ?>
						</span>
						<div class="small text-muted mt-1" style="font-size: 0.65rem;">
							Estoque: <?= $qtdTotal ?>
						</div>
					</div>

					<div class="d-flex justify-content-between align-items-center mt-auto pt-2 px-1">
						<div class="d-flex gap-1">
							<button type="button"
									class="btn btn-sm btn-outline-secondary border-0 btn-editar-livro"
									title="Editar"
									data-id="<?= $l['livro_id'] ?>"
									data-titulo="<?= htmlspecialchars($l['livro_titulo']) ?>"
									data-autor="<?= htmlspecialchars($l['livro_autor']) ?>"
									data-categoria="<?= $l['livro_categoria'] ?>"
									data-capa="<?= !empty($l['livro_capa']) ? url("assets/uploads/{$igreja['igreja_id']}/biblioteca/{$l['livro_capa']}") : '' ?>"
									data-bs-toggle="modal"
									data-bs-target="#modalEditarLivro">
								<i class="bi bi-pencil-square"></i>
							</button>

							<button type="button"
									class="btn btn-sm btn-outline-danger border-0 btn-excluir-livro"
									title="Excluir"
									data-id="<?= $l['livro_id'] ?>"
									data-titulo="<?= htmlspecialchars($l['livro_titulo']) ?>"
									data-bs-toggle="modal"
									data-bs-target="#modalExcluirLivro">
								<i class="bi bi-trash"></i>
							</button>
						</div>

						<div>
							<?php if($podeEmprestar): ?>
								<button class="btn btn-sm btn-success btn-emprestar"
										data-id="<?= $l['livro_id'] ?>"
										data-titulo="<?= htmlspecialchars($l['livro_titulo']) ?>"
										data-bs-toggle="modal"
										data-bs-target="#modalEmprestar">
									<i class="bi bi-book me-1"></i> Emprestar
								</button>
							<?php else: ?>
								<button class="btn btn-sm btn-secondary disabled py-1" style="font-size: 0.75rem;" title="Sem estoque disponível">
									<i class="bi bi-x-circle me-1"></i> Esgotado
								</button>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcluirLivro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="mb-1">Você está prestes a excluir o livro:</p>
                <h5 id="excluir-livro-nome" class="fw-bold text-danger mb-3">---</h5>
                <p class="text-muted small mb-0">Esta ação removerá o registro do banco de dados e o arquivo de imagem da capa permanentemente.</p>
            </div>
            <div class="modal-footer bg-light border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <a id="btn-confirmar-exclusao" href="#" class="btn btn-danger px-4">Excluir Agora</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovoLivro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Novo Livro na Estante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('biblioteca/salvar') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="capa_url_externa" id="capa_url_externa">
                <input type="hidden" name="letra_atual" value="<?= $letraAtiva ?>">
                <input type="hidden" name="categoria_atual" value="<?= $_GET['categoria'] ?? '' ?>">

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <label class="small fw-bold d-block mb-2">Capa do Livro</label>

							<div id="wrapper-preview" class="mb-3 rounded border shadow-sm bg-light" style="height: 320px; width: 100%; overflow: hidden;">
								<img id="img-preview-livro" src=""
									 style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: none;">

								<div id="placeholder-novo-livro" class="h-100 w-100 d-flex flex-column align-items-center justify-content-center text-muted">
									<i class="bi bi-book-half fs-1"></i>
									<span class="small">Sem capa</span>
								</div>
							</div>

                            <div class="mb-3">
                                <label class="btn btn-outline-dark btn-sm w-100">
                                    <i class="bi bi-upload me-1"></i> Upload Manual
                                    <input type="file" name="livro_capa" id="input_livro_capa" class="d-none" accept="image/*">
                                </label>
                                <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Ou busque pelo ISBN ao lado</small>
                            </div>
                        </div>

                        <div class="col-md-8">
							<div class="mb-3">
								<label class="small fw-bold">ISBN / Código de Barras</label>
								<div class="input-group">
									<input type="text" name="livro_isbn" id="livro_isbn" class="form-control" placeholder="Digite ou leia o código...">

									<button class="btn btn-outline-secondary" type="button" id="btnAbrirScanner" title="Ler Código de Barras">
										<i class="bi bi-camera"></i>
									</button>

									<button class="btn btn-dark" type="button" id="btnBuscarIsbn">
										<i class="bi bi-search"></i>
									</button>
								</div>

								<div id="reader" style="width: 100%; display: none; overflow: hidden;" class="mt-2 rounded border shadow-sm"></div>

								<div id="isbn-feedback" class="small mt-1" style="display: none;"></div>
							</div>
                            <div class="mb-3">
                                <label class="small fw-bold">Título do Livro</label>
                                <input type="text" name="livro_titulo" id="livro_titulo" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold">Autor</label>
                                <input type="text" name="livro_autor" id="livro_autor" class="form-control">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold">Categoria</label>
                                    <select name="livro_categoria" id="livro_categoria" class="form-select" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach($categorias as $cat): ?>
                                            <option value="<?= $cat['categoria_nome'] ?>"><?= $cat['categoria_nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold">Data de Publicação</label>
                                    <input type="text" name="livro_publicacao" id="livro_publicacao" class="form-control" placeholder="Ex: 2024 ou Jan/2024">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold">Quantidade</label>
                                    <input type="number" name="livro_quantidade" class="form-control" value="1" min="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark px-4 fw-bold">Salvar Livro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarLivro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Livro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?= url('biblioteca/atualizar') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="letra_atual" value="<?= $letraAtiva ?>">
                <input type="hidden" name="categoria_atual" value="<?= $_GET['categoria'] ?? '' ?>">
                <input type="hidden" name="livro_id" id="edit_livro_id">

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center border-end d-flex flex-column justify-content-center">
                            <label class="small fw-bold d-block mb-2">Capa do Livro</label>

							<div id="wrapper-edit-preview" class="mb-3 rounded border shadow-sm bg-light" style="height: 320px; width: 100%; overflow: hidden;">
								<img id="edit_preview_capa" src=""
									 style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: none;">

								<div id="edit_placeholder_capa" class="h-100 w-100 d-flex flex-column align-items-center justify-content-center text-muted">
									<i class="bi bi-book-half fs-1"></i>
									<span class="small">Sem capa</span>
								</div>
							</div>

                            <div class="mb-3">
                                <label class="btn btn-outline-dark btn-sm w-100">
                                    <i class="bi bi-upload me-1"></i> Substituir Capa
                                    <input type="file" name="livro_capa" id="edit_input_capa" class="d-none" accept="image/*">
                                </label>
                                <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Formatos aceitos: JPG, PNG</small>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="small fw-bold">Título do Livro</label>
                                <input type="text" name="livro_titulo" id="edit_livro_titulo" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold">Autor</label>
                                <input type="text" name="livro_autor" id="edit_livro_autor" class="form-control">
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="small fw-bold">Categoria</label>
                                    <select name="livro_categoria" id="edit_livro_categoria" class="form-select" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach($categorias as $cat): ?>
                                            <option value="<?= $cat['categoria_nome'] ?>"><?= $cat['categoria_nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="alert alert-light border small text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Para atualizar o ISBN ou a data de publicação, verifique os campos adicionais no banco de dados ou adicione-os aqui se necessário.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark px-4 fw-bold">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEmprestar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Novo Empréstimo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('biblioteca/emprestar') ?>" method="POST">
                <input type="hidden" name="livro_id" id="emp_livro_id">
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Livro: <b id="emp_livro_titulo"></b></p>

					<div class="mb-3">
						<label class="small fw-bold">Selecionar Membro</label>
						<select name="membro_id" id="select-membro" class="form-select" required>
							<option value="">Pesquise pelo nome do membro...</option>
							<?php foreach($membros as $m): ?>
								<option value="<?= $m['membro_id'] ?>"><?= htmlspecialchars($m['membro_nome']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>

                    <div class="mb-3">
                        <label class="small fw-bold">Devolução Prevista</label>
                        <input type="date" name="data_devolucao" class="form-control" required value="<?= date('Y-m-d', strtotime('+15 days')) ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100 fw-bold">Confirmar Empréstimo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. LÓGICA DO MODAL DE EDIÇÃO
    const btnEditar = document.querySelectorAll('.btn-editar-livro');
    btnEditar.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_livro_id').value = this.getAttribute('data-id');
            document.getElementById('edit_livro_titulo').value = this.getAttribute('data-titulo');
            document.getElementById('edit_livro_autor').value = this.getAttribute('data-autor');
            document.getElementById('edit_livro_categoria').value = this.getAttribute('data-categoria');

            const urlCapa = this.getAttribute('data-capa');
            const imgPreview = document.getElementById('edit_preview_capa');
            const placeholder = document.getElementById('edit_placeholder_capa');

			if (urlCapa && urlCapa !== '') {
				imgPreview.src = urlCapa;
				imgPreview.style.display = 'block'; // Garante que a imagem ocupe o espaço
				placeholder.style.display = 'none';
			} else {
				imgPreview.style.display = 'none';
				placeholder.style.setProperty('display', 'flex', 'important'); // Garante que o placeholder centralize
			}
        });
    });

    // 2. FILTRO AUTOMÁTICO POR CATEGORIA
    const filtroCat = document.getElementById('filtroCategoria');
    if (filtroCat) {
        filtroCat.addEventListener('change', function() {
            const categoria = this.value;
            const urlParams = new URLSearchParams(window.location.search);

            if (categoria) {
                urlParams.set('categoria', categoria);
                urlParams.delete('letra');
            } else {
                urlParams.delete('categoria');
            }
            window.location.href = window.location.pathname + '?' + urlParams.toString();
        });
    }

    // 3. GESTÃO DE EMPRÉSTIMOS (CHOICES.JS)
    const elementoMembro = document.getElementById('select-membro');
    let choicesMembro = null;
    if (elementoMembro) {
        choicesMembro = new Choices(elementoMembro, {
            searchEnabled: true,
            itemSelectText: 'Selecionar',
            noResultsText: 'Nenhum membro encontrado',
            noChoicesText: 'Sem opções disponíveis',
            placeholder: true,
            placeholderValue: 'Digite o nome para buscar...',
            searchPlaceholderValue: 'Buscar membro...',
            shouldSort: false,
        });
    }

    document.querySelectorAll('.btn-emprestar').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('emp_livro_id').value = this.getAttribute('data-id');
            document.getElementById('emp_livro_titulo').innerText = this.getAttribute('data-titulo');
            if(choicesMembro) choicesMembro.setChoiceByValue('');
        });
    });

    // 4. LÓGICA DE EXCLUSÃO (MODAL)
    const btnConfirmarExcluir = document.getElementById('btn-confirmar-exclusao');
    const nomeLivroExibir = document.getElementById('excluir-livro-nome');

    document.querySelectorAll('.btn-excluir-livro').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const titulo = this.getAttribute('data-titulo');

            if(nomeLivroExibir) nomeLivroExibir.textContent = titulo;
            if(btnConfirmarExcluir) {
                const urlParams = new URLSearchParams(window.location.search);
                const query = urlParams.toString() ? '&' + urlParams.toString() : '';
                btnConfirmarExcluir.setAttribute('href', '<?= url("biblioteca/excluir/") ?>' + id + '?' + query);
            }
        });
    });

    // 5. PREVIEW DE CAPA (UPLOAD MANUAL)
    const inputCapaManual = document.getElementById('input_livro_capa');
    if (inputCapaManual) {
        inputCapaManual.addEventListener('change', function() {
            const reader = new FileReader();
            const imgPreview = document.getElementById('img-preview-livro');
            const inputExterno = document.getElementById('capa_url_externa');

			reader.onload = function(e) {
				const imgPreview = document.getElementById('img-preview-livro');
				const placeholder = document.getElementById('placeholder-novo-livro');

				imgPreview.src = e.target.result;
				imgPreview.style.display = 'block'; // Mostra a imagem
				placeholder.style.display = 'none';  // Esconde o placeholder

				if(inputExterno) inputExterno.value = '';
			}
        });
    }

    // 6. BUSCA ISBN VIA PROXY PHP (EVITA BLOQUEIO DE RASTREIO E ERRO 503)
    const btnBuscaIsbn = document.getElementById('btnBuscarIsbn');
    if (btnBuscaIsbn) {
        btnBuscaIsbn.addEventListener('click', async function() {
            const isbnInput = document.getElementById('livro_isbn');
            const isbn = isbnInput.value.replace(/\D/g, '');
            const feedback = document.getElementById('isbn-feedback');
            const btn = this;

            if (isbn.length < 10) {
                alert('Por favor, insira um ISBN válido (10 ou 13 dígitos).');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            feedback.style.display = 'block';
            feedback.className = 'small mt-1 text-primary';
            feedback.innerText = 'Buscando informações através do servidor...';

            try {
                // Chamada para o seu arquivo PHP que faz o CURL
                // Ajuste o caminho se necessário conforme sua estrutura de URL
				// ... dentro do try do seu fetch no Bloco 6 ...
				const response = await fetch(`<?= url("biblioteca/api_isbn") ?>?isbn=${isbn}`);
				const dados = await response.json();

				if (dados.titulo) {
					document.getElementById('livro_titulo').value = dados.titulo;
					document.getElementById('livro_autor').value = dados.autor;
					document.getElementById('livro_publicacao').value = dados.data;

					const imgPreview = document.getElementById('img-preview-livro');
					const placeholder = document.getElementById('placeholder-novo-livro');

					if (dados.capa && dados.capa !== '') {
						imgPreview.src = dados.capa;
						imgPreview.style.display = 'block';
						placeholder.style.display = 'none';
						inputExterno.value = dados.capa;
					} else {
						imgPreview.src = '';
						imgPreview.style.display = 'none';
						placeholder.style.display = 'flex';
						inputExterno.value = '';
					}

					feedback.innerText = 'Dados recuperados!';
					feedback.className = 'small mt-1 text-success';
				}
                // ... resto do catch/finally ...
                else {
                    feedback.innerText = 'Livro não encontrado ou serviço instável.';
                    feedback.className = 'small mt-1 text-danger';
                }

            } catch (error) {
                console.error("Erro na busca:", error);
                feedback.innerText = 'Erro ao conectar com o serviço de busca.';
                feedback.className = 'small mt-1 text-danger';
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-search"></i> Buscar';
            }
        });
    }
});

//CODIGO DE BARRAS
let html5QrCode;

// Função para parar a câmera com segurança
const pararScanner = async () => {
    if (html5QrCode && html5QrCode.isScanning) {
        await html5QrCode.stop();
        document.getElementById('reader').style.display = 'none';
    }
};

const btnScanner = document.getElementById('btnAbrirScanner');
if (btnScanner) {
    btnScanner.addEventListener('click', async function() {
        const readerDiv = document.getElementById('reader');

        // Se clicar e já estiver aberto, ele fecha
        if (readerDiv.style.display === 'block') {
            await pararScanner();
            return;
        }

        readerDiv.style.display = 'block';
        html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 15,
            qrbox: { width: 300, height: 150 }, // Formato retangular para EAN-13
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" }, // Tenta abrir a câmera traseira
            config,
            (decodedText) => {
                // Sucesso na leitura!
                document.getElementById('livro_isbn').value = decodedText;

                // Feedback tátil no celular
                if (navigator.vibrate) navigator.vibrate(100);

                pararScanner(); // Fecha a câmera

                // Aciona a busca automática que já criamos antes
                document.getElementById('btnBuscarIsbn').click();
            },
            (errorMessage) => {
                // Erros de foco são disparados constantemente, apenas ignoramos
            }
        ).catch(err => {
            console.error("Erro na câmera:", err);
            alert("Não foi possível acessar a câmera. Verifique as permissões.");
            readerDiv.style.display = 'none';
        });
    });
}

// GARANTIA: Se fechar o modal no "X", desliga a câmera para não gastar bateria
const modalNovo = document.getElementById('modalNovoLivro');
if (modalNovo) {
    modalNovo.addEventListener('hidden.bs.modal', pararScanner);
}


</script>
<script src="https://unpkg.com/html5-qrcode"></script>
