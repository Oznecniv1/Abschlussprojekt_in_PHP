<?php
//Funktion zum erfassen von Single-option Feldern und umwandeln von bestimmten zeichen in html sichere Entsprtechungen(z.B. & => &amp;)
function get_param($name)
{
	if(METHOD == 'get')
	{
		if(isset($_GET[$name]))
		{	
			if(is_array($_GET[$name]))
			{
				$werte = $_GET[$name];
				for($i = 0; $i < count($werte); $i++)
				{
					$werte[$i]=htmlspecialchars(strip_tags($werte[$i]));
				}
				return $werte;
			}
			else
			{
				$_GET[$name] = strip_tags($_GET[$name]);
				return htmlspecialchars($_GET[$name]);
			}
		}
		else
		{
			return "";
		}
	}
	if(METHOD == 'post')
	{
		if(isset($_POST[$name]))
		{	
			if(is_array($_POST[$name]))
			{
				$werte = $_POST[$name];
				for($i = 0; $i < count($werte); $i++)
				{
					$werte[$i]=htmlspecialchars(strip_tags($werte[$i]));
				}
				return $werte;
			}
			else
			{
				$_POST[$name] = strip_tags($_POST[$name]);
				return htmlspecialchars($_POST[$name]);
			}
		}
		else
		{
			return "";
		}
	}
}


//Funktion zum erfassen von Multiple-option Feldern und umwandeln von bestimmten zeichen in html sichere Entsprtechungen(z.B. & => &amp;)
function get_params($name)
{
	if(METHOD == 'get')
	{
		if(isset($_GET[$name]))
		{	
			$rtn = array();
			//var_dump($rtn);
			foreach($_GET[$name] as $value){
				$value = strip_tags($value);
				array_push($rtn,htmlspecialchars($value));
			}
			return $rtn;
		}
		else
		{
			return "";
		}
	}
	if(METHOD == 'post')
	{
		if(isset($_POST[$name]))
		{	
			$rtn[] = array();
			foreach($_POST[$name] as $value){
				$value = strip_tags($value);
				array_push($rtn,htmlspecialchars($value));
			}
			return $rtn;
		}
		else
		{
			return "";
		}
	}
}

// Funktion zum erstellen einer selectboxen mit den übergebenen array als werten
// die funktion enthält die parameter $name $arr und optional $class="" die immer leer ist
// $name erwartet einen namen 
// $arr ist hier eigentlich mit den in der datanbank benannten einheuten verbunden
// 
function selectbox($name,$arr,$formwert,$class="")//Erste wert in Values ist preselected
{
	$ausgabe ="";
    $ausgabe .= "<select name=".$name.">\n";
	$i=0;
	$ausgabe .= "\t<option class='$class' value='-' selected>Bitte Wählen</option>\n";
	foreach($arr as $row)
	{
		$row = array_values($row);
		$selected = ($formwert == $row[0]) ? 'selected' : '';
		$ausgabe .= "\t<option class='$class' value='$row[0]' $selected>$row[1]</option>\n";
	}
	$ausgabe .= "</select>\n";		
	return $ausgabe;
}

// Funktion zum erstellen einer checkboxen mit den übergebenen array als werten
// die funktion enthält die parameter $name $arr und optional $class="" die immer leer ist
// $name erwartet einen 
// $tags ist das, wie die checkbox eigentlich heist
// 
function checkboxes($name,$tags,$formwertarray,$class="")//Erste wert in Values ist preselected
{
	if($formwertarray == '')
	{
		$formwertarray = array();
	}

	$ausgabe ="";
	$name    .= "[]";

	foreach($tags as $row)//mdarr = multidimensional array
	{
		$row = array_values($row);
		$checked = (array_search($row[0],$formwertarray) !== false) ? 'checked' : '';
        $ausgabe .= "\t<input type='checkbox' class='$class' name='$name' value='".$row[0]."' $checked>".$row[1]."<br>\n";
	}		
	return $ausgabe;
}

