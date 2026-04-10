<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js'></script>

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
        events: '<?= url("calendario/feed") ?>',
        eventDisplay: 'block',
        height: 'auto',

        // Formatação da hora: 24h
        displayEventTime: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },

        // Renderização customizada com Logo da Sociedade
        eventContent: function(arg) {
            let container = document.createElement('div');
            container.style.display = 'flex';
            container.style.alignItems = 'center';
            container.style.overflow = 'hidden';
            container.style.padding = '2px';

            // Verifica se há uma imagem enviada pelo Model (extendedProps.image)
            if (arg.event.extendedProps.image) {
                let img = document.createElement('img');
                img.src = arg.event.extendedProps.image;
                img.style.width = '30px';
                img.style.height = '30px';
                img.style.marginRight = '5px';
                img.style.borderRadius = '2px';
                img.style.flexShrink = '0'; // Garante que a imagem não seja comprimida
                container.appendChild(img);
            }

            // Cria o elemento de texto (Horário + Título)
            let textNode = document.createElement('div');
            textNode.style.overflow = 'hidden';
            textNode.style.textOverflow = 'ellipsis';
            textNode.style.whiteSpace = 'nowrap';

            // Adiciona o horário se disponível
            let timeText = arg.timeText ? '<b>' + arg.timeText + '</b> ' : '';
            textNode.innerHTML = timeText + arg.event.title;

            container.appendChild(textNode);

            return { domNodes: [container] };
        },

        // Descrição ao passar o mouse
        eventDidMount: function(info) {
            if (info.event.extendedProps.description) {
                info.el.setAttribute('title', info.event.extendedProps.description);
            }
        },

        // Estilo para eventos de dia inteiro (aniversários)
        eventClassNames: function(arg) {
            if (arg.event.allDay) {
                return [ 'fw-bold' ];
            }
            return [];
        }
    });

    calendar.render();

    // Ajuste para impressão
    window.onbeforeprint = function() {
        calendar.setOption('height', 'auto');
        calendar.updateSize();
    };
});
</script>
