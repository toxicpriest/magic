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
    public function loadDeck($deckid){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");
        $sqlSelect="Select deck_name from decks where deck_id='".$deckid."'";
    }
    public function deleteDeck($deckId=null){
        $deckId=$this->deckId;

    }
    public function shuffle(){

    }
    public function search(){

    }
    public function save($title=null){
        $playerid=1;
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");

        if($this->deckId == "" || $this->deckId == null){
            $sqlInsert = "INSERT INTO decks(deck_name,player_id) VALUES('".$title."','".$playerid."');";
            mysql_query($sqlInsert, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");

        }
        else{
            $sqlDelete = "Delete from deck_contents where deck_id='".$this->deckId."';";
            mysql_query($sqlDelete, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
        }
        foreach($this->deck as $cardID => $ammount){
            $sqlInsert = "INSERT INTO deck_contents(card_stock,deck_id,card_id) VALUES('".$ammount."','1','".$cardID."');";
            mysql_query($sqlInsert, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
        }
    }

    public function lookAtTopCards($n){}

}
