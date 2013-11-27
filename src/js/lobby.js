function checkInvites() {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "check_invites"}

    })
        .done(function (returnValue) {
            if (returnValue != "" && returnValue != null) {
                var obj = $.parseJSON(returnValue);
                openDialog(obj.msg, "answerInvite", obj.enemyID, obj.playerID, '', obj.actionID);
            }
        });
}
setInterval("checkInvites()", 3500);
function checkInviteState() {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "check_invite_state"}

    })
        .done(function (returnValue) {
            if (returnValue != "" && returnValue != null) {
                var obj = $.parseJSON(returnValue);

                if (obj.msg == "cancel") {
                    alert("The other Player has diclined ur Game!");
                }
                if (obj.msg == "game") {
                    selectDeck();
                }
            }
        });
}
setInterval("checkInviteState()", 3500);
function invitePlayer(playerID, enemyID) {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "invite", playerID: playerID, enemyID: enemyID}
    })
        .done(function () {
            alert("invite sent");
        });
}
function answerPlayer(playerID, enemyID, actionID, answer) {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "answerPlayer", playerID: playerID, enemyID: enemyID, actionID: actionID, answer: answer}
    })
        .done(function (obj) {
            if (answer == "yes" && obj.gameID != "cancel") {
                selectDeck();
            }
        });
}
function selectDeck() {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "selectDeck"}
    })
        .done(function (deckSelector) {
            openDialog(deckSelector, "selectDeck");
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
