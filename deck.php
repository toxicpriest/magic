<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 11.11.13
 * Time: 13:19
 * To change this template use File | Settings | File Templates.
 */ 
class deck {
    public $deckId;
    public $deckname="";
    public $deck=array();

    public function addCards($decklist){
        foreach($decklist as $cards){
            $this->addCard($cards["card_id"],$cards["amount"]);
        }
        echo "Cards added";
    }
    public function addCard($cardID,$ammount=1){
       $newCard = new card();
       $newCard->load($cardID);
       if(in_array($cardID,$this->deck)){
           if($this->deck[$cardID]+$ammount <= 4 || strpos($newCard->type,'Basic Land') !== false){
               $this->deck[$cardID]= $this->deck[$cardID]+$ammount;
           }
       }
       else{

           if($ammount<=4 || strpos($newCard->type,'Basic Land') !== false){
               $this->deck[$cardID]= $ammount;
           }
       }
    }
    public function loadDeck(){

    }
    public function deleteDeck(){

    }
    public function shuffle(){

    }
    public function search(){

    }
    public function save(){

    }

    public function lookAtTopCards($n){}

}
