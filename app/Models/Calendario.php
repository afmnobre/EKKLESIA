<?php

namespace App\Models;

use App\Core\Database;
use PDO;

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

        // Define o caminho dinâmico baseado no seu método uploadLogo()
        if (!empty($dadosIgreja['igreja_logo'])) {
            // O caminho segue a estrutura: assets/uploads/{id}/logo/{arquivo}
            $logoIgrejaPath = url("assets/uploads/{$igrejaId}/logo/" . $dadosIgreja['igreja_logo']);
        } else {
            // Fallback caso não tenha logo
            $logoIgrejaPath = url('public/assets/img/logo_igreja_calendario.png');
        }

        $anoReferencia = isset($_GET['start']) ? date('Y', strtotime($_GET['start'])) : date('Y');

        // --- 1. PROGRAMAÇÃO RECORRENTE ---
        $sqlProg = "SELECT * FROM igrejas_programacao WHERE programacao_igreja_id = ? AND programacao_status = 'Ativo'";
        $stP = $this->db->prepare($sqlProg);
        $stP->execute([$igrejaId]);

        $diasMapa = [
            'Domingo' => 0, 'Segunda' => 1, 'Terça' => 2, 'Quarta' => 3,
            'Quinta' => 4, 'Sexta' => 5, 'Sábado' => 6
        ];

        while ($p = $stP->fetch(PDO::FETCH_ASSOC)) {
            $diaSemanaNumero = $diasMapa[$p['programacao_dia_semana']];

            if ($p['programacao_recorrencia_mensal'] == 0) {
                $eventos[] = [
                    'title'         => $p['programacao_titulo'],
                    'startTime'     => $p['programacao_hora'],
                    'daysOfWeek'    => [(int)$diaSemanaNumero],
                    'color'         => '#6c757d',
                    'extendedProps' => [
                        'description' => $p['programacao_descricao'],
                        'image'       => $logoIgrejaPath // Logo da igreja na programação
                    ]
                ];
            } else {
                for ($m = 1; $m <= 12; $m++) {
                    $mesAno = $anoReferencia . '-' . str_pad($m, 2, "0", STR_PAD_LEFT);
                    $dataRec = $this->getDataRecorrenciaFixa($p['programacao_dia_semana'], (int)$p['programacao_recorrencia_mensal'], $mesAno);

                    $isCeia = ($p['programacao_is_ceia'] == 1);
                    $eventos[] = [
                        'title'         => ($isCeia ? "🍷 " : "") . $p['programacao_titulo'],
                        'start'         => $dataRec . 'T' . $p['programacao_hora'],
                        'color'         => $isCeia ? '#721c24' : '#007bff',
                        'extendedProps' => [
                            'description' => $p['programacao_descricao'],
                            'image'       => $logoIgrejaPath
                        ]
                    ];
                }
            }
        }

        // --- 2. ANIVERSÁRIOS DE MEMBROS (Sem alteração) ---
        $sqlM = "SELECT membro_nome, membro_data_nascimento, membro_data_casamento, membro_data_batismo
                 FROM membros WHERE membro_igreja_id = ? AND membro_status = 'Ativo'";
        $stM = $this->db->prepare($sqlM);
        $stM->execute([$igrejaId]);

        while ($m = $stM->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($m['membro_data_nascimento'])) {
                $eventos[] = ['title' => "🎂 " . $m['membro_nome'], 'start' => $anoReferencia . '-' . date('m-d', strtotime($m['membro_data_nascimento'])), 'allDay' => true, 'color' => '#28a745'];
            }
            if (!empty($m['membro_data_casamento'])) {
                $eventos[] = ['title' => "💍 " . $m['membro_nome'], 'start' => $anoReferencia . '-' . date('m-d', strtotime($m['membro_data_casamento'])), 'allDay' => true, 'color' => '#e83e8c'];
            }
            if (!empty($m['membro_data_batismo'])) {
                $eventos[] = ['title' => "🌊 Batismo: " . $m['membro_nome'], 'start' => $anoReferencia . '-' . date('m-d', strtotime($m['membro_data_batismo'])), 'allDay' => true, 'color' => '#17a2b8'];
            }
        }

        // --- 3. EVENTOS DAS SOCIEDADES (Logo da Sociedade) ---
        $sqlS = "SELECT e.sociedade_evento_titulo, e.sociedade_evento_data_hora_inicio,
                        e.sociedade_evento_descricao, s.sociedade_logo
                 FROM sociedades_eventos e
                 LEFT JOIN sociedades s ON e.sociedade_evento_sociedade_id = s.sociedade_id
                 WHERE e.sociedade_evento_igreja_id = ? AND e.sociedade_evento_status != 'Cancelado'";

        $stS = $this->db->prepare($sqlS);
        $stS->execute([$igrejaId]);

        while ($e = $stS->fetch(PDO::FETCH_ASSOC)) {
            $logoSociedade = "";
            if (!empty($e['sociedade_logo'])) {
                $logoSociedade = url("public/assets/uploads/" . $e['sociedade_logo']);
            }

            $eventos[] = [
                'title'         => $e['sociedade_evento_titulo'],
                'start'         => $e['sociedade_evento_data_hora_inicio'],
                'color'         => '#17a2b8',
                'extendedProps' => [
                    'description' => $e['sociedade_evento_descricao'],
                    'image'       => $logoSociedade
                ]
            ];
        }

        // --- 4. EVENTOS GERAIS DA IGREJA (Logo Dinâmico da Igreja) ---
        $sqlG = "SELECT evento_titulo, evento_data_hora_inicio, evento_descricao, evento_cor
                 FROM igrejas_eventos
                 WHERE evento_igreja_id = ?";
        $stG = $this->db->prepare($sqlG);
        $stG->execute([$igrejaId]);

        while ($g = $stG->fetch(PDO::FETCH_ASSOC)) {
            $eventos[] = [
                'title'          => "⛪ " . $g['evento_titulo'],
                'start'          => $g['evento_data_hora_inicio'],
                'color'          => $g['evento_cor'] ?? '#0B1C2D',
                'extendedProps'  => [
                    'description' => $g['evento_descricao'],
                    'image'       => $logoIgrejaPath // AGORA DINÂMICO!
                ]
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
