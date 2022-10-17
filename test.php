<?php
// hier wird alles eingebunden was ich für die seite brauche.
// uebersichtshalber habe ich alles hier ausgelagert,
// damit das hinzufuegen etwas schlanker aussieht.
include ("include/config.php");
include ("include/functions.php");

session_start();
if(!isset($_SESSION['e_mail'])) 
{  
	header('Location:index.php');
	die; 
}
else
{

    //Variablen für das Template deklarieren und initialisieren
    $title                      = "rezept hinzufügen";
    $ausgabe                    = "";
    $db                         = db_connect();
    $wrapper                    = "wrapperein";
    // Variablen für die Fehlermeldungen deklarieren und initialisieren. 
    // diese fehlermeldung ist ein Array und arbeitet mit der datei fehlerprüfung.php Mehr in fehlerpruefung
    $err = [];
    // Variablen der FormularFelder deklarieren und initialisieren
    // geschieht durch nutzung der Funtion get_param()
    // Überprüfung und setzen der Werte auf übergebenen Formularwerte.
    // durch formdata Array kann ich alles in printform aufarbeiten und hier ausgeben
    $formdata = [];
    $formdata['action']         = get_param("senden");
    $formdata['mehrfelder']     = get_param("mehrfelder");
    $formdata['rezeptname']     = get_param("rezeptname");
    $formdata['menge']          = get_param("menge");
    $formdata['einheit']        = get_param("einheit");
    $formdata['zutatenname']    = get_param("zutatenname");
    $formdata['zubereitung']    = get_param("zubereitung");
    $formdata['tag']            = get_params("tag");
    $formdata['ein_id']         = get_param("ein_id");
    $formdata['imgname']        = get_param("imgname");
    
    $ausgabe .= "<h1>Rezept hinzfügen</h1>";

    //soll überprüfen, ob mehrfelder oder action gedrückt wurde und, ob sie leer sind oder schon was drinne steht
    if(!empty($formdata['action']) || !empty($formdata['mehrfelder']))
    {
        //soll überpürfen, ob mehrfelder schon gedrückt wurde und ob vorher was drinne stand
        if (!empty($formdata['mehrfelder']))
        {  
            //das ist die schleife für mehrfelder
            for($i=1;$i<=1;$i++)
            {
                //hier wird mit dem betätigen, der mehrfeldertaste immer ein feld mehr vom programm gestellt
                $einheiten = getEinheiten($db);
                $zeilen = count($formdata['menge']) + 1;
                $ausgabe .= printForm($formdata, $zeilen, $einheiten);
            }
        }
        else
        {
            //wenn bis hier hin kein fehler gemacht wurde, dann soll in der datenbank geschrieben werden
            $err = fehlerPruefung($formdata);
            if(empty($err))
            {
                $zut_id=[];
                // hier wird die sql verbindung angegeben und in der datenbank reingeschrieben
                $ausgabe .= 
                // Erfolgsmeldung, dass es geklappt hat
                    "Ihr Daten wurden übermittelt!<br>";
                   
                    $db = db_connect();
                    try{
                        mysqli_begin_transaction($db);
                        # Tabelle Rezept !Funktioniert! FINGER WEG!
                            $query = "INSERT INTO rezepte(rez_id,rezeptname,zubereitung,users_id,bild) VALUES (NULL,'{$formdata['rezeptname']}','{$formdata['zubereitung']}',users_id,bild)";
                                mysqli_query($db,$query) or die("Fehler beim Datenschreiben 1".mysqli_error($db));
                                $rez_id = mysqli_insert_id($db);
                            mysqli_commit($db);
                        }
                        catch(Exception $e){
                            mysqli_rollback($db);
                            $ausgabe .= $e->getMessage();
                        }
                    db_close($db); 
                    
                    $ausgabe .= "<br>\n<a href=uebersicht.php>übersichtsseite</a>\n";
            }
            else
            {
                //hier hält er die eingetragenden werte und behält sie auch, wenn man auf mehrfelder drückt
                $einheiten = getEinheiten($db);
                $zeilen = count($formdata['menge']);
                $ausgabe  .= printForm($formdata, $zeilen, $einheiten, $err);
            }
        }
    }
    else
    {
        $einheiten = getEinheiten($db); 
        $ausgabe  .= printForm($formdata,1,$einheiten,$err);
    }
    $ausgabe = "<div class='flex'>$ausgabe</div>";
    
    include("template/template.php");
}	

 // Erfolgsmeldung, dass es geklappt hat
 "Ihr Daten wurden übermittelt!<br>";
 $ausgabe .= "<br>";
 $db = db_connect();
 try{
     mysqli_begin_transaction($db);
     # Tabelle Rezept !Funktioniert! FINGER WEG!
         $query = "INSERT INTO rezepte(rez_id,rezeptname,zubereitung,users_id,bild) 
                     VALUES (NULL,'{$formdata['rezeptname']}','{$formdata['zubereitung']}','{$formdata['user_id']}','{$formdata['bild']}')";
             mysqli_query($db,$query) or die("Fehler beim Datenschreiben 1".mysqli_error($db));
             $rez_id = mysqli_insert_id($db);
         mysqli_commit($db);
     }
     catch(Exception $e){
         mysqli_rollback($db);
         $ausgabe .= $e->getMessage();
     }
 db_close($db); 




$db = db_connect();
 try{
 mysqli_begin_transaction($db);
# Tabelle Rezept !Funktioniert! FINGER WEG!
 $query = "INSERT INTO rezepte(rez_id,rezeptname,zubereitung,users_id,bild) VALUES (NULL,'{$formdata['rezeptname']}','{$formdata['zubereitung']}',users_id,bild)";
 mysqli_query($db,$query) or die("Fehler beim Datenschreiben 1".mysqli_error($db));
$rez_id = mysqli_insert_id($db);
 mysqli_commit($db);
}
 catch(Exception $e){
 mysqli_rollback($db);
 $ausgabe .= $e->getMessage();
 }
 db_close($db); 

?>