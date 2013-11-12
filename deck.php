<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 11.11.13
 * Time: 13:19
 * To change this template use File | Settings | File Templates.
 */ 
class deck {
    public $deckId="";
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
    public function load($deckid){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");
        $sqlSelect="Select * from decks where deck_id='".$deckid."'";
        $result = mysql_query($sqlSelect, $con) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        while($row = mysql_fetch_object($result)){
                   $this->deckId=$row->deck_id;
                   $this->deckname=$row->deck_name;
        }
        $sqlSelectContents="Select * from deck_contents where deck_id='".$deckid."'";
        $result2 = mysql_query($sqlSelectContents, $con) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        $this->deck = array();
        while($row = mysql_fetch_object($result2)){
            $this->deck[$row->card_id]=$row->card_stock;
        }
    }
    public function delete($deckId=null){

        if(!$deckId){
            if(count($this->deckId) > 0){
                $deckId=$this->deckId;
                $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
                mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");
                $sqlDelete="Delete from decks where deck_id='".$deckId."'";
                mysql_query($sqlDelete, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
                $sqlDelete2="Delete from deck_contents where deck_id='".$deckId."'";
                mysql_query($sqlDelete2, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
            }
        }
        else{
            $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
            mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");
            $sqlDelete="Delete from decks where deck_id='".$deckId."'";
            mysql_query($sqlDelete, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
            $sqlDelete2="Delete from deck_contents where deck_id='".$deckId."'";
            mysql_query($sqlDelete2, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
        }
    }

    public function save($title=null,$playerid){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");
        $uniqid = uniqid();

        if($this->deckId == "" || $this->deckId == null){
            $sqlInsert = "INSERT INTO decks(deck_id,deck_name,player_id) VALUES('".$uniqid."','".$title."','".$playerid."');";
            mysql_query($sqlInsert, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
            $this->deckId=$uniqid;
        }
        else{
            $sqlDelete = "Delete from deck_contents where deck_id='".$this->deckId."';";
            mysql_query($sqlDelete, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
        }
        foreach($this->deck as $cardID => $ammount){
            $sqlInsert = "INSERT INTO deck_contents(card_stock,deck_id,card_id) VALUES('".$ammount."','".$uniqid."','".$cardID."');";
            mysql_query($sqlInsert, $con) or die("SQL-Statement konnte nicht abgesetzt werden!3");
        }
    }
}
