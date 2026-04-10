<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Backup
{
    private $db;
    private $path;

    public function __construct()
    {
        $this->db = Database::getInstance();
        // Caminho seguro respeitando o open_basedir do servidor
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/EKKLESIA/public/assets/dbbkp/';

        if (!file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    public function getPath() { return $this->path; }

    public function listarTodos()
    {
        if (!file_exists($this->path)) return [];
        $arquivos = [];
        $iterator = new \DirectoryIterator($this->path);
        foreach ($iterator as $file) {
            if (!$file->isDot() && $file->getExtension() === 'sql') {
                $arquivos[] = [
                    'nome'      => $file->getFilename(),
                    'tamanho'   => round($file->getSize() / 1024 / 1024, 2) . ' MB',
                    'data'      => date("d/m/Y H:i:s", $file->getMTime()),
                    'timestamp' => $file->getMTime()
                ];
            }
        }
        usort($arquivos, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
        return $arquivos;
    }

    /**
     * Gera o Backup usando PHP Puro (PDO)
     */
    public function gerarBackupPDO($nomeArquivo)
    {
        $tabelas = [];
        $result = $this->db->query("SHOW TABLES");
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $tabelas[] = $row[0];
        }

        $sqlScript = "-- Backup EKKLESIA Gerado em " . date('d/m/Y H:i:s') . "\n";
        $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tabelas as $tabela) {
            // Estrutura da Tabela
            $res = $this->db->query("SHOW CREATE TABLE $tabela");
            $row = $res->fetch(PDO::FETCH_NUM);
            $sqlScript .= "\n\n" . $row[1] . ";\n\n";

            // Dados da Tabela
            $res = $this->db->query("SELECT * FROM $tabela");
            $colunas = $res->columnCount();

            while ($row = $res->fetch(PDO::FETCH_NUM)) {
                $sqlScript .= "INSERT INTO $tabela VALUES(";
                for ($j = 0; $j < $colunas; $j++) {
                    if (isset($row[$j])) {
                        $sqlScript .= "'" . str_replace("\n", "\\n", addslashes($row[$j])) . "'";
                    } else {
                        $sqlScript .= "NULL";
                    }
                    if ($j < ($colunas - 1)) $sqlScript .= ",";
                }
                $sqlScript .= ");\n";
            }
        }

        $sqlScript .= "\nSET FOREIGN_KEY_CHECKS=1;";
        return file_put_contents($this->path . $nomeArquivo, $sqlScript);
    }

    public function deletarArquivo($nome)
    {
        $arquivo = $this->path . $nome;
        if (file_exists($arquivo) && strpos($nome, 'backup_ekklesia_') === 0) {
            return unlink($arquivo);
        }
        return false;
    }
}
