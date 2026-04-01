-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql200.4sql.net
-- Tempo de geração: 01/04/2026 às 02:46
-- Versão do servidor: 11.4.10-MariaDB
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sq_40883683_EKKLESIA`
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
(12, 'Líder de Jovens (UPA / UMP)'),
(13, 'Líder de Adolescentes'),
(14, 'Líder de Crianças'),
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `classes_escola`
--

INSERT INTO `classes_escola` (`classe_id`, `classe_igreja_id`, `classe_nome`, `classe_professor_id`, `classe_idade_min`, `classe_idade_max`, `classe_senha`) VALUES
(1, 1, 'Cordeirinhos', 196, 1, 3, '123456'),
(2, 1, 'Arca de Noé', 174, 4, 6, '123456'),
(3, 1, 'Josias', 227, 7, 12, NULL),
(4, 1, 'Timóteo', 192, 13, 17, '123456'),
(5, 1, 'Adultos I', 243, 18, 99, '123456'),
(6, 1, 'Adultos II', 175, 18, 99, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `classes_membros`
--

CREATE TABLE `classes_membros` (
  `classe_membro_id` int(11) NOT NULL,
  `classe_membro_classe_id` int(11) DEFAULT NULL,
  `classe_membro_membro_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `classes_membros`
--

INSERT INTO `classes_membros` (`classe_membro_id`, `classe_membro_classe_id`, `classe_membro_membro_id`) VALUES
(1, 5, 230),
(2, 5, 177),
(3, 5, 165),
(4, 5, 223),
(5, 5, 136),
(6, 5, 196),
(7, 6, 228),
(8, 6, 215),
(9, 6, 224),
(10, 6, 194),
(11, 6, 207),
(12, 6, 222),
(13, 6, 164),
(14, 2, 229),
(15, 2, 200),
(16, 2, 192),
(17, 2, 160),
(18, 1, 217),
(35, 6, 238),
(20, 3, 193),
(21, 3, 211),
(22, 3, 151),
(23, 3, 178),
(24, 3, 202),
(25, 4, 188),
(26, 4, 132),
(27, 4, 226),
(28, 4, 185),
(29, 4, 131),
(30, 4, 179),
(31, 5, 1),
(32, 6, 149),
(33, 5, 3),
(34, 1, 218),
(37, 5, 243);

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `classes_presencas`
--

INSERT INTO `classes_presencas` (`presenca_id`, `presenca_classe_id`, `presenca_membro_id`, `presenca_data`, `presenca_status`) VALUES
(1, 5, 1, '2026-03-21', 1),
(2, 5, 1, '2026-03-23', 1),
(3, 5, 230, '2026-03-24', 1),
(4, 5, 196, '2026-03-24', 1),
(5, 5, 223, '2026-03-24', 1),
(6, 5, 177, '2026-03-24', 0),
(7, 5, 136, '2026-03-24', 1),
(8, 5, 165, '2026-03-24', 0),
(9, 5, 1, '2026-03-24', 1),
(10, 5, 3, '2026-03-24', 1),
(11, 1, 217, '2026-03-24', 1),
(12, 1, 186, '2026-03-24', 0),
(13, 1, 218, '2026-03-24', 0),
(14, 6, 238, '2026-03-25', 0),
(15, 6, 149, '2026-03-25', 0),
(16, 6, 224, '2026-03-25', 1),
(17, 6, 228, '2026-03-25', 1),
(18, 6, 222, '2026-03-25', 1),
(19, 6, 194, '2026-03-25', 1),
(20, 6, 215, '2026-03-25', 1),
(21, 6, 207, '2026-03-25', 0),
(22, 6, 164, '2026-03-25', 1),
(23, 6, 238, '2026-03-26', 1),
(24, 6, 149, '2026-03-26', 1),
(25, 6, 224, '2026-03-26', 0),
(26, 6, 228, '2026-03-26', 1),
(27, 6, 222, '2026-03-26', 1),
(28, 6, 215, '2026-03-26', 1),
(29, 6, 194, '2026-03-26', 1),
(30, 6, 207, '2026-03-26', 0),
(31, 6, 164, '2026-03-26', 1),
(32, 5, 1, '2026-03-26', 1),
(33, 5, 230, '2026-03-26', 0),
(34, 5, 3, '2026-03-26', 1),
(35, 5, 196, '2026-03-26', 1),
(36, 5, 223, '2026-03-26', 1),
(37, 5, 177, '2026-03-26', 0),
(38, 5, 136, '2026-03-26', 1),
(39, 5, 165, '2026-03-26', 1),
(40, 5, 230, '2026-03-27', 1),
(41, 5, 1, '2026-03-27', 1),
(42, 5, 3, '2026-03-27', 1),
(43, 5, 196, '2026-03-27', 0),
(44, 5, 223, '2026-03-27', 1),
(45, 5, 177, '2026-03-27', 1),
(46, 5, 136, '2026-03-27', 1),
(47, 5, 165, '2026-03-27', 1),
(48, 5, 243, '2026-03-27', 0),
(49, 4, 188, '2026-03-30', 1),
(50, 4, 179, '2026-03-30', 0),
(51, 4, 185, '2026-03-30', 1),
(52, 4, 132, '2026-03-30', 1),
(53, 4, 226, '2026-03-30', 1),
(54, 4, 131, '2026-03-30', 1);

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `documentos`
--

INSERT INTO `documentos` (`documento_id`, `documento_igreja_id`, `documento_documento_categoria_id`, `documento_nome`, `documento_descricao`, `documento_data_referencia`, `documento_status`) VALUES
(5, 1, 4, 'Constituição 2018', '', '2026-03-31', 'ativo'),
(4, 1, 4, 'Constituição Original', 'Primeiro documento feito.', '2026-03-31', 'ativo'),
(6, 1, 7, 'Manual do Candidato para o Ministério Pastoral', '', '2026-03-31', 'ativo'),
(7, 1, 7, 'Manual Presbiteriano 2025', '', '2026-03-31', 'ativo'),
(8, 1, 8, 'REGIMENTO INTERNO DA SECRETARIA EXECUTIVA ', '', '2026-03-31', 'ativo'),
(9, 1, 8, 'Regimento Interno da Tesouraria da IPB', '', '2026-03-31', 'ativo'),
(10, 1, 8, 'Regimento Interno do Supremo Concílio da IPB', '', '2026-03-31', 'ativo'),
(11, 1, 7, 'Regulamento para Confecção das Atas', '', '2026-03-31', 'ativo'),
(12, 1, 5, 'Estatuto da IPB', '', '2026-03-31', 'ativo'),
(13, 1, 6, 'LGPD nas Igrejas e Concílios', '', '2026-03-31', 'ativo'),
(14, 1, 9, 'Imagens do Projeto da Construção', 'Anexo montagens em 3d do Projeto da Construção do Templo.', '2026-03-31', 'ativo');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `documentos_arquivos`
--

