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
        $db =new db();
        $sql="Select * from cards where card_id='".$id."'";
        $result = mysql_query($sql, $db->db) or die("SQL-Statement konnte nicht abgesetzt werden!2");
        while($row = mysql_fetch_object($result)){
                   $this->name=$row->card_name;
                   $this->mana=$row->card_mana;
                   $this->type=$row->card_type;
                   $this->picture=$row->card_picture;
        }

    }
}
