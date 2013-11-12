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
        $test = "stop";
    }
    public function addCard($player_id,$card_id,$zone){
       $db = new db();
       $db->execute("INSERT INTO cards_in_game(game_id,card_id,player_id,zone) VALUES('".$this->gameid."','".$card_id."','".$player_id."','".$zone."');");
    }
    public function moveCard($id,$zone){
       $db = new db();
       $db->execute("UPDATE cards_in_game zone='".$zone."' WHERE id='".$id."';");
    }
    public function getGraveyard(){
    }
    public function getBattlefield(){
    }
    public function getExile(){
    }

}
