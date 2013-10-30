

<?php

include_once "CardFunctions.php";
include_once "includes/header.php";

$cardFunction = new CardFunctions();
?>
<div id="loadScreen"></div>

<div id="content">
    <div id="hoverDiv"></div>
    <div id="renderTable main"><?php $cardFunction->render(); ?></div>
    <button class="addButton" onclick="addData()">Werte Übertragen</button>
    <button class="addButton" onclick="refreshData()">REFRESH</button>

    <label for="newURL">
        Neuen Link hinzufügen:
    </label>
    <input type="text" id="newURL">
    <button onclick="addCard()">Hinzufügen</button>
</div>