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

    public function addCard($player_id,$card_id,$zone){
       $db = new db();
       $db->execute("INSERT INTO cards_in_game(game_id,card_id,player_id,zone) VALUES('".$this->gameid."','".$card_id."','".$player_id."','".$zone."');");
    }
}
