<?php
/**
 * Created by IntelliJ IDEA.
 * User: mbi
 * Date: 11.11.13
 * Time: 10:36
 * To change this template use File | Settings | File Templates.
 */ 
class import{

    public $CardDATA= array();

    public function forgeImport($verzeichnis="./cardsfolder"){
        $handle =  opendir($verzeichnis);

            while ($datei = readdir($handle))

            {

                if ($datei != "." && $datei != ".." &&  strpos($datei, ".") !== 0)

                {
                    if (is_dir($verzeichnis."/".$datei))

                    {
                       $newFolder = $verzeichnis."/".$datei;
                       $this->forgeImport($newFolder);
                    }

                    else

                    {
                        $file=$verzeichnis."/".$datei;
                        $cardData = file("$file");
                        $cardDataArray=array();
                        foreach($cardData as $dataline){
                            $valName=substr($dataline,0,strpos($dataline,":"));
                            $value=str_replace('"','\"',str_replace("\n","",str_replace("\r","",substr($dataline,strpos($dataline,":")+1,strlen($dataline)))));
                            if($valName == "SVar"){
                                $valName=substr($value,0,strpos($value,":"));
                                $value = substr($value,strpos($value,":")+1,strlen($value));
                            }
                            $cardDataArray[$valName] = $value;
                        }
                        if(!isset($cardDataArray["Picture"])){
                            $cardDataArray["Picture"]="";
                        }
                        $this->CardDATA[] = $cardDataArray;
                        //echo $file.'<br />';
                        }
                }

            }
            closedir($handle);
        }
    public function importIntoDB(){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_wars", $con) or die("Konnte die Datenbank nicht selecten!1");

        $sqlDelete = "DELETE FROM Cards;";
        mysql_query($sqlDelete, $con) or die("SQL-Statement konnte nicht abgesetzt werden!2");

        foreach($this->CardDATA as $cardData){
            $sqlInsert = "INSERT INTO Cards(card_id,card_name, card_mana, card_type, card_picture) VALUES(\"".md5($cardData['Name'])."\",\"".$cardData['Name']."\",\"".$cardData['ManaCost']."\",\"".$cardData['Types']."\",\"".$cardData['Picture']."\");";
            mysql_query($sqlInsert, $con)
            or die("SQL-Statement konnte nicht abgesetzt werden!3");
        }
    }
    public function doImport(){
    $this->forgeImport();
    $this->importIntoDB();
    echo "import DONE<br>";
    }
}
