<?php
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
    $ausgabe            = "";
    $home 			    = $_SERVER['PHP_SELF'];
    $rez_id             = get_param("rez_id");
    $action 			= get_param("action");
//je nach rezept wird hier das geaendert bearbeiten ist ein knopf
    if($action != ""){
            $ausgabe .= "Sie haben den Datensatz erfolgreich geloescht! <br>";
            $db = db_connect();
            // Abfrage
                //tabelle rezept
                $query = "DELETE FROM rezepte
                            WHERE rez_id = $rez_id";
                mysqli_query($db,$query) or die("Fehler beim Datenlöschen 1".mysqli_error($db));
                # Tabelle reztag 
                $query = "DELETE FROM  reztag
                            WHERE rez_id = $rez_id";
                mysqli_query($db,$query) or die("Fehler beim Datenlöschen 2".mysqli_error($db));
                # Tabelle rezzut 
                $query = "DELETE FROM  rezzut
                            WHERE rez_id = $rez_id";
                mysqli_query($db,$query) or die("Fehler beim Datenlöschen 3".mysqli_error($db));
            // Verbindung zum DB-Server trennen
            db_close($db); 
        }
        else
        {
            $ausgabe .= <<<FORMULAR
            <form action="{$_SERVER['PHP_SELF']}">
                Wollen sie das rezept wirklich löschen?<br>
                <input type="hidden" name="rez_id" value="$rez_id">  
                <input type="submit" name="action" value="loeschen">
            </form>
FORMULAR;
        }
        $ausgabe1 .= '<a href="uebersicht.php">Übersicht</a>';
        include("template/template.php");
}



?>