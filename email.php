<?php
// hier kann ich selber E-Mails schreiben und versenden, wenn ich einen server habe. 
// Mario, hier verwendest du denn bplace.de server. 
// da hast du einen. Name war mario.kai.sigismondi@gmail.com und passwort glaube ich schinken2010
//Normale Includes
include	("include/functions.php");
include ("include/config.php");

//Muss drinne sein
include ("include/phpmailer/class.phpmailer.php");

// Parameter einlesen
$aktion = $_POST["aktion"] ?? "";

// Variablen deklarieren und initialisieren
$ausgabe = "";

//E-Mail versendungsformular
$ausgabe .= "<h2> Formular </h2>\n".
"<form action='email.php' method='post'>\n".
    "<p><input name='absender'>Ihre E-Mail</p>\n".
    "<p><input name='betreff'>Ihr Betreff</p>\n".
    "<p><textarea name='nachricht' cols='50' rows='5'></textarea> Ihre Nachricht</p>\n".
    "<p><input type='submit' value='Senden' name='aktion'> <input type='reset'></p>\n".
"<form>\n";

//funktion für die E-Mail versendung
if($aktion == "Senden")
{
    if(mail("hier die mail vom absender eintragen", $_POST["betreff"],
        $_POST["nachricht"], "From: " . $_POST["absender"]))
            echo "Mail zum Senden akzeptiert";
    else
            echo "Mail nicht zum Senden akzeptiert";
}
?>
<!Doctype html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="" type="text/css">
	</head>
	<header>

	</header>
	<nav>
		<div id=navWrap>
		</div>
	</nav>
	<body>
		<main>
			<?php echo $ausgabe; ?>
		</main>
		<footer>
			
		</footer>
	</body>
</html>