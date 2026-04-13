<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use DateTime;

class Calendario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getEventos($igrejaId)
    {
        $eventos = [];

        // --- BUSCA LOGO DA IGREJA PARA O CALENDÁRIO ---
        $sqlIgreja = "SELECT igreja_logo FROM igrejas WHERE igreja_id = ?";
        $stI = $this->db->prepare($sqlIgreja);
        $stI->execute([$igrejaId]);
        $dadosIgreja = $stI->fetch(PDO::FETCH_ASSOC);

        if (!empty($dadosIgreja['igreja_logo'])) {
            $logoIgrejaPath = url("assets/uploads/{$igrejaId}/logo/" . $dadosIgreja['igreja_logo']);
        } else {
            $logoIgrejaPath = url('public/assets/img/logo_igreja_calendario.png');
        }

        $anoReferencia = isset($_GET['start']) ? date('Y', strtotime($_GET['start'])) : date('Y');

        $diasMapa = ['Domingo' => 0, 'Segunda' => 1, 'Terça' => 2, 'Quarta' => 3, 'Quinta' => 4, 'Sexta' => 5, 'Sábado' => 6];
        $mapaIngles = ['Domingo' => 'Sunday', 'Segunda' => 'Monday', 'Terça' => 'Tuesday', 'Quarta' => 'Wednesday', 'Quinta' => 'Thursday', 'Sexta' => 'Friday', 'Sábado' => 'Saturday'];

		// --- 1. BUSCA ESCALAS REAIS (Com Foto do Membro da Reunião) ---
        $escalasExistentes = [];
        // Ajustei a query para buscar a foto do membro vinculado à escala/local
        $sqlEsc = "SELECT l.data_evento, l.local_nome_endereco, p.programacao_id, p.programacao_titulo,
                          p.programacao_hora, p.programacao_descricao,
                          m.membro_nome, m.membro_registro_interno, m.membro_id,
                          f.membro_foto_arquivo
                   FROM igrejas_programacao_locais l
                   INNER JOIN igrejas_programacao p ON l.programacao_id = p.programacao_id
                   LEFT JOIN membros m ON l.membro_id = m.membro_id
                   LEFT JOIN membros_fotos f ON f.membro_foto_membro_id = m.membro_id
                   WHERE p.programacao_igreja_id = ?";

        $stEsc = $this->db->prepare($sqlEsc);
        $stEsc->execute([$igrejaId]);

        while ($e = $stEsc->fetch(PDO::FETCH_ASSOC)) {
            $escalasExistentes[$e['programacao_id']][] = $e['data_evento'];

            // Se houver membro e foto na escala, usa ela. Senão, usa a logo da igreja.
            $fotoMembroEscala = (!empty($e['membro_foto_arquivo']))
                ? url("public/assets/uploads/{$igrejaId}/membros/{$e['membro_registro_interno']}/{$e['membro_foto_arquivo']}")
                : $logoIgrejaDefault;

            $eventos[] = [
                'title'          => "🏠 " . $e['programacao_titulo'],
                'start'          => $e['data_evento'] . 'T' . $e['programacao_hora'],
                'color'          => '#fd7e14',
                'extendedProps'  => [
                    'tipo'        => 'escala',
                    'description' => $e['programacao_descricao'] . ($e['membro_nome'] ? " (Responsável: {$e['membro_nome']})" : ""),
                    'local'       => $e['local_nome_endereco'],
                    'image'       => $fotoMembroEscala
                ]
            ];
        }

        // --- 2. PROGRAMAÇÃO RECORRENTE ---
        $sqlProg = "SELECT * FROM igrejas_programacao WHERE programacao_igreja_id = ? AND programacao_status = 'Ativo'";
        $stP = $this->db->prepare($sqlProg);
        $stP->execute([$igrejaId]);

        while ($p = $stP->fetch(PDO::FETCH_ASSOC)) {
            $isExterno = ($p['programacao_is_externo'] == 1);
            $isCeia = ($p['programacao_is_ceia'] == 1);
            $diaSemanaNome = $p['programacao_dia_semana'];
            $diaSemanaIngles = $mapaIngles[$diaSemanaNome];

            if (!$isExterno) {
                // Evento Interno: Usa daysOfWeek (Leve)
                if ($p['programacao_recorrencia_mensal'] == 0) {
                    $eventos[] = [
                        'title'          => ($isCeia ? "🍷 " : "") . $p['programacao_titulo'],
                        'startTime'      => $p['programacao_hora'],
                        'daysOfWeek'     => [(int)$diasMapa[$diaSemanaNome]],
                        'color'          => $isCeia ? '#721c24' : '#6c757d',
                        'extendedProps'  => [
                            'tipo' => 'igreja', 'description' => $p['programacao_descricao'], 'local' => "Na Igreja", 'image' => $logoIgrejaPath
                        ]
                    ];
                } else {
                    for ($m = 1; $m <= 12; $m++) {
                        $mesAnoStr = $anoReferencia . '-' . str_pad($m, 2, "0", STR_PAD_LEFT);
                        $dataRec = $this->getDataRecorrenciaFixa($diaSemanaNome, (int)$p['programacao_recorrencia_mensal'], $mesAnoStr);
                        $eventos[] = [
                            'title' => ($isCeia ? "🍷 " : "") . $p['programacao_titulo'],
                            'start' => $dataRec . 'T' . $p['programacao_hora'],
                            'color' => $isCeia ? '#721c24' : '#007bff',
                            'extendedProps' => ['tipo' => 'igreja', 'description' => $p['programacao_descricao'], 'local' => "Na Igreja", 'image' => $logoIgrejaPath]
                        ];
                    }
                }
            } else {
                // Evento Externo: Geramos datas manuais filtrando as escalas reais para não duplicar
                for ($m = 1; $m <= 12; $m++) {
                    $mesAnoStr = $anoReferencia . '-' . str_pad($m, 2, "0", STR_PAD_LEFT);
                    $datasPossiveis = [];

                    if ($p['programacao_recorrencia_mensal'] == 0) {
                        $date = new DateTime($mesAnoStr . '-01');
                        $date->modify("first $diaSemanaIngles");
                        if ($date->format('m') != str_pad($m, 2, "0", STR_PAD_LEFT)) { $date->modify('+1 week'); }
                        while ($date->format('Y-m') == $mesAnoStr) {
                            $datasPossiveis[] = $date->format('Y-m-d');
                            $date->modify('+1 week');
                        }
                    } else {
                        $datasPossiveis[] = $this->getDataRecorrenciaFixa($diaSemanaNome, (int)$p['programacao_recorrencia_mensal'], $mesAnoStr);
                    }

                    foreach ($datasPossiveis as $dataDestaOcorrencia) {
                        $temEscala = isset($escalasExistentes[$p['programacao_id']]) && in_array($dataDestaOcorrencia, $escalasExistentes[$p['programacao_id']]);
                        if (!$temEscala) {
                            $eventos[] = [
                                'title'          => ($isCeia ? "🍷 " : "📍 ") . $p['programacao_titulo'],
                                'start'          => $dataDestaOcorrencia . 'T' . $p['programacao_hora'],
                                'color'          => '#6f42c1',
                                'extendedProps'  => [
                                    'tipo'        => 'igreja',
                                    'description' => $p['programacao_descricao'],
                                    'local'       => $p['programacao_local_nome'] ?: "Local Externo a definir",
                                    'image'       => $logoIgrejaPath
                                ]
                            ];
                        }
                    }
                }
            }
        }

        // --- 3. ANIVERSÁRIOS DE MEMBROS (Completo: Nascimento, Casamento e Batismo) ---
        $sqlM = "SELECT m.membro_id, m.membro_igreja_id, m.membro_nome, m.membro_registro_interno,
                        m.membro_data_nascimento, m.membro_data_casamento, m.membro_data_batismo,
                        f.membro_foto_arquivo
                 FROM membros m
                 LEFT JOIN membros_fotos f ON f.membro_foto_membro_id = m.membro_id
                 WHERE m.membro_igreja_id = ? AND m.membro_status = 'Ativo'";

        $stM = $this->db->prepare($sqlM);
        $stM->execute([$igrejaId]);

        while ($m = $stM->fetch(PDO::FETCH_ASSOC)) {
            $fotoCaminho = !empty($m['membro_foto_arquivo'])
                ? url("public/assets/uploads/{$m['membro_igreja_id']}/membros/{$m['membro_registro_interno']}/{$m['membro_foto_arquivo']}")
                : null;

            if (!empty($m['membro_data_nascimento'])) {
                $eventos[] = [
                    'title' => "🎂 " . $m['membro_nome'],
                    'start' => $anoReferencia . '-' . date('m-d', strtotime($m['membro_data_nascimento'])),
                    'allDay' => true, 'color' => '#28a745',
                    'extendedProps' => ['image' => $fotoCaminho, 'tipo' => 'nascimento', 'description' => 'Aniversário de Nascimento']
                ];
            }
            if (!empty($m['membro_data_casamento'])) {
                $eventos[] = [
                    'title' => "💍 " . $m['membro_nome'],
                    'start' => $anoReferencia . '-' . date('m-d', strtotime($m['membro_data_casamento'])),
                    'allDay' => true, 'color' => '#e83e8c',
                    'extendedProps' => ['image' => $fotoCaminho, 'tipo' => 'casamento', 'description' => 'Aniversário de Casamento']
                ];
            }
            if (!empty($m['membro_data_batismo'])) {
                $eventos[] = [
                    'title' => "🌊 Batismo: " . $m['membro_nome'],
                    'start' => $anoReferencia . '-' . date('m-d', strtotime($m['membro_data_batismo'])),
                    'allDay' => true, 'color' => '#17a2b8',
                    'extendedProps' => ['image' => $fotoCaminho, 'tipo' => 'batismo', 'description' => 'Aniversário de Batismo']
                ];
            }
        }

        // --- 4. EVENTOS DAS SOCIEDADES (Com Lógica de Logo Dinâmico) ---
        $sqlS = "SELECT e.*, s.sociedade_logo, s.sociedade_nome FROM sociedades_eventos e
                 LEFT JOIN sociedades s ON e.sociedade_evento_sociedade_id = s.sociedade_id
                 WHERE e.sociedade_evento_igreja_id = ? AND e.sociedade_evento_status != 'Cancelado'";
        $stS = $this->db->prepare($sqlS);
        $stS->execute([$igrejaId]);

        while ($se = $stS->fetch(PDO::FETCH_ASSOC)) {
            $imagemEvento = !empty($se['sociedade_evento_imagem'])
                ? url("public/assets/uploads/sociedades/eventos/" . $se['sociedade_evento_imagem'])
                : (!empty($se['sociedade_logo']) ? url("public/assets/uploads/" . $se['sociedade_logo']) : "");

            $eventos[] = [
                'title'          => $se['sociedade_evento_titulo'],
                'start'          => $se['sociedade_evento_data_hora_inicio'],
                'color'          => '#17a2b8',
                'extendedProps'  => [
                    'tipo' => 'sociedade', 'subtitulo' => $se['sociedade_nome'],
                    'description' => $se['sociedade_evento_descricao'], 'local' => $se['sociedade_evento_local'], 'image' => $imagemEvento
                ]
            ];
        }

        // --- 5. EVENTOS GERAIS DA IGREJA ---
        $sqlG = "SELECT * FROM igrejas_eventos WHERE evento_igreja_id = ?";
        $stG = $this->db->prepare($sqlG);
        $stG->execute([$igrejaId]);

        while ($g = $stG->fetch(PDO::FETCH_ASSOC)) {
            $eventos[] = [
                'title'          => "⛪ " . $g['evento_titulo'],
                'start'          => $g['evento_data_hora_inicio'],
                'color'          => $g['evento_cor'] ?? '#0B1C2D',
                'extendedProps'  => ['description' => $g['evento_descricao'], 'image' => $logoIgrejaPath]
            ];
        }

        return $eventos;
    }

    private function getDataRecorrenciaFixa($diaNome, $semana, $mesAno)
    {
        $mapaIngles = ['Domingo' => 'Sunday', 'Segunda' => 'Monday', 'Terça' => 'Tuesday', 'Quarta' => 'Wednesday', 'Quinta' => 'Thursday', 'Sexta' => 'Friday', 'Sábado' => 'Saturday'];
        $diaIngles = $mapaIngles[$diaNome];
        $ordinais = [1 => 'first', 2 => 'second', 3 => 'third', 4 => 'fourth'];
        return date('Y-m-d', strtotime("{$ordinais[$semana]} $diaIngles of $mesAno"));
    }
}
