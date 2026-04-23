<div class="container-fluid py-4">
    <form action="<?= url('liturgia/salvar') ?>" method="POST" id="formMasterLiturgia">
        <input type="hidden" name="igreja_liturgia_id" value="<?= $liturgia['igreja_liturgia_id'] ?? '' ?>">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="text-secondary mb-0">
                    <?= isset($liturgia['igreja_liturgia_id']) ? '📝 Editar Liturgia' : '✨ Nova Liturgia' ?>
                </h3>
                <p class="text-muted small text-uppercase fw-bold">Configuração Geral do Culto</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= url('liturgia/index') ?>" class="btn btn-outline-secondary px-4 shadow-sm">Voltar</a>
                <button type="submit" class="btn btn-success shadow-sm px-5">
                    <i class="bi bi-save me-2"></i> Gravar Liturgia Completa
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="bi bi-calendar-check me-2 text-primary"></i> Informações do Culto
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Data e Horário</label>
                            <input type="datetime-local" name="igreja_liturgia_data" class="form-control"
                                   value="<?= isset($liturgia['igreja_liturgia_data']) ? date('Y-m-d\TH:i', strtotime($liturgia['igreja_liturgia_data'])) : date('Y-m-d\T18:00') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tema do Culto</label>
                            <input type="text" name="igreja_liturgia_tema" class="form-control"
                                   placeholder="Ex: Culto Dominical" value="<?= $liturgia['igreja_liturgia_tema'] ?? '' ?>">
                        </div>

                        <hr class="my-4">

						<div class="mb-4">
							<label class="form-label small fw-bold">Dirigente</label>
							<select name="dirigente_id" class="form-select choice-select mb-2" onchange="toggleManualInput(this, 'dirigente_manual')">
								<option value="">-- Selecionar da Lista --</option>

								<option value="outro" class="fw-bold text-primary" style="color: #0d6efd; font-weight: bold;" <?= (!empty($liturgia['igreja_liturgia_dirigente_nome']) && empty($liturgia['igreja_liturgia_dirigente_id'])) ? 'selected' : '' ?>>
									★ Outro (Visitante)
								</option>

								<?php foreach ($membros as $m): ?>
									<option value="<?= $m['membro_id'] ?>" <?= (isset($liturgia['igreja_liturgia_dirigente_id']) && $liturgia['igreja_liturgia_dirigente_id'] == $m['membro_id']) ? 'selected' : '' ?>>
										<?= htmlspecialchars($m['membro_nome']) ?>
									</option>
								<?php endforeach; ?>
							</select>

							<input type="text" name="dirigente_nome_manual" id="dirigente_manual"
								   class="form-control form-control-sm mb-1 <?= (empty($liturgia['igreja_liturgia_dirigente_nome']) || !empty($liturgia['igreja_liturgia_dirigente_id'])) ? 'd-none' : '' ?>"
								   placeholder="Digite o nome do Dirigente Visitante" value="<?= $liturgia['igreja_liturgia_dirigente_nome'] ?? '' ?>">
						</div>

						<div class="mb-3">
							<label class="form-label small fw-bold">Pregador</label>
							<select name="pregador_id" class="form-select choice-select mb-2" onchange="toggleManualInput(this, 'pregador_manual')">
								<option value="">-- Selecionar da Lista --</option>

								<option value="outro" class="fw-bold text-primary" style="color: #0d6efd; font-weight: bold;" <?= (!empty($liturgia['igreja_liturgia_pregador_nome']) && empty($liturgia['igreja_liturgia_pregador_id'])) ? 'selected' : '' ?>>
									★ Outro (Visitante)
								</option>

								<?php foreach ($membros as $m): ?>
									<option value="<?= $m['membro_id'] ?>" <?= (isset($liturgia['igreja_liturgia_pregador_id']) && $liturgia['igreja_liturgia_pregador_id'] == $m['membro_id']) ? 'selected' : '' ?>>
										<?= htmlspecialchars($m['membro_nome']) ?>
									</option>
								<?php endforeach; ?>
							</select>

							<input type="text" name="pregador_nome_manual" id="pregador_manual"
								   class="form-control form-control-sm mb-1 <?= (empty($liturgia['igreja_liturgia_pregador_nome']) || !empty($liturgia['igreja_liturgia_pregador_id'])) ? 'd-none' : '' ?>"
								   placeholder="Digite o nome do Pregador Visitante" value="<?= $liturgia['igreja_liturgia_pregador_nome'] ?? '' ?>">
						</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold"><i class="bi bi-list-ol me-2 text-primary"></i> Ordem do Culto</span>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddItem">
                            <i class="bi bi-plus-lg me-1"></i> Adicionar Parte
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div id="lista-liturgia" class="list-group list-group-flush">
                            <div id="empty-state" class="text-center py-5 text-muted d-none">
                                <i class="bi bi-layers fs-2 d-block mb-2"></i>
                                <p class="small mb-0">Nenhuma parte adicionada à liturgia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modalAddItem" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Adicionar Parte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal_edit_mode" value="0">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tipo</label>
                    <select id="modal_tipo" class="form-select">
                        <option value="texto">Texto/Aviso</option>
                        <option value="hino">Hino/Cântico</option>
                        <option value="leitura">Leitura Bíblica</option>
                        <option value="mensagem">Mensagem/Sermão</option>
                        <option value="oracao">Oração</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Descrição</label>
                    <input type="text" id="modal_descricao" class="form-control">
                </div>
                <div id="div_referencia" class="d-none">
                    <label class="form-label small fw-bold">Referência Bíblica</label>
                    <div class="input-group mb-2">
                        <input type="text" id="modal_referencia" class="form-control" placeholder="Livro Cap:Ver">
                        <button type="button" id="btnBuscarBiblia" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div id="div_conteudo_api" class="d-none">
                    <label class="form-label small fw-bold text-primary">Conteúdo/Mensagem</label>
                    <textarea id="modal_conteudo_api" class="form-control form-control-sm" rows="6"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmarItem" class="btn btn-primary">Salvar Parte</button>
            </div>
        </div>
    </div>
