CREATE TABLE IF NOT EXISTS `arena_history` (
  `game` int(11) NOT NULL,
  `player1_id` int(11) NOT NULL,
  `player2_id` int(11) NOT NULL,
  `player1_winsCount` int(11) NOT NULL,
  `player2_winsCount` int(11) NOT NULL,
  `nulCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `arena_history`
  ADD PRIMARY KEY (`game`,`player1_id`,`player2_id`);


CREATE TABLE IF NOT EXISTS `bots` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `game` varchar(10) NOT NULL,
  `url` int(11) NOT NULL,
  `description` text NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `bots`
  ADD PRIMARY KEY (`id`);

