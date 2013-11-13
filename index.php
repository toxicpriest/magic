<?php

include_once "import.php";
include_once "deck.php";
include_once "card.php";
include_once "library.php";
include_once "includes/header.php";
include_once "player.php";
include_once "db.php";
include_once "deck.php";
include_once "game.php";
session_start();
//$import = new import();
//$import->doImport();

$player = new player();
/*$player2 = new player();
$player->login("pete","1234");
$player2->login("bieti","toxic666");



$cards=array();
    $cards[0]["card_id"]="2b6f48ac3e1c531576cb06c03d0cb81b";
    $cards[0]["amount"]=20;
    $cards[1]["card_id"]="2f52027abcb61f50aff7aef89dd56dac";
    $cards[1]["amount"]=4;
    $cards[2]["card_id"]="dc93aeec5532ad715f08bd3c5939f9c4";
    $cards[2]["amount"]=4;
$deck = new deck();
$deck->addCards($cards);
$deck->save("test4",$player->playerId);
*/
/*$player->selectDeck("MUD");
$player2->selectDeck("The Gate");
$game = new game($player,$player2);
echo"<div class='player1'>";
foreach($game->player1->playerHand as $card){
echo "<img src='".$card->picture."' height='80px'>";
}
 echo "</div><br><div class='player2'>";
foreach($game->player2->playerHand as $card){
echo "<img src='".$card->picture."' height='80px'>";
}
echo "</div>";*/
include_once "lobby.php";
?>


