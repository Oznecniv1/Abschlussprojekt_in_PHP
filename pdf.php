<?php
// Funktionsbibliothek inkludieren
include	("include/functions.php");
include ("include/config.php");
//fpdf muss immer  drinne sein
//sonst klappt es nicht eine PDF zu erstellen
require ("include/fpdf/fpdf.php");

session_start();
if(!isset($_SESSION['e_mail'])) 
{  
	header('Location:index.php');
	die; 
}
else
{
	// Variablen deklarieren und initialisieren
	$rezept 		= "PDF Drucken";
	$wrapper 		= "wrapperaus";
    $home 			= $_SERVER['PHP_SELF'];
    $rez_id 		= get_param("rez_id");
    $pdf 			= new FPDF();
    $pdf			-> AddPage();
	
	// Verbindung zum DB-Server herstellen und DB wählen
	$db = db_connect(); 

	//Mehrere Datensatz abfragen aus der tabellen

	//Abfrage von rezeptname und zubereitung !FUNKTIONIER!FINGER!WEG!

	$query ="SELECT rezeptname,zubereitung 
				FROM rezepte  
				WHERE rez_id = $rez_id";
	$result = mysqli_query($db,$query);
	if(mysqli_affected_rows($db))
	{
		while($row = mysqli_fetch_assoc($result))
		{
			foreach($row as $key => $value)
			{
				if($key == "rezepte")
				{
                    $pdf->SetFont("helvetica","B",10);
					$pdf->SetTextColor(255,0,0);
					$pdf->Ln();
					$pdf->Cell(28,8,utf8_decode($row['rezeptname']),0,0,"C",1);
					$pdf->Write(12,$pdf);
				}
				else
				{
					$pdf->SetFont("helvetica","",10);
					$pdf->Ln();
					$pdf->SetTextColor(255,0,0);
					$pdf->Write(12,utf8_decode($value));
				}
			}
		}
	}
	//Abfrage von zutaten
	$query ="SELECT  z.zutatenname
				FROM zutaten 			as z
				JOIN rezzut 			as rz ON rz.zut_id = z.zut_id
				WHERE rz.rez_id = $rez_id";
	$result = mysqli_query($db,$query);
	if(mysqli_affected_rows($db))
	{
		while($row = mysqli_fetch_assoc($result))
		{
			foreach($row as $key => $value)
			{
				if($key == "zutaten")
				{
					$pdf->SetFont("helvetica","",10);
					$pdf->SetTextColor(255,0,0);
					$pdf->Ln();
					$pdf->Write(12,utf8_decode($row['zutatenname']));
				}
			}
		}
	}
	//Abfrage von menge
	$query ="SELECT menge 
				FROM rezzut 		as rz
				WHERE rz.rez_id = $rez_id";
	$result = mysqli_query($db,$query);
	if(mysqli_affected_rows($db))
	{
		while($row = mysqli_fetch_assoc($result))
		{
			foreach($row as $key => $value)
			{
				if($key == "zutaten")
				{
					$pdf->SetFont("helvetica","",10);
					$pdf->SetTextColor(255,0,0);
					$pdf->Ln();
					$pdf->Write(12,utf8_decode($row['zutatenname']));
				}
			}
		}
	}
	// Datensatz abfrage für Die selecktboxen und Text felder
	$query ="SELECT e.einheiten 
				FROM einheiten 				as e 
				JOIN rezzut 				as rz ON rz.ein_id = e.ein_id
				WHERE rz.rez_id = $rez_id";
	$result = mysqli_query($db,$query);
	if(mysqli_affected_rows($db))
	{
		while($row = mysqli_fetch_assoc($result))
		{
			foreach($row as $key => $value)
			{
				
				 if($key == "einheiten")
				 {
					$pdf->SetFont("helvetica","",10);
					$pdf->SetTextColor(255,0,0);
					$pdf->Ln();
					$pdf->Write(12,utf8_decode($row['einheiten']));
				}
			}
		}
	}
	// Datensatz abfrage für Die Checkbox style
	$query ="SELECT t.tag 
				FROM tag 					as t
				JOIN reztag 				as rt on rt.tag_id = t.tag_id
				WHERE rt.rez_id = $rez_id ";
	$result = mysqli_query($db,$query);
	if(mysqli_affected_rows($db))
	{
		while($row = mysqli_fetch_assoc($result))
		{
			foreach($row as $key => $value)
			{
				 if($key == "tag")
				 {
					$pdf->SetFont("helvetica","",10);
					$pdf->SetTextColor(255,0,0);
					$pdf->Ln();
					$pdf->Write(12,utf8_decode($row['tag']));
				 }
			}
		}
    }	
	//Verbindung zum DB-Server trennen
	db_close($db);
	$pdf -> Output("rezept.pdf","I");
}
?>