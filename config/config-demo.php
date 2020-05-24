<?php
// impostazioni del database Mysql
$mysql_ip = "localhost";
$db_name = "mercusademo";
$db_user = "root";
$db_pass = "";

// connessione utilizzata per tutte le funzioni di Database in tutto MercUsa
$con=mysqli_connect($mysql_ip,$db_user,$db_pass,$db_name);
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
function selectSettingsArray($con,$setting){
	$query = "SELECT * FROM mercusa_settings WHERE nome = '$setting'";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	$riga = mysqli_fetch_array($result);
	return $riga;
}

// configurazione del programma
//ini_set('session.gc_maxlifetime','14400');  // impostazione per la durata delle sessioni.
$setting1 = selectSettingsArray($con,'configModuloPunti');
$configModuloPunti = $setting1['contenuto']; // modulo di gestione tessere punti per i clienti

$setting2 = selectSettingsArray($con,'configIvaEtichette');
$configIvaEtichette = $setting2['contenuto']; // mostra automaticamente IVA negli importi delle etichette.

$configTplMandato = "../servers/demo/stampa_mandato_tpl.php"; // file con il template modificabile del mandato di vendita

$setting3 = selectSettingsArray($con,'rimborsoImmediato');
$rimborsoImmediato = $setting3['contenuto'];

$setting4 = selectSettingsArray($con,'rimborsoConIva');
$rimborsoConIva = $setting4['contenuto'];

$setting5 = selectSettingsArray($con,'ColonnaScontiVendita');
$ColonnaScontiVendita = $setting5['contenuto'];

$setting6 = selectSettingsArray($con,'CustomProvvigioni');
$CustomProvvigioni = $setting6['contenuto'];

$setting7 = selectSettingsArray($con,'ArrotondaPrezzi');
$ArrotondaPrezzi = $setting7['contenuto'];

// Dati e Intestazioni aziendali
$setting8 = selectSettingsArray($con,'nome_azienda');
$nome_azienda = $setting8['contenuto'];

$setting9 = selectSettingsArray($con,'titolare_azienda');
$titolare_azienda = $setting9['contenuto'];

$setting10 = selectSettingsArray($con,'emailAzienda');
$emailAzienda = $setting10['contenuto'];

$setting11 = selectSettingsArray($con,'indirizzo_azienda');
$indirizzo_azienda = $setting11['contenuto'];

$setting12 = selectSettingsArray($con,'pivaAzienda');
$pivaAzienda = $setting12['contenuto'];

$setting13 = selectSettingsArray($con,'cfAzienda');
$cfAzienda = $setting13['contenuto'];

$setting14 = selectSettingsArray($con,'titolo_etichetta');
$titolo_etichetta = $setting14['contenuto']; // testata dell'etichetta per gli articoli

$setting15 = selectSettingsArray($con,'phone_azienda');
$phone_azienda = $setting15['contenuto'];

$setting16 = selectSettingsArray($con,'cellphone_azienda');
$cellphone_azienda = $setting16['contenuto'];

$setting17 = selectSettingsArray($con,'web_azienda');
$web_azienda = $setting17['contenuto'];

$setting18 = selectSettingsArray($con,'url_imgLogo');
$url_imgLogo = "/mercusa/accounts/$endofurl/img/".$setting18['contenuto'];

$setting19 = selectSettingsArray($con, 'scad_abb');
$scad_abb = $setting19['contenuto'];

$setting20 = selectSettingsArray($con, 'tipo_abb');
$tipo_abb = $setting20['contenuto'];


// intestazioni aziendali da applicare alle stampe dei documenti generati da MercUsa
// cambiare immagine logo e scritte aziendali
$headerStampeHTML = "
	<html>
	<head>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"bootstrap.css\" />
		<link rel=\"stylesheet\" type=\"text/css\" href=\"bootstrap.min.css\" />
	</head>
";
$headerStampeDocumenti = "
	<body onload=\"window.print()\" style=\"font-size: 1.2em;\">
	<table width=\"100%\">
		<tr><td width=\"70%\">
		<h5>
		<b>$nome_azienda</b> di $titolare_azienda<br />
		$indirizzo_azienda<br />
		P.I. <b>$pivaAzienda</b> - C.F. <b>$cfAzienda</b> <br />
		Tel. $phone_azienda - Cell. $cellphone_azienda<br />
		E-mail: $emailAzienda - Web: $web_azienda
		</h5>
		</td>
		<td align=\"center\">
		<img src=\"$url_imgLogo\" width=\"250px\" height=\"83px\" />
		</td>
		</tr>
	</table>
";
$headerExportPDFdocumenti = "
<table style=\"width: 100%;\">
	<tr><td>
	<h5>
	<b>$nome_azienda</b> di $titolare_azienda<br />
	$indirizzo_azienda<br />
	P.I. <b>$pivaAzienda</b> - C.F. <b>$cfAzienda</b> <br />
	Tel. $phone_azienda - Cell. $cellphone_azienda<br />
	E-mail: $emailAzienda - Web: $web_azienda
	</h5>
	</td>
	<td style=\"width: 50%;\" align=\"center\">
	<img src=\"$url_imgLogo\" style=\"width: 250px; height: 83px;\" />
	</td>
	</tr>
</table>
";
$imagesURL = "http://95.110.226.234/mercusa/accounts/demo/";

?>
