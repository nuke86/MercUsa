<?php

include_once "include.php";

$campi_doc = query_select_detail($con, $_GET['id'], 'mercusa_documenti');
$campi = query_select_list_articoli_rimborsati($con, $_GET['id']);
$campi_cliente = query_select_detail($con, $campi_doc['id_cliente'], 'mercusa_clienti');

// stampa le righe che popolano la tabella dei rimborsi spettanti a $id_cliente
$righe = $campi[0];
echo $headerStampeHTML;
echo $headerStampeDocumenti;

?>

<p align="center"><b>DOCUMENTO DI RIMBORSO AL MANDANTE</b></p>
<table width="100%" style="font-size: 0.9em;" border="1">
	<tr>
	<td align="center">DOC n.
		<b><?php echo $campi_doc['num_doc']; ?></b>
	</td>
	<td align="center"><b><?php echo $campi_doc['data_doc']; ?></b>
	</td>
	<td colspan="5">DATI IDENTIFICATIVI MANDANTE<br />
		<b><?php echo $campi_cliente['cognome'].' '.$campi_cliente['nome']. '</b> '.$campi_cliente['indirizzo']; ?><br />
		Nato il: <?php echo $campi_cliente['data_nascita'].' CF '.$campi_cliente['cod_fiscale']. ' PI '.$campi_cliente['p_iva']; ?>
	</td>
	</tr>
	<tr>
		<td align="center">COD ARTICOLO</td>
		<td align="center">NOME ARTICOLO</td>
		<td align="center">DATA di VENDITA</td>
		<td align="center">PREZZO</td>
		<?php if ($rimborsoConIva == 'yes'){ ?>
			<td align="center">PROVVIGIONE NEGOZIO</td>
			<td align="center">IMPONIBILE</td>
			<td align="center">IVA</td>
		<?php } ?>
		
		<td align="center">IMPORTO di RIMBORSO</td>
		
		<?php if ($rimborsoConIva == 'no'){ ?>
			<td align="center">SCONTO %</td>
			<td align="center">RIMBORSO SCONTATO</td>
		<?php } ?>
	</tr>
<?php echo $righe; ?>
	<tr>
		<td colspan="3" align="right">
		<?php if ($rimborsoConIva == 'yes'){ ?>
			<b>TOTALE IMPONIBILE: <b><?php echo money_format('%.2n',$campi[3]); ?> Euro</b><br />
			<b>TOTALE IVA: <b><?php echo money_format('%.2n',$campi[2]); ?> Euro</b><br />
		<?php } ?>	
			<b>TOTALE AL MANDANTE: <b><?php echo money_format('%.2n',$campi[1]); ?> Euro</b>
		</td>
	</tr>
	<tr><td colspan="3" align="right">Il seguente documento ha valore di ricevuta per il totale indicato come 
	compenso delle vendite eseguite nelle date sopra indicate.</td></tr>
</table>
<br />
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
