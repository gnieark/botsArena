<?php
#- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of botsArena.
#
# Copyright (C) Gnieark et contributeurs
# Licensed under the GPL version 3.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/gpl-3.0-standalone.html
#
# -- END LICENSE BLOCK -----------------------------------------

$hist=get_battles_history($currentArena);


foreach($hist as $sc){
    echo '<article><h3><a href="/p/aboutBot/'.urlencode(htmlentities($sc['bot1'])).'">'.$sc['bot1'].'</a> VS <a href="/p/aboutBot/'.urlencode(htmlentities($sc['bot2'])).'">'.$sc['bot2'].'</a></h3>
	<ul>
	<li>'.$sc['bot1']." ".$lang['VICTORIES'].":".$sc['player1Wins'].'</li>
	<li>'.$sc['bot2']." ".$lang['VICTORIES'].":".$sc['player2Wins'].'</li>
	<li>'.$lang['DRAW'].":".$sc['draws'].'</li>
	</ul></article>';
}