</div>

<template id="item-template">
    <div class="list-group-item item-liturgia p-3 border-bottom animate__animated animate__fadeIn">
        <div class="d-flex align-items-center">
            <i class="bi bi-grip-vertical fs-4 text-muted me-2" style="cursor:move"></i>
            <div class="flex-grow-1">
                <input type="hidden" class="in-tipo">
                <input type="hidden" class="in-desc">
                <input type="hidden" class="in-ref">
                <input type="hidden" class="in-conteudo">
                <span class="badge border me-2 in-label-tipo"></span>
                <span class="in-label-desc"></span>
                <small class="text-muted d-block in-label-ref"></small>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm text-secondary btn-edit-item"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-sm text-danger btn-remove-item"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
let itemSendoEditado = null;
const coresBadge = {
    'texto': 'bg-light text-dark',
    'hino': 'bg-info text-white',
    'leitura': 'bg-primary text-white',
    'mensagem': 'bg-success text-white',
    'oracao': 'bg-warning text-dark'
};

// --- FUNÇÃO PARA O CAMPO "OUTRO (VISITANTE)" ---
function toggleManualInput(selectElement, inputId) {
    const inputField = document.getElementById(inputId);
    if (!inputField) return;

    if (selectElement.value === 'outro') {
        inputField.classList.remove('d-none');
        setTimeout(() => inputField.focus(), 100);
    } else {
        inputField.classList.add('d-none');
        inputField.value = '';
    }
}

function adicionarItemLista(dados) {
    const lista = document.getElementById('lista-liturgia');
    const template = document.getElementById('item-template');
    if (!lista || !template) return;

    const tipo = (dados.tipo || 'texto').toLowerCase().trim();
    const desc = dados.desc || '';
    const ref  = dados.ref || '';
    const cont = dados.conteudo || '';

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = template.innerHTML;
    const novoItem = tempDiv.firstElementChild;

    novoItem.querySelector('.in-tipo').value = tipo;
    novoItem.querySelector('.in-desc').value = desc;
    novoItem.querySelector('.in-ref').value = ref;
    novoItem.querySelector('.in-conteudo').value = cont;

    const badge = novoItem.querySelector('.in-label-tipo');
    badge.innerText = tipo.toUpperCase();
    badge.className = `badge border me-2 in-label-tipo ${coresBadge[tipo] || 'bg-light text-dark'}`;
    novoItem.querySelector('.in-label-desc').innerText = desc;
    novoItem.querySelector('.in-label-ref').innerText = (tipo === 'leitura' || tipo === 'mensagem') ? ref : '';

    vincularAcoes(novoItem);
    lista.appendChild(novoItem);
    reindexarItens();
}

