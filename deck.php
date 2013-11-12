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
    public function load($deckid,$deckname=null,$playerid=null){
        $db = new db();
        if($deckname!=null){
            $sqlSelect="Select * from decks where deck_name='".$deckname."' and player_id='".$playerid."'";
        }
        else{
            $sqlSelect="Select * from decks where deck_id='".$deckid."'";
        }
        $result = mysql_query($sqlSelect, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        while($row = mysql_fetch_object($result)){
                   $this->deckId=$row->deck_id;
                   $this->deckname=$row->deck_name;
        }
        $sqlSelectContents="Select * from deck_contents where deck_id='".$this->deckId."'";
        $result2 = mysql_query($sqlSelectContents, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        $this->deck = array();
        while($row = mysql_fetch_object($result2)){
            $this->deck[$row->card_id]=$row->card_stock;
        }
    }
    public function delete($deckId=null){

        if(!$deckId){
            if(count($this->deckId) > 0){
                $deckId=$this->deckId;
                $db=new db();
                $db->execute("Delete from decks where deck_id='".$deckId."';");
                $db->execute("Delete from deck_contents where deck_id='".$deckId."';");
            }
        }
        else{
            $db = new db();
            $db->execute("Delete from decks where deck_id='".$deckId."';");
            $db->execute("Delete from deck_contents where deck_id='".$deckId."';");
        }
    }

    public function save($title=null,$playerid){
        $db = new db();
        $uniqid = uniqid();

        if($this->deckId == "" || $this->deckId == null){
            $db->execute("INSERT INTO decks(deck_id,deck_name,player_id) VALUES('".$uniqid."','".$title."','".$playerid."');");
            $this->deckId=$uniqid;
        }
        else{
            $db->execute("Delete from deck_contents where deck_id='".$this->deckId."';");
        }
        foreach($this->deck as $cardID => $ammount){
            $db->execute("INSERT INTO deck_contents(card_stock,deck_id,card_id) VALUES('".$ammount."','".$uniqid."','".$cardID."');");
        }
    }
}
