<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 12.11.13
 * Time: 11:11
 * To change this template use File | Settings | File Templates.
 */ 
class player {
   public $playerName="";
   public $playerId="";
   public $playerLife=20;
   public $handcards="";
   public $firstdraw=true;
   public $playerHand=array();
   public $selectedDeck= null;


   public function login($name,$password){
       $db = new db();
       $sqlSelect="Select * from player where player_nick='".$name."' and player_password='".$password."'";
       $result = mysql_query($sqlSelect, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!2");
       if($result){
           while($row = mysql_fetch_object($result)){
                      $this->playerName=$row->player_nick;
                      $this->playerId=$row->player_id;
           }
       }
       $db->execute("INSERT INTO lounge(player_id,player_nick,player_state) VALUES('".$this->playerId."','".$this->playerName."','active');");
   }
   public function logout(){
       if($this->playerId != ""){
           $db = new db();
           $db->execute("Delete from lounge where player_id='".$this->playerId."';");
       }

   }

   public function reduceLife($n){
       $this->playerLife=$this->playerLife-$n;
   }
   public function getLife($n){
       $this->playerLife=$this->playerLife+$n;
   }
   public function mulligan(&$library){
       if($this->firstdraw){
           for($i=1;$i<=7;$i++){
               $this->playerHand[]=$library->drawCard();
           }
           $this->firstdraw=false;
       }
       else{
           $count= count($this->playerHand)-1;
           foreach($this->playerHand as $card){
               $library->cardOnBot($card);
           }
           $library->shuffleLibrary();
           $this->playerHand=array();
           for($i=1;$i<=$count;$i++){
               $this->playerHand[]=$library->drawCard();
           }
       }
   }
   public function playCard($cardid,&$game){
       $game->addCard($this->playerId,$cardid,"battlefield");
   }
   public function discardCard($cardid,&$game){
        $game->addCard($this->playerId,$cardid,"graveyard");
   }
   public function exileCard($cardid,&$game){
        $game->addCard($this->playerId,$cardid,"exile");
   }
   public function selectDeck($deckname){
       $deck=new deck();
       $deck->load(true,$deckname,$this->playerId);
       $this->selectedDeck=$deck;
   }
   public function askPlayer($question){

       return true;
   }
   public function rollDice($n=20){
      return rand(1,$n);
   }
}
