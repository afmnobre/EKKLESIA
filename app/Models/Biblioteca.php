<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Biblioteca
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getIgrejaDetalhes($igrejaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM igrejas WHERE igreja_id = ?");
        $stmt->execute([$igrejaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca livros com filtros combinados de Letra, Categoria e Igreja
     */
	public function getLivrosFiltrados($igrejaId, $filtros = []) {
		// Se o segundo parâmetro for uma string (como no seu código antigo),
		// convertemos para o novo formato de array para manter compatibilidade.
		if (!is_array($filtros)) {
			$letra = $filtros;
			$filtros = ['letra' => $letra];
		}

		$sql = "SELECT l.*,
				(SELECT COUNT(*) FROM biblioteca_emprestimos e
				 WHERE e.emprestimo_livro_id = l.livro_id
				 AND e.emprestimo_status = 'Ativo') as total_emprestados
				FROM biblioteca_livros l
				WHERE l.livro_igreja_id = ?";

		$params = [$igrejaId];

		// 1. Filtro por Título (%nome%)
		if (!empty($filtros['titulo'])) {
			$sql .= " AND l.livro_titulo LIKE ?";
			$params[] = "%" . $filtros['titulo'] . "%";
		}

		// 2. Filtro por Autor (Exato)
		if (!empty($filtros['autor'])) {
			$sql .= " AND l.livro_autor = ?";
			$params[] = $filtros['autor'];
		}

		// 3. Filtro por Editora (Exato)
		if (!empty($filtros['editora'])) {
			$sql .= " AND l.livro_editora = ?";
			$params[] = $filtros['editora'];
		}

		// 4. Filtro por Categoria (Exato)
		if (!empty($filtros['categoria'])) {
			$sql .= " AND l.livro_categoria = ?";
			$params[] = $filtros['categoria'];
		}

		// 5. Mantendo a lógica de Letras (A-Z ou 0-9)
		if (!empty($filtros['letra'])) {
			if ($filtros['letra'] === '0-9') {
				$sql .= " AND l.livro_titulo REGEXP '^[0-9]'";
			} else {
				$sql .= " AND l.livro_titulo LIKE ?";
				$params[] = $filtros['letra'] . '%';
			}
		}

		$sql .= " ORDER BY l.livro_titulo ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function cadastrarLivro($data) {
		// Agora com 10 campos e 10 interrogações
		$sql = "INSERT INTO biblioteca_livros (
					livro_igreja_id,
					livro_titulo,
					livro_autor,
					livro_isbn,
					livro_editora,
					livro_categoria,
					livro_publicacao,
					livro_quantidade,
					livro_capa,
					livro_status
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		try {
			$stmt = $this->db->prepare($sql);
			return $stmt->execute([
				$data['livro_igreja_id'],
				$data['livro_titulo'],
				$data['livro_autor'],
				$data['livro_isbn'],
				$data['livro_editora'],
				$data['livro_categoria'],
				$data['livro_publicacao'],
				$data['livro_quantidade'],
				$data['livro_capa'],
				'Disponível'
			]);
		} catch (\PDOException $e) {
			// Log de erro para debug (opcional, mas ajuda muito)
			// error_log($e->getMessage());
			return false;
		}
	}

	public function excluirLivro($id, $igrejaId) {
		$sql = "DELETE FROM biblioteca_livros WHERE livro_id = ? AND livro_igreja_id = ?";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$id, $igrejaId]);
	}

    public function getLivroPorId($id, $igrejaId) {
        $sql = "SELECT * FROM biblioteca_livros WHERE livro_id = ? AND livro_igreja_id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $igrejaId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

	public function getCategorias($igrejaId)
	{
		// Adicionado categoria_id e categoria_descricao na busca
		$stmt = $this->db->prepare("SELECT categoria_id, categoria_nome, categoria_descricao
									FROM biblioteca_categorias
									WHERE categoria_igreja_id = ?
									ORDER BY categoria_nome ASC");
		$stmt->execute([$igrejaId]);
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result ? $result : [];
	}

	public function atualizarCategoria($data)
	{
		$sql = "UPDATE biblioteca_categorias
				SET categoria_nome = ?, categoria_descricao = ?
				WHERE categoria_id = ? AND categoria_igreja_id = ?";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$data['nome'],
			$data['descricao'],
			$data['id'],
			$data['igreja_id']
		]);
	}

    public function salvarCategoria($data)
    {
        $sql = "INSERT INTO biblioteca_categorias (categoria_igreja_id, categoria_nome, categoria_descricao) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['igreja_id'],
            $data['nome'],
            $data['descricao']
        ]);
    }

    public function atualizarLivro($data) {
        $sql = "UPDATE biblioteca_livros SET
                    livro_titulo = ?,
                    livro_autor = ?,
                    livro_isbn = ?,
                    livro_editora =?,
                    livro_categoria = ?,
                    livro_publicacao = ?,
                    livro_capa = ?
                WHERE livro_id = ? AND livro_igreja_id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['livro_titulo'],
                $data['livro_autor'],
                $data['livro_isbn'],
                $data['livro_editora'],
                $data['livro_categoria'],
                $data['livro_publicacao'],
                $data['livro_capa'],
                $data['livro_id'],
                $data['livro_igreja_id']
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function excluirCategoria($id, $igrejaId)
    {
        $stmt = $this->db->prepare("DELETE FROM biblioteca_categorias WHERE categoria_id = ? AND categoria_igreja_id = ?");
        return $stmt->execute([$id, $igrejaId]);
    }

	// Busca membros da igreja para o select do modal
	public function getMembros($igrejaId) {
		$sql = "SELECT membro_id, membro_nome, membro_telefone FROM membros WHERE membro_igreja_id = ? ORDER BY membro_nome ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function emprestarLivro($data) {
		try {
			$this->db->beginTransaction();

			$sql = "INSERT INTO biblioteca_emprestimos (
				emprestimo_igreja_id,
				emprestimo_livro_id,
				emprestimo_membro_id,
				emprestimo_data_prevista,
				emprestimo_status
			) VALUES (?, ?, ?, ?, 'Ativo')";

			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				$data['igreja_id'],
				$data['livro_id'],
				$data['membro_id'],
				$data['data_prevista']
			]);

			$this->db->prepare("UPDATE biblioteca_livros SET livro_status = 'Emprestado' WHERE livro_id = ?")
					 ->execute([$data['livro_id']]);

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	// Busca empréstimos ativos com dados dos membros e livros
	public function getEmprestimosAtivos($igrejaId) {
		// Adicionado m.membro_telefone ao SELECT
		$sql = "SELECT e.*, l.livro_titulo, m.membro_nome, m.membro_telefone
				FROM biblioteca_emprestimos e
				JOIN biblioteca_livros l ON e.emprestimo_livro_id = l.livro_id
				JOIN membros m ON e.emprestimo_membro_id = m.membro_id
				WHERE e.emprestimo_igreja_id = ? AND e.emprestimo_status != 'Devolvido'
				ORDER BY e.emprestimo_data_saida DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Registra a devolução
	public function devolverLivro($emprestimoId, $igrejaId) {
		$sql = "UPDATE biblioteca_emprestimos
				SET emprestimo_status = 'Devolvido', emprestimo_data_devolucao = NOW()
				WHERE emprestimo_id = ? AND emprestimo_igreja_id = ?";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$emprestimoId, $igrejaId]);
	}

	public function processarDevolucaoEmLote($ids, $igrejaId) {
		// Garante que os IDs sejam um array
		if (!is_array($ids)) {
			$ids = explode(',', $ids);
		}

		// Criamos os placeholders (?, ?, ?) para a query
		$placeholders = implode(',', array_fill(0, count($ids), '?'));

		// SQL para atualizar data de devolução e status
		// O filtro de igreja_id é uma medida de segurança
		$sql = "UPDATE biblioteca_emprestimos
				SET emprestimo_status = 'Devolvido',
					emprestimo_data_devolucao = NOW()
				WHERE emprestimo_id IN ($placeholders)
				AND emprestimo_igreja_id = ?";

		$stmt = $this->db->prepare($sql);

		// Mesclamos os IDs com o ID da igreja para o execute
		$params = array_merge($ids, [$igrejaId]);

		return $stmt->execute($params);
	}

	public function getEstatisticasDashboard($igrejaId) {
		$res = [];

		// 1. Indicadores Rápidos
		$sqlStats = "SELECT
			(SELECT COUNT(*) FROM biblioteca_livros WHERE livro_igreja_id = ?) as total_livros,
			(SELECT COUNT(*) FROM biblioteca_emprestimos WHERE emprestimo_igreja_id = ? AND emprestimo_status != 'Devolvido') as ativos,
			(SELECT COUNT(*) FROM biblioteca_emprestimos WHERE emprestimo_igreja_id = ? AND emprestimo_status != 'Devolvido' AND (emprestimo_data_prevista < CURDATE())) as atrasados,
			(SELECT COUNT(DISTINCT emprestimo_membro_id) FROM biblioteca_emprestimos WHERE emprestimo_igreja_id = ?) as membros_leitores";

		$stmt = $this->db->prepare($sqlStats);
		$stmt->execute([$igrejaId, $igrejaId, $igrejaId, $igrejaId]);
		$res['stats'] = $stmt->fetch(PDO::FETCH_ASSOC);

		// 2. Dados por Categoria (Gráfico Pizza)
		// Ligação pelo NOME (c.categoria_nome = l.livro_categoria)
		$sqlCat = "SELECT
					c.categoria_nome as label,
					COUNT(l.livro_id) as total
				   FROM biblioteca_livros l
				   INNER JOIN biblioteca_categorias c ON l.livro_categoria = c.categoria_id
				   WHERE l.livro_igreja_id = ?
					 AND l.livro_categoria IS NOT NULL
					 AND l.livro_categoria != ''
				   GROUP BY c.categoria_id, c.categoria_nome";

		$stmt = $this->db->prepare($sqlCat);
		$stmt->execute([$igrejaId]);
		$res['categorias'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// 3. Gráfico por Autor (Top 10 autores com mais livros)
		$sqlAutores = "SELECT livro_autor as label, COUNT(*) as total
					   FROM biblioteca_livros
					   WHERE livro_igreja_id = ?
					   GROUP BY livro_autor
					   ORDER BY total DESC LIMIT 10";
		$stmt = $this->db->prepare($sqlAutores);
		$stmt->execute([$igrejaId]);
		$res['autores'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// 4. Nova query para o gráfico de Editoras
		$sqlEditoras = "SELECT livro_editora as label, COUNT(*) as total
						FROM biblioteca_livros
						WHERE livro_igreja_id = ? AND livro_editora IS NOT NULL AND livro_editora != ''
						GROUP BY livro_editora
						ORDER BY total DESC LIMIT 10";
		$stmt = $this->db->prepare($sqlEditoras);
		$stmt->execute([$igrejaId]);
		$res['editoras'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// 5. Top 5 Populares
		$sqlPop = "SELECT
					l.livro_titulo,
					l.livro_categoria as categoria_nome,
					l.livro_quantidade,
					COUNT(e.emprestimo_id) as total_emprestimos
				   FROM biblioteca_emprestimos e
				   JOIN biblioteca_livros l ON e.emprestimo_livro_id = l.livro_id
				   WHERE e.emprestimo_igreja_id = ?
				   GROUP BY l.livro_id
				   ORDER BY total_emprestimos DESC
				   LIMIT 5";
		$stmt = $this->db->prepare($sqlPop);
		$stmt->execute([$igrejaId]);
		$res['populares'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// 6. Histórico (Garantindo que venha ordenado por mês)
		$sqlHist = "SELECT DATE_FORMAT(emprestimo_data_saida, '%m/%Y') as mes_ano, COUNT(*) as total
					FROM biblioteca_emprestimos
					WHERE emprestimo_igreja_id = ?
					GROUP BY mes_ano
					ORDER BY emprestimo_data_saida ASC LIMIT 12";
		$stmt = $this->db->prepare($sqlHist);
		$stmt->execute([$igrejaId]);
		$res['historico'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $res;
	}

	public function getAutoresDistinct($igrejaId) {
		// Adicionado filtro para ignorar strings vazias
		$stmt = $this->db->prepare("SELECT DISTINCT livro_autor FROM biblioteca_livros WHERE livro_igreja_id = ? AND livro_autor IS NOT NULL AND livro_autor != '' ORDER BY livro_autor ASC");
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getEditorasDistinct($igrejaId) {
		// Adicionado filtro para ignorar strings vazias
		$stmt = $this->db->prepare("SELECT DISTINCT livro_editora FROM biblioteca_livros WHERE livro_igreja_id = ? AND livro_editora IS NOT NULL AND livro_editora != '' ORDER BY livro_editora ASC");
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTodosLivrosRelatorio($igrejaId)
	{
		$sql = "SELECT l.*, c.categoria_nome,
				(SELECT COUNT(*) FROM biblioteca_emprestimos e
				 WHERE e.emprestimo_livro_id = l.livro_id
				 AND e.emprestimo_status = 'Ativo') as total_emprestados
				FROM biblioteca_livros l
				LEFT JOIN biblioteca_categorias c ON l.livro_categoria = c.categoria_id
				WHERE l.livro_igreja_id = ?
				ORDER BY l.livro_titulo ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
