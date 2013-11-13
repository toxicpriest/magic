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

    function __construct($player1,$player2){
      $db = new db();
      $gameid = uniqid();
      $db->execute("INSERT INTO game(game_id,player_id_1,player_id_2,game_state) VALUES('".$gameid."','".$player1->playerId."','".$player2->playerId."','start');");
      $this->gameid = $gameid;
      $this->player1 = $player1;
      $this->player2 = $player2;
      $this->library_1=new library($player1->selectedDeck->deckId);
      $this->library_2=new library($player2->selectedDeck->deckId);
      $this->startGame();
    }
    public function startGame(){
        $this->library_1->shuffleLibrary();
        $this->library_2->shuffleLibrary();
        if($this->player1->rollDice() > $this->player1->rollDice()){
            $db = new db();
            $db->execute("UPDATE game SET game_state='p1_start' WHERE game_id='".$this->gameid."';");
        }else{
            $db = new db();
            $db->execute("UPDATE game SET game_state='p2_start' WHERE game_id='".$this->gameid."';");
        }
        $this->player1->mulligan($this->library_1);
        $this->player2->mulligan($this->library_2);

        $db = new db();
        $db->execute('INSERT INTO cards_in_game(game_id,object,player_id,zone) VALUES ("'.$this->gameid.'","'.base64_encode(serialize($this->library_1)).'","'.$this->player1->playerId.'","LIBRARY");');
        $db->execute('INSERT INTO cards_in_game(game_id,object,player_id,zone) VALUES ("'.$this->gameid.'","'.base64_encode(serialize($this->library_2)).'","'.$this->player2->playerId.'","LIBRARY");');

    }
    public function addCard($player_id,$card_id,$zone){
       $db = new db();
    }
    public function moveCard($id,$zone){
       $db = new db();
    }
    public function loadLibrary($playerId){
        $db = new db();
        $sqlSelect = "SELECT object FROM cards_in_game where player_id='".$playerId."' and game_id='".$this->gameid."' and zone='LIBRARY'";
        $result = mysql_query($sqlSelect, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        if($result){
            while($row = mysql_fetch_object($result)){
                       $library=$row->object;
            }
        }
        return $library;
    }
    public function getGraveyard(){
    }
    public function getBattlefield(){
    }
    public function getExile(){
    }
}
