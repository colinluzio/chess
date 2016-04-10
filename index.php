<?php
require 'vendor/autoload.php';
//spl_autoload_register();

$game = (isset($_GET['game']) ? $_GET['game'] : 0);
$move = (isset($_GET['move']) ? $_GET['move'] : 0);


$chess = new Game\Chess($game,$move);
?>