// Prüfung der PLZ im formular registrieren
function plz($plz)
{
	return preg_match("/^[0-9]{5}$/", $plz);
}
// Prüfung Straße und Ort im formular registrieren
function strort($strort)
{
	return preg_match("/^[-. A-ZÄÖÜa-zäöüß0-9]{2,}$/", $strort);
}
// prüfung email im formular registrieren
function e_mail($e_mail)
{
	return preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix",$e_mail);
}
//prüfung mengenbegrenzung im formular rezept hinzufügen
function menge($mengenbegrenzung)
{
	return preg_match("/^[0-9]+$/", $mengenbegrenzung);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++ debug methods +++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//Funktion zum "dumpen" von Mehrdimensionalen Arrays
function loopecho($arr){
	foreach($arr as $row){
		var_dump($row);
	}
}

function bildup($uploadfile,$uploaddir,$imgname)
{
	$uploaddir 	= "img/";
	$uploadfile = $uploaddir;	
	$msg = "";
	if($_FILES['userfile']['tmp_name']!= "")
	{
		$data = mime_content_type($_FILES['userfile']['tmp_name']);
		if($data == 'image/jpeg' || $data == 'image/png' || $data == 'image/gif')
		{
			if($data == 'image/jpeg')
			{
				$imgname=time().".jpg";
				$uploadfile .= $imgname;
			}
			elseif($data == 'image/png')
			{
				$imgname=time().".png";
				$uploadfile .= $imgname;
			}
			else
			{
				$imgname=time().".gif";
				$uploadfile .= $imgname;
			}
			if(move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile))
			{
				$msg .= "Datei wurde hochgeladen";
				$fehler = 0;
			}
			else
			{
				$msg .= "Es ist ein Fehler beim hochladen aufgetreten!";
				$fehler = 1;
			}
			// else
			// {
			// 	$msg .= "Die Datei scheint kein Bild zu sein";
			// 	$fehler = 1;
			// }
		}
		else
		{
			$msg .= "Es wurde keine oder eine groesse Datei hochgeladen!";
			$fehler = 1;
		}
		if(!$fehler)
		{
			// Bild und Bildinformationen ausgebn
			$array = getimagesize($uploadfile);
			$msg .=	"<pre>".
					"</pre>".
					"<img src='$uploadfile'>";
		}
		else
		{
			// Fehler info
			$msg .=	"<pre>".
					"Weitere Debugginginfos ausgeben".
					"</pre>";
			switch($_FILES['userfile']['error'])
			{
				case 1:$msg .= "Die hochgeladene Datei ueberschreitet die in der Anweisung".
							   " upload_max:filesize in php.ini festgelegte Groesse";
							   break;
				case 2:$msg .= "Die hochgeladene Datei ueberschreitet die in dem HTML-Formular".
							   " mittels der Anweisung MAX_FILE_SIZE festgelegte Groesse";
							   break;
				case 3:$msg .= "Die  Datei wurde nur teilweise hochgeladen.";
							   break;
				case 4:$msg .= "Es wurde keine Datei Hochgeladen";
							   break;
				case 6:$msg .= "Fehler temporaerer Ordner";
							   break;
				case 7:$msg .= "Speichern der Datei auf der Festplatte ist fehlgeschlagen";
							   break;
				case 8:$msg .= " Eine PHP Erweiterung hat den Upload der Datei gestoppt";
			}
		}
	}
}

// Verbindung zum DB-Server aufbauen und DB auswählen und fehler abfrage
function db_connect()
{
	$db = @mysqli_connect(HOST_NAME, USER_NAME, PASS_WORD, DB_NAME);
	if(!$db)
	{
		die("Verbindungsfehler: ".mysqli_connect_errno() ."".mysqli_connect_error());
	}
	if(!mysqli_set_charset($db, "utf8"))
	{
		printf("Fehler beim Laden von CharakterSet utf8: %s\n", mysqli_error($db));
	}
	return $db;
}

