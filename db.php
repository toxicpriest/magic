<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 12.11.13
 * Time: 12:59
 * To change this template use File | Settings | File Templates.
 */ 
class db {
    public $db;

    function __construct(){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");
        $this->db=$con;
    }
    public function execute($sql){
        mysql_query($sql, $this->db) or die("SQL-Statement konnte nicht abgesetzt werden!<br>Error in :".$sql);
    }
}
