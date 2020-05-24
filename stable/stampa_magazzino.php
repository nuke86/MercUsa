<?php
include_once "include.php";

$data_inizio = $_GET['dataInizio'];
$data_fine = $_GET['dataFine'];
$riga = query_stampa_magazzino($con,$data_inizio,$data_fine);
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
</head>
<body style="font-size: 1.1em;" onload="window.print()">
<table width="100%">
<tr><td width="70%">
<h5>
<b>Il Ripostiglio</b> di Francesca Grussu<br />
Via Piemonte 37 A - Quartu Sant'Elena (CA)<br />
P.I. <b>03566510925</b> - C.F. <b>GRSFNC82E64B354I</b> <br />
Tel. 070/2043818 - Cell. 338/8957479<br />
E-mail: info@ilripostiglio.net - Web: www.ilripostiglio.net
</h5>
</td>
<td align="center">
<img src="http://www.ilripostiglio.net/img/logo.png" width="250px" height="83px" />
</td>
</tr>
</table><br />
<h3 align="center">Situazione di Magazzino dal: <?php echo $data_inizio; ?>  al: <?php echo $data_fine; ?></h3>
<table class="table">
<tr>
<td><b># Codice</b></td><td><b>Nome</b></td><td><b>Descrizione</b></td><td><b>Prezzo &euro;</b></td>
<td><b>Quantita</b></td><td><b>Data Inserimento</b></td>
</tr>
<?php echo $riga[0]; ?>
<tr><td colspan="3" align="right"><b>TOTALE:</b></td><td><?php echo $riga[1]; ?></td></tr>
</table>
</body>
</html>