// hier wird die Verbindung zum DB-server getrennen
function db_close($db)
{
	mysqli_close($db);
}

// das ist die funktion zur fehlerüberprüfung.
// die funktion ist ein Array.
function fehlerPruefung(array $formdata)
{
    // wird ein array aufgebaut
    $err = [];
    // die eigentliche fehlermeldung
    $fehlermeldung = "\n<span class='fehler'>Fehler!</span>\n";
    // fehlermeldung für rezeptname
    if(empty($formdata['rezeptname']))
    {
        $err['rezeptname'] = $fehlermeldung;
    }
    // fehlermeldung für menge
    for($i = 0; $i < count($formdata['menge']); $i++)
    {
        // Wenn nichts drinne steht
        if(empty($formdata['menge'][$i]))
        {
            $err['menge'][$i] = $fehlermeldung;
        }
        // wenn buchstaben drinne stehen
        elseif(!menge($formdata['menge'][$i])){
            $err['menge'][$i] = "Bitte eine Zahl eingeben";
        }
    }
    // fehlermeldung für einheit
    for($i = 0; $i < count($formdata['einheit']); $i++)
    {
        if($formdata['einheit'][$i] == "-")
        {
            $err['einheit'][$i] = $fehlermeldung;
        }
        elseif(!($formdata['einheit'][$i])){
            $err['einheit'][$i] = "bitte eine einheit auswählen!";
        }
    // fehlermeldung für zutatenname
    }for($i = 0; $i < count($formdata['zutatenname']); $i++)
    {
        if(empty($formdata['zutatenname'][$i]))
        {
            $err['zutatenname'][$i] = $fehlermeldung;
        }
    }
    // fehlermeldung für zubereitung
    if(empty($formdata['zubereitung']))
    {
        $err['zubereitung'] = $fehlermeldung;
    }
    // fehlermeldung für
    if(empty($formdata['tag']))
    {
        $err['tag'] = $fehlermeldung;
    }
    // rückgabewert
    return $err;
}

// die Funktion getEeinheit ist für die einheiten nötig.
// die funktion liest aus der db und gibt sie bei 
// es gibt ein array zurück
function getEinheiten($db)
{
    $query= "SELECT * FROM einheiten";
    return mysqli_query($db,$query);
}

// die Funktion getTag ist für das Tagsystem benötigt nötig.
// die funktion liest aus der db und gibt sie bei 
// es gibt ein array zurück
function getTag($db)
{
    $query= "SELECT * FROM tag ORDER BY tag_id";
    return mysqli_query($db,$query);
}
//Funktion zur erstellung eines neuen passwortes
// function createRandomPassword($length=8,$chars="") 
// { 
//     if($chars=="")
//         $chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789"; 
//     srand((double)microtime()*1000000); 
//     $i = 0; 
//     $pass = '' ; 
 
//     while ($i < $length) { 
//         $num = rand() % strlen($chars); 
//         $tmp = substr($chars, $num, 1); 
//         $pass = $pass . $tmp; 
//         $i++; 
//     } 
//     return $pass; 
// }

