<?php
include_once "CardFunctions.php";
$cards = new CardFunctions();
if(isset($_GET['fn'])){
    if(isset($_GET['param'])){
        $cards->$_GET['fn']($_GET['param']);
    }
    else{
        $cards->$_GET['fn']();
    }
}
?>
