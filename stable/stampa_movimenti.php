<?php

include_once "include.php";
$campi_cliente = query_select_detail($con, $_GET['id_cliente'], 'mercusa_clienti');

?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
</head>
<body onload="window.print()" style="font-size: 1.2em;">
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
</table>

<p align="center"><b>LISTA MOVIMENTI DEL MANDANTE</b></p>
<table width="100%" style="font-size: 0.9em;" border="1">
	<tr>
	<td align="center"><b><?php echo date('d/m/Y'); ?></b>
	</td>
	<td colspan="10">DATI IDENTIFICATIVI MANDANTE<br />
		<b><?php echo $campi_cliente['cognome'].' '.$campi_cliente['nome']. '</b> '.$campi_cliente['indirizzo']; ?>
	</td>
	</tr>
	<tr>
		<td><b># Codice Articolo</b></td>
		<td><b>Nome</b></td>
		<td><b>Quantit&agrave;</b></td>
		<td><b>Prezzo di Carico</b></td>
		<td><b>Rimborso</b></td>
		<td><b>% Sconto</b></td>
		<td><b>Rimborso scontato</b></td>
		<td><b>Data di Carico</b></td>
		<td><b>Data di Vendita</b></td>
		<td><b>Rimborsato</b></td>
		<td><b>Reso il</b></td>
	</tr>
	
<?php echo query_lista_movimenti_cliente($con, $_GET['id_cliente'], 0); ?>
	<tr><td colspan="10" align="right">Il seguente documento ha valore di Informativa di Trasparenza come storico completo di tutti i movimenti in entrata e in uscita eseguiti c/o <?php echo $nome_azienda; ?></td></tr>
</table><br />
<hr />
<br />
</body>
</html>