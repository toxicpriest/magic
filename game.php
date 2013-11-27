<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 11.11.13
 * Time: 10:36
 * To change this template use File | Settings | File Templates.
 */
class game
{
    public $gameid;
    public $player1;
    public $player2;
    public $library_1;
    public $library_2;
    public $state;

    function __construct($player1_id = null, $player2_id = null, $gameid = null)
    {
        if ($player1_id != null && $player2_id != null) {
            $db = new db();
            if ($gameid == null) {
                $gameid = uniqid();
            }
            $db->execute("INSERT INTO game(game_id,player_id_1,player_id_2,game_state) VALUES('" . $gameid . "','" . $player1_id . "','" . $player2_id . "','start');");
            $this->gameid = $gameid;
        }
        elseif ($gameid != null) {
            $this->gameid = $gameid;
        }
    }

    public function rollDice($sides = 20)
    {
        return rand(1, $sides);
    }

    public function checkCoinToss($player_number){
        $db = new db();
                $sqlSelect = "SELECT game_state FROM game where game_id='" . $this->gameid . "';";
                $result = mysql_query($sqlSelect, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!");
                if ($result) {
                    while ($row = mysql_fetch_object($result)) {
                        $game_state = $row->game_state;
                    }
                }
        if($game_state == "begin_player1" || $game_state == "begin_player2"){
            if(($player_number == 1 && $game_state == "begin_player1") || ($player_number == 2 && $game_state == "begin_player2")){
                return true;
            }
            else{
                return false;
            }
        }
    }
    public function coinToss()
    {
        $rand = rand(1, 2);
        $db = new db();
        $sqlSelect = "SELECT game_state FROM game where game_id='" . $this->gameid . "';";
        $result = mysql_query($sqlSelect, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!");
        if ($result) {
            while ($row = mysql_fetch_object($result)) {
                $game_state = $row->game_state;
            }
        }
        if ($game_state != "begin_player1" && $game_state != "begin_player2") {
            if ($rand == 1) {
                $db->execute('UPDATE game SET game_state="begin_player1" where game_id="' . $this->gameid . '";');
            }
            if ($rand == 2) {
                $db->execute('UPDATE game SET game_state="begin_player2" where game_id="' . $this->gameid . '";');
            }
        }
    }

    public function addPlayer1($player1)
    {
        $this->player1 = $player1;
        $this->library_1 = new library($player1->selectedDeck->deckId);
    }

    public function addPlayer2($player2)
    {
        $this->player2 = $player2;
        $this->library_2 = new library($player2->selectedDeck->deckId);
    }

    public function addCard($player_id, $card_id, $zone)
    {
        $db = new db();
    }

    public function moveCard($id, $zone)
    {
        $db = new db();
    }

    public function loadLibrary($playerId)
    {
        $db = new db();
        $sqlSelect = "SELECT object FROM cards_in_game where player_id='" . $playerId . "' and game_id='" . $this->gameid . "' and zone='LIBRARY'";
        $result = mysql_query($sqlSelect, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        if ($result) {
            while ($row = mysql_fetch_object($result)) {
                $library = $row->object;
            }
        }
        return $library;
    }

    public function saveData($dataType, $data, $playerID)
    {
        $db = new db();
        $sqlSelect = "SELECT object FROM cards_in_game where player_id='" . $playerID . "' and game_id='" . $this->gameid . "' and zone='" . $dataType . "'";
        $result = mysql_query($sqlSelect, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        if (mysql_num_rows($result)) {
            $db->execute('UPDATE cards_in_game SET object="' . base64_encode(serialize($data)) . '" where player_id="' . $playerID . '" and game_id="' . $this->gameid . '" and zone="' . $dataType . '";');
        }
        else {
            $db->execute('INSERT INTO cards_in_game(game_id,object,player_id,zone) VALUES ("' . $this->gameid . '","' . base64_encode(serialize($data)) . '","' . $playerID . '","' . $dataType . '");');
        }
    }

    public function getGraveyard()
    {
    }

    public function getBattlefield()
    {
    }

    public function getExile()
    {
    }
}
