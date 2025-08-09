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

CREATE VIEW viewMusica AS (
SELECT mu.idMusica, mu.NomeMusica, mu.Musica, mm.DescMomento, mm.OrdemDeExecucao, 
       v.LinkVideo, v.autor, c.TomMusica, c.DescMusicaCifra, c.DescMusicaCifraHtml,
       tl.DescTempo, tl.Sigla
FROM tbMusica mu
LEFT OUTER JOIN tbmusicamomentomissa mmm ON mu.IdMusica = mmm.idMusica
LEFT OUTER JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
LEFT OUTER JOIN tbvideo v ON mu.idMusica = v.idVideo
LEFT OUTER JOIN tbcifras c ON mu.idMusica = c.idCifra
LEFT OUTER JOIN tbtempoMusica tm ON mu.idMusica = tm.idMusica
LEFT OUTER JOIN tbtpliturgico tl ON tm.idTpLiturgico = tl.idTpLiturgico
ORDER BY mm.OrdemDeExecucao, mu.NomeMusica);


CREATE VIEW viewMissaMusicas AS (
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
ORDER BY mi.DataMissa DESC, ig.NomeIgreja, mm.OrdemDeExecucao);


