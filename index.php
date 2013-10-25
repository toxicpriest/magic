

<?php

include_once "KartenController.php";
include_once "includes/header.php";

echo "Supergay";
$cardFunction = new CardFunctions();
?>
<script>
    $("#button").click(function(){
      $.ajax({url:"insertD",success:function(result){
        $("#div1").html(result);
      }});
    }); >
</script>



<div><?php $cardFunction->render(); ?></div>



