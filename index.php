<?php

include ("include/functions.php");
include ("include/config.php");
$fehler = "";
// Login
if(isset($_POST['aktion'])) 
{
	$e_mail  = $_POST['e_mail'];  
    $passwort = $_POST['passwort'];
    $db   = db_connect();
    $query  = "SELECT e_mail,passwort FROM user  WHERE e_mail = '$e_mail'";
    // $query = "SELECT rechte FROM rechte WHERE rechte = '$rechte_id'";
    $result = mysqli_query($db,$query);
    $result = mysqli_fetch_assoc($result);
    db_close($db);
    
    $fehler = false;
    if(!empty($result))
    {
        if(password_verify($passwort,$result['passwort']))
		{
			session_start();    
            $_SESSION['e_mail']         = $e_mail;
            $_SESSION['rechte_id']      = "";
            // $_SESSION['rechte_id']      = $rechte_id;
			header('Location:uebersicht.php');  		
			die;
        }
    }
	if($fehler)
	{
		$fehler = "<h1 class=\"fehler\">falsches passwort oder name</h1>";
    }
}
?> 
<!DOCTYPE HTML> 
    <html>  
        <head>    
                <title>Passworteingabe</title>    
            <meta charset="utf-8"> 
            <link rel="stylesheet" href="css/style.css"> 
            <style>
            #login{
                width:200px;
                background-color:#FFF;
                margin-top: 100px;
                margin-bottom: 0px;
                margin-right: 50%;
                margin-left: 45%;
            }
            legend{
                font-style:italic;
                font-weight:bold;
                color:#F00;
            }
            #e_mail,#passwort{
                width:200px;
                margin:1px auto;
            }
            </style>
        </head>  
    <body>   
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">  
            <fieldset id="login">    
                <legend> Login </legend>
                <label for="e_mail">E-Mail:</label>
                <input type="text" name="e_mail" id="e_mail"><?php echo $fehler; ?>
                <label for="pass">Passwort:</label>
                <input type="password" name="passwort" id="passwort"><?php echo $fehler; ?><br>
                <input type="submit" name="aktion" value="login">   
                <a href="registrierung.php">Registrieren</a>
            </fieldset>   
        </form>  
    </body> 
    </html> 