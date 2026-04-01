-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/04/2026 às 06:46
-- Versão do servidor: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Versão do PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `EKKLESIA`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `cargo_id` int(11) NOT NULL,
  `cargo_nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`cargo_id`, `cargo_nome`) VALUES
(1, 'Pastor (Ministro da Palavra)'),
(2, 'Pastor Auxiliar'),
(3, 'Seminarista'),
(4, 'Pastor Evangelista'),
(5, 'Presbítero Regente'),
(6, 'Presbítero Docente (Pastor)'),
(7, 'Diácono'),
(8, 'Presidente do Conselho'),
(9, 'Vice-Presidente do Conselho'),
(10, 'Secretário do Conselho'),
(11, 'Tesoureiro'),
(12, 'Líder de Jovens (UMP)'),
(13, 'Líder de Adolescentes (UPA)'),
(14, 'Líder de Crianças (UCP)'),
(15, 'Líder da Escola Dominical'),
(16, 'Professor de Escola Dominical'),
(17, 'Líder da SAF (Sociedade Auxiliadora Feminina)'),
(18, 'Líder da UPH (União Presbiteriana de Homens)'),
(19, 'Líder de Louvor'),
(20, 'Ministro de Música'),
(21, 'Músico / Instrumentista'),
(22, 'Vocalista'),
(23, 'Líder de Evangelismo'),
(24, 'Líder de Missões'),
(25, 'Líder de Ação Social / Diaconia'),
(26, 'Coordenador Administrativo'),
(27, 'Secretário Administrativo'),
(28, 'Tesoureiro Auxiliar'),
(29, 'Zelador / Responsável pela Estrutura'),
(30, 'Equipe de Recepção'),
(31, 'Equipe de Mídia / Som / Projeção');

-- --------------------------------------------------------

--
-- Estrutura para tabela `classes_config`
--

CREATE TABLE `classes_config` (
  `config_id` int(11) NOT NULL,
  `config_igreja_id` int(11) DEFAULT NULL,
  `config_nome` varchar(100) DEFAULT NULL,
  `config_idade_min` int(3) DEFAULT 0,
  `config_idade_max` int(3) DEFAULT 99
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `classes_config`
--

INSERT INTO `classes_config` (`config_id`, `config_igreja_id`, `config_nome`, `config_idade_min`, `config_idade_max`) VALUES
(1, 1, 'Cordeirinhos', 1, 3),
(2, 1, 'Arca de Noé', 4, 6),
(3, 1, 'Josias', 7, 12),
(4, 1, 'Timóteo', 13, 17),
(5, 1, 'Adultos I', 18, 99),
(6, 1, 'Adultos II', 18, 99);

-- --------------------------------------------------------

--
-- Estrutura para tabela `classes_escola`
--

CREATE TABLE `classes_escola` (
  `classe_id` int(11) NOT NULL,
  `classe_igreja_id` int(11) DEFAULT NULL,
  `classe_nome` varchar(100) DEFAULT NULL,
  `classe_professor_id` int(11) DEFAULT NULL,
  `classe_idade_min` int(11) DEFAULT 0,
  `classe_idade_max` int(11) DEFAULT 99,
  `classe_senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `classes_escola`
--

