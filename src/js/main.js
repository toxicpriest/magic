function openDialog(msg, type, enemyID, playerID, gameid,actionID) {
    var innerHtml = getInnerHtml(msg, type, enemyID, playerID,actionID);
    $("#dialog").html(innerHtml);
    $("#dialog").css("display", "block");
}
function getInnerHtml(msg, type, enemyID, playerID,actionID) {
    var InnerHtml = "<span id='question'>" + msg + "</span>";
    if (type == "answerInvite") {
            InnerHtml += "<button onclick='dialogAction(\""+type+"\","+playerID+","+enemyID+","+actionID+",\"yes\");'>Yes</button><button onclick='dialogAction(\""+type+"\","+playerID+","+enemyID+","+actionID+",\"no\");'>No</button>";
        }
    if (type == "yes_no") {
        InnerHtml += "<button onclick='dialogAction(\"yes\");'>Yes</button><button onclick='dialogAction(\"no\");'>No</button>";
    }
    else if (type == "user_menu") {
        InnerHtml += "<button onclick='dialogAction(\"invite\","+playerID+","+enemyID+");'>Invite</button><button onclick='dialogAction(\"pn\");'>Msg</button><button onclick='dialogAction(\"no\");'>Close</button>";
    }
    return InnerHtml;
}
function dialogAction(action, playerID, enemyID,actionID,answer) {
    $("#dialog").css("display", "none");
    if (action == "invite") {
        invitePlayer(playerID,enemyID);
    }
    if (action == "answerInvite") {
        answerPlayer(playerID,enemyID,actionID,answer);
    }
}
function checkInvites() {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "check_invites"}

    })
        .done(function (returnValue) {
            if(returnValue!="" && returnValue!=null){
                var obj = $.parseJSON(returnValue);
                openDialog(obj.msg,"answerInvite",obj.enemyID,obj.playerID,'',obj.actionID);
            }
        });
}
setInterval("checkInvites('reload')", 3500);
function checkInviteState() {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "check_invite_state"}

    })
        .done(function (returnValue) {
            if(returnValue!="" && returnValue!=null){
                alert(returnValue);
            }
        });
}
setInterval("checkInviteState('reload')", 3500);
function invitePlayer(playerID,enemyID) {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "invite",playerID:playerID,enemyID:enemyID}
    })
        .done(function () {
            alert("invite sent");
        });
}
function answerPlayer(playerID,enemyID,actionID,answer) {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "answerPlayer",playerID:playerID,enemyID:enemyID,actionID:actionID,answer:answer}
    })
        .done(function () {
            alert("answered");
        });
}
function loadUserList() {
    $.ajax({
        type: "POST",
        url: "db_handler.php?action=userlist",
        data: {  action: "userlist"}
    })
        .done(function (user) {
            $("#user_list").html(user);
        });
}
setInterval("loadUserList('reload')", 2000);

function loadXMLDoc(action, player) {
    if (action == "add") {
        var msg = document.getElementById("textbox").value;
        if (msg == "") {
            alert("Bitte Nachricht eingeben -.-")
        }
        else {
            $.ajax({
                type: "POST",
                url: "db_handler.php",
                data: { action: action, player: player, msg: msg}
            })
                .done(function (chatData) {
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
            .done(function (chatData) {
                $("#ajax_chat").html(chatData);
            });
    }
}
setInterval("loadXMLDoc('reload')", 3000);
function addData() {
    showLoadScreen();
    $.ajax({url: "cardsHandler.php?fn=addNewPrices", type: "POST", success: function (result) {
        $("#renderTable").html(result);
        hideLoadScreen();
    }});
}
function addCard() {
    showLoadScreen();
    var url = document.getElementById("newURL").value;
    $.ajax({url: "cardsHandler.php?fn=addNewURL&param=" + url, type: "POST", success: function (result) {
        $("#renderTable").html(result);
        hideLoadScreen();
    }});
    document.getElementById("newURL").value = "";
}
function removeCard(id) {
    showLoadScreen();
    $.ajax({url: "cardsHandler.php?fn=deleteCard&param=" + id, type: "POST", success: function (result) {
        $("#renderTable").html(result);
        hideLoadScreen();
    }});
}
function refreshData() {
    showLoadScreen();
    $.ajax({url: "cardsHandler.php?fn=render", type: "POST", success: function (result) {
        $("#renderTable").html(result);
        hideLoadScreen();
    }});
}
function showLoadScreen() {
    $("#loadScreen").css("width", "100%");
    $("#loadScreen").css("height", "100%");
    $("#loadScreen").css("z-index", "100");
}
function hideLoadScreen() {
    $("#loadScreen").css("width", "0px");
    $("#loadScreen").css("height", "0px");
    $("#loadScreen").css("z-index", "0");
}
function hoverPic(pic, event) {
    $("#hoverDiv").css("background", "url('" + pic + "') no-repeat scroll 0 0 / 100% auto");
    $("#hoverDiv").css("display", "block");
    $("#hoverDiv").css("margin-top", event.pageY - 100);
    $("#hoverDiv").css("margin-left", event.pageX - 350);
}
function hidePic() {
    $("#hoverDiv").css("display", "none");
}
function deleteAll() {
    showLoadScreen();
    $.ajax({url: "cardsHandler.php?fn=deleteAllCards", type: "POST", success: function (result) {
        $("#renderTable").html(result);
        hideLoadScreen();
    }});
}
$(document).ready(function () {

    $('tbody tr').hover(function () {
        $(this).addClass('odd');
    }, function () {
        $(this).removeClass('odd');
    });

});
