

<?php

include_once "CardFunctions.php";
include_once "includes/header.php";

$cardFunction = new CardFunctions();
?>
<script>
    function addData(cards){
      $.ajax({url:"cardsHandler.php?fn=addNewPrices",type:"POST",success:function(result){
        $("#renderTable").html(result);
      }});
    }
</script>

<div id="renderTable"><?php $cardFunction->render(); ?></div>
<button class="addButton" onclick="addData()">Werte Übertragen</button>

