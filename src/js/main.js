function loadUserList() {
$.ajax({
type: "POST",
url: "db_handler.php?action=userlist",
data: {  action: "userlist"}
})
.done(function( user ) {
$("#user_list").html(user);
});
  }
  setInterval("loadUserList('reload')",2000);

function loadXMLDoc(action, player) {
      if (action == "add") {
          var msg=document.getElementById("textbox").value;
          if(msg ==""){
              alert("Bitte Nachricht eingeben -.-")
          }
          else{
              $.ajax({
              type: "POST",
              url: "db_handler.php",
              data: { action: action, player: player, msg: msg}
              })
              .done(function( chatData ) {
              $("#ajax_chat").html(chatData);
              });
          }
      }
      else {
          $.ajax({
                    type: "POST",
                    url: "db_handler.php",
                    data: { action: action}
                    })
                    .done(function( chatData ) {
                    $("#ajax_chat").html(chatData);
                    });
      }
  }
  setInterval("loadXMLDoc('reload')",3000);
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
  function hoverPic(pic,event){
      $("#hoverDiv").css("background","url('"+pic+"') no-repeat scroll 0 0 / 100% auto");
      $("#hoverDiv").css("display","block");
      $("#hoverDiv").css("margin-top",event.pageY-100);
      $("#hoverDiv").css("margin-left",event.pageX-350);
  }
  function hidePic(){
      $("#hoverDiv").css("display","none");
  }
function deleteAll(){
    showLoadScreen();
    $.ajax({url:"cardsHandler.php?fn=deleteAllCards",type:"POST",success:function(result){
        $("#renderTable").html(result);
        hideLoadScreen();
    }});
}
$(document).ready(function () {

	$('tbody tr').hover(function() {
	  $(this).addClass('odd');
	}, function() {
	  $(this).removeClass('odd');
	});

});