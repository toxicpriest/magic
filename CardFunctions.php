<?php

class CardFunctions
{
    public $cardsInfo =array();


    function __construct(){
    }
    public function refreshData()
    {
        ini_set("allow_url_fopen",true);
        ini_set("user_agent","user_agent','MSIE 4\.0b2;");


        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_preise", $con) or die("Konnte die Datenbank nicht selecten!");

        $showStatement = "SELECT * FROM card;";
        $result = mysql_query($showStatement, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");
        while($row = mysql_fetch_object($result)){
            $i=$row->id;
            $this->cardsInfo[$i]["id"]=$row->id;
            $this->cardsInfo[$i]["Name"]=$row->cardname;
            $this->cardsInfo[$i]["Edition"]=$row->edition;
            $this->cardsInfo[$i]["OldMinmalPrice"]=$row->pricelowest;
            $this->cardsInfo[$i]["OldAveragePrice"]=$row->priceaverage;
            $this->cardsInfo[$i]["OldFoilPrice"]=$row->pricefoil;
            $quellcodeMKM = file ($row->urlmkm);
            $strippedCodeMKM=(strip_tags($quellcodeMKM[46]));
            $pricePregmatch="/[0-9]*.,[0-9]+./";
            preg_match_all($pricePregmatch,$strippedCodeMKM,$price);
            $this->cardsInfo[$i]["MinmalPrice"]=trim(str_replace(",",".",$price[0][0]));
            $this->cardsInfo[$i]["AveragePrice"]=trim(str_replace(",",".",$price[0][1]));
            if(isset($price[0][2])){
                $this->cardsInfo[$i]["FoilPrice"]=trim(str_replace(",",".",$price[0][2]));
            }
            else{
                $this->cardsInfo[$i]["FoilPrice"]=0;
            }

            $this->cardsInfo[$i]["firstGerman"] = "/";
            for($n = 61; $n < sizeof($quellcodeMKM) ; $n++){
                preg_match("/Artikelstandort: Deutschland/", $quellcodeMKM[$n], $matches);
                if(!empty($matches)){
                    preg_match("/Artikelstandort: Deutschland/", $quellcodeMKM[$n], $matches);

                    preg_match("/[0-9]{1,3},[0-9]{2}/", $quellcodeMKM[$n+1], $priceMatches);
                    $this->cardsInfo[$i]["firstGerman"] = str_replace(",", ".", $priceMatches[0]);
                    break;
                }
            }
            for($n = 61; $n < sizeof($quellcodeMKM) ; $n++){
                           preg_match("/Artikelstandort: Deutschland/", $quellcodeMKM[$n], $matches);
                           if(!empty($matches)){
                               preg_match("/showMsgBox\('Near Mint'\)/", $quellcodeMKM[$n+1], $match);
                               if(!empty($match)){
                                   preg_match("/[0-9]{1,3},[0-9]{2}/", $quellcodeMKM[$n+1], $priceMatch);
                                   $this->cardsInfo[$i]["firstGermanNearMint"] = str_replace(",", ".", $priceMatch[0]);
                                   break;
                               }

                           }
                       }
            $this->cardsInfo[$i]["BildLink"]="<div class='hoverPictures'><a href=".$row->urlmkm."><img src='".str_replace(" ","_","./pictures/".str_replace("'", "´", $row->cardname)."_".$row->edition.".jpg")."' width='60px' onMouseLeave=\"hidePic()\" onMouseOver=\"hoverPic('".str_replace(" ","_","./pictures/".str_replace("'", "´", $row->cardname)."_".$row->edition.".jpg")."')\"></a></div>";

        }
    }

    public function getTable(){
        $table= "<table id='cardTable' border='3'>
        <tr>
        <th width='200px'>Name</th>
        <th>Edition</th>
        <th>Alter Min.Preis</th>
        <th>Alter Ø-Preis</th>
        <th>Alter Foil-Preis</th>
        <th>Min.Preis</th>
        <th>Ø-Preis</th>
        <th>Foil-Preis</th>
        <th>1. Dt. Karte</th>
        <th>1. Dt. Karte (NM)</th>
        <th>Abbildung</th>
        <th>Löschen</th>
        <th>info</th>
        </tr>";
        $iTable=0;
        foreach($this->cardsInfo as $cardInfo){
            if($cardInfo["OldMinmalPrice"] >$cardInfo["MinmalPrice"]){
                $info="<span class='tdinfo_lower'>!</span>";
            }
            elseif($cardInfo["OldMinmalPrice"] < $cardInfo["MinmalPrice"]){
                $info="<span class='tdinfo_higher'>!</span>";
            }
            elseif($cardInfo["OldMinmalPrice"] == $cardInfo["MinmalPrice"]){
                $info="<span class='tdinfo_equal'>=</span>";
            }
            $iTable++;
            $table .="<tr class='row_".($iTable%2)."'>
            <td>".$cardInfo["Name"]."</td>
            <td>".$cardInfo["Edition"]."</td>
            <td>".$cardInfo["OldMinmalPrice"]." €</td>
            <td>".$cardInfo["OldAveragePrice"]." €</td>
            <td>".$cardInfo["OldFoilPrice"]." €</td>
            <td>".$cardInfo["MinmalPrice"]." €</td>
            <td>".$cardInfo["AveragePrice"]." €</td>
            <td>".$cardInfo["FoilPrice"]." €</td>
            <td>".$cardInfo["firstGerman"]." €</td>
            <td>".$cardInfo["firstGermanNearMint"]." €</td>
            <td>".$cardInfo["BildLink"]."</td>
            <td><img src=\"./src/img/del.png\" onclick=\"removeCard(".$cardInfo["id"].")\" class='removeCard'></img></td>
            <td>".$info."</td>
            </tr>";
        }
        $table .="</table>";
        return $table;
    }

    function addNewPrices(){

        $this->refreshData();
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
                mysql_select_db("mtg_preise", $con) or die("Konnte die Datenbank nicht selecten!");

        foreach($this->cardsInfo as $cards){
            $addStatement = "Update card set pricelowest='".$cards["MinmalPrice"]."', priceaverage='".$cards["AveragePrice"]."', pricefoil='".$cards["FoilPrice"]."' where id = ".$cards["id"].";";
            $result = mysql_query($addStatement, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");
            $this->cardsInfo[$cards["id"]]["OldMinmalPrice"]=$cards["MinmalPrice"];
            $this->cardsInfo[$cards["id"]]["OldAveragePrice"]=$cards["AveragePrice"];
            $this->cardsInfo[$cards["id"]]["OldFoilPrice"]=$cards["FoilPrice"];
        }
        $this->render();
    }

    function addNewURL($urlmkm){
        ini_set("allow_url_fopen",true);
        ini_set("user_agent","Price_Reader");

        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
                mysql_select_db("mtg_preise", $con) or die("Konnte die Datenbank nicht selecten!");

        //check if the card is already on the list
        $checkSQL = "SELECT urlmkm FROM card where urlmkm = \"$urlmkm\";";
        $checkResult = mysql_query($checkSQL, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");
        $resultURL = mysql_fetch_assoc($checkResult);
        if(false == $resultURL){

            $quellcodeMKM = file ($urlmkm);

            //Getting the cards name
            $patternName = "/<title>(.*?)\(/";
            preg_match($patternName, $quellcodeMKM[4], $nameArr);
            $name = $nameArr[1];

            //Getting the cards edition
            $patternEdition ="/<title>.*\((.*)\)/";
            preg_match($patternEdition, $quellcodeMKM[4], $editionArr);
            $edition = $editionArr[1];

            //Getting the mkm prices
            $strippedCodeMKM=(strip_tags($quellcodeMKM[46]));
            $pricePregmatch="/[0-9]*.,[0-9]*./";
            preg_match_all($pricePregmatch,$strippedCodeMKM,$price);
            $minimalPrice=str_replace(",",".",$price[0][0]);
            $averagePrice=str_replace(",",".",$price[0][1]);
            if(isset($price[0][2])){
              $foilPrice=str_replace(",",".",$price[0][2]);
            }
            else{
                $foilPrice=0;
            }
            $sql = "INSERT INTO card(urlmkm, edition, cardname, pricelowest, priceaverage, pricefoil) VALUES(\"$urlmkm\", \"$edition\", \"$name\", \"$minimalPrice\", \"$averagePrice\", \"$foilPrice\");";

            //Getting the picture
            $picPregmatch='/<span class="prodImage"><img src=".(.*)" alt=".*<span class="prodDetails">/';
            preg_match($picPregmatch, $quellcodeMKM[46], $pic);
            $picPath="https://www.magickartenmarkt.de".$pic[1];
            //Change blank spaces for "_" and change "'" in $name for "´"
            $picSavePath="./pictures/".str_replace(" ","_",str_replace("'", "´", $name)."_".$edition.".jpg");
            file_put_contents($picSavePath, file_get_contents($picPath));
            mysql_query($sql, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");

            $this->render();
        }
        else{
            echo "Diese Karte befindet sich bereits auf der Liste!";
        }
    }

    function deleteCard($id){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_preise", $con) or die("Konnte die Datenbank nicht selecten!");

        $sql = "DELETE FROM card WHERE id=\"$id\";";
        mysql_query($sql, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");
        $this->render();
    }

    public function render(){
        $this->refreshData();
        echo $this->getTable();
    }
}

?>

