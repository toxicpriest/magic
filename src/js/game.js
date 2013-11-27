/**
 * Created with IntelliJ IDEA.
 * User: mbi
 * Date: 19.11.13
 * Time: 10:12
 * To change this template use File | Settings | File Templates.
 */
function checkGameState() {
    $.ajax({
        type: "POST",
        url: "db_handler.php",
        data: {  action: "check_gamestate"}

    })
        .done(function (returnValue) {
            if (returnValue != "" && returnValue != null) {
                var obj = $.parseJSON(returnValue);
                openDialog(obj.msg, "answerInvite", obj.enemyID, obj.playerID, '', obj.actionID);
            }
        });
}
setInterval("checkInvites()", 3500);