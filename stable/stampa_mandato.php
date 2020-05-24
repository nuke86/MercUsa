<?php

include_once "include.php";

$campi_cliente = query_select_detail($con, $_GET['id'], 'mercusa_clienti');

if ($campi_cliente[13]=='1') {
	$documento = "Carta d'Identit&agrave;";
} elseif ($campi_cliente[13]=='2') {
	$documento = "Patente di Guida";
} elseif ($campi_cliente[13]=='3') {
	$documento = "Patente Nautica";
} elseif ($campi_cliente[13]=='4') {
	$documento = "Porto d'armi";
} elseif ($campi_cliente[13]=='5') {
	$documento = "Passaporto";
}

include "$configTplMandato";
?>

