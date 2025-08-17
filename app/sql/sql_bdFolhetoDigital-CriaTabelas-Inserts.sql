-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para bdfolhetodigital
DROP DATABASE IF EXISTS `bdfolhetodigital`;
CREATE DATABASE IF NOT EXISTS `bdfolhetodigital` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `bdfolhetodigital`;

-- Copiando estrutura para tabela bdfolhetodigital.tbcifras
DROP TABLE IF EXISTS `tbcifras`;
CREATE TABLE IF NOT EXISTS `tbcifras` (
  `idCifra` int(11) NOT NULL AUTO_INCREMENT,
  `idMusica` int(11) NOT NULL,
  `TomMusica` varchar(5) NOT NULL,
  `DescMusicaCifra` text DEFAULT NULL,
  `LinkSiteCifra` text DEFAULT NULL,
  PRIMARY KEY (`idCifra`,`idMusica`,`TomMusica`) USING BTREE,
  KEY `idMusica` (`idMusica`),
  CONSTRAINT `tbcifras_ibfk_1` FOREIGN KEY (`idMusica`) REFERENCES `tbmusica` (`idMusica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbcifras: ~7 rows (aproximadamente)
DELETE FROM `tbcifras`;
INSERT INTO `tbcifras` (`idCifra`, `idMusica`, `TomMusica`, `DescMusicaCifra`, `LinkSiteCifra`) VALUES
	(1, 4, 'D', 'Intro:  D  G  A  D\r\n\r\n[nota]                   D               G                  A                   D[/nota]\r\n1. Senhor tende piedade e perdoai a nossa culpa.\r\n                 G                  A7    D   D7     \r\n    E perdoai a nossa cul...pa, \r\n\r\n[nota]                           G                      A  A7    D [/nota]\r\n[negrito]    Porque nós somos vosso Po.........vo, [/negrito]\r\n[nota]Bm                   Em                    A    D[/nota]\r\n[negrito]    Que vem pedir vosso perdã.....ão.[/negrito]\r\n\r\n\r\n                  D              G                  A                   D\r\n2. Cristo tende piedade e perdoai a nossa culpa.\r\n                 G                  A7    D   D7     \r\n    E perdoai a nossa cul...pa, \r\n\r\n                   D               G                  A                   D\r\n3. Senhor tende piedade e perdoai a nossa culpa.\r\n                 G                  A7    D   D7     \r\n    E perdoai a nossa cul...pa,', ''),
	(2, 59, 'G', 'SALMO 8 Faixa 44 – Vol. 1\r\n\r\n    G           B7           C      Cm\r\nÓ SENHOR NOSSO DEUS, COMO É GRANDE\r\n      G        D         G\r\nVOSSO NOME POR TODO O UNIVERSO!\r\n\r\nEm                              Bm      C\r\nContemplando estes céus que plasmastes\r\n                            G     Em\r\nE formastes com dedos de artista;\r\n                          Bm      C\r\nVendo a lua e estrelas brilhantes,\r\n                              G     D\r\nPerguntamos: “Senhor, que é o homem,\r\n                       G        Am\r\nPara dele assim vos lembrardes\r\n                  D       G\r\nE o tratardes com tanto carinho?”\r\n\r\n\r\nEm                       Bm     C\r\nPouco abaixo de Deus o fizestes,\r\n                             G   Em\r\nCoroando-o de glória e esplendor;\r\n                           G    Am\r\nVós lhe destes poder sobre tudo,\r\n                 D         G\r\nVossas obras aos pés lhe pusestes:\r\n\r\n\r\nEm                        Bm     C\r\nAs ovelhas, os bois, os rebanhos,\r\n                          G     D\r\nTodo o gado e as feras da mata;\r\n                         G     Am\r\nPassarinhos e peixes dos mares,\r\n                D        G\r\nTodo ser que se move nas águas.', NULL),
	(3, 60, 'F', 'F  C/G       Dm    Am      Bb  C/G   F   C/G    \r\nA..amém   A..amém       a, a, a, amém     \r\nF  C/G       Dm    Am      Bb  C/G   F   \r\nA..amém   A..amém       a, a, a, amém', NULL),
	(4, 14, 'E', '[nota]Dm                  Dm                  D7[/nota]\r\nA...a...a..amém, A...a...a..amém, \r\n[nota]Gm   C      F    Dm    Gm  C   Dm D7 [/nota]\r\nA...a...a,     A...a...a,     A...a...a...amém, \r\n[nota]Gm   C      F    Dm    Gm  C   Dm D7 [/nota]\r\nA...a...a,     A...a...a,     A...a...a...amém, \r\n[nota]C   Dm   C   Dm [/nota]\r\nA...mém, A...mém', 'https://www.youtube.com/watch?v=291SzD3ef9k&list=RD291SzD3ef9k&start_radio=1'),
	(5, 28, 'D', '[nota]D                  Bm          Em   A7[/nota]\r\nTu anseias, eu bem sei, por salva--ção\r\n[nota]       Em         A7      D[/nota]\r\nTens desejo de banir a escuridão\r\n[nota]       D             D7         G     Gm[/nota]\r\nAbre, pois, de par em par, teu coração\r\n[nota]   D      Bm      Em    A7   D  A7[/nota]\r\nE deixa a luz do céu    entrar\r\n\r\n[nota]D                     A7[/nota]\r\n[refrão]Deixa a luz do céu entrar[/refrão]\r\n[nota]Em             A7     D[/nota]\r\n[refrão]Deixa a luz do céu entrar[/refrão]\r\n[nota]D             D7           G     Gm[/nota]\r\n[refrão]Abre bem as portas do teu coração[/refrão]\r\n[nota]  D       Bm      Em    A7   D  A7[/nota]\r\n[refrão]E deixa a luz do céu    entrar[/refrão]\r\n\r\n[nota]D                       Bm      Em    A7[/nota]\r\nCristo, a luz do céu, em ti quer habi---tar\r\n[nota]        Em            A7       D[/nota]\r\nPara as trevas do pecado dissipar\r\n[nota]     D              D7   G    Gm[/nota]\r\nTeu caminho e coração iluminar\r\n[nota]   D       Bm     Em    A7   D  A7[/nota]\r\nE deixa a luz do céu    entrar\r\n\r\n[nota]D                    Bm    Em    A7[/nota]\r\nQue alegria andar ao brilho dessa luz\r\n[nota]       Em            A7             D[/nota]\r\nVida eterna e paz no cora---ção produz\r\n[nota]         D         D7       G        Gm[/nota]\r\nOh! Aceita agora o salva---dor Jesus\r\n[nota]   D       Bm     Em    A7   D  A7[/nota]\r\nE deixa a luz do céu    entrar\r\n', 'https://www.cifraclub.com.br/padre-marcelo-rossi/deixa-a-luz-do-ceu-entrar/'),
	(6, 19, 'D', '  D                           A          Bm  A D\r\nSenhor, tende piedade dos corações arrependidos\r\n\r\n[Refrão] \r\n\r\n                  A           Bm       F#m\r\nTende piedade de nós tende piedade de nós\r\n   G              Em         A         D  A \r\nTende piedade de nós tende piedade de nós\r\n   D                           A          Bm  A D\r\nJesus, tende piedade dos pecadores, tão humilhdos!\r\n\r\n D                           A          Bm  A D\r\nSenhor, tende piedade intercedendo por nós ao pai!', 'https://www.bananacifras.com/simplificada/c/com-catolica-sta-edwiges/senhor-tende-piedade-dos-coracoes'),
	(7, 19, 'C', '  C                           G          Am  G C\r\nSenhor, tende piedade dos corações arrependidos\r\n\r\n[Refrão] \r\n\r\n                  G           Am       Em\r\nTende piedade de nós tende piedade de nós\r\n   F              Dm         G        C  G\r\nTende piedade de nós tende piedade de nós\r\n   C                           G          Am  G C\r\nJesus, tende piedade dos pecadores, tão humilhdos!\r\n\r\n C                           G          Am  G C\r\nSenhor, tende piedade intercedendo por nós ao pai!', 'https://www.bananacifras.com/simplificada/c/com-catolica-sta-edwiges/senhor-tende-piedade-dos-coracoes');

-- Copiando estrutura para tabela bdfolhetodigital.tbdatacomemorativa
DROP TABLE IF EXISTS `tbdatacomemorativa`;
CREATE TABLE IF NOT EXISTS `tbdatacomemorativa` (
  `idDataComemorativa` int(11) NOT NULL AUTO_INCREMENT,
  `DescComemoracao` varchar(200) NOT NULL,
  `QuandoAcontece` varchar(200) NOT NULL,
  `MesDia` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idDataComemorativa`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbdatacomemorativa: ~5 rows (aproximadamente)
DELETE FROM `tbdatacomemorativa`;
INSERT INTO `tbdatacomemorativa` (`idDataComemorativa`, `DescComemoracao`, `QuandoAcontece`, `MesDia`) VALUES
	(1, 'Normal', 'O ano todo', '01/01'),
	(2, 'Festa de Nossa Senhora dos Remédios', 'Outubro - Dia 12', '10/12'),
	(3, 'Festa de Nossa Senhora das Mercês', 'Setembro - Dia 24', '09/24'),
	(4, 'Missa de Natal', 'Dezembro - dia 25', '12/25');

-- Copiando estrutura para tabela bdfolhetodigital.tbigreja
DROP TABLE IF EXISTS `tbigreja`;
CREATE TABLE IF NOT EXISTS `tbigreja` (
  `idIgreja` int(11) NOT NULL AUTO_INCREMENT,
  `NomeIgreja` varchar(250) NOT NULL,
  `Endereco` varchar(250) DEFAULT NULL,
  `Telefone` varchar(30) DEFAULT NULL,
  `Tipo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idIgreja`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbigreja: ~4 rows (aproximadamente)
DELETE FROM `tbigreja`;
INSERT INTO `tbigreja` (`idIgreja`, `NomeIgreja`, `Endereco`, `Telefone`, `Tipo`) VALUES
	(1, 'Nossa Senhora das Mercês', 'COABE', '', 'Paróquia'),
	(2, 'Santuário Nossa Senhora dos Remédios', 'Rua Cunha couto', '', 'Santuário'),
	(6, 'Santo Antônio', 'xxxxx', '', 'Paróquia'),
	(9, 'teste', 'tsste', 'te', 'Paróquia');

-- Copiando estrutura para tabela bdfolhetodigital.tbigrejasacerdote
DROP TABLE IF EXISTS `tbigrejasacerdote`;
CREATE TABLE IF NOT EXISTS `tbigrejasacerdote` (
  `idIgrejaSacerdote` int(11) NOT NULL AUTO_INCREMENT,
  `idIgreja` int(11) NOT NULL,
  `idSacerdote` int(11) NOT NULL,
  `DataInicio` date DEFAULT NULL,
  `DataFim` date DEFAULT NULL,
  `Status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idIgrejaSacerdote`),
  KEY `idIgreja` (`idIgreja`),
  KEY `idSacerdotes` (`idSacerdote`) USING BTREE,
  CONSTRAINT `tbIgrejaSacerdote_ibfk_1` FOREIGN KEY (`idIgreja`) REFERENCES `tbigreja` (`idIgreja`) ON DELETE CASCADE,
  CONSTRAINT `tbIgrejaSacerdote_ibfk_2` FOREIGN KEY (`idSacerdote`) REFERENCES `tbsacerdotes` (`idSacerdote`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbigrejasacerdote: ~2 rows (aproximadamente)
DELETE FROM `tbigrejasacerdote`;
INSERT INTO `tbigrejasacerdote` (`idIgrejaSacerdote`, `idIgreja`, `idSacerdote`, `DataInicio`, `DataFim`, `Status`) VALUES
	(1, 1, 2, '1970-01-01', '1980-01-01', 0),
	(3, 6, 1, '2024-01-01', NULL, 1);

-- Copiando estrutura para tabela bdfolhetodigital.tbmissa
DROP TABLE IF EXISTS `tbmissa`;
CREATE TABLE IF NOT EXISTS `tbmissa` (
  `idMissa` int(11) NOT NULL AUTO_INCREMENT,
  `idDataComemorativa` int(11) DEFAULT NULL,
  `idIgreja` int(11) DEFAULT NULL,
  `idTpLiturgico` int(11) DEFAULT NULL,
  `DataMissa` date NOT NULL,
  `AnoMissa` varchar(4) DEFAULT NULL,
  `TituloMissa` varchar(200) DEFAULT NULL,
  `Status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idMissa`),
  KEY `idDataComemorativa` (`idDataComemorativa`),
  KEY `tbmissa_ibfk_2` (`idIgreja`),
  KEY `tbMissa_ibfk_3` (`idTpLiturgico`),
  CONSTRAINT `tbMissa_ibfk_3` FOREIGN KEY (`idTpLiturgico`) REFERENCES `tbtpliturgico` (`idTpLiturgico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `tbmissa_ibfk_1` FOREIGN KEY (`idDataComemorativa`) REFERENCES `tbdatacomemorativa` (`idDataComemorativa`) ON DELETE SET NULL,
  CONSTRAINT `tbmissa_ibfk_2` FOREIGN KEY (`idIgreja`) REFERENCES `tbigreja` (`idIgreja`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbmissa: ~2 rows (aproximadamente)
DELETE FROM `tbmissa`;
INSERT INTO `tbmissa` (`idMissa`, `idDataComemorativa`, `idIgreja`, `idTpLiturgico`, `DataMissa`, `AnoMissa`, `TituloMissa`, `Status`) VALUES
	(2, 1, 1, 1, '2025-07-26', 'C', '5º domingo', 0),
	(3, 1, 1, NULL, '2025-07-19', 'C', 'Sábado', 0),
	(4, 1, 1, NULL, '2025-08-02', 'C', 'Domingo', 1);

-- Copiando estrutura para tabela bdfolhetodigital.tbmissamusicas
DROP TABLE IF EXISTS `tbmissamusicas`;
CREATE TABLE IF NOT EXISTS `tbmissamusicas` (
  `idMusica` int(11) DEFAULT NULL,
  `idMissa` int(11) DEFAULT NULL,
  `idMusicaMomentoMissa` int(11) DEFAULT NULL,
  KEY `idMissa` (`idMissa`),
  KEY `idMusica` (`idMusica`,`idMusicaMomentoMissa`),
  CONSTRAINT `FK_tbmusicasmissa_tbmissa` FOREIGN KEY (`idMissa`) REFERENCES `tbmissa` (`idMissa`) ON DELETE CASCADE,
  CONSTRAINT `FK_tbmusicasmissa_tbmusicamomentomissa` FOREIGN KEY (`idMusica`, `idMusicaMomentoMissa`) REFERENCES `tbmusicamomentomissa` (`idMusica`, `idMomento`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbmissamusicas: ~17 rows (aproximadamente)
DELETE FROM `tbmissamusicas`;
INSERT INTO `tbmissamusicas` (`idMusica`, `idMissa`, `idMusicaMomentoMissa`) VALUES
	(37, 2, 1),
	(39, 2, 3),
	(43, 2, 5),
	(11, 2, 6),
	(40, 2, 12),
	(28, 4, 1),
	(2, 4, 2),
	(19, 4, 3),
	(29, 4, 4),
	(21, 4, 5),
	(57, 4, 7),
	(58, 4, 6),
	(13, 4, 8),
	(14, 4, 9),
	(23, 4, 12),
	(22, 4, 18),
	(24, 4, 13);

-- Copiando estrutura para tabela bdfolhetodigital.tbmomentosmissa
DROP TABLE IF EXISTS `tbmomentosmissa`;
CREATE TABLE IF NOT EXISTS `tbmomentosmissa` (
  `idMomento` int(11) NOT NULL AUTO_INCREMENT,
  `DescMomento` varchar(50) DEFAULT NULL,
  `OrdemDeExecucao` char(4) DEFAULT NULL,
  PRIMARY KEY (`idMomento`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbmomentosmissa: ~14 rows (aproximadamente)
DELETE FROM `tbmomentosmissa`;
INSERT INTO `tbmomentosmissa` (`idMomento`, `DescMomento`, `OrdemDeExecucao`) VALUES
	(1, 'Entrada', '01'),
	(2, 'Deus Trino', '02'),
	(3, 'Ato', '03'),
	(4, 'Glória', '04'),
	(5, 'Aclamação', '05'),
	(6, 'Ofertório', '07'),
	(7, 'Salmo', '06'),
	(8, 'Santo', '08'),
	(9, 'Amém', '09'),
	(10, 'Pai Nosso', '10'),
	(11, 'Cordeiro', '11'),
	(12, 'Comunhão', '12'),
	(13, 'Final', '13'),
	(18, 'Pós-Comunhão', '12.1'),
	(19, 'Especiais', '14');

-- Copiando estrutura para tabela bdfolhetodigital.tbmusica
DROP TABLE IF EXISTS `tbmusica`;
CREATE TABLE IF NOT EXISTS `tbmusica` (
  `idMusica` int(11) NOT NULL AUTO_INCREMENT,
  `NomeMusica` varchar(200) DEFAULT NULL,
  `Musica` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`idMusica`),
  UNIQUE KEY `NomeMusica` (`NomeMusica`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbmusica: ~40 rows (aproximadamente)
DELETE FROM `tbmusica`;
INSERT INTO `tbmusica` (`idMusica`, `NomeMusica`, `Musica`) VALUES
	(1, 'Pelas estradas da vida', 'Pelas estradas da vida, \r\nnunca sozinho estás\r\nContigo pelo caminho, \r\nSanta Maria vai\r\n[negrito]\r\nOh vem conosco vem caminhar, \r\nSanta Maria vem\r\nOh vem conosco vem caminhar\r\nSanta Maria vem,\r\n[/negrito]\r\nSe pelo mundo os homens, \r\nsem conhecer-se, vão\r\nNão negues nunca a tua mão, \r\na quem te encontrar\r\n[negrito][refrão][/negrito]\r\n\r\nMesmo que digam os homens,\r\nTu nada podes mudar,\r\nLuta por um mundo novo\r\nDe unidade e paz\r\n[negrito][refrão][/negrito]\r\n\r\nSe parecer tua vida\r\nInútil caminhar\r\nLembra que abres caminho\r\nOutros te seguirão\r\n[negrito][refrão][/negrito]'),
	(2, 'Deus Trino', 'Em nome do Pai\r\nEm nome do Filho\r\nem nome do Espírito Santo\r\nEstamos aqui\r\n\r\nPara louvar e agradecer, bendizer e adorar\r\nEstamos aqui Senhor, a seu dispor\r\n\r\nPara louvar e agradecer, bendizer e adorar\r\nTe aclamar, Deus Trino de amor'),
	(3, 'Coração Contrito', 'Senhor, que viestes salvar \r\nos corações arrependidos.\r\nTende piedade de nós (2x)\r\n[negrito]\r\nMisericórdia,\r\nMisericórdia Senhor\r\n[/negrito]\r\nOh! Cristo, que viestes chamar \r\nos pecadores de coração contrito\r\nTende piedade de nós (2x)\r\n[negrito]\r\nMisericórdia,\r\nOh! Cristo misericórdia.\r\n[/negrito]\r\nSenhor, que intercedeis por nós \r\njunto do Pai das misericórdias\r\nTende piedade de nós (2x)\r\n[negrito]\r\nMisericórdia,\r\nMisericórdia Senhor.\r\n[/negrito]'),
	(4, 'Senhor, tende piedade e perdoai a nossa culpa', '1.Senhor, tende piedade e perdoai a nossa culpa.\r\n[negrito]\r\nE perdoai a nossa culpa, \r\nporque nós somos vosso Povo, \r\nque vem pedir vosso perdão.\r\n[/negrito]\r\n2. Cristo, tende piedade e perdoai a nossa culpa.\r\n\r\n3. Senhor, tende piedade e perdoai a nossa culpa.'),
	(5, 'Kyrie Eleison', 'Senhor, que viestes salvar\r\nOs corações arrependidos\r\n[negrito]\r\nKyrie eleison, eleison, eleison\r\nKyrie eleison, eleison, eleison\r\n[/negrito]\r\nÓ, Cristo, que viestes chamar\r\nOs pecadores humilhados\r\n[negrito]\r\nChriste eleison, eleison, eleison\r\nChriste eleison, eleison, eleison\r\n[/negrito]\r\nSenhor, que intercedeis por nós\r\nJunto a Deus Pai que nos perdoa\r\n[negrito]\r\nKyrie eleison, eleison, eleison\r\nKyrie eleison, eleison, eleison\r\n[/negrito]'),
	(6, 'Senhor Nós Estamos Aqui', 'Senhor, nós estamos aqui\r\nJunto à mesa da celebração\r\nSimplesmente atraídos por vós\r\nDesejamos formar comunhão!\r\n[negrito]\r\nIgualdade, fraternidade, \r\nNesta mesa nos ensinais\r\nAs lições que melhor educam, \r\nNa Eucaristia é que nos dais!\r\nAs lições que melhor educam, \r\nNa Eucaristia é que nos dais!\r\n[/negrito]\r\nEste encontro convosco, senhor\r\nIncentiva a justiça e a paz\r\nNos inquieta e convida a sentir\r\nOs apelos que o pobre nos faz.   \r\n[negrito][refrão][/negrito]'),
	(8, 'Glória A Deus Nas Alturas (Exaltemos ao senhor)', 'Glória a Deus nas alturas\r\nE paz na terra \r\nAos homens por ele amados\r\nAos homens por ele amados!\r\n\r\nSenhor Deus rei do céu\r\nDeus pai todo poderoso\r\nNós vos louvamos\r\nNós vos bendizemos\r\n\r\nNós vos adoramos\r\nNós vos glorificamos\r\nNós vos damos graças\r\nPor vossa imensa glória\r\n\r\nSenhor Jesus Cristo\r\nFilho Unigênito\r\nSenhor Deus cordeiro de Deus\r\nFilho de Deus Pai\r\n\r\nVós que tirais o pecado do mundo\r\nTende piedade de nós\r\nVós que tirais o pecado do mundo\r\nAcolhei a nossa súplica\r\n\r\nVós que estais a direita do Pai\r\nTende piedade de nós,	\r\nTende piedade de nós\r\nTende piedade de nós!\r\n\r\nSó vós sois o Santo\r\nSó vós o Senhor\r\nSó vós o altíssimo\r\nJesus Cristo\r\n\r\nCom o espírito santo\r\nNa glória de Deus Pai\r\nNa glória de Deus Pai\r\nA-a-amém!'),
	(9, 'Salmo 68 (69) - Humildes, Buscai A Deus E Alegrai-Vos:', '[negrito]\r\nHumildes, Buscai A Deus E Alegrai-Vos:\r\nO Vosso Coração Reviverá!\r\n[/negrito]\r\nPor isso elevo para vós minha oração,\r\nNeste tempo favorável, Senhor Deus!\r\nRespondei-me pelo vosso imenso amor,\r\nPela vossa salvação que nunca falha!\r\nSenhor, ouvi-me pois suave é vossa graça,\r\nPonde os olhos sobre mim com grande amor!\r\n\r\nPobre de mim, sou infeliz e sofredor!\r\nQue vosso auxílio me levante, Senhor Deus!\r\nCantando eu louvarei o Vosso Nome\r\nE agradecido exultarei de alegria!\r\n\r\nHumildes, vede isto e alegrai-vos:\r\nO vosso coração reviverá,\r\nSe procurardes o Senhor continuamente!\r\nPois nosso Deus atende à prece dos seus pobres,\r\nE não despreza o clamor de seus cativos.\r\n\r\nSim, Deus virá e salvará Jerusalém,\r\nReconstruindo as cidades de Judá.\r\nA descendência de seus servos há de herdá-las,\r\nE os que amam o santo nome do Senhor\r\nDentro delas fixarão sua morada!'),
	(10, 'Aleluuia! Aleluuia, Alelu.u.uia!  (Arquidiocese Goiania)', '[negrito]Aleluuia! Aleluuia, Alelu.u.uia!   \r\nAleluuia! Aleluuia, Alelu.u.uia!   \r\n[/negrito]'),
	(11, 'Bendito e Louvado Seja', 'Bendito e louvado seja  / O pai, nosso criador\r\nO pão que nós recebemos  / É prova do seu amor\r\nO pão que nós recebemos / Que é prova do seu amor\r\nÉ o fruto de sua terra  / do povo trabalhador\r\nO fruto de sua terra  / do povo trabalhador\r\nNa missa é transformado  / No corpo do salvador\r\n[negrito]\r\nBendito seja Deus \r\nBendito o seu amor\r\nBendito seja Deus, \r\nPai onipotente\r\nNosso criador (2x)\r\n[/negrito]\r\nBendito e louvado seja  /  O pai, nosso criador\r\nO vinho que recebemos  /  É prova do seu amor\r\nO vinho que recebemos  /  É prova do seu amor\r\nÉ o fruto de sua terra  /  Do povo trabalhador\r\nÉ o fruto de sua terra  /  Do povo trabalhador\r\nNa missa é transformado  /  No sangue do salvador\r\n[negrito][refrão][/negrito]'),
	(12, 'Eis Que Sou o Pão da Vida', '[negrito]\r\nEis que sou o Pão da Vida \r\nEis que sou o Pão do céu; \r\nFaço-me vossa com comida, \r\nEu sou mais que leite e mel. \r\n[/negrito]\r\n1. Todo aquele que comer do meu corpo que é doado, \r\nTodo aquele que beber do meu sangue derramado. \r\nE crê nas minhas palavras que são plenas de vida, \r\nNunca mais sentirá fome e nem sede em sua lida. \r\n[negrito][refrão][/negrito]\r\n2. O meu Corpo e meu Sangue são sublimes alimentos, \r\nDo fraco indigente é vigor, do faminto é o sustento. \r\nDo aflito é consolo, do enfermo é a unção, \r\nDo pequeno e excluído, rocha viva e proteção. \r\n[negrito][refrão[/negrito]\r\n3. Eu sou o Caminho, a Vida, Água Viva e a Verdade, \r\nSou a paz e a luz, sou a própria liberdade. \r\nSou a Palavra do Pai que entre vós habitou, \r\nPara que vós habiteis na Trindade onde estou. \r\n[negrito][refrão[/negrito]\r\n4. Eu sou a Palavra Viva que sai da boca de Deus, \r\nSou a lâmpada para guiar vossos passos, irmãos meus. \r\nSou o rio, eu sou a ponte, sou a brisa que afaga, \r\nSou a água, sou a'),
	(13, 'Santo, Santo, Santo', 'Santo, Santo, Santo\r\nSenhor Deus do Universo\r\n\r\nO céu e a Terra o proclamam \r\nProclamam a vossa glória\r\n[negrito]\r\nHosana, Hosana nas alturas\r\nHosana, Hosana nas alturas\r\nHosana, Hosana nas alturas, hosana.\r\n[/negrito]\r\nBendito aquele que vem \r\nem nome do Senhor!'),
	(14, 'Amém 3 (Tempos Liturgicos) A.a.a.a.mém', 'A.a.a.a.mém, A.a.a.a.mém\r\nA...a...a, 	A...a...a, A.a.a.a.mém\r\nA...a...a,	A...a...a, A.a.a.a.mém\r\nA.mém, A.mém'),
	(15, 'Cordeiro de Deus (grupo Santa Cecília)', 'Cordeiro de Deus, \r\nQue tirais o pecado do mundo\r\n[negrito]\r\nTende piedade de nós\r\nTende piedade de nós\r\n[/negrito]\r\nCordeiro de Deus, \r\nQue tirais o pecado do mundo\r\n[negrito]Tende piedade de nós\r\nTende piedade de nós\r\n[/negrito]\r\nCordeiro de Deus, \r\nQue tirais o pecado do mundo\r\n[negrito]Dai-nos a paz,  \r\nDai-nos a vossa paz. \r\n[/negrito]'),
	(16, 'Paz, paz de Cristo!', '[negrito]Paz, paz de Cristo!\r\nPaz, paz que vem do amor\r\nTe desejo, irmão\r\n\r\nPaz que a felicidade\r\nDe ver em você\r\nCristo nosso irmão\r\n[/negrito]\r\nSe algum dia na vida / Você de mim precisar\r\nSaiba que sou seu amigo / Pode comigo contar\r\n\r\nO mundo dá tantas voltas / E a gente vai se encontrar \r\nQuero nas voltas da vida / A sua mão apertar\r\n[negrito][refrão][/negrito]'),
	(18, 'Salmo 14 - Senhor, quem morará em Vossa casa?  (16º DTC – Ano C)', '[negrito]\r\nSenhor, Quem Morará Em Vossa Casa? \r\nSenhor, Quem Morará Em Vossa Casa? \r\n[/negrito] \r\nÉ Aquele Que Caminha Sem Pecado\r\nE Pratica A Justiça Fielmente;\r\nQue Pensa A Verdade No Seu Íntimo\r\nE Não Solta Em Calúnias Sua Língua.\r\n \r\nQue Em Nada Prejudica O Seu Irmão,\r\nNem Cobre De Insultos Seu Vizinho;\r\nQue Não Dá Valor Algum Ao Homem Ímpio,\r\nMas Honra Os Que Respeitam O Senhor.\r\n \r\nNão Empresta O Seu Dinheiro Com Usura,\r\nNem Se Deixa Subornar Contra O Inocente.\r\nJamais Vacilará Quem Vive Assim!\r\nJamais Vacilará Quem Vive Assim!'),
	(19, 'Senhor, tende piedade,  dos corações arrependi...i...dos.', 'Senhor, tende piedade, \r\ndos corações arrependi...i...dos.\r\n[negrito]\r\nTende piedade de nós, Tende piedade de nós.\r\nTende piedade de nós, Tende piedade de nós.\r\n[/negrito]\r\n2. Jesus, tende piedade, \r\ndos pecadores, tão humilha...a...dos! \r\n[negrito][refrão][/negrito]\r\n\r\n3. Senhor, tende piedade, \r\nintercedendo por nós ao Pa...a...i! \r\n[negrito][refrão][/negrito]'),
	(20, 'Buscai Primeiro', 'Buscai primeiro o reino de Deus \r\nE a sua justiça\r\nE tudo mais vos será acrescentado, \r\nAleluia, aleluia\r\n\r\nNem só de pão o Homem viverá, \r\nMas de toda palavra\r\nQue procede da boca de Deus,  \r\nAleluia, aleluia\r\n\r\nSe vos perseguem por causa de mim, \r\nnão esqueçais o porque\r\nNão é o servo maior que o senhor,  \r\nAleluia, aleluia\r\n\r\nQuer comais, quer bebais	\r\nQuer façais qualquer coisa\r\nFazei tudo pra glória de Deus, \r\nAleluia, aleluia\r\n\r\nAleeeluuuuuia, Aleluia, Aleluia'),
	(21, 'Como São Belos', 'Como são belos os pés do mensageiro\r\nQue anuncia a paz\r\nComo são belos os pés do mensageiro\r\nQue anuncia o Senhor\r\n\r\nEle vive (aleluia), ele reina (para sempre)\r\nEle é Deus e Senhor\r\n\r\nO meu senhor chegou com toda a glória\r\nVivo, eu sei, ele está, \r\nBem junto a nós seu corpo santo a nos tocar, \r\nE vivo eu sei Ele está'),
	(22, 'Só Por Ti Jesus', 'Só por Ti Jesus, \r\nquero me consumir\r\nComo vela que queima no altar\r\nMe consumir de amor\r\n\r\nSó em Ti Jesus,\r\nquero me derramar\r\nComo o rio se entrega ao mar\r\nDerramar de amor\r\n\r\nPois Tu és o meu amparo o meu refúgio\r\nÉs alegria de minh´alma\r\nSó em Ti repousa a minha esperança\r\nNão vacilarei e mesmo na dor\r\nQuero seguir até o fim\r\n\r\nSó por Ti Jesus, \r\nSó por Ti Jesus, \r\nSó por Ti Jesus.'),
	(23, 'Como és Lindo', 'Que bom, senhor, ir ao teu encontro\r\nPoder chegar e adentrar a tua casa\r\nSentar-me contigo e partilhar da mesma mesa\r\nTe olhar, te tocar\r\nE te dizer: meu Deus, como és lindo\r\nTe olhar, te tocar\r\nE te dizer: meu Deus, como és lindo\r\n\r\nOh, meu senhor, sei que não sou nada\r\nSem merecer fizeste em mim tua morada\r\nMais ao receber-te perfeita comunhão se cria\r\nSou em ti, és em mim\r\nMinha alma diz: meu Deus, como és lindo\r\nSou em ti, és em mim\r\nMinha alma diz: meu Deus, como és lindo'),
	(24, 'Quero Te Dar a Paz', '[negrito]\r\nQuero te dar a paz 	\r\nDo meu Senhor\r\nCom muito amor\r\n[/negrito]\r\nNa flor vejo manifestar \r\nO poder da criação\r\nNos teus lábios eu vejo estar \r\nO sorriso de um irmão\r\n\r\nToda vez que eu te abraço\r\nE aperto a sua mão\r\nSinto forte o poder do amor, \r\nDentro do seu coração\r\n[negrito][Refrão][/negrito]\r\n\r\nDeus é Pai e nos protege\r\nCristo é filho e salvação\r\nSanto Espírito consolador\r\nNa trindade somos irmãos\r\n\r\nToda vez que te abraço\r\nE aperto a sua mão\r\nSinto forte o poder do amor, \r\nDentro do seu coração\r\n[negrito][Refrão][/negrito]'),
	(25, 'Os Sonhos de Deus (Preto no Branco)', 'Eu sei que não há nenhuma provação\r\nMaior do que eu possa suportar\r\nMas estou cansado Pai preciso crer\r\nNas tuas promessas pra continuar\r\n\r\nEu sei que me livraste das acusações\r\nMas estou cansado de chorar\r\nEspírito Santo vem me renovar\r\nSó tua presença pra me alegrar\r\n\r\nOs sonhos de Deus são maiores que os teus\r\nTão grandes que nem pode imaginar\r\nNão desanime filho Eu vim te consolar\r\nNas minhas promessas volte a acreditar\r\nOs sonhos de Deus são maiores que os teus\r\nPor isso vale a pena acreditar\r\nO dia está chegando Eu vou te renovar\r\nNa minha presença tu vais prosperar\r\n\r\nEu sei que não há nenhuma provação\r\nMaior do que eu possa suportar\r\nMas estou cansado Pai preciso crer\r\nNas tuas promessas pra continuar\r\n\r\nEu sei que me livraste das acusações\r\nMas estou cansado de chorar\r\nEspírito Santo vem me renovar\r\nSó tua presença pra me alegrar\r\n\r\nOs sonhos de Deus são maiores que os teus\r\nTão grandes que nem pode imaginar\r\nNão desanime filho Eu vim te consolar\r\nNas minhas promessas volte a'),
	(26, 'Os Sonhos de Deus  (Nani Azevedo)', 'Os sonhos de Deus são maiores que os meus\r\nEle vai fazer o melhor por mim\r\nEle vai além do que eu posso ver\r\nEle faz o que eu não posso fazer\r\n\r\nDeus vai cumprir os Seus planos em mim\r\nEle vai fazer o que lhe apraz\r\nSou pequeno e falho, mas Ele é Deus\r\nEle só faz o melhor pelos Seus\r\n\r\nDeus vai cumprir os Seus planos em mim\r\nEle vai fazer o que lhe apraz\r\nSou pequeno e falho, mas Ele é Deus\r\nEle só faz o melhor pelos Seus\r\n\r\nOs sonhos de Deus são maiores que os meus\r\nEle vai fazer o melhor por mim\r\nEle vai além do que eu posso ver\r\nEle faz o que eu não posso fazer\r\n\r\nDeus vai cumprir os Seus planos em mim\r\nEle vai fazer o que lhe apraz\r\nSou pequeno e falho, mas Ele é Deus\r\nEle só faz o melhor pelos Seus\r\n\r\nDeus vai cumprir os Seus planos em mim\r\nEle vai fazer o que lhe apraz\r\nSou pequeno e falho, mas Ele é Deus\r\nEle só faz o melhor pelos Seus\r\n\r\nAcredito sim, acredito sim\r\nAcredito sim que Deus vai fazer\r\nO impossível em meu viver\r\n\r\nAcredito sim, acredito sim\r\nAcredito sim que Deus va'),
	(27, 'Os Sonhos de Deus  (Ludmila)', 'Não desista, não pare de crer\r\nOs sonhos de Deus jamais vão morrer\r\nNão desista, não pare de lutar\r\nNão pare de adorar\r\n\r\nLevanta teus olhos e vê\r\nDeus está restaurando os teus sonhos\r\nE a tua visão\r\n\r\nSe tentaram matar os teus sonhos\r\nSufocando o teu coração\r\nSe lançaram você numa cova\r\nE, ferido, perdeu a visão\r\n\r\nSe tentaram matar os teus sonhos\r\nSufocando o teu coração\r\nSe lançaram você numa cova\r\nE, ferido, perdeu a visão\r\n\r\nNão desista, não pare de crer\r\nOs sonhos de Deus jamais vão morrer\r\nNão desista, não pare de lutar\r\nNão pare de adorar\r\n\r\nLevanta teus olhos e vê\r\nDeus está restaurando os teus sonhos\r\nE a tua visão\r\n\r\nRecebe a cura\r\nRecebe a unção\r\nUnção de ousadia\r\nUnção de conquista\r\nUnção de multiplicação\r\n\r\nRecebe a cura\r\nRecebe a unção\r\nUnção de ousadia\r\nUnção de conquista\r\nUnção de multiplicação\r\nOh, oh, oh\r\n\r\nSe tentaram matar os teus sonhos\r\nSufocando o teu coração\r\nSe lançaram você numa cova\r\nE, ferido, perdeu a visão\r\n\r\nSe tentaram matar os teus sonhos\r\nSufocando o '),
	(28, 'Deixa a luz do céu entrar', 'Tu anseias, eu bem sei, por salvação\r\nTens desejo de banir a escuridão\r\nAbre, pois, de par em par, teu coração\r\nE deixa a luz do céu entrar\r\n[negrito]\r\nDeixa a luz do céu entrar (deixa a luz céu entrar)\r\nDeixa a luz do céu entrar (deixa a luz céu entrar)\r\nAbre bem as portas do teu coração\r\nE deixa a luz do céu entrar\r\n[/negrito]\r\nCristo, a luz do céu, em ti quer habitar\r\nPara as trevas do pecado dissipar\r\nTeu caminho e coração iluminar\r\nE deixa a luz do céu entrar\r\n[negrito][refrão][/negrito]'),
	(29, 'Glória a Deus nas Alturas (Eliana)', 'Glória a Deus nas Alturas\r\nE Paz na Terra aos homens por Ele amados\r\nSenhor Deus, Rei dos Céus, Deus Pai, Todo-poderoso\r\n\r\nNós Vos louvamos, Nós vos bendizemos\r\nNós Vos adoramos, Nós vos glorificamos\r\nNós vos damos graças por Vossa imensa glória\r\n\r\nSenhor Jesus Cristo, Filho Unigênito\r\nSenhor Deus, Cordeiro de Deus, Filho de Deus Pai\r\n\r\nVós que tirais o pecado do mundo\r\nTende piedade de nós\r\nVós que tirais o pecado do mundo\r\nAcolhei a nossa súplica\r\nVós que estais à direita do Pai\r\nTende piedade de nós\r\n\r\nSó Vós sois o Santo. Só Vós o Senhor\r\nSó Vós o Altíssimo, Jesus Cristo\r\n[negrito]\r\nCom o Espírito Santo, Na glória de Deus Pai. Amém! \r\nCom o Espírito Santo, Na glória de Deus Pai. Amém! \r\nCom o Espírito Santo, Na glória de Deus Pai. Amém! \r\nCom o Espírito Santo, Na glória de Deus Pai. Amém! \r\n[/negrito]'),
	(31, 'O Profeta', 'Antes que te formasses dentro do ventre de tua mãe,\r\nAntes que tu nascesses, te conhecia  te consagrei.\r\nPara ser meu profeta entre as nações eu te escolhi,\r\nirás onde enviar-te   o que  te mando proclamarás!\r\n[negrito]\r\nTenho que gritar, tenho que arriscar,\r\nAi de mim se não o faço!\r\nComo escapar de ti, como calar,\r\nSe tua voz arde em meu peito?\r\n[/negrito]\r\nNão temas arriscar-te, porque contigo eu estarei,\r\nNão temas anunciar-me, por tua boca eu falarei.\r\nHoje te dou meu povo para arrancar e derrubar,\r\nPara edificar, construirás e plantarás!\r\n[negrito][refrão][/negrito]\r\n\r\n3.Deixa os teus irmãos, deixa teu pai e tua mãe,\r\nDeixa a tua casa, porque a terra gritando está.\r\nNada tragas contigo, porque ao teu lado eu estarei;\r\nÉ hora de lutar, porque meu povo sofrendo está.\r\n[negrito][refrão][/negrito]'),
	(37, 'Vimos te louvar em tua casa ó Senhor', '[negrito]\r\nVimos te louvar em tua casa ó Senhor.\r\nSomos a família que teu Filho congregou.\r\n [/negrito]\r\n1. Teu povo, tua família, vem hoje, com gratidão,\r\nLouvar o teu nome santo, unidos na adoração.\r\n[negrito][refrão][/negrito]\r\n\r\n2. Cantamos a tua graça, o teu infinito amor;\r\nA prece de nossas vidas em casa já começou.\r\n [negrito][refrão][/negrito]\r\n\r\n3. Das faltas contra a unidade queremos pedir perdão.\r\nÉ falta todo egoísmo que gera separação.\r\n[negrito][refrão][/negrito]\r\n \r\n4. Começa em nossa casa a vida em fraternidade.\r\nPossamos, com tua graça, vivê-la na liberdade.\r\n[negrito][refrão][/negrito]'),
	(39, 'Senhor que vieste salvar os corações arrependidos.  (Fabiane Vilarinho)', '1. Senhor que vieste salvar os corações arrependidos. \r\nTende piedade de nós!\r\n[negrito]Senhor, tende piedade, tende piedade,\r\nPiedade de nós!\r\nPiedade de nós!\r\n[/negrito]\r\n2.Cristo, que vieste chamar os pecadores, \r\nTende piedade de nós.\r\n[negrito]Cristo, tende piedade, tende piedade,\r\nPiedade de nós!\r\nPiedade de nós!\r\n[/negrito]\r\n3. Senhor que intercedei por nós junto do pai,\r\nTende piedade de nós.\r\n[negrito]Senhor, tende piedade, tende piedade,\r\nPiedade de nós!\r\nPiedade de nós!\r\n[/negrito]'),
	(40, 'Já não és mais pão e vinho', 'Já não és mais pão e vinho\r\nAgora és corpo e sangue vives em mim\r\nDe joelhos eu caio, a contemplar tua bondade\r\nComo não te adorar\r\nEntrastes pelos meus lábios\r\nTua graça vai inundando todo meu coração\r\nPor esta paz que enche de alegria meu ser\r\nComo não te adorar\r\n[negrito]\r\nSenhor jesus, meu salvador\r\nAmor eterno, amor divino\r\nJá não falta nada eu tenho tudo\r\nEu tenho a ti\r\nJá não falta nada eu tenho tudo\r\nEu tenho a ti\r\n[/negrito]\r\nDono e rei do universo\r\nComo pode ser possível, buscar meu amor\r\nÉs tão grande, eu pequeno e tu olhas pra mim\r\nComo não te adorar\r\nDe joelhos eu te peço\r\nQue.... todos os meus dias, sejam assim\r\nEntão olhar-te nos olhos e dizer-te assim\r\nComo não te adorar\r\n[negrito][refrão][/negrito]'),
	(43, 'Aleluuia! Aleluuia, Alelu.u.uia! A paz de Cristo (Arquidiocese Goiania)', '[negrito]\r\nAleluuia! Aleluuia, Alelu.u.uia!   \r\nAleluuia! Aleluuia, Alelu.u.uia! \r\n[/negrito]\r\n\r\n1. A paz de Cristo reine em vossos corações\r\nRicamente habite em vós sua palavra\r\n[negrito][refrão][/negrito]'),
	(44, 'Salmo 137 - Naquele dia em que gritei (salmos CatólicaWeb)', '[negrito]\r\nNaquele dia em que gritei, vos me escutastes, ó senhor!\r\nNaquele dia em que gritei, vós me escutastes! ó senhor!\r\n[/negrito]\r\nÓ Senhor, de coração, eu vos dou graças, porque ouvistes as palavras dos meus lábios!\r\nPerante vossos anjos vou cantar-vos e ante vosso templo vou prostrar-me\r\nEu agradeço vosso amor, vossa verdade, porque fizestes muito mais que prometestes\r\nNaquele dia em que gritei, vós me escutastes e aumentastes o vigor da minha alma\r\n\r\nAltíssimo é o Senhor, mas olha os pobres, e de longe reconhece os orgulhosos\r\nSe no meio da desgraça eu caminhar, vós me fazeis tornar à vida novamente\r\nQuando os meus perseguidores me atacarem e com ira investirem contra mim\r\nEstendereis o vosso braço em meu auxílio e havereis de me salvar com vossa destra\r\n\r\nCompletai em mim a obra começada; ó Senhor, vossa bondade é para sempre!\r\nEu vos peço: Não deixeis inacabada esta obra que fizeram vossas mãos!'),
	(45, 'Louvor e glória a ti, Senhor', '[negrito]\r\nLouvor e glória a ti, Senhor,\r\nCristo palavra de Deus!\r\nCristo palavra de Deus!\r\n[/negrito]\r\nOxalá ouvísseis hoje a sua voz:\r\nNão fecheis os corações como em meriba!'),
	(46, 'Aleluia, Aclamemos, a Palavra do Senhor !', '[negrito]\r\nAleluia! Aleluia, Aleluia!   \r\nCom alegria aclamemos, a Palavra do Senhor !\r\n[/negrito]\r\n1-O homem não vive somente de pão, \r\nMas de toda palavra que sai da boca de Deus !\r\n\r\n2-Fala Senhor que teu servo te escuta,\r\nTua palavra fortalece nossa luta !\r\n\r\n3-Só tu tens palavras eternas de vida,\r\nLuz pra guiar e iluminar nova lida !'),
	(48, 'Aleluia, aleluia a minh\'alma abrirei', 'Aleluia, aleluia a minh\'alma abrirei\r\nAleluia, aleluia, Cristo é meu Rei!\r\n\r\nAleluia, aleluia a minh\'alma abrirei\r\nAleluia, aleluia, Cristo é meu Rei!\r\n\r\nAleluia, aleluia a minh\'alma abrirei\r\nAleluia, aleluia, Cristo é meu Rei!'),
	(57, 'Salmo 89 - Vós fostes ó Senhor um refúgio para nós', '[negrito]\r\nVós fostes, ó Senhor, um refúgio para nós. \r\n[/negrito]\r\n1. Vós fazeis voltar ao pó todo mortal \r\nQuando dizeis: “voltai ao pó, filhos de Adão!”  \r\nPois, mil anos para vós são como ontem \r\nQual vigília de uma noite que passou. \r\n\r\n2. Eles passam como o sono da manhã, \r\nSão iguais à erva verde pelos campos:  \r\nDe manhã, ela floresce vicejante, \r\nMas, à tarde, é cortada e logo seca. \r\n\r\n3. Ensinai-nos a contar os nossos dias, \r\nE dai ao nosso coração sabedoria!  \r\nSenhor, voltai-vos! Até quando tardareis! \r\nTende piedade e compaixão de vossos servos! \r\n\r\n4. Saciai-nos de manhã com vosso amor, \r\nE exultaremos de alegria todo o dia!  \r\nQue a bondade do Senhor e nosso Deus \r\nRepouse sobre nós e nos conduza! \r\nTornai fecundo, ó Senhor, nosso trabalho.'),
	(58, 'Daqui do meu lugar', 'Daqui do meu lugar, eu olho o teu altar\r\nE fico a imaginar aquele pão, aquela refeição\r\nPartiste aquele pão e o deste aos teus irmãos\r\nCriaste a religião do pão do céu\r\nDo pão que vem do céu\r\n[negrito]\r\nSomos a Igreja do pão\r\nDo pão repartido, e do abraço e da paz\r\nSomos a Igreja do pão\r\nDo pão repartido, e do abraço e da paz\r\n[/negrito]\r\nDaqui do meu lugar, eu olho o teu altar\r\nE fico a imaginar aquela paz, aquela comunhão\r\nViveste aquela paz e a deste aos teus irmãos\r\nCriaste a religião do pão da paz\r\nDa paz que vem do céu\r\n[negrito]\r\nSomos a Igreja da paz\r\nDa paz partilhada, e do abraço e do pão\r\nSomos a Igreja da paz\r\nDa paz partilhada, e do abraço e do pão\r\n[/negrito]'),
	(59, 'Salmo 8 - Ó Senhor nosso Deus, como é grande Vosso nome', '[negrito]\r\nÓ Senhor nosso Deus, como é grande\r\nVosso nome por todo o universo!\r\n[/negrito]\r\nContemplando estes céus que plasmastes\r\nE formastes com dedos de artista;\r\nVendo a lua e estrelas brilhantes,\r\nPerguntamos: “Senhor, que é o homem,\r\nPara dele assim vos lembrardes\r\nE o tratardes com tanto carinho?”\r\n\r\nPouco abaixo de Deus o fizestes,\r\nCoroando-o de glória e esplendor;\r\nVós lhe destes poder sobre tudo,\r\nVossas obras aos pés lhe pusestes:\r\n\r\nAs ovelhas, os bois, os rebanhos,\r\nTodo o gado e as feras da mata;\r\nPassarinhos e peixes dos mares,\r\nTodo ser que se move nas águas.'),
	(60, 'Amém (Música 39 - Músicas de Igreja)', 'A..amém, A..amém, a, a, a, amém     \r\nA..amém, A..amém, a, a, a, amém');

-- Copiando estrutura para tabela bdfolhetodigital.tbmusicamomentomissa
DROP TABLE IF EXISTS `tbmusicamomentomissa`;
CREATE TABLE IF NOT EXISTS `tbmusicamomentomissa` (
  `idMusica` int(11) NOT NULL,
  `idMomento` int(11) NOT NULL,
  PRIMARY KEY (`idMusica`,`idMomento`),
  KEY `idMomento` (`idMomento`),
  CONSTRAINT `tbmusicamomentomissa_ibfk_1` FOREIGN KEY (`idMusica`) REFERENCES `tbmusica` (`idMusica`) ON DELETE CASCADE,
  CONSTRAINT `tbmusicamomentomissa_ibfk_2` FOREIGN KEY (`idMomento`) REFERENCES `tbmomentosmissa` (`idMomento`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbmusicamomentomissa: ~41 rows (aproximadamente)
DELETE FROM `tbmusicamomentomissa`;
INSERT INTO `tbmusicamomentomissa` (`idMusica`, `idMomento`) VALUES
	(1, 1),
	(1, 13),
	(2, 2),
	(3, 3),
	(4, 3),
	(5, 3),
	(6, 1),
	(8, 4),
	(9, 7),
	(10, 5),
	(11, 6),
	(12, 12),
	(13, 8),
	(14, 9),
	(15, 11),
	(16, 13),
	(18, 7),
	(19, 3),
	(20, 5),
	(21, 5),
	(22, 18),
	(23, 12),
	(24, 13),
	(25, 19),
	(26, 19),
	(27, 19),
	(28, 1),
	(29, 4),
	(31, 1),
	(37, 1),
	(39, 3),
	(40, 12),
	(43, 5),
	(44, 7),
	(45, 5),
	(46, 5),
	(48, 5),
	(57, 7),
	(58, 6),
	(59, 7),
	(60, 9);

-- Copiando estrutura para tabela bdfolhetodigital.tbsacerdotes
DROP TABLE IF EXISTS `tbsacerdotes`;
CREATE TABLE IF NOT EXISTS `tbsacerdotes` (
  `idSacerdote` int(11) NOT NULL AUTO_INCREMENT,
  `NomeSacerdote` varchar(250) DEFAULT NULL,
  `Funcao` varchar(50) DEFAULT NULL,
  `Telefone` varchar(30) DEFAULT NULL,
  `DataOrdenacao` date DEFAULT NULL,
  PRIMARY KEY (`idSacerdote`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbsacerdotes: ~3 rows (aproximadamente)
DELETE FROM `tbsacerdotes`;
INSERT INTO `tbsacerdotes` (`idSacerdote`, `NomeSacerdote`, `Funcao`, `Telefone`, `DataOrdenacao`) VALUES
	(1, 'Padre Fábio', 'Pároco', '', NULL),
	(2, 'Jacinto', 'Pároco', '', NULL),
	(4, 'Rodrigo 2', 's', '', NULL),
	(5, 'teste', 'padre', 'axxxx', NULL);

-- Copiando estrutura para tabela bdfolhetodigital.tbtempomusica
DROP TABLE IF EXISTS `tbtempomusica`;
CREATE TABLE IF NOT EXISTS `tbtempomusica` (
  `idTpLiturgico` int(11) NOT NULL,
  `idMusica` int(11) NOT NULL,
  PRIMARY KEY (`idTpLiturgico`,`idMusica`),
  KEY `idMusica` (`idMusica`),
  CONSTRAINT `FK_tbtempomusica_tbmusica` FOREIGN KEY (`idMusica`) REFERENCES `tbmusica` (`idMusica`) ON DELETE CASCADE,
  CONSTRAINT `FK_tbtempomusica_tbtpliturgico` FOREIGN KEY (`idTpLiturgico`) REFERENCES `tbtpliturgico` (`idTpLiturgico`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbtempomusica: ~37 rows (aproximadamente)
DELETE FROM `tbtempomusica`;
INSERT INTO `tbtempomusica` (`idTpLiturgico`, `idMusica`) VALUES
	(1, 1),
	(1, 2),
	(1, 3),
	(1, 4),
	(1, 5),
	(1, 8),
	(1, 9),
	(1, 10),
	(1, 12),
	(1, 13),
	(1, 14),
	(1, 15),
	(1, 16),
	(1, 18),
	(1, 19),
	(1, 20),
	(1, 21),
	(1, 22),
	(1, 23),
	(1, 24),
	(1, 25),
	(1, 26),
	(1, 29),
	(1, 37),
	(1, 39),
	(1, 40),
	(1, 43),
	(1, 44),
	(1, 45),
	(1, 46),
	(1, 48),
	(1, 57),
	(1, 58),
	(1, 59),
	(1, 60),
	(4, 6),
	(4, 20),
	(4, 28),
	(4, 31),
	(5, 21);

-- Copiando estrutura para tabela bdfolhetodigital.tbtpliturgico
DROP TABLE IF EXISTS `tbtpliturgico`;
CREATE TABLE IF NOT EXISTS `tbtpliturgico` (
  `idTpLiturgico` int(11) NOT NULL AUTO_INCREMENT,
  `DescTempo` varchar(50) NOT NULL,
  `Sigla` varchar(2) NOT NULL,
  PRIMARY KEY (`idTpLiturgico`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbtpliturgico: ~4 rows (aproximadamente)
DELETE FROM `tbtpliturgico`;
INSERT INTO `tbtpliturgico` (`idTpLiturgico`, `DescTempo`, `Sigla`) VALUES
	(1, 'Tempo Comum', 'TC'),
	(2, 'Advento', 'AD'),
	(3, 'Natal', 'NT'),
	(4, 'Quaresma', 'QM'),
	(5, 'Páscoa', 'PS');

-- Copiando estrutura para tabela bdfolhetodigital.tbvideo
DROP TABLE IF EXISTS `tbvideo`;
CREATE TABLE IF NOT EXISTS `tbvideo` (
  `idVideo` int(11) NOT NULL AUTO_INCREMENT,
  `idMusica` int(11) NOT NULL,
  `linkVideo` varchar(255) DEFAULT NULL,
  `Autor` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idVideo`),
  KEY `FK1_tbVideo_tbMusica` (`idMusica`),
  CONSTRAINT `FK1_tbVideo_tbMusica` FOREIGN KEY (`idMusica`) REFERENCES `tbmusica` (`idMusica`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela bdfolhetodigital.tbvideo: ~7 rows (aproximadamente)
DELETE FROM `tbvideo`;
INSERT INTO `tbvideo` (`idVideo`, `idMusica`, `linkVideo`, `Autor`) VALUES
	(1, 27, 'https://www.letras.mus.br/ludmila-ferber/167705/', 'Ludmila Ferber'),
	(2, 26, 'https://www.letras.mus.br/nani-azevedo/1287663/', 'Nani Azevedo'),
	(3, 25, 'https://www.vagalume.com.br/preto-no-branco/os-sonhos-de-deus.html', 'Preto no Branco'),
	(4, 44, 'https://www.youtube.com/watch?v=uZx1UTfIjUs', ''),
	(5, 57, 'https://www.youtube.com/watch?v=aExU7RMoJSE', ''),
	(6, 9, 'https://www.youtube.com/watch?v=2raE97GbvPs', ''),
	(7, 59, 'https://www.youtube.com/watch?v=hRRd7OEMjUM', ''),
	(8, 60, 'https://www.youtube.com/watch?v=291SzD3ef9k&list=RD291SzD3ef9k&start_radio=1', '');

-- Copiando estrutura para view bdfolhetodigital.viewmissacifra
DROP VIEW IF EXISTS `viewmissacifra`;
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `viewmissacifra` (
	`DataMissa` DATE NOT NULL,
	`Sigla` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`DescTempo` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`NomeIgreja` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`NomeSacerdote` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`AnoMissa` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`TituloMissa` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`Status` TINYINT(1) NULL,
	`SituacaoMissa` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`DescMomento` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`OrdemDeExecucao` CHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`NomeMusica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`Musica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`DescMusicaCifra` TEXT NULL COLLATE 'utf8mb4_general_ci',
	`TomMusica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci'
);

-- Copiando estrutura para view bdfolhetodigital.viewmissamusicas
DROP VIEW IF EXISTS `viewmissamusicas`;
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `viewmissamusicas` (
	`DataMissa` DATE NOT NULL,
	`Sigla` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`DescTempo` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`NomeIgreja` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`NomeSacerdote` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`AnoMissa` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`TituloMissa` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`Status` TINYINT(1) NULL,
	`SituacaoMissa` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`DescMomento` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`OrdemDeExecucao` CHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`NomeMusica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`Musica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci'
);

-- Copiando estrutura para view bdfolhetodigital.viewmusicas
DROP VIEW IF EXISTS `viewmusicas`;
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `viewmusicas` (
	`idMusica` INT(11) NOT NULL,
	`NomeMusica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`Musica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`DescMomento` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`OrdemDeExecucao` CHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`LinkVideo` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`autor` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`TomMusica` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`DescMusicaCifra` TEXT NULL COLLATE 'utf8mb4_general_ci',
	`LinkSiteCifra` TEXT NULL COLLATE 'utf8mb4_general_ci',
	`DescTempo` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`Sigla` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci'
);

-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `viewmissacifra`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `viewmissacifra` AS (
SELECT mi.DataMissa, 
		 tpl.Sigla, tpl.DescTempo,
     	 ig.NomeIgreja, sa.NomeSacerdote, 
       mi.AnoMissa, mi.TituloMissa, 
		 mi.`Status`, CASE WHEN 1 THEN 'Ativo' WHEN 0 THEN 'nativa' ELSE '' END AS `SituacaoMissa`,
		 mm.DescMomento, mm.OrdemDeExecucao ,
		 mu.NomeMusica, mu.Musica,
		 ci.DescMusicaCifra, ci.TomMusica
FROM tbmissa mi
LEFT OUTER JOIN tbtpliturgico tpl ON mi.idTpLiturgico = tpl.idTpLiturgico
LEFT OUTER JOIN tbmissamusicas mim ON mi.idMissa = mim.idMissa
LEFT OUTER JOIN tbmusicamomentomissa mmm ON mim.idMusica = mmm.idMusica AND mim.idMusicaMomentoMissa = mmm.idMomento
LEFT OUTER JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
LEFT OUTER JOIN tbmusica mu ON mmm.idMusica = mu.idMusica 
LEFT OUTER JOIN tbCifras ci ON ci.idMusica = mmm.idMusica
LEFT OUTER JOIN tbigreja ig ON mi.idIgreja = ig.idIgreja
LEFT OUTER JOIN tbigrejasacerdote igs ON ig.idIgreja = igs.idIgreja AND igs.`Status` = 1
LEFT OUTER JOIN tbsacerdotes sa ON igs.idSacerdote = sa.idSacerdote
ORDER BY mi.DataMissa DESC, ig.NomeIgreja, mm.OrdemDeExecucao) 
;

-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `viewmissamusicas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `viewmissamusicas` AS (
SELECT mi.DataMissa, 
		 tpl.Sigla, tpl.DescTempo,
     	 ig.NomeIgreja, sa.NomeSacerdote, 
       mi.AnoMissa, mi.TituloMissa, 
		 mi.`Status`, CASE WHEN 1 THEN 'Ativo' WHEN 0 THEN 'nativa' ELSE '' END AS `SituacaoMissa`,
		 mm.DescMomento, mm.OrdemDeExecucao ,
		 mu.NomeMusica, mu.Musica  
FROM tbmissa mi
LEFT OUTER JOIN tbtpliturgico tpl ON mi.idTpLiturgico = tpl.idTpLiturgico
LEFT OUTER JOIN tbmissamusicas mim ON mi.idMissa = mim.idMissa
LEFT OUTER JOIN tbmusicamomentomissa mmm ON mim.idMusica = mmm.idMusica AND mim.idMusicaMomentoMissa = mmm.idMomento
LEFT OUTER JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
LEFT OUTER JOIN tbmusica mu ON mmm.idMusica = mu.idMusica
LEFT OUTER JOIN tbigreja ig ON mi.idIgreja = ig.idIgreja
LEFT OUTER JOIN tbigrejasacerdote igs ON ig.idIgreja = igs.idIgreja AND igs.`Status` = 1
LEFT OUTER JOIN tbsacerdotes sa ON igs.idSacerdote = sa.idSacerdote
ORDER BY mi.DataMissa DESC, ig.NomeIgreja, mm.OrdemDeExecucao) 
;

-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `viewmusicas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `viewmusicas` AS (
SELECT mu.idMusica, mu.NomeMusica, mu.Musica, mm.DescMomento, mm.OrdemDeExecucao, 
       v.LinkVideo, v.autor, c.TomMusica, c.DescMusicaCifra, c.LinkSiteCifra,
       tl.DescTempo, tl.Sigla
FROM tbMusica mu
LEFT OUTER JOIN tbmusicamomentomissa mmm ON mu.IdMusica = mmm.idMusica
LEFT OUTER JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
LEFT OUTER JOIN tbvideo v ON mu.idMusica = v.idVideo
LEFT OUTER JOIN tbcifras c ON mu.idMusica = c.idCifra
LEFT OUTER JOIN tbtempoMusica tm ON mu.idMusica = tm.idMusica
LEFT OUTER JOIN tbtpliturgico tl ON tm.idTpLiturgico = tl.idTpLiturgico
ORDER BY mm.OrdemDeExecucao, mu.NomeMusica) 
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
