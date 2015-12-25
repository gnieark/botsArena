CREATE TABLE `arena_history` (

CREATE TABLE IF NOT EXISTS `arena_history` (

  `game` varchar(8) NOT NULL,
  `player1_id` int(11) NOT NULL,
  `player2_id` int(11) NOT NULL,
  `player1_winsCount` int(11) NOT NULL,
  `player2_winsCount` int(11) NOT NULL,
  `nulCount` int(11) NOT NULL,
  PRIMARY KEY (`game`,`player1_id`,`player2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `bots` VALUES (1,'moul','tictactoe','http://tictactoe.m.42.am/','moul\'s Tic Tac Toe resolver in Golang. <a href=\"https://github.com/moul/tictactoe\">Open sourced</a> using Minimax algorithm',1,'2015-12-03 10:55:34',''),(2,'stupidAI','tictactoe','http://morpionmaster.tinad.fr/stupidIa.php','A PHP script that choose next case by... random. <a href=\"https://github.com/jeannedhack/programmingChallenges/blob/master/morpionsFights/Master/stupidIa.php>By Gnieark, here on github</a>',1,'2015-12-03 10:55:34',''),(3,'Gnieark','tictactoe','http://morpionmaster.tinad.fr/gnieark.php','Gnieark\'s PHP AI, using minmax algorythm. <a href=\"https://github.com/gnieark/tictactoeChallenge\">Published on github</a>',1,'2015-12-03 10:55:34','');
