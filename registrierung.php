<?php
// hier regestrieren sich die user auf der seite, damit sie zugang haben und die seite auch verwenden können.

// Hier wird alles, was ich brauche für das programm eingebunden
include ("include/functions.php");
include ("include/config.php");

//Variablen für das Template deklarieren und initialisieren
$title                  = "registrierung";
$ausgabe                = "";
$action                 = "";
$db                     = "";
$wrapper                = "wrapperein";
$amail                  = "";
$strort                 = "";

// Variablen für Steuerung deklarieren und initialisieren
$err_flag = true;

// Variablen für die Fehlermeldungen deklarieren und initialisieren
$err_vname              = "";
$err_nname              = "";
$err_plz                = "";
$err_ort                = "";
$err_str                = "";
$err_hn                 = "";
$err_e_mail             = "";
$err_passwort           = "";

// Variablen der FormularFelder deklarieren und initialisieren
// geschieht durch nutzung der Funtion get_param()
// Überprüfung und setzen der Werte auf übergebenen Formularwerte oder ""

$action                 = get_param("senden",'get');
$vname                  = get_param("vname",'get');
$nname                  = get_param("nname",'get');
$plz                    = get_param("plz",'get');
$ort                    = get_param("ort",'get');
$str                    = get_param("str",'get');
$hn                     = get_param("hn",'get');
$e_mail                 = get_param("e_mail",'get');
$passwort               = get_param("passwort",'get');

// Fehlerprüfung und Ausgabesteuerung
if($action != "")
{
    $err_flag = false;

    $fehlermeldung = "\n<span class='fehler'>Fehler!</span>\n";
    if($vname == "")
            {
                $err_flag = true;
                $err_vname = $fehlermeldung;
            }
    if($nname == "")
            {
                $err_flag = true;
                $err_nname = $fehlermeldung;
            }
    if(!plz($plz))
            {
                $err_flag = true;
                $err_plz = $fehlermeldung;
            }
    if(!strort($ort))
            {
                $err_flag = true;
                $err_ort = $fehlermeldung;
            }
    if(!strort($str))
            {
                $err_flag = true;
                $err_str = $fehlermeldung;
            }
    if($hn == "")
            {
                $err_flag = true;
                $err_hn = $fehlermeldung;
            }
    if(!e_mail($e_mail))
            {
                $err_flag = true;
                $err_e_mail = $fehlermeldung;
            }
    if($passwort == "")
            {
                $err_flag = true;
                $err_passwort = $fehlermeldung;
            }
}
if($err_flag == false)
    {
        $options = ['cost' => 12,];
        $passwort = password_hash($passwort, PASSWORD_BCRYPT, $options);

        $ausgabe .= 
        "Ihr Daten wurden übermittelt!<br>";
        $db = db_connect();
            
        // tabelle user
        $query = "INSERT INTO kochbuch.user (users_id,vname,nname,plz,ort,str,hn,e_mail,passwort,rechte_id) VALUES (NULL,'$vname','$nname','$plz','$ort','$str','$hn','$e_mail','$passwort','$rechte_id')";
            mysqli_query($db,$query) or die("Fehler beim Datenschreiben ".mysqli_error($db));
            $user_id = mysqli_insert_id($db);

        db_close($db); 

        echo $ausgabe .= "\n<a href=index.php> zum login zurück</a>\n";
    }

    else
    {
    $db = db_connect();
    $ausgabe = null;
;
    $ausgabe .= "<fieldset id=\"registrierung\">\n";
    $ausgabe .= "<legend> Bitte ausfüllen </legend>\n";

        //felder
        $ausgabe .= "\n";
        $ausgabe .= "<form action=\"$_SERVER[PHP_SELF]\" method=\"get\">\n";

        $ausgabe .= "<label for=\"rezepte\">Vorname:</label>\n";
        $ausgabe .= "<input type=\"text\" name=\"vname\" id=\"vname\" value=\"$vname\"> $err_vname\n<br>\n";

        $ausgabe .= "<label for=\"rezepte\">Nachname:</label>\n";
        $ausgabe .= "<input type=\"text\" name=\"nname\" id=\"nname\" value=\"$nname\"> $err_nname\n<br>\n";

        $ausgabe .= "<label for=\"rezepte\">Postleitzahl:</label>\n";
        $ausgabe .= "<input type=\"text\" name=\"plz\" id=\"plz\" value=\"$plz\"> $err_plz\n<br>\n";

        $ausgabe .= "<label for=\"rezepte\">Ort:</label>\n";
        $ausgabe .= "<input type=\"text\" name=\"ort\" id=\"ort\" value=\"$ort\"> $err_ort\n<br>\n";

        $ausgabe .= "<label for=\"rezepte\">Strasse:</label>\n";
        $ausgabe .= "<input type=\"text\" name=\"str\" id=\"str\" value=\"$str\"> $err_str\n<br>\n";

        $ausgabe .= "<label for=\"rezepte\">Hausnummer:</label>\n";
        $ausgabe .= "<input type=\"text\" name=\"hn\" id=\"hn\" value=\"$hn\"> $err_hn\n<br>\n";

        $ausgabe .= "<label for=\"rezepte\">E_Mail:</label>\n";
        $ausgabe .= "<input type=\"text\" name=\"e_mail\" id=\"e_mail\" value=\"$e_mail\"> $err_e_mail\n<br>\n";

        $ausgabe .= "<label for=\"rezepte\">Passwort:</label>\n";
        $ausgabe .= "<input type=\"password\" name=\"passwort\" id=\"passwort\"> $err_passwort\n<br>\n";
       
        $ausgabe .= "\n<input type=submit name=senden value=Senden >\n";
        $ausgabe .= "</fieldset>\n";

        //Einbau des Submit Buttons zum abschicken des Formulars 
        // $ausgabe .= "\n<input type=submit name=senden value=Senden >\n";

        $ausgabe .="\n</form>\n";

        include("template/template.php");

        db_close($db);
    }
?>