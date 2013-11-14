<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 13.11.13
 * Time: 10:32
 * To change this template use File | Settings | File Templates.
 */
if (isset($_POST['nick']) && isset($_POST['password'])) {
    $nick = $_POST['nick'];
    $password = $_POST['password'];
    $player->login($nick, $password);
    if ($player->playerName) {
        $_SESSION['nick'] = $nick;
        $_SESSION['password'] = $password;
        $_SESSION['id']= $player->playerId;
    }
}
elseif (isset($_SESSION['nick']) && isset($_SESSION['password'])) {
    $player->login($_SESSION['nick'], $_SESSION['password']);
}

?>
<div id="lobby">
    <div id="dialog" title="Challenge?">
    </div>
    <div class="textfield_chat">
        <?php if ($player->playerName == null || $player->playerName == "") { ?>
            <div id="login">
                <form action="index.php" method="POST">
                    <input type="text" name="nick">
                    <input type="password" name="password">
                    <input type="submit" name="login" value="Login">
                </form>
            </div>
        <?php }
        else { ?>
            <table border="0">
                <tr>
                    <th>
                        <span style="font-weight:normal;">Nachricht:</span>
                    </th>
                    <td>
                        <input type="text" name="eintrag" value="" id="textbox">&nbsp;<input type="button" name="eintragen" value="Senden!" id="button" onClick="loadXMLDoc('add','<?php echo $player->playerName; ?>');">
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <?php
    echo '<div class="chat">
    <div id="ajax_chat">';
    $db = new db();
    $result = mysql_query("SELECT chat_msg,player_name FROM chat order by id desc limit 20 ;", $db->db);
    while ($row = mysql_fetch_object($result)) {
        echo $row->player_name . ' :' . $row->chat_msg . '<br />';
    }
    ?></div>
</div>
<div class="user">
    <div id="user_list">
    </div>
</div>
</div>





