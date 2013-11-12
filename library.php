<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 12.11.13
 * Time: 10:22
 * To change this template use File | Settings | File Templates.
 */ 
class library{
  public $library= array();

  public function buildLibrary($deckid){
  $deck = new deck();
  $deck->load($deckid);

  foreach($deck->deck as $cardID => $ammount){
      for($i=1 ;$i <= $ammount;$i++){
          $card=new card();
          $card->load($cardID);
          $this->library[]=$card;
      }
  }
      $test="test";
  }
  public function shuffleLibrary(){
      shuffle($this->library);
  }

  public function drawCard(){
      $card2draw=$this->library[0];
      array_splice($this->library,0,1);
      return $card2draw ;
  }
    public function cardOnBot($card){
        $this->library[]=$card;
    }
    public function cardOnTop($card){
        array_unshift($this->library,$card);
    }

}
