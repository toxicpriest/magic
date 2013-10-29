

<?php

include_once "CardFunctions.php";
include_once "includes/header.php";

$cardFunction = new CardFunctions();
?>
<script>
    function addData(){
        showLoadScreen();
      $.ajax({url:"cardsHandler.php?fn=addNewPrices",type:"POST",success:function(result){
        $("#renderTable").html(result);
        hideLoadScreen();
      }});
    }
    function addCard(){
        showLoadScreen();
        var url = document.getElementById("newURL").value;
        $.ajax({url:"cardsHandler.php?fn=addNewURL&param=" + url,type:"POST",success:function(result){
            $("#renderTable").html(result);
            hideLoadScreen();
        }});
        document.getElementById("newURL").value = "";
    }
    function removeCard(id){
        showLoadScreen();
        $.ajax({url:"cardsHandler.php?fn=deleteCard&param=" + id,type:"POST",success:function(result){
            $("#renderTable").html(result);
            hideLoadScreen();
        }});
    }
    function refreshData(){
      showLoadScreen();
      $.ajax({url:"cardsHandler.php?fn=render",type:"POST",success:function(result){
        $("#renderTable").html(result);
        hideLoadScreen();
      }});
    }
    function showLoadScreen(){
        $("#loadScreen").css("width","100%");
        $("#loadScreen").css("height","100%");
        $("#loadScreen").css("z-index","100");
    }
    function hideLoadScreen(){
        $("#loadScreen").css("width","0px");
        $("#loadScreen").css("height","0px");
        $("#loadScreen").css("z-index","0");
    }
    function hoverPic(pic){
        $("#hoverDiv").css("background","url('"+pic+"') no-repeat scroll 0 0 / 100% auto");
        $("#hoverDiv").css("display","block");
    }
    function hidePic(){
        $("#hoverDiv").css("display","none");
    }

</script>
<div id="loadScreen"></div>

<div id="content">
    <div id="hoverDiv"></div>
    <div id="renderTable"><?php $cardFunction->render(); ?></div>
    <button class="addButton" onclick="addData()">Werte Übertragen</button>
    <button class="addButton" onclick="refreshData()">REFRESH</button>

    <label for="newURL">
        Neuen Link hinzufügen:
    </label>
    <input type="text" id="newURL">
    <button onclick="addCard()">Hinzufügen</button>
</div>