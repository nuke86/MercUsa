<?php
include_once "include.php";

$campi_doc = query_select_detail($con, $_GET['id'], 'mercusa_documenti');
$campi_cliente = query_select_detail($con, $campi_doc['id_cliente'], 'mercusa_clienti');
$riga = query_select_list_articoli_resi($con, $_GET['id']);
$riga_doc = $riga[1];

echo $headerStampeHTML;
echo $headerStampeDocumenti;
?>

<p align="center"><b>DOCUMENTO DI RESO AL MANDANTE</b></p>
<table width="100%" style="font-size: 0.9em;" border="1">
	<tr>
	<td align="center">DOC n.
		<b><?php echo $campi_doc['num_doc']; ?></b>
	</td>
	<td align="center"><b><?php echo $campi_doc['data_doc']; ?></b>
	</td>
	<td colspan="3">DATI IDENTIFICATIVI MANDANTE<br />
		<b><?php echo $campi_cliente['cognome'].' '.$campi_cliente['nome']. '</b> '.$campi_cliente['indirizzo']; ?>
	</td>
	</tr>
	<tr>
		<td align="center">COD ARTICOLO</td>
		<td align="center">NOME ARTICOLO</td>
		<td align="center">DATA DI CARICO</td>
		<td align="center">QUANTITA' RESA</td>
		<td align="center">DATA SCONTO 90</td>
		<td align="center">LISTA DI CARICO</td>
	</tr>
<?php echo $riga_doc; ?>
	<tr><td colspan="3" align="right">Il seguente documento ha valore di ricevuta per gli articoli sopra elencati di propriet&agrave; del mandante.</td></tr>
</table>
<br />
<?php
if ($riga_doc==""){
	echo "<p align=\"center\">DOCUMENTO ANNULLATO</p>";
}
?>
<hr />
<table width="100%" style="font-size: 0.9em;">
	<tr>
		<td>
		Per ricevuta
		</td>
	</tr>
	<tr>
		<td>
		______________________
		</td>
	</tr>
</table>
<br />
</body>
</html>