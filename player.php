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

   public function load($name,$password){
       $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
       mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");
       $sqlSelect="Select * from player where player_nick='".$name."' and player_password='".$password."'";
       $result = mysql_query($sqlSelect, $con) or die("SQL-Statement konnte nicht abgesetzt werden!2");
       if($result){
           while($row = mysql_fetch_object($result)){
                      $this->playerName=$row->player_nick;
                      $this->playerId=$row->player_id;
           }
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

}
