<?php
// Funktionsbibliothek inkludieren
include	("include/functions.php");
include ("include/config.php");
include ("include/dbconnect.php");

session_start();
if(!isset($_SESSION['e_mail'])) 
{  
	header('Location:index.php');
	die; 
}
else
{
	// Variablen deklarieren und initialisieren
	$rezept 		= "rezept ansicht";
	$wrapper 		= "wrapperaus";
    $home 			= $_SERVER['PHP_SELF'];
	$rez_id 		= get_param("rez_id");
	// Verbindung zum DB-Server herstellen und DB wählen
	$db = db_connect(); 

	//Mehrere Datensatz abfragen aus den tabellen

	//Abfrage von rezeptname und zubereitung !FUNKTIONIER!FINGER!WEG!
	$query ="SELECT rezeptname,zubereitung 
				FROM rezepte  
				WHERE rez_id = $rez_id";
	$result = mysqli_query($db,$query);
	if($row = mysqli_fetch_assoc($result))
	{
		$ausgabe .= "<h1>{$row['rezeptname']}</h1>\n";
		$ausgabe .= "<p>{$row['zubereitung']}</p>\n";
	}

	//Abfrage von zutaten und einheiten und menge !FUNKTIONIER!FINGER!WEG!
	$query ="SELECT * 
				FROM rezzut as rz 
				join einheiten as e on rz.ein_id = e.ein_id
				join zutaten as z on rz.zut_id = z.zut_id
				where rz.rez_id = $rez_id ";
	$result = mysqli_query($db,$query);
	$ausgabe .="<img src=img/chinesisch.jpg id=pic alt=bild />\n";

	if(mysqli_affected_rows($db))
	{
		$ausgabe .= "<table id=tabelle1>\n";
		$ausgabe .= "<th>menge</th>\n";
		$ausgabe .= "<th>einheiten</th>\n";
		$ausgabe .= "<th>zutatenname</th>";
			while($row = mysqli_fetch_assoc($result))
			{
				$ausgabe .= "<tr>\n";
				$ausgabe .= "<td>".$row['menge']."</td>\n";
				$ausgabe .= "<td>".$row['einheiten']."</td>\n";
				$ausgabe .= "<td>".$row['zutatenname']."</td>\n";
				$ausgabe .= "</tr>\n";
			}
		$ausgabe .= "</table>\n";
	}
	
	// Datensatz abfrage für Die Checkbox style !FUNKTIONIER!FINGER!WEG!
	$query ="SELECT t.tag 
				FROM tag 					as t
				JOIN reztag 				as rt on rt.tag_id = t.tag_id
				WHERE rt.rez_id = $rez_id ";

	$result = mysqli_query($db,$query);
	if(mysqli_affected_rows($db))
	{
		$ausgabe .= "<ol id=ol>\n";
			while($row = mysqli_fetch_assoc($result))
			{
				foreach($row as $key => $value)
				{
					if($key == "tag")
					{
						$ausgabe .="<li>".$row['tag']."</li>\n";
					}
				}
			}
		$ausgabe .= "</ol>\n";
	}
	else
		{
			$ausgabe .= "<h1><colspan=7>Keine Daten gefunden!</h1>\n";
		}

		$ausgabe .= "<a href=pdf.php?rez_id=.$rez_id.> Drucken </a>";
		$ausgabe .= "<a href=bearbeiten.php?rez_id=".$rez_id."> Bearbeiten </a>";
		$ausgabe .= "<a href=loeschen.php?rez_id=".$rez_id."> Löschen </a>";
		$ausgabe .= "<a href=uebersicht.php> Übersicht </a>";
		$ausgabe .= "<a href=uebersicht.php> Impresseum </a>";
		$ausgabe .= "<a href=uebersicht.php> Datenschutzerklärung </a>";
		$ausgabe .= "<a href=hinzufuegen.php> Rezept Hinzufügen </a>";
	//Verbindung zum DB-Server trennen 
	db_close($db);
$ausgabe = "<div class='flex'>$ausgabe</div>";
include("template/template.php");
}
?>