function vincularAcoes(item) {
    item.querySelector('.btn-remove-item').onclick = function() {
        if(confirm('Remover esta parte?')) { item.remove(); reindexarItens(); }
    };

    item.querySelector('.btn-edit-item').onclick = function() {
        itemSendoEditado = item;
        const tipo = item.querySelector('.in-tipo').value;
        const desc = item.querySelector('.in-desc').value;
        const ref  = item.querySelector('.in-ref').value;
        const cont = item.querySelector('.in-conteudo').value;

        document.getElementById('modalTitle').innerText = '📝 Editar Parte';
        document.getElementById('modal_tipo').value = tipo;
        document.getElementById('modal_descricao').value = desc;
        document.getElementById('modal_referencia').value = ref;
        document.getElementById('modal_conteudo_api').value = cont;

        atualizarCamposModal(tipo);
        const modalEl = document.getElementById('modalAddItem');
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    };
}

function reindexarItens() {
    const itens = document.querySelectorAll('.item-liturgia');
    itens.forEach((item, index) => {
        item.querySelector('.in-tipo').name = `itens[${index}][tipo]`;
        item.querySelector('.in-desc').name = `itens[${index}][descricao]`;
        item.querySelector('.in-ref').name = `itens[${index}][referencia]`;
        item.querySelector('.in-conteudo').name = `itens[${index}][conteudo_api]`;
        const descBase = item.querySelector('.in-desc').value.replace(/^\d+\.\s*/, "");
        item.querySelector('.in-label-desc').innerText = `${index + 1}. ${descBase}`;
    });
    const empty = document.getElementById('empty-state');
    if (itens.length === 0) empty?.classList.remove('d-none');
    else empty?.classList.add('d-none');
}

