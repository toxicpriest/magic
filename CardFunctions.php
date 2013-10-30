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
            //getting Data from DB
            $i=$row->id;
            $this->cardsInfo[$i]["id"]=$row->id;
            $this->cardsInfo[$i]["Name"]=$row->cardname;
            $this->cardsInfo[$i]["Edition"]=$row->edition;
            $this->cardsInfo[$i]["OldMinimalPrice"]=$row->pricelowest;
            $this->cardsInfo[$i]["OldAveragePrice"]=$row->priceaverage;
            $this->cardsInfo[$i]["OldFoilPrice"]=$row->pricefoil;
            $this->cardsInfo[$i]["OldFirstGermanPrice"]=$row->pricefirstger;
            $this->cardsInfo[$i]["OldFirstGermanPriceNM"]=$row->pricefirstgernm;
            $this->cardsInfo[$i]["OldFirstGermanPricePS"]=$row->pricefirstgerps;

            $newData = $this->getFreshData($row->urlmkm);
            $this->cardsInfo[$i]["MinimalPrice"] = $newData['MinimalPrice'];
            $this->cardsInfo[$i]["AveragePrice"] = $newData['AveragePrice'];
            $this->cardsInfo[$i]["FoilPrice"] = $newData['FoilPrice'];
            $this->cardsInfo[$i]["firstGerman"] = $newData['firstGerman'];
            $this->cardsInfo[$i]["firstGermanNearMint"] = $newData['firstGermanNearMint'];
            $this->cardsInfo[$i]["firstGermanPlayset"] = $newData['firstGermanPlayset'];

            $this->cardsInfo[$i]["BildLink"]="<div class='hoverPictures'><a href=".$row->urlmkm."><img src='".str_replace(" ","_","./pictures/".str_replace("'", "´", $row->cardname)."_".$row->edition.".jpg")."' width='60px' onMouseLeave=\"hidePic()\" onMouseMove=\"hoverPic('".str_replace(" ","_","./pictures/".str_replace("'", "´", $row->cardname)."_".$row->edition.".jpg")."',event)\"></a></div>";

        }
    }

    function getFreshData($url){
        $data = array();
        $quellcodeMKM = file ($url);
        $strippedCodeMKM=(strip_tags($quellcodeMKM[46]));
        $pricePregmatch="/[0-9]*.,[0-9]+./";
        preg_match_all($pricePregmatch,$strippedCodeMKM,$price);
        $data["MinimalPrice"]=trim(str_replace(",",".",$price[0][0]));
        $data["AveragePrice"]=trim(str_replace(",",".",$price[0][1]));
        if(isset($price[0][2])){
            $data["FoilPrice"]=trim(str_replace(",",".",$price[0][2]));
        }
        else{
            $data["FoilPrice"]=0;
        }

        $data["firstGerman"] = "/";
        for($n = 61; $n < sizeof($quellcodeMKM) ; $n++){
            preg_match("/Artikelstandort: Deutschland/", $quellcodeMKM[$n], $matches);
            preg_match("/showMsgBox\('Playset'\)/",$quellcodeMKM[$n+1] , $playsetMatch);
            if(!empty($matches) && empty($playsetMatch)){
                preg_match("/[0-9]{1,3},[0-9]{2}/", $quellcodeMKM[$n+1], $priceMatches);
                $data["firstGerman"] = str_replace(",", ".", $priceMatches[0]);
                break;
            }
        }

        $data["firstGermanNearMint"] = "/";
        for($n = 61; $n < sizeof($quellcodeMKM) ; $n++){
            preg_match("/Artikelstandort: Deutschland/", $quellcodeMKM[$n], $matches);
            preg_match("/showMsgBox\('Playset'\)/",$quellcodeMKM[$n+1] , $playsetMatch);
            if(!empty($matches) && empty($playsetMatch)){
                preg_match("/showMsgBox\('Near Mint'\)/", $quellcodeMKM[$n+1], $match);
                if(!empty($match)){
                    preg_match("/[0-9]{1,3},[0-9]{2}/", $quellcodeMKM[$n+1], $priceMatch);
                    $data["firstGermanNearMint"] = str_replace(",", ".", $priceMatch[0]);
                    break;
                }
            }
        }

        $data["firstGermanPlayset"] = "/";
        for($n = 61; $n < sizeof($quellcodeMKM); $n++){
            preg_match("/Artikelstandort: Deutschland/", $quellcodeMKM[$n], $matches);
            if(!empty($matches)){
                preg_match("/showMsgBox\('Playset'\)/",$quellcodeMKM[$n+1] , $match);
                if(!empty($match)){
                    preg_match("/[0-9]{1,3},[0-9]{2}/", $quellcodeMKM[$n+1], $priceMatch);
                    $data["firstGermanPlayset"] = str_replace(",", ".", $priceMatch[0]);
                    break;
                }
                preg_match("/col_Even col_[0-9]+? cell_[0-9]+?_[0-9]+? st_ItemCount centered\">(([4-9])|([0-9]{2,}))/",$quellcodeMKM[$n+1] , $match);
                if(!empty($match)){
                    preg_match("/[0-9]{1,3},[0-9]{2}/", $quellcodeMKM[$n+1], $priceMatch);
                    $singlePrice = str_replace(",", "", $priceMatch[0]);
                    $singlePrice = $singlePrice * 4;
                    $singlePrice = $singlePrice / 100;
                    $data["firstGermanPlayset"] = $singlePrice;
                    break;
                }
            }
        }
        return $data;
    }

    public function getTable(){
        $table= "<table id='cardTable' class='features-table''>

           <thead><tr>
           <td width='200px'>Name</td>
           <td >Edition</td>
           <td class='minimum'>A. Min.Preis</td>
           <td class='average'>A. Ø-Preis</td>
           <td class='firstgerman'>A. Dt. Preis</td>
           <td class='firstgermannm'>A. Dt. Preis(NM)</td>
           <td class='firstgermanps'>A. Dt. Preis(PS)</td>
           <td class='minimum'>Min.Preis</td>
           <td class='average'>Ø-Preis</td>
           <td class='firstgerman'>Dt. Preis</td>
           <td class='firstgermannm'>Dt. Preis(NM)</td>
           <td class='firstgermanps'>Dt. Preis(PS)</td>
           <td>Abbildung</td>
           <td>Löschen</td>
           <td>info</td>
           </tr><thead>
          <tfoot><tr>
          <td></td>
          <td></td>
          <td class='minimum'>A. Min.Preis</td>
          <td class='average'>A. Ø-Preis</td>
          <td class='firstgerman'>A. Dt. Preis</td>
          <td class='firstgermannm'>A. Dt. Preis(NM)</td>
          <td class='firstgermanps'>A. Dt. Preis(PS)</td>
          <td class='minimum'>Min.Preis</td>
          <td class='average'>Ø-Preis</td>
          <td class='firstgerman'>Dt. Preis</td>
          <td class='firstgermannm'>Dt. Preis(NM)</td>
          <td class='firstgermanps'>Dt. Preis(PS)</td>
          </tr><tfoot><tbody>
           ";

        $iTable=0;
        foreach($this->cardsInfo as $cardInfo){
            if($cardInfo["OldMinimalPrice"] >$cardInfo["MinimalPrice"]){
                $info="<span class='tdinfo_lower'>!</span>";
            }
            elseif($cardInfo["OldMinimalPrice"] < $cardInfo["MinimalPrice"]){
                $info="<span class='tdinfo_higher'>!</span>";
            }
            elseif($cardInfo["OldMinimalPrice"] == $cardInfo["MinimalPrice"]){
                $info="<span class='tdinfo_equal'>=</span>";
            }
            $iTable++;
            $table .="<tr class='row_".($iTable%2)."'>
            <td>".$cardInfo["Name"]."</td>
            <td>".$cardInfo["Edition"]."</td>
            <td class='minimum'>".$cardInfo["OldMinimalPrice"]." €</td>
            <td class='average'>".$cardInfo["OldAveragePrice"]." €</td>
            <td class='firstgerman'>".$cardInfo["OldFirstGermanPrice"]." €</td>
            <td class='firstgermannm'>".$cardInfo["OldFirstGermanPriceNM"]." €</td>
            <td class='firstgermanps'>".$cardInfo["OldFirstGermanPricePS"]." €</td>
            <td class='minimum'>".$cardInfo["MinimalPrice"]." €</td>
            <td class='average'>".$cardInfo["AveragePrice"]." €</td>
            <td class='firstgerman'>".$cardInfo["firstGerman"]." €</td>
            <td class='firstgermannm'>".$cardInfo["firstGermanNearMint"]." €</td>
            <td class='firstgermanps'>".$cardInfo["firstGermanPlayset"]." €</td>
            <td>".$cardInfo["BildLink"]."</td>
            <td><img src=\"./src/img/del.png\" onclick=\"removeCard(".$cardInfo["id"].")\" class='removeCard'></img></td>
            <td>".$info."</td>
            </tr>";
        }
        $table .="</tbody></table>";
        return $table;
    }

    function addNewPrices(){

        $this->refreshData();
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
                mysql_select_db("mtg_preise", $con) or die("Konnte die Datenbank nicht selecten!");

        foreach($this->cardsInfo as $cards){
            $addStatement = "Update card set pricelowest='".$cards["MinimalPrice"]."', priceaverage='".$cards["AveragePrice"]."', pricefoil='".$cards["FoilPrice"]."', pricefirstger='".$cards["firstGerman"]."', pricefirstgernm='".$cards["firstGermanNearMint"]."', pricefirstgerps='".$cards["firstGermanPlayset"]."' where id = ".$cards["id"].";";
            $result = mysql_query($addStatement, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");
            $this->cardsInfo[$cards["id"]]["OldMinimalPrice"]=$cards["MinimalPrice"];
            $this->cardsInfo[$cards["id"]]["OldAveragePrice"]=$cards["AveragePrice"];
            $this->cardsInfo[$cards["id"]]["OldFoilPrice"]=$cards["FoilPrice"];
        }
        $this->render();
    }

    function addNewURL($urlmkm){
        ini_set("allow_url_fopen",true);
        ini_set("user_agent","user_agent','MSIE 4\.0b2;");

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

            $freshData = $this->getFreshData($urlmkm);

            $sql = "INSERT INTO card(urlmkm, edition, cardname, pricelowest, priceaverage, pricefoil, pricefirstger, pricefirstgernm, pricefirstgerps) VALUES(\"$urlmkm\", \"$edition\", \"$name\", \"".$freshData['MinimalPrice']."\", \"".$freshData['AveragePrice']."\", \"".$freshData['FoilPrice']."\", \"".$freshData['firstGerman']."\" , \"".$freshData['firstGermanNearMint']."\" , \"".$freshData['firstGermanPlayset']."\");";

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

    function  deleteAllCards(){
        $con = mysql_connect("127.0.0.1", "root", "") or die("Konnte keine Verbindung aufbauen!");
        mysql_select_db("mtg_preise", $con) or die("Konnte die Datenbank nicht selecten!");

        $sql = "DELETE FROM card;";
        mysql_query($sql, $con) or die("SQL-Statement konnte nicht abgesetzt werden!");
        $this->render();
    }

    public function render(){
        $this->refreshData();
        echo $this->getTable();
    }
}

?>

