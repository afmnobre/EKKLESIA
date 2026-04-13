<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js'></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/shift-away.css" />

<style>
/* --- ESTILOS DE TELA --- */
.fc-theme-standard td,
.fc-theme-standard th,
.fc-theme-standard .fc-scrollgrid {
    border: 1.5px solid #444 !important; /* Linhas mais grossas e visíveis */
}

.fc .fc-daygrid-day-number {
    font-weight: 800 !important; /* Números dos dias em negrito */
    font-size: 1.1em !important;
    color: #000 !important;
    padding: 4px !important;
}

/* --- CONFIGURAÇÃO DE IMPRESSÃO PROFISSIONAL --- */
@media print {
    /* Força orientação Paisagem e remove margens da impressora */
    @page {
        size: A4 landscape;
        margin: 0.5cm !important;
    }

    /* Esconde elementos da interface do sistema */
    .sidebar, .navbar, .btn, .no-print, header, footer,
    .main-header, #footer, .breadcrumb, .content-header {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    /* Reseta o layout para usar 100% do papel sem recuos */
    html, body {
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background-color: #fff !important;
    }

    /* Remove o recuo da sidebar e centraliza o conteúdo */
    body, .main-content, .container-fluid, #calendar-container, main {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        left: 0 !important;
        position: absolute !important; /* Fixa no topo para evitar quebras */
        top: 0 !important;
    }

    /* Ajusta o calendário para preencher a página A4 */
    #calendar {
        width: 100% !important;
        max-height: 18.5cm !important; /* Altura máxima para caber em 1 folha A4 paisagem */
    }

    /* Engrossa as linhas ainda mais para a impressão */
    .fc-theme-standard td,
    .fc-theme-standard th {
        border: 2px solid #000 !important;
    }

    /* Garante que os números dos dias fiquem bem destacados no papel */
    .fc .fc-daygrid-day-number {
        font-weight: 900 !important;
        font-size: 1.2rem !important;
    }

    /* Estilo dos eventos na impressão */
    .fc-event-title {
        font-size: 0.7rem !important;
        font-weight: bold !important;
        white-space: normal !important;
        line-height: 1.1 !important;
    }

    /* Força a impressão das cores de fundo (logos e categorias) */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h3 class="text-secondary mb-0"><i class="bi bi-calendar-event-fill me-2"></i> Calendário Geral</h3>
            <p class="text-muted small fw-bold text-uppercase mb-0">Programação, Aniversários e Eventos</p>
        </div>
        <button onclick="window.print()" class="btn btn-outline-dark shadow-sm">
            <i class="bi bi-printer me-2"></i> Imprimir para Boletim
        </button>
    </div>

    <div id="calendar-container">
        <div id='calendar'></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana'
        },
        events: <?php if (isset($eventos)): ?>
                    <?= json_encode($eventos) ?>
                <?php else: ?>
                    '<?= url("calendario/feed") ?>'
                <?php endif; ?>,
        eventDisplay: 'block',
        height: 'auto',

        // Formatação da hora: 24h
        displayEventTime: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },

        // Renderização customizada (Icone/Foto na linha do calendário)
        eventContent: function(arg) {
            let container = document.createElement('div');
            container.style.display = 'flex';
            container.style.alignItems = 'center';
            container.style.overflow = 'hidden';
            container.style.padding = '2px';

            if (arg.event.extendedProps.image) {
                let img = document.createElement('img');
                img.src = arg.event.extendedProps.image;
                img.style.width = '30px';
                img.style.height = '30px';
                img.style.marginRight = '5px';
                img.style.borderRadius = '2px';
                img.style.flexShrink = '0';
                container.appendChild(img);
            }

            let textNode = document.createElement('div');
            textNode.style.overflow = 'hidden';
            textNode.style.textOverflow = 'ellipsis';
            textNode.style.whiteSpace = 'nowrap';

            let timeText = arg.timeText ? '<b>' + arg.timeText + '</b> ' : '';
            textNode.innerHTML = timeText + arg.event.title;

            container.appendChild(textNode);
            return { domNodes: [container] };
        },

        // Função de Tooltip (Pop-up ao passar o mouse ou tocar)
		eventDidMount: function(info) {
			let props = info.event.extendedProps;
			let content = "";
			let borderColor = "#28a745"; // Verde padrão (Nascimento)

			// Ajusta a cor da borda baseado no tipo
			if (props.tipo === 'casamento') borderColor = "#e83e8c";
			if (props.tipo === 'batismo') borderColor = "#17a2b8";

			if (info.event.allDay) {
				// --- LAYOUT PARA ANIVERSARIANTES ---
				content = `
					<div style="text-align:center; padding:8px; min-width:150px;">
						${props.image ?
							`<img src="${props.image}" style="width:70px; height:70px; object-fit:cover; border-radius:50%; border:3px solid ${borderColor}; margin-bottom:8px; box-shadow:0 2px 4px rgba(0,0,0,0.2);">`
							: `<div style="width:70px; height:70px; background:#555; border-radius:50%; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; border:3px solid ${borderColor};"><i class="bi bi-person text-white fs-2"></i></div>`
						}
						<div style="font-weight:bold; font-size:1rem;">${info.event.title}</div>
						<div style="font-size:0.8rem; color:#eee;">🎉 ${props.description || 'Comemoração'}</div>
					</div>`;
			} else {
				// --- LAYOUT PARA PROGRAMAÇÃO / SOCIEDADES ---
				content = `
					<div style="padding:10px; min-width:200px; max-width:280px;">
						<div style="display:flex; align-items:center; margin-bottom:8px; border-bottom:1px solid rgba(255,255,255,0.2); padding-bottom:5px;">
							${props.image ? `<img src="${props.image}" style="width:40px; height:40px; object-fit:cover; border-radius:5px; margin-right:10px;">` : ''}
							<div>
								<div style="font-weight:bold; color:#ffc107; font-size:0.95rem; line-height:1.1;">${info.event.title}</div>
								<small style="opacity:0.8;">${props.subtitulo || 'Programação'}</small>
							</div>
						</div>

						<div style="font-size:0.85rem; margin-bottom:8px; line-height:1.3;">
							${props.description ? props.description : '<i>Sem descrição disponível.</i>'}
						</div>

						${props.local ? `
							<div style="font-size:0.75rem; background:rgba(0,0,0,0.2); padding:6px; border-radius:4px; margin-top:5px;">
								<i class="bi bi-geo-alt-fill text-danger"></i> <b>Local:</b> ${props.local}
							</div>
						` : ''}

						<div style="font-size:0.75rem; margin-top:8px; font-weight:bold; color:#fff;">
							<i class="bi bi-clock"></i> Início: ${info.timeText || 'Horário não definido'}
						</div>
					</div>`;
			}

			// Inicializa o Tippy
			tippy(info.el, {
				content: content,
				allowHTML: true,
				theme: 'material',
				placement: 'top',
				animation: 'shift-away',
				touch: ['hold', 500],
				interactive: true
			});
		},

        eventClassNames: function(arg) {
            if (arg.event.allDay) {
                return [ 'fw-bold' ];
            }
            return [];
        }
    });

    calendar.render();

    window.onbeforeprint = function() {
        calendar.setOption('height', 'auto');
        calendar.updateSize();
    };
});
</script>

<style>
    /* Estilização do Balão (Verde IPB Dark) */
    .tippy-box {
        background-color: #002d19 !important;
        color: white !important;
        border-radius: 10px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3) !important;
    }
    .tippy-arrow {
        color: #002d19 !important;
    }
    .fc-event {
        cursor: help !important; /* Indica que há informação ao passar o mouse */
    }
</style>
