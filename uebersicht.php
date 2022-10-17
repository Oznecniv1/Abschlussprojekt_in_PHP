<?php
// Funktionsbibliothek inkludieren
include	("include/functions.php");
include ("include/config.php");

$nav="";

session_start();
if(isset($_SESSION['e_mail'])) 
{  
	$nav .= "<a href=hinzufuegen.php >Rezept Hinzufügen</a>\n";
	$nav .= "<a href=logout.php> ausloggen</a>";
	$nav .= "<a href=uebersicht.php> Impressum </a>";
	$nav .= "<a href=uebersicht.php> Datenschutzerklärung </a>";
}
else{
	$nav .= "<a href=index.php>Einloggen</a>" ;
}

	// Variablen deklarieren und initialisieren
	$rezepte 	        = "rezepte uebersicht";
	$wrapper 	        = "wrapperaus";
    $home 		        = $_SERVER['PHP_SELF'];
	// Verbindung zum DB-Server herstellen und DB wählen
	$db = db_connect(); 

	// DB-auslesen
	$orderstring        = "";
	$suchstring         = "";
	$rezeptname         = get_param('rezeptname');
	$order              = get_param('order');

	if($rezeptname != "")
		$suchstring .= "AND rezeptname = '$rezeptname'";

	if($order != "")
		$orderstring .= " ORDER BY $order";

	// Datensatz abfragen aus der tabelle rezepte
	$query ="SELECT * FROM rezepte WHERE 1 $suchstring $orderstring";
	$result = mysqli_query($db,$query);
	if(mysqli_affected_rows($db))
	{	
		while($row = mysqli_fetch_assoc($result))
		{
			$ausgabe .="<div class=masonry>\n";
			$ausgabe .="<section class=masonry-brick>\n";
			$ausgabe .="<a href=ansicht.php?rez_id=".$row['rez_id'].">\n";
			$ausgabe .="<img src=img/chinesisch.jpg class=masonry-img alt=bild />\n";
			$ausgabe .="<h1>".$row['rezeptname']."</h1>\n";
			$ausgabe .="</a>\n";
			$ausgabe .="</section>\n";
			$ausgabe .="</div>\n";
		}
			//Suchformular
			$ausgabe1 .=	"<form action=$home method=get>\n".
								"<fieldset>\n".
									"<legend>rezpet suchen</legend>\n".
									"<input type=text name=rezeptname id=suchen value=$rezeptname>\n".
									"<input type=submit value=suchen>\n".
								"</fieldset>\n";
							"</form>\n";
	}
	else
		{
			$ausgabe .= "Keine Daten gefunden!\n";
		}
		$ausgabe2 .= $nav;
	//Verbindung zum DB-Server trennen
	db_close($db);

	$ausgabe = "<div id='flex'>$ausgabe</div>";

	include("template/template.php");

?>