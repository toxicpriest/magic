# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.27)
# Datenbank: mtg_preise
# Erstellungsdauer: 2013-10-25 15:13:35 +0000
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
  `urltrader` varchar(255) DEFAULT NULL,
  `edition` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `pricelowest` double DEFAULT NULL,
  `priceaverage` double DEFAULT NULL,
  `pricefoil` double DEFAULT NULL,
  `pricetrader` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `card` WRITE;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;

INSERT INTO `card` (`id`, `urlmkm`, `urltrader`, `edition`, `name`, `pricelowest`, `priceaverage`, `pricefoil`, `pricetrader`)
VALUES
	(1,'https://www.magickartenmarkt.de/Dark_Confidant_Ravnica_City_of_Guilds.c1p13348.prod','URL von Trader','Ravnica City of Guilds','Dark Confidant',1,1.5,3,100),
	(2,'https://www.magickartenmarkt.de/Liliana_of_the_Veil_Innistrad.c1p250442.prod','URL von Trader2','Innistrad','Liliana of the Veil',2,4.5,15,13500);

/*!40000 ALTER TABLE `card` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
