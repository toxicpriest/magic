<?php
session_start();
include_once "db.php";
$db = new db();
$action = $_POST['action'];
$entries = "";
$users = "";
if ($action == "invite") {
    $enemyID = $_POST['enemyID'];
    $playerID = $_POST['playerID'];
    $timestamp = time();
    $db->execute("INSERT INTO actions(player_id,invited_id,timestamp,status) VALUES('" . $playerID . "','" . $enemyID . "','" . $timestamp . "','wait_for_answer')");
}
if($action== "answerPlayer"){
    $enemyID = $_POST['enemyID'];
    $playerID = $_POST['playerID'];
    $action_id = $_POST['actionID'];
    $answer = $_POST['answer'];
    if($answer == "yes"){
        $db->execute("UPDATE actions set status='rdy_for_start' where id='".$action_id."';");
    }
    elseif($answer=="no"){
        $db->execute("UPDATE actions set status='canceled_wait' where id='".$action_id."';");
    }
}
if ($action == "check_invites") {
    if (isset($_SESSION['id'])) {
        $action_id="";
        $actions = mysql_query("SELECT * FROM actions where invited_id ='" . $_SESSION['id'] . "' and status='wait_for_answer';", $db->db);
        $msg = "";
        while ($row = mysql_fetch_object($actions)) {
            $action_id=$row->id;
            $playerName = mysql_query("SELECT * FROM player where player_id ='" . $row->player_id . "';", $db->db);
            while ($row2 = mysql_fetch_object($playerName)) {
                $msg = '{"msg":"'. $row2->player_nick .' hat Sie eingeladen wollen Sie dem Spiel beitreten?","enemyID" :"'.$row->player_id.'","playerID" :"'.$_SESSION['id'].'","actionID":"'.$action_id.'"}';
            }
        }
        if($action_id != ""){
            $db->execute("UPDATE actions set status='wait_for_click' where id='".$action_id."';");
        }
        echo $msg;
    }
}
if ($action == "check_invite_state") {
    if (isset($_SESSION['id'])) {
        $action_id="";
        $state="";
        $actions = mysql_query("SELECT * FROM actions where player_id ='" . $_SESSION['id'] . "' and (status='canceled_wait' or status='rdy_for_start');", $db->db);
        $msg = "";
        while ($row = mysql_fetch_object($actions)) {
            $state=$row->status;
            $action_id=$row->id;
        }
        if($state == "canceled_wait"){
            $db->execute("UPDATE actions set status='canceled' where id='".$action_id."';");
            $msg="canceled";
        }
        elseif($state == "rdy_for_start"){
            $db->execute("UPDATE actions set status='in_game' where id='".$action_id."';");
            $msg="game";
        }
        if($action_id!=""){
            echo $msg;
        }
    }
}
if ($action == "add") {
    $msg = $_POST['msg'];
    $player = $_POST['player'];
    $db->execute("INSERT INTO chat(player_name,chat_msg) VALUES('" . $player . "','" . $msg . "')");
    $action = "reload";
}
if ($action == "userlist") {
    $result2 = mysql_query("SELECT * FROM lounge;", $db->db);
    if (isset($_SESSION['id'])) {
        while ($row = mysql_fetch_object($result2)) {
            if ($row->player_id != $_SESSION['id']) {
                $users .= '<div class="user_row enemy" id="' . $row->player_id . '"  onclick="openDialog(\'Action\',\'user_menu\',\'' . $row->player_id . '\',\'' . $_SESSION['id'] . '\');">' . $row->player_nick . ' :' . $row->player_state . '<br /></div>';
            }
            else {
                $users .= '<div class="user_row myname" id="' . $row->player_id . '">' . $row->player_nick . ' :' . $row->player_state . '<br /></div>';
            }
        }
    }
    else {
        while ($row = mysql_fetch_object($result2)) {
            $users .= '<div class="user_row" id="' . $row->player_id . '">' . $row->player_nick . ' :' . $row->player_state . '<br /></div>';
        }
    }
    echo $users;
}
if ($action == "reload") {
    $result = mysql_query("SELECT * FROM chat order by id desc limit 20 ;", $db->db);
    while ($row = mysql_fetch_object($result)) {
        $entries .= '<div>' . $row->player_name . ' :' . $row->chat_msg . '<br /></div>';
    }
    echo $entries;
}

