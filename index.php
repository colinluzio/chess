<?php

spl_autoload_register();

if(isset($_GET['game'])){
    $game = $_GET['game'];
} else {
    $game = 0;
}
if(isset($_GET['move'])){
    $move = $_GET['move'];
} else {
    $move = '';
}

$chess = new Game\Chess($game,$move);
?>
