<?php

include_once "include.php";

$campi_ricevuta = query_select_detail($con, $_GET['id'], 'mercusa_documenti');
$campi_cliente = query_select_detail($con, $campi_ricevuta['id_cliente'], 'mercusa_clienti');

function query_select_list_mov_carico_cv($con, $id_documento){
	
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_articoli WHERE codice = '$id_documento' AND contabilita = 'cv' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
    
     // recupero il contenuto di ogni record trovato
    	$id = $results['id'];
	$contab_articolo = $results['contabilita'];
	$nome_articolo = $results['nome'];
	$descrizione_articolo = $results['descrizione'];
	$prezzo_articolo = (float)$results['prezzo'];
	$iva = $results['iva'];
	$perc_provv = $results['provvigione']; //percentuale di provvigione spettante al negozio
	if ($perc_provv == '0') { $perc_provv = 50; }
	
	$prezzo_iva = "";
	if ($iva != ""){
		$prezzo_iva = incremento_iva_articolo($con, $id);
	} else {
		$prezzo_iva = $prezzo_articolo;
	}
	
	$quantita = (int)$results['quantita'];
	$prezzo_provvigione = (($prezzo_articolo*$perc_provv)/100)*$quantita;
	$prezzo_provvigione = money_format('%.2n',$prezzo_provvigione);
	
	if ($contab_articolo=='cv') $print_contab='CV';
	
	$totale_prezzi=$totale_prezzi+($prezzo_provvigione);
	
	$riga .= "
	<tr>
		<td>$print_contab"."00$id</td>
		<td>$nome_articolo $descrizione_articolo</td>
		<td align=\"center\">$quantita</td>
		<td align=\"center\"><b>$prezzo_articolo</b></td>
		<td align=\"center\">$iva %</td>
		<td align=\"center\">$prezzo_iva</td>
		<td align=\"center\">$perc_provv %</td>
		<td align=\"center\">$prezzo_provvigione</td>
	</tr>
	";
	
	}
	
	return array($riga,$totale_prezzi);	
}

function query_select_list_mov_carico_ct($con, $id_documento){
	
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_articoli WHERE codice = '$id_documento' AND contabilita = 'ct' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
    
     // recupero il contenuto di ogni record rovato
    	$id = $results['id'];
	$contab_articolo = $results['contabilita'];
	$nome_articolo = $results['nome'];
	$descrizione_articolo = $results['descrizione'];
	$prezzo_articolo = (float)$results['prezzo'];
	$prezzo_acq = money_format('%.2n',$results['prezzo_acq']);
	$iva = $results['iva'];

	$prezzo_iva = "";
	if ($iva != ""){
		$imponibile = "1.".$iva;
		$prezzo_iva = ($prezzo_acq/($imponibile));
		$prezzo_iva = money_format('%.2n',$prezzo_iva);
	} else {
		$prezzo_iva = $prezzo_acq;
	}
	
	$quantita = (int)$results['quantita'];
	if ($contab_articolo=='ct') $print_contab='CT';
	
	$totale_prezzi=$totale_prezzi+($prezzo_acq);
	
	$riga .= "
	<tr>
		<td>$print_contab"."00$id</td>
		<td>$nome_articolo $descrizione_articolo</td>
		<td align=\"center\">$quantita</td>
		<td align=\"center\">$prezzo_iva</td>
		<td align=\"center\">$iva %</td>
		<td align=\"center\">$prezzo_acq</td>
	</tr>
	";
	
	}
	
	return array($riga,$totale_prezzi);	
}

$campi_lista_cv = query_select_list_mov_carico_cv($con, $_GET['id']);
$campi_lista_ct = query_select_list_mov_carico_ct($con, $_GET['id']);

echo $headerStampeHTML;
echo $headerStampeDocumenti;

?>