// das ist die funktion printform.
// sie beinhaltet die textfelder(rezeptname,menge und zutatenname)
// die selectbox(einheiten)
// die textarea(zubereitung)
// und die checkboxen(tag)
function printForm(array $formdata, int $zeilen, $einheiten, array $err = [])
{
    //var_dump($formdata);

    $db = db_connect();
    $html = "";

    $err['rezeptname'] = $err['rezeptname'] ?? '';

    $method = METHOD;
    $html .= <<<HTML
    <form action="/Projektarbeit_original/hinzufuegen.php" method="$method" class="hinzufuegen">
    <label for="rezeptname">Rezeptname:</label>
    <input type="text" name="rezeptname" id="rezeptname" value="{$formdata['rezeptname']}"> {$err['rezeptname']}
HTML;

    // ich überprüfe, ob es nicht gleich leer ist und wenn was drinne steht soll er es halten
    $tag = !empty($formdata['tag']) ? $formdata['tag'] : [];
    // verbunden mit dem mehrfelder und gibt immer eine zeile, also menge, einheiten und zutatenname aus

    $mengehtml       = "";
    $einheitenhtml   = "";
    $zutatennamehtml = "";


    $html .= "<br><br>";

    $mengehtml       .= '<label for="menge" class="label_menge">Menge:</label><br>';
    $einheitenhtml   .= '<label for="einheiten" class="label_einheiten">Einheiten:</label><br>';
    $zutatennamehtml .= '<label for="zutatenname" class="label_zutatenname">Zutatenname:</label><br>';
    


    for($i = 0; $i < $zeilen; $i++)
    {
        // ich überprüfe, ob es nicht gleich leer ist und wenn was drinne steht soll er es halten
        $menge       = !empty($formdata['menge'][$i])       ? $formdata['menge'][$i]       : '';
        $einheit     = !empty($formdata['einheit'][$i])     ? $formdata['einheit'][$i]     : '';
        $zutatenname = !empty($formdata['zutatenname'][$i]) ? $formdata['zutatenname'][$i] : '';

        $err['menge'][$i]       = $err['menge'][$i] ?? '';
        $err['zutatenname'][$i] = $err['zutatenname'][$i] ?? '';
        $err['zubereitung']     = $err['zubereitung'] ?? '';

        $mengehtml       .= <<<HTML
        <!-- Das feld Menge wird ausgegeben und es ist ein array  -->        
        <input type="text" name="menge[]" id="menge" value="$menge">{$err['menge'][$i]} <br>     

        <!-- die selectbox wird ausgegeben und es ist ein array -->
HTML;
        $einheitenhtml   .= selectbox('einheit[]', $einheiten,$einheit);
        
        $einheitenhtml   .= $err['einheit'][$i] ?? '';
        $einheitenhtml   .= "<br>";
        $zutatennamehtml .= <<<HTML
        <!-- das feld zutatenname wird ausgegeben und es ist ein array -->
        <input type="text" name="zutatenname[]" id="zutatenname" value="$zutatenname"> {$err['zutatenname'][$i]}<br>
HTML;
    }
    $html .= <<<HTML
    <!-- Das ist der mehrfelder button der felder generiert -->
    <div class="flex">
        <div class="menge">$mengehtml</div>
        <div class="einheiten">$einheitenhtml</div>
        <div class="zutatenname">$zutatennamehtml</div>
    </div>
    <input type='submit' name='senden' value='Senden' class='senden'>
    <input type="submit" name="mehrfelder" value="Mehrfelder">
    <!-- hier ist das feld textarea zubereitung -->
    <br><label for="zubereitung"><br>Zubereitung:</label>
    <textarea name="zubereitung" id="zubereitung">{$formdata['zubereitung']}</textarea> {$err['zubereitung']}<br>

    <!-- bilderupload  hier wird das bild in der datenbank mitgesendet --> 
    <input type="hidden" name="MAX_FILE_SIZE" value="30000"><br>
    <input type="file" name="files"><br>
    <!-- in diesem fieldset befinden sich die checkboxen für das tagssystem -->
        <fieldset>
            <legend>Tags</legend>
HTML;
                $html .= checkboxes('tag', getTag($db),$formdata['tag']); 
                $html .= $err['tag'] ?? '';

    $html .= <<<HTML
        </fieldset>
    <!-- Das ist der sendenbutton, womit das formular abgeschickt wird und in der datenbank eingetragen wird -->
    

    <a href='uebersicht.php'>übersichtsseite</a>

    </form>
HTML;
    return $html;
}

?>