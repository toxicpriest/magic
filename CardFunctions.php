<?php

class CardFunctions
{
    public $cardsInfo =array();


    function __construct(){
       $this->refreshData();
    }
    public function refreshData()
    {
        ini_set("allow_url_fopen",true);
        ini_set("user_agent","Price_Reader");


        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_preise", $con) or die("Konnte die Datenbank nicht selecten!");

        $showStatement = "SELECT * FROM card;";
        $result = mysql_query($showStatement, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");
        while($row = mysql_fetch_object($result)){
            $i=$row->id;
            $this->cardsInfo[$i]["id"]=$row->id;
            $this->cardsInfo[$i]["Name"]=$row->name;
            $this->cardsInfo[$i]["Edition"]=$row->edition;
            $this->cardsInfo[$i]["OldMinmalPrice"]=$row->pricelowest;
            $this->cardsInfo[$i]["OldAveragePrice"]=$row->priceaverage;
            $this->cardsInfo[$i]["OldFoilPrice"]=$row->pricefoil;
            $this->cardsInfo[$i]["OldTraderPrice"]=$row->pricetrader;
            $quellcodeMKM = file ($row->urlmkm);
            $strippedCodeMKM=(strip_tags($quellcodeMKM[46]));
            $pricePregmatch="/[0-9]*.,[0-9]*./";
            preg_match_all($pricePregmatch,$strippedCodeMKM,$price);
            $picPregmatch='/<span class="prodImage"><img src=".(.*)" alt=".*<span class="prodDetails">/';
            preg_match($picPregmatch, $quellcodeMKM[46], $pic);
            $this->cardsInfo[$i]["MinmalPrice"]=str_replace(",",".",$price[0][0]);
            $this->cardsInfo[$i]["AveragePrice"]=str_replace(",",".",$price[0][1]);
            $this->cardsInfo[$i]["FoilPrice"]=str_replace(",",".",$price[0][2]);
            $this->cardsInfo[$i]["BildLink"]="<a href=".$row->urlmkm."><img src='https://www.magickartenmarkt.de".$pic[1]."' width='60px'></a>";

        }
    }

    public function getTable(){
        $table= "<table id='cardTable'>
        <tr>
        <th>Name</th>
        <th>Edition</th>
        <th>Alter Minimalpreis</th>
        <th>Alter Durchschnittspreis</th>
        <th>Alter Preis für Foils</th>
        <th>Alter Preis Trader</th>
        <th>Aktueller Minimalpreis</th>
        <th>Aktueller Durchschnittspreis</th>
        <th>Aktueller Preis für Foils</th>
        <th>Aktueller Trader Preis</th>
        <th>Abbildung</th>
        </tr>";
        $iTable=0;
        foreach($this->cardsInfo as $cardInfo){
            $iTable++;
            $table .="<tr class='row_".($iTable%2)."'>
            <td>".$cardInfo["Name"]."</td>
            <td>".$cardInfo["Edition"]."</td>
            <td>".$cardInfo["OldMinmalPrice"]."</td>
            <td>".$cardInfo["OldAveragePrice"]."</td>
            <td>".$cardInfo["OldFoilPrice"]."</td>
            <td>".$cardInfo["OldTraderPrice"]."</td>

            <td>".$cardInfo["MinmalPrice"]."</td>
            <td>".$cardInfo["AveragePrice"]."</td>
            <td>".$cardInfo["FoilPrice"]."</td>
            <td>Rattenpreis</td>
            <td>".$cardInfo["BildLink"]."</td>
            </tr>";
        }
        $table .="</table>";
        return $table;
    }

    function addNewPrices(){
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

    public function render(){
        echo $this->getTable();
    }
}

?>

