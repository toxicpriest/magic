function openDialog(msg, type, enemyID, playerID, gameid, actionID) {
    var innerHtml = getInnerHtml(msg, type, enemyID, playerID, actionID);
    $("#dialog").html(innerHtml);
    $("#dialog").css("display", "block");
}
function getInnerHtml(msg, type, enemyID, playerID, actionID) {
    var InnerHtml = "<span id='question'>" + msg + "</span>";

    if (type == "answerInvite") {
        InnerHtml += "<button onclick='dialogAction(\"" + type + "\"," + playerID + "," + enemyID + "," + actionID + ",\"yes\");'>Yes</button><button onclick='dialogAction(\"" + type + "\"," + playerID + "," + enemyID + "," + actionID + ",\"no\");'>No</button>";
    }
    else if (type == "selectDeck") {

    }
    else if (type == "yes_no") {
        InnerHtml += "<button onclick='dialogAction(\"yes\");'>Yes</button><button onclick='dialogAction(\"no\");'>No</button>";
    }
    else if (type == "user_menu") {
        InnerHtml += "<button onclick='dialogAction(\"invite\"," + playerID + "," + enemyID + ");'>Invite</button><button onclick='dialogAction(\"pn\");'>Msg</button><button onclick='dialogAction(\"no\");'>Close</button>";
    }
    return InnerHtml;
}
function dialogAction(action, playerID, enemyID, actionID, answer) {
    $("#dialog").css("display", "none");
    if (action == "invite") {
        invitePlayer(playerID, enemyID);
    }
    if (action == "answerInvite") {
        answerPlayer(playerID, enemyID, actionID, answer);
    }
}
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
