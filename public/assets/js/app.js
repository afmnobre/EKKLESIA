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
