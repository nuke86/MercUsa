<?php
// configurazione del programma
//ini_set('session.gc_maxlifetime','14400');  // impostazione per la durata delle sessioni.

// Dati e Intestazioni aziendali
$nome_azienda = "Il Ripostiglio di Francesca Grussu";
$titolare_azienda = "Francesca";
$indirizzo_azienda = "Via Piemonte 37/A - 09045 Quartu S. Elena (CA)";
$titolo_etichetta = "Il Ripostiglio";

// impostazioni del database Mysql
$mysql_ip = "localhost";
$db_name = "mercusa";
$db_user = "root";
$db_pass = "";

// connessione utilizzata per tutte le funzioni di Database in tutto MercUsa
$con=mysqli_connect($mysql_ip,$db_user,$db_pass,$db_name);
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>
