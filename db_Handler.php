<?php
include_once "db.php";
$db = new db();
$action = $_POST['action'];
$entries = "";
$users ="";
if ($action == "add") {
    $msg = $_POST['msg'];
    $player = $_POST['player'];
    $db->execute("INSERT INTO chat(player_name,chat_msg) VALUES('" . $player . "','" . $msg . "')");
    $action="reload";
}
if ($action == "userlist") {
    $result2 = mysql_query("SELECT * FROM lounge;", $db->db);
    while ($row = mysql_fetch_object($result2)) {
        $users .= '<div>'.$row->player_nick . ' :' . $row->player_state . '<br /></div>';
    }
    echo $users;
}
if ($action == "reload") {
    $result = mysql_query("SELECT * FROM chat order by id desc limit 20 ;", $db->db);
    while ($row = mysql_fetch_object($result)) {
        $entries .= '<div>'.$row->player_name . ' :' . $row->chat_msg . '<br /></div>';
    }
    echo $entries;
}

