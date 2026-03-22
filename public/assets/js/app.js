    // Define a função no escopo global
    window.initRichEditor = function(selector) {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => {
            // Verifica se o editor já não foi inicializado para evitar erros duplicados
            if (!el.classList.contains('ck-editor-initialized')) {
                ClassicEditor
                    .create(el, {
                        // Configuração simplificada da toolbar
                        toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                        // Remove a contagem de palavras para manter limpo
                        removePlugins: ['WordCount']
                    })
                    .then(editor => {
                        el.classList.add('ck-editor-initialized');

                        // Se você precisar de uma altura específica para UM editor,
                        // você pode setar aqui via JS baseando-se no ID do elemento:
                        // if (el.id === 'historico') { editor.editing.view.change(writer => { writer.setStyle('height', '500px', editor.editing.view.document.getRoot()); }); }
                    })
                    .catch(error => {
                        console.error('Erro ao carregar o editor rico:', error);
                    });
            }
        });
    };

    // Inicialização automática ao carregar a janela
    window.onload = function() {
        // Inicializa todos os textareas que tiverem a classe .rich-text
        window.initRichEditor('.rich-text');
    };


	document.addEventListener("DOMContentLoaded", function() {
		// 1. Persistência dos Menus Abertos (Collapse)
		const sidebar = document.querySelector('.sidebar');

		// Salva qual menu foi clicado
		sidebar.addEventListener('click', function(e) {
			const target = e.target.closest('.menu-link');
			if (target) {
				const menuId = target.getAttribute('href');
				localStorage.setItem('openMenu', menuId);
			}
		});

		// Ao carregar, verifica se existe um menu para abrir
		const openMenuId = localStorage.getItem('openMenu');
		if (openMenuId) {
			const menuElement = document.querySelector(openMenuId);
			if (menuElement) {
				// Abre o menu usando a classe do Bootstrap
				const bsCollapse = new bootstrap.Collapse(menuElement, { toggle: false });
				bsCollapse.show();

				// Ajusta o atributo aria para manter o estilo do CSS (borda azul)
				const parentLink = document.querySelector(`[href="${openMenuId}"]`);
				if (parentLink) parentLink.setAttribute('aria-expanded', 'true');
			}
		}

		// 2. Destaque do Link Ativo (Baseado na URL)
		const currentUrl = window.location.href;
		const allLinks = document.querySelectorAll('.sidebar a');

		allLinks.forEach(link => {
			// Se a URL do link for igual à URL atual do navegador
			if (link.href === currentUrl) {
				link.classList.add('active-link');

				// Se for um link de submenu, garante que o pai esteja aberto
				const parentSubmenu = link.closest('.submenu');
				if (parentSubmenu) {
					const bsCollapse = new bootstrap.Collapse(parentSubmenu, { toggle: false });
					bsCollapse.show();

					const parentId = "#" + parentSubmenu.id;
					const parentLink = document.querySelector(`[href="${parentId}"]`);
					if (parentLink) parentLink.setAttribute('aria-expanded', 'true');
				}
			}
		});
	});

