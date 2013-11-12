<?php

include_once "import.php";
include_once "deck.php";
include_once "card.php";
include_once "library.php";
include_once "includes/header.php";
include_once "player.php";

//$import = new import();
//$import->doImport();

$player = new player();
$player->login("bieti","toxic666");
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
$library=new library();
$library->buildLibrary($deck->deckId);
$library->shuffleLibrary();
$player->mulligan($library);

foreach($player->playerHand as $cardInHand){
echo "<img height='30px' src='".$cardInHand->picture."'>";
}
$player->mulligan($library);
$card=$library->drawCard();
$test="new";
?>
<div id="loadScreen"></div>

