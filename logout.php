<?php
// Logout
session_start();
unset($_SESSION['e_mail']);
unset($_SESSION['passwort']);
session_destroy(); 
header('Location:uebersicht.php'); 
 ?> 