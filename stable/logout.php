<?php
include_once("include.php");

session_start();
write_mysql_log("Utente uscito dal sistema, effettuato logout.", $con);
$_SESSION = array();
session_destroy(); //distruggo tutte le sessioni
setcookie("PHPSESSID","",time()-3600,"/");
header("location: login_form.php");
exit();
?>