function atualizarCamposModal(tipo) {
    const divRef = document.getElementById('div_referencia');
    const divCont = document.getElementById('div_conteudo_api');
    if (tipo === 'leitura' || tipo === 'mensagem') {
        divRef?.classList.remove('d-none');
        divCont?.classList.remove('d-none');
    } else {
        divRef?.classList.add('d-none');
        divCont?.classList.add('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function() {

    // --- 1. INICIALIZAÇÃO CHOICES.JS (O QUE ESTAVA FALTANDO) ---
    const selects = document.querySelectorAll('.choice-select');
    selects.forEach(select => {
        const choices = new Choices(select, {
            searchEnabled: true,
            itemSelectText: 'Selecionar',
            noResultsText: 'Não encontrado',
            noChoicesText: 'Sem opções',
            placeholder: true,
            searchPlaceholderValue: 'Digite para pesquisar...',
            shouldSort: false
        });

        // Escuta a mudança para abrir o campo manual (Visitante)
        select.addEventListener('change', function(event) {
            const inputId = (this.name === 'dirigente_id') ? 'dirigente_manual' : 'pregador_manual';
            toggleManualInput(this, inputId);
        });
    });

    // --- 2. SORTABLE ---
    if(document.getElementById('lista-liturgia')){
        new Sortable(document.getElementById('lista-liturgia'), {
            animation: 150, handle: '.bi-grip-vertical', onEnd: reindexarItens
        });
    }

    // --- 3. EVENTOS DO MODAL ---
    document.getElementById('modal_tipo').onchange = (e) => atualizarCamposModal(e.target.value);

    document.getElementById('btnConfirmarItem').onclick = () => {
        const tipo = document.getElementById('modal_tipo').value;
        const desc = document.getElementById('modal_descricao').value;
        const ref  = document.getElementById('modal_referencia').value;
        const cont = document.getElementById('modal_conteudo_api').value;

        if (!desc) return alert('Insira uma descrição');

        if (itemSendoEditado) {
            itemSendoEditado.querySelector('.in-tipo').value = tipo;
            itemSendoEditado.querySelector('.in-desc').value = desc;
            itemSendoEditado.querySelector('.in-ref').value = ref;
            itemSendoEditado.querySelector('.in-conteudo').value = cont;

            const badge = itemSendoEditado.querySelector('.in-label-tipo');
            badge.innerText = tipo.toUpperCase();
            badge.className = `badge border me-2 in-label-tipo ${coresBadge[tipo] || 'bg-light'}`;
            itemSendoEditado.querySelector('.in-label-desc').innerText = desc;
            itemSendoEditado.querySelector('.in-label-ref').innerText = (tipo === 'leitura' || tipo === 'mensagem') ? ref : '';
            reindexarItens();
        } else {
            adicionarItemLista({tipo, desc, ref, conteudo: cont});
        }
        bootstrap.Modal.getInstance(document.getElementById('modalAddItem')).hide();
        itemSendoEditado = null;
    };

    //API BUSCAR BIBLIA
    /* FUNCIONANDO
    * document.getElementById('btnBuscarBiblia').onclick = async () => {
        const ref = document.getElementById('modal_referencia').value;
        if(!ref) return alert('Insira uma referência');
        const btn = document.getElementById('btnBuscarBiblia');
        btn.disabled = true;
        try {
            const res = await fetch(`https://bible-api.com/${encodeURIComponent(ref)}?translation=almeida`);
            const data = await res.json();
            if(data.text) {
                document.getElementById('modal_conteudo_api').value = data.text.trim();
                document.getElementById('div_conteudo_api').classList.remove('d-none');
            } else alert('Referência não encontrada');
        } catch (e) { alert('Erro na busca'); }
        finally { btn.disabled = false; }
    };*/

	document.getElementById('btnBuscarBiblia').onclick = async () => {
		const refInput = document.getElementById('modal_referencia').value.trim();
		if (!refInput) return alert('Insira uma referência');

		const btn = document.getElementById('btnBuscarBiblia');
		btn.disabled = true;

		try {
			// CHAMA SUA ROTA INTERNA DO MVC
			// Ajuste a URL para o padrão do seu sistema (ex: /liturgia/buscarTexto?ref=...)
			const url = `buscarTexto?ref=${encodeURIComponent(refInput)}`;

			const res = await fetch(url);
			if (!res.ok) throw new Error('Erro na requisição');

			const data = await res.json();

			if (data.success) {
				// Formata os versículos vindos do seu banco
				const textoFormatado = data.versiculos.map(v => {
					return `${v.versiculo}. ${v.texto.trim()}`;
				}).join('\n');

				document.getElementById('modal_conteudo_api').value = textoFormatado;
				document.getElementById('div_conteudo_api').classList.remove('d-none');
			} else {
				alert(data.error || 'Referência não encontrada.');
			}

		} catch (e) {
			console.error(e);
			alert('Erro ao buscar no banco interno. Verifique a referência.');
		} finally {
			btn.disabled = false;
		}
    };

    // --- 4. CARREGAMENTO INICIAL ---
    <?php if(!empty($liturgia['itens'])): ?>
        try {
            const jsonItens = <?= json_encode(array_values($liturgia['itens'])) ?>;
            jsonItens.forEach(it => adicionarItemLista(it));
        } catch (e) { console.error("Erro ao carregar itens:", e); }
    <?php else: ?>
        const templatePadrao = [
            { tipo: 'texto', desc: 'Notas e Avisos' },
            { tipo: 'oracao', desc: 'Oração de Invocação' },
            { tipo: 'leitura', desc: 'Leitura Bíblica de Abertura' },
            { tipo: 'oracao', desc: 'Oração de Confissão' },
            { tipo: 'hino', desc: 'Hino / Cântico' },
            { tipo: 'leitura', desc: 'Leitura Bíblica' },
            { tipo: 'oracao', desc: 'Oração de Intercessão' },
            { tipo: 'hino', desc: 'Hino / Cântico' },
            { tipo: 'leitura', desc: 'Leitura Bíblica' },
            { tipo: 'hino', desc: 'Ofertas e Dízimos' },
            { tipo: 'oracao', desc: 'Oração de Gratidão' },
            { tipo: 'mensagem', desc: 'Exposição da Palavra (Sermão)' },
            { tipo: 'hino', desc: 'Hino de Encerramento' },
            { tipo: 'oracao', desc: 'Benção Apostólica' },
            { tipo: 'texto', desc: 'Amém Tríplice' },
            { tipo: 'texto', desc: 'Pósludio' },
            { tipo: 'texto', desc: 'Saudação aos Visitantes' },
            { tipo: 'texto', desc: 'Cumprimentos à porta' }
        ];
        templatePadrao.forEach(it => adicionarItemLista(it));
    <?php endif; ?>
});
</script>
