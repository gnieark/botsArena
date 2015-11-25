<?php
function  arenas_get_list(){
    include (__DIR__."/arenas_lists.php");
    if(isset($_GET['arena'])){
        foreach ($arenas as $arena){
            if($arena['id'] == $_GET['arena']){
                $arenas['current'] = $arena;
                break;
            }
        }
    }
    return $arenas;
}