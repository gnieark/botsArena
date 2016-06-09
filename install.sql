
--
-- Structure de la table `arena_history`
--

CREATE TABLE IF NOT EXISTS `arena_history` (
  `game` varchar(8) NOT NULL,
  `player1_id` int(11) NOT NULL,
  `player2_id` int(11) NOT NULL,
  `player1_winsCount` int(11) NOT NULL,
  `player2_winsCount` int(11) NOT NULL,
  `nulCount` int(11) NOT NULL,
   PRIMARY KEY (`game`,`player1_id`,`player2_id`)
);


--
-- Structure de la table `bots`
--

CREATE TABLE IF NOT EXISTS `bots` (
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
);


--
-- Contenu de la table `bots` only stupid ias
--

INSERT INTO `bots` (`id`, `name`, `game`, `url`, `description`, `unclean_description`, `active`, `date_inscription`, `validate_secret`, `author_email`) VALUES
(2, 'stupidAI', 'tictactoe', 'https://ias.tinad.fr/stupidIATictactoe.php', '', '', 1, '2015-12-03 10:55:34', '', ''),
(3, 'stupidAI', 'Battleship', 'https://botsArena.tinad.fr/StupidIABattleship.php', '', '', 1, '2015-12-11 11:16:50', '', ''),
(4, 'stupidAI', 'connectFou', 'https://ias.tinad.fr/StupidIAconnectFour.php', '', '', 1, '2016-05-11 07:47:57', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `bots_modifs`
--

CREATE TABLE IF NOT EXISTS `bots_modifs` (
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
  PRIMARY KEY (`id`);
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


