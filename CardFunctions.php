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
        $i=0;
        while($row = mysql_fetch_object($result)){

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

            $this->cardsInfo[$i]["MinmalPrice"]=$price[0][0];
            $this->cardsInfo[$i]["AveragePrice"]=$price[0][1];
            $this->cardsInfo[$i]["FoilPrice"]=$price[0][2];
            $urlMCI="http://magiccards.info/query?q=".str_replace(" ","+",$row->name)."&v=card&s=cname";
            $quellcodeMCI = file ($urlMCI);
            $this->cardsInfo[$i]["BildLink"]="<a href=".$row->urlmkm.">".$quellcodeMCI[142]." width='60px'></a>";
            $this->cardsInfo[$i]["LegalLegacy"]=$quellcodeMCI[170];
            $this->cardsInfo[$i]["LegalModern"]=$quellcodeMCI[183];
            $i++;
        }
    }

    public function getTable(){
        $table= "<table border=\"3\">
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
        <th>Legacy legal?</th>
        <th>Modern Legal?</th>
        </tr>";
        foreach($this->cardsInfo as $cardInfo){
            $table .="<tr>
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
            <td>".$cardInfo["LegalLegacy"]."</td>
            <td>".$cardInfo["LegalModern"]."</td>
            </tr>";
        }
        $table .="</table>";
        return $table;
    }

    public function render(){
        echo $this->getTable();
    }
}

?>

