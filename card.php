<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 11.11.13
 * Time: 15:40
 * To change this template use File | Settings | File Templates.
 */ 
class card {
    public $name="";
    public $mana="";
    public $type="";
    public $picture="";

    public function load($id){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");

        $sql="Select * from cards where card_id='".$id."'";
        $result = mysql_query($sql, $con) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        while($row = mysql_fetch_object($result)){
                   $this->name=$row->card_name;
                   $this->mana=$row->card_mana;
                   $this->type=$row->card_type;
                   $this->picture=$row->card_picture;
        }

    }
}