<?php if ($campi_lista_cv[0]!=""){ ?>
<p align="center"><b>LISTA DEGLI OGGETTI RICEVUTI DAL MANDANTE IN CONTO VENDITA</b></p>
<table width="100%" style="font-size: 0.9em;" border="1">
	<tr>
	<td align="center">DOC n.
		<b><?php echo $campi_ricevuta['num_doc']; ?></b>
	</td><td align="center"><b><?php echo $campi_ricevuta['data_doc']; ?></b>
	</td>
	<td colspan="5">DATI IDENTIFICATIVI MANDANTE<br />
		<b><?php echo $campi_cliente['cognome'].' '.$campi_cliente['nome']. '</b> '.$campi_cliente['indirizzo']; ?>
	</td>
	</tr>
	<tr><td align="center">COD ARTICOLO</td><td align="center">DESCRIZIONE</td><td align="center">QUANTITA'</td><td align="center">IMPORTO</td><td align="center">IVA</td><td align="center">PREZZO di VENDITA</td><td align="center">% PROVVIGIONE NEGOZIO</td><td align="center">PROVVIGIONE al MANDANTE</td></tr>
<?php echo $campi_lista_cv['0']; ?>
	<tr>
	<td colspan="7" align="right">TOTALE:	
	</td><td align="center"><b><?php echo money_format('%.2n',$campi_lista_cv['1']); ?> &euro;</b></td></tr>
</table>
<table width="100%" style="font-size: 0.9em;">
	<tr>
		<td><br /><br />
		Per accettazione dell'incarico e per ricevuta <br />degli oggetti ricevuti
		</td>
		<td align="right"><br /><br />
		Confermo gli oggetti in vendita alle condizioni <br />del Mandato di Vendita
		</td>
	</tr>
	<tr>
		<td><br /><br />
		Il Mandatario ______________________
		</td>
		<td align="right"><br /><br />
		Il Mandante ______________________
		</td>
	</tr>
</table>

<?php } elseif ($campi_lista_ct[0]!=""){ ?>
<p align="center"><b>LISTA DEI BENI USATI ACQUISTATI</b></p>
<table width="100%" style="font-size: 0.9em;" border="1">
	<tr>
	<td align="center">DOC n.
		<b><?php echo $campi_ricevuta['num_doc']; ?></b>
	</td><td align="center"><b><?php echo $campi_ricevuta['data_doc']; ?></b>
	</td>
	<td colspan="5">DATI IDENTIFICATIVI MANDANTE<br />
		<b><?php echo $campi_cliente['cognome'].' '.$campi_cliente['nome']. '</b> '.$campi_cliente['indirizzo']; ?>
	</td>
	</tr>
	<tr><td align="center">COD ARTICOLO</td><td align="center">DESCRIZIONE</td><td align="center">QUANTITA'</td><td align="center">IMPONIBILE</td><td align="center">IVA</td><td align="center">PREZZO di ACQUISTO</td></tr>
<?php echo $campi_lista_ct['0']; ?>
	<tr>
	<td colspan="5" align="right">TOTALE:	
	</td><td align="center"><b><?php echo money_format('%.2n',$campi_lista_ct['1']); ?> &euro;</b></td></tr>
</table>
<table width="100%" style="font-size: 0.9em;">
	<tr>
		<td><br /><br />
		Per accettazione dell'incarico e per ricevuta <br />degli oggetti acquistati
		</td>
		<td align="right"><br /><br />
		Confermo gli oggetti ceduti alle condizioni <br />sopra indicate
		</td>
	</tr>
	<tr>
		<td><br /><br />
		Il Negoziante ______________________
		</td>
		<td align="right"><br /><br />
		Il Fornitore ______________________
		</td>
	</tr>
</table>
<?php } ?>
<br />
<?php
if (($campi_lista_ct['0']=="")AND($campi_lista_cv['0']=="")){
	echo "<p align=\"center\">DOCUMENTO ANNULLATO</p>";
}
?>
</body>
</html>