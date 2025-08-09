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
CREATE DATABASE IF NOT EXISTS `bdfolhetodigital` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `bdfolhetodigital`;

-- Copiando estrutura para tabela bdfolhetodigital.tbcifras
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

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbdatacomemorativa
CREATE TABLE IF NOT EXISTS `tbdatacomemorativa` (
  `idDataComemorativa` int(11) NOT NULL AUTO_INCREMENT,
  `DescComemoracao` varchar(200) NOT NULL,
  `QuandoAcontece` varchar(200) NOT NULL,
  `MesDia` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`idDataComemorativa`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbigreja
CREATE TABLE IF NOT EXISTS `tbigreja` (
  `idIgreja` int(11) NOT NULL AUTO_INCREMENT,
  `NomeIgreja` varchar(250) NOT NULL,
  `Endereco` varchar(250) DEFAULT NULL,
  `Telefone` varchar(30) DEFAULT NULL,
  `Tipo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idIgreja`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbigrejasacerdote
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

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbmissa
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

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbmissamusicas
CREATE TABLE IF NOT EXISTS `tbmissamusicas` (
  `idMusica` int(11) DEFAULT NULL,
  `idMissa` int(11) DEFAULT NULL,
  `idMusicaMomentoMissa` int(11) DEFAULT NULL,
  KEY `idMissa` (`idMissa`),
  KEY `idMusica` (`idMusica`,`idMusicaMomentoMissa`),
  CONSTRAINT `FK_tbmusicasmissa_tbmissa` FOREIGN KEY (`idMissa`) REFERENCES `tbmissa` (`idMissa`) ON DELETE CASCADE,
  CONSTRAINT `FK_tbmusicasmissa_tbmusicamomentomissa` FOREIGN KEY (`idMusica`, `idMusicaMomentoMissa`) REFERENCES `tbmusicamomentomissa` (`idMusica`, `idMomento`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbmomentosmissa
CREATE TABLE IF NOT EXISTS `tbmomentosmissa` (
  `idMomento` int(11) NOT NULL AUTO_INCREMENT,
  `DescMomento` varchar(50) DEFAULT NULL,
  `OrdemDeExecucao` char(4) DEFAULT NULL,
  PRIMARY KEY (`idMomento`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbmusica
CREATE TABLE IF NOT EXISTS `tbmusica` (
  `idMusica` int(11) NOT NULL AUTO_INCREMENT,
  `NomeMusica` varchar(200) DEFAULT NULL,
  `Musica` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`idMusica`),
  UNIQUE KEY `NomeMusica` (`NomeMusica`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbmusicamomentomissa
CREATE TABLE IF NOT EXISTS `tbmusicamomentomissa` (
  `idMusica` int(11) NOT NULL,
  `idMomento` int(11) NOT NULL,
  PRIMARY KEY (`idMusica`,`idMomento`),
  KEY `idMomento` (`idMomento`),
  CONSTRAINT `tbmusicamomentomissa_ibfk_1` FOREIGN KEY (`idMusica`) REFERENCES `tbmusica` (`idMusica`) ON DELETE CASCADE,
  CONSTRAINT `tbmusicamomentomissa_ibfk_2` FOREIGN KEY (`idMomento`) REFERENCES `tbmomentosmissa` (`idMomento`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbsacerdotes
CREATE TABLE IF NOT EXISTS `tbsacerdotes` (
  `idSacerdote` int(11) NOT NULL AUTO_INCREMENT,
  `NomeSacerdote` varchar(250) DEFAULT NULL,
  `Funcao` varchar(50) DEFAULT NULL,
  `Telefone` varchar(30) DEFAULT NULL,
  `DataOrdenacao` date DEFAULT NULL,
  PRIMARY KEY (`idSacerdote`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbtempomusica
CREATE TABLE IF NOT EXISTS `tbtempomusica` (
  `idTpLiturgico` int(11) NOT NULL,
  `idMusica` int(11) NOT NULL,
  PRIMARY KEY (`idTpLiturgico`,`idMusica`),
  KEY `idMusica` (`idMusica`),
  CONSTRAINT `FK_tbtempomusica_tbmusica` FOREIGN KEY (`idMusica`) REFERENCES `tbmusica` (`idMusica`) ON DELETE CASCADE,
  CONSTRAINT `FK_tbtempomusica_tbtpliturgico` FOREIGN KEY (`idTpLiturgico`) REFERENCES `tbtpliturgico` (`idTpLiturgico`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbtpliturgico
CREATE TABLE IF NOT EXISTS `tbtpliturgico` (
  `idTpLiturgico` int(11) NOT NULL AUTO_INCREMENT,
  `DescTempo` varchar(50) NOT NULL,
  `Sigla` varchar(2) NOT NULL,
  PRIMARY KEY (`idTpLiturgico`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela bdfolhetodigital.tbvideo
CREATE TABLE IF NOT EXISTS `tbvideo` (
  `idVideo` int(11) NOT NULL AUTO_INCREMENT,
  `idMusica` int(11) NOT NULL,
  `linkVideo` varchar(255) DEFAULT NULL,
  `Autor` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idVideo`),
  KEY `FK1_tbVideo_tbMusica` (`idMusica`),
  CONSTRAINT `FK1_tbVideo_tbMusica` FOREIGN KEY (`idMusica`) REFERENCES `tbmusica` (`idMusica`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para view bdfolhetodigital.viewmissacifra
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
