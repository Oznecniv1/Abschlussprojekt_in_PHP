<?php 
// hier wird alles eingebunden was ich für die seite brauche.
// uebersichtshalber habe ich alles hier ausgelagert,
// damit das hinzufuegen etwas schlanker aussieht.
include ("include/config.php");
include ("include/functions.php");

//hier beginnt die sassion 
session_start();
if(!isset($_SESSION['e_mail'])) 
{  
	header('Location:index.php');
	die; 
}
else
{
    //Variablen für das Template deklarieren und initialisieren
    $ausgabe                    = "";
    $ausgabe1                   = "";
    $home 			= $_SERVER['PHP_SELF'];
    $rez_id                     = get_param("rez_id");
    $action 			= get_param("action");
    
    $i                          = 0;
    $zeilen                     = [];
    $formdata                   = [];
    $formdata['rezeptname']     = get_param("rezeptname");
    $formdata['menge']          = get_param("menge");
    $formdata['einheit']        = get_param("einheit");
    $formdata['zutatenname']    = get_param("zutatenname");
    $formdata['zubereitung']    = get_param("zubereitung");
    $formdata['tag']            = get_params("tag");
    $formdata['ein_id']         = get_param("ein_id");
    $formdata['imgname']        = get_param("imgname");

//     datensatz aus der datenbank holen
    if($action == "")
{
        $db = db_connect();
                mysqli_set_charset($db,"utf8");
        // abfrage für rezeptname und zubereitung
        $query = "SELECT rezeptname,zubereitung 
                        FROM rezepte 
                        WHERE rez_id = $rez_id ";
        echo $query;                
        $result = mysqli_query($db,$query);
        $feld = mysqli_fetch_assoc($result);

        $formdata                   = [];
        $formdata['rezeptname']     = $feld['rezeptname'];
        $formdata['zubereitung']    = $feld['zubereitung'];

        // 
        $query = "SELECT *
                        FROM reztag 
                        WHERE rez_id = $rez_id  ";
        
        $result = mysqli_query($db,$query);
        $feld = mysqli_fetch_assoc($result);
        $formdata['tag']            = $feld['tag_id'];
        
        $query = "SELECT *
                        FROM rezzut as rz 
                        JOIN zutaten as zu ON rz.zut_id = zu.zut_id
                        JOIN einheiten as ein ON rz.ein_id = ein.ein_id
                        WHERE rez_id = $rez_id";

        $result = mysqli_query($db,$query);
        $formdata['menge']                      = [];
        $formdata['einheiten_id']               = [];
        $formdata['zutaten_id']                 = [];
        
        $ausgabemenge     = "<label for='menge'>menge</label><br>";
        $ausgabeeinheiten = "<label for='einheit'>Einheit</label><br>";
        $ausgabezname     = "<label for='zutatenname'>zutatenname</label><br>";       

        foreach($result as $value){
                
                $formdata['menge'][$i]            = $value['menge'];
                $formdata['einheiten_id'][$i]     = $value['einheiten'];
                $formdata['zutatenname'][$i]      = $value['zutatenname'];

                $ausgabemenge .= "<input type='text' name='menge[]' id='menge' value={$formdata['menge'][$i]}><br>\n";
                
                $ausgabeeinheiten .= selectbox('einheit[]', $einheiten,$formdata['einheit']);
                $ausgabeeinheiten .="<br>";

                $ausgabezname .= "<input type='text' name='zutatenname[]' id='zutatenname' value={$formdata['zutatenname'][$i]}><br>";
        }
        
        $ausgabezutaten = <<<HTML
        <div class="flex">
                <div class="menge">$ausgabemenge</div>
                <div class="einheiten">$ausgabeeinheiten</div>
                <div class="zutatenname">$ausgabezname</div>
        </div>   
HTML;

        // $formdata['bild']                 = "!BILD!";

        db_close($db);
}

    
    }
    //formulare aufbauen
    if($action == ""){

        $ausgabe .= '<form action="$home">';        

        $ausgabe .= "<label for='rezeptname'>Rezeptname</label>";
        $ausgabe .= "<input type='text' name='rezeptname' id='rezeptname' value={$formdata['rezeptname']}>";

        $ausgabe .= $ausgabezutaten;
        
  
        $ausgabe .= "<label for='zubereitung'>Zubereitung</lable>";
        $ausgabe .= "<textarea name='zubereitung' id='zubereitung'>{$formdata['zubereitung']}</textarea><br>";

        $ausgabe .= $ausgabetag;

        $ausgabe .= '<input type="submit" name="action" value="senden">';
        $ausgabe .= "</form>";

        




    }
    elseif($action != "")
    {
        //meldung, dass es geklappt hat
        $ausgabe .= "Sie haben den Datensatz erfolgreich geändert!<br>";
 
        $db = db_connect();
        // Abfrage
     //tabelle rezept
        $query = "UPDATE rezepte
                    SET rezeptname = {$formdata['rezeptname']}, zubereitung = {$formdata['zubereitung']}
                    WHERE rez_id = $rez_id";
                    mysqli_query($db,$query) or die("Fehler beim Datenschreiben 1".mysqli_error($db));
        # Tabelle zutaten
                foreach($formdata['zutatenname'] as $value){
        $update ="UPDATE zutaten
                    SET NULL,'$value'
                    WHERE rez_id = $rez_id";
                    mysqli_query($db,$query) or die("Fehler beim Datenschreiben 2".mysqli_error($db));
                    array_push($zut_id, mysqli_insert_id($db));
                    }
        # Tabelle reztag  
                foreach($formdata['tag'] as $value){
        $update ="UPDATE reztag
                    SET $rez_id,'$value'
                    WHERE rez_id = $rez_id";
                }
        # Tabelle rezzut //dreckige lösung 
                $i=0;
                while($i<sizeof($formdata['menge'])){
        $update ="UPDATE rezzut
                    SET $rez_id,$zut_id[$i],menge = {$formdata['menge'][$i]},einheit = {$formdata['einheit'][$i]}
                    WHERE rez_id = $rez_id";
                    mysqli_query($db,$query) or die("Fehler beim Datenschreiben 3".mysqli_error($db));
                    $i++;
                    }

        
        // Verbindung zum DB-Server trennen
        db_close($db);
        
    }

include("template/template.php");





?>