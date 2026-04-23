<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class PortalMembro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

	public function registrarPendente($dados) {
		try {
			// 1. Inserção na tabela de Membros
			// Adicionada a coluna membro_data_casamento e corrigida a ordem para garantir membro_data_batismo
			$sqlMembro = "INSERT INTO membros (
				membro_igreja_id,
				membro_nome,
				membro_data_nascimento,
				membro_genero,
				membro_estado_civil,
				membro_rg,
				membro_cpf,
				membro_email,
				membro_senha,
				membro_telefone,
				membro_data_batismo,
				membro_data_casamento,
				membro_status,
				membro_data_criacao,
				membro_aceite_lgpd,
				membro_data_aceite,
				membro_ip_aceite
			) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pendente', NOW(), ?, ?, ?)";

			$stmt = $this->db->prepare($sqlMembro);

			$stmt->execute([
				$dados['igreja_id'],
				$dados['nome'],
				$dados['data_nasc'],
				$dados['sexo'],
				$dados['estado_civil'],
				$dados['rg'],
				$dados['cpf'],
				$dados['email'],
				$dados['senha'],
				$dados['telefone'],
				!empty($dados['data_batismo']) ? $dados['data_batismo'] : null,   // CORREÇÃO DATA BATISMO
				!empty($dados['data_casamento']) ? $dados['data_casamento'] : null, // CORREÇÃO DATA CASAMENTO
				$dados['aceite_lgpd'],
				$dados['data_aceite_lgpd'],
				$dados['ip_aceite_lgpd']
			]);

			$membroId = $this->db->lastInsertId();

			if ($membroId) {
				// 2. Inserção na tabela de Endereços
				// Adicionado membro_endereco_complemento e membro_endereco_igreja_id
				$sqlEnd = "INSERT INTO membros_enderecos (
					membro_endereco_membro_id,
					membro_endereco_igreja_id,
					membro_endereco_rua,
					membro_endereco_numero,
					membro_endereco_complemento,
					membro_endereco_bairro,
					membro_endereco_cidade,
					membro_endereco_estado,
					membro_endereco_cep
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

				$stmtEnd = $this->db->prepare($sqlEnd);
				$stmtEnd->execute([
					$membroId,
					$dados['igreja_id'],            // CORREÇÃO IGREJA_ID NO ENDEREÇO
					$dados['rua'],
					$dados['numero'],
					$dados['complemento'] ?? null,  // CORREÇÃO COMPLEMENTO
					$dados['bairro'],
					$dados['cidade'],
					$dados['estado'],
					$dados['cep']
				]);

				return $membroId;
			}

			return false;
		} catch (\PDOException $e) {
			error_log("ERRO BANCO EKKLESIA: " . $e->getMessage());
			// die($e->getMessage()); // Descomente para ver o erro na tela se falhar
			return false;
		}
	}

    public function saveFoto($membroId, $nomeArquivo) {
        $sql = "INSERT INTO membros_fotos (membro_foto_membro_id, membro_foto_arquivo) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$membroId, $nomeArquivo]);
    }

    public function getIgreja($id) {
        $stmt = $this->db->prepare("SELECT * FROM igrejas WHERE igreja_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPendentes($igrejaId) {
        $sql = "SELECT m.*, f.membro_foto_arquivo
                FROM membros m
                LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
                WHERE m.membro_igreja_id = ? AND m.membro_status = 'Pendente'
                ORDER BY m.membro_id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function alterarStatus($membroId, $status, $registroInterno = null) {
        $sql = "UPDATE membros SET membro_status = ?, membro_registro_interno = ? WHERE membro_id = ?";
        return $this->db->prepare($sql)->execute([$status, $registroInterno, $membroId]);
    }

    public function gerarSugestaoRegistro($igrejaId) {
        $sql = "SELECT MAX(membro_id) as ultimo FROM membros";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

        $proximoId = ($resultado['ultimo'] ?? 0) + 1;
        $ano = date('Y');
        $mes = date('m');

        return $igrejaId . $ano . $mes . $proximoId;
    }

    public function getMembroComEndereco($id) {
        $sql = "SELECT m.*, e.*, i.igreja_nome, f.membro_foto_arquivo,
                IF(f.membro_foto_arquivo IS NOT NULL,
                   CONCAT('public/assets/uploads/', m.membro_igreja_id, '/membros/PENDENTE_', m.membro_id, '/', f.membro_foto_arquivo),
                   NULL) as caminho_foto_pendente
                FROM membros m
                JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
                JOIN igrejas i ON m.membro_igreja_id = i.igreja_id
                LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
                WHERE m.membro_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function listarDependentes($idResponsavel) {
        $sql = "SELECT m.*, r.parentesco_grau, f.membro_foto_arquivo
                FROM membros_responsaveis r
                JOIN membros m ON r.parentesco_dependente_id = m.membro_id
                LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
                WHERE r.parentesco_responsavel_id = ?
                ORDER BY m.membro_nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idResponsavel]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMembroCompleto($membroId) {
        $sql = "SELECT
                    m.*,
                    e.*,
                    f.membro_foto_arquivo,
                    c.cargo_nome,
                    s.sociedade_nome,
                    s.sociedade_logo,
                    ce.classe_nome,
                    ce.classe_id
                FROM membros m
                LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
                LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
                LEFT JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
                LEFT JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
                LEFT JOIN sociedades_membros sm ON m.membro_id = sm.sociedade_membro_membro_id
                LEFT JOIN sociedades s ON sm.sociedade_membro_sociedade_id = s.sociedade_id
                LEFT JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
                LEFT JOIN classes_escola ce ON cm.classe_membro_classe_id = ce.classe_id
                WHERE m.membro_id = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$membroId]);
        $membro = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($membro) {
            // Buscamos as presenças do ano atual independente de estar em uma classe no momento
            $membro['presencas'] = $this->getPresencasAnuais($membroId);
        }

        return $membro;
    }

    public function getPresencasAnuais($membroId) {
        $anoAtual = date('Y');

        $sql = "SELECT
                    p.presenca_data,
                    p.presenca_status,
                    c.classe_nome
                FROM classes_presencas p
                JOIN classes_escola c ON p.presenca_classe_id = c.classe_id
                WHERE p.presenca_membro_id = ?
                AND YEAR(p.presenca_data) = ?
                ORDER BY p.presenca_data DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$membroId, $anoAtual]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarPorTelefone($telefone) {
        $sql = "SELECT * FROM membros
                WHERE REPLACE(REPLACE(REPLACE(REPLACE(membro_telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?
                AND membro_status != 'Rejeitado'
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$telefone]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function inserirMembro($dados) {
        $sql = "INSERT INTO membros (" . implode(',', array_keys($dados)) . ")
                VALUES (" . implode(',', array_fill(0, count($dados), '?')) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($dados));
        return $this->db->lastInsertId();
    }

    public function vincularResponsavel($dados) {
        $sql = "INSERT INTO membros_responsaveis
                (parentesco_responsavel_id, parentesco_dependente_id, parentesco_grau)
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['parentesco_responsavel_id'],
            $dados['parentesco_dependente_id'],
            $dados['parentesco_grau']
        ]);
    }

    public function excluirMembroCompleto($membroId)
    {
        $stmt1 = $this->db->prepare("DELETE FROM membros_enderecos WHERE membro_endereco_membro_id = :id");
        $stmt1->bindValue(':id', $membroId, \PDO::PARAM_INT);
        $stmt1->execute();

        $stmt2 = $this->db->prepare("DELETE FROM membros WHERE membro_id = :id");
        $stmt2->bindValue(':id', $membroId, \PDO::PARAM_INT);

        return $stmt2->execute();
    }

    // Busca membro pelo e-mail e igreja
	// Mantemos o getByEmail caso você precise dele em outra parte do sistema
    public function getByEmail($email, $igreja_id) {
        $sql = "SELECT * FROM membros WHERE membro_email = :email AND membro_igreja_id = :igreja_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'igreja_id' => $igreja_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // BUSCA POR TELEFONE (Limpando caracteres especiais do banco para comparar)
	public function validarMembroParaRecuperacao($telefone, $nascimento, $igreja_id) {
		$sql = "SELECT * FROM membros
				WHERE REPLACE(REPLACE(REPLACE(REPLACE(membro_telefone, '(', ''), ')', ''), '-', ''), ' ', '') = :telefone
				AND membro_data_nascimento = :nascimento
				AND membro_igreja_id = :igreja_id";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'telefone'  => $telefone,
			'nascimento' => $nascimento, // Formato YYYY-MM-DD vindo do input date
			'igreja_id'  => $igreja_id
		]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

    // Salva o token de reset no banco
    public function setResetToken($id, $token, $expira) {
        $sql = "UPDATE membros SET membro_reset_token = :token, membro_reset_expira = :expira WHERE membro_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token, 'expira' => $expira, 'id' => $id]);
    }

    // Busca membro pelo token (e verifica se não expirou)
    public function getByToken($token) {
        $sql = "SELECT * FROM membros WHERE membro_reset_token = :token AND membro_reset_expira > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Atualiza a senha definitiva e limpa o token
    public function updatePassword($id, $novaSenhaHash) {
        $sql = "UPDATE membros SET
                    membro_senha = :senha,
                    membro_reset_token = NULL,
                    membro_reset_expira = NULL
                WHERE membro_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['senha' => $novaSenhaHash, 'id' => $id]);
    }

	public function getEmprestimosMensais($membroId) {
		$sql = "SELECT e.*, l.livro_titulo, l.livro_autor
				FROM biblioteca_emprestimos e
				JOIN biblioteca_livros l ON e.emprestimo_livro_id = l.livro_id
				WHERE e.emprestimo_membro_id = :membro_id
				ORDER BY e.emprestimo_data_saida DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute(['membro_id' => $membroId]);
		$dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$agrupados = [];
		foreach ($dados as $item) {
			// Extrai o mês (ex: 04) da data de saída
			$mes = date('m', strtotime($item['emprestimo_data_saida']));
			$agrupados[$mes][] = $item;
		}
		return $agrupados;
	}

}