INSERT INTO `documentos_arquivos` (`documento_arquivo_id`, `documento_arquivo_documento_id`, `documento_arquivo_nome`, `documento_arquivo_caminho`, `documento_arquivo_tipo`, `documento_arquivo_data`) VALUES
(4, 4, 'constituicao_original.pdf', 'doc_4_1774934710_25.pdf', 'application/pdf', '2026-03-30 22:25:10'),
(5, 5, 'Constituição da IPB.pdf', 'doc_5_1774934826_73.pdf', 'application/pdf', '2026-03-30 22:27:06'),
(6, 6, 'Manual do Candidato.pdf', 'doc_6_1774934990_38.pdf', 'application/pdf', '2026-03-30 22:29:50'),
(7, 7, 'Manual Presbiteriano 2025.pdf', 'doc_7_1774935030_95.pdf', 'application/pdf', '2026-03-30 22:30:30'),
(8, 8, 'Regimento Interno da Secretaria Executiva SC_IPB.pdf', 'doc_8_1774935098_19.pdf', 'application/pdf', '2026-03-30 22:31:38'),
(9, 9, 'Regimento Interno da Tesouraria SC_IPB.pdf', 'doc_9_1774935132_41.pdf', 'application/pdf', '2026-03-30 22:32:12'),
(10, 10, 'Regimento Interno do SC_IPB.pdf', 'doc_10_1774935166_54.pdf', 'application/pdf', '2026-03-30 22:32:46'),
(11, 11, 'Regulamento para Confecção das Atas.pdf', 'doc_11_1774935206_12.pdf', 'application/pdf', '2026-03-30 22:33:26'),
(12, 12, 'Estatuto da IPB.pdf', 'doc_12_1774935316_88.pdf', 'application/pdf', '2026-03-30 22:35:16'),
(13, 13, 'LGPD nas Igrejas e Concílios.pdf', 'doc_13_1774935343_58.pdf', 'application/pdf', '2026-03-30 22:35:43'),
(14, 14, 'Projeto  (1).jfif', 'doc_14_1774935570_38.jfif', 'image/jpeg', '2026-03-30 22:39:30'),
(15, 14, 'Projeto  (1).jpg', 'doc_14_1774935570_43.jpg', 'image/jpeg', '2026-03-30 22:39:30'),
(16, 14, 'Projeto  (2).jfif', 'doc_14_1774935570_31.jfif', 'image/jpeg', '2026-03-30 22:39:30'),
(17, 14, 'Projeto  (2).jpg', 'doc_14_1774935570_74.jpg', 'image/jpeg', '2026-03-30 22:39:30'),
(18, 14, 'Projeto  (3).jfif', 'doc_14_1774935570_51.jfif', 'image/jpeg', '2026-03-30 22:39:30'),
(19, 14, 'Projeto  (3).jpg', 'doc_14_1774935570_36.jpg', 'image/jpeg', '2026-03-30 22:39:30'),
(20, 14, 'Projeto  (4).jfif', 'doc_14_1774935570_73.jfif', 'image/jpeg', '2026-03-30 22:39:30'),
(21, 14, 'Projeto  (4).jpg', 'doc_14_1774935570_25.jpg', 'image/jpeg', '2026-03-30 22:39:30'),
(22, 14, 'Projeto  (5).jpg', 'doc_14_1774935570_62.jpg', 'image/jpeg', '2026-03-30 22:39:30'),
(23, 14, 'Projeto  (6).jpg', 'doc_14_1774935570_34.jpg', 'image/jpeg', '2026-03-30 22:39:30'),
(24, 14, 'Projeto  (7).jpg', 'doc_14_1774935570_50.jpg', 'image/jpeg', '2026-03-30 22:39:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `documentos_categorias`
--

CREATE TABLE `documentos_categorias` (
  `documento_categoria_id` int(11) NOT NULL,
  `documento_categoria_igreja_id` int(11) DEFAULT NULL,
  `documento_categoria_nome` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `documentos_categorias`
--

INSERT INTO `documentos_categorias` (`documento_categoria_id`, `documento_categoria_igreja_id`, `documento_categoria_nome`) VALUES
(1, 1, 'Escritura'),
(2, 1, 'Imagens Institucionais'),
(3, 1, 'Atas Gerais'),
(4, 1, 'Constituição'),
(5, 1, 'Estatuto'),
(6, 1, 'LGPD'),
(7, 1, 'Manuais'),
(8, 1, 'Regimentos'),
(9, 1, 'Imagens do Projeto da Construção');

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_categorias`
--

CREATE TABLE `financeiro_categorias` (
  `financeiro_categoria_id` int(11) NOT NULL,
  `financeiro_categoria_igreja_id` int(11) DEFAULT NULL,
  `financeiro_categoria_nome` varchar(100) DEFAULT NULL,
  `financeiro_categoria_tipo` enum('entrada','saida') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `financeiro_categorias`
--

INSERT INTO `financeiro_categorias` (`financeiro_categoria_id`, `financeiro_categoria_igreja_id`, `financeiro_categoria_nome`, `financeiro_categoria_tipo`) VALUES
(23, 1, 'Escola Dominical', 'entrada'),
(22, 1, 'Escola Dominical', 'saida'),
(15, 1, 'Despesas Fixas (Recorrentes)', 'saida'),
(16, 1, 'Manutenção e Consumo', 'saida'),
(17, 1, 'Social / Benevolência', 'saida'),
(18, 1, 'Culto - Dizimo e Ofertas', 'entrada'),
(19, 1, 'Ofertas Alçadas (Construção/Cozinha/Doação)', 'entrada'),
(20, 1, 'Rendimentos Financeiros', 'entrada'),
(21, 1, 'Sociedades (Anual)', 'saida');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `financeiro_contas`
--

INSERT INTO `financeiro_contas` (`financeiro_conta_id`, `financeiro_conta_igreja_id`, `financeiro_conta_financeiro_categoria_id`, `financeiro_conta_descricao`, `financeiro_conta_valor`, `financeiro_conta_tipo`, `financeiro_conta_data_vencimento`, `financeiro_conta_pago`, `financeiro_conta_data_pagamento`, `financeiro_conta_reembolso`, `financeiro_conta_comprovante`, `financeiro_conta_nota_fiscal`) VALUES
(5, 1, 5, 'Internet', '150.00', 'saida', '2026-03-25', 1, '2026-03-23', 0, NULL, NULL),
(26, 1, 20, 'Pão Francês', '40.00', 'saida', '2026-03-27', 1, '2026-03-27', 1, NULL, NULL),
(6, 1, 14, 'Oferta Dizimo', '1450.00', 'entrada', '2026-03-24', 1, '2026-03-23', 0, NULL, NULL),
(4, 1, 7, 'Janeiro 2026', '25.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, '1/financeiro/comprovantes/2026/03/comprovante_1774729527_4074.jpg', NULL),
(7, 1, 5, 'Internet Vivo', '100.00', 'saida', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(8, 1, 13, 'Oferta Culto', '1450.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(9, 1, 3, 'Conta de Agua - Janeiro 2025', '285.00', 'saida', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(10, 1, 12, 'Compra de Material de Escritorio', '190.00', 'saida', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(11, 1, 14, 'Dizimo', '5238.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(12, 1, 13, 'Ofertas', '2500.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(14, 1, 14, 'Dizimo Março 2026', '5000.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(15, 1, 13, 'Ofertas Março 2026', '2500.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(16, 1, 14, 'Dizimos e Ofertas', '1000.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(17, 1, 14, 'Teste de dizimos', '1000.00', 'entrada', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(18, 1, 5, 'Internet Vivo', '150.00', 'saida', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(19, 1, 10, 'Produtos de Limpeza', '132.00', 'saida', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(20, 1, 4, 'Luz Fevereiro', '50.00', 'saida', '2026-03-23', 1, '2026-03-23', 0, NULL, NULL),
(21, 1, 18, 'Compra de Vasos', '150.00', 'saida', '2026-03-26', 1, '2026-03-27', 0, '1/financeiro/comprovantes/2026/03/comprovante_1774727896_2786.jpg', '1/financeiro/notasfiscais/2026/03/notafiscal_1774727908_5378.jpg'),
(25, 1, 20, 'Pão Francês', '35.00', 'saida', '2026-03-27', 1, '2026-03-27', 1, '1/financeiro/comprovantes/2026/03/comprovante_1774726808_9678.jpg', '1/financeiro/notasfiscais/2026/03/notafiscal_1774726704_9090.jpg'),
(27, 1, 14, 'Dizimo Março 2026', '1500.00', 'entrada', '2026-03-27', 1, '2026-03-27', 0, NULL, NULL),
(28, 1, 5, 'Internet Vivo', '50.00', 'saida', '2026-03-28', 1, '2026-03-28', 0, NULL, NULL),
(29, 1, 14, 'dizimo', '5000.00', 'entrada', '2026-03-29', 1, '2026-03-29', 0, NULL, NULL),
(30, 1, 13, 'oferta', '2000.00', 'entrada', '2026-03-29', 1, '2026-03-29', 0, NULL, NULL);

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `financeiro_contas_financeiras`
--

INSERT INTO `financeiro_contas_financeiras` (`financeiro_conta_financeira_id`, `financeiro_conta_financeira_igreja_id`, `financeiro_conta_financeira_nome`, `financeiro_conta_financeira_tipo`, `financeiro_conta_financeira_saldo`, `financeiro_conta_financeira_status`) VALUES
(1, 1, 'Banco do Brasil', 'banco', '18988.00', 'ativo'),
(2, 1, 'Caixinha', 'caixa', '7480.00', 'ativo'),
(3, 1, 'Banco Bradesco', 'banco', '14018.00', 'ativo');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `financeiro_movimentacoes`
--

INSERT INTO `financeiro_movimentacoes` (`financeiro_movimentacao_id`, `financeiro_movimentacao_igreja_id`, `financeiro_movimentacao_financeiro_conta_id`, `financeiro_movimentacao_financeiro_categoria_id`, `financeiro_movimentacao_financeiro_conta_financeira_id`, `financeiro_movimentacao_tipo`, `financeiro_movimentacao_valor`, `financeiro_movimentacao_data`, `financeiro_movimentacao_descricao`, `financeiro_movimentacao_origem`) VALUES
(1, 1, NULL, NULL, 2, 'saida', '500.00', '2026-03-23 08:34:22', 'Transf. para conta ID 1: Deposito no Banco do Brasil', 'transferencia'),
(2, 1, NULL, NULL, 1, 'saida', '500.00', '2026-03-23 08:42:02', 'Transferência enviada: Saque para o caixinha', 'transferencia'),
(3, 1, NULL, NULL, 2, 'saida', '500.00', '2026-03-23 08:42:02', 'Transferência recebida: Saque para o caixinha', 'transferencia'),
(4, 1, NULL, NULL, 2, 'saida', '500.00', '2026-03-23 08:46:56', 'Transferência enviada: Deposito para o banco', 'transferencia'),
(5, 1, NULL, NULL, 1, 'entrada', '500.00', '2026-03-23 08:46:56', 'Transferência recebida: Deposito para o banco', 'transferencia'),
(6, 1, NULL, NULL, 1, 'saida', '25.00', '2026-03-23 00:00:00', 'Pagamento de conta ID: 4', 'pagamento'),
(7, 1, NULL, NULL, 2, 'saida', '495.00', '2026-03-23 00:00:00', 'Pagamento de conta ID: 2', 'pagamento'),
(8, 1, NULL, NULL, 2, 'saida', '150.00', '2026-03-23 00:00:00', 'Pagamento de conta ID: 5', 'pagamento'),
(9, 1, NULL, NULL, 2, 'saida', '1450.00', '2026-03-23 00:00:00', 'Pagamento de conta ID: 6', 'pagamento'),
(10, 1, NULL, NULL, 2, 'saida', '100.00', '2026-03-23 00:00:00', 'Baixa de Despesa: Internet Vivo', 'pagamento'),
(11, 1, NULL, NULL, 2, 'entrada', '1450.00', '2026-03-23 00:00:00', 'Baixa de Receita: Oferta Culto', 'pagamento'),
(12, 1, NULL, NULL, 1, 'saida', '285.00', '2026-03-23 00:00:00', 'Baixa de Despesa: Conta de Agua - Janeiro 2025', 'pagamento'),
(13, 1, NULL, NULL, 1, 'saida', '190.00', '2026-03-23 00:00:00', 'Baixa de Despesa: Compra de Material de Escritorio', 'pagamento'),
(14, 1, NULL, NULL, 1, 'entrada', '5238.00', '2026-03-23 00:00:00', 'Baixa de Receita: Dizimo', 'pagamento'),
(15, 1, NULL, NULL, 2, 'entrada', '2500.00', '2026-03-23 00:00:00', 'Baixa de Receita: Ofertas', 'pagamento'),
(16, 1, NULL, NULL, 2, 'entrada', '5000.00', '2026-03-23 00:00:00', 'Baixa de Receita: Dizimo Março 2026', 'pagamento'),
(17, 1, NULL, NULL, 1, 'entrada', '2500.00', '2026-03-23 00:00:00', 'Baixa de Receita: Ofertas Março 2026', 'pagamento'),
(18, 1, NULL, NULL, 1, 'entrada', '1000.00', '2026-03-23 00:00:00', 'Baixa de Receita: Dizimos e Ofertas', 'pagamento'),
(19, 1, NULL, NULL, 1, 'entrada', '1000.00', '2026-03-23 00:00:00', 'Baixa de Receita: Teste de dizimos', 'pagamento'),
(20, 1, NULL, NULL, 2, 'saida', '150.00', '2026-03-23 17:39:57', 'Baixa de Despesa: Internet Vivo', 'pagamento'),
(21, 1, NULL, NULL, 2, 'saida', '5000.00', '2026-03-23 14:41:30', 'Transferência enviada: Deposito Bancario.', 'transferencia'),
(22, 1, NULL, NULL, 1, 'entrada', '5000.00', '2026-03-23 14:41:30', 'Transferência recebida: Deposito Bancario.', 'transferencia'),
(23, 1, NULL, NULL, 2, 'saida', '2000.00', '2026-03-23 15:38:42', 'Transferência enviada: Depósito Bancário', 'transferencia'),
(24, 1, NULL, NULL, 1, 'entrada', '2000.00', '2026-03-23 15:38:42', 'Transferência recebida: Depósito Bancário', 'transferencia'),
(25, 1, NULL, NULL, 1, 'saida', '600.00', '2026-03-23 19:18:59', 'Transferência enviada: Transferência entre contas.', 'transferencia'),
(26, 1, NULL, NULL, 3, 'entrada', '600.00', '2026-03-23 19:18:59', 'Transferência recebida: Transferência entre contas.', 'transferencia'),
(27, 1, NULL, NULL, 3, 'saida', '132.00', '2026-03-23 22:20:32', 'Baixa de Despesa: Produtos de Limpeza', 'pagamento'),
(28, 1, NULL, NULL, 2, 'saida', '50.00', '2026-03-23 23:12:33', 'Baixa de Despesa: Luz Fevereiro', 'pagamento'),
(29, 1, NULL, NULL, 1, 'saida', '10000.00', '2026-03-27 08:31:03', 'Transferência enviada: ', 'transferencia'),
(30, 1, NULL, NULL, 3, 'entrada', '10000.00', '2026-03-27 08:31:03', 'Transferência recebida: ', 'transferencia'),
(31, 1, NULL, NULL, 2, 'saida', '35.00', '2026-03-27 13:47:33', 'Baixa de Despesa: Pão Francês', 'pagamento'),
(32, 1, NULL, NULL, 1, 'saida', '150.00', '2026-03-27 13:48:58', 'Baixa de Despesa: Compra de Vasos', 'pagamento'),
(33, 1, NULL, NULL, 2, 'saida', '1000.00', '2026-03-27 12:48:44', 'Transferência enviada: ', 'transferencia'),
(34, 1, NULL, NULL, 1, 'entrada', '1000.00', '2026-03-27 12:48:44', 'Transferência recebida: ', 'transferencia'),
(35, 1, NULL, NULL, 2, 'saida', '40.00', '2026-03-27 15:55:14', 'Baixa de Despesa: Pão Francês', 'pagamento'),
(36, 1, NULL, NULL, 2, 'entrada', '1500.00', '2026-03-27 15:57:24', 'Baixa de Receita: Dizimo Março 2026', 'pagamento'),
(37, 1, NULL, NULL, 3, 'saida', '50.00', '2026-03-28 15:47:53', 'Baixa de Despesa: Internet Vivo', 'pagamento'),
(38, 1, NULL, NULL, 3, 'entrada', '2000.00', '2026-03-29 10:20:29', 'Baixa de Receita: oferta', 'pagamento'),
(39, 1, NULL, NULL, 2, 'entrada', '5000.00', '2026-03-29 10:20:36', 'Baixa de Receita: dizimo', 'pagamento');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `financeiro_pagamentos`
--

INSERT INTO `financeiro_pagamentos` (`financeiro_pagamento_id`, `financeiro_pagamento_igreja_id`, `financeiro_pagamento_financeiro_conta_id`, `financeiro_pagamento_valor`, `financeiro_pagamento_conta_financeira_id`, `financeiro_pagamento_metodo`, `financeiro_pagamento_documentos`, `financeiro_pagamento_data`) VALUES
(1, 1, 4, '25.00', 1, NULL, NULL, '2026-03-23 00:00:00'),
(2, 1, 2, '495.00', 2, NULL, NULL, '2026-03-23 00:00:00'),
(3, 1, 5, '150.00', 2, NULL, NULL, '2026-03-23 00:00:00'),
(4, 1, 6, '1450.00', 2, NULL, NULL, '2026-03-23 00:00:00'),
(5, 1, 7, '100.00', 2, NULL, NULL, '2026-03-23 00:00:00'),
(6, 1, 8, '1450.00', 2, NULL, NULL, '2026-03-23 00:00:00'),
(7, 1, 9, '285.00', 1, NULL, NULL, '2026-03-23 00:00:00'),
(8, 1, 10, '190.00', 1, NULL, NULL, '2026-03-23 00:00:00'),
(9, 1, 11, '5238.00', 1, NULL, NULL, '2026-03-23 00:00:00'),
(10, 1, 12, '2500.00', 2, NULL, NULL, '2026-03-23 00:00:00'),
(11, 1, 14, '5000.00', 2, NULL, NULL, '2026-03-23 00:00:00'),
(12, 1, 15, '2500.00', 1, NULL, NULL, '2026-03-23 00:00:00'),
(13, 1, 16, '1000.00', 1, NULL, NULL, '2026-03-23 00:00:00'),
(14, 1, 17, '1000.00', 1, NULL, NULL, '2026-03-23 00:00:00'),
(15, 1, 18, '150.00', 2, NULL, NULL, '2026-03-23 17:39:57'),
(16, 1, 19, '132.00', 3, NULL, NULL, '2026-03-23 22:20:32'),
(17, 1, 20, '50.00', 2, NULL, NULL, '2026-03-23 23:12:33'),
(18, 1, 25, '35.00', 2, NULL, NULL, '2026-03-27 13:47:33'),
(19, 1, 21, '150.00', 1, NULL, NULL, '2026-03-27 13:48:58'),
(20, 1, 26, '40.00', 2, NULL, NULL, '2026-03-27 15:55:14'),
(21, 1, 27, '1500.00', 2, NULL, NULL, '2026-03-27 15:57:24'),
(22, 1, 28, '50.00', 3, NULL, NULL, '2026-03-28 15:47:53'),
(23, 1, 30, '2000.00', 3, NULL, NULL, '2026-03-29 10:20:29'),
(24, 1, 29, '5000.00', 2, NULL, NULL, '2026-03-29 10:20:36');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `financeiro_receita_membros`
--

INSERT INTO `financeiro_receita_membros` (`receita_membro_id`, `receita_membro_conta_id`, `receita_membro_categoria_id`, `receita_membro_subcategoria_id`, `receita_membro_usuario_id`, `receita_membro_valor`, `receita_membro_data`) VALUES
(1, 14, 14, 14, 3, '1000.00', '2026-03-23 15:37:32'),
(2, 14, 14, 14, 1, '1000.00', '2026-03-23 15:37:32'),
(3, 14, 14, 14, 2, '1000.00', '2026-03-23 15:37:32'),
(4, 14, 14, 14, 162, '1000.00', '2026-03-23 15:37:32'),
(5, 14, 14, 14, 176, '1000.00', '2026-03-23 15:37:32'),
(6, 15, 13, 13, 3, '500.00', '2026-03-23 15:39:12'),
(7, 15, 13, 13, 162, '500.00', '2026-03-23 15:39:12'),
(8, 15, 13, 13, 1, '500.00', '2026-03-23 15:39:12'),
(9, 15, 13, 13, 2, '500.00', '2026-03-23 15:39:12'),
(10, 15, 13, 13, 176, '500.00', '2026-03-23 15:39:12'),
(20, 17, 14, 14, 176, '200.00', '2026-03-23 00:00:00'),
(19, 17, 14, 14, 162, '200.00', '2026-03-23 00:00:00'),
(18, 17, 14, 14, 1, '200.00', '2026-03-23 00:00:00'),
(17, 17, 14, 14, 2, '200.00', '2026-03-23 00:00:00'),
(16, 17, 14, 14, 3, '200.00', '2026-03-23 00:00:00'),
(21, 27, 14, 14, 243, '500.00', '2026-03-27 00:00:00'),
(22, 27, 14, 14, 2, '400.00', '2026-03-27 00:00:00'),
(23, 27, 14, 14, 1, '200.00', '2026-03-27 00:00:00'),
(24, 27, 14, 14, 3, '400.00', '2026-03-27 00:00:00'),
(25, 29, 14, 14, 238, '1550.00', '2026-03-29 00:00:00'),
(26, 29, 14, 14, 240, '2000.00', '2026-03-29 00:00:00'),
(27, 29, 14, 14, 162, '1450.00', '2026-03-29 00:00:00'),
(28, 30, 13, 13, 162, '1000.00', '2026-03-29 00:00:00'),
(29, 30, 13, 13, 220, '1000.00', '2026-03-29 00:00:00');

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

--
-- Despejando dados para a tabela `financeiro_subcategorias`
--

INSERT INTO `financeiro_subcategorias` (`subcategoria_id`, `subcategoria_igreja_id`, `subcategoria_categoria_id`, `subcategoria_nome`) VALUES
(3, 1, 15, 'Agua e Esgoto'),
(4, 1, 15, 'Luz'),
(5, 1, 15, 'Internet'),
(6, 1, 19, 'Construção do Templo'),
(7, 1, 20, 'Rendimento - Banco do Brasil'),
(8, 1, 17, 'Cestas Básicas'),
(10, 1, 16, 'Material de Limpeza'),
(11, 1, 16, 'Manutenção Predial (reparos rápidos, lâmpadas, pintura)'),
(12, 1, 16, 'Papelaria e Escritório'),
(13, 1, 18, 'Oferta'),
(14, 1, 18, 'Dizimo'),
(15, 1, 21, 'UCP - Gastos'),
(16, 1, 21, 'UPA - Gastos'),
(17, 1, 21, 'UMP - Gastos'),
(18, 1, 21, 'SAF - Gastos'),
(19, 1, 21, 'UPH - Gastos'),
(20, 1, 22, 'Café da Manhã'),
(21, 1, 23, 'Oferta');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `igrejas`
--

INSERT INTO `igrejas` (`igreja_id`, `igreja_nome`, `igreja_cnpj`, `igreja_endereco`, `igreja_pastor_id`, `igreja_data_criacao`, `igreja_logo`) VALUES
(1, 'Igreja Presbiteriana do Jardim Girassol', '08.012.132/0001-45', 'Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', 181, '2026-03-18 00:00:00', 'logo_1774904936.png');

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

--
-- Despejando dados para a tabela `igrejas_liturgias`
--

INSERT INTO `igrejas_liturgias` (`igreja_liturgia_id`, `igreja_liturgia_igreja_id`, `igreja_liturgia_data`, `igreja_liturgia_tema`, `igreja_liturgia_pregador_id`, `igreja_liturgia_pregador_nome`, `igreja_liturgia_dirigente_id`, `igreja_liturgia_dirigente_nome`, `igreja_liturgia_status`, `igreja_liturgia_data_criacao`) VALUES
(7, 1, '2026-03-31 19:00:00', '', 245, '', 3, '', 'publicado', '2026-03-31 22:08:25');

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

--
-- Despejando dados para a tabela `igrejas_liturgias_itens`
--

INSERT INTO `igrejas_liturgias_itens` (`liturgia_item_id`, `liturgia_item_liturgia_id`, `liturgia_item_ordem`, `liturgia_item_descricao`, `liturgia_item_tipo`, `liturgia_item_referencia`, `liturgia_item_conteudo_api`) VALUES
(454, 7, 1, 'Notas e Avisos', 'texto', '', ''),
(455, 7, 2, 'Prelúdio', 'oracao', '', ''),
(456, 7, 3, 'Leitura', 'leitura', 'Romanos 5:1-11', 'Justificados, pois, pela fé, tenhamos paz com Deus, por nosso Senhor Jesus Cristo,   por quem obtivemos também nosso acesso pela fé a esta graça, na qual estamos firmes, e gloriemo-nos na esperança da glória de Deus.   E não somente isso, mas também gloriemo-nos nas tribulações; sabendo que a tribulação produz a perseverança,   e a perseverança a experiência, e a experiência a esperança;   e a esperança não desaponta, porquanto o amor de Deus está derramado em nossos corações pelo Espírito Santo que nos foi dado.   Pois, quando ainda éramos fracos, Cristo morreu a seu tempo pelos ímpios.   Porque dificilmente haverá quem morra por um justo; pois poderá ser que pelo homem bondoso alguém ouse morrer.   Mas Deus dá prova do seu amor para conosco, em que, quando éramos ainda pecadores, Cristo morreu por nós.   Logo muito mais, sendo agora justificados pelo seu sangue, seremos por ele salvos da ira.   Porque se nós, quando éramos inimigos, fomos reconciliados com Deus pela morte de seu Filho, muito mais, estando já reconciliados, seremos salvos pela sua vida.   E não somente isso, mas também nos gloriamos em Deus por nosso Senhor Jesus Cristo, pelo qual agora temos recebido a reconciliação.'),
(457, 7, 4, 'Oração de Confissão', 'oracao', '', ''),
(458, 7, 5, 'Hino 222', 'hino', '', ''),
(459, 7, 6, 'teste 1', 'leitura', 'Cantares 2:1-10', 'Eu sou a rosa de Sarom, o lírio dos vales.   Qual o lírio entre os espinhos, tal é a minha amada entre as filhas.   Qual a macieira entre as árvores do bosque, tal é o meu amado entre os filhos; com grande gozo sentei-me à sua sombra; e o seu fruto era doce ao meu paladar.   Levou-me à sala do banquete, e o seu estandarte sobre mim era o amor.   Sustentai-me com passas, confortai-me com maçãs, porque desfaleço de amor.   A sua mão esquerda esteja debaixo da minha cabeça, e a sua mão direita me abrace.   Conjuro-vos, ó filhas de Jerusalém, pelas gazelas e cervas do campo, que não acordeis nem desperteis o amor, até que ele o queira.   A voz do meu amado! eis que vem aí, saltando sobre os montes, pulando sobre os outeiros.   O meu amado é semelhante ao gamo, ou ao filho do veado; eis que está detrás da nossa parede, olhando pelas janelas, lançando os olhos pelas grades.   Fala o meu amado e me diz: Levanta-te, amada minha, formosa minha, e vem.'),
(460, 7, 7, 'Oração de Intercessão', 'oracao', '', ''),
(461, 7, 8, 'Hino 423', 'hino', '', ''),
(462, 7, 9, 'teste 2', 'leitura', 'Atos 3:1-10', 'Pedro e João subiam ao templo à hora da oração, a nona.   E, era carregado um homem, coxo de nascença, o qual todos os dias punham à porta do templo, chamada Formosa, para pedir esmolas aos que entravam.   Ora, vendo ele a Pedro e João, que iam entrando no templo, pediu que lhe dessem uma esmola.   E Pedro, com João, fitando os olhos nele, disse: Olha para nós.   E ele os olhava atentamente, esperando receber deles alguma coisa.   Disse-lhe Pedro: Não tenho prata nem ouro; mas o que tenho, isso te dou; em nome de Jesus Cristo, o nazareno, anda.   Nisso, tomando-o pela mão direita, o levantou; imediatamente os seus pés e artelhos se firmaram   e, dando ele um salto, pôs-se em pé. Começou a andar e entrou com eles no templo, andando, saltando e louvando a Deus.   Todo o povo, ao vê-lo andar e louvar a Deus,   reconhecia-o como o mesmo que estivera sentado a pedir esmola à Porta Formosa do templo; e todos ficaram cheios de pasmo e assombro, pelo que lhe acontecera.'),
(463, 7, 10, 'Ofertas e Dízimos', 'hino', '', ''),
(464, 7, 11, 'Oração de Gratidão', 'oracao', '', ''),
(465, 7, 12, 'SERA?', 'mensagem', 'Lucas 3:1-2', 'No décimo quinto ano do reinado de Tibério César, sendo Pôncio Pilatos governador da Judéia, Herodes tetrarca da Galiléia, seu irmão Filipe tetrarca da região da Ituréia e de Traconites, e Lisânias tetrarca de Abilene,   sendo Anás e Caifás sumos sacerdotes, veio a palavra de Deus a João, filho de Zacarias, no deserto.'),
(466, 7, 13, '235', 'hino', '', ''),
(467, 7, 14, 'Benção Apostólica', 'oracao', '', ''),
(468, 7, 15, 'Amém Tríplice', 'texto', '', ''),
(469, 7, 16, 'Pósludio', 'texto', '', ''),
(470, 7, 17, 'Saudação aos Visitantes', 'texto', '', ''),
(471, 7, 18, 'Cumprimentos à porta', 'texto', '', '');

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

--
-- Despejando dados para a tabela `igrejas_mensagens_dominicais`
--

INSERT INTO `igrejas_mensagens_dominicais` (`igreja_mensagem_dominical_id`, `igreja_mensagem_dominical_igreja_id`, `igreja_mensagem_dominical_num_historico`, `igreja_mensagem_dominical_data`, `igreja_mensagem_dominical_autor_id`, `igreja_mensagem_dominical_titulo`, `igreja_mensagem_dominical_mensagem`, `igreja_mensagem_dominical_status`, `igreja_mensagem_dominical_data_criacao`) VALUES
(4, 1, 1002, '2026-03-31', 181, 'Fazei tudo com ordem e decência.', '<h2>Where does it come from?</h2>\r\n\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>\r\n', 'publicado', '2026-03-31 14:14:38');

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

--
-- Despejando dados para a tabela `igrejas_programacao`
--

INSERT INTO `igrejas_programacao` (`programacao_id`, `programacao_igreja_id`, `programacao_titulo`, `programacao_descricao`, `programacao_dia_semana`, `programacao_hora`, `programacao_recorrencia_mensal`, `programacao_is_ceia`, `programacao_status`, `programacao_criado_em`) VALUES
(1, 1, 'Escola Dominical', NULL, 'Domingo', '09:00:00', 0, 0, 'Ativo', '2026-03-31 00:23:46'),
(2, 1, 'Culto Solene', NULL, 'Domingo', '18:00:00', 0, 0, 'Ativo', '2026-03-31 00:24:21'),
(3, 1, 'Reunião de Oração nos Lares', NULL, 'Sexta', '20:00:00', 0, 0, 'Ativo', '2026-03-31 00:25:23'),
(4, 1, 'Oração/Estudo Bíblico (Online)', NULL, 'Quarta', '20:00:00', 0, 0, 'Ativo', '2026-03-31 00:26:34'),
(7, 1, 'Santa Ceia', NULL, 'Domingo', '18:00:00', 1, 1, 'Ativo', '2026-03-31 00:31:57');

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
(1, 1, 'Instagram', 'ipjdgirassol', NULL, 'ativo'),
(2, 1, 'Facebook', 'ipjardimgirassol', NULL, 'ativo');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `membros`
--

INSERT INTO `membros` (`membro_id`, `membro_igreja_id`, `membro_registro_interno`, `membro_nome`, `membro_data_nascimento`, `membro_genero`, `membro_estado_civil`, `membro_email`, `membro_senha`, `membro_telefone`, `membro_data_batismo`, `membro_status`, `membro_data_criacao`) VALUES
(1, 1, '12026030001', 'Lucas Nobre Ferreira Martins', '1987-09-15', 'Masculino', NULL, 'lucas_guitar1987@hotmail.com', NULL, '11962851514', '2017-02-22', 'Ativo', '2026-03-19 01:48:57'),
(2, 1, '12026030002', 'Matheus Nobre Ferreira Martins', '1994-12-02', 'Masculino', NULL, 'matheus_ipjg@hotmail.com', NULL, '119584181511', NULL, 'Ativo', '2026-03-19 03:18:07'),
(3, 1, '12026030003', 'Adilson Ferreira Martins', '1962-07-26', 'Masculino', NULL, 'afmnobre1962@gmail.com', NULL, '11995852032', NULL, 'Ativo', '2026-03-19 05:08:13'),
(131, 1, '1202603131', 'Tony Stark', '2010-08-01', 'Masculino', NULL, 'hero1@geekchurch.com', NULL, '11985821749', '2018-11-03', 'Ativo', '2026-03-20 13:12:59'),
(132, 1, '1202603132', 'Steve Rogers', '2010-01-08', 'Masculino', NULL, 'hero2@geekchurch.com', NULL, '11964349513', '2020-12-08', 'Ativo', '2026-03-20 13:12:59'),
(133, 1, '1202603133', 'Natasha Romanoff', '1944-02-14', 'Feminino', NULL, 'hero3@geekchurch.com', NULL, '11960774013', '2022-11-27', 'Ativo', '2026-03-20 13:12:59'),
(134, 1, '1202603134', 'Wanda Maximoff', '1930-10-24', 'Feminino', NULL, 'hero4@geekchurch.com', NULL, '11984484391', '2023-10-08', 'Ativo', '2026-03-20 13:12:59'),
(135, 1, '1202603135', 'Bruce Wayne', '1952-10-14', 'Masculino', NULL, 'hero5@geekchurch.com', NULL, '11997853185', '2019-09-06', 'Ativo', '2026-03-20 13:12:59'),
(136, 1, '1202603136', 'Clark Kent', '1992-05-30', 'Masculino', NULL, 'hero6@geekchurch.com', NULL, '11977176488', '2019-02-25', 'Ativo', '2026-03-20 13:12:59'),
(137, 1, '1202603137', 'Diana Prince', '1996-12-26', 'Feminino', NULL, 'hero7@geekchurch.com', NULL, '11942083266', '2017-04-06', 'Ativo', '2026-03-20 13:12:59'),
(138, 1, '1202603138', 'Peter Parker', '1985-08-01', 'Masculino', NULL, 'hero8@geekchurch.com', NULL, '11942896267', '2020-04-07', 'Ativo', '2026-03-20 13:12:59'),
(139, 1, '1202603139', 'Luke Skywalker', '1939-02-10', 'Masculino', NULL, 'hero9@geekchurch.com', NULL, '11965453392', '2021-10-29', 'Ativo', '2026-03-20 13:12:59'),
(140, 1, '1202603140', 'Leia Organa', '1991-10-08', 'Feminino', NULL, 'hero10@geekchurch.com', NULL, '11948072325', '2025-07-06', 'Ativo', '2026-03-20 13:12:59'),
(141, 1, '1202603141', 'Han Solo', '2017-12-14', 'Masculino', NULL, 'hero11@geekchurch.com', NULL, '11928544589', '2018-06-05', 'Ativo', '2026-03-20 13:12:59'),
(142, 1, '1202603142', 'Hermione Granger', '1998-06-27', 'Feminino', NULL, 'hero12@geekchurch.com', NULL, '11915614508', '2021-07-05', 'Ativo', '2026-03-20 13:12:59'),
(143, 1, '1202603143', 'Harry Potter', '2009-08-03', 'Masculino', NULL, 'hero13@geekchurch.com', NULL, '11948456951', '2019-11-25', 'Ativo', '2026-03-20 13:12:59'),
(144, 1, '1202603144', 'Ron Weasley', '1939-03-25', 'Masculino', NULL, 'hero14@geekchurch.com', NULL, '11954964563', '2017-08-15', 'Ativo', '2026-03-20 13:12:59'),
(145, 1, '1202603145', 'Ellen Ripley', '1946-10-31', 'Feminino', NULL, 'hero15@geekchurch.com', NULL, '11948827574', '2018-09-22', 'Ativo', '2026-03-20 13:12:59'),
(146, 1, '1202603146', 'Sarah Connor', '1981-03-27', 'Feminino', NULL, 'hero16@geekchurch.com', NULL, '11912162883', '2018-08-30', 'Ativo', '2026-03-20 13:12:59'),
(147, 1, '1202603147', 'Daenerys Targaryen', '1956-02-14', 'Feminino', NULL, 'hero17@geekchurch.com', NULL, '11934686346', '2023-10-05', 'Ativo', '2026-03-20 13:12:59'),
(148, 1, '1202603148', 'Jon Snow', '1986-01-03', 'Masculino', NULL, 'hero18@geekchurch.com', NULL, '11936509979', '2023-09-07', 'Ativo', '2026-03-20 13:12:59'),
(149, 1, '1202603149', 'Arya Stark', '1988-03-31', 'Feminino', NULL, 'hero19@geekchurch.com', NULL, '11924267163', '2019-10-23', 'Ativo', '2026-03-20 13:12:59'),
(150, 1, '1202603150', 'Tyrion Lannister', '1953-12-11', 'Masculino', NULL, 'hero20@geekchurch.com', NULL, '11975603749', '2021-09-09', 'Ativo', '2026-03-20 13:12:59'),
(151, 1, '1202603151', 'Monkey D Luffy', '2018-06-06', 'Masculino', NULL, 'hero21@geekchurch.com', NULL, '11913086793', NULL, 'Ativo', '2026-03-20 13:12:59'),
(152, 1, '1202603152', 'Roronoa Zoro', '1969-06-16', 'Masculino', NULL, 'hero22@geekchurch.com', NULL, '11915642382', '2020-04-19', 'Ativo', '2026-03-20 13:12:59'),
(153, 1, '1202603153', 'Nami', '1949-09-19', 'Feminino', NULL, 'hero23@geekchurch.com', NULL, '11918047294', '2024-12-11', 'Ativo', '2026-03-20 13:12:59'),
(154, 1, '1202603154', 'Nico Robin', '1989-10-05', 'Feminino', NULL, 'hero24@geekchurch.com', NULL, '11951439421', '2024-04-02', 'Ativo', '2026-03-20 13:12:59'),
(155, 1, '1202603155', 'Naruto Uzumaki', '1966-08-22', 'Masculino', NULL, 'hero25@geekchurch.com', NULL, '11947876929', '2023-03-30', 'Ativo', '2026-03-20 13:12:59'),
(156, 1, '1202603156', 'Sasuke Uchiha', '2003-11-12', 'Masculino', NULL, 'hero26@geekchurch.com', NULL, '11931290117', '2021-03-03', 'Ativo', '2026-03-20 13:12:59'),
(157, 1, '1202603157', 'Sakura Haruno', '1945-07-23', 'Feminino', NULL, 'hero27@geekchurch.com', NULL, '11960532429', '2022-08-09', 'Ativo', '2026-03-20 13:12:59'),
(158, 1, '1202603158', 'Hinata Hyuga', '2014-01-17', 'Feminino', NULL, 'hero28@geekchurch.com', NULL, '11957761414', '2023-05-18', 'Ativo', '2026-03-20 13:12:59'),
(159, 1, '1202603159', 'Goku', '1944-02-23', 'Masculino', NULL, 'hero29@geekchurch.com', NULL, '11936537689', '2016-05-14', 'Ativo', '2026-03-20 13:12:59'),
(160, 1, '1202603160', 'Vegeta', '2021-11-30', 'Masculino', NULL, 'hero30@geekchurch.com', NULL, '11933457117', '2024-06-28', 'Ativo', '2026-03-20 13:12:59'),
(161, 1, '1202603161', 'Bulma', '2018-02-25', 'Feminino', NULL, 'hero31@geekchurch.com', NULL, '11990051921', '2024-03-10', 'Ativo', '2026-03-20 13:12:59'),
(162, 1, '1202603162', 'Android 18', '1992-01-06', 'Feminino', NULL, 'hero32@geekchurch.com', NULL, '(11) 92081-1781', '2020-08-02', 'Ativo', '2026-03-20 13:12:59'),
(163, 1, '1202603163', 'Walter White', '1980-12-30', 'Masculino', NULL, 'hero33@geekchurch.com', NULL, '11963540769', '2020-03-10', 'Ativo', '2026-03-20 13:12:59'),
(164, 1, '1202603164', 'Jesse Pinkman', '2003-04-21', 'Masculino', NULL, 'hero34@geekchurch.com', NULL, '11941343607', '2025-10-02', 'Ativo', '2026-03-20 13:12:59'),
(165, 1, '1202603165', 'Eleven', '2007-08-27', 'Feminino', NULL, 'hero35@geekchurch.com', NULL, '11981887970', '2021-11-29', 'Ativo', '2026-03-20 13:12:59'),
(166, 1, '1202603166', 'Jim Hopper', '1951-03-25', 'Masculino', NULL, 'hero36@geekchurch.com', NULL, '11954760476', '2024-02-03', 'Ativo', '2026-03-20 13:12:59'),
(167, 1, '1202603167', 'MICHAEL JACKSON', '1969-08-25', 'Masculino', 'Casado(a)', 'hero37@geekchurch.com', NULL, '11929863034', '2022-05-04', 'Ativo', '2026-03-20 13:12:59'),
(168, 1, '1202603168', 'Freddie Mercury', '1998-09-02', 'Masculino', NULL, 'hero38@geekchurch.com', NULL, '11930510082', '2023-03-06', 'Ativo', '2026-03-20 13:12:59'),
(169, 1, '1202603169', 'Lady Gaga', '1943-06-02', 'Feminino', NULL, 'hero39@geekchurch.com', NULL, '11934504841', '2017-09-19', 'Ativo', '2026-03-20 13:12:59'),
(170, 1, '1202603170', 'Beyonce', '1983-01-24', 'Feminino', NULL, 'hero40@geekchurch.com', NULL, '11966615223', '2017-11-08', 'Ativo', '2026-03-20 13:12:59'),
(171, 1, '1202603171', 'Rick Sanchez', '1996-11-11', 'Masculino', NULL, 'hero41@geekchurch.com', NULL, '11997587378', '2016-06-19', 'Ativo', '2026-03-20 13:12:59'),
(172, 1, '1202603172', 'Morty Smith', '1931-04-10', 'Masculino', NULL, 'hero42@geekchurch.com', NULL, '11988379453', '2021-06-19', 'Ativo', '2026-03-20 13:12:59'),
(173, 1, '1202603173', 'Summer Smith', '1950-08-16', 'Feminino', NULL, 'hero43@geekchurch.com', NULL, '11945497265', '2019-06-04', 'Ativo', '2026-03-20 13:12:59'),
(174, 1, '1202603174', 'Beth Smith', '2004-11-15', 'Feminino', NULL, 'hero44@geekchurch.com', NULL, '11913536155', '2020-09-23', 'Ativo', '2026-03-20 13:12:59'),
(175, 1, '1202603175', 'Homelander', '1964-01-20', 'Masculino', NULL, 'hero45@geekchurch.com', NULL, '11954466362', '2020-05-14', 'Ativo', '2026-03-20 13:12:59'),
(176, 1, '1202603176', 'Starlight', '1982-04-12', 'Feminino', NULL, 'hero46@geekchurch.com', NULL, '11951833036', '2016-04-22', 'Ativo', '2026-03-20 13:12:59'),
(177, 1, '1202603177', 'Billy Butcher', '1970-06-02', 'Masculino', NULL, 'hero47@geekchurch.com', NULL, '11986051287', '2020-11-21', 'Ativo', '2026-03-20 13:12:59'),
(178, 1, '1202603178', 'Queen Maeve', '2013-05-08', 'Feminino', NULL, 'hero48@geekchurch.com', NULL, '11914611186', '2017-07-25', 'Ativo', '2026-03-20 13:12:59'),
(179, 1, '1202603179', 'Joel Miller', '2008-10-10', 'Masculino', NULL, 'hero49@geekchurch.com', NULL, '11935510262', '2017-05-04', 'Ativo', '2026-03-20 13:12:59'),
(180, 1, '1202603180', 'Ellie Williams', '1967-09-12', 'Feminino', NULL, 'hero50@geekchurch.com', NULL, '11936319101', '2019-05-07', 'Ativo', '2026-03-20 13:12:59'),
(181, 1, '1202603181', 'Geralt of Rivia', '1970-09-22', 'Masculino', NULL, 'hero51@geekchurch.com', NULL, '11976751204', '2025-12-17', 'Ativo', '2026-03-20 13:12:59'),
(182, 1, '1202603182', 'Yennefer', '1936-11-13', 'Feminino', NULL, 'hero52@geekchurch.com', NULL, '11949480848', '2021-05-19', 'Ativo', '2026-03-20 13:12:59'),
(183, 1, '1202603183', 'Ciri', '2015-11-29', 'Feminino', NULL, 'hero53@geekchurch.com', NULL, '11916185528', '2025-11-27', 'Ativo', '2026-03-20 13:12:59'),
(184, 1, '1202603184', 'Triss Merigold', '1932-04-13', 'Feminino', NULL, 'hero54@geekchurch.com', NULL, '11968859498', '2022-01-02', 'Ativo', '2026-03-20 13:12:59'),
(185, 1, '1202603185', 'Katniss Everdeen', '2011-10-21', 'Feminino', NULL, 'hero55@geekchurch.com', NULL, '11951750765', '2017-05-26', 'Ativo', '2026-03-20 13:12:59'),
(186, 1, '1202603186', 'Peeta Mellark', '2024-03-20', 'Masculino', NULL, 'hero56@geekchurch.com', NULL, '11950854933', NULL, 'Ativo', '2026-03-20 13:12:59'),
(187, 1, '1202603187', 'John Wick', '1958-08-06', 'Masculino', NULL, 'hero57@geekchurch.com', NULL, '11981124150', '2017-03-19', 'Ativo', '2026-03-20 13:12:59'),
(188, 1, '1202603188', 'James Bond', '2013-01-03', 'Masculino', NULL, 'hero58@geekchurch.com', NULL, '11996849032', '2021-12-22', 'Ativo', '2026-03-20 13:12:59'),
(189, 1, '1202603189', 'Indiana Jones', '2003-10-04', 'Masculino', NULL, 'hero59@geekchurch.com', NULL, '11987570765', '2019-12-09', 'Ativo', '2026-03-20 13:12:59'),
(190, 1, '1202603190', 'Lara Croft', '1971-03-01', 'Feminino', NULL, 'hero60@geekchurch.com', NULL, '11990737513', '2018-01-23', 'Ativo', '2026-03-20 13:12:59'),
(191, 1, '1202603191', 'LEGOLAS', '1987-10-06', 'Masculino', 'Solteiro(a)', 'hero61@geekchurch.com', NULL, '(11) 95459-0615', '2023-02-09', 'Ativo', '2026-03-20 13:12:59'),
(192, 1, '1202603192', 'GALADRIEL', '2019-04-28', 'Feminino', 'Solteiro(a)', 'hero62@geekchurch.com', NULL, '(11) 94733-6532', '0000-00-00', 'Ativo', '2026-03-20 13:12:59'),
(193, 1, '1202603193', 'Aragorn', '2018-03-06', 'Masculino', NULL, 'hero63@geekchurch.com', NULL, '(11) 98297-6151', '0000-00-00', 'Ativo', '2026-03-20 13:12:59'),
(194, 1, '1202603194', 'Frodo Bolseiro', '1964-12-01', 'Masculino', NULL, 'hero64@geekchurch.com', NULL, '11970005936', '2021-06-21', 'Ativo', '2026-03-20 13:12:59'),
(195, 1, '1202603195', 'Eowyn', '1989-03-18', 'Feminino', NULL, 'hero65@geekchurch.com', NULL, '11950118996', '2025-02-24', 'Ativo', '2026-03-20 13:12:59'),
(196, 1, '1202603196', 'Arwen', '2006-11-02', 'Feminino', NULL, 'hero66@geekchurch.com', NULL, '11969359619', '2019-02-14', 'Ativo', '2026-03-20 13:12:59'),
(197, 1, '1202603197', 'Tanjiro Kamado', '1969-08-27', 'Masculino', NULL, 'hero67@geekchurch.com', NULL, '11975556791', '2016-12-14', 'Ativo', '2026-03-20 13:12:59'),
(198, 1, '1202603198', 'Nezuko Kamado', '1981-10-15', 'Feminino', NULL, 'hero68@geekchurch.com', NULL, '11951833793', '2016-06-15', 'Ativo', '2026-03-20 13:12:59'),
(199, 1, '1202603199', 'Zenitsu Agatsuma', '1977-10-04', 'Masculino', NULL, 'hero69@geekchurch.com', NULL, '11956670301', '2024-12-25', 'Ativo', '2026-03-20 13:12:59'),
(200, 1, '1202603200', 'Inosuke Hashibira', '2020-03-11', 'Masculino', NULL, 'hero70@geekchurch.com', NULL, '11994111871', '2021-04-26', 'Ativo', '2026-03-20 13:12:59'),
(201, 1, '1202603201', 'Saitama', '1962-03-13', 'Masculino', NULL, 'hero71@geekchurch.com', NULL, '11978851765', '2017-05-21', 'Ativo', '2026-03-20 13:12:59'),
(202, 1, '1202603202', 'Tatsumaki', '2013-12-17', 'Feminino', NULL, 'hero72@geekchurch.com', NULL, '11997120879', '2021-07-15', 'Ativo', '2026-03-20 13:12:59'),
(203, 1, '1202603203', 'Edward Elric', '1982-11-15', 'Masculino', NULL, 'hero73@geekchurch.com', NULL, '11980672902', '2020-02-07', 'Ativo', '2026-03-20 13:12:59'),
(204, 1, '1202603204', 'Winry Rockbell', '1956-07-27', 'Feminino', NULL, 'hero74@geekchurch.com', NULL, '11971568598', '2023-02-18', 'Ativo', '2026-03-20 13:12:59'),
(205, 1, '1202603205', 'Roy Mustang', '1977-09-18', 'Masculino', NULL, 'hero75@geekchurch.com', NULL, '11957319692', '2024-08-20', 'Ativo', '2026-03-20 13:12:59'),
(206, 1, '1202603206', 'Riza Hawkeye', '2005-02-17', 'Feminino', NULL, 'hero76@geekchurch.com', NULL, '11963223401', '2023-01-17', 'Ativo', '2026-03-20 13:12:59'),
(207, 1, '1202603207', 'Ichigo Kurosaki', '1945-10-05', 'Masculino', NULL, 'hero77@geekchurch.com', NULL, '11920240609', '2024-12-04', 'Ativo', '2026-03-20 13:12:59'),
(208, 1, '1202603208', 'Rukia Kuchiki', '1996-01-28', 'Feminino', NULL, 'hero78@geekchurch.com', NULL, '11922217077', '2018-07-29', 'Ativo', '2026-03-20 13:12:59'),
(209, 1, '1202603209', 'Orihime Inoue', '1985-01-11', 'Feminino', NULL, 'hero79@geekchurch.com', NULL, '11980869863', '2019-05-04', 'Ativo', '2026-03-20 13:12:59'),
(210, 1, '1202603210', 'Sosuke Aizen', '2018-05-26', 'Masculino', NULL, 'hero80@geekchurch.com', NULL, '11939728170', '2022-01-28', 'Ativo', '2026-03-20 13:12:59'),
(211, 1, '1202603211', 'Light Yagami', '2018-02-25', 'Masculino', NULL, 'hero81@geekchurch.com', NULL, '11924767459', '2020-06-18', 'Ativo', '2026-03-20 13:12:59'),
(212, 1, '1202603212', 'Misa Amano', '1987-11-30', 'Feminino', NULL, 'hero82@geekchurch.com', NULL, '11928736277', '2017-06-06', 'Ativo', '2026-03-20 13:12:59'),
(213, 1, '1202603213', 'L Lawliet', '1949-10-17', 'Masculino', NULL, 'hero83@geekchurch.com', NULL, '11930180969', '2018-03-09', 'Ativo', '2026-03-20 13:12:59'),
(214, 1, '1202603214', 'Mikasa Ackerman', '1992-01-04', 'Feminino', NULL, 'hero84@geekchurch.com', NULL, '11938598194', '2020-09-15', 'Ativo', '2026-03-20 13:12:59'),
(215, 1, '1202603215', 'Eren Yeager', '1946-08-29', 'Masculino', NULL, 'hero85@geekchurch.com', NULL, '11942969550', '2022-01-11', 'Ativo', '2026-03-20 13:12:59'),
(216, 1, '1202603216', 'Levi Ackerman', '1927-08-06', 'Masculino', NULL, 'hero86@geekchurch.com', NULL, '11975412921', '2019-10-15', 'Ativo', '2026-03-20 13:12:59'),
(217, 1, '1202603217', 'Annie Leonhart', '2022-07-27', 'Feminino', NULL, 'hero87@geekchurch.com', NULL, '11932856095', '2024-08-16', 'Ativo', '2026-03-20 13:12:59'),
(218, 1, '1202603218', 'David Bowie', '2022-11-06', 'Masculino', NULL, 'hero88@geekchurch.com', NULL, '11972338907', NULL, 'Ativo', '2026-03-20 13:12:59'),
(219, 1, '1202603219', 'Kurt Cobain', '1954-03-19', 'Masculino', NULL, 'hero89@geekchurch.com', NULL, '11959998190', '2020-04-11', 'Ativo', '2026-03-20 13:12:59'),
(220, 1, '1202603220', 'Amy Winehouse', '1996-01-23', 'Feminino', NULL, 'hero90@geekchurch.com', NULL, '11976704889', '2018-04-23', 'Ativo', '2026-03-20 13:12:59'),
(221, 1, '1202603221', 'Janis Joplin', '1953-08-22', 'Feminino', NULL, 'hero91@geekchurch.com', NULL, '11936440183', '2023-07-16', 'Ativo', '2026-03-20 13:12:59'),
(222, 1, '1202603222', 'ELVIS PRESLEY', '1980-10-21', 'Masculino', 'Solteiro(a)', 'hero92@geekchurch.com', NULL, '(11) 95418-2783', '2025-06-11', 'Ativo', '2026-03-20 13:12:59'),
(223, 1, '1202603223', 'Axl Rose', '1935-09-18', 'Masculino', NULL, 'hero93@geekchurch.com', NULL, '11940737941', '2016-08-04', 'Ativo', '2026-03-20 13:12:59'),
(224, 1, '1202603224', 'Dua Lipa', '1947-11-30', 'Feminino', NULL, 'hero94@geekchurch.com', NULL, '11916100279', '2016-07-30', 'Ativo', '2026-03-20 13:12:59'),
(225, 1, '1202603225', 'Rihanna', '1964-12-27', 'Feminino', NULL, 'hero95@geekchurch.com', NULL, '11928098097', '2024-09-23', 'Ativo', '2026-03-20 13:12:59'),
(226, 1, '1202603226', 'Taylor Swift', '2012-04-18', 'Feminino', NULL, 'hero96@geekchurch.com', NULL, '11933140390', '2017-08-03', 'Ativo', '2026-03-20 13:12:59'),
(227, 1, '1202603227', 'Bruno Mars', '1972-03-12', 'Masculino', NULL, 'hero97@geekchurch.com', NULL, '11922582785', '2025-08-10', 'Ativo', '2026-03-20 13:12:59'),
(228, 1, '1202603228', 'Ed Sheeran', '1938-08-28', 'Masculino', NULL, 'hero98@geekchurch.com', NULL, '11931832440', '2020-08-23', 'Ativo', '2026-03-20 13:12:59'),
(229, 1, '1202603229', 'Billie Eilish', '2020-03-07', 'Feminino', NULL, 'hero99@geekchurch.com', NULL, '11966860199', NULL, 'Ativo', '2026-03-20 13:12:59'),
(230, 1, '1202603230', 'ADELE', '1928-11-10', 'Feminino', 'Viúvo(a)', 'hero100@geekchurch.com', NULL, '11999340253', '2026-02-08', 'Ativo', '2026-03-20 13:12:59'),
(238, 1, '1202603239', 'ANA MARIA CASTRO BARBOSA', '1999-02-15', 'Feminino', 'Solteiro(a)', 'anamaria@teste.com.br', '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', '(11) 92514-5815', '2002-04-01', 'Ativo', '2026-03-25 14:32:02'),
(239, 1, '1202603240', 'ANTONIO CASTRO BARBOSA', '2010-02-15', 'Masculino', NULL, 'anamaria@teste.com.br', NULL, NULL, NULL, 'Ativo', '2026-03-25 16:34:58'),
(240, 1, '1202603241', 'ANDRÉ CASTRO BARBOSA', '2009-03-10', 'Masculino', NULL, 'anamaria@teste.com.br', NULL, NULL, NULL, 'Ativo', '2026-03-25 16:58:24'),
(241, 1, '1202603242', 'ARTHUR COSTA SANTOS', '1987-09-15', 'Masculino', NULL, 'arthur@teste.com.br', '$2y$10$D0ISRIA/t7bzrcH3IuPymeUY9VHkYX3OxyaUnkhDTwDQ.DPadywVW', '(11) 11111-1111', '2022-08-22', 'Ativo', '2026-03-26 10:07:12'),
(243, 1, '1202603244', 'MICHAEL DOUGLAS DOS SANTOS TEIXEIRA ', '1990-04-09', 'Masculino', 'Casado(a)', 'michellldt@hotmail.com', '$2y$10$Ws7aE8XtmnB31X17FdQ9JeaFcljm57xEpjRXwYkY5xdYpRpRTFb0W', '(11) 91188-1704', '2005-04-10', 'Ativo', '2026-03-27 12:24:35'),
(244, 1, '1202603245', 'ROSANGELA SILVA SANTOS', '1984-07-25', 'Feminino', 'Casado(a)', 'rosangela@teste.com', '$2y$10$Oi9tr8CSCfY0hGozQ4bW1ud8w9TrowYNsYgEImSRqTYnVA16mtxs2', '(11) 98540-1785', '2020-04-22', 'Ativo', '2026-03-29 13:27:16'),
(245, 1, '1202603246', 'AMARILDO JOSÉ CAMPOS', '1999-08-22', 'Masculino', 'Casado(a)', 'amarildo@teste.com', '$2y$10$NKhlqMKfVKx6TGCBWc6H2O4Gbwc5XWfpqsnqA7bPeezOyj.pYgrdO', '(11) 96254-1818', '2015-09-22', 'Ativo', '2026-03-29 13:43:14'),
(246, 1, '1202603247', 'MARCOS ANTONIO CESAR SILVA', '2002-08-15', 'Masculino', 'Separado(a)', 'marcos@teste.com', '$2y$10$OoQ2oITeY323a3febgaeTuI7wZPl135WNPDnmPjd7hm1svl9hrpge', '(11) 92518-2785', '2004-02-22', 'Ativo', '2026-03-29 17:12:39'),
(247, 1, '1202603248', 'JEREMIAS SANTOS', '2018-02-15', 'Masculino', 'Solteiro(a)', 'anamaria@teste.com.br', NULL, '(11) 92514-5815', '2024-09-22', 'Ativo', '2026-03-30 16:18:26');

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
(39, 2, 7, '2026-03-19 09:51:33'),
(45, 222, 12, '2026-03-20 16:21:49'),
(46, 191, 13, '2026-03-20 16:22:05'),
(47, 181, 18, '2026-03-20 16:22:31'),
(48, 162, 17, '2026-03-20 16:23:11'),
(52, 3, 7, '2026-03-24 12:41:50'),
(53, 1, 7, '2026-03-24 12:50:16'),
(54, 1, 31, '2026-03-24 12:50:16'),
(55, 238, 20, '2026-03-25 15:49:45'),
(56, 238, 22, '2026-03-25 15:49:45'),
(57, 241, 16, '2026-03-26 10:51:43'),
(58, 193, 14, '2026-03-27 10:25:53'),
(59, 193, 11, '2026-03-27 10:25:53'),
(60, 224, 7, '2026-03-27 11:52:14'),
(61, 243, 7, '2026-03-27 12:27:21'),
(63, 181, 1, '2026-03-30 17:12:30'),
(64, 212, 5, '2026-03-30 21:56:15'),
(65, 150, 5, '2026-03-30 21:56:26'),
(66, 132, 3, '2026-03-30 21:56:42'),
(67, 246, 5, '2026-04-01 03:09:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_contatos`
--

CREATE TABLE `membros_contatos` (
  `membro_contato_id` int(11) NOT NULL,
  `membro_contato_membro_id` int(11) DEFAULT NULL,
  `membro_contato_tipo` varchar(50) DEFAULT NULL,
  `membro_contato_valor` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `membros_contatos`
--

INSERT INTO `membros_contatos` (`membro_contato_id`, `membro_contato_membro_id`, `membro_contato_tipo`, `membro_contato_valor`) VALUES
(1, 231, 'Celular', '(11) 96825-4813'),
(2, 232, 'Celular', '(11) 92514-5815'),
(3, 233, 'Celular', '(11) 92514-5815'),
(4, 234, 'Celular', '(11) 92514-5815');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `membros_enderecos`
--

INSERT INTO `membros_enderecos` (`membro_endereco_id`, `membro_endereco_membro_id`, `membro_endereco_igreja_id`, `membro_endereco_rua`, `membro_endereco_numero`, `membro_endereco_complemento`, `membro_endereco_bairro`, `membro_endereco_cidade`, `membro_endereco_estado`, `membro_endereco_cep`) VALUES
(1, 1, 1, 'Rua das Aeromoças', '48', 'Casa 2', 'Jardim Aeródromo', 'Guarulhos', 'SP', '07161725'),
(2, 3, 1, 'Rua Domingas Fanganiello Pavan', '109', 'Casa Térrea', 'Jardim Bondança', 'Guarulhos', 'SP', '07162-460'),
(3, 140, 1, 'Rua Iguaraçu Tietê', '1263', 'Casa Térrea', 'Jardim Santo Expedito', 'Guarulhos', 'SP', '07160-250'),
(4, 231, 1, 'Rua Sargento da Aeronáutica Plínio J. da Cunha', '43', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-390'),
(5, 232, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-220'),
(6, 233, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', '', '', '07180-220'),
(7, 234, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-220'),
(8, 235, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-220'),
(9, 236, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-220'),
(10, 237, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-220'),
(11, 238, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-220'),
(12, 239, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', '', '07180-220'),
(13, 240, 1, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', '', '07180-220'),
(14, 230, 1, 'Rua Babaçu', '22', '', 'Jardim Santa Terezinha', 'Guarulhos', 'SP', '07160-360'),
(15, 196, 1, 'Rua São João da Serra', '2589', 'teste', 'Cidade Jardim Cumbica', 'Guarulhos', 'SP', '07180-280'),
(16, 241, NULL, 'Rua Santana da Vargem', '4521', NULL, 'Cidade Seródio', 'Guarulhos', 'SP', '07150-200'),
(18, 243, NULL, 'Rua Belém', '446', NULL, 'Jardim Novo Portugal', 'Guarulhos', 'SP', '07160-540'),
(19, 244, NULL, 'Rua Elvis Presley', '457', NULL, 'Jardim Angélica', 'Guarulhos', 'SP', '07260-360'),
(20, 245, NULL, 'Viela Lupércio', '1234', NULL, 'Jardim Cristina', 'Guarulhos', 'SP', '07160-280'),
(21, 246, NULL, 'Viela Coluna', '982', NULL, 'Cidade Seródio', 'Guarulhos', 'SP', '07150-250'),
(22, 193, 1, 'Rua Antônio Artoni', '890', 'Casa dos fundos.', 'Vila Flórida', 'Guarulhos', 'SP', '07130-100'),
(23, 247, NULL, 'Rua Ibiraci', '22', NULL, 'Cidade Jardim Cumbica', 'Guarulhos', '', '07180-220');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_fotos`
--

CREATE TABLE `membros_fotos` (
  `membro_foto_id` int(11) NOT NULL,
  `membro_foto_membro_id` int(11) DEFAULT NULL,
  `membro_foto_arquivo` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `membros_fotos`
--

INSERT INTO `membros_fotos` (`membro_foto_id`, `membro_foto_membro_id`, `membro_foto_arquivo`) VALUES
(8, 139, 'perfil_1774377456.jpg'),
(7, 2, 'perfil_1774377446.jpg'),
(6, 230, 'perfil_1774377522.jpg'),
(5, 1, 'perfil_1774673016.jpg'),
(9, 3, 'perfil_1774377533.jpg'),
(10, 231, 'selfie_1774464897_231.jpg'),
(11, 232, 'selfie_1774468100_232.jpg'),
(12, 233, 'selfie_1774468340_233.jpg'),
(13, 234, 'selfie_1774470488_234.jpg'),
(14, 235, 'selfie_1774472589_235.jpg'),
(15, 236, 'selfie_1774473050_236.jpg'),
(16, 237, 'selfie_1774473464_237.jpg'),
(17, 238, 'selfie_1774474323_238.jpg'),
(18, 239, 'selfie_1774481698_239.jpg'),
(19, 240, 'selfie_1774483104_240.png'),
(20, 241, 'selfie_1774544832_241.jpg'),
(21, 242, 'selfie_1774546794_242.jpg'),
(22, 243, 'perfil_1774639814.jpeg'),
(23, 162, 'perfil_1774750613.jpg'),
(24, 193, 'perfil_1774751742.jpg'),
(25, 244, 'selfie_1774816036_244.jpg'),
(26, 245, 'selfie_1774816993_245.jpg'),
(27, 246, 'selfie_1774829559_246.jpg'),
(28, 181, 'perfil_1774900840.jpg'),
(29, 247, 'selfie_1774912705_247.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membros_historico`
--

CREATE TABLE `membros_historico` (
  `membro_historico_id` int(11) NOT NULL,
  `membro_historico_membro_id` int(11) DEFAULT NULL,
  `membro_historico_data` datetime DEFAULT NULL,
  `membro_historico_texto` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `membros_historico`
--

INSERT INTO `membros_historico` (`membro_historico_id`, `membro_historico_membro_id`, `membro_historico_data`, `membro_historico_texto`) VALUES
(1, 230, '2026-03-24 15:19:25', 'Teste de insert de histórico.'),
(2, 1, '2026-03-24 16:05:33', 'Teste de Inserção de Histórico de membro.'),
(3, 220, '2026-03-28 18:58:11', 'Teste de Mensagem sobre o membro.'),
(4, 2, '2026-03-30 15:28:22', 'teste do Matheus');

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

--
-- Despejando dados para a tabela `membros_responsaveis`
--

INSERT INTO `membros_responsaveis` (`parentesco_id`, `parentesco_responsavel_id`, `parentesco_dependente_id`, `parentesco_grau`) VALUES
(1, 238, 239, 'Pai/Mãe'),
(2, 238, 240, 'Pai/Mãe'),
(3, 238, 247, 'Pai/Mãe');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `patrimonio_bens`
--

INSERT INTO `patrimonio_bens` (`patrimonio_bem_id`, `patrimonio_bem_igreja_id`, `patrimonio_bem_codigo`, `patrimonio_bem_nome`, `patrimonio_bem_descricao`, `patrimonio_bem_data_aquisicao`, `patrimonio_bem_valor`, `patrimonio_bem_status`, `patrimonio_bem_local_id`) VALUES
(4, 1, 'PAT-2026-0001', 'Banco Madeira - 5 Lugares', 'Usado e bem antigo.', '2026-03-01', '500.00', 'ativo', 2),
(5, 1, 'PAT-2026-0005', 'Banco Madeira - 5 Lugares', 'Usado bem antigo', '2026-03-01', '500.00', 'ativo', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_documentos`
--

CREATE TABLE `patrimonio_documentos` (
  `patrimonio_documento_id` int(11) NOT NULL,
  `patrimonio_documento_bem_id` int(11) DEFAULT NULL,
  `patrimonio_documento_arquivo` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_imagens`
--

CREATE TABLE `patrimonio_imagens` (
  `patrimonio_imagem_id` int(11) NOT NULL,
  `patrimonio_imagem_bem_id` int(11) DEFAULT NULL,
  `patrimonio_imagem_arquivo` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `patrimonio_imagens`
--

INSERT INTO `patrimonio_imagens` (`patrimonio_imagem_id`, `patrimonio_imagem_bem_id`, `patrimonio_imagem_arquivo`) VALUES
(6, 4, 'foto_1774191653_7982.jpg'),
(5, 4, 'foto_1774191653_7987.png'),
(7, 5, 'foto_1774192722_2726.png'),
(8, 5, 'foto_1774192722_7483.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `patrimonio_locais`
--

CREATE TABLE `patrimonio_locais` (
  `patrimonio_local_id` int(11) NOT NULL,
  `patrimonio_local_igreja_id` int(11) DEFAULT NULL,
  `patrimonio_local_nome` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `patrimonio_locais`
--

INSERT INTO `patrimonio_locais` (`patrimonio_local_id`, `patrimonio_local_igreja_id`, `patrimonio_local_nome`) VALUES
(2, 1, 'Templo'),
(3, 1, 'Cozinha');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `patrimonio_movimentacoes`
--

INSERT INTO `patrimonio_movimentacoes` (`patrimonio_movimentacao_id`, `patrimonio_movimentacao_patrimonio_bem_id`, `patrimonio_movimentacao_igreja_id`, `patrimonio_movimentacao_tipo`, `patrimonio_movimentacao_local_origem`, `patrimonio_movimentacao_local_destino`, `patrimonio_movimentacao_data`, `patrimonio_movimentacao_observacao`) VALUES
(4, 4, 1, 'entrada', NULL, 2, '2026-03-22 10:58:38', 'Entrada inicial. Código gerado: PAT-2026-0001'),
(5, 5, 1, 'entrada', NULL, 2, '2026-03-22 11:18:09', 'Entrada inicial. Código gerado: PAT-2026-0005');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfis`
--

CREATE TABLE `perfis` (
  `perfil_id` int(11) NOT NULL,
  `perfil_nome` varchar(100) DEFAULT NULL,
  `perfil_descricao` text DEFAULT NULL,
  `perfil_status` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `perfis`
--

INSERT INTO `perfis` (`perfil_id`, `perfil_nome`, `perfil_descricao`, `perfil_status`) VALUES
(1, 'Admin', 'Acesso total ao sistema e gestão de usuários', 'ativo'),
(2, 'Tesoureiro', 'Gestão financeira, lançamentos e relatórios', 'ativo'),
(3, 'Secretario', 'Gestão de membros, certificados e documentos', 'ativo'),
(4, 'Professor', 'Acesso ao módulo de Escola Dominical e Chamadas', 'ativo'),
(5, 'Patrimônio', 'Gerenciar, controlar e registrar os bens ativos da Igreja.', 'ativo');

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
(1, 1, 'UCP - União de Crianças Presbiterianas', 'Ambos', 0, 12, 'Infantil', 'Ativo', 193, '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', '1/sociedades/1/logo/logo_1774221938.png', NULL, NULL),
(2, 1, 'UPA - União Presbiteriana de Adolescentes', 'Ambos', 13, 18, 'Adolescentes', 'Ativo', 191, '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', '1/sociedades/2/logo/logo_1774221969.png', NULL, NULL),
(3, 1, 'UMP - União de Mocidade Presbiteriana', 'Ambos', 19, 35, 'Jovens', 'Ativo', 222, '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', '1/sociedades/3/logo/logo_1774221948.png', NULL, NULL),
(4, 1, 'SAF - Sociedade Auxiliadora Feminina', 'Feminino', 18, 99, 'Mulheres', 'Ativo', 162, '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', '1/sociedades/4/logo/logo_1774221924.png', NULL, NULL),
(5, 1, 'UPH - União Presbiteriana de Homens', 'Masculino', 18, 99, 'Homens', 'Ativo', 181, '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', '1/sociedades/5/logo/logo_1774221984.png', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `sociedades_eventos`
--

INSERT INTO `sociedades_eventos` (`sociedade_evento_id`, `sociedade_evento_igreja_id`, `sociedade_evento_sociedade_id`, `sociedade_evento_titulo`, `sociedade_evento_descricao`, `sociedade_evento_local`, `sociedade_evento_imagem`, `sociedade_evento_data_hora_inicio`, `sociedade_evento_data_hora_fim`, `sociedade_evento_valor`, `sociedade_evento_status`, `sociedade_evento_criado_em`) VALUES
(8, 1, 2, 'Futebol', '', 'Igreja Presbiteriana do Jardim Girassol - Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', NULL, '2026-03-27 10:30:00', NULL, '15.00', 'Agendado', '2026-03-23 10:42:56'),
(9, 1, 3, 'Almoço Comunitário', 'teste', 'Na Igreja: Sede Principal', NULL, '2026-03-31 10:30:00', '2026-03-31 12:30:00', '50.00', 'Concluído', '2026-03-26 15:23:31'),
(10, 1, 5, 'Multirão de Limpeza', '', 'Igreja Presbiteriana do Jardim Girassol - Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', NULL, '2026-03-30 16:36:00', '2026-03-30 16:36:00', '5.00', 'Agendado', '2026-03-27 19:36:33'),
(11, 1, 4, 'Noite do Pastel de Vento', 'Vamos fazer um pastel!!!', 'Na Igreja: Sede Principal', NULL, '2026-03-31 17:00:00', '2026-03-31 19:00:00', '8.00', 'Agendado', '2026-03-30 02:59:36'),
(13, 1, 3, 'Cinema em Casa', '', 'Residência de Lucas Nobre Ferreira Martins - Rua das Aeromoças, Guarulhos - SP', NULL, '2026-04-03 19:00:00', '2026-04-03 22:30:00', '5.00', 'Agendado', '2026-03-31 23:11:11'),
(14, 1, 1, 'Futebol Evangelista', '', 'Igreja Presbiteriana do Jardim Girassol - Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', NULL, '2026-04-04 10:30:00', '2026-04-04 13:30:00', '15.00', 'Agendado', '2026-03-31 23:13:01'),
(15, 1, 5, 'Multirão de Limpeza', '', 'Igreja Presbiteriana do Jardim Girassol - Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', NULL, '2026-04-05 08:00:00', '2026-04-05 12:00:00', '0.00', 'Agendado', '2026-03-31 23:14:44'),
(16, 1, 2, 'Escola Bíblica de Férias', '', 'Igreja Presbiteriana do Jardim Girassol - Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350', NULL, '2026-04-12 09:00:00', '2026-04-12 12:30:00', '0.00', 'Agendado', '2026-03-31 23:16:14');

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

--
-- Despejando dados para a tabela `sociedades_eventos_presencas`
--

INSERT INTO `sociedades_eventos_presencas` (`sociedade_presenca_id`, `sociedade_presenca_igreja_id`, `sociedade_presenca_sociedade_id`, `sociedade_presenca_evento_id`, `sociedade_presenca_membro_id`, `sociedade_presenca_status`, `sociedade_presenca_observacao`, `sociedade_presenca_registrado_em`) VALUES
(40, 1, 4, 11, 220, 'Presente', NULL, '2026-03-30 14:30:51'),
(41, 1, 4, 11, 238, 'Faltou', NULL, '2026-03-30 14:30:55'),
(42, 1, 4, 11, 162, 'Presente', NULL, '2026-03-30 14:30:56'),
(43, 1, 4, 11, 196, 'Presente', NULL, '2026-03-30 14:30:57'),
(44, 1, 4, 11, 149, 'Presente', NULL, '2026-03-30 14:31:29'),
(45, 1, 4, 11, 174, 'Faltou', NULL, '2026-03-30 14:31:31'),
(46, 1, 4, 11, 170, 'Presente', NULL, '2026-03-30 14:31:32'),
(47, 1, 4, 11, 147, 'Presente', NULL, '2026-03-30 14:31:33'),
(48, 1, 4, 11, 137, 'Presente', NULL, '2026-03-30 14:31:33'),
(49, 1, 4, 11, 165, 'Presente', NULL, '2026-03-30 14:31:35'),
(50, 1, 4, 11, 145, 'Presente', NULL, '2026-03-30 14:31:36'),
(51, 1, 4, 11, 180, 'Faltou', NULL, '2026-03-30 14:31:37'),
(52, 1, 4, 11, 195, 'Faltou', NULL, '2026-03-30 14:31:38'),
(53, 1, 4, 11, 212, 'Presente', NULL, '2026-03-30 14:31:39'),
(54, 1, 4, 11, 153, 'Presente', NULL, '2026-03-30 14:31:40'),
(55, 1, 4, 11, 133, 'Presente', NULL, '2026-03-30 14:31:40'),
(56, 1, 4, 11, 198, 'Faltou', NULL, '2026-03-30 14:31:41'),
(57, 1, 4, 11, 225, 'Presente', NULL, '2026-03-30 14:31:42'),
(58, 1, 4, 11, 206, 'Presente', NULL, '2026-03-30 14:31:43'),
(59, 1, 4, 11, 208, 'Faltou', NULL, '2026-03-30 14:31:43'),
(60, 1, 4, 11, 157, 'Presente', NULL, '2026-03-30 14:31:44'),
(61, 1, 4, 11, 146, 'Presente', NULL, '2026-03-30 14:31:45'),
(62, 1, 4, 11, 204, 'Presente', NULL, '2026-03-30 14:31:47'),
(63, 1, 4, 11, 182, 'Presente', NULL, '2026-03-30 14:31:47'),
(64, 1, 4, 11, 134, 'Presente', NULL, '2026-03-30 14:32:02'),
(65, 1, 3, 9, 196, 'Presente', NULL, '2026-03-30 22:25:58'),
(66, 1, 3, 9, 174, 'Presente', NULL, '2026-03-30 22:25:59'),
(67, 1, 3, 9, 136, 'Faltou', NULL, '2026-03-30 22:26:00'),
(68, 1, 3, 9, 137, 'Presente', NULL, '2026-03-30 22:26:01'),
(69, 1, 3, 9, 168, 'Presente', NULL, '2026-03-30 22:26:01'),
(70, 1, 3, 9, 142, 'Presente', NULL, '2026-03-30 22:26:02'),
(71, 1, 3, 9, 189, 'Faltou', NULL, '2026-03-30 22:26:03'),
(72, 1, 3, 9, 2, 'Presente', NULL, '2026-03-30 22:26:03'),
(73, 1, 3, 9, 206, 'Presente', NULL, '2026-03-30 22:26:04'),
(74, 1, 3, 9, 208, 'Faltou', NULL, '2026-03-30 22:26:05'),
(75, 1, 3, 9, 156, 'Presente', NULL, '2026-03-30 22:26:05');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `sociedades_membros`
--

INSERT INTO `sociedades_membros` (`sociedade_membro_id`, `sociedade_membro_igreja_id`, `sociedade_membro_sociedade_id`, `sociedade_membro_membro_id`, `sociedade_membro_funcao`) VALUES
(217, 1, 2, 165, 'Sócio'),
(218, 1, 2, 143, 'Sócio'),
(219, 1, 2, 188, 'Sócio'),
(220, 1, 2, 185, 'Sócio'),
(221, 1, 2, 226, 'Sócio'),
(222, 1, 2, 131, 'Sócio'),
(223, 1, 3, 196, 'Sócio'),
(224, 1, 3, 174, 'Sócio'),
(225, 1, 3, 136, 'Sócio'),
(226, 1, 3, 137, 'Sócio'),
(227, 1, 3, 168, 'Sócio'),
(228, 1, 3, 142, 'Sócio'),
(229, 1, 3, 189, 'Sócio'),
(230, 1, 3, 2, 'Sócio'),
(231, 1, 3, 206, 'Sócio'),
(232, 1, 3, 208, 'Sócio'),
(233, 1, 3, 156, 'Sócio'),
(234, 1, 1, 193, 'Sócio'),
(235, 1, 1, 229, 'Sócio'),
(236, 1, 1, 161, 'Sócio'),
(237, 1, 1, 183, 'Sócio'),
(238, 1, 1, 218, 'Sócio'),
(239, 1, 1, 141, 'Sócio'),
(240, 1, 1, 211, 'Sócio'),
(241, 1, 1, 151, 'Sócio'),
(242, 1, 1, 186, 'Sócio'),
(243, 1, 1, 178, 'Sócio'),
(244, 1, 1, 202, 'Sócio'),
(245, 1, 1, 160, 'Sócio'),
(268, 1, 4, 220, 'Sócio'),
(269, 1, 4, 238, 'Sócio'),
(270, 1, 4, 196, 'Sócio'),
(271, 1, 4, 149, 'Sócio'),
(272, 1, 4, 174, 'Sócio'),
(273, 1, 4, 165, 'Sócio'),
(274, 1, 4, 145, 'Sócio'),
(275, 1, 4, 180, 'Sócio'),
(276, 1, 4, 195, 'Sócio'),
(277, 1, 4, 212, 'Sócio'),
(278, 1, 4, 153, 'Sócio'),
(279, 1, 4, 133, 'Sócio'),
(280, 1, 4, 198, 'Sócio'),
(281, 1, 4, 225, 'Sócio'),
(282, 1, 4, 206, 'Sócio'),
(283, 1, 4, 208, 'Sócio'),
(284, 1, 4, 157, 'Sócio'),
(285, 1, 4, 146, 'Sócio'),
(286, 1, 4, 134, 'Sócio'),
(287, 1, 4, 204, 'Sócio'),
(288, 1, 4, 182, 'Sócio'),
(310, NULL, 4, 162, 'Sócio'),
(311, NULL, 4, 170, 'Sócio'),
(312, NULL, 4, 147, 'Sócio'),
(314, 1, 5, 3, 'Sócio'),
(315, 1, 5, 241, 'Sócio'),
(316, 1, 5, 177, 'Sócio'),
(317, 1, 5, 135, 'Sócio'),
(318, 1, 5, 181, 'Sócio'),
(319, 1, 5, 159, 'Sócio'),
(320, 1, 5, 187, 'Sócio'),
(321, 1, 5, 148, 'Sócio'),
(322, 1, 5, 219, 'Sócio'),
(323, 1, 5, 191, 'Sócio'),
(324, 1, 5, 216, 'Sócio'),
(325, 1, 5, 1, 'Sócio'),
(326, 1, 5, 139, 'Sócio'),
(327, 1, 5, 2, 'Sócio'),
(328, 1, 5, 243, 'Sócio'),
(329, 1, 5, 172, 'Sócio'),
(330, 1, 5, 152, 'Sócio'),
(331, 1, 5, 205, 'Sócio'),
(332, 1, 5, 201, 'Sócio'),
(333, 1, 5, 150, 'Sócio'),
(334, 1, 5, 163, 'Sócio'),
(335, 1, 5, 199, 'Sócio'),
(342, NULL, 4, 137, 'Sócio');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario_igreja_id`, `usuario_nome`, `usuario_email`, `usuario_senha`, `usuario_status`, `usuario_data_criacao`) VALUES
(1, 1, 'admin', 'admin@ekklesia.com', '$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe', 'ativo', NULL),
(5, 1, 'Abner Genari', 'abner@teste.com.br', '$2y$10$Yt8wagh/u1fDoc7i6dtR1.mcArmSasVIfu.RBDQJvoLTpyoh1gRHS', 'ativo', '2026-03-28 15:50:09'),
(4, 1, 'Ricardo Oliveira Amorin', 'ricardo@teste.com.br', '$2y$10$7HSa3AvGcCPuuOgDdcZWoOTO68c2FS6sT/Fklr0llEUJFtiQ0Gn6G', 'ativo', '2026-03-28 15:33:52'),
(6, 1, 'Vanderlei Costa Lima', 'vanderlei@teste.com.br', '$2y$10$xlNyez1o6QZ1DraF3vhJTePjTitnTyUPnXx3nSZLw4NfQoBYfR0DS', 'ativo', '2026-03-28 16:08:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_perfis`
--

CREATE TABLE `usuarios_perfis` (
  `usuario_perfil_id` int(11) NOT NULL,
  `usuario_perfil_igreja_id` int(11) DEFAULT NULL,
  `usuario_perfil_usuario_id` int(11) DEFAULT NULL,
  `usuario_perfil_perfil_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `usuarios_perfis`
--

INSERT INTO `usuarios_perfis` (`usuario_perfil_id`, `usuario_perfil_igreja_id`, `usuario_perfil_usuario_id`, `usuario_perfil_perfil_id`) VALUES
(1, 1, 1, 1),
(5, 1, 5, 3),
(30, 1, 4, 2),
(6, 1, 6, 5),
(29, 1, 4, 3),
(28, 1, 4, 4),
(27, 1, 4, 5);

--
-- Índices de tabelas apagadas
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
  ADD KEY `fk_sociedade_lider` (`sociedade_lider`),
  ADD KEY `sociedades_ibfk_1` (`sociedade_igreja_id`);

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
-- AUTO_INCREMENT de tabelas apagadas
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
  MODIFY `classe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `classes_membros`
--
ALTER TABLE `classes_membros`
  MODIFY `classe_membro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `classes_presencas`
--
ALTER TABLE `classes_presencas`
  MODIFY `presenca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `documentos`
--
ALTER TABLE `documentos`
  MODIFY `documento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `documentos_arquivos`
--
ALTER TABLE `documentos_arquivos`
  MODIFY `documento_arquivo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `documentos_categorias`
--
ALTER TABLE `documentos_categorias`
  MODIFY `documento_categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `financeiro_categorias`
--
ALTER TABLE `financeiro_categorias`
  MODIFY `financeiro_categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `financeiro_contas`
--
ALTER TABLE `financeiro_contas`
  MODIFY `financeiro_conta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `financeiro_contas_financeiras`
--
ALTER TABLE `financeiro_contas_financeiras`
  MODIFY `financeiro_conta_financeira_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `financeiro_movimentacoes`
--
ALTER TABLE `financeiro_movimentacoes`
  MODIFY `financeiro_movimentacao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `financeiro_pagamentos`
--
ALTER TABLE `financeiro_pagamentos`
  MODIFY `financeiro_pagamento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `financeiro_receita_membros`
--
ALTER TABLE `financeiro_receita_membros`
  MODIFY `receita_membro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `financeiro_subcategorias`
--
ALTER TABLE `financeiro_subcategorias`
  MODIFY `subcategoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `igrejas`
--
ALTER TABLE `igrejas`
  MODIFY `igreja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `igrejas_liturgias`
--
ALTER TABLE `igrejas_liturgias`
  MODIFY `igreja_liturgia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `igrejas_liturgias_itens`
--
ALTER TABLE `igrejas_liturgias_itens`
  MODIFY `liturgia_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=472;

--
-- AUTO_INCREMENT de tabela `igrejas_mensagens_dominicais`
--
ALTER TABLE `igrejas_mensagens_dominicais`
  MODIFY `igreja_mensagem_dominical_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `igrejas_programacao`
--
ALTER TABLE `igrejas_programacao`
  MODIFY `programacao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `igrejas_redes_sociais`
--
ALTER TABLE `igrejas_redes_sociais`
  MODIFY `rede_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `membros`
--
ALTER TABLE `membros`
  MODIFY `membro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT de tabela `membros_cargos_vinculo`
--
ALTER TABLE `membros_cargos_vinculo`
  MODIFY `vinculo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de tabela `membros_contatos`
--
ALTER TABLE `membros_contatos`
  MODIFY `membro_contato_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `membros_enderecos`
--
ALTER TABLE `membros_enderecos`
  MODIFY `membro_endereco_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `membros_fotos`
--
ALTER TABLE `membros_fotos`
  MODIFY `membro_foto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `membros_historico`
--
ALTER TABLE `membros_historico`
  MODIFY `membro_historico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `membros_responsaveis`
--
ALTER TABLE `membros_responsaveis`
  MODIFY `parentesco_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `patrimonio_bens`
--
ALTER TABLE `patrimonio_bens`
  MODIFY `patrimonio_bem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `patrimonio_documentos`
--
ALTER TABLE `patrimonio_documentos`
  MODIFY `patrimonio_documento_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `patrimonio_imagens`
--
ALTER TABLE `patrimonio_imagens`
  MODIFY `patrimonio_imagem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `patrimonio_locais`
--
ALTER TABLE `patrimonio_locais`
  MODIFY `patrimonio_local_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `patrimonio_movimentacoes`
--
ALTER TABLE `patrimonio_movimentacoes`
  MODIFY `patrimonio_movimentacao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `perfis`
--
ALTER TABLE `perfis`
  MODIFY `perfil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `sociedades`
--
ALTER TABLE `sociedades`
  MODIFY `sociedade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `sociedades_eventos`
--
ALTER TABLE `sociedades_eventos`
  MODIFY `sociedade_evento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `sociedades_eventos_presencas`
--
ALTER TABLE `sociedades_eventos_presencas`
  MODIFY `sociedade_presenca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de tabela `sociedades_membros`
--
ALTER TABLE `sociedades_membros`
  MODIFY `sociedade_membro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT de tabela `sociedades_orcamentos`
--
ALTER TABLE `sociedades_orcamentos`
  MODIFY `sociedade_orcamento_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuarios_perfis`
--
ALTER TABLE `usuarios_perfis`
  MODIFY `usuario_perfil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `classes_config`
--
ALTER TABLE `classes_config`
  ADD CONSTRAINT `fk_config_igreja` FOREIGN KEY (`config_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE;

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
-- Restrições para tabelas `membros_cargos_vinculo`
--
ALTER TABLE `membros_cargos_vinculo`
  ADD CONSTRAINT `fk_cargo` FOREIGN KEY (`vinculo_cargo_id`) REFERENCES `cargos` (`cargo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_membro` FOREIGN KEY (`vinculo_membro_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `membros_responsaveis`
--
ALTER TABLE `membros_responsaveis`
  ADD CONSTRAINT `fk_resp_adulto` FOREIGN KEY (`parentesco_responsavel_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resp_crianca` FOREIGN KEY (`parentesco_dependente_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
