<?php$hist=get_battles_history($currentArena);
$hist=get_battles_history($currentArena);

foreach($hist as $sc){
    echo '<h3><a href="/p/aboutBot/'.urlencode(htmlentities($sc['bot1'])).'">'.$sc['bot1'].'</a> VS <a href="/p/aboutBot/'.urlencode(htmlentities($sc['bot2'])).'">'.$sc['bot2'].'</a></h3>
	<ul>
	<li>'.$sc['bot1']." ".$lang['VICTORIES'].":".$sc['player1Wins'].'</li>
	<li>'.$sc['bot2']." ".$lang['VICTORIES'].":".$sc['player2Wins'].'</li>
	<li>'.$lang['DRAW'].":".$sc['draws'].'</li>
	</ul>';
}