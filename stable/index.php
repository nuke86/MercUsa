<?php
include('include.php');

ini_set("session.gc_maxlifetime","86400");
ini_set("session.cookie_lifetime","86400");
session_start();
//se non c'e la sessione registrata
if (!$_SESSION['autorizzato']) {
  header("Location: login_form.php");
  die;
}

//Altrimenti Prelevo il codice identificatico dell'utente loggato e controllo che sia coerente col DB
$cod = $_SESSION['cod']; //id cod recuperato nel file di verifica
$idU = $_SESSION['idU'];
// piccola verifica per stabilire se l'utente loggato e' davvero di questa azienda o e' un FAKE
$query = "SELECT * FROM mercusa_users WHERE username = '$cod' AND id = '$idU' ";
$ris = mysqli_query($con, $query) or die (mysqli_error($con));
$riga = mysqli_fetch_array($ris); 
$cod = $riga['username'];
// Effettuo il controllo 
if ($cod == NULL) $trovato = 0 ;
else $trovato = 1; 

if ($trovato == 0) { // altrimenti rimando al login, sempre e comunque.
	echo '<script language=javascript>document.location.href="login_form.php"</script>';
} else {

	include('header.php');
	
	//Azioni della barra di navigazione
	if ($_GET['loc']=='01'){
		include('modulo_clienti.php');
	} elseif ($_GET['loc']=='02'){
		include('modulo_articoli.php');
	} elseif ($_GET['loc']=='03'){
		include('modulo_documenti.php');
	} elseif ($_GET['loc']=='04'){
		include('modulo_contabilita.php');
	} elseif ($_GET['loc']=='05'){
		include('modulo_extra.php');
	} elseif (($_GET['loc']=='') OR ($_GET['loc']=='00')){
		include('modulo_start.php');
	}
	
	include('footer.php');
} 
?>
