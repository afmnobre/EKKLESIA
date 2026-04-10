-- Backup EKKLESIA Gerado em 09/04/2026 06:18:40
SET FOREIGN_KEY_CHECKS=0;



CREATE TABLE `cargos` (
  `cargo_id` int(11) NOT NULL AUTO_INCREMENT,
  `cargo_nome` varchar(100) NOT NULL,
  PRIMARY KEY (`cargo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO cargos VALUES('1','Pastor (Ministro da Palavra)');
INSERT INTO cargos VALUES('2','Pastor Auxiliar');
INSERT INTO cargos VALUES('3','Seminarista');
INSERT INTO cargos VALUES('4','Pastor Evangelista');
INSERT INTO cargos VALUES('5','Presbítero Regente');
INSERT INTO cargos VALUES('6','Presbítero Docente (Pastor)');
INSERT INTO cargos VALUES('7','Diácono');
INSERT INTO cargos VALUES('8','Presidente do Conselho');
INSERT INTO cargos VALUES('9','Vice-Presidente do Conselho');
INSERT INTO cargos VALUES('10','Secretário do Conselho');
INSERT INTO cargos VALUES('11','Tesoureiro');
INSERT INTO cargos VALUES('12','Líder de Jovens (UMP)');
INSERT INTO cargos VALUES('13','Líder de Adolescentes (UPA)');
INSERT INTO cargos VALUES('14','Líder de Crianças (UCP)');
INSERT INTO cargos VALUES('15','Líder da Escola Dominical');
INSERT INTO cargos VALUES('16','Professor de Escola Dominical');
INSERT INTO cargos VALUES('17','Líder da SAF (Sociedade Auxiliadora Feminina)');
INSERT INTO cargos VALUES('18','Líder da UPH (União Presbiteriana de Homens)');
INSERT INTO cargos VALUES('19','Líder de Louvor');
INSERT INTO cargos VALUES('20','Ministro de Música');
INSERT INTO cargos VALUES('21','Músico / Instrumentista');
INSERT INTO cargos VALUES('22','Vocalista');
INSERT INTO cargos VALUES('23','Líder de Evangelismo');
INSERT INTO cargos VALUES('24','Líder de Missões');
INSERT INTO cargos VALUES('25','Líder de Ação Social / Diaconia');
INSERT INTO cargos VALUES('26','Coordenador Administrativo');
INSERT INTO cargos VALUES('27','Secretário Administrativo');
INSERT INTO cargos VALUES('28','Tesoureiro Auxiliar');
INSERT INTO cargos VALUES('29','Zelador / Responsável pela Estrutura');
INSERT INTO cargos VALUES('30','Equipe de Recepção');
INSERT INTO cargos VALUES('31','Equipe de Mídia / Som / Projeção');


CREATE TABLE `classes_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_igreja_id` int(11) DEFAULT NULL,
  `config_nome` varchar(100) DEFAULT NULL,
  `config_idade_min` int(3) DEFAULT 0,
  `config_idade_max` int(3) DEFAULT 99,
  PRIMARY KEY (`config_id`),
  KEY `config_igreja_id` (`config_igreja_id`),
  CONSTRAINT `fk_config_igreja` FOREIGN KEY (`config_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO classes_config VALUES('1','1','Cordeirinhos','1','3');
INSERT INTO classes_config VALUES('2','1','Arca de Noé','4','6');
INSERT INTO classes_config VALUES('3','1','Josias','7','12');
INSERT INTO classes_config VALUES('4','1','Timóteo','13','17');
INSERT INTO classes_config VALUES('5','1','Adultos I','18','99');
INSERT INTO classes_config VALUES('6','1','Adultos II','18','99');


CREATE TABLE `classes_escola` (
  `classe_id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_igreja_id` int(11) DEFAULT NULL,
  `classe_nome` varchar(100) DEFAULT NULL,
  `classe_professor_id` int(11) DEFAULT NULL,
  `classe_idade_min` int(11) DEFAULT 0,
  `classe_idade_max` int(11) DEFAULT 99,
  `classe_senha` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`classe_id`),
  KEY `classe_igreja_id` (`classe_igreja_id`),
  KEY `classe_professor_id` (`classe_professor_id`),
  CONSTRAINT `classes_escola_ibfk_1` FOREIGN KEY (`classe_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `classes_escola_ibfk_2` FOREIGN KEY (`classe_professor_id`) REFERENCES `membros` (`membro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO classes_escola VALUES('8','1','Cordeirinhos','3','1','3',NULL);
INSERT INTO classes_escola VALUES('9','1','Arca de Noé','399','4','6',NULL);
INSERT INTO classes_escola VALUES('10','1','Josias','405','7','12',NULL);
INSERT INTO classes_escola VALUES('11','1','Timóteo','440','13','17',NULL);
INSERT INTO classes_escola VALUES('12','1','Adultos I','473','18','99',NULL);
INSERT INTO classes_escola VALUES('13','1','Adultos II','408','18','99',NULL);


CREATE TABLE `classes_membros` (
  `classe_membro_id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_membro_classe_id` int(11) DEFAULT NULL,
  `classe_membro_membro_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`classe_membro_id`),
  KEY `classe_membro_classe_id` (`classe_membro_classe_id`),
  KEY `classe_membro_membro_id` (`classe_membro_membro_id`),
  CONSTRAINT `classes_membros_ibfk_1` FOREIGN KEY (`classe_membro_classe_id`) REFERENCES `classes_escola` (`classe_id`),
  CONSTRAINT `classes_membros_ibfk_2` FOREIGN KEY (`classe_membro_membro_id`) REFERENCES `membros` (`membro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO classes_membros VALUES('1','8','423');
INSERT INTO classes_membros VALUES('2','11','474');
INSERT INTO classes_membros VALUES('3','11','431');
INSERT INTO classes_membros VALUES('4','11','468');
INSERT INTO classes_membros VALUES('5','11','440');
INSERT INTO classes_membros VALUES('7','10','448');
INSERT INTO classes_membros VALUES('8','10','393');
INSERT INTO classes_membros VALUES('9','10','406');
INSERT INTO classes_membros VALUES('10','10','480');
INSERT INTO classes_membros VALUES('11','10','416');
INSERT INTO classes_membros VALUES('12','10','418');
INSERT INTO classes_membros VALUES('13','10','469');
INSERT INTO classes_membros VALUES('14','13','390');
INSERT INTO classes_membros VALUES('15','13','471');
INSERT INTO classes_membros VALUES('16','13','403');
INSERT INTO classes_membros VALUES('17','13','415');
INSERT INTO classes_membros VALUES('18','13','401');
INSERT INTO classes_membros VALUES('19','13','395');
INSERT INTO classes_membros VALUES('20','13','419');
INSERT INTO classes_membros VALUES('21','13','399');
INSERT INTO classes_membros VALUES('22','12','3');
INSERT INTO classes_membros VALUES('23','12','434');
INSERT INTO classes_membros VALUES('24','12','478');
INSERT INTO classes_membros VALUES('25','12','449');
INSERT INTO classes_membros VALUES('26','12','461');
INSERT INTO classes_membros VALUES('27','12','442');
INSERT INTO classes_membros VALUES('28','12','443');
INSERT INTO classes_membros VALUES('29','12','421');
INSERT INTO classes_membros VALUES('30','12','465');
INSERT INTO classes_membros VALUES('31','12','457');
INSERT INTO classes_membros VALUES('32','9','460');
INSERT INTO classes_membros VALUES('33','12','1');


CREATE TABLE `classes_presencas` (
  `presenca_id` int(11) NOT NULL AUTO_INCREMENT,
  `presenca_classe_id` int(11) DEFAULT NULL,
  `presenca_membro_id` int(11) DEFAULT NULL,
  `presenca_data` date DEFAULT NULL,
  `presenca_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`presenca_id`),
  KEY `presenca_classe_id` (`presenca_classe_id`),
  KEY `presenca_membro_id` (`presenca_membro_id`),
  CONSTRAINT `classes_presencas_ibfk_1` FOREIGN KEY (`presenca_classe_id`) REFERENCES `classes_escola` (`classe_id`),
  CONSTRAINT `classes_presencas_ibfk_2` FOREIGN KEY (`presenca_membro_id`) REFERENCES `membros` (`membro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO classes_presencas VALUES('1','10','416','2026-03-21','1');
INSERT INTO classes_presencas VALUES('2','10','469','2026-03-21','1');
INSERT INTO classes_presencas VALUES('3','10','448','2026-03-21','0');
INSERT INTO classes_presencas VALUES('4','10','418','2026-03-21','1');
INSERT INTO classes_presencas VALUES('5','10','393','2026-03-21','0');
INSERT INTO classes_presencas VALUES('6','10','406','2026-03-21','1');
INSERT INTO classes_presencas VALUES('7','10','480','2026-03-21','1');
INSERT INTO classes_presencas VALUES('8','11','474','2026-03-21','1');
INSERT INTO classes_presencas VALUES('9','11','431','2026-03-21','1');
INSERT INTO classes_presencas VALUES('10','11','468','2026-03-21','0');
INSERT INTO classes_presencas VALUES('11','11','440','2026-03-21','1');
INSERT INTO classes_presencas VALUES('12','12','1','2026-03-21','1');


CREATE TABLE `documentos` (
  `documento_id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_igreja_id` int(11) DEFAULT NULL,
  `documento_documento_categoria_id` int(11) DEFAULT NULL,
  `documento_nome` varchar(150) DEFAULT NULL,
  `documento_descricao` text DEFAULT NULL,
  `documento_data_referencia` date DEFAULT NULL,
  `documento_status` enum('ativo','arquivado','cancelado') DEFAULT NULL,
  PRIMARY KEY (`documento_id`),
  KEY `documento_igreja_id` (`documento_igreja_id`),
  KEY `documento_documento_categoria_id` (`documento_documento_categoria_id`),
  CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`documento_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `documentos_ibfk_2` FOREIGN KEY (`documento_documento_categoria_id`) REFERENCES `documentos_categorias` (`documento_categoria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `documentos_arquivos` (
  `documento_arquivo_id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_arquivo_documento_id` int(11) DEFAULT NULL,
  `documento_arquivo_nome` varchar(150) DEFAULT NULL,
  `documento_arquivo_caminho` varchar(255) DEFAULT NULL,
  `documento_arquivo_tipo` varchar(20) DEFAULT NULL,
  `documento_arquivo_data` datetime DEFAULT NULL,
  PRIMARY KEY (`documento_arquivo_id`),
  KEY `documento_arquivo_documento_id` (`documento_arquivo_documento_id`),
  CONSTRAINT `documentos_arquivos_ibfk_1` FOREIGN KEY (`documento_arquivo_documento_id`) REFERENCES `documentos` (`documento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `documentos_categorias` (
  `documento_categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_categoria_igreja_id` int(11) DEFAULT NULL,
  `documento_categoria_nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`documento_categoria_id`),
  KEY `documento_categoria_igreja_id` (`documento_categoria_igreja_id`),
  CONSTRAINT `documentos_categorias_ibfk_1` FOREIGN KEY (`documento_categoria_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `financeiro_categorias` (
  `financeiro_categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `financeiro_categoria_igreja_id` int(11) DEFAULT NULL,
  `financeiro_categoria_nome` varchar(100) DEFAULT NULL,
  `financeiro_categoria_tipo` enum('entrada','saida') DEFAULT NULL,
  PRIMARY KEY (`financeiro_categoria_id`),
  KEY `financeiro_categoria_igreja_id` (`financeiro_categoria_igreja_id`),
  CONSTRAINT `financeiro_categorias_ibfk_1` FOREIGN KEY (`financeiro_categoria_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `financeiro_contas` (
  `financeiro_conta_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `financeiro_conta_nota_fiscal` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`financeiro_conta_id`),
  KEY `financeiro_conta_igreja_id` (`financeiro_conta_igreja_id`),
  KEY `financeiro_conta_financeiro_categoria_id` (`financeiro_conta_financeiro_categoria_id`),
  CONSTRAINT `financeiro_contas_ibfk_1` FOREIGN KEY (`financeiro_conta_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `financeiro_contas_ibfk_2` FOREIGN KEY (`financeiro_conta_financeiro_categoria_id`) REFERENCES `financeiro_categorias` (`financeiro_categoria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `financeiro_contas_financeiras` (
  `financeiro_conta_financeira_id` int(11) NOT NULL AUTO_INCREMENT,
  `financeiro_conta_financeira_igreja_id` int(11) DEFAULT NULL,
  `financeiro_conta_financeira_nome` varchar(100) DEFAULT NULL,
  `financeiro_conta_financeira_tipo` enum('caixa','banco','carteira') DEFAULT NULL,
  `financeiro_conta_financeira_saldo` decimal(12,2) DEFAULT NULL,
  `financeiro_conta_financeira_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`financeiro_conta_financeira_id`),
  KEY `financeiro_conta_financeira_igreja_id` (`financeiro_conta_financeira_igreja_id`),
  CONSTRAINT `financeiro_contas_financeiras_ibfk_1` FOREIGN KEY (`financeiro_conta_financeira_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `financeiro_movimentacoes` (
  `financeiro_movimentacao_id` int(11) NOT NULL AUTO_INCREMENT,
  `financeiro_movimentacao_igreja_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_financeiro_conta_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_financeiro_categoria_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_financeiro_conta_financeira_id` int(11) DEFAULT NULL,
  `financeiro_movimentacao_tipo` enum('entrada','saida') DEFAULT NULL,
  `financeiro_movimentacao_valor` decimal(12,2) DEFAULT NULL,
  `financeiro_movimentacao_data` datetime DEFAULT NULL,
  `financeiro_movimentacao_descricao` text DEFAULT NULL,
  `financeiro_movimentacao_origem` enum('manual','pagamento','transferencia') DEFAULT NULL,
  PRIMARY KEY (`financeiro_movimentacao_id`),
  KEY `financeiro_movimentacao_igreja_id` (`financeiro_movimentacao_igreja_id`),
  KEY `financeiro_movimentacao_financeiro_conta_id` (`financeiro_movimentacao_financeiro_conta_id`),
  KEY `financeiro_movimentacao_financeiro_categoria_id` (`financeiro_movimentacao_financeiro_categoria_id`),
  KEY `financeiro_movimentacao_financeiro_conta_financeira_id` (`financeiro_movimentacao_financeiro_conta_financeira_id`),
  CONSTRAINT `financeiro_movimentacoes_ibfk_1` FOREIGN KEY (`financeiro_movimentacao_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `financeiro_movimentacoes_ibfk_2` FOREIGN KEY (`financeiro_movimentacao_financeiro_conta_id`) REFERENCES `financeiro_contas` (`financeiro_conta_id`),
  CONSTRAINT `financeiro_movimentacoes_ibfk_3` FOREIGN KEY (`financeiro_movimentacao_financeiro_categoria_id`) REFERENCES `financeiro_categorias` (`financeiro_categoria_id`),
  CONSTRAINT `financeiro_movimentacoes_ibfk_4` FOREIGN KEY (`financeiro_movimentacao_financeiro_conta_financeira_id`) REFERENCES `financeiro_contas_financeiras` (`financeiro_conta_financeira_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `financeiro_pagamentos` (
  `financeiro_pagamento_id` int(11) NOT NULL AUTO_INCREMENT,
  `financeiro_pagamento_igreja_id` int(11) DEFAULT NULL,
  `financeiro_pagamento_financeiro_conta_id` int(11) DEFAULT NULL,
  `financeiro_pagamento_valor` decimal(12,2) DEFAULT NULL,
  `financeiro_pagamento_conta_financeira_id` int(11) DEFAULT NULL,
  `financeiro_pagamento_metodo` enum('dinheiro','pix','cartao_credito','cartao_debito','transferencia') DEFAULT NULL,
  `financeiro_pagamento_documentos` text DEFAULT NULL,
  `financeiro_pagamento_data` datetime DEFAULT NULL,
  PRIMARY KEY (`financeiro_pagamento_id`),
  KEY `financeiro_pagamento_igreja_id` (`financeiro_pagamento_igreja_id`),
  KEY `financeiro_pagamento_financeiro_conta_id` (`financeiro_pagamento_financeiro_conta_id`),
  CONSTRAINT `financeiro_pagamentos_ibfk_1` FOREIGN KEY (`financeiro_pagamento_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `financeiro_pagamentos_ibfk_2` FOREIGN KEY (`financeiro_pagamento_financeiro_conta_id`) REFERENCES `financeiro_contas` (`financeiro_conta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `financeiro_receita_membros` (
  `receita_membro_id` int(11) NOT NULL AUTO_INCREMENT,
  `receita_membro_conta_id` int(11) NOT NULL,
  `receita_membro_categoria_id` int(11) DEFAULT NULL,
  `receita_membro_subcategoria_id` int(11) DEFAULT NULL,
  `receita_membro_usuario_id` int(11) NOT NULL,
  `receita_membro_valor` decimal(10,2) NOT NULL,
  `receita_membro_data` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`receita_membro_id`),
  KEY `receita_membro_conta_id` (`receita_membro_conta_id`),
  CONSTRAINT `financeiro_receita_membros_ibfk_1` FOREIGN KEY (`receita_membro_conta_id`) REFERENCES `financeiro_contas` (`financeiro_conta_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `financeiro_subcategorias` (
  `subcategoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `subcategoria_igreja_id` int(11) NOT NULL,
  `subcategoria_categoria_id` int(11) NOT NULL,
  `subcategoria_nome` varchar(100) NOT NULL,
  PRIMARY KEY (`subcategoria_id`),
  KEY `subcategoria_igreja_id` (`subcategoria_igreja_id`),
  KEY `subcategoria_categoria_id` (`subcategoria_categoria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



CREATE TABLE `igrejas` (
  `igreja_id` int(11) NOT NULL AUTO_INCREMENT,
  `igreja_nome` varchar(150) DEFAULT NULL,
  `igreja_cnpj` varchar(20) DEFAULT NULL,
  `igreja_endereco` text DEFAULT NULL,
  `igreja_pastor_id` int(11) DEFAULT NULL,
  `igreja_data_criacao` datetime DEFAULT NULL,
  `igreja_logo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`igreja_id`),
  KEY `fk_igreja_pastor` (`igreja_pastor_id`),
  CONSTRAINT `fk_igreja_pastor` FOREIGN KEY (`igreja_pastor_id`) REFERENCES `membros` (`membro_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO igrejas VALUES('1','Igreja Presbiteriana do Jardim Girassol','08.012.132/0001-45','Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350','413','2026-03-18 22:31:04','logo_1775250808.png');


CREATE TABLE `igrejas_liturgias` (
  `igreja_liturgia_id` int(11) NOT NULL AUTO_INCREMENT,
  `igreja_liturgia_igreja_id` int(11) NOT NULL,
  `igreja_liturgia_data` datetime NOT NULL,
  `igreja_liturgia_tema` varchar(255) DEFAULT NULL COMMENT 'Ex: Culto de Doutrina, Celebração',
  `igreja_liturgia_pregador_id` int(11) DEFAULT NULL,
  `igreja_liturgia_pregador_nome` varchar(255) DEFAULT NULL,
  `igreja_liturgia_dirigente_id` int(11) DEFAULT NULL,
  `igreja_liturgia_dirigente_nome` varchar(255) DEFAULT NULL,
  `igreja_liturgia_status` enum('rascunho','publicado') DEFAULT 'publicado',
  `igreja_liturgia_data_criacao` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`igreja_liturgia_id`),
  KEY `fk_liturgia_igreja` (`igreja_liturgia_igreja_id`),
  CONSTRAINT `fk_liturgia_igreja` FOREIGN KEY (`igreja_liturgia_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO igrejas_liturgias VALUES('1','1','2026-04-03 18:00:00','','474','','484','','publicado','2026-04-03 17:49:31');


CREATE TABLE `igrejas_liturgias_itens` (
  `liturgia_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `liturgia_item_liturgia_id` int(11) NOT NULL,
  `liturgia_item_ordem` int(11) NOT NULL DEFAULT 0,
  `liturgia_item_descricao` varchar(255) NOT NULL COMMENT 'Ex: Leitura Bíblica, Hino, Oração',
  `liturgia_item_tipo` enum('texto','hino','leitura','mensagem','oracao') DEFAULT 'texto',
  `liturgia_item_referencia` varchar(100) DEFAULT NULL COMMENT 'Ex: Salmo 23 ou Hino 100',
  `liturgia_item_conteudo_api` longtext DEFAULT NULL COMMENT 'Texto da bíblia retornado pela API',
  PRIMARY KEY (`liturgia_item_id`),
  KEY `fk_item_liturgia` (`liturgia_item_liturgia_id`),
  CONSTRAINT `fk_item_liturgia` FOREIGN KEY (`liturgia_item_liturgia_id`) REFERENCES `igrejas_liturgias` (`igreja_liturgia_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO igrejas_liturgias_itens VALUES('19','1','1','Notas e Avisos','texto','','');
INSERT INTO igrejas_liturgias_itens VALUES('20','1','2','Oração de Invocação','oracao','','');
INSERT INTO igrejas_liturgias_itens VALUES('21','1','3','Leitura Bíblica de Abertura','leitura','Ageu 1:1-2','No segundo ano do rei Dario, no sexto mês, no primeiro dia do mês, veio a palavra do Senhor, por intermédio do profeta Ageu, a Zorobabel, governador de Judá, filho de Sealtiel, e a Josué, o sumo sacerdote, filho de Jeozadaque, dizendo:   Assim fala o Senhor dos exércitos, dizendo: Este povo diz: Não veio ainda o tempo, o tempo de se edificar a casa do Senhor.');
INSERT INTO igrejas_liturgias_itens VALUES('22','1','4','Oração de Confissão','oracao','','');
INSERT INTO igrejas_liturgias_itens VALUES('23','1','5','Hino / Cântico','hino','','');
INSERT INTO igrejas_liturgias_itens VALUES('24','1','6','Leitura Bíblica','leitura','Romanos 1:1-2','Paulo, servo de Jesus Cristo, chamado para ser apóstolo, separado para o evangelho de Deus,   que ele antes havia prometido pelos seus profetas nas santas Escrituras,');
INSERT INTO igrejas_liturgias_itens VALUES('25','1','7','Oração de Intercessão','oracao','','');
INSERT INTO igrejas_liturgias_itens VALUES('26','1','8','Hino / Cântico','hino','','');
INSERT INTO igrejas_liturgias_itens VALUES('27','1','9','Leitura Bíblica','leitura','Mateus 5:1-4','Jesus, pois, vendo as multidões, subiu ao monte; e, tendo se assentado, aproximaram-se os seus discípulos,   e ele se pôs a ensiná-los, dizendo:   Bem-aventurados os humildes de espírito, porque deles é o reino dos céus.   Bem-aventurados os que choram, porque eles serão consolados.');
INSERT INTO igrejas_liturgias_itens VALUES('28','1','10','Ofertas e Dízimos','hino','','');
INSERT INTO igrejas_liturgias_itens VALUES('29','1','11','Oração de Gratidão','oracao','','');
INSERT INTO igrejas_liturgias_itens VALUES('30','1','12','Exposição da Palavra (Sermão)','mensagem','Lucas 2:1-11','Naqueles dias saiu um decreto da parte de César Augusto, para que todo o mundo fosse recenseado.   Este primeiro recenseamento foi feito quando Quirínio era governador da Síria.   E todos iam alistar-se, cada um à sua própria cidade.   Subiu também José, da Galiléia, da cidade de Nazaré, à cidade de Davi, chamada Belém, porque era da casa e família de Davi,   a fim de alistar-se com Maria, sua esposa, que estava grávida.   Enquanto estavam ali, chegou o tempo em que ela havia de dar à luz,   e teve a seu filho primogênito; envolveu-o em faixas e o deitou em uma manjedoura, porque não havia lugar para eles na estalagem.   Ora, havia naquela mesma região pastores que estavam no campo, e guardavam durante as vigílias da noite o seu rebanho.   E um anjo do Senhor apareceu-lhes, e a glória do Senhor os cercou de resplendor; pelo que se encheram de grande temor.   O anjo, porém, lhes disse: Não temais, porquanto vos trago novas de grande alegria que o será para todo o povo:   É que vos nasceu hoje, na cidade de Davi, o Salvador, que é Cristo, o Senhor.');
INSERT INTO igrejas_liturgias_itens VALUES('31','1','13','Hino de Encerramento','hino','','');
INSERT INTO igrejas_liturgias_itens VALUES('32','1','14','Benção Apostólica','oracao','','');
INSERT INTO igrejas_liturgias_itens VALUES('33','1','15','Amém Tríplice','texto','','');
INSERT INTO igrejas_liturgias_itens VALUES('34','1','16','Pósludio','texto','','');
INSERT INTO igrejas_liturgias_itens VALUES('35','1','17','Saudação aos Visitantes','texto','','');
INSERT INTO igrejas_liturgias_itens VALUES('36','1','18','Cumprimentos à porta','texto','','');


CREATE TABLE `igrejas_mensagens_dominicais` (
  `igreja_mensagem_dominical_id` int(11) NOT NULL AUTO_INCREMENT,
  `igreja_mensagem_dominical_igreja_id` int(11) NOT NULL,
  `igreja_mensagem_dominical_num_historico` int(11) NOT NULL,
  `igreja_mensagem_dominical_data` date NOT NULL,
  `igreja_mensagem_dominical_autor_id` int(11) NOT NULL,
  `igreja_mensagem_dominical_titulo` varchar(255) NOT NULL,
  `igreja_mensagem_dominical_mensagem` longtext NOT NULL,
  `igreja_mensagem_dominical_status` enum('rascunho','publicado') DEFAULT 'publicado',
  `igreja_mensagem_dominical_data_criacao` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`igreja_mensagem_dominical_id`),
  KEY `fk_boletim_igreja` (`igreja_mensagem_dominical_igreja_id`),
  KEY `fk_boletim_autor` (`igreja_mensagem_dominical_autor_id`),
  CONSTRAINT `fk_boletim_autor` FOREIGN KEY (`igreja_mensagem_dominical_autor_id`) REFERENCES `membros` (`membro_id`),
  CONSTRAINT `fk_boletim_igreja` FOREIGN KEY (`igreja_mensagem_dominical_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO igrejas_mensagens_dominicais VALUES('1','1','1','2026-04-03','413','Reflexão sobre Graça','<h2>Why do we use it?</h2>\n\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\n\n<p>&nbsp;</p>\n\n<h2>Where does it come from?</h2>\n\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\n','publicado','2026-04-03 17:48:47');


CREATE TABLE `igrejas_programacao` (
  `programacao_id` int(11) NOT NULL AUTO_INCREMENT,
  `programacao_igreja_id` int(11) NOT NULL,
  `programacao_titulo` varchar(150) NOT NULL,
  `programacao_descricao` text DEFAULT NULL,
  `programacao_dia_semana` enum('Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado') NOT NULL,
  `programacao_hora` time NOT NULL,
  `programacao_recorrencia_mensal` tinyint(1) DEFAULT 0 COMMENT '0: Toda semana, 1-4: Semana específica do mês',
  `programacao_is_ceia` tinyint(1) DEFAULT 0 COMMENT '1: Define que este horário é o Culto de Ceia',
  `programacao_status` enum('Ativo','Inativo') DEFAULT 'Ativo',
  `programacao_criado_em` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`programacao_id`),
  KEY `fk_programacao_igreja` (`programacao_igreja_id`),
  CONSTRAINT `fk_programacao_igreja` FOREIGN KEY (`programacao_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO igrejas_programacao VALUES('1','1','Reunião de Oração',NULL,'Sexta','20:00:00','0','0','Ativo','2026-04-03 18:14:03');
INSERT INTO igrejas_programacao VALUES('2','1','Culto Publico',NULL,'Domingo','18:00:00','0','0','Ativo','2026-04-03 18:14:17');
INSERT INTO igrejas_programacao VALUES('3','1','Estudo Bíblico',NULL,'Quarta','20:00:00','0','0','Ativo','2026-04-03 18:15:01');
INSERT INTO igrejas_programacao VALUES('4','1','Santa Ceia',NULL,'Domingo','18:00:00','0','1','Ativo','2026-04-03 18:15:15');


CREATE TABLE `igrejas_redes_sociais` (
  `rede_id` int(11) NOT NULL AUTO_INCREMENT,
  `rede_igreja_id` int(11) NOT NULL,
  `rede_nome` varchar(50) NOT NULL,
  `rede_usuario` varchar(100) NOT NULL,
  `rede_icone` varchar(50) DEFAULT NULL,
  `rede_status` enum('ativo','inativo') DEFAULT 'ativo',
  PRIMARY KEY (`rede_id`),
  KEY `rede_igreja_id` (`rede_igreja_id`),
  CONSTRAINT `igrejas_redes_sociais_ibfk_1` FOREIGN KEY (`rede_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO igrejas_redes_sociais VALUES('2','1','Instagram','@ipjdgirassol/',NULL,'ativo');


CREATE TABLE `membros` (
  `membro_id` int(11) NOT NULL AUTO_INCREMENT,
  `membro_igreja_id` int(11) DEFAULT NULL,
  `membro_registro_interno` varchar(20) DEFAULT NULL,
  `membro_nome` varchar(150) DEFAULT NULL,
  `membro_data_nascimento` date DEFAULT NULL,
  `membro_genero` varchar(20) DEFAULT NULL,
  `membro_estado_civil` varchar(50) DEFAULT NULL,
  `membro_rg` varchar(20) DEFAULT NULL,
  `membro_cpf` varchar(20) DEFAULT NULL,
  `membro_email` varchar(150) DEFAULT NULL,
  `membro_senha` varchar(255) DEFAULT NULL,
  `membro_telefone` varchar(20) DEFAULT NULL,
  `membro_data_batismo` date DEFAULT NULL,
  `membro_data_casamento` date DEFAULT NULL,
  `membro_status` varchar(20) DEFAULT NULL,
  `membro_data_criacao` datetime DEFAULT NULL,
  `membro_aceite_lgpd` tinyint(1) DEFAULT 0 COMMENT '0=Não, 1=Sim',
  `membro_data_aceite` datetime DEFAULT NULL,
  `membro_ip_aceite` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`membro_id`),
  KEY `membro_igreja_id` (`membro_igreja_id`),
  CONSTRAINT `membros_ibfk_1` FOREIGN KEY (`membro_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB AUTO_INCREMENT=485 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO membros VALUES('1','1','12026030001','Lucas Nobre Ferreira Martins','1987-09-15','Masculino',NULL,NULL,NULL,'lnfm1987@gmail.com',NULL,'11962851513',NULL,NULL,'Ativo','2026-03-19 05:04:18','0',NULL,NULL);
INSERT INTO membros VALUES('3','1','12026030003','Adilson Ferreira Martins','1962-07-26','Masculino',NULL,NULL,NULL,'afmnobre1962@gmail.com',NULL,'11995852032',NULL,NULL,'Ativo','2026-03-19 07:06:10','0',NULL,NULL);
INSERT INTO membros VALUES('385','1','1202603385','Tony Stark','1977-03-29','Masculino',NULL,NULL,NULL,'hero1@geekchurch.com',NULL,'11929682946','2020-02-20',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('386','1','1202603386','Steve Rogers','1988-01-17','Masculino',NULL,NULL,NULL,'hero2@geekchurch.com',NULL,'11919368454','2022-08-04',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('387','1','1202603387','Natasha Romanoff','1976-07-11','Feminino',NULL,NULL,NULL,'hero3@geekchurch.com',NULL,'11948036946','2020-02-27',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('388','1','1202603388','Wanda Maximoff','1950-08-26','Feminino',NULL,NULL,NULL,'hero4@geekchurch.com',NULL,'11910007599','2019-02-18',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('389','1','1202603389','Bruce Wayne','1972-05-07','Masculino',NULL,NULL,NULL,'hero5@geekchurch.com',NULL,'11963610533','2022-10-09',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('390','1','1202603390','Clark Kent','1933-06-30','Masculino',NULL,NULL,NULL,'hero6@geekchurch.com',NULL,'11968637717','2021-10-05',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('391','1','1202603391','Diana Prince','1998-12-19','Feminino',NULL,NULL,NULL,'hero7@geekchurch.com',NULL,'11913533168','2022-07-09',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('392','1','1202603392','Peter Parker','1953-10-26','Masculino',NULL,NULL,NULL,'hero8@geekchurch.com',NULL,'11959435251','2020-09-16',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('393','1','1202603393','Luke Skywalker','2015-08-16','Masculino',NULL,NULL,NULL,'hero9@geekchurch.com',NULL,'11989424767','2025-04-22',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('394','1','1202603394','Leia Organa','1946-03-23','Feminino',NULL,NULL,NULL,'hero10@geekchurch.com',NULL,'11979229799','2022-01-06',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('395','1','1202603395','Han Solo','1947-08-22','Masculino',NULL,NULL,NULL,'hero11@geekchurch.com',NULL,'11973915410','2024-07-15',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('396','1','1202603396','Hermione Granger','1955-12-30','Feminino',NULL,NULL,NULL,'hero12@geekchurch.com',NULL,'11914028454','2025-04-10',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('397','1','1202603397','Harry Potter','1992-10-14','Masculino',NULL,NULL,NULL,'hero13@geekchurch.com',NULL,'11946560426','2026-01-16',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('398','1','1202603398','Ron Weasley','1940-04-22','Masculino',NULL,NULL,NULL,'hero14@geekchurch.com',NULL,'11936066022','2017-10-17',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('399','1','1202603399','Ellen Ripley','1991-12-22','Feminino',NULL,NULL,NULL,'hero15@geekchurch.com',NULL,'11928150472','2016-07-10',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('400','1','1202603400','Sarah Connor','2002-01-01','Feminino',NULL,NULL,NULL,'hero16@geekchurch.com',NULL,'11938255320','2017-11-13',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('401','1','1202603401','Daenerys Targaryen','2002-12-05','Feminino',NULL,NULL,NULL,'hero17@geekchurch.com',NULL,'11970363012','2019-10-01',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('402','1','1202603402','Jon Snow','2004-01-12','Masculino',NULL,NULL,NULL,'hero18@geekchurch.com',NULL,'11926147529','2023-12-23',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('403','1','1202603403','Arya Stark','1968-06-28','Feminino',NULL,NULL,NULL,'hero19@geekchurch.com',NULL,'11932001129','2021-07-03',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('404','1','1202603404','Tyrion Lannister','1964-05-01','Masculino',NULL,NULL,NULL,'hero20@geekchurch.com',NULL,'11974107242','2019-05-17',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('405','1','1202603405','Monkey D Luffy','1997-10-01','Masculino',NULL,NULL,NULL,'hero21@geekchurch.com',NULL,'11944549113','2025-08-27',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('406','1','1202603406','Roronoa Zoro','2013-06-02','Masculino',NULL,NULL,NULL,'hero22@geekchurch.com',NULL,'11953033690','2026-03-11',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('407','1','1202603407','Nami','1968-12-03','Feminino',NULL,NULL,NULL,'hero23@geekchurch.com',NULL,'11989869322','2019-03-23',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('408','1','1202603408','Nico Robin','1943-06-04','Feminino',NULL,NULL,NULL,'hero24@geekchurch.com',NULL,'11917636343','2017-02-01',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('409','1','1202603409','Naruto Uzumaki','1995-03-12','Masculino',NULL,NULL,NULL,'hero25@geekchurch.com',NULL,'11984435895','2024-04-09',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('410','1','1202603410','Sasuke Uchiha','1977-07-31','Masculino',NULL,NULL,NULL,'hero26@geekchurch.com',NULL,'11988690552','2017-04-03',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('411','1','1202603411','Sakura Haruno','1940-12-29','Feminino',NULL,NULL,NULL,'hero27@geekchurch.com',NULL,'11965475191','2021-03-30',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('412','1','1202603412','Hinata Hyuga','1962-12-15','Feminino',NULL,NULL,NULL,'hero28@geekchurch.com',NULL,'11973370605','2020-03-13',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('413','1','1202603413','Goku','1937-04-22','Masculino',NULL,NULL,NULL,'hero29@geekchurch.com',NULL,'11971818481','2018-11-03',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('414','1','1202603414','Vegeta','1963-10-28','Masculino',NULL,NULL,NULL,'hero30@geekchurch.com',NULL,'11994472367','2018-03-19',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('415','1','1202603415','Bulma','2007-07-18','Feminino',NULL,NULL,NULL,'hero31@geekchurch.com',NULL,'11958690583','2024-10-30',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('416','1','1202603416','Android 18','2019-03-10','Feminino',NULL,NULL,NULL,'hero32@geekchurch.com',NULL,'11994492959','2021-05-30',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('417','1','1202603417','Walter White','1968-01-14','Masculino',NULL,NULL,NULL,'hero33@geekchurch.com',NULL,'11954791196','2018-12-24',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('418','1','1202603418','Jesse Pinkman','2013-07-28','Masculino',NULL,NULL,NULL,'hero34@geekchurch.com',NULL,'11951985980','2016-09-24',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('419','1','1202603419','Eleven','1991-12-20','Feminino',NULL,NULL,NULL,'hero35@geekchurch.com',NULL,'11989516272','2022-06-06',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('420','1','1202603420','Jim Hopper','2002-01-12','Masculino',NULL,NULL,NULL,'hero36@geekchurch.com',NULL,'11917680552','2019-04-16',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('421','1','1202603421','Michael Jackson','2005-05-26','Masculino',NULL,NULL,NULL,'hero37@geekchurch.com',NULL,'11997504966','2023-12-02',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('422','1','1202603422','Freddie Mercury','2003-02-06','Masculino',NULL,NULL,NULL,'hero38@geekchurch.com',NULL,'11953022616','2019-05-01',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('423','1','1202603423','Lady Gaga','2025-01-23','Feminino',NULL,NULL,NULL,'hero39@geekchurch.com',NULL,'11999175406',NULL,NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('424','1','1202603424','Beyonce','1964-07-10','Feminino',NULL,NULL,NULL,'hero40@geekchurch.com',NULL,'11942422054','2016-12-01',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('425','1','1202603425','Rick Sanchez','1969-08-29','Masculino',NULL,NULL,NULL,'hero41@geekchurch.com',NULL,'11916028524','2020-01-09',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('426','1','1202603426','Morty Smith','1937-05-05','Masculino',NULL,NULL,NULL,'hero42@geekchurch.com',NULL,'11966886594','2021-07-26',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('427','1','1202603427','Summer Smith','1983-08-10','Feminino',NULL,NULL,NULL,'hero43@geekchurch.com',NULL,'11978157622','2021-04-11',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('428','1','1202603428','Beth Smith','2006-05-23','Feminino',NULL,NULL,NULL,'hero44@geekchurch.com',NULL,'11956671899','2016-04-22',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('429','1','1202603429','Homelander','1986-05-15','Masculino',NULL,NULL,NULL,'hero45@geekchurch.com',NULL,'11913496362','2016-05-13',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('430','1','1202603430','Starlight','1945-10-22','Feminino',NULL,NULL,NULL,'hero46@geekchurch.com',NULL,'11919567868','2025-04-14',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('431','1','1202603431','Billy Butcher','2011-08-31','Masculino',NULL,NULL,NULL,'hero47@geekchurch.com',NULL,'11951027854','2017-11-04',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('432','1','1202603432','Queen Maeve','1944-11-01','Feminino',NULL,NULL,NULL,'hero48@geekchurch.com',NULL,'11963896044','2020-12-15',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('433','1','1202603433','Joel Miller','1943-07-16','Masculino',NULL,NULL,NULL,'hero49@geekchurch.com',NULL,'11963931675','2021-04-30',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('434','1','1202603434','Ellie Williams','1962-02-26','Feminino',NULL,NULL,NULL,'hero50@geekchurch.com',NULL,'11979341035','2017-02-15',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('435','1','1202603435','Geralt of Rivia','2002-09-30','Masculino',NULL,NULL,NULL,'hero51@geekchurch.com',NULL,'11951127759','2020-06-27',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('436','1','1202603436','Yennefer','1977-03-29','Feminino',NULL,NULL,NULL,'hero52@geekchurch.com',NULL,'11978013006','2023-04-15',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('437','1','1202603437','Ciri','2006-06-23','Feminino',NULL,NULL,NULL,'hero53@geekchurch.com',NULL,'11920591157','2016-04-29',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('438','1','1202603438','Triss Merigold','1967-04-04','Feminino',NULL,NULL,NULL,'hero54@geekchurch.com',NULL,'11910941581','2023-07-29',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('439','1','1202603439','Katniss Everdeen','1997-06-21','Feminino',NULL,NULL,NULL,'hero55@geekchurch.com',NULL,'11969365437','2021-12-15',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('440','1','1202603440','Peeta Mellark','2011-01-22','Masculino',NULL,NULL,NULL,'hero56@geekchurch.com',NULL,'11953812501','2016-06-24',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('441','1','1202603441','John Wick','1985-06-30','Masculino',NULL,NULL,NULL,'hero57@geekchurch.com',NULL,'11922133477','2021-10-29',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('442','1','1202603442','James Bond','1947-11-16','Masculino',NULL,NULL,NULL,'hero58@geekchurch.com',NULL,'11967740779','2017-11-25',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('443','1','1202603443','Indiana Jones','2002-11-01','Masculino',NULL,NULL,NULL,'hero59@geekchurch.com',NULL,'11971658635','2019-01-21',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('444','1','1202603444','Lara Croft','1974-01-31','Feminino',NULL,NULL,NULL,'hero60@geekchurch.com',NULL,'11953690014','2017-10-04',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('445','1','1202603445','Legolas','1949-07-03','Masculino',NULL,NULL,NULL,'hero61@geekchurch.com',NULL,'11940418144','2022-08-01',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('446','1','1202603446','Galadriel','1946-08-18','Feminino',NULL,NULL,NULL,'hero62@geekchurch.com',NULL,'11993821354','2023-10-14',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('447','1','1202603447','Aragorn','1984-06-03','Masculino',NULL,NULL,NULL,'hero63@geekchurch.com',NULL,'11944418624','2019-10-10',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('448','1','1202603448','Frodo Bolseiro','2018-08-28','Masculino',NULL,NULL,NULL,'hero64@geekchurch.com',NULL,'11950313567','2026-02-11',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('449','1','1202603449','Eowyn','1956-03-06','Feminino',NULL,NULL,NULL,'hero65@geekchurch.com',NULL,'11955782718','2022-01-09',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('450','1','1202603450','Arwen','1969-08-13','Feminino',NULL,NULL,NULL,'hero66@geekchurch.com',NULL,'11964151350','2023-04-20',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('451','1','1202603451','Tanjiro Kamado','1961-05-26','Masculino',NULL,NULL,NULL,'hero67@geekchurch.com',NULL,'11945974777','2025-11-20',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('452','1','1202603452','Nezuko Kamado','1930-08-15','Feminino',NULL,NULL,NULL,'hero68@geekchurch.com',NULL,'11975895887','2018-08-08',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('453','1','1202603453','Zenitsu Agatsuma','1965-07-08','Masculino',NULL,NULL,NULL,'hero69@geekchurch.com',NULL,'11980352181','2025-07-16',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('454','1','1202603454','Inosuke Hashibira','1927-11-18','Masculino',NULL,NULL,NULL,'hero70@geekchurch.com',NULL,'11978985769','2017-09-19',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('455','1','1202603455','Saitama','1931-11-25','Masculino',NULL,NULL,NULL,'hero71@geekchurch.com',NULL,'11929352823','2024-01-25',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('456','1','1202603456','Tatsumaki','1983-08-20','Feminino',NULL,NULL,NULL,'hero72@geekchurch.com',NULL,'11955610286','2023-10-17',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('457','1','1202603457','Edward Elric','1957-08-31','Masculino',NULL,NULL,NULL,'hero73@geekchurch.com',NULL,'11976296876','2020-03-05',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('458','1','1202603458','Winry Rockbell','1945-11-14','Feminino',NULL,NULL,NULL,'hero74@geekchurch.com',NULL,'11932272553','2018-03-19',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('459','1','1202603459','Roy Mustang','2000-04-07','Masculino',NULL,NULL,NULL,'hero75@geekchurch.com',NULL,'11991788785','2018-08-27',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('460','1','1202603460','Riza Hawkeye','2020-06-24','Feminino',NULL,NULL,NULL,'hero76@geekchurch.com',NULL,'11911744136',NULL,NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('461','1','1202603461','Ichigo Kurosaki','1971-02-28','Masculino',NULL,NULL,NULL,'hero77@geekchurch.com',NULL,'11911233811','2022-03-25',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('462','1','1202603462','Rukia Kuchiki','1931-10-28','Feminino',NULL,NULL,NULL,'hero78@geekchurch.com',NULL,'11961613349','2026-03-02',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('463','1','1202603463','Orihime Inoue','1996-01-27','Feminino',NULL,NULL,NULL,'hero79@geekchurch.com',NULL,'11955755241','2019-12-11',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('464','1','1202603464','Sosuke Aizen','1965-06-15','Masculino',NULL,NULL,NULL,'hero80@geekchurch.com',NULL,'11926859064','2025-04-11',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('465','1','1202603465','Light Yagami','1936-05-11','Masculino',NULL,NULL,NULL,'hero81@geekchurch.com',NULL,'11933333746','2020-07-04',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('466','1','1202603466','Misa Amano','2018-05-07','Feminino',NULL,NULL,NULL,'hero82@geekchurch.com',NULL,'11971505175','2024-06-08',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('467','1','1202603467','L Lawliet','1942-12-30','Masculino',NULL,NULL,NULL,'hero83@geekchurch.com',NULL,'11970403070','2017-11-25',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('468','1','1202603468','Mikasa Ackerman','2011-08-24','Feminino',NULL,NULL,NULL,'hero84@geekchurch.com',NULL,'11931606109','2018-08-21',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('469','1','1202603469','Eren Yeager','2019-02-05','Masculino',NULL,NULL,NULL,'hero85@geekchurch.com',NULL,'11917606013','2024-02-25',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('470','1','1202603470','Levi Ackerman','1949-01-25','Masculino',NULL,NULL,NULL,'hero86@geekchurch.com',NULL,'11935116295','2025-08-28',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('471','1','1202603471','Annie Leonhart','1982-06-17','Feminino',NULL,NULL,NULL,'hero87@geekchurch.com',NULL,'11913941459','2017-04-20',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('472','1','1202603472','David Bowie','1993-09-02','Masculino',NULL,NULL,NULL,'hero88@geekchurch.com',NULL,'11997176896','2017-08-29',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('473','1','1202603473','Kurt Cobain','1989-01-29','Masculino',NULL,NULL,NULL,'hero89@geekchurch.com',NULL,'11937732524','2022-01-27',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('474','1','1202603474','Amy Winehouse','2011-07-17','Feminino',NULL,NULL,NULL,'hero90@geekchurch.com',NULL,'11954825939','2025-10-06',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('475','1','1202603475','Janis Joplin','1953-10-08','Feminino',NULL,NULL,NULL,'hero91@geekchurch.com',NULL,'11957297128','2021-11-29',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('476','1','1202603476','Elvis Presley','1969-02-07','Masculino',NULL,NULL,NULL,'hero92@geekchurch.com',NULL,'11963470074','2023-10-31',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('477','1','1202603477','Axl Rose','1985-07-15','Masculino',NULL,NULL,NULL,'hero93@geekchurch.com',NULL,'11940583665','2021-07-25',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('478','1','1202603478','Dua Lipa','1995-09-22','Feminino',NULL,NULL,NULL,'hero94@geekchurch.com',NULL,'11923001361','2018-03-31',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('479','1','1202603479','Rihanna','1971-04-26','Feminino',NULL,NULL,NULL,'hero95@geekchurch.com',NULL,'11944350124','2023-10-14',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('480','1','1202603480','Taylor Swift','2019-01-08','Feminino',NULL,NULL,NULL,'hero96@geekchurch.com',NULL,'11966973387',NULL,NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('481','1','1202603481','Bruno Mars','1943-07-13','Masculino',NULL,NULL,NULL,'hero97@geekchurch.com',NULL,'11940417807','2024-05-23',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('482','1','1202603482','Ed Sheeran','1937-04-21','Masculino',NULL,NULL,NULL,'hero98@geekchurch.com',NULL,'11995143301','2025-11-16',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('483','1','1202603483','Billie Eilish','1993-05-18','Feminino',NULL,NULL,NULL,'hero99@geekchurch.com',NULL,'11960183522','2018-04-20',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);
INSERT INTO membros VALUES('484','1','1202603484','Adele','1997-09-13','Feminino',NULL,NULL,NULL,'hero100@geekchurch.com',NULL,'11915812820','2021-08-21',NULL,'Ativo','2026-03-20 17:12:02','0',NULL,NULL);


CREATE TABLE `membros_cargos_vinculo` (
  `vinculo_id` int(11) NOT NULL AUTO_INCREMENT,
  `vinculo_membro_id` int(11) NOT NULL,
  `vinculo_cargo_id` int(11) NOT NULL,
  `vinculo_data_atribuicao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`vinculo_id`),
  KEY `vinculo_membro_id` (`vinculo_membro_id`),
  KEY `vinculo_cargo_id` (`vinculo_cargo_id`),
  CONSTRAINT `fk_cargo` FOREIGN KEY (`vinculo_cargo_id`) REFERENCES `cargos` (`cargo_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_membro` FOREIGN KEY (`vinculo_membro_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO membros_cargos_vinculo VALUES('46','1','7','2026-03-20 18:11:56');
INSERT INTO membros_cargos_vinculo VALUES('47','1','22','2026-03-20 18:11:56');
INSERT INTO membros_cargos_vinculo VALUES('55','3','7','2026-03-20 18:26:25');
INSERT INTO membros_cargos_vinculo VALUES('56','474','17','2026-03-20 18:27:48');
INSERT INTO membros_cargos_vinculo VALUES('57','416','14','2026-03-20 18:28:02');
INSERT INTO membros_cargos_vinculo VALUES('59','453','18','2026-03-20 18:31:11');
INSERT INTO membros_cargos_vinculo VALUES('60','461','12','2026-03-20 18:34:24');
INSERT INTO membros_cargos_vinculo VALUES('61','396','13','2026-03-20 18:34:34');
INSERT INTO membros_cargos_vinculo VALUES('63','413','1','2026-03-21 03:56:03');


CREATE TABLE `membros_contatos` (
  `membro_contato_id` int(11) NOT NULL AUTO_INCREMENT,
  `membro_contato_membro_id` int(11) DEFAULT NULL,
  `membro_contato_tipo` varchar(50) DEFAULT NULL,
  `membro_contato_valor` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`membro_contato_id`),
  KEY `membro_contato_membro_id` (`membro_contato_membro_id`),
  CONSTRAINT `membros_contatos_ibfk_1` FOREIGN KEY (`membro_contato_membro_id`) REFERENCES `membros` (`membro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `membros_enderecos` (
  `membro_endereco_id` int(11) NOT NULL AUTO_INCREMENT,
  `membro_endereco_membro_id` int(11) DEFAULT NULL,
  `membro_endereco_igreja_id` int(11) DEFAULT NULL,
  `membro_endereco_rua` varchar(255) DEFAULT NULL,
  `membro_endereco_numero` varchar(20) DEFAULT NULL,
  `membro_endereco_complemento` varchar(100) DEFAULT NULL,
  `membro_endereco_bairro` varchar(100) DEFAULT NULL,
  `membro_endereco_cidade` varchar(100) DEFAULT NULL,
  `membro_endereco_estado` varchar(50) DEFAULT NULL,
  `membro_endereco_cep` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`membro_endereco_id`),
  KEY `membro_endereco_membro_id` (`membro_endereco_membro_id`),
  KEY `membro_endereco_igreja_id` (`membro_endereco_igreja_id`),
  CONSTRAINT `membros_enderecos_ibfk_1` FOREIGN KEY (`membro_endereco_membro_id`) REFERENCES `membros` (`membro_id`),
  CONSTRAINT `membros_enderecos_ibfk_2` FOREIGN KEY (`membro_endereco_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO membros_enderecos VALUES('1','1','1','Rua das Aeromoças, 48 - Casa 2',NULL,NULL,NULL,'Guarulhos','SP','07161725');
INSERT INTO membros_enderecos VALUES('2','3','1','Rua Babaçu',NULL,NULL,NULL,'Guarulhos','SP','07160360');
INSERT INTO membros_enderecos VALUES('3','386','1','Viela Ubirajara',NULL,NULL,NULL,'Guarulhos','SP','07160290');


CREATE TABLE `membros_fotos` (
  `membro_foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `membro_foto_membro_id` int(11) DEFAULT NULL,
  `membro_foto_arquivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`membro_foto_id`),
  KEY `membro_foto_membro_id` (`membro_foto_membro_id`),
  CONSTRAINT `membros_fotos_ibfk_1` FOREIGN KEY (`membro_foto_membro_id`) REFERENCES `membros` (`membro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO membros_fotos VALUES('1','3','perfil_1775249543.jpg');
INSERT INTO membros_fotos VALUES('2','1','perfil_1775249607.jpg');
INSERT INTO membros_fotos VALUES('3','465','perfil_1775249567.jpg');


CREATE TABLE `membros_historico` (
  `membro_historico_id` int(11) NOT NULL AUTO_INCREMENT,
  `membro_historico_membro_id` int(11) DEFAULT NULL,
  `membro_historico_data` datetime DEFAULT NULL,
  `membro_historico_texto` text DEFAULT NULL,
  PRIMARY KEY (`membro_historico_id`),
  KEY `membro_historico_membro_id` (`membro_historico_membro_id`),
  CONSTRAINT `membros_historico_ibfk_1` FOREIGN KEY (`membro_historico_membro_id`) REFERENCES `membros` (`membro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO membros_historico VALUES('1','3','2026-03-19 12:47:00','<ul><li>Teste de Inserção de Registro de Histórico.<br><br><strong>sera que fuciona?</strong></li></ul>');
INSERT INTO membros_historico VALUES('2','3','2026-03-19 13:00:15','<p><strong>Entrada do membro</strong><br><br>Não sabemos o que esta acontecendo.</p>');


CREATE TABLE `membros_responsaveis` (
  `parentesco_id` int(11) NOT NULL AUTO_INCREMENT,
  `parentesco_responsavel_id` int(11) NOT NULL,
  `parentesco_dependente_id` int(11) NOT NULL,
  `parentesco_grau` varchar(50) DEFAULT 'Pai/Mãe',
  PRIMARY KEY (`parentesco_id`),
  KEY `fk_resp_adulto` (`parentesco_responsavel_id`),
  KEY `fk_resp_crianca` (`parentesco_dependente_id`),
  CONSTRAINT `fk_resp_adulto` FOREIGN KEY (`parentesco_responsavel_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_resp_crianca` FOREIGN KEY (`parentesco_dependente_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `patrimonio_bens` (
  `patrimonio_bem_id` int(11) NOT NULL AUTO_INCREMENT,
  `patrimonio_bem_igreja_id` int(11) DEFAULT NULL,
  `patrimonio_bem_codigo` varchar(50) DEFAULT NULL,
  `patrimonio_bem_nome` varchar(150) DEFAULT NULL,
  `patrimonio_bem_descricao` text DEFAULT NULL,
  `patrimonio_bem_data_aquisicao` date DEFAULT NULL,
  `patrimonio_bem_valor` decimal(12,2) DEFAULT NULL,
  `patrimonio_bem_status` enum('ativo','inativo','manutencao','baixado','extraviado','danificado') DEFAULT NULL,
  `patrimonio_bem_local_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`patrimonio_bem_id`),
  UNIQUE KEY `patrimonio_bem_codigo` (`patrimonio_bem_codigo`),
  KEY `patrimonio_bem_igreja_id` (`patrimonio_bem_igreja_id`),
  KEY `fk_patrimonio_bem_local` (`patrimonio_bem_local_id`),
  CONSTRAINT `fk_patrimonio_bem_local` FOREIGN KEY (`patrimonio_bem_local_id`) REFERENCES `patrimonio_locais` (`patrimonio_local_id`) ON DELETE SET NULL,
  CONSTRAINT `patrimonio_bens_ibfk_1` FOREIGN KEY (`patrimonio_bem_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO patrimonio_bens VALUES('5','1','PAT-2026-0001','Banco Madeira - 5 Lugares','Usado a muito tempo.','2026-03-01','500.00','ativo','3');
INSERT INTO patrimonio_bens VALUES('6','1','PAT-2026-0006','Banco Madeira - 5 Lugares','usado a tempo demais mas vamos ver como fica a etiqueta.','2026-03-01','500.00','ativo','4');


CREATE TABLE `patrimonio_documentos` (
  `patrimonio_documento_id` int(11) NOT NULL AUTO_INCREMENT,
  `patrimonio_documento_bem_id` int(11) DEFAULT NULL,
  `patrimonio_documento_arquivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`patrimonio_documento_id`),
  KEY `patrimonio_documento_bem_id` (`patrimonio_documento_bem_id`),
  CONSTRAINT `patrimonio_documentos_ibfk_1` FOREIGN KEY (`patrimonio_documento_bem_id`) REFERENCES `patrimonio_bens` (`patrimonio_bem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO patrimonio_documentos VALUES('2','5','doc_1774195530_1943.pdf');


CREATE TABLE `patrimonio_imagens` (
  `patrimonio_imagem_id` int(11) NOT NULL AUTO_INCREMENT,
  `patrimonio_imagem_bem_id` int(11) DEFAULT NULL,
  `patrimonio_imagem_arquivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`patrimonio_imagem_id`),
  KEY `patrimonio_imagem_bem_id` (`patrimonio_imagem_bem_id`),
  CONSTRAINT `patrimonio_imagens_ibfk_1` FOREIGN KEY (`patrimonio_imagem_bem_id`) REFERENCES `patrimonio_bens` (`patrimonio_bem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO patrimonio_imagens VALUES('9','5','foto_1774191683_5057.png');
INSERT INTO patrimonio_imagens VALUES('10','5','foto_1774191683_1070.jpg');


CREATE TABLE `patrimonio_locais` (
  `patrimonio_local_id` int(11) NOT NULL AUTO_INCREMENT,
  `patrimonio_local_igreja_id` int(11) DEFAULT NULL,
  `patrimonio_local_nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`patrimonio_local_id`),
  KEY `patrimonio_local_igreja_id` (`patrimonio_local_igreja_id`),
  CONSTRAINT `patrimonio_locais_ibfk_1` FOREIGN KEY (`patrimonio_local_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO patrimonio_locais VALUES('3','1','Templo');
INSERT INTO patrimonio_locais VALUES('4','1','Cozinha');


CREATE TABLE `patrimonio_movimentacoes` (
  `patrimonio_movimentacao_id` int(11) NOT NULL AUTO_INCREMENT,
  `patrimonio_movimentacao_patrimonio_bem_id` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_igreja_id` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_tipo` enum('entrada','saida','transferencia','manutencao','baixa') DEFAULT NULL,
  `patrimonio_movimentacao_local_origem` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_local_destino` int(11) DEFAULT NULL,
  `patrimonio_movimentacao_data` datetime DEFAULT NULL,
  `patrimonio_movimentacao_observacao` text DEFAULT NULL,
  PRIMARY KEY (`patrimonio_movimentacao_id`),
  KEY `patrimonio_movimentacao_patrimonio_bem_id` (`patrimonio_movimentacao_patrimonio_bem_id`),
  CONSTRAINT `patrimonio_movimentacoes_ibfk_1` FOREIGN KEY (`patrimonio_movimentacao_patrimonio_bem_id`) REFERENCES `patrimonio_bens` (`patrimonio_bem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO patrimonio_movimentacoes VALUES('5','5','1','entrada',NULL,'3','2026-03-22 14:52:14','Entrada inicial. Código gerado: PAT-2026-0001');
INSERT INTO patrimonio_movimentacoes VALUES('6','5','1','transferencia','3','4','2026-03-22 14:54:59','agora esta na cozinha');
INSERT INTO patrimonio_movimentacoes VALUES('7','5','1','transferencia','4','3','2026-03-22 14:55:24','Agora esta no templo.');
INSERT INTO patrimonio_movimentacoes VALUES('8','6','1','entrada',NULL,'3','2026-03-22 16:52:44','Entrada inicial. Código gerado: PAT-2026-0006');
INSERT INTO patrimonio_movimentacoes VALUES('9','6','1','transferencia','3','4','2026-03-22 17:55:58','agora ta na cozinha');


CREATE TABLE `perfis` (
  `perfil_id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil_nome` varchar(100) DEFAULT NULL,
  `perfil_descricao` text DEFAULT NULL,
  `perfil_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`perfil_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO perfis VALUES('1','Admin','Acesso total ao sistema e gestão de usuários','ativo');
INSERT INTO perfis VALUES('2','Tesoureiro','Gestão financeira, lançamentos e relatórios','ativo');
INSERT INTO perfis VALUES('3','Secretario','Gestão de membros, certificados e documentos','ativo');
INSERT INTO perfis VALUES('4','Professor','Acesso ao módulo de Escola Dominical e Chamadas','ativo');


CREATE TABLE `sociedades` (
  `sociedade_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sociedade_layout_config` longtext DEFAULT NULL,
  PRIMARY KEY (`sociedade_id`),
  KEY `sociedade_igreja_id` (`sociedade_igreja_id`),
  KEY `fk_sociedade_lider` (`sociedade_lider`),
  CONSTRAINT `fk_sociedade_lider` FOREIGN KEY (`sociedade_lider`) REFERENCES `membros` (`membro_id`) ON DELETE SET NULL,
  CONSTRAINT `sociedades_ibfk_1` FOREIGN KEY (`sociedade_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO sociedades VALUES('1','1','UCP - União de Crianças Presbiterianas','Ambos','0','12','Infantil','Ativo','416',NULL,'1/sociedades/1/logo/logo_1774209497.png',NULL,NULL);
INSERT INTO sociedades VALUES('2','1','UPA - União Presbiteriana de Adolescentes','Ambos','13','18','Adolescentes','Ativo','396',NULL,'1/sociedades/2/logo/logo_1774209528.png',NULL,NULL);
INSERT INTO sociedades VALUES('3','1','UMP - União de Mocidade Presbiteriana','Ambos','19','35','Jovens','Ativo','461',NULL,'1/sociedades/3/logo/logo_1774209513.png',NULL,NULL);
INSERT INTO sociedades VALUES('4','1','SAF - Sociedade Auxiliadora Feminina','Feminino','18','99','Mulheres','Ativo','474',NULL,'1/sociedades/4/logo/logo_1774209144.png',NULL,NULL);
INSERT INTO sociedades VALUES('5','1','UPH - União Presbiteriana de Homens','Masculino','18','99','Homens','Ativo','453',NULL,'1/sociedades/5/logo/logo_1774209557.png',NULL,'{\"version\":\"5.3.0\",\"objects\":[],\"background\":\"#ffffff\"}');


CREATE TABLE `sociedades_eventos` (
  `sociedade_evento_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `sociedade_evento_criado_em` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sociedade_evento_id`),
  KEY `sociedade_evento_igreja_id` (`sociedade_evento_igreja_id`),
  KEY `sociedade_evento_sociedade_id` (`sociedade_evento_sociedade_id`),
  CONSTRAINT `sociedades_eventos_ibfk_1` FOREIGN KEY (`sociedade_evento_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE,
  CONSTRAINT `sociedades_eventos_ibfk_2` FOREIGN KEY (`sociedade_evento_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO sociedades_eventos VALUES('7','1','5','Evento A - Homens','Evento criado para teste na casa.','Residência de Lucas Nobre Ferreira Martins - Rua das Aeromoças, 48 - Casa 2, Guarulhos - SP',NULL,'2026-03-20 12:30:00','2026-03-20 14:30:00','0.00','Agendado','2026-03-20 11:48:01');
INSERT INTO sociedades_eventos VALUES('8','1','3','Treino de Futebol','Treino dos Jovens no campo da igreja','Igreja Presbiteriana do Jardim Girassol - Rua Alto dos Rodrigues, 385 - Jardim Santa Terezinha, Guarulhos - SP, 07160-350',NULL,'2026-03-29 09:00:00','2026-03-29 11:30:00','15.00','Agendado','2026-03-21 04:57:12');


CREATE TABLE `sociedades_eventos_presencas` (
  `sociedade_presenca_id` int(11) NOT NULL AUTO_INCREMENT,
  `sociedade_presenca_igreja_id` int(11) NOT NULL,
  `sociedade_presenca_sociedade_id` int(11) NOT NULL,
  `sociedade_presenca_evento_id` int(11) NOT NULL,
  `sociedade_presenca_membro_id` int(11) NOT NULL,
  `sociedade_presenca_status` enum('Presente','Faltou','Justificado') DEFAULT 'Presente',
  `sociedade_presenca_observacao` varchar(255) DEFAULT NULL,
  `sociedade_presenca_registrado_em` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sociedade_presenca_id`),
  UNIQUE KEY `idx_presenca_unica` (`sociedade_presenca_evento_id`,`sociedade_presenca_membro_id`),
  KEY `fk_presenca_igreja` (`sociedade_presenca_igreja_id`),
  KEY `fk_presenca_sociedade` (`sociedade_presenca_sociedade_id`),
  KEY `fk_presenca_membro` (`sociedade_presenca_membro_id`),
  CONSTRAINT `fk_presenca_evento` FOREIGN KEY (`sociedade_presenca_evento_id`) REFERENCES `sociedades_eventos` (`sociedade_evento_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_presenca_igreja` FOREIGN KEY (`sociedade_presenca_igreja_id`) REFERENCES `igrejas` (`igreja_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_presenca_membro` FOREIGN KEY (`sociedade_presenca_membro_id`) REFERENCES `membros` (`membro_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_presenca_sociedade` FOREIGN KEY (`sociedade_presenca_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `sociedades_membros` (
  `sociedade_membro_id` int(11) NOT NULL AUTO_INCREMENT,
  `sociedade_membro_igreja_id` int(11) DEFAULT NULL,
  `sociedade_membro_sociedade_id` int(11) DEFAULT NULL,
  `sociedade_membro_membro_id` int(11) DEFAULT NULL,
  `sociedade_membro_funcao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sociedade_membro_id`),
  KEY `sociedade_membro_igreja_id` (`sociedade_membro_igreja_id`),
  KEY `sociedade_membro_sociedade_id` (`sociedade_membro_sociedade_id`),
  KEY `sociedade_membro_membro_id` (`sociedade_membro_membro_id`),
  CONSTRAINT `sociedades_membros_ibfk_1` FOREIGN KEY (`sociedade_membro_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `sociedades_membros_ibfk_2` FOREIGN KEY (`sociedade_membro_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`),
  CONSTRAINT `sociedades_membros_ibfk_3` FOREIGN KEY (`sociedade_membro_membro_id`) REFERENCES `membros` (`membro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO sociedades_membros VALUES('43','1','5','3','Sócio');
INSERT INTO sociedades_membros VALUES('44','1','5','447','Sócio');
INSERT INTO sociedades_membros VALUES('45','1','5','477','Sócio');
INSERT INTO sociedades_membros VALUES('46','1','5','389','Sócio');
INSERT INTO sociedades_membros VALUES('47','1','5','481','Sócio');
INSERT INTO sociedades_membros VALUES('48','1','5','390','Sócio');
INSERT INTO sociedades_membros VALUES('49','1','5','472','Sócio');
INSERT INTO sociedades_membros VALUES('50','1','5','457','Sócio');
INSERT INTO sociedades_membros VALUES('51','1','5','476','Sócio');
INSERT INTO sociedades_membros VALUES('52','1','5','422','Sócio');
INSERT INTO sociedades_membros VALUES('53','1','5','435','Sócio');
INSERT INTO sociedades_membros VALUES('54','1','5','413','Sócio');
INSERT INTO sociedades_membros VALUES('55','1','5','397','Sócio');
INSERT INTO sociedades_membros VALUES('56','1','5','433','Sócio');
INSERT INTO sociedades_membros VALUES('57','1','5','441','Sócio');
INSERT INTO sociedades_membros VALUES('58','1','5','402','Sócio');
INSERT INTO sociedades_membros VALUES('59','1','5','473','Sócio');
INSERT INTO sociedades_membros VALUES('60','1','5','467','Sócio');
INSERT INTO sociedades_membros VALUES('61','1','5','445','Sócio');
INSERT INTO sociedades_membros VALUES('62','1','5','470','Sócio');
INSERT INTO sociedades_membros VALUES('63','1','5','1','Sócio');
INSERT INTO sociedades_membros VALUES('64','1','5','392','Sócio');
INSERT INTO sociedades_membros VALUES('65','1','5','425','Sócio');
INSERT INTO sociedades_membros VALUES('66','1','5','398','Sócio');
INSERT INTO sociedades_membros VALUES('67','1','5','459','Sócio');
INSERT INTO sociedades_membros VALUES('68','1','5','410','Sócio');
INSERT INTO sociedades_membros VALUES('69','1','5','464','Sócio');
INSERT INTO sociedades_membros VALUES('70','1','5','386','Sócio');
INSERT INTO sociedades_membros VALUES('71','1','5','385','Sócio');
INSERT INTO sociedades_membros VALUES('72','1','5','417','Sócio');
INSERT INTO sociedades_membros VALUES('73','1','5','453','Sócio');
INSERT INTO sociedades_membros VALUES('74','1','2','474','Sócio');
INSERT INTO sociedades_membros VALUES('75','1','2','431','Sócio');
INSERT INTO sociedades_membros VALUES('76','1','2','415','Sócio');
INSERT INTO sociedades_membros VALUES('77','1','2','468','Sócio');
INSERT INTO sociedades_membros VALUES('78','1','3','484','Sócio');
INSERT INTO sociedades_membros VALUES('79','1','3','428','Sócio');
INSERT INTO sociedades_membros VALUES('80','1','3','483','Sócio');
INSERT INTO sociedades_membros VALUES('81','1','3','437','Sócio');
INSERT INTO sociedades_membros VALUES('82','1','3','401','Sócio');
INSERT INTO sociedades_membros VALUES('83','1','3','391','Sócio');
INSERT INTO sociedades_membros VALUES('84','1','3','422','Sócio');
INSERT INTO sociedades_membros VALUES('85','1','3','435','Sócio');
INSERT INTO sociedades_membros VALUES('86','1','3','397','Sócio');
INSERT INTO sociedades_membros VALUES('87','1','3','443','Sócio');
INSERT INTO sociedades_membros VALUES('88','1','3','402','Sócio');
INSERT INTO sociedades_membros VALUES('89','1','3','421','Sócio');
INSERT INTO sociedades_membros VALUES('90','1','3','405','Sócio');
INSERT INTO sociedades_membros VALUES('91','1','3','409','Sócio');
INSERT INTO sociedades_membros VALUES('92','1','3','463','Sócio');
INSERT INTO sociedades_membros VALUES('93','1','1','416','Sócio');
INSERT INTO sociedades_membros VALUES('94','1','1','469','Sócio');
INSERT INTO sociedades_membros VALUES('95','1','1','448','Sócio');
INSERT INTO sociedades_membros VALUES('96','1','1','418','Sócio');
INSERT INTO sociedades_membros VALUES('97','1','1','423','Sócio');
INSERT INTO sociedades_membros VALUES('98','1','1','406','Sócio');
INSERT INTO sociedades_membros VALUES('99','1','1','480','Sócio');
INSERT INTO sociedades_membros VALUES('100','1','4','484','Sócio');
INSERT INTO sociedades_membros VALUES('101','1','4','471','Sócio');
INSERT INTO sociedades_membros VALUES('102','1','4','450','Sócio');
INSERT INTO sociedades_membros VALUES('103','1','4','403','Sócio');
INSERT INTO sociedades_membros VALUES('104','1','4','428','Sócio');
INSERT INTO sociedades_membros VALUES('105','1','4','424','Sócio');
INSERT INTO sociedades_membros VALUES('106','1','4','483','Sócio');
INSERT INTO sociedades_membros VALUES('107','1','4','415','Sócio');
INSERT INTO sociedades_membros VALUES('108','1','4','437','Sócio');
INSERT INTO sociedades_membros VALUES('109','1','4','401','Sócio');
INSERT INTO sociedades_membros VALUES('110','1','4','391','Sócio');
INSERT INTO sociedades_membros VALUES('111','1','4','478','Sócio');
INSERT INTO sociedades_membros VALUES('112','1','4','419','Sócio');
INSERT INTO sociedades_membros VALUES('113','1','4','399','Sócio');
INSERT INTO sociedades_membros VALUES('114','1','4','434','Sócio');
INSERT INTO sociedades_membros VALUES('115','1','4','449','Sócio');
INSERT INTO sociedades_membros VALUES('116','1','4','446','Sócio');
INSERT INTO sociedades_membros VALUES('117','1','4','396','Sócio');
INSERT INTO sociedades_membros VALUES('118','1','4','412','Sócio');
INSERT INTO sociedades_membros VALUES('119','1','4','475','Sócio');
INSERT INTO sociedades_membros VALUES('120','1','4','439','Sócio');
INSERT INTO sociedades_membros VALUES('121','1','4','444','Sócio');
INSERT INTO sociedades_membros VALUES('122','1','4','394','Sócio');
INSERT INTO sociedades_membros VALUES('123','1','4','407','Sócio');
INSERT INTO sociedades_membros VALUES('124','1','4','387','Sócio');
INSERT INTO sociedades_membros VALUES('125','1','4','452','Sócio');
INSERT INTO sociedades_membros VALUES('126','1','4','408','Sócio');
INSERT INTO sociedades_membros VALUES('127','1','4','411','Sócio');
INSERT INTO sociedades_membros VALUES('128','1','4','400','Sócio');
INSERT INTO sociedades_membros VALUES('129','1','4','430','Sócio');
INSERT INTO sociedades_membros VALUES('130','1','4','456','Sócio');
INSERT INTO sociedades_membros VALUES('131','1','4','388','Sócio');
INSERT INTO sociedades_membros VALUES('132','1','4','458','Sócio');
INSERT INTO sociedades_membros VALUES('133','1','4','436','Sócio');


CREATE TABLE `sociedades_orcamentos` (
  `sociedade_orcamento_id` int(11) NOT NULL AUTO_INCREMENT,
  `sociedade_orcamento_igreja_id` int(11) DEFAULT NULL,
  `sociedade_orcamento_sociedade_id` int(11) DEFAULT NULL,
  `sociedade_orcamento_valor` decimal(12,2) DEFAULT NULL,
  `sociedade_orcamento_ano` year(4) DEFAULT NULL,
  PRIMARY KEY (`sociedade_orcamento_id`),
  KEY `sociedade_orcamento_igreja_id` (`sociedade_orcamento_igreja_id`),
  KEY `sociedade_orcamento_sociedade_id` (`sociedade_orcamento_sociedade_id`),
  CONSTRAINT `sociedades_orcamentos_ibfk_1` FOREIGN KEY (`sociedade_orcamento_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `sociedades_orcamentos_ibfk_2` FOREIGN KEY (`sociedade_orcamento_sociedade_id`) REFERENCES `sociedades` (`sociedade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_igreja_id` int(11) DEFAULT NULL,
  `usuario_nome` varchar(150) DEFAULT NULL,
  `usuario_email` varchar(150) DEFAULT NULL,
  `usuario_senha` varchar(255) DEFAULT NULL,
  `usuario_status` varchar(20) DEFAULT NULL,
  `usuario_data_criacao` datetime DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `usuario_igreja_id` (`usuario_igreja_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`usuario_igreja_id`) REFERENCES `igrejas` (`igreja_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuarios VALUES('1','1','Admin','admin@ekklesia.com','$2y$10$ZWnKwTGMcAPhF6thFhxxEub7h1F5Ff7UoVk.g3oH9igcHfsnLmcwe','ativo',NULL);


CREATE TABLE `usuarios_perfis` (
  `usuario_perfil_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_perfil_igreja_id` int(11) DEFAULT NULL,
  `usuario_perfil_usuario_id` int(11) DEFAULT NULL,
  `usuario_perfil_perfil_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`usuario_perfil_id`),
  KEY `usuario_perfil_igreja_id` (`usuario_perfil_igreja_id`),
  KEY `usuario_perfil_usuario_id` (`usuario_perfil_usuario_id`),
  KEY `usuario_perfil_perfil_id` (`usuario_perfil_perfil_id`),
  CONSTRAINT `usuarios_perfis_ibfk_1` FOREIGN KEY (`usuario_perfil_igreja_id`) REFERENCES `igrejas` (`igreja_id`),
  CONSTRAINT `usuarios_perfis_ibfk_2` FOREIGN KEY (`usuario_perfil_usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  CONSTRAINT `usuarios_perfis_ibfk_3` FOREIGN KEY (`usuario_perfil_perfil_id`) REFERENCES `perfis` (`perfil_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuarios_perfis VALUES('1','1','1','1');
INSERT INTO usuarios_perfis VALUES('3','1','3','1');

SET FOREIGN_KEY_CHECKS=1;