INSERT INTO `classes_escola` (`classe_id`, `classe_igreja_id`, `classe_nome`, `classe_professor_id`, `classe_idade_min`, `classe_idade_max`, `classe_senha`) VALUES
(8, 1, 'Cordeirinhos', 3, 1, 3, NULL),
(9, 1, 'Arca de Noé', 399, 4, 6, NULL),
(10, 1, 'Josias', 405, 7, 12, NULL),
(11, 1, 'Timóteo', 440, 13, 17, NULL),
(12, 1, 'Adultos I', 473, 18, 99, NULL),
(13, 1, 'Adultos II', 408, 18, 99, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `classes_membros`
--

CREATE TABLE `classes_membros` (
  `classe_membro_id` int(11) NOT NULL,
  `classe_membro_classe_id` int(11) DEFAULT NULL,
  `classe_membro_membro_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `classes_membros`
--

INSERT INTO `classes_membros` (`classe_membro_id`, `classe_membro_classe_id`, `classe_membro_membro_id`) VALUES
(1, 8, 423),
(2, 11, 474),
(3, 11, 431),
(4, 11, 468),
(5, 11, 440),
(7, 10, 448),
(8, 10, 393),
(9, 10, 406),
(10, 10, 480),
(11, 10, 416),
(12, 10, 418),
(13, 10, 469),
(14, 13, 390),
(15, 13, 471),
(16, 13, 403),
(17, 13, 415),
(18, 13, 401),
(19, 13, 395),
(20, 13, 419),
(21, 13, 399),
(22, 12, 3),
(23, 12, 434),
(24, 12, 478),
(25, 12, 449),
(26, 12, 461),
(27, 12, 442),
(28, 12, 443),
(29, 12, 421),
(30, 12, 465),
(31, 12, 457),
(32, 9, 460),
(33, 12, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `classes_presencas`
--

CREATE TABLE `classes_presencas` (
  `presenca_id` int(11) NOT NULL,
  `presenca_classe_id` int(11) DEFAULT NULL,
  `presenca_membro_id` int(11) DEFAULT NULL,
  `presenca_data` date DEFAULT NULL,
  `presenca_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `classes_presencas`
--

INSERT INTO `classes_presencas` (`presenca_id`, `presenca_classe_id`, `presenca_membro_id`, `presenca_data`, `presenca_status`) VALUES
(1, 10, 416, '2026-03-21', 1),
(2, 10, 469, '2026-03-21', 1),
(3, 10, 448, '2026-03-21', 0),
(4, 10, 418, '2026-03-21', 1),
(5, 10, 393, '2026-03-21', 0),
(6, 10, 406, '2026-03-21', 1),
(7, 10, 480, '2026-03-21', 1),
(8, 11, 474, '2026-03-21', 1),
(9, 11, 431, '2026-03-21', 1),
(10, 11, 468, '2026-03-21', 0),
(11, 11, 440, '2026-03-21', 1),
(12, 12, 1, '2026-03-21', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `documentos`
--

CREATE TABLE `documentos` (
  `documento_id` int(11) NOT NULL,
  `documento_igreja_id` int(11) DEFAULT NULL,
  `documento_documento_categoria_id` int(11) DEFAULT NULL,
  `documento_nome` varchar(150) DEFAULT NULL,
  `documento_descricao` text DEFAULT NULL,
  `documento_data_referencia` date DEFAULT NULL,
  `documento_status` enum('ativo','arquivado','cancelado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `documentos_arquivos`
--

CREATE TABLE `documentos_arquivos` (
  `documento_arquivo_id` int(11) NOT NULL,
  `documento_arquivo_documento_id` int(11) DEFAULT NULL,
  `documento_arquivo_nome` varchar(150) DEFAULT NULL,
  `documento_arquivo_caminho` varchar(255) DEFAULT NULL,
  `documento_arquivo_tipo` varchar(20) DEFAULT NULL,
  `documento_arquivo_data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `documentos_categorias`
--

CREATE TABLE `documentos_categorias` (
  `documento_categoria_id` int(11) NOT NULL,
  `documento_categoria_igreja_id` int(11) DEFAULT NULL,
  `documento_categoria_nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_categorias`
--

CREATE TABLE `financeiro_categorias` (
  `financeiro_categoria_id` int(11) NOT NULL,
  `financeiro_categoria_igreja_id` int(11) DEFAULT NULL,
  `financeiro_categoria_nome` varchar(100) DEFAULT NULL,
  `financeiro_categoria_tipo` enum('entrada','saida') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_contas`
--

CREATE TABLE `financeiro_contas` (
  `financeiro_conta_id` int(11) NOT NULL,
  `financeiro_conta_igreja_id` int(11) DEFAULT NULL,
  `financeiro_conta_financeiro_categoria_id` int(11) DEFAULT NULL,
  `financeiro_conta_descricao` text DEFAULT NULL,
  `financeiro_conta_valor` decimal(12,2) DEFAULT NULL,
  `financeiro_conta_tipo` enum('entrada','saida') DEFAULT NULL,
  `financeiro_conta_data_vencimento` date DEFAULT NULL,
  `financeiro_conta_pago` tinyint(1) DEFAULT NULL,
  `financeiro_conta_data_pagamento` date DEFAULT NULL,
  `financeiro_conta_reembolso` tinyint(1) DEFAULT 0,
  `financeiro_conta_comprovante` varchar(255) DEFAULT NULL,
  `financeiro_conta_nota_fiscal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_contas_financeiras`
--

CREATE TABLE `financeiro_contas_financeiras` (
  `financeiro_conta_financeira_id` int(11) NOT NULL,
  `financeiro_conta_financeira_igreja_id` int(11) DEFAULT NULL,
  `financeiro_conta_financeira_nome` varchar(100) DEFAULT NULL,
  `financeiro_conta_financeira_tipo` enum('caixa','banco','carteira') DEFAULT NULL,
  `financeiro_conta_financeira_saldo` decimal(12,2) DEFAULT NULL,
  `financeiro_conta_financeira_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_movimentacoes`
--

CREATE TABLE `financeiro_movimentacoes` (
  `financeiro_movimentacao_id` int(11) NOT NULL,
  `financeiro_movimentacao_igreja_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_financeiro_conta_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_financeiro_categoria_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_financeiro_conta_financeira_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_tipo` enum('entrada','saida') DEFAULT NULL,
  `financeiro_movimentacao_valor` decimal(12,2) DEFAULT NULL,
  `financeiro_movimentacao_data` datetime DEFAULT NULL,
  `financeiro_movimentacao_descricao` text DEFAULT NULL,
  `financeiro_movimentacao_origem` enum('manual','pagamento','transferencia') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_pagamentos`
--

CREATE TABLE `financeiro_pagamentos` (
  `financeiro_pagamento_id` int(11) NOT NULL,
  `financeiro_pagamento_igreja_id` int(11) DEFAULT NULL,
  `financeiro_pagamento_financeiro_conta_id` int(11) DEFAULT NULL,
  `financeiro_pagamento_valor` decimal(12,2) DEFAULT NULL,
  `financeiro_pagamento_conta_financeira_id` int(11) DEFAULT NULL,
  `financeiro_pagamento_metodo` enum('dinheiro','pix','cartao_credito','cartao_debito','transferencia') DEFAULT NULL,
  `financeiro_pagamento_documentos` text DEFAULT NULL,
  `financeiro_pagamento_data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_receita_membros`
--

CREATE TABLE `financeiro_receita_membros` (
  `receita_membro_id` int(11) NOT NULL,
  `receita_membro_conta_id` int(11) NOT NULL,
  `receita_membro_categoria_id` int(11) DEFAULT NULL,
  `receita_membro_subcategoria_id` int(11) DEFAULT NULL,
  `receita_membro_usuario_id` int(11) NOT NULL,
  `receita_membro_valor` decimal(10,2) NOT NULL,
  `receita_membro_data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_subcategorias`
--

CREATE TABLE `financeiro_subcategorias` (
  `subcategoria_id` int(11) NOT NULL,
  `subcategoria_igreja_id` int(11) NOT NULL,
  `subcategoria_categoria_id` int(11) NOT NULL,
  `subcategoria_nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `igrejas`
--

CREATE TABLE `igrejas` (
  `igreja_id` int(11) NOT NULL,
  `igreja_nome` varchar(150) DEFAULT NULL,
  `igreja_cnpj` varchar(20) DEFAULT NULL,
  `igreja_endereco` text DEFAULT NULL,
  `igreja_pastor_id` int(11) DEFAULT NULL,
  `igreja_data_criacao` datetime DEFAULT NULL,
  `igreja_logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `igrejas`
--

INSERT INTO `igrejas` (`igreja_id`, `igreja_nome`, `igreja_cnpj`, `igreja_endereco`, `igreja_pastor_id`, `igreja_data_criacao`, `igreja_logo`) VALUES
(1, 'Igreja Presbiteriana do Jardim Girassol', '08.012.132/0001-45', 'Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', 413, '2026-03-18 22:31:04', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `igrejas_liturgias`
--

CREATE TABLE `igrejas_liturgias` (
  `igreja_liturgia_id` int(11) NOT NULL,
  `igreja_liturgia_igreja_id` int(11) NOT NULL,
  `igreja_liturgia_data` datetime NOT NULL,
  `igreja_liturgia_tema` varchar(255) DEFAULT NULL COMMENT 'Ex: Culto de Doutrina, Celebração',
  `igreja_liturgia_pregador_id` int(11) DEFAULT NULL,
  `igreja_liturgia_pregador_nome` varchar(255) DEFAULT NULL,
  `igreja_liturgia_dirigente_id` int(11) DEFAULT NULL,
  `igreja_liturgia_dirigente_nome` varchar(255) DEFAULT NULL,
  `igreja_liturgia_status` enum('rascunho','publicado') DEFAULT 'publicado',
  `igreja_liturgia_data_criacao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `igrejas_liturgias_itens`
--

CREATE TABLE `igrejas_liturgias_itens` (
  `liturgia_item_id` int(11) NOT NULL,
  `liturgia_item_liturgia_id` int(11) NOT NULL,
  `liturgia_item_ordem` int(11) NOT NULL DEFAULT 0,
  `liturgia_item_descricao` varchar(255) NOT NULL COMMENT 'Ex: Leitura Bíblica, Hino, Oração',
  `liturgia_item_tipo` enum('texto','hino','leitura','mensagem','oracao') DEFAULT 'texto',
  `liturgia_item_referencia` varchar(100) DEFAULT NULL COMMENT 'Ex: Salmo 23 ou Hino 100',
  `liturgia_item_conteudo_api` longtext DEFAULT NULL COMMENT 'Texto da bíblia retornado pela API'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `igrejas_mensagens_dominicais`
--

CREATE TABLE `igrejas_mensagens_dominicais` (
  `igreja_mensagem_dominical_id` int(11) NOT NULL,
  `igreja_mensagem_dominical_igreja_id` int(11) NOT NULL,
  `igreja_mensagem_dominical_num_historico` int(11) NOT NULL,
  `igreja_mensagem_dominical_data` date NOT NULL,
  `igreja_mensagem_dominical_autor_id` int(11) NOT NULL,
  `igreja_mensagem_dominical_titulo` varchar(255) NOT NULL,
  `igreja_mensagem_dominical_mensagem` longtext NOT NULL,
  `igreja_mensagem_dominical_status` enum('rascunho','publicado') DEFAULT 'publicado',
  `igreja_mensagem_dominical_data_criacao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `igrejas_programacao`
--

CREATE TABLE `igrejas_programacao` (
  `programacao_id` int(11) NOT NULL,
  `programacao_igreja_id` int(11) NOT NULL,
  `programacao_titulo` varchar(150) NOT NULL,
  `programacao_descricao` text DEFAULT NULL,
  `programacao_dia_semana` enum('Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado') NOT NULL,
  `programacao_hora` time NOT NULL,
  `programacao_recorrencia_mensal` tinyint(1) DEFAULT 0 COMMENT '0: Toda semana, 1-4: Semana específica do mês',
  `programacao_is_ceia` tinyint(1) DEFAULT 0 COMMENT '1: Define que este horário é o Culto de Ceia',
  `programacao_status` enum('Ativo','Inativo') DEFAULT 'Ativo',
  `programacao_criado_em` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `igrejas_redes_sociais`
--

CREATE TABLE `igrejas_redes_sociais` (
  `rede_id` int(11) NOT NULL,
  `rede_igreja_id` int(11) NOT NULL,
  `rede_nome` varchar(50) NOT NULL,
  `rede_usuario` varchar(100) NOT NULL,
  `rede_icone` varchar(50) DEFAULT NULL,
  `rede_status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `igrejas_redes_sociais`
--

INSERT INTO `igrejas_redes_sociais` (`rede_id`, `rede_igreja_id`, `rede_nome`, `rede_usuario`, `rede_icone`, `rede_status`) VALUES
(2, 1, 'Instagram', '@ipjdgirassol/', NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros`
--

CREATE TABLE `membros` (
  `membro_id` int(11) NOT NULL,
  `membro_igreja_id` int(11) DEFAULT NULL,
  `membro_registro_interno` varchar(20) DEFAULT NULL,
  `membro_nome` varchar(150) DEFAULT NULL,
  `membro_data_nascimento` date DEFAULT NULL,
  `membro_genero` varchar(20) DEFAULT NULL,
  `membro_estado_civil` varchar(50) DEFAULT NULL,
  `membro_email` varchar(150) DEFAULT NULL,
  `membro_senha` varchar(255) DEFAULT NULL,
  `membro_telefone` varchar(20) DEFAULT NULL,
  `membro_data_batismo` date DEFAULT NULL,
  `membro_status` varchar(20) DEFAULT NULL,
  `membro_data_criacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `membros`
--

INSERT INTO `membros` (`membro_id`, `membro_igreja_id`, `membro_registro_interno`, `membro_nome`, `membro_data_nascimento`, `membro_genero`, `membro_estado_civil`, `membro_email`, `membro_senha`, `membro_telefone`, `membro_data_batismo`, `membro_status`, `membro_data_criacao`) VALUES
(1, 1, '12026030001', 'Lucas Nobre Ferreira Martins', '1987-09-15', 'Masculino', NULL, 'lnfm1987@gmail.com', NULL, '11962851513', NULL, 'Ativo', '2026-03-19 05:04:18'),
(3, 1, '12026030003', 'Adilson Ferreira Martins', '1962-07-26', 'Masculino', NULL, 'afmnobre1962@gmail.com', NULL, '11995852032', NULL, 'Ativo', '2026-03-19 07:06:10'),
(385, 1, '1202603385', 'Tony Stark', '1977-03-29', 'Masculino', NULL, 'hero1@geekchurch.com', NULL, '11929682946', '2020-02-20', 'Ativo', '2026-03-20 17:12:02'),
(386, 1, '1202603386', 'Steve Rogers', '1988-01-17', 'Masculino', NULL, 'hero2@geekchurch.com', NULL, '11919368454', '2022-08-04', 'Ativo', '2026-03-20 17:12:02'),
(387, 1, '1202603387', 'Natasha Romanoff', '1976-07-11', 'Feminino', NULL, 'hero3@geekchurch.com', NULL, '11948036946', '2020-02-27', 'Ativo', '2026-03-20 17:12:02'),
(388, 1, '1202603388', 'Wanda Maximoff', '1950-08-26', 'Feminino', NULL, 'hero4@geekchurch.com', NULL, '11910007599', '2019-02-18', 'Ativo', '2026-03-20 17:12:02'),
(389, 1, '1202603389', 'Bruce Wayne', '1972-05-07', 'Masculino', NULL, 'hero5@geekchurch.com', NULL, '11963610533', '2022-10-09', 'Ativo', '2026-03-20 17:12:02'),
(390, 1, '1202603390', 'Clark Kent', '1933-06-30', 'Masculino', NULL, 'hero6@geekchurch.com', NULL, '11968637717', '2021-10-05', 'Ativo', '2026-03-20 17:12:02'),
(391, 1, '1202603391', 'Diana Prince', '1998-12-19', 'Feminino', NULL, 'hero7@geekchurch.com', NULL, '11913533168', '2022-07-09', 'Ativo', '2026-03-20 17:12:02'),
(392, 1, '1202603392', 'Peter Parker', '1953-10-26', 'Masculino', NULL, 'hero8@geekchurch.com', NULL, '11959435251', '2020-09-16', 'Ativo', '2026-03-20 17:12:02'),
(393, 1, '1202603393', 'Luke Skywalker', '2015-08-16', 'Masculino', NULL, 'hero9@geekchurch.com', NULL, '11989424767', '2025-04-22', 'Ativo', '2026-03-20 17:12:02'),
(394, 1, '1202603394', 'Leia Organa', '1946-03-23', 'Feminino', NULL, 'hero10@geekchurch.com', NULL, '11979229799', '2022-01-06', 'Ativo', '2026-03-20 17:12:02'),
(395, 1, '1202603395', 'Han Solo', '1947-08-22', 'Masculino', NULL, 'hero11@geekchurch.com', NULL, '11973915410', '2024-07-15', 'Ativo', '2026-03-20 17:12:02'),
(396, 1, '1202603396', 'Hermione Granger', '1955-12-30', 'Feminino', NULL, 'hero12@geekchurch.com', NULL, '11914028454', '2025-04-10', 'Ativo', '2026-03-20 17:12:02'),
(397, 1, '1202603397', 'Harry Potter', '1992-10-14', 'Masculino', NULL, 'hero13@geekchurch.com', NULL, '11946560426', '2026-01-16', 'Ativo', '2026-03-20 17:12:02'),
(398, 1, '1202603398', 'Ron Weasley', '1940-04-22', 'Masculino', NULL, 'hero14@geekchurch.com', NULL, '11936066022', '2017-10-17', 'Ativo', '2026-03-20 17:12:02'),
(399, 1, '1202603399', 'Ellen Ripley', '1991-12-22', 'Feminino', NULL, 'hero15@geekchurch.com', NULL, '11928150472', '2016-07-10', 'Ativo', '2026-03-20 17:12:02'),
(400, 1, '1202603400', 'Sarah Connor', '2002-01-01', 'Feminino', NULL, 'hero16@geekchurch.com', NULL, '11938255320', '2017-11-13', 'Ativo', '2026-03-20 17:12:02'),
(401, 1, '1202603401', 'Daenerys Targaryen', '2002-12-05', 'Feminino', NULL, 'hero17@geekchurch.com', NULL, '11970363012', '2019-10-01', 'Ativo', '2026-03-20 17:12:02'),
(402, 1, '1202603402', 'Jon Snow', '2004-01-12', 'Masculino', NULL, 'hero18@geekchurch.com', NULL, '11926147529', '2023-12-23', 'Ativo', '2026-03-20 17:12:02'),
(403, 1, '1202603403', 'Arya Stark', '1968-06-28', 'Feminino', NULL, 'hero19@geekchurch.com', NULL, '11932001129', '2021-07-03', 'Ativo', '2026-03-20 17:12:02'),
(404, 1, '1202603404', 'Tyrion Lannister', '1964-05-01', 'Masculino', NULL, 'hero20@geekchurch.com', NULL, '11974107242', '2019-05-17', 'Ativo', '2026-03-20 17:12:02'),
(405, 1, '1202603405', 'Monkey D Luffy', '1997-10-01', 'Masculino', NULL, 'hero21@geekchurch.com', NULL, '11944549113', '2025-08-27', 'Ativo', '2026-03-20 17:12:02'),
(406, 1, '1202603406', 'Roronoa Zoro', '2013-06-02', 'Masculino', NULL, 'hero22@geekchurch.com', NULL, '11953033690', '2026-03-11', 'Ativo', '2026-03-20 17:12:02'),
(407, 1, '1202603407', 'Nami', '1968-12-03', 'Feminino', NULL, 'hero23@geekchurch.com', NULL, '11989869322', '2019-03-23', 'Ativo', '2026-03-20 17:12:02'),
(408, 1, '1202603408', 'Nico Robin', '1943-06-04', 'Feminino', NULL, 'hero24@geekchurch.com', NULL, '11917636343', '2017-02-01', 'Ativo', '2026-03-20 17:12:02'),
(409, 1, '1202603409', 'Naruto Uzumaki', '1995-03-12', 'Masculino', NULL, 'hero25@geekchurch.com', NULL, '11984435895', '2024-04-09', 'Ativo', '2026-03-20 17:12:02'),
(410, 1, '1202603410', 'Sasuke Uchiha', '1977-07-31', 'Masculino', NULL, 'hero26@geekchurch.com', NULL, '11988690552', '2017-04-03', 'Ativo', '2026-03-20 17:12:02'),
(411, 1, '1202603411', 'Sakura Haruno', '1940-12-29', 'Feminino', NULL, 'hero27@geekchurch.com', NULL, '11965475191', '2021-03-30', 'Ativo', '2026-03-20 17:12:02'),
(412, 1, '1202603412', 'Hinata Hyuga', '1962-12-15', 'Feminino', NULL, 'hero28@geekchurch.com', NULL, '11973370605', '2020-03-13', 'Ativo', '2026-03-20 17:12:02'),
(413, 1, '1202603413', 'Goku', '1937-04-22', 'Masculino', NULL, 'hero29@geekchurch.com', NULL, '11971818481', '2018-11-03', 'Ativo', '2026-03-20 17:12:02'),
(414, 1, '1202603414', 'Vegeta', '1963-10-28', 'Masculino', NULL, 'hero30@geekchurch.com', NULL, '11994472367', '2018-03-19', 'Ativo', '2026-03-20 17:12:02'),
(415, 1, '1202603415', 'Bulma', '2007-07-18', 'Feminino', NULL, 'hero31@geekchurch.com', NULL, '11958690583', '2024-10-30', 'Ativo', '2026-03-20 17:12:02'),
(416, 1, '1202603416', 'Android 18', '2019-03-10', 'Feminino', NULL, 'hero32@geekchurch.com', NULL, '11994492959', '2021-05-30', 'Ativo', '2026-03-20 17:12:02'),
(417, 1, '1202603417', 'Walter White', '1968-01-14', 'Masculino', NULL, 'hero33@geekchurch.com', NULL, '11954791196', '2018-12-24', 'Ativo', '2026-03-20 17:12:02'),
(418, 1, '1202603418', 'Jesse Pinkman', '2013-07-28', 'Masculino', NULL, 'hero34@geekchurch.com', NULL, '11951985980', '2016-09-24', 'Ativo', '2026-03-20 17:12:02'),
(419, 1, '1202603419', 'Eleven', '1991-12-20', 'Feminino', NULL, 'hero35@geekchurch.com', NULL, '11989516272', '2022-06-06', 'Ativo', '2026-03-20 17:12:02'),
(420, 1, '1202603420', 'Jim Hopper', '2002-01-12', 'Masculino', NULL, 'hero36@geekchurch.com', NULL, '11917680552', '2019-04-16', 'Ativo', '2026-03-20 17:12:02'),
(421, 1, '1202603421', 'Michael Jackson', '2005-05-26', 'Masculino', NULL, 'hero37@geekchurch.com', NULL, '11997504966', '2023-12-02', 'Ativo', '2026-03-20 17:12:02'),
(422, 1, '1202603422', 'Freddie Mercury', '2003-02-06', 'Masculino', NULL, 'hero38@geekchurch.com', NULL, '11953022616', '2019-05-01', 'Ativo', '2026-03-20 17:12:02'),
(423, 1, '1202603423', 'Lady Gaga', '2025-01-23', 'Feminino', NULL, 'hero39@geekchurch.com', NULL, '11999175406', NULL, 'Ativo', '2026-03-20 17:12:02'),
(424, 1, '1202603424', 'Beyonce', '1964-07-10', 'Feminino', NULL, 'hero40@geekchurch.com', NULL, '11942422054', '2016-12-01', 'Ativo', '2026-03-20 17:12:02'),
(425, 1, '1202603425', 'Rick Sanchez', '1969-08-29', 'Masculino', NULL, 'hero41@geekchurch.com', NULL, '11916028524', '2020-01-09', 'Ativo', '2026-03-20 17:12:02'),
(426, 1, '1202603426', 'Morty Smith', '1937-05-05', 'Masculino', NULL, 'hero42@geekchurch.com', NULL, '11966886594', '2021-07-26', 'Ativo', '2026-03-20 17:12:02'),
(427, 1, '1202603427', 'Summer Smith', '1983-08-10', 'Feminino', NULL, 'hero43@geekchurch.com', NULL, '11978157622', '2021-04-11', 'Ativo', '2026-03-20 17:12:02'),
(428, 1, '1202603428', 'Beth Smith', '2006-05-23', 'Feminino', NULL, 'hero44@geekchurch.com', NULL, '11956671899', '2016-04-22', 'Ativo', '2026-03-20 17:12:02'),
(429, 1, '1202603429', 'Homelander', '1986-05-15', 'Masculino', NULL, 'hero45@geekchurch.com', NULL, '11913496362', '2016-05-13', 'Ativo', '2026-03-20 17:12:02'),
(430, 1, '1202603430', 'Starlight', '1945-10-22', 'Feminino', NULL, 'hero46@geekchurch.com', NULL, '11919567868', '2025-04-14', 'Ativo', '2026-03-20 17:12:02'),
(431, 1, '1202603431', 'Billy Butcher', '2011-08-31', 'Masculino', NULL, 'hero47@geekchurch.com', NULL, '11951027854', '2017-11-04', 'Ativo', '2026-03-20 17:12:02'),
(432, 1, '1202603432', 'Queen Maeve', '1944-11-01', 'Feminino', NULL, 'hero48@geekchurch.com', NULL, '11963896044', '2020-12-15', 'Ativo', '2026-03-20 17:12:02'),
(433, 1, '1202603433', 'Joel Miller', '1943-07-16', 'Masculino', NULL, 'hero49@geekchurch.com', NULL, '11963931675', '2021-04-30', 'Ativo', '2026-03-20 17:12:02'),
(434, 1, '1202603434', 'Ellie Williams', '1962-02-26', 'Feminino', NULL, 'hero50@geekchurch.com', NULL, '11979341035', '2017-02-15', 'Ativo', '2026-03-20 17:12:02'),
(435, 1, '1202603435', 'Geralt of Rivia', '2002-09-30', 'Masculino', NULL, 'hero51@geekchurch.com', NULL, '11951127759', '2020-06-27', 'Ativo', '2026-03-20 17:12:02'),
(436, 1, '1202603436', 'Yennefer', '1977-03-29', 'Feminino', NULL, 'hero52@geekchurch.com', NULL, '11978013006', '2023-04-15', 'Ativo', '2026-03-20 17:12:02'),
(437, 1, '1202603437', 'Ciri', '2006-06-23', 'Feminino', NULL, 'hero53@geekchurch.com', NULL, '11920591157', '2016-04-29', 'Ativo', '2026-03-20 17:12:02'),
(438, 1, '1202603438', 'Triss Merigold', '1967-04-04', 'Feminino', NULL, 'hero54@geekchurch.com', NULL, '11910941581', '2023-07-29', 'Ativo', '2026-03-20 17:12:02'),
(439, 1, '1202603439', 'Katniss Everdeen', '1997-06-21', 'Feminino', NULL, 'hero55@geekchurch.com', NULL, '11969365437', '2021-12-15', 'Ativo', '2026-03-20 17:12:02'),
(440, 1, '1202603440', 'Peeta Mellark', '2011-01-22', 'Masculino', NULL, 'hero56@geekchurch.com', NULL, '11953812501', '2016-06-24', 'Ativo', '2026-03-20 17:12:02'),
(441, 1, '1202603441', 'John Wick', '1985-06-30', 'Masculino', NULL, 'hero57@geekchurch.com', NULL, '11922133477', '2021-10-29', 'Ativo', '2026-03-20 17:12:02'),
(442, 1, '1202603442', 'James Bond', '1947-11-16', 'Masculino', NULL, 'hero58@geekchurch.com', NULL, '11967740779', '2017-11-25', 'Ativo', '2026-03-20 17:12:02'),
(443, 1, '1202603443', 'Indiana Jones', '2002-11-01', 'Masculino', NULL, 'hero59@geekchurch.com', NULL, '11971658635', '2019-01-21', 'Ativo', '2026-03-20 17:12:02'),
(444, 1, '1202603444', 'Lara Croft', '1974-01-31', 'Feminino', NULL, 'hero60@geekchurch.com', NULL, '11953690014', '2017-10-04', 'Ativo', '2026-03-20 17:12:02'),
(445, 1, '1202603445', 'Legolas', '1949-07-03', 'Masculino', NULL, 'hero61@geekchurch.com', NULL, '11940418144', '2022-08-01', 'Ativo', '2026-03-20 17:12:02'),
(446, 1, '1202603446', 'Galadriel', '1946-08-18', 'Feminino', NULL, 'hero62@geekchurch.com', NULL, '11993821354', '2023-10-14', 'Ativo', '2026-03-20 17:12:02'),
(447, 1, '1202603447', 'Aragorn', '1984-06-03', 'Masculino', NULL, 'hero63@geekchurch.com', NULL, '11944418624', '2019-10-10', 'Ativo', '2026-03-20 17:12:02'),
(448, 1, '1202603448', 'Frodo Bolseiro', '2018-08-28', 'Masculino', NULL, 'hero64@geekchurch.com', NULL, '11950313567', '2026-02-11', 'Ativo', '2026-03-20 17:12:02'),
(449, 1, '1202603449', 'Eowyn', '1956-03-06', 'Feminino', NULL, 'hero65@geekchurch.com', NULL, '11955782718', '2022-01-09', 'Ativo', '2026-03-20 17:12:02'),
(450, 1, '1202603450', 'Arwen', '1969-08-13', 'Feminino', NULL, 'hero66@geekchurch.com', NULL, '11964151350', '2023-04-20', 'Ativo', '2026-03-20 17:12:02'),
(451, 1, '1202603451', 'Tanjiro Kamado', '1961-05-26', 'Masculino', NULL, 'hero67@geekchurch.com', NULL, '11945974777', '2025-11-20', 'Ativo', '2026-03-20 17:12:02'),
(452, 1, '1202603452', 'Nezuko Kamado', '1930-08-15', 'Feminino', NULL, 'hero68@geekchurch.com', NULL, '11975895887', '2018-08-08', 'Ativo', '2026-03-20 17:12:02'),
(453, 1, '1202603453', 'Zenitsu Agatsuma', '1965-07-08', 'Masculino', NULL, 'hero69@geekchurch.com', NULL, '11980352181', '2025-07-16', 'Ativo', '2026-03-20 17:12:02'),
(454, 1, '1202603454', 'Inosuke Hashibira', '1927-11-18', 'Masculino', NULL, 'hero70@geekchurch.com', NULL, '11978985769', '2017-09-19', 'Ativo', '2026-03-20 17:12:02'),
(455, 1, '1202603455', 'Saitama', '1931-11-25', 'Masculino', NULL, 'hero71@geekchurch.com', NULL, '11929352823', '2024-01-25', 'Ativo', '2026-03-20 17:12:02'),
(456, 1, '1202603456', 'Tatsumaki', '1983-08-20', 'Feminino', NULL, 'hero72@geekchurch.com', NULL, '11955610286', '2023-10-17', 'Ativo', '2026-03-20 17:12:02'),
(457, 1, '1202603457', 'Edward Elric', '1957-08-31', 'Masculino', NULL, 'hero73@geekchurch.com', NULL, '11976296876', '2020-03-05', 'Ativo', '2026-03-20 17:12:02'),
(458, 1, '1202603458', 'Winry Rockbell', '1945-11-14', 'Feminino', NULL, 'hero74@geekchurch.com', NULL, '11932272553', '2018-03-19', 'Ativo', '2026-03-20 17:12:02'),
(459, 1, '1202603459', 'Roy Mustang', '2000-04-07', 'Masculino', NULL, 'hero75@geekchurch.com', NULL, '11991788785', '2018-08-27', 'Ativo', '2026-03-20 17:12:02'),
(460, 1, '1202603460', 'Riza Hawkeye', '2020-06-24', 'Feminino', NULL, 'hero76@geekchurch.com', NULL, '11911744136', NULL, 'Ativo', '2026-03-20 17:12:02'),
(461, 1, '1202603461', 'Ichigo Kurosaki', '1971-02-28', 'Masculino', NULL, 'hero77@geekchurch.com', NULL, '11911233811', '2022-03-25', 'Ativo', '2026-03-20 17:12:02'),
(462, 1, '1202603462', 'Rukia Kuchiki', '1931-10-28', 'Feminino', NULL, 'hero78@geekchurch.com', NULL, '11961613349', '2026-03-02', 'Ativo', '2026-03-20 17:12:02'),
(463, 1, '1202603463', 'Orihime Inoue', '1996-01-27', 'Feminino', NULL, 'hero79@geekchurch.com', NULL, '11955755241', '2019-12-11', 'Ativo', '2026-03-20 17:12:02'),
(464, 1, '1202603464', 'Sosuke Aizen', '1965-06-15', 'Masculino', NULL, 'hero80@geekchurch.com', NULL, '11926859064', '2025-04-11', 'Ativo', '2026-03-20 17:12:02'),
(465, 1, '1202603465', 'Light Yagami', '1936-05-11', 'Masculino', NULL, 'hero81@geekchurch.com', NULL, '11933333746', '2020-07-04', 'Ativo', '2026-03-20 17:12:02'),
(466, 1, '1202603466', 'Misa Amano', '2018-05-07', 'Feminino', NULL, 'hero82@geekchurch.com', NULL, '11971505175', '2024-06-08', 'Ativo', '2026-03-20 17:12:02'),
(467, 1, '1202603467', 'L Lawliet', '1942-12-30', 'Masculino', NULL, 'hero83@geekchurch.com', NULL, '11970403070', '2017-11-25', 'Ativo', '2026-03-20 17:12:02'),
(468, 1, '1202603468', 'Mikasa Ackerman', '2011-08-24', 'Feminino', NULL, 'hero84@geekchurch.com', NULL, '11931606109', '2018-08-21', 'Ativo', '2026-03-20 17:12:02'),
(469, 1, '1202603469', 'Eren Yeager', '2019-02-05', 'Masculino', NULL, 'hero85@geekchurch.com', NULL, '11917606013', '2024-02-25', 'Ativo', '2026-03-20 17:12:02'),
(470, 1, '1202603470', 'Levi Ackerman', '1949-01-25', 'Masculino', NULL, 'hero86@geekchurch.com', NULL, '11935116295', '2025-08-28', 'Ativo', '2026-03-20 17:12:02'),
(471, 1, '1202603471', 'Annie Leonhart', '1982-06-17', 'Feminino', NULL, 'hero87@geekchurch.com', NULL, '11913941459', '2017-04-20', 'Ativo', '2026-03-20 17:12:02'),
(472, 1, '1202603472', 'David Bowie', '1993-09-02', 'Masculino', NULL, 'hero88@geekchurch.com', NULL, '11997176896', '2017-08-29', 'Ativo', '2026-03-20 17:12:02'),
(473, 1, '1202603473', 'Kurt Cobain', '1989-01-29', 'Masculino', NULL, 'hero89@geekchurch.com', NULL, '11937732524', '2022-01-27', 'Ativo', '2026-03-20 17:12:02'),
(474, 1, '1202603474', 'Amy Winehouse', '2011-07-17', 'Feminino', NULL, 'hero90@geekchurch.com', NULL, '11954825939', '2025-10-06', 'Ativo', '2026-03-20 17:12:02'),
(475, 1, '1202603475', 'Janis Joplin', '1953-10-08', 'Feminino', NULL, 'hero91@geekchurch.com', NULL, '11957297128', '2021-11-29', 'Ativo', '2026-03-20 17:12:02'),
(476, 1, '1202603476', 'Elvis Presley', '1969-02-07', 'Masculino', NULL, 'hero92@geekchurch.com', NULL, '11963470074', '2023-10-31', 'Ativo', '2026-03-20 17:12:02'),
(477, 1, '1202603477', 'Axl Rose', '1985-07-15', 'Masculino', NULL, 'hero93@geekchurch.com', NULL, '11940583665', '2021-07-25', 'Ativo', '2026-03-20 17:12:02'),
(478, 1, '1202603478', 'Dua Lipa', '1995-09-22', 'Feminino', NULL, 'hero94@geekchurch.com', NULL, '11923001361', '2018-03-31', 'Ativo', '2026-03-20 17:12:02'),
(479, 1, '1202603479', 'Rihanna', '1971-04-26', 'Feminino', NULL, 'hero95@geekchurch.com', NULL, '11944350124', '2023-10-14', 'Ativo', '2026-03-20 17:12:02'),
(480, 1, '1202603480', 'Taylor Swift', '2019-01-08', 'Feminino', NULL, 'hero96@geekchurch.com', NULL, '11966973387', NULL, 'Ativo', '2026-03-20 17:12:02'),
(481, 1, '1202603481', 'Bruno Mars', '1943-07-13', 'Masculino', NULL, 'hero97@geekchurch.com', NULL, '11940417807', '2024-05-23', 'Ativo', '2026-03-20 17:12:02'),
(482, 1, '1202603482', 'Ed Sheeran', '1937-04-21', 'Masculino', NULL, 'hero98@geekchurch.com', NULL, '11995143301', '2025-11-16', 'Ativo', '2026-03-20 17:12:02'),
(483, 1, '1202603483', 'Billie Eilish', '1993-05-18', 'Feminino', NULL, 'hero99@geekchurch.com', NULL, '11960183522', '2018-04-20', 'Ativo', '2026-03-20 17:12:02'),
(484, 1, '1202603484', 'Adele', '1997-09-13', 'Feminino', NULL, 'hero100@geekchurch.com', NULL, '11915812820', '2021-08-21', 'Ativo', '2026-03-20 17:12:02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_cargos_vinculo`
--

CREATE TABLE `membros_cargos_vinculo` (
  `vinculo_id` int(11) NOT NULL,
  `vinculo_membro_id` int(11) NOT NULL,
  `vinculo_cargo_id` int(11) NOT NULL,
  `vinculo_data_atribuicao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `membros_cargos_vinculo`
--

INSERT INTO `membros_cargos_vinculo` (`vinculo_id`, `vinculo_membro_id`, `vinculo_cargo_id`, `vinculo_data_atribuicao`) VALUES
(46, 1, 7, '2026-03-20 18:11:56'),
(47, 1, 22, '2026-03-20 18:11:56'),
(55, 3, 7, '2026-03-20 18:26:25'),
(56, 474, 17, '2026-03-20 18:27:48'),
(57, 416, 14, '2026-03-20 18:28:02'),
(59, 453, 18, '2026-03-20 18:31:11'),
(60, 461, 12, '2026-03-20 18:34:24'),
(61, 396, 13, '2026-03-20 18:34:34'),
(63, 413, 1, '2026-03-21 03:56:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_contatos`
--

CREATE TABLE `membros_contatos` (
  `membro_contato_id` int(11) NOT NULL,
  `membro_contato_membro_id` int(11) DEFAULT NULL,
  `membro_contato_tipo` varchar(50) DEFAULT NULL,
  `membro_contato_valor` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_enderecos`
--

CREATE TABLE `membros_enderecos` (
  `membro_endereco_id` int(11) NOT NULL,
  `membro_endereco_membro_id` int(11) DEFAULT NULL,
  `membro_endereco_igreja_id` int(11) DEFAULT NULL,
  `membro_endereco_rua` varchar(255) DEFAULT NULL,
  `membro_endereco_numero` varchar(20) DEFAULT NULL,
  `membro_endereco_complemento` varchar(100) DEFAULT NULL,
  `membro_endereco_bairro` varchar(100) DEFAULT NULL,
  `membro_endereco_cidade` varchar(100) DEFAULT NULL,
  `membro_endereco_estado` varchar(50) DEFAULT NULL,
  `membro_endereco_cep` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `membros_enderecos`
--

INSERT INTO `membros_enderecos` (`membro_endereco_id`, `membro_endereco_membro_id`, `membro_endereco_igreja_id`, `membro_endereco_rua`, `membro_endereco_numero`, `membro_endereco_complemento`, `membro_endereco_bairro`, `membro_endereco_cidade`, `membro_endereco_estado`, `membro_endereco_cep`) VALUES
(1, 1, 1, 'Rua das Aeromoças, 48 - Casa 2', NULL, NULL, NULL, 'Guarulhos', 'SP', '07161725'),
(2, 3, 1, 'Rua Babaçu', NULL, NULL, NULL, 'Guarulhos', 'SP', '07160360'),
(3, 386, 1, 'Viela Ubirajara', NULL, NULL, NULL, 'Guarulhos', 'SP', '07160290');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_fotos`
--

CREATE TABLE `membros_fotos` (
  `membro_foto_id` int(11) NOT NULL,
  `membro_foto_membro_id` int(11) DEFAULT NULL,
  `membro_foto_arquivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `membros_fotos`
--

INSERT INTO `membros_fotos` (`membro_foto_id`, `membro_foto_membro_id`, `membro_foto_arquivo`) VALUES
(1, 3, 'perfil_1773921493.jpg'),
(2, 1, 'perfil_1773921732.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_historico`
--

CREATE TABLE `membros_historico` (
  `membro_historico_id` int(11) NOT NULL,
  `membro_historico_membro_id` int(11) DEFAULT NULL,
  `membro_historico_data` datetime DEFAULT NULL,
  `membro_historico_texto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `membros_historico`
--

INSERT INTO `membros_historico` (`membro_historico_id`, `membro_historico_membro_id`, `membro_historico_data`, `membro_historico_texto`) VALUES
(1, 3, '2026-03-19 12:47:00', '<ul><li>Teste de Inserção de Registro de Histórico.<br><br><strong>sera que fuciona?</strong></li></ul>'),
(2, 3, '2026-03-19 13:00:15', '<p><strong>Entrada do membro</strong><br><br>Não sabemos o que esta acontecendo.</p>');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_responsaveis`
--

CREATE TABLE `membros_responsaveis` (
  `parentesco_id` int(11) NOT NULL,
  `parentesco_responsavel_id` int(11) NOT NULL,
  `parentesco_dependente_id` int(11) NOT NULL,
  `parentesco_grau` varchar(50) DEFAULT 'Pai/Mãe'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_bens`
--

CREATE TABLE `patrimonio_bens` (
  `patrimonio_bem_id` int(11) NOT NULL,
  `patrimonio_bem_igreja_id` int(11) DEFAULT NULL,
  `patrimonio_bem_codigo` varchar(50) DEFAULT NULL,
  `patrimonio_bem_nome` varchar(150) DEFAULT NULL,
  `patrimonio_bem_descricao` text DEFAULT NULL,
  `patrimonio_bem_data_aquisicao` date DEFAULT NULL,
  `patrimonio_bem_valor` decimal(12,2) DEFAULT NULL,
  `patrimonio_bem_status` enum('ativo','inativo','manutencao','baixado','extraviado','danificado') DEFAULT NULL,
  `patrimonio_bem_local_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `patrimonio_bens`
--

INSERT INTO `patrimonio_bens` (`patrimonio_bem_id`, `patrimonio_bem_igreja_id`, `patrimonio_bem_codigo`, `patrimonio_bem_nome`, `patrimonio_bem_descricao`, `patrimonio_bem_data_aquisicao`, `patrimonio_bem_valor`, `patrimonio_bem_status`, `patrimonio_bem_local_id`) VALUES
(5, 1, 'PAT-2026-0001', 'Banco Madeira - 5 Lugares', 'Usado a muito tempo.', '2026-03-01', 500.00, 'ativo', 3),
(6, 1, 'PAT-2026-0006', 'Banco Madeira - 5 Lugares', 'usado a tempo demais mas vamos ver como fica a etiqueta.', '2026-03-01', 500.00, 'ativo', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_documentos`
--

CREATE TABLE `patrimonio_documentos` (
  `patrimonio_documento_id` int(11) NOT NULL,
  `patrimonio_documento_bem_id` int(11) DEFAULT NULL,
  `patrimonio_documento_arquivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `patrimonio_documentos`
--

INSERT INTO `patrimonio_documentos` (`patrimonio_documento_id`, `patrimonio_documento_bem_id`, `patrimonio_documento_arquivo`) VALUES
(2, 5, 'doc_1774195530_1943.pdf');

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_imagens`
--

CREATE TABLE `patrimonio_imagens` (
  `patrimonio_imagem_id` int(11) NOT NULL,
  `patrimonio_imagem_bem_id` int(11) DEFAULT NULL,
  `patrimonio_imagem_arquivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `patrimonio_imagens`
--

INSERT INTO `patrimonio_imagens` (`patrimonio_imagem_id`, `patrimonio_imagem_bem_id`, `patrimonio_imagem_arquivo`) VALUES
(9, 5, 'foto_1774191683_5057.png'),
(10, 5, 'foto_1774191683_1070.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_locais`
--

CREATE TABLE `patrimonio_locais` (
  `patrimonio_local_id` int(11) NOT NULL,
  `patrimonio_local_igreja_id` int(11) DEFAULT NULL,
  `patrimonio_local_nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `patrimonio_locais`
--

INSERT INTO `patrimonio_locais` (`patrimonio_local_id`, `patrimonio_local_igreja_id`, `patrimonio_local_nome`) VALUES
(3, 1, 'Templo'),
(4, 1, 'Cozinha');

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_movimentacoes`
--

CREATE TABLE `patrimonio_movimentacoes` (
  `patrimonio_movimentacao_id` int(11) NOT NULL,
  `patrimonio_movimentacao_patrimonio_bem_id` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_igreja_id` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_tipo` enum('entrada','saida','transferencia','manutencao','baixa') DEFAULT NULL,
  `patrimonio_movimentacao_local_origem` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_local_destino` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_data` datetime DEFAULT NULL,
  `patrimonio_movimentacao_observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `patrimonio_movimentacoes`
--

INSERT INTO `patrimonio_movimentacoes` (`patrimonio_movimentacao_id`, `patrimonio_movimentacao_patrimonio_bem_id`, `patrimonio_movimentacao_igreja_id`, `patrimonio_movimentacao_tipo`, `patrimonio_movimentacao_local_origem`, `patrimonio_movimentacao_local_destino`, `patrimonio_movimentacao_data`, `patrimonio_movimentacao_observacao`) VALUES
(5, 5, 1, 'entrada', NULL, 3, '2026-03-22 14:52:14', 'Entrada inicial. Código gerado: PAT-2026-0001'),
(6, 5, 1, 'transferencia', 3, 4, '2026-03-22 14:54:59', 'agora esta na cozinha'),
(7, 5, 1, 'transferencia', 4, 3, '2026-03-22 14:55:24', 'Agora esta no templo.'),
(8, 6, 1, 'entrada', NULL, 3, '2026-03-22 16:52:44', 'Entrada inicial. Código gerado: PAT-2026-0006'),
(9, 6, 1, 'transferencia', 3, 4, '2026-03-22 17:55:58', 'agora ta na cozinha');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfis`
--

CREATE TABLE `perfis` (
  `perfil_id` int(11) NOT NULL,
  `perfil_nome` varchar(100) DEFAULT NULL,
  `perfil_descricao` text DEFAULT NULL,
  `perfil_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfis`
--

INSERT INTO `perfis` (`perfil_id`, `perfil_nome`, `perfil_descricao`, `perfil_status`) VALUES
(1, 'Admin', 'Acesso total ao sistema e gestão de usuários', 'ativo'),
(2, 'Tesoureiro', 'Gestão financeira, lançamentos e relatórios', 'ativo'),
(3, 'Secretario', 'Gestão de membros, certificados e documentos', 'ativo'),
(4, 'Professor', 'Acesso ao módulo de Escola Dominical e Chamadas', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sociedades`
--

CREATE TABLE `sociedades` (
  `sociedade_id` int(11) NOT NULL,
  `sociedade_igreja_id` int(11) DEFAULT NULL,
  `sociedade_nome` varchar(100) DEFAULT NULL,
  `sociedade_genero` varchar(20) DEFAULT 'Ambos',
  `sociedade_idade_min` int(11) DEFAULT 0,
  `sociedade_idade_max` int(11) DEFAULT 99,
  `sociedade_tipo` varchar(50) DEFAULT NULL,
  `sociedade_status` varchar(20) DEFAULT NULL,
  `sociedade_lider` int(11) DEFAULT NULL,
  `sociedade_senha` varchar(255) DEFAULT NULL,
  `sociedade_logo` varchar(255) DEFAULT NULL,
  `sociedade_banner_background` varchar(255) DEFAULT NULL,
  `sociedade_layout_config` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sociedades`
--

INSERT INTO `sociedades` (`sociedade_id`, `sociedade_igreja_id`, `sociedade_nome`, `sociedade_genero`, `sociedade_idade_min`, `sociedade_idade_max`, `sociedade_tipo`, `sociedade_status`, `sociedade_lider`, `sociedade_senha`, `sociedade_logo`, `sociedade_banner_background`, `sociedade_layout_config`) VALUES
(1, 1, 'UCP - União de Crianças Presbiterianas', 'Ambos', 0, 12, 'Infantil', 'Ativo', 416, NULL, '1/sociedades/1/logo/logo_1774209497.png', NULL, NULL),
(2, 1, 'UPA - União Presbiteriana de Adolescentes', 'Ambos', 13, 18, 'Adolescentes', 'Ativo', 396, NULL, '1/sociedades/2/logo/logo_1774209528.png', NULL, NULL),
(3, 1, 'UMP - União de Mocidade Presbiteriana', 'Ambos', 19, 35, 'Jovens', 'Ativo', 461, NULL, '1/sociedades/3/logo/logo_1774209513.png', NULL, NULL),
(4, 1, 'SAF - Sociedade Auxiliadora Feminina', 'Feminino', 18, 99, 'Mulheres', 'Ativo', 474, NULL, '1/sociedades/4/logo/logo_1774209144.png', NULL, NULL),
(5, 1, 'UPH - União Presbiteriana de Homens', 'Masculino', 18, 99, 'Homens', 'Ativo', 453, NULL, '1/sociedades/5/logo/logo_1774209557.png', NULL, '{\"version\":\"5.3.0\",\"objects\":[],\"background\":\"#ffffff\"}');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sociedades_eventos`
--

CREATE TABLE `sociedades_eventos` (
  `sociedade_evento_id` int(11) NOT NULL,
  `sociedade_evento_igreja_id` int(11) NOT NULL,
  `sociedade_evento_sociedade_id` int(11) NOT NULL,
  `sociedade_evento_titulo` varchar(150) NOT NULL,
  `sociedade_evento_descricao` text DEFAULT NULL,
  `sociedade_evento_local` varchar(255) DEFAULT NULL,
  `sociedade_evento_imagem` varchar(255) DEFAULT NULL,
  `sociedade_evento_data_hora_inicio` datetime NOT NULL,
  `sociedade_evento_data_hora_fim` datetime DEFAULT NULL,
  `sociedade_evento_valor` decimal(10,2) DEFAULT 0.00,
  `sociedade_evento_status` enum('Agendado','Confirmado','Cancelado','Concluído') DEFAULT 'Agendado',
  `sociedade_evento_criado_em` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sociedades_eventos`
--

INSERT INTO `sociedades_eventos` (`sociedade_evento_id`, `sociedade_evento_igreja_id`, `sociedade_evento_sociedade_id`, `sociedade_evento_titulo`, `sociedade_evento_descricao`, `sociedade_evento_local`, `sociedade_evento_imagem`, `sociedade_evento_data_hora_inicio`, `sociedade_evento_data_hora_fim`, `sociedade_evento_valor`, `sociedade_evento_status`, `sociedade_evento_criado_em`) VALUES
(7, 1, 5, 'Evento A - Homens', 'Evento criado para teste na casa.', 'Residência de Lucas Nobre Ferreira Martins - Rua das Aeromoças, 48 - Casa 2, Guarulhos - SP', NULL, '2026-03-20 12:30:00', '2026-03-20 14:30:00', 0.00, 'Agendado', '2026-03-20 14:48:01'),
(8, 1, 3, 'Treino de Futebol', 'Treino dos Jovens no campo da igreja', 'Igreja Presbiteriana do Jardim Girassol - Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', NULL, '2026-03-29 09:00:00', '2026-03-29 11:30:00', 15.00, 'Agendado', '2026-03-21 07:57:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sociedades_eventos_presencas`
--

CREATE TABLE `sociedades_eventos_presencas` (
  `sociedade_presenca_id` int(11) NOT NULL,
  `sociedade_presenca_igreja_id` int(11) NOT NULL,
  `sociedade_presenca_sociedade_id` int(11) NOT NULL,
  `sociedade_presenca_evento_id` int(11) NOT NULL,
  `sociedade_presenca_membro_id` int(11) NOT NULL,
  `sociedade_presenca_status` enum('Presente','Faltou','Justificado') DEFAULT 'Presente',
  `sociedade_presenca_observacao` varchar(255) DEFAULT NULL,
  `sociedade_presenca_registrado_em` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sociedades_membros`
--

CREATE TABLE `sociedades_membros` (
  `sociedade_membro_id` int(11) NOT NULL,
  `sociedade_membro_igreja_id` int(11) DEFAULT NULL,
  `sociedade_membro_sociedade_id` int(11) DEFAULT NULL,
  `sociedade_membro_membro_id` int(11) DEFAULT NULL,
  `sociedade_membro_funcao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sociedades_membros`
--

INSERT INTO `sociedades_membros` (`sociedade_membro_id`, `sociedade_membro_igreja_id`, `sociedade_membro_sociedade_id`, `sociedade_membro_membro_id`, `sociedade_membro_funcao`) VALUES
(43, 1, 5, 3, 'Sócio'),
(44, 1, 5, 447, 'Sócio'),
(45, 1, 5, 477, 'Sócio'),
(46, 1, 5, 389, 'Sócio'),
(47, 1, 5, 481, 'Sócio'),
(48, 1, 5, 390, 'Sócio'),
(49, 1, 5, 472, 'Sócio'),
(50, 1, 5, 457, 'Sócio'),
(51, 1, 5, 476, 'Sócio'),
(52, 1, 5, 422, 'Sócio'),
(53, 1, 5, 435, 'Sócio'),
(54, 1, 5, 413, 'Sócio'),
(55, 1, 5, 397, 'Sócio'),
(56, 1, 5, 433, 'Sócio'),
(57, 1, 5, 441, 'Sócio'),
(58, 1, 5, 402, 'Sócio'),
(59, 1, 5, 473, 'Sócio'),
(60, 1, 5, 467, 'Sócio'),
(61, 1, 5, 445, 'Sócio'),
(62, 1, 5, 470, 'Sócio'),
(63, 1, 5, 1, 'Sócio'),
(64, 1, 5, 392, 'Sócio'),
(65, 1, 5, 425, 'Sócio'),
(66, 1, 5, 398, 'Sócio'),
(67, 1, 5, 459, 'Sócio'),
(68, 1, 5, 410, 'Sócio'),
(69, 1, 5, 464, 'Sócio'),
(70, 1, 5, 386, 'Sócio'),
(71, 1, 5, 385, 'Sócio'),
(72, 1, 5, 417, 'Sócio'),
(73, 1, 5, 453, 'Sócio'),
(74, 1, 2, 474, 'Sócio'),
(75, 1, 2, 431, 'Sócio'),
(76, 1, 2, 415, 'Sócio'),
(77, 1, 2, 468, 'Sócio'),
(78, 1, 3, 484, 'Sócio'),
(79, 1, 3, 428, 'Sócio'),
(80, 1, 3, 483, 'Sócio'),
(81, 1, 3, 437, 'Sócio'),
(82, 1, 3, 401, 'Sócio'),
(83, 1, 3, 391, 'Sócio'),
(84, 1, 3, 422, 'Sócio'),
(85, 1, 3, 435, 'Sócio'),
(86, 1, 3, 397, 'Sócio'),
(87, 1, 3, 443, 'Sócio'),
(88, 1, 3, 402, 'Sócio'),
(89, 1, 3, 421, 'Sócio'),
(90, 1, 3, 405, 'Sócio'),
(91, 1, 3, 409, 'Sócio'),
(92, 1, 3, 463, 'Sócio'),
(93, 1, 1, 416, 'Sócio'),
(94, 1, 1, 469, 'Sócio'),
(95, 1, 1, 448, 'Sócio'),
(96, 1, 1, 418, 'Sócio'),
(97, 1, 1, 423, 'Sócio'),
(98, 1, 1, 406, 'Sócio'),
(99, 1, 1, 480, 'Sócio'),
(100, 1, 4, 484, 'Sócio'),
(101, 1, 4, 471, 'Sócio'),
(102, 1, 4, 450, 'Sócio'),
(103, 1, 4, 403, 'Sócio'),
(104, 1, 4, 428, 'Sócio'),
(105, 1, 4, 424, 'Sócio'),
(106, 1, 4, 483, 'Sócio'),
(107, 1, 4, 415, 'Sócio'),
(108, 1, 4, 437, 'Sócio'),
(109, 1, 4, 401, 'Sócio'),
(110, 1, 4, 391, 'Sócio'),
(111, 1, 4, 478, 'Sócio'),
(112, 1, 4, 419, 'Sócio'),
(113, 1, 4, 399, 'Sócio'),
(114, 1, 4, 434, 'Sócio'),
(115, 1, 4, 449, 'Sócio'),
(116, 1, 4, 446, 'Sócio'),
(117, 1, 4, 396, 'Sócio'),
(118, 1, 4, 412, 'Sócio'),
(119, 1, 4, 475, 'Sócio'),
(120, 1, 4, 439, 'Sócio'),
(121, 1, 4, 444, 'Sócio'),
(122, 1, 4, 394, 'Sócio'),
(123, 1, 4, 407, 'Sócio'),
(124, 1, 4, 387, 'Sócio'),
(125, 1, 4, 452, 'Sócio'),
(126, 1, 4, 408, 'Sócio'),
(127, 1, 4, 411, 'Sócio'),
(128, 1, 4, 400, 'Sócio'),
(129, 1, 4, 430, 'Sócio'),
(130, 1, 4, 456, 'Sócio'),
(131, 1, 4, 388, 'Sócio'),
(132, 1, 4, 458, 'Sócio'),
(133, 1, 4, 436, 'Sócio');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sociedades_orcamentos`
--

CREATE TABLE `sociedades_orcamentos` (
  `sociedade_orcamento_id` int(11) NOT NULL,
  `sociedade_orcamento_igreja_id` int(11) DEFAULT NULL,
  `sociedade_orcamento_sociedade_id` int(11) DEFAULT NULL,
  `sociedade_orcamento_valor` decimal(12,2) DEFAULT NULL,
  `sociedade_orcamento_ano` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `usuario_igreja_id` int(11) DEFAULT NULL,
  `usuario_nome` varchar(150) DEFAULT NULL,
  `usuario_email` varchar(150) DEFAULT NULL,
  `usuario_senha` varchar(255) DEFAULT NULL,
  `usuario_status` varchar(20) DEFAULT NULL,
  `usuario_data_criacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario_igreja_id`, `usuario_nome`, `usuario_email`, `usuario_senha`, `usuario_status`, `usuario_data_criacao`) VALUES
(1, 1, 'Admin', 'admin@ekklesia.com', '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', 'ativo', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_perfis`
--

CREATE TABLE `usuarios_perfis` (
  `usuario_perfil_id` int(11) NOT NULL,
  `usuario_perfil_igreja_id` int(11) DEFAULT NULL,
  `usuario_perfil_usuario_id` int(11) DEFAULT NULL,
  `usuario_perfil_perfil_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios_perfis`
--

INSERT INTO `usuarios_perfis` (`usuario_perfil_id`, `usuario_perfil_igreja_id`, `usuario_perfil_usuario_id`, `usuario_perfil_perfil_id`) VALUES
(1, 1, 1, 1),
(3, 1, 3, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`cargo_id`);

--
-- Índices de tabela `classes_config`
--
ALTER TABLE `classes_config`
  ADD PRIMARY KEY (`config_id`),
  ADD KEY `config_igreja_id` (`config_igreja_id`);

--
-- Índices de tabela `classes_escola`
--
ALTER TABLE `classes_escola`
  ADD PRIMARY KEY (`classe_id`),
  ADD KEY `classe_igreja_id` (`classe_igreja_id`),
  ADD KEY `classe_professor_id` (`classe_professor_id`);

--
-- Índices de tabela `classes_membros`
--
ALTER TABLE `classes_membros`
  ADD PRIMARY KEY (`classe_membro_id`),
  ADD KEY `classe_membro_classe_id` (`classe_membro_classe_id`),
  ADD KEY `classe_membro_membro_id` (`classe_membro_membro_id`);

--
-- Índices de tabela `classes_presencas`
--
ALTER TABLE `classes_presencas`
  ADD PRIMARY KEY (`presenca_id`),
  ADD KEY `presenca_classe_id` (`presenca_classe_id`),
  ADD KEY `presenca_membro_id` (`presenca_membro_id`);

--
-- Índices de tabela `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`documento_id`),
  ADD KEY `documento_igreja_id` (`documento_igreja_id`),
  ADD KEY `documento_documento_categoria_id` (`documento_documento_categoria_id`);

--
-- Índices de tabela `documentos_arquivos`
--
ALTER TABLE `documentos_arquivos`
  ADD PRIMARY KEY (`documento_arquivo_id`),
  ADD KEY `documento_arquivo_documento_id` (`documento_arquivo_documento_id`);

--
-- Índices de tabela `documentos_categorias`
--
ALTER TABLE `documentos_categorias`
  ADD PRIMARY KEY (`documento_categoria_id`),
  ADD KEY `documento_categoria_igreja_id` (`documento_categoria_igreja_id`);

--
-- Índices de tabela `financeiro_categorias`
--
ALTER TABLE `financeiro_categorias`
  ADD PRIMARY KEY (`financeiro_categoria_id`),
  ADD KEY `financeiro_categoria_igreja_id` (`financeiro_categoria_igreja_id`);

--
-- Índices de tabela `financeiro_contas`
--
ALTER TABLE `financeiro_contas`
  ADD PRIMARY KEY (`financeiro_conta_id`),
  ADD KEY `financeiro_conta_igreja_id` (`financeiro_conta_igreja_id`),
  ADD KEY `financeiro_conta_financeiro_categoria_id` (`financeiro_conta_financeiro_categoria_id`);

--
-- Índices de tabela `financeiro_contas_financeiras`
--
ALTER TABLE `financeiro_contas_financeiras`
  ADD PRIMARY KEY (`financeiro_conta_financeira_id`),
  ADD KEY `financeiro_conta_financeira_igreja_id` (`financeiro_conta_financeira_igreja_id`);

--
-- Índices de tabela `financeiro_movimentacoes`
--
ALTER TABLE `financeiro_movimentacoes`
  ADD PRIMARY KEY (`financeiro_movimentacao_id`),
  ADD KEY `financeiro_movimentacao_igreja_id` (`financeiro_movimentacao_igreja_id`),
  ADD KEY `financeiro_movimentacao_financeiro_conta_id` (`financeiro_movimentacao_financeiro_conta_id`),
  ADD KEY `financeiro_movimentacao_financeiro_categoria_id` (`financeiro_movimentacao_financeiro_categoria_id`),
  ADD KEY `financeiro_movimentacao_financeiro_conta_financeira_id` (`financeiro_movimentacao_financeiro_conta_financeira_id`);

--
-- Índices de tabela `financeiro_pagamentos`
--
ALTER TABLE `financeiro_pagamentos`
  ADD PRIMARY KEY (`financeiro_pagamento_id`),
  ADD KEY `financeiro_pagamento_igreja_id` (`financeiro_pagamento_igreja_id`),
  ADD KEY `financeiro_pagamento_financeiro_conta_id` (`financeiro_pagamento_financeiro_conta_id`);

--
-- Índices de tabela `financeiro_receita_membros`
--
ALTER TABLE `financeiro_receita_membros`
  ADD PRIMARY KEY (`receita_membro_id`),
  ADD KEY `receita_membro_conta_id` (`receita_membro_conta_id`);

--
-- Índices de tabela `financeiro_subcategorias`
--
ALTER TABLE `financeiro_subcategorias`
  ADD PRIMARY KEY (`subcategoria_id`),
  ADD KEY `subcategoria_igreja_id` (`subcategoria_igreja_id`),
  ADD KEY `subcategoria_categoria_id` (`subcategoria_categoria_id`);

--
-- Índices de tabela `igrejas`
--
ALTER TABLE `igrejas`
  ADD PRIMARY KEY (`igreja_id`),
  ADD KEY `fk_igreja_pastor` (`igreja_pastor_id`);

--
-- Índices de tabela `igrejas_liturgias`
--
ALTER TABLE `igrejas_liturgias`
  ADD PRIMARY KEY (`igreja_liturgia_id`),
  ADD KEY `fk_liturgia_igreja` (`igreja_liturgia_igreja_id`);

--
-- Índices de tabela `igrejas_liturgias_itens`
--
ALTER TABLE `igrejas_liturgias_itens`
  ADD PRIMARY KEY (`liturgia_item_id`),
  ADD KEY `fk_item_liturgia` (`liturgia_item_liturgia_id`);

--
-- Índices de tabela `igrejas_mensagens_dominicais`
--
ALTER TABLE `igrejas_mensagens_dominicais`
  ADD PRIMARY KEY (`igreja_mensagem_dominical_id`),
  ADD KEY `fk_boletim_igreja` (`igreja_mensagem_dominical_igreja_id`),
  ADD KEY `fk_boletim_autor` (`igreja_mensagem_dominical_autor_id`);

--
-- Índices de tabela `igrejas_programacao`
--
ALTER TABLE `igrejas_programacao`
  ADD PRIMARY KEY (`programacao_id`),
  ADD KEY `fk_programacao_igreja` (`programacao_igreja_id`);

--
-- Índices de tabela `igrejas_redes_sociais`
--
ALTER TABLE `igrejas_redes_sociais`
  ADD PRIMARY KEY (`rede_id`),
  ADD KEY `rede_igreja_id` (`rede_igreja_id`);

--
-- Índices de tabela `membros`
--
ALTER TABLE `membros`
  ADD PRIMARY KEY (`membro_id`),
  ADD KEY `membro_igreja_id` (`membro_igreja_id`);

--
-- Índices de tabela `membros_cargos_vinculo`
--
ALTER TABLE `membros_cargos_vinculo`
  ADD PRIMARY KEY (`vinculo_id`),
  ADD KEY `vinculo_membro_id` (`vinculo_membro_id`),
  ADD KEY `vinculo_cargo_id` (`vinculo_cargo_id`);

--
-- Índices de tabela `membros_contatos`
--
ALTER TABLE `membros_contatos`
  ADD PRIMARY KEY (`membro_contato_id`),
  ADD KEY `membro_contato_membro_id` (`membro_contato_membro_id`);

--
-- Índices de tabela `membros_enderecos`
--
ALTER TABLE `membros_enderecos`
  ADD PRIMARY KEY (`membro_endereco_id`),
  ADD KEY `membro_endereco_membro_id` (`membro_endereco_membro_id`),
  ADD KEY `membro_endereco_igreja_id` (`membro_endereco_igreja_id`);

--
-- Índices de tabela `membros_fotos`
--
ALTER TABLE `membros_fotos`
  ADD PRIMARY KEY (`membro_foto_id`),
  ADD KEY `membro_foto_membro_id` (`membro_foto_membro_id`);

--
-- Índices de tabela `membros_historico`
--
ALTER TABLE `membros_historico`
  ADD PRIMARY KEY (`membro_historico_id`),
  ADD KEY `membro_historico_membro_id` (`membro_historico_membro_id`);

--
-- Índices de tabela `membros_responsaveis`
--
ALTER TABLE `membros_responsaveis`
  ADD PRIMARY KEY (`parentesco_id`),
  ADD KEY `fk_resp_adulto` (`parentesco_responsavel_id`),
  ADD KEY `fk_resp_crianca` (`parentesco_dependente_id`);

--
-- Índices de tabela `patrimonio_bens`
--
ALTER TABLE `patrimonio_bens`
  ADD PRIMARY KEY (`patrimonio_bem_id`),
  ADD UNIQUE KEY `patrimonio_bem_codigo` (`patrimonio_bem_codigo`),
  ADD KEY `patrimonio_bem_igreja_id` (`patrimonio_bem_igreja_id`),
  ADD KEY `fk_patrimonio_bem_local` (`patrimonio_bem_local_id`);

--
-- Índices de tabela `patrimonio_documentos`
--
ALTER TABLE `patrimonio_documentos`
  ADD PRIMARY KEY (`patrimonio_documento_id`),
  ADD KEY `patrimonio_documento_bem_id` (`patrimonio_documento_bem_id`);

--
-- Índices de tabela `patrimonio_imagens`
--
ALTER TABLE `patrimonio_imagens`
  ADD PRIMARY KEY (`patrimonio_imagem_id`),
  ADD KEY `patrimonio_imagem_bem_id` (`patrimonio_imagem_bem_id`);

--
-- Índices de tabela `patrimonio_locais`
--
ALTER TABLE `patrimonio_locais`
  ADD PRIMARY KEY (`patrimonio_local_id`),
  ADD KEY `patrimonio_local_igreja_id` (`patrimonio_local_igreja_id`);

--
-- Índices de tabela `patrimonio_movimentacoes`
--
ALTER TABLE `patrimonio_movimentacoes`
  ADD PRIMARY KEY (`patrimonio_movimentacao_id`),
  ADD KEY `patrimonio_movimentacao_patrimonio_bem_id` (`patrimonio_movimentacao_patrimonio_bem_id`);

--
-- Índices de tabela `perfis`
--
ALTER TABLE `perfis`
  ADD PRIMARY KEY (`perfil_id`);

--
-- Índices de tabela `sociedades`
--
ALTER TABLE `sociedades`
  ADD PRIMARY KEY (`sociedade_id`),
  ADD KEY `sociedade_igreja_id` (`sociedade_igreja_id`),
  ADD KEY `fk_sociedade_lider` (`sociedade_lider`);

--
-- Índices de tabela `sociedades_eventos`
--
ALTER TABLE `sociedades_eventos`
  ADD PRIMARY KEY (`sociedade_evento_id`),
  ADD KEY `sociedade_evento_igreja_id` (`sociedade_evento_igreja_id`),
  ADD KEY `sociedade_evento_sociedade_id` (`sociedade_evento_sociedade_id`);

--
-- Índices de tabela `sociedades_eventos_presencas`
--
ALTER TABLE `sociedades_eventos_presencas`
  ADD PRIMARY KEY (`sociedade_presenca_id`),
  ADD UNIQUE KEY `idx_presenca_unica` (`sociedade_presenca_evento_id`,`sociedade_presenca_membro_id`),
  ADD KEY `fk_presenca_igreja` (`sociedade_presenca_igreja_id`),
  ADD KEY `fk_presenca_sociedade` (`sociedade_presenca_sociedade_id`),
  ADD KEY `fk_presenca_membro` (`sociedade_presenca_membro_id`);

--
-- Índices de tabela `sociedades_membros`
--
ALTER TABLE `sociedades_membros`
  ADD PRIMARY KEY (`sociedade_membro_id`),
  ADD KEY `sociedade_membro_igreja_id` (`sociedade_membro_igreja_id`),
  ADD KEY `sociedade_membro_sociedade_id` (`sociedade_membro_sociedade_id`),
  ADD KEY `sociedade_membro_membro_id` (`sociedade_membro_membro_id`);

--
-- Índices de tabela `sociedades_orcamentos`
--
ALTER TABLE `sociedades_orcamentos`
  ADD PRIMARY KEY (`sociedade_orcamento_id`),
  ADD KEY `sociedade_orcamento_igreja_id` (`sociedade_orcamento_igreja_id`),
  ADD KEY `sociedade_orcamento_sociedade_id` (`sociedade_orcamento_sociedade_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `usuario_igreja_id` (`usuario_igreja_id`);

--
-- Índices de tabela `usuarios_perfis`
--
ALTER TABLE `usuarios_perfis`
  ADD PRIMARY KEY (`usuario_perfil_id`),
  ADD KEY `usuario_perfil_igreja_id` (`usuario_perfil_igreja_id`),
  ADD KEY `usuario_perfil_usuario_id` (`usuario_perfil_usuario_id`),
  ADD KEY `usuario_perfil_perfil_id` (`usuario_perfil_perfil_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `cargo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `classes_config`
--
ALTER TABLE `classes_config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `classes_escola`
--
ALTER TABLE `classes_escola`
  MODIFY `classe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `classes_membros`
--
ALTER TABLE `classes_membros`
  MODIFY `classe_membro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `classes_presencas`
--
ALTER TABLE `classes_presencas`
  MODIFY `presenca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `documentos`
--
ALTER TABLE `documentos`
  MODIFY `documento_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `documentos_arquivos`
--
ALTER TABLE `documentos_arquivos`
  MODIFY `documento_arquivo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `documentos_categorias`
--
ALTER TABLE `documentos_categorias`
  MODIFY `documento_categoria_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financeiro_categorias`
--
ALTER TABLE `financeiro_categorias`
  MODIFY `financeiro_categoria_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financeiro_contas`
--
ALTER TABLE `financeiro_contas`
  MODIFY `financeiro_conta_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financeiro_contas_financeiras`
--
ALTER TABLE `financeiro_contas_financeiras`
  MODIFY `financeiro_conta_financeira_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financeiro_movimentacoes`
--
ALTER TABLE `financeiro_movimentacoes`
  MODIFY `financeiro_movimentacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financeiro_pagamentos`
--
ALTER TABLE `financeiro_pagamentos`
  MODIFY `financeiro_pagamento_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financeiro_receita_membros`
--
ALTER TABLE `financeiro_receita_membros`
  MODIFY `receita_membro_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financeiro_subcategorias`
--
ALTER TABLE `financeiro_subcategorias`
  MODIFY `subcategoria_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `igrejas`
--
ALTER TABLE `igrejas`
  MODIFY `igreja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `igrejas_liturgias`
--
ALTER TABLE `igrejas_liturgias`
  MODIFY `igreja_liturgia_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `igrejas_liturgias_itens`
--
ALTER TABLE `igrejas_liturgias_itens`
  MODIFY `liturgia_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `igrejas_mensagens_dominicais`
--
ALTER TABLE `igrejas_mensagens_dominicais`
  MODIFY `igreja_mensagem_dominical_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `igrejas_programacao`
--
ALTER TABLE `igrejas_programacao`
  MODIFY `programacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `igrejas_redes_sociais`
--
ALTER TABLE `igrejas_redes_sociais`
  MODIFY `rede_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `membros`
--
ALTER TABLE `membros`
  MODIFY `membro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

--
-- AUTO_INCREMENT de tabela `membros_cargos_vinculo`
--
ALTER TABLE `membros_cargos_vinculo`
  MODIFY `vinculo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `membros_contatos`
--
ALTER TABLE `membros_contatos`
  MODIFY `membro_contato_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `membros_enderecos`
--
ALTER TABLE `membros_enderecos`
  MODIFY `membro_endereco_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `membros_fotos`
--
ALTER TABLE `membros_fotos`
  MODIFY `membro_foto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `membros_historico`
--
ALTER TABLE `membros_historico`
  MODIFY `membro_historico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `membros_responsaveis`
--
ALTER TABLE `membros_responsaveis`
  MODIFY `parentesco_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `patrimonio_bens`
--
ALTER TABLE `patrimonio_bens`
  MODIFY `patrimonio_bem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `patrimonio_documentos`
--
ALTER TABLE `patrimonio_documentos`
  MODIFY `patrimonio_documento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `patrimonio_imagens`
--
ALTER TABLE `patrimonio_imagens`
  MODIFY `patrimonio_imagem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `patrimonio_locais`
--
ALTER TABLE `patrimonio_locais`
  MODIFY `patrimonio_local_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `patrimonio_movimentacoes`
--
ALTER TABLE `patrimonio_movimentacoes`
  MODIFY `patrimonio_movimentacao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `perfis`
--
ALTER TABLE `perfis`
  MODIFY `perfil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `sociedades`
--
ALTER TABLE `sociedades`
  MODIFY `sociedade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `sociedades_eventos`
--
ALTER TABLE `sociedades_eventos`
  MODIFY `sociedade_evento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `sociedades_eventos_presencas`
--
ALTER TABLE `sociedades_eventos_presencas`
  MODIFY `sociedade_presenca_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sociedades_membros`
--
ALTER TABLE `sociedades_membros`
  MODIFY `sociedade_membro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de tabela `sociedades_orcamentos`
--
ALTER TABLE `sociedades_orcamentos`
  MODIFY `sociedade_orcamento_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios_perfis`
--
ALTER TABLE `usuarios_perfis`
  MODIFY `usuario_perfil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `classes_config`
--
ALTER TABLE `classes_config`
  ADD CONSTRAINT `fk_config_igreja` FOREIGN KEY (`config_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `classes_escola`
--
ALTER TABLE `classes_escola`
  ADD CONSTRAINT `classes_escola_ibfk_1` FOREIGN KEY (`classe_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `classes_escola_ibfk_2` FOREIGN KEY (`classe_professor_id`) REFERENCES `membros` (`membro_id`);

--
-- Restrições para tabelas `classes_membros`
--
ALTER TABLE `classes_membros`
  ADD CONSTRAINT `classes_membros_ibfk_1` FOREIGN KEY (`classe_membro_classe_id`) REFERENCES `classes_escola` (`classe_id`),
  ADD CONSTRAINT `classes_membros_ibfk_2` FOREIGN KEY (`classe_membro_membro_id`) REFERENCES `membros` (`membro_id`);

--
-- Restrições para tabelas `classes_presencas`
--
ALTER TABLE `classes_presencas`
  ADD CONSTRAINT `classes_presencas_ibfk_1` FOREIGN KEY (`presenca_classe_id`) REFERENCES `classes_escola` (`classe_id`),
  ADD CONSTRAINT `classes_presencas_ibfk_2` FOREIGN KEY (`presenca_membro_id`) REFERENCES `membros` (`membro_id`);

--
-- Restrições para tabelas `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`documento_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `documentos_ibfk_2` FOREIGN KEY (`documento_documento_categoria_id`) REFERENCES `documentos_categorias` (`documento_categoria_id`);

--
-- Restrições para tabelas `documentos_arquivos`
--
ALTER TABLE `documentos_arquivos`
  ADD CONSTRAINT `documentos_arquivos_ibfk_1` FOREIGN KEY (`documento_arquivo_documento_id`) REFERENCES `documentos` (`documento_id`);

--
-- Restrições para tabelas `documentos_categorias`
--
ALTER TABLE `documentos_categorias`
  ADD CONSTRAINT `documentos_categorias_ibfk_1` FOREIGN KEY (`documento_categoria_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `financeiro_categorias`
--
ALTER TABLE `financeiro_categorias`
  ADD CONSTRAINT `financeiro_categorias_ibfk_1` FOREIGN KEY (`financeiro_categoria_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `financeiro_contas`
--
ALTER TABLE `financeiro_contas`
  ADD CONSTRAINT `financeiro_contas_ibfk_1` FOREIGN KEY (`financeiro_conta_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `financeiro_contas_ibfk_2` FOREIGN KEY (`financeiro_conta_financeiro_categoria_id`) REFERENCES `financeiro_categorias` (`financeiro_categoria_id`);

--
-- Restrições para tabelas `financeiro_contas_financeiras`
--
ALTER TABLE `financeiro_contas_financeiras`
  ADD CONSTRAINT `financeiro_contas_financeiras_ibfk_1` FOREIGN KEY (`financeiro_conta_financeira_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `financeiro_movimentacoes`
--
ALTER TABLE `financeiro_movimentacoes`
  ADD CONSTRAINT `financeiro_movimentacoes_ibfk_1` FOREIGN KEY (`financeiro_movimentacao_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `financeiro_movimentacoes_ibfk_2` FOREIGN KEY (`financeiro_movimentacao_financeiro_conta_id`) REFERENCES `financeiro_contas` (`financeiro_conta_id`),
  ADD CONSTRAINT `financeiro_movimentacoes_ibfk_3` FOREIGN KEY (`financeiro_movimentacao_financeiro_categoria_id`) REFERENCES `financeiro_categorias` (`financeiro_categoria_id`),
  ADD CONSTRAINT `financeiro_movimentacoes_ibfk_4` FOREIGN KEY (`financeiro_movimentacao_financeiro_conta_financeira_id`) REFERENCES `financeiro_contas_financeiras` (`financeiro_conta_financeira_id`);

--
-- Restrições para tabelas `financeiro_pagamentos`
--
ALTER TABLE `financeiro_pagamentos`
  ADD CONSTRAINT `financeiro_pagamentos_ibfk_1` FOREIGN KEY (`financeiro_pagamento_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `financeiro_pagamentos_ibfk_2` FOREIGN KEY (`financeiro_pagamento_financeiro_conta_id`) REFERENCES `financeiro_contas` (`financeiro_conta_id`);

--
-- Restrições para tabelas `financeiro_receita_membros`
--
ALTER TABLE `financeiro_receita_membros`
  ADD CONSTRAINT `financeiro_receita_membros_ibfk_1` FOREIGN KEY (`receita_membro_conta_id`) REFERENCES `financeiro_contas` (`financeiro_conta_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `igrejas`
--
ALTER TABLE `igrejas`
  ADD CONSTRAINT `fk_igreja_pastor` FOREIGN KEY (`igreja_pastor_id`) REFERENCES `membros` (`membro_id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `igrejas_liturgias`
--
ALTER TABLE `igrejas_liturgias`
  ADD CONSTRAINT `fk_liturgia_igreja` FOREIGN KEY (`igreja_liturgia_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `igrejas_liturgias_itens`
--
ALTER TABLE `igrejas_liturgias_itens`
  ADD CONSTRAINT `fk_item_liturgia` FOREIGN KEY (`liturgia_item_liturgia_id`) REFERENCES `igrejas_liturgias` (`igreja_liturgia_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `igrejas_mensagens_dominicais`
--
ALTER TABLE `igrejas_mensagens_dominicais`
  ADD CONSTRAINT `fk_boletim_autor` FOREIGN KEY (`igreja_mensagem_dominical_autor_id`) REFERENCES `membros` (`membro_id`),
  ADD CONSTRAINT `fk_boletim_igreja` FOREIGN KEY (`igreja_mensagem_dominical_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `igrejas_programacao`
--
ALTER TABLE `igrejas_programacao`
  ADD CONSTRAINT `fk_programacao_igreja` FOREIGN KEY (`programacao_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `igrejas_redes_sociais`
--
ALTER TABLE `igrejas_redes_sociais`
  ADD CONSTRAINT `igrejas_redes_sociais_ibfk_1` FOREIGN KEY (`rede_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `membros`
--
ALTER TABLE `membros`
  ADD CONSTRAINT `membros_ibfk_1` FOREIGN KEY (`membro_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `membros_cargos_vinculo`
--
ALTER TABLE `membros_cargos_vinculo`
  ADD CONSTRAINT `fk_cargo` FOREIGN KEY (`vinculo_cargo_id`) REFERENCES `cargos` (`cargo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_membro` FOREIGN KEY (`vinculo_membro_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `membros_contatos`
--
ALTER TABLE `membros_contatos`
  ADD CONSTRAINT `membros_contatos_ibfk_1` FOREIGN KEY (`membro_contato_membro_id`) REFERENCES `membros` (`membro_id`);

--
-- Restrições para tabelas `membros_enderecos`
--
ALTER TABLE `membros_enderecos`
  ADD CONSTRAINT `membros_enderecos_ibfk_1` FOREIGN KEY (`membro_endereco_membro_id`) REFERENCES `membros` (`membro_id`),
  ADD CONSTRAINT `membros_enderecos_ibfk_2` FOREIGN KEY (`membro_endereco_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `membros_fotos`
--
ALTER TABLE `membros_fotos`
  ADD CONSTRAINT `membros_fotos_ibfk_1` FOREIGN KEY (`membro_foto_membro_id`) REFERENCES `membros` (`membro_id`);

--
-- Restrições para tabelas `membros_historico`
--
ALTER TABLE `membros_historico`
  ADD CONSTRAINT `membros_historico_ibfk_1` FOREIGN KEY (`membro_historico_membro_id`) REFERENCES `membros` (`membro_id`);

--
-- Restrições para tabelas `membros_responsaveis`
--
ALTER TABLE `membros_responsaveis`
  ADD CONSTRAINT `fk_resp_adulto` FOREIGN KEY (`parentesco_responsavel_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resp_crianca` FOREIGN KEY (`parentesco_dependente_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `patrimonio_bens`
--
ALTER TABLE `patrimonio_bens`
  ADD CONSTRAINT `fk_patrimonio_bem_local` FOREIGN KEY (`patrimonio_bem_local_id`) REFERENCES `patrimonio_locais` (`patrimonio_local_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patrimonio_bens_ibfk_1` FOREIGN KEY (`patrimonio_bem_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `patrimonio_documentos`
--
ALTER TABLE `patrimonio_documentos`
  ADD CONSTRAINT `patrimonio_documentos_ibfk_1` FOREIGN KEY (`patrimonio_documento_bem_id`) REFERENCES `patrimonio_bens` (`patrimonio_bem_id`);

--
-- Restrições para tabelas `patrimonio_imagens`
--
ALTER TABLE `patrimonio_imagens`
  ADD CONSTRAINT `patrimonio_imagens_ibfk_1` FOREIGN KEY (`patrimonio_imagem_bem_id`) REFERENCES `patrimonio_bens` (`patrimonio_bem_id`);

--
-- Restrições para tabelas `patrimonio_locais`
--
ALTER TABLE `patrimonio_locais`
  ADD CONSTRAINT `patrimonio_locais_ibfk_1` FOREIGN KEY (`patrimonio_local_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `patrimonio_movimentacoes`
--
ALTER TABLE `patrimonio_movimentacoes`
  ADD CONSTRAINT `patrimonio_movimentacoes_ibfk_1` FOREIGN KEY (`patrimonio_movimentacao_patrimonio_bem_id`) REFERENCES `patrimonio_bens` (`patrimonio_bem_id`);

--
-- Restrições para tabelas `sociedades`
--
ALTER TABLE `sociedades`
  ADD CONSTRAINT `fk_sociedade_lider` FOREIGN KEY (`sociedade_lider`) REFERENCES `membros` (`membro_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sociedades_ibfk_1` FOREIGN KEY (`sociedade_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `sociedades_eventos`
--
ALTER TABLE `sociedades_eventos`
  ADD CONSTRAINT `sociedades_eventos_ibfk_1` FOREIGN KEY (`sociedade_evento_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sociedades_eventos_ibfk_2` FOREIGN KEY (`sociedade_evento_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `sociedades_eventos_presencas`
--
ALTER TABLE `sociedades_eventos_presencas`
  ADD CONSTRAINT `fk_presenca_evento` FOREIGN KEY (`sociedade_presenca_evento_id`) REFERENCES `sociedades_eventos` (`sociedade_evento_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_presenca_igreja` FOREIGN KEY (`sociedade_presenca_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_presenca_membro` FOREIGN KEY (`sociedade_presenca_membro_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_presenca_sociedade` FOREIGN KEY (`sociedade_presenca_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `sociedades_membros`
--
ALTER TABLE `sociedades_membros`
  ADD CONSTRAINT `sociedades_membros_ibfk_1` FOREIGN KEY (`sociedade_membro_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `sociedades_membros_ibfk_2` FOREIGN KEY (`sociedade_membro_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`),
  ADD CONSTRAINT `sociedades_membros_ibfk_3` FOREIGN KEY (`sociedade_membro_membro_id`) REFERENCES `membros` (`membro_id`);

--
-- Restrições para tabelas `sociedades_orcamentos`
--
ALTER TABLE `sociedades_orcamentos`
  ADD CONSTRAINT `sociedades_orcamentos_ibfk_1` FOREIGN KEY (`sociedade_orcamento_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `sociedades_orcamentos_ibfk_2` FOREIGN KEY (`sociedade_orcamento_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`usuario_igreja_id`) REFERENCES `igrejas` (`igreja_id`);

--
-- Restrições para tabelas `usuarios_perfis`
--
ALTER TABLE `usuarios_perfis`
  ADD CONSTRAINT `usuarios_perfis_ibfk_1` FOREIGN KEY (`usuario_perfil_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  ADD CONSTRAINT `usuarios_perfis_ibfk_2` FOREIGN KEY (`usuario_perfil_usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `usuarios_perfis_ibfk_3` FOREIGN KEY (`usuario_perfil_perfil_id`) REFERENCES `perfis` (`perfil_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
