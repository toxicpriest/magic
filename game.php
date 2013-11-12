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
    public $library_player1;
    public $library_player2;

    function __construct($player1,$player2,$deckId1,$deckId2){
      $db = new db();
      $gameid = uniqid();
      $db->execute("INSERT INTO game(game_id,player_id_1,player_id_1,game_state) VALUES('".$gameid."','".$player1->playerId."','".$player2->playerId."','start');");
      $this->gameid = $gameid;
      $this->player1 = $player1;
      $this->player2 = $player2;
      $library1=new library();
      $this->library_player1=$library1->buildLibrary($player1->selectedDeck->deckId);
      $library2=new library();
      $this->library_player2=$library2->buildLibrary($player2->selectedDeck->deckId);
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
        $db=new db();

    }
    public function getBattlefield(){

    }
    public function getExile(){

    }

}
