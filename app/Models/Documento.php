<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Documento
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // --- CATEGORIAS ---
    public function getCategorias($igrejaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM documentos_categorias WHERE documento_categoria_igreja_id = ? ORDER BY documento_categoria_nome ASC");
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function salvarCategoria($data)
	{
		if (!empty($data['id'])) {
			$sql = "UPDATE documentos_categorias SET documento_categoria_nome = ?
					WHERE documento_categoria_id = ? AND documento_categoria_igreja_id = ?";
			return $this->db->prepare($sql)->execute([$data['nome'], $data['id'], $data['igreja_id']]);
		} else {
			$sql = "INSERT INTO documentos_categorias (documento_categoria_igreja_id, documento_categoria_nome) VALUES (?, ?)";
			return $this->db->prepare($sql)->execute([$data['igreja_id'], $data['nome']]);
		}
	}

    // --- DOCUMENTOS ---
    public function listar($igrejaId)
    {
        $sql = "SELECT d.*, c.documento_categoria_nome
                FROM documentos d
                LEFT JOIN documentos_categorias c ON d.documento_documento_categoria_id = c.documento_categoria_id
                WHERE d.documento_igreja_id = ?
                ORDER BY d.documento_data_referencia DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvarDocumento($data)
    {
        $sql = "INSERT INTO documentos (documento_igreja_id, documento_documento_categoria_id, documento_nome, documento_descricao, documento_data_referencia, documento_status)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['igreja_id'],
            $data['categoria_id'],
            $data['nome'],
            $data['descricao'],
            $data['data_referencia'],
            $data['status']
        ]);
        return $this->db->lastInsertId();
    }

    public function salvarArquivo($documentoId, $nomeOriginal, $caminho, $tipo)
    {
        $sql = "INSERT INTO documentos_arquivos (documento_arquivo_documento_id, documento_arquivo_nome, documento_arquivo_caminho, documento_arquivo_tipo, documento_arquivo_data)
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->prepare($sql)->execute([$documentoId, $nomeOriginal, $caminho, $tipo]);
    }

    public function getArquivos($documentoId)
    {
        $stmt = $this->db->prepare("SELECT * FROM documentos_arquivos WHERE documento_arquivo_documento_id = ?");
        $stmt->execute([$documentoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function listarAgrupado($igrejaId)
	{
		$sql = "SELECT d.*, c.documento_categoria_nome
				FROM documentos d
				LEFT JOIN documentos_categorias c ON d.documento_documento_categoria_id = c.documento_categoria_id
				WHERE d.documento_igreja_id = ?
				ORDER BY c.documento_categoria_nome ASC, d.documento_data_referencia DESC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$agrupado = [];
		foreach ($rows as $row) {
			$categoria = $row['documento_categoria_nome'] ?? 'Sem Categoria';
			$agrupado[$categoria][] = $row;
		}
		return $agrupado;
	}

	public function excluirArquivo($arquivoId, $igrejaId)
	{
		// Primeiro buscamos o caminho para deletar o arquivo físico
		$stmt = $this->db->prepare("
			SELECT da.documento_arquivo_caminho, d.documento_documento_categoria_id
			FROM documentos_arquivos da
			JOIN documentos d ON da.documento_arquivo_documento_id = d.documento_id
			WHERE da.documento_arquivo_id = ? AND d.documento_igreja_id = ?
		");
		$stmt->execute([$arquivoId, $igrejaId]);
		$arquivo = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($arquivo) {
			$raiz = dirname(__DIR__, 2);
			$caminhoFisico = $raiz . "/public/assets/uploads/{$igrejaId}/documentos/{$arquivo['documento_documento_categoria_id']}/{$arquivo['documento_arquivo_caminho']}";

			if (file_exists($caminhoFisico)) {
				unlink($caminhoFisico);
			}

			$stmtDel = $this->db->prepare("DELETE FROM documentos_arquivos WHERE documento_arquivo_id = ?");
			return $stmtDel->execute([$arquivoId]);
		}
		return false;
	}

	public function excluirDocumentoCompleto($id, $igrejaId)
	{
		// 1. Buscar todos os arquivos deste documento para apagar do disco
		$stmt = $this->db->prepare("SELECT * FROM documentos_arquivos WHERE documento_arquivo_documento_id = ?");
		$stmt->execute([$id]);
		$arquivos = $stmt->fetchAll();

		// 2. Apagar arquivos físicos (opcional, mas recomendado para não lotar o servidor)
		foreach ($arquivos as $arq) {
			$this->excluirArquivo($arq['documento_arquivo_id'], $igrejaId);
		}

		// 3. Apagar o registro do documento (as chaves estrangeiras devem estar em CASCADE no banco)
		$stmtDel = $this->db->prepare("DELETE FROM documentos WHERE documento_id = ? AND documento_igreja_id = ?");
		return $stmtDel->execute([$id, $igrejaId]);
	}

}
