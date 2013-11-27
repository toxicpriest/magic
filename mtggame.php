<script language="JavaScript" src="/src/js/game.js"></script>
<?php
session_start();
include_once "includes/header.php";
include_once "db.php";
include_once "game.php";
include_once "deck.php";
include_once "player.php";
include_once "library.php";
include_once "card.php";
$player = new player();
$deckID = $_POST['deck'];
$player_number=$_SESSION['player_number'];
$game = new game(null, null, $_SESSION['gameID']);
$game->coinToss();
$player->justFillInfos($_SESSION['nick'], $_SESSION['id']);
$player->selectDeckById($deckID);
if ($player_number == 1) {
    $game->addPlayer1($player);
    $game->library_1->shuffleLibrary();
    $game->player1->mulligan($game->library_1);
    $game->saveData("LIBRARY", $game->library_1, $game->player1->playerId);
    $game->saveData("HAND", $game->player1->playerHand, $game->player1->playerId);

    echo'
    <div id = "urHand" >';
    foreach ($game->player1->playerHand as $card) {
        echo "<img src='" . $card->picture . "' height='80px'>";
    }
    echo'</div>';
}
elseif ($player_number == 2) {
    $game->addPlayer2($player);
    $game->library_2->shuffleLibrary();
    $game->player2->mulligan($game->library_2);
    $game->saveData("LIBRARY", $game->library_2, $game->player2->playerId);
    $game->saveData("HAND", $game->player2->playerHand, $game->player2->playerId);

    echo'
    <div id = "urHand" >';
    foreach ($game->player2->playerHand as $card) {
        echo "<img src='" . $card->picture . "' height='80px'>";
    }
    echo'</div>';
}
if ($game->checkCoinToss($player_number)) {
    echo'
<div id="dialog" title="Challenge?">
    <button onclick="dialogAction(" begin
    ","' . $player_number . '","yes");">Yes</button>
    <button onclick="dialogAction(" begin
    ","' . $player_number . '","no");">No</button>
</div>';
}
echo "game";

