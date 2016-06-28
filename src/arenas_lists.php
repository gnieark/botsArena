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

$arenas=array(
    array(
        'id'    => "tictactoe",
        'url'   => "/tictactoe",
        'title' => "Tic Tac Toe",
        'metaDescription'   => 'Affrontements de bots au TicTacToe, morpion',
        'jsFile'=> "js.js",
        'cssFile'=> "style.css",
        'ludusUrl' => "/testBotScripts/tictactoe.html"
    ),
    array(
        'id' => "Battleship",
        'url' => "/Battleship",
        'title' => "bataille Navale",
        'metaDescription'   => 'Affrontements de bots à la battaille navale',
        'jsFile'=> "js.js",
        'cssFile'=> "style.css"
    ),
        array(
        'id' => "connectFour",
        'url' => "/connectFour",
        'title' => "Puissance 4",
        'metaDescription'   => 'Affrontements de bots puissance 4',
        'jsFile'=> "js.js",
        'cssFile'=> "style.css",
        'ludusUrl' => "/testBotScripts/connectfour.html"
    )
    
);