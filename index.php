

<?php

include_once "CardFunctions.php";
include_once "includes/header.php";

$cardFunction = new CardFunctions();
?>


<div id="renderTable"><?php $cardFunction->render(); ?></div>
<button class="addButton" onclick="addData()">Werte Übertragen</button>

