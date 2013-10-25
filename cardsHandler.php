<?php
include_once "CardFunctions.php";
$cards = new CardFunctions();
if(isset($_GET['fn'])){
    $cards->$_GET['fn']();
}
?>
