CREATE TABLE `arena_history` (
<<<<<<< HEAD
=======

CREATE TABLE IF NOT EXISTS `arena_history` (

>>>>>>> f4f3a1f5e4b489cb7ef2bf70fc80467382f82585
  `game` varchar(8) NOT NULL,
  `player1_id` int(11) NOT NULL,
  `player2_id` int(11) NOT NULL,
  `player1_winsCount` int(11) NOT NULL,
  `player2_winsCount` int(11) NOT NULL,
  `nulCount` int(11) NOT NULL,
  PRIMARY KEY (`game`,`player1_id`,`player2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
LOCK TABLES `arena_history` WRITE;
/*!40000 ALTER TABLE `arena_history` DISABLE KEYS */;
INSERT INTO `arena_history` VALUES ('Battlesh',10,10,173,438,0),('tictacto',1,1,0,0,44),('tictacto',1,2,46,0,2),('tictacto',1,3,23,0,47),('tictacto',2,1,0,20,4),('tictacto',2,3,0,5,0),('tictacto',3,1,0,0,6),('tictacto',3,2,2,0,0),('tictacto',3,3,3,0,0);
/*!40000 ALTER TABLE `arena_history` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `bots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `game` varchar(10) NOT NULL,
  `url` text NOT NULL,
  `description` text NOT NULL,
  `active` int(1) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `validate_secret` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
LOCK TABLES `bots` WRITE;
/*!40000 ALTER TABLE `bots` DISABLE KEYS */;
INSERT INTO `bots` VALUES (1,'moul','tictactoe','http://tictactoe.m.42.am/','moul\'s Tic Tac Toe resolver in Golang. <a href=\"https://github.com/moul/tictactoe\">Open sourced</a> using Minimax algorithm',1,'2015-12-03 10:55:34',''),(2,'stupidAI','tictactoe','http://morpionmaster.tinad.fr/stupidIa.php','A PHP script that choose next case by... random. <a href=\"https://github.com/jeannedhack/programmingChallenges/blob/master/morpionsFights/Master/stupidIa.php>By Gnieark, here on github</a>',1,'2015-12-03 10:55:34',''),(3,'Gnieark','tictactoe','http://morpionmaster.tinad.fr/gnieark.php','Gnieark\'s PHP AI, using minmax algorythm. <a href=\"https://github.com/gnieark/tictactoeChallenge\">Published on github</a>',1,'2015-12-03 10:55:34',''),(10,'stupidIA','Battleship','https://botsArena.tinad.fr/StupidIABattleship.php','',1,'2015-12-11 11:16:50','!!!');
/*!40000 ALTER TABLE `bots` ENABLE KEYS */;
UNLOCK TABLES;
