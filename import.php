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
                                if($valName=="Picture"){
                                  $endPos=strpos($value,".jpg")+4;
                                  if($endPos!=false){
                                    $value=substr($value,0,$endPos);
                                  }
                                }
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
        $db= new db();
        $db->execute("DELETE FROM Cards;");

        foreach($this->CardDATA as $cardData){
            $db->execute("INSERT INTO Cards(card_id,card_name, card_mana, card_type, card_picture) VALUES(\"".md5($cardData['Name'])."\",\"".$cardData['Name']."\",\"".$cardData['ManaCost']."\",\"".$cardData['Types']."\",\"".$cardData['Picture']."\");");
        }
    }
    public function doImport(){
    $this->forgeImport();
    $this->importIntoDB();
    echo "import DONE<br>";
    }
}
