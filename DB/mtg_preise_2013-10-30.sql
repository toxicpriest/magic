# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.1.67)
# Datenbank: mtg_preise
# Erstellungsdauer: 2013-10-30 10:32:58 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle card
# ------------------------------------------------------------

DROP TABLE IF EXISTS `card`;

CREATE TABLE `card` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `urlmkm` varchar(255) DEFAULT NULL,
  `edition` varchar(255) DEFAULT NULL,
  `cardname` varchar(255) DEFAULT NULL,
  `pricelowest` double DEFAULT NULL,
  `priceaverage` double DEFAULT NULL,
  `pricefoil` double DEFAULT NULL,
  `pricefirstger` double DEFAULT NULL,
  `pricefirstgernm` double DEFAULT NULL,
  `pricefirstgerps` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `card` WRITE;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;

INSERT INTO `card` (`id`, `urlmkm`, `edition`, `cardname`, `pricelowest`, `priceaverage`, `pricefoil`, `pricefirstger`, `pricefirstgernm`, `pricefirstgerps`)
VALUES
	(33,'https://www.magickartenmarkt.de/Akroma_Angel_of_Fury_Planar_Chaos.c1p14273.prod','Planar Chaos','Akroma, Angel of Fury ',0.95,2.57,14.89,1.25,1.25,7.6),
	(30,'https://www.magickartenmarkt.de/Overgrown_Tomb_Return_to_Ravnica.c1p258317.prod','Return to Ravnica','Overgrown Tomb ',7,10.53,18,7.2,7.2,32),
	(32,'https://www.magickartenmarkt.de/Dark_Confidant_Modern_Masters.c1p261911.prod','Modern Masters','Dark Confidant ',44,56.45,105,47.99,47.99,200);

/*!40000 ALTER TABLE `card` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
