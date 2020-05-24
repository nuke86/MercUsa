<?php
include_once "include.php";

$campi_ricevuta = query_select_detail($con, $_GET['id'], 'mercusa_documenti');
$campi_cliente = query_select_detail($con, $campi_ricevuta['id_cliente'], 'mercusa_clienti');
$campi_lista = query_select_list_mov_ricevuta($con, $_GET['id']);

echo $headerStampeHTML;
echo $headerStampeDocumenti;
?>

<p align="center"><b>RICEVUTA DI VENDITA</b></p>
<table width="100%" border="1">
	<tr><td align="center">DOC n.
		<b><?php echo $campi_ricevuta['num_doc']; ?></b>
	</td><td align="center">
		<b><?php echo $campi_ricevuta['data_doc']; ?></b>
	</td>
	<?php if ($campi_ricevuta['id_cliente'] != 0){ ?>
	<td colspan="3">Spett.le<br />
		<b><?php echo $campi_cliente['nome'].' '.$campi_cliente['cognome']. '</b> '.$campi_cliente['indirizzo'].' - '.$campi_cliente['citta'].' - '.$campi_cliente['cap'].' P.I.'.$campi_cliente['p_iva']; ?>
	</td>
	<?php } ?>
	</tr>
	<tr>
	<td align="center">COD ARTICOLO</td>
	<td align="center">DESCRIZIONE</td>
	<td align="center">QUANTITA'</td>
	<td align="center">PREZZO</td>
		<?php if ($endofurl != 'magazzeno'){ ?>
			<td align="center">% SCONTO</td><td align="center">IMPORTO</td>
		<?php } ?>
	
	</tr>
<?php echo $campi_lista['0']; ?>
	<tr><td colspan="3" align="right">TOTALE RICEVUTA: <b>&euro; <?php 
		if ($ArrotondaPrezzi == "yes"){
			echo money_format('%.1n0',$campi_lista['1']); 
		} else {
			echo money_format('%.2n',$campi_lista['1']);
		}
		?></b></td></tr>
</table><br />
	<?php 
	if ($campi_lista['0']==""){
		echo "<p align=\"center\">DOCUMENTO ANNULLATO</p>";
	}
	
	?>
<hr />
<br />
<?php echo $headerStampeDocumenti; ?>
<p align="center"><b>RICEVUTA DI VENDITA</b></p>
<table width="100%" border="1">
	<tr><td align="center">DOC n.
		<b><?php echo $campi_ricevuta['num_doc']; ?></b>
	</td><td align="center">
		<b><?php echo $campi_ricevuta['data_doc']; ?></b>
	</td>
	<?php if ($campi_ricevuta['id_cliente'] != 0){ ?>
	<td colspan="3">Spett.le<br />
		<b><?php echo $campi_cliente['nome'].' '.$campi_cliente['cognome']. '</b> '.$campi_cliente['indirizzo'].' - '.$campi_cliente['citta'].' - '.$campi_cliente['cap'].' P.I.'.$campi_cliente['p_iva']; ?>
	</td>
	<?php } ?>
	</tr>
	<tr>
	<td align="center">COD ARTICOLO</td>
	<td align="center">DESCRIZIONE</td>
	<td align="center">QUANTITA'</td>
	<td align="center">PREZZO</td>
		<?php if ($endofurl != 'magazzeno'){ ?>
			<td align="center">% SCONTO</td><td align="center">IMPORTO</td>
		<?php } ?>	
	
	</tr>
<?php echo $campi_lista['0']; ?>
	<tr><td colspan="3" align="right">TOTALE RICEVUTA: <b>&euro; <?php 
		if ($ArrotondaPrezzi == "yes"){
			echo money_format('%.1n0',$campi_lista['1']); 
		} else {
			echo money_format('%.2n',$campi_lista['1']);
		}
		?></b></td></tr>
</table>
<table width="100%" style="font-size: 0.9em;">
	<tr>
		<td><br />
		Per quietanza
		</td>
	</tr>
	<tr>
		<td><br /><br />
		______________________
		</td>
	</tr>
</table>
