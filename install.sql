-- MySQL dump 10.13  Distrib 5.5.54, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: botsarena
-- ------------------------------------------------------
-- Server version	5.5.54-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `arena_history`
--

DROP TABLE IF EXISTS `arena_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arena_history` (
  `game` varchar(8) NOT NULL,
  `player1_id` int(11) NOT NULL,
  `player2_id` int(11) NOT NULL,
  `player1_winsCount` int(11) NOT NULL,
  `player2_winsCount` int(11) NOT NULL,
  `nulCount` int(11) NOT NULL,
  PRIMARY KEY (`game`,`player1_id`,`player2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bots`
--

DROP TABLE IF EXISTS `bots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `game` varchar(10) NOT NULL,
  `url` text NOT NULL,
  `description` text NOT NULL,
  `unclean_description` text NOT NULL,
  `active` int(1) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `validate_secret` varchar(8) NOT NULL,
  `author_email` text NOT NULL,
  `ELO` int(11) NOT NULL DEFAULT '1500',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bots`
--

LOCK TABLES `bots` WRITE;
/*!40000 ALTER TABLE `bots` DISABLE KEYS */;
INSERT INTO `bots` VALUES 
(2,'stupidAI','tictactoe','https://ias.tinad.fr/stupidIATictactoe.php','This bot choose the cell to play simply by random.<br />\r\nSon code source: https://github.com/gnieark/IAS/blob/master/stupidIATictactoe.php','This bot choose the cell to play simply by random.\r\nSon code source: https://github.com/gnieark/IAS/blob/master/stupidIATictactoe.php',1,'2015-12-03 10:55:34','','',814),
(3,'stupidIA','Battleship','https://ias.tinad.fr/StupidIABattleship.php','','',1,'2015-12-11 11:16:50','','',1538),
(4,'stupidIA','connectFou','https://ias.tinad.fr/StupidIAconnectFour.php','','',1,'2016-05-11 07:47:57','','',2087),
(5,'stupidIa','tron','http://ias.localhost/stupidIATronBetter.php','','',1,'2016-06-29 07:20:08','','',1677);

/*!40000 ALTER TABLE `bots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bots_modifs`
--

DROP TABLE IF EXISTS `bots_modifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bots_modifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `real_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `game` varchar(10) NOT NULL,
  `url` text NOT NULL,
  `description` text NOT NULL,
  `unclean_description` text NOT NULL,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `validate_secret` varchar(8) NOT NULL,
  `author_email` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bots_modifs`
--

UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-01  8:09:06
