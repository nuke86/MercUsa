<?php
//include 'config.php';
include_once "include.php"; 


if ($_POST['sez']=='apri_ric'){
	query_insert_new_doc($con, 2);
	header("Location: index.php?loc=00&sez=vend_dett01");
} elseif ($_POST['sez']=='apri_carico'){
	query_insert_new_doc($con, 1);
	header("Location: index.php?loc=02&sez=carico_multiplo01");
} elseif ($_POST['sez']=='apri_reso_fornitore'){
	query_insert_new_doc($con, 3);
	header("Location: index.php?loc=03&sez=reso_fornitore_aperto");
} elseif ($_GET['sez']=='new_art_reso'){
	query_new_art_reso($con);
	header("Location: index.php?loc=03&sez=reso_fornitore_aperto");
} elseif ($_POST['sez']=='aggiungi_articolo'){
	query_insert_new_mov($con);
	header("Location: index.php?loc=00&sez=vend_dett01");
} elseif ($_GET['sez']=='art_del'){
	query_delete_from($con, 'mercusa_movimenti', $_GET['id']);
	header("Location: index.php?loc=00&sez=vend_dett01");
} elseif ($_GET['sez']=='art_del_carico'){
	query_delete_from($con, 'mercusa_articoli', $_GET['id']);
	header("Location: index.php?loc=02&sez=carico_multiplo01");
} elseif ($_GET['sez']=='svuota'){
	query_svuota_ricevuta($con, $_GET['id']);
	//query_delete_from($con, 'mercusa_documenti', $_GET['id']);
	header("Location: index.php?loc=00&sez=vend_dett");
} elseif ($_GET['sez']=='svuota_carico'){
	query_svuota_carico($con, $_GET['id']);
	//query_delete_from($con, 'mercusa_documenti', $_GET['id']);
	header("Location: index.php?loc=02&sez=start");
} elseif ($_GET['sez']=='svuota_reso_fornitore'){
	query_svuota_reso_fornitore($con, $_GET['id']);
	//query_delete_from($con, 'mercusa_documenti', $_GET['id']);
	header("Location: index.php?loc=03&sez=reso_fornitore");
} elseif ($_POST['sez']=='stampa_ricevuta'){
	query_stampa_ricevuta($con, $_POST['id_documento']);
	header("Location: index.php?loc=00&sez=vend_dett");
} elseif ($_POST['sez']=='stampa_carico'){
	query_stampa_ricevuta($con, $_POST['id_documento']);
	header("Location: index.php?loc=02&sez=start");
} elseif ($_POST['sez']=='stampa_reso_fornitore'){
	query_salva_reso($con, $_POST['id_documento']);
	header("Location: index.php?loc=03&sez=reso_fornitore");
} elseif ($_POST['sez']=='scheda_rimborso'){
	$indirizzo = 'index.php?loc=04&sez=cont_rimborsi_cliente&id_cliente='.$_POST['id_cliente'];
	header("Location: $indirizzo");
} elseif ($_POST['sez']=='scheda_movimenti'){
	$indirizzo = 'index.php?loc=04&sez=scheda_movimenti&id_cliente='.$_POST['id_cliente'];
	header("Location: $indirizzo");
} elseif ($_GET['sez']=='apri_carico_chiuso'){
	query_apertura_carico_chiuso($con, $_GET['id']);
	header("Location: index.php?loc=02&sez=carico_multiplo");
} elseif ($_GET['sez']=='sconto'){
	query_aggiungi_sconto($con, $_GET['id'], $_POST['sconto']);
	header("Location: index.php?loc=00&sez=vend_dett01");
} elseif ($_GET['sez']=='add_new_prom'){
	add_new_prom($con);
	header("Location: index.php?loc=05&sez=promemoria");
} elseif ($_GET['sez']=='sendmail'){
	invia_mailingList($con);
	header("Location: index.php?loc=05&sez=gest_mailinglist");
} elseif ($_POST['sez']=='apri_fatt_acqu'){
	query_insert_new_doc($con, 5);
	header("Location: index.php?loc=04&sez=nuova_fattura_acquisto01");
} elseif ($_GET['sez']=='update_settings'){
	UpdateAllSettings($con);
	header("Location: index.php?loc=05&sez=settings");
}



// ******************************
// ******* funzioni utili *******
// ******************************

function query_insert_new_doc($con,$tipo_doc){
	// preparo le variabili
	// allo stato attuale tipo_doc: 1=lista di carico, 2=ricevuta di vendita
	// 3=reso a fornitore, 4=rimborso a fornitore, 5=fattura di acquisto
	if ($_POST['id_cliente'] != ''){
		$id_cliente = $_POST['id_cliente'];
	} else {
		$id_cliente = $_GET['id_cliente'];
	}
	session_start();
	$user = $_SESSION['cod'];
	if ($user == ""){
		header("Location: login_form.php");
		die();
	}
	if ($tipo_doc=="5") {
		$num_doc = $_POST['num_doc'];
	}else{
		// generazione del num_doc sequenziale sempre che non sia F.A.
		$num_doc = query_select_last_doc($con,$tipo_doc);
		if ($num_doc==""){
			$num_doc = 0;
		}
		$num_doc = $num_doc+1;
	}	
	if ($_POST['data_doc'] != ''){
		$data_doc = $_POST['data_doc']." ".date('H:i:s');
	} else {
		$data_doc = date('Y-m-d H:i:s');
	}
		
	$sql = "INSERT INTO mercusa_documenti (id, tipo_doc, id_cliente, num_doc, data_doc, open, user)
		VALUES (NULL, '$tipo_doc', '$id_cliente', '$num_doc', '$data_doc', '1','$user')";
	
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
	$last_doc = query_select_doc_open_details($con,$tipo_doc);
	$lastID = $last_doc[0];
	write_mysql_log("id=$lastID?user=$user?state=OPEN", $con);
	
}

function query_select_list_mov_ricevuta($con, $id_documento){
	global $ArrotondaPrezzi;
	global $ColonnaScontiVendita;
	
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_movimenti WHERE id_documento = '$id_documento' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
    
     // recupero il contenuto di ogni record rovato
     	$id = $results['id'];
	$id_articolo = $results['id_articolo'];
	
	$campi_articolo = query_select_detail($con, $id_articolo, 'mercusa_articoli');
	$nome_articolo = $campi_articolo['nome'];
	$prezzo_articolo_db = $campi_articolo['prezzo'];
	$prezzo_articolo = str_replace(',', '.', $prezzo_articolo_db);
	$iva_articolo = $campi_articolo['iva'];
	if ($iva_articolo != ""){
		$prezzo_articolo = incremento_iva_articolo($con, $id_articolo);
	}
	if ($ArrotondaPrezzi == "yes"){
		$prezzo_articolo = money_format('%.1n0',$prezzo_articolo);
	}
	$sconto = $campi_articolo['sconto'];
	
	$contab_articolo = $campi_articolo['contabilita'];
	if ($contab_articolo=='ct') $print_contab='CT';
	if ($contab_articolo=='cv') $print_contab='CV';
	if ($sconto != 0) {
		$prezzo_scontato = $prezzo_articolo-(($prezzo_articolo*$sconto)/100);
		$totale_prezzi = $totale_prezzi+$prezzo_scontato;
	}else{
		$prezzo_scontato = $prezzo_articolo;
		$sconto = "";
		$totale_prezzi = $totale_prezzi+$prezzo_articolo;
	}
	if ($ArrotondaPrezzi == "yes"){
		$prezzo_scontato = money_format('%.1n0',$prezzo_scontato);
	}
	if ($ColonnaScontiVendita == 'no') {
		$col_sconto = "";
	} else {
		$col_sconto = "<td align=\"center\">$sconto %</td><td align=\"center\">$prezzo_scontato</td>";
	}
	
	$riga .= "
	<tr><td>$print_contab"."00$id_articolo</td><td>$nome_articolo</td><td align=\"center\"> 1 </td><td align=\"center\">$prezzo_articolo</td>$col_sconto</tr>
	";
	}
	
	return array($riga,$totale_prezzi);	
}

function query_insert_new_mov($con){
	// preparo le variabili
	// inserimento movimento di vendita
	$id_articolo = $_POST['id_articolo'];
	$id_documento = $_POST['id_documento'];
	$data_mov = date('Y-m-d H:i:s');
		
	$sql="INSERT INTO mercusa_movimenti (id, data, quantita, id_articolo, id_documento)
		VALUES (NULL, '$data_mov', '1', '$id_articolo', '$id_documento')";
	
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
	//echo "1 record added";

	mysqli_close($con);
	
}


function query_select_last_doc($con, $tipo_doc){
	// pesca l'ultimo numero di documento per crearne poi il successivo
	// imposto ed eseguo la query
	$data_oggi = date("Y-01-01 00:00:00");
	
	$query = "SELECT * FROM mercusa_documenti WHERE tipo_doc = $tipo_doc AND data_doc > '$data_oggi' ORDER BY id DESC limit 1";
	$result = mysqli_query($con, $query) or die('Errore...');
	
	$results = mysqli_fetch_array($result);
	
	return $results['num_doc'];
	
}  

function query_select_list_articoli_resi($con, $id_documento){
	// questo genera una lista di articoli (stampata a video) di uno specifico documento di reso a fornitore
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_movimenti_resi WHERE id_reso = '$id_documento' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
		$id_articolo = $results['id_articolo'];
			$campi_articolo = query_select_detail($con, $id_articolo, 'mercusa_articoli');
			$id_cliente = $campi_articolo['id_cliente'];
				$cliente = query_select_detail($con, $id_cliente, 'mercusa_clienti');
				$mandante = $cliente['cognome']." ".$cliente['nome'];
			$nome = $campi_articolo['nome'];
			$descrizione = $campi_articolo['descrizione'];
			$data_inserimento = $campi_articolo['data_inserimento'];
			$data_sconto90 = date("Y-m-d", strtotime($data_inserimento. ' + 90 days'));
			$id_lista_carico = $campi_articolo['codice'];
				$doc_carico = query_select_detail($con, $id_lista_carico, 'mercusa_documenti');
				$lista_carico = $doc_carico['num_doc'];
			$prezzo = $campi_articolo['prezzo'];
			$contab_articolo = $campi_articolo['contabilita'];
				if ($contab_articolo=='ct') $print_contab='CT';
				if ($contab_articolo=='cv') $print_contab='CV';
		$quantita_resa = $results['quantita'];
		
		$riga .= "
		<tr><td>$print_contab"."00$id_articolo</td><td>$nome</td><td>$data_inserimento</td><td>$quantita_resa</td><td>$lista_carico</td><td>$mandante</td></tr>
		";
		$riga_x_stampa .= "
		<tr><td>$print_contab"."00$id_articolo</td><td>$nome</td><td>$data_inserimento</td><td>$quantita_resa</td><td>$data_sconto90</td><td>$lista_carico</td></tr>
		";
	}
	return array($riga,$riga_x_stampa);
}

function query_select_list_articoli_rimborsati($con, $id_documento){
	// questo genera una lista di articoli (stampata in doc) di uno specifico documento di rimborso a fornitore
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_movimenti WHERE id_rimborso = '$id_documento' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
		$id_articolo = $results['id_articolo'];
		$articolo = query_select_detail($con, $id_articolo, 'mercusa_articoli');
			$nome = $articolo['nome'];
			$descrizione = $articolo['descrizione'];
			$data_inserimento = $articolo['data_inserimento'];
			$prezzo = $articolo['prezzo'];
			$perc_provv = $articolo['provvigione'];
			if (($perc_provv == "")OR($perc_provv == "0")){
				$perc_provv = 50;
			}
			$importo_rimborso = $prezzo-(($prezzo*$perc_provv)/100);
			$euro_rimborso = money_format('%.2n',$importo_rimborso);
			$sconto = $articolo['sconto'];
			if ($sconto != "0"){
				$importo_rimborso_scontato = ($importo_rimborso-(($importo_rimborso*$sconto)/100));
				$importo_rimborso = money_format('%.2n',$importo_rimborso_scontato);
			}else{
				$importo_rimborso_scontato = "";
			}
			$totale = $totale+$importo_rimborso;
			$contab_articolo = $articolo['contabilita'];
			if ($contab_articolo=='ct') $print_contab='CT';
			if ($contab_articolo=='cv') $print_contab='CV';
		$data_vendita = $results['data'];
		
		global $rimborsoConIva;
		if ($rimborsoConIva == 'yes') {
			$imponibile = ($prezzo-$importo_rimborso)/1.22;
			$iva_art = ($prezzo-$importo_rimborso) - $imponibile;
			$imponibile = money_format('%.2n',$imponibile);
			$iva_art = money_format('%.2n',$iva_art);
			$totIVA = $totIVA+$iva_art;
			$totIMP = $totIMP+$imponibile;
			$riga .= "
				<tr>
				<td>$print_contab"."00$id_articolo</td>
				<td>$nome $descrizione</td><td>$data_vendita</td>
				<td align=\"center\">$prezzo</td><td align=\"center\">$perc_provv %</td>
				<td align=\"center\">$imponibile</td><td align=\"center\">$iva_art</td>
				<td align=\"center\">$importo_rimborso</td>
				</tr>
			";
		} else {
		
			$riga .= "
			<tr>
			<td align=\"center\">".$print_contab.$id_articolo."</td>
			<td align=\"center\">$nome $descrizione</td>
			<td align=\"center\">$data_vendita</td>
			<td align=\"center\">$prezzo</td>
			<td align=\"center\">$euro_rimborso</td>
			<td align=\"center\">$sconto</td>
			<td align=\"center\">$importo_rimborso_scontato</td>
			</tr>
			";
		}
	}
	return array($riga,$totale,$totIVA,$totIMP);
}

function query_select_list_mov($con, $id_documento){
	// questo genera una lista di movimenti (stampata a video) di uno specifico documento (usato per la cassa vendite)
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_movimenti WHERE id_documento = '$id_documento' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
    
     // recupero il contenuto di ogni record rovato
     	$id = $results['id'];
	$id_articolo = $results['id_articolo'];
	
	$campi_articolo = query_select_detail($con, $id_articolo, 'mercusa_articoli');
	$nome_articolo = $campi_articolo['nome'];
	$sconto = $campi_articolo['sconto'];
	$prezzo_articolo_db = $campi_articolo['prezzo'];
	$prezzo_articolo = str_replace(',', '.', $prezzo_articolo_db);
	$prezzo_articolo_db = str_replace(',', '.', $prezzo_articolo_db);
	$data_inserimento = $campi_articolo['data_inserimento'];
	$tipo_mandato = $campi_articolo['tipo_mandato'];
	$data_oggi = date("Y-m-d");
	$sconto60 = date("Y-m-d", strtotime($data_inserimento. ' + 60 days'));
	$sconto90 = date("Y-m-d", strtotime($data_inserimento. ' + 90 days'));
	$iva_articolo = $campi_articolo['iva'];
	$perc_provv = $campi_articolo['provvigione'];
	
	$prezzoIva = incremento_iva_articolo($con, $id_articolo);

	if ($iva_articolo != ""){
		$prezzo_articolo = $prezzoIva;
	}
	
	if ($data_oggi >= $sconto60){
		$alert_sconto = "!!!SCONTO60";
		if ($data_oggi >= $sconto90) {
			$alert_sconto = "!!!SCONTO90";
		}
	} else {
		$alert_sconto = "";
	}
	$print_mandato = "";
	if ($tipo_mandato == '1') {
		$print_mandato = "Mandato Standard";
	} elseif ($tipo_mandato == '2') {
		$print_mandato = "<b style=\"color: green;\">NO SCONTO 50%</b>";
	} elseif ($tipo_mandato == '3') {
		$print_mandato = "Particolare Cartaceo";
	} else {
		$print_mandato = "Mandato Standard";
	}
	global $CustomProvvigioni;
	if ($CustomProvvigioni == 'yes') {
		$print_mandato = $perc_provv.' %';
	//	$col_sconto = "";
	}
	$col_sconto = "
                <td>
                <b style=\"color: red;\">$alert_sconto</b> 
                <form method=\"post\" action=\"query_start.php?sez=sconto&id=$id_articolo\">
                <input type=\"text\" id=\"sconto\" name=\"sconto\" value=\"$sconto\" onkeyup=\"updateSconto()\" autocomplete=\"off\" />
                <input type=\"submit\" value=\"Applica\" />
                <input type=\"hidden\" id=\"prezzoIva\" value=\"$prezzoIva\" />
                <input type=\"text\" id=\"totSconto\" value=\"$prezzoIva\" disabled=\"true\" />
                </form>
                </td>
                ";

	$contab_articolo = $campi_articolo['contabilita'];
	if ($contab_articolo=='ct') $print_contab='CT';
	if ($contab_articolo=='cv') $print_contab='CV';
	
	if ($sconto != 0) {
		$prezzo_articolo = $prezzo_articolo-(($prezzo_articolo*$sconto)/100);
	}
		
	$totale_prezzi = $totale_prezzi+$prezzo_articolo;
	$prezzoIva = money_format('%.2n',$prezzoIva);
	
	$riga .= "
	<tr><td>$print_contab"."00$id_articolo</td>
	<td>$nome_articolo</td>
	<td><b>$prezzo_articolo_db</b></td>
	<td>$iva_articolo</td>
	<td>$prezzoIva</td>
	<td>$print_mandato</td>
	
	$col_sconto
	
	<td><b>|<a href=\"query_start.php?sez=art_del&id=$id\" title=\"Elimina da questa Ricevuta\">E</a>|</b>
	</td></tr>
	";

	
	}
	
	return array($riga,$totale_prezzi);	
}

function query_aggiungi_sconto($con, $id_articolo,$sconto){
	$sql_sconto = "UPDATE mercusa_articoli SET sconto='$sconto' WHERE id='$id_articolo' ";
	if (!mysqli_query($con,$sql_sconto)) {
  		die('Error: ' . mysqli_error($con));
	}
	//mysqli_close($con);
}

function query_select_ricevute_list($con,$data_inizio){
	// genera la stampa a video della lista di ricevute (usata in gestione doc)
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '2' ORDER BY id DESC ";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id = $results['id'];
		$num_doc = $results['num_doc'];
		$user_doc = $results['user'];
		$data_doc1 = $results['data_doc'];
			$data_giorno = date('d',strtotime($data_doc1));
			$data_mese = date('m',strtotime($data_doc1));
			$data_anno = date('Y',strtotime($data_doc1));
			$data_doc = $data_giorno .'/'. $data_mese .'/'. $data_anno;
			$data_fine = $data_mese.$data_anno;
		
		$id_cliente = $results['id_cliente'];
		$campi_cliente = query_select_detail($con, $id_cliente, 'mercusa_clienti');
		$nome_cliente = $campi_cliente['nome'];
		$cognome_cliente = $campi_cliente['cognome'];
		$importo_ricevuta = 0;
		
		$query_mov = "SELECT * FROM mercusa_movimenti WHERE id_documento = '$id' ";
		$result_mov = mysqli_query($con, $query_mov) or die('Errore...1 '. mysqli_error($con));
		while ($results_mov = mysqli_fetch_array($result_mov)) {
			$id_articolo = $results_mov['id_articolo'];
			$campi_articolo = query_select_detail($con, $id_articolo, 'mercusa_articoli');
			$prezzo_articolo = $campi_articolo['prezzo'];
			$iva_articolo = $campi_articolo['iva'];
			$sconto = $campi_articolo['sconto'];
			if ($sconto != 0) {
				$prezzo_articolo = $prezzo_articolo-(($prezzo_articolo*$sconto)/100);
			}else{
				$sconto = "";
			}
			if ($iva_articolo != ""){
				$prezzo_articolo = incremento_iva_articolo($con, $id_articolo);
			}
			$importo_ricevuta = $importo_ricevuta+$prezzo_articolo;		
		}
		if ($data_inizio == $data_fine) {
		$riga .= "
		<tr><td><b>$num_doc</b></td><td>$importo_ricevuta</td><td>$data_doc</td><td>
		<a href=\"stampa_ricevuta.php?id=$id\" target=\"$id\" class=\"btn btn-sm btn-primary\" title=\"Stampa ricevuta\"><i class=\"glyphicon glyphicon-print\"></i></a>
		<a href=\"stampa_pdf_ricevuta.php?id=$id\" target=\"pdf_$id\"><img src=\"http://fresnostate.edu/webresources/images/128x128/128x128-pdf.png\" title=\"Esporta in PDF\" width=\"31px\" height=\"31px\" /></a>
		<a href=\"stampa_pdf_fattura_vendita.php?id=$id\" target=\"$id\" class=\"btn btn-sm btn-primary\" title=\"Genera Fattura\"><i class=\"glyphicon glyphicon-file\"></i></a>
		</td><td>$user_doc</td></tr>
		";
		}
		
	}
	return $riga;
}   

function query_select_resi_fornitore_list($con,$data_inizio){
	// genera la stampa a video della lista di resi a fornitore (usata in gestione doc)
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '3' ORDER BY id DESC ";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id = $results['id'];
		$num_doc = $results['num_doc'];
		$user_doc = $results['user'];
		$data_doc1 = $results['data_doc'];
			$data_giorno = date('d',strtotime($data_doc1));
			$data_mese = date('m',strtotime($data_doc1));
			$data_anno = date('Y',strtotime($data_doc1));
			$data_doc = $data_giorno .'/'. $data_mese .'/'. $data_anno;
			$data_fine = $data_mese.$data_anno;
		
		$id_cliente = $results['id_cliente'];
		$campi_cliente = query_select_detail($con, $id_cliente, 'mercusa_clienti');
		$nome_cliente = $campi_cliente['nome'];
		$cognome_cliente = $campi_cliente['cognome'];
		
		if ($data_inizio == $data_fine) {
		$riga .= "
		<tr><td><b>$num_doc</b></td><td>$cognome_cliente $nome_cliente</td><td>$data_doc</td><td><b>
		<a class=\"btn btn-sm btn-primary\" href=\"stampa_reso_fornitore.php?id=$id\" target=\"$id\" title=\"Stampa doc. di Reso\"><i class=\"glyphicon glyphicon-print\"></i></a></b></td><td>$user_doc</td></tr>
		";
		}
		
	}
	return $riga;
} 

function query_select_doc_rimborso_list($con,$data_inizio){
	// genera la stampa a video della lista di doc di rimborso effettuati (usata in gestione doc)
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '4' ORDER BY id DESC ";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id = $results['id'];
		$num_doc = $results['num_doc'];
		$user_doc = $results['user'];
		$data_doc1 = $results['data_doc'];
			$data_giorno = date('d',strtotime($data_doc1));
			$data_mese = date('m',strtotime($data_doc1));
			$data_anno = date('Y',strtotime($data_doc1));
			$data_doc = $data_giorno .'/'. $data_mese .'/'. $data_anno;
			$data_fine = $data_mese.$data_anno;
		
		$id_cliente = $results['id_cliente'];
		$campi_cliente = query_select_detail($con, $id_cliente, 'mercusa_clienti');
		$nome_cliente = $campi_cliente['nome'];
		$cognome_cliente = $campi_cliente['cognome'];
		
		if ($data_inizio == $data_fine) {
		$riga .= "
		<tr><td><b>$num_doc</b></td><td>$cognome_cliente $nome_cliente</td><td>$data_doc</td><td><b>
		<a class=\"btn btn-sm btn-primary\" href=\"stampa_doc_rimborso.php?id=$id\" target=\"$id\" title=\"Stampa doc. di Rimborso\"><i class=\"glyphicon glyphicon-print\"></i></a></b></td><td>$user_doc</td></tr>
		";
		}
		
	}
	return $riga;
}

function query_select_ricevute_carico_list($con,$data_inizio){
	// genera la stampa a video della lista di ricevute di carico (usata in gestione doc)
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '1' ORDER by id DESC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id = $results['id'];
		$num_doc = $results['num_doc'];
		$user_doc = $results['user'];
		$data_doc1 = $results['data_doc'];
			$data_giorno = date('d',strtotime($data_doc1));
			$data_mese = date('m',strtotime($data_doc1));
			$data_anno = date('Y',strtotime($data_doc1));
			$data_doc = $data_giorno .'/'. $data_mese .'/'. $data_anno;
			$data_fine = $data_mese.$data_anno;
		$id_cliente = $results['id_cliente'];
		$campi_cliente = query_select_detail($con, $id_cliente, 'mercusa_clienti');
		$nome_cliente = $campi_cliente['nome'];
		$cognome_cliente = $campi_cliente['cognome'];
		$importo_ricevuta = 0;
		
		
		
		$query_mov = "SELECT * FROM mercusa_articoli WHERE codice = '$id' ";
		$result_mov = mysqli_query($con, $query_mov) or die('Errore...1 '. mysqli_error($con));
		while ($results_mov = mysqli_fetch_array($result_mov)) {
			$id_articolo = $results_mov['id'];
			$prezzo_articolo = (float)$results_mov['prezzo'];
			$quantita = (int)$results_mov['quantita'];
			$contab = $results_mov['contabilita'];
			if ($contab=="ct"){
				$prezzo_articolo = $results_mov['prezzo_acq'];
				$importo_ricevuta = $importo_ricevuta+($prezzo_articolo*$quantita);	
			}elseif ($contab=="cv"){
				$importo = ($prezzo_articolo/2)*$quantita;
				$importo_ricevuta = $importo_ricevuta+$importo;
			}

		}
		$open = query_select_doc_open($con,1);
		if ($open==0){
			$link_open = "<a href=\"query_start.php?sez=apri_carico_chiuso&id=$id\" class=\"btn btn-sm btn-primary\" title=\"Riapri questo carico\"><i class=\"glyphicon glyphicon-pencil\"></i></a>";
		}
		$importo_ricevuta = money_format('%.2n',$importo_ricevuta);
		
		if ($data_inizio == $data_fine) {
			$riga .= "
			<tr><td><b>$num_doc</b></td><td>$cognome_cliente $nome_cliente</td><td>$importo_ricevuta</td><td>$data_doc</td><td>
			<a href=\"stampa_carico.php?id=$id\" class=\"btn btn-sm btn-primary\" target=\"$id\" title=\"Stampa ricevuta di carico\"><i class=\"glyphicon glyphicon-print\"></i></a>
			<a href=\"stampa_carico_pdf.php?id=$id\" target=\"$id\" class=\"btn btn-sm btn-primary\" title=\"Genera PDF della ricevuta di carico\"><i class=\"glyphicon glyphicon-paperclip\"></i></a>
			$link_open
			</b>
			<td>$user_doc</td>
			</td></tr>
			";
		}
		
	}
	return $riga;
} 
function query_lista_giacenze_cliente ($con, $id_cliente){
	$query = "SELECT * FROM mercusa_articoli WHERE id_cliente = '$id_cliente' ORDER BY id ASC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id_articolo = $results['id'];
		$nome_articolo = $results['nome'];
		$contab_articolo = $results['contabilita'];
		$prezzo = money_format('%.2n',$results['prezzo']);
		$importo_rimborso = ($prezzo*50)/100;
		$importo_rimborso = money_format('%.2n',$importo_rimborso);
		$quantita_db = $results['quantita'];
		$quantita_mov = query_calcola_quantita($con,$id_articolo);
		$quantita_art = $quantita_db-$quantita_mov;
		$data_inserimento_articolo = date('d/m/Y',strtotime($results['data_inserimento']));
		if (($quantita_art > 0) AND ($contab_articolo=='cv')) {
			$print_contab='CV';
			$riga .= "<tr><td><b>$print_contab"."00$id_articolo</b>
						</td><td>$nome_articolo</td>
						<td>$quantita_art</td>
						<td>$prezzo</td>
						<td>$importo_rimborso</td>
						<td>$data_inserimento_articolo</td>
					</tr>";
		}
	}
	return $riga;
}

function query_lista_movimenti_cliente ($con, $id_cliente, $stampa){
	$query = "SELECT * FROM mercusa_articoli WHERE id_cliente = '$id_cliente' ORDER BY id ASC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id_articolo = $results['id'];
		$nome_articolo = $results['nome'];
		$contab_articolo = $results['contabilita'];
		$quantita_art = $results['quantita'];
		
		$prezzo = $results['prezzo'];
		$prezzo = money_format('%.2n',$prezzo);
		$importo_rimborso = $prezzo/2;
		$importo_rimborso = money_format('%.2n',$importo_rimborso);
		
		$data_inserimento = $results['data_inserimento'];
		$data_inserimento_giorno = date('d',strtotime($data_inserimento));
		$data_inserimento_mese = date('m',strtotime($data_inserimento));
		$data_inserimento_anno = date('Y',strtotime($data_inserimento));
		$data_inserimento_articolo = $data_inserimento_giorno .'/'. $data_inserimento_mese .'/'. $data_inserimento_anno;
		
		// controlliamo i resi
		$reso = $results['reso'];
		$reso_data = "";
		
		$mov_reso = mysqli_query($con, "SELECT * FROM mercusa_movimenti_resi WHERE id_articolo = $id_articolo") or die('Errore...0 '. mysqli_error($con));
		$quantita_reso = "";
		while ($mov_reso_riga = mysqli_fetch_array($mov_reso)) {
			$reso_id = $mov_reso_riga['id_reso'];
			$reso_quantita = $mov_reso_riga['quantita'];
			$quantita_reso = "(".($quantita_reso+$reso_quantita).")";
			$mov_reso_dettagli = query_select_detail($con, $reso_id, 'mercusa_documenti');
				$reso_data = date('d/m/Y',strtotime($mov_reso_dettagli['data_doc']));
		}
		
		if ($reso != 0) {
			$reso_dettagli = query_select_detail($con, $reso, 'mercusa_documenti');
			$reso_data = date('d/m/Y',strtotime($reso_dettagli['data_doc']));
			$quantita_reso = "(*)";
		}
		
		$sconto = $results['sconto'];
		if ($sconto != 0) {
			$importo_rimborso_scontato = ($prezzo-(($prezzo*$sconto)/100))/2;
		} else {
			$sconto = "";
			$importo_rimborso_scontato = "";
		}
		
		//if ($contab_articolo=='ct') $print_contab='CT';
		if ($contab_articolo=='cv') {
			$print_contab='CV';
		
			$query_mov = "SELECT * FROM mercusa_movimenti WHERE id_articolo = '$id_articolo' ";
			$result_mov = mysqli_query($con, $query_mov) or die('Errore...0 '. mysqli_error($con));
			
			$mese_precedente = '';
			$quantita_mov_ven = '';
			while ($results_mov = mysqli_fetch_array($result_mov)) {
					$id_mov_articolo = $results_mov['id_articolo'];
					$quantita_venduto = $results_mov['quantita'];
					$quantita_mov_ven = "(".($quantita_venduto+$quantita_mov_ven).")";
				
					$data_mov = $results_mov['data'];
					$rimborso = "";
					$rimborsato = $results_mov['is_rimborsato'];
					if ($rimborsato!='0'){
						$rimborso = "SI";
					}
					$data_mov_giorno = date('d',strtotime($data_mov));
					$data_mov_mese = date('m',strtotime($data_mov));
					$data_mov_anno = date('Y',strtotime($data_mov));
					$data_mov_stamp = $data_mov_giorno .'/'. $data_mov_mese .'/'. $data_mov_anno;
					
					
					$data_rimborso_giorno = '01';
					$data_rimborso_mese = date('m',strtotime($data_mov))+1;
					$data_rimborso_anno = date('Y',strtotime($data_mov));
					$data_rimborso = $data_rimborso_giorno .'/'. $data_rimborso_mese .'/'. $data_rimborso_anno;
					if ($sconto != 0) {
						$importo_rimborso = "";
					} else {
						$importo_rimborso_scontato = "";
					}
					
					$data_oggi_mese = (int)date('m');
					$data_rimborso_mese = (int)$data_rimborso_mese;
					if ($mese_precedente != $data_mov_mese){
						$riga .= "
							<tr><td><b><a href=\"index.php?loc=02&sez=art_edit&id=$id_articolo\">$print_contab"."00$id_articolo</a></b></td><td>$nome_articolo</td><td>$quantita_art</td><td>$prezzo</td><td>$importo_rimborso</td><td>$sconto</td><td>$importo_rimborso_scontato</td><td>$data_inserimento_articolo</td><td>$data_mov_stamp $quantita_mov_ven</td><td align=\"center\">$rimborso</td><td>$reso_data $quantita_reso</td></tr>
						";
						$mese_precedente = $data_mov_mese;
					}
			}
			if ($id_articolo != $id_mov_articolo){
				$riga .= "
				<tr><td><b><a href=\"index.php?loc=02&sez=art_edit&id=$id_articolo\">$print_contab"."00$id_articolo</a></b></td><td>$nome_articolo</td><td>$quantita_art</td><td>$prezzo</td><td>$importo_rimborso</td><td>$sconto</td><td>$importo_rimborso_scontato</td><td>$data_inserimento_articolo</td><td></td><td></td><td>$reso_data $quantita_reso</td></tr>
				";
			}
		
		}
		}
		
	
		
	
	if ($stampa==0){
		return $riga;
	}elseif ($stampa==1){
		return $campi;
	}
}

function diff_in_giorni($first, $second){

//isoliamo i valori contenuti nei due array
  $array_f = @explode ("/", $first);
  $array_s = @explode ("/", $second);

  $dd1 = $array_f[0];
  $mm1 = $array_f[1];
  $yyyy1 = $array_f[2];

  $dd2 = $array_s[0];
  $mm2 = $array_s[1];
  $yyyy2 = $array_s[2];

//utilizziamo i valori degli array come termini di confronto 
  $confronto1 = @gregoriantojd($mm1, $dd1, $yyyy1);
  $confronto2 = @gregoriantojd($mm2, $dd2, $yyyy2);
  
//calcoliamo la differenza in giorni 
  return $confronto1 - $confronto2; 
}

function query_rimborsi_cliente ($con, $id_cliente, $stampa){
	if ($stampa==1){
		query_insert_new_doc($con,4); // ora il documento di rimborso risulta creato e APERTO;
		$last_doc = query_select_doc_open_details($con,4);
		$lastID = $last_doc[0]; // ora ho l'ID del mio documento di rimborso
		$lastNUM = $last_doc[3];
	}
	
	$query = "SELECT * FROM mercusa_articoli WHERE id_cliente = '$id_cliente' ";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id_articolo = $results['id'];
		$nome_articolo = $results['nome'];
		$prezzo_db = $results['prezzo'];
		$sconto = (int)$results['sconto'];
		$perc_provv = $results['provvigione'];
		$iva = $results['iva'];
		if ($perc_provv == 0) {
			$perc_provv = 50;
		}
		$prezzo = str_replace(',', '.', $prezzo_db);
		
		
		if (($sconto != 0) OR ($sconto != NULL) OR ($sconto != "") OR ($sconto != "0")) {
			$importo_rimborso_scontato = ($prezzo-(($prezzo*$sconto)/100))/2;
		} else {
			$importo_rimborso_scontato = "";
		}

		$contab_articolo = $results['contabilita'];
		if ($contab_articolo=='cv') {
			$print_contab='CV';
		
		$query_mov = "SELECT * FROM mercusa_movimenti WHERE id_articolo = '$id_articolo' AND is_rimborsato = '0' ";
		$result_mov = mysqli_query($con, $query_mov) or die('Errore...0 '. mysqli_error($con));
		while ($results_mov = mysqli_fetch_array($result_mov)) {
			$data_mov = $results_mov['data'];
			$data_mov_giorno = date('d',strtotime($data_mov));
			$data_mov_mese = date('m',strtotime($data_mov));
			$data_mov_anno = date('Y',strtotime($data_mov));
			$data_mov_stamp = $data_mov_giorno .'/'. $data_mov_mese .'/'. $data_mov_anno;
			
			$importo_rimborso = $prezzo-(($prezzo*$perc_provv)/100);
			$importo_rimborso = money_format('%.2n',$importo_rimborso);
			$data_rimborso_giorno = '01';
			$data_rimborso_mese = date('m',strtotime($data_mov))+1;
			$data_rimborso_anno = date('Y',strtotime($data_mov));
			if ($data_rimborso_mese > 12) {
				$data_rimborso_mese = '01';
				$data_rimborso_anno = date('Y',strtotime($data_mov))+1;
			}
			$data_rimborso = $data_rimborso_giorno .'/'. $data_rimborso_mese .'/'. $data_rimborso_anno;
			
			if (($sconto != 0) OR ($sconto != NULL) OR ($sconto != "") OR ($sconto != "0")) {
				$importo_rimborso = "";
			} else {
				$importo_rimborso_scontato = "";
			}
			$riga .= "
			<tr><td>$print_contab"."00$id_articolo</td><td>$nome_articolo</td><td>$data_mov_stamp</td><td>$prezzo</td><td>$importo_rimborso</td><td>$sconto %</td><td>$importo_rimborso_scontato</td><td>$data_rimborso</td></tr>
			";
			if (($sconto != 0) OR ($sconto != NULL) OR ($sconto != "") OR ($sconto != "0")) {
				$importo_rimborso = $importo_rimborso_scontato;
			} else {
				$importo_rimborso_scontato = "";
			}
			$totale_da_pagare = $totale_da_pagare+$importo_rimborso;
			
			$data_oggi = date("d/m/Y");
			$differenza = diff_in_giorni("$data_oggi","$data_rimborso");
			
			global $rimborsoImmediato, $rimborsoConIva;
			if ($rimborsoImmediato == 'yes') {
				$differenza = 0;
			}
			
			if ($differenza >= 0){
				if ($rimborsoConIva == 'yes') {
					$imponibile = ($prezzo-$importo_rimborso)/1.22;
					$iva_art = ($prezzo-$importo_rimborso) - $imponibile;
					$imponibile = money_format('%.2n',$imponibile);
					$iva_art = money_format('%.2n',$iva_art);
					$totIVA = $totIVA+$iva_art;
					$totIMP = $totIMP+$imponibile;
					$riga_x_stampa .= "
						<tr>
						<td>$print_contab"."00$id_articolo</td>
						<td>$nome_articolo</td><td>$data_mov_stamp</td>
						<td align=\"center\">$prezzo</td><td align=\"center\">$perc_provv %</td>
						<td align=\"center\">$imponibile</td><td align=\"center\">$iva_art</td>
						<td align=\"center\">$importo_rimborso</td>
						</tr>
					";
				} else {
					$riga_x_stampa .= "
						<tr>
						<td>$print_contab"."00$id_articolo</td>
						<td>$nome_articolo</td><td>$data_mov_stamp</td>
						<td>$prezzo</td><td>$importo_rimborso</td>
						<td>$sconto %</td><td>$importo_rimborso_scontato</td>
						</tr>
					";
				}
					$totale_stampa = $totale_stampa+$importo_rimborso;
					$id_movimento = $results_mov['id'];
					if ($stampa==1){
						// cambio il flag sulle righe dei movimenti interessate dal rimborso e aggiungo l'id del 
						// documento appena creato
						$sql_rimborsato = "UPDATE mercusa_movimenti SET is_rimborsato=1, id_rimborso='$lastID' WHERE id='$id_movimento' ";
						if (!mysqli_query($con,$sql_rimborsato)) {
							die('Error: ' . mysqli_error($con));
						}
						
					}
			}
			// costruisco un array con dentro i dettagli articolo, i dettagli movimento e la stampa delle righe;
			$campi = array ($results,$results_mov,$riga_x_stampa,$totale_stampa,$lastNUM,$totIVA,$totIMP);
			
		}
		
		}

		
	}
	if ($stampa==0){
		echo '<b>Totale da rimborsare: '.$totale_da_pagare.'</b> - Totale rimborsabile alla data di oggi: <b>'.$totale_stampa.'</b><br />';
		return $riga;
	}elseif ($stampa==1){
		// chiudo il documento appena terminato
		$sql_close = "UPDATE mercusa_documenti SET open=0 WHERE id='$lastID' ";
		if (!mysqli_query($con,$sql_close)) {
			die('Error: ' . mysqli_error($con));
		}
		session_start();
		$user = $_SESSION['cod'];
		write_mysql_log("id=$lastID?user=$user?state=CLOSE RIMBORSO", $con);
		
		return $campi;
	}
}

function query_clienti_rimborsabili($con,$mese_input,$anno_input){
	$sql_clienti = "SELECT * FROM mercusa_clienti ORDER BY cognome ASC";
	$result_clienti = mysqli_query($con, $sql_clienti) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results_clienti = mysqli_fetch_array($result_clienti)) {
		$id_cliente = $results_clienti['id'];
		$nome_cliente = $results_clienti['nome'];
		$cognome_cliente = $results_clienti['cognome'];
		$importo_rimborso = 0;
		$email = $results_clienti['email'];
		$telefono = $results_clienti['telefono'];

		//seleziono gli articoli del cliente
		$sql_articoli = "SELECT * FROM mercusa_articoli WHERE id_cliente = '$id_cliente' AND contabilita = 'cv' ";
		$result_articoli = mysqli_query($con, $sql_articoli) or die('Errore...1 '. mysqli_error($con));
		 
		while ($results_articoli = mysqli_fetch_array($result_articoli)) {
			$id_articolo = $results_articoli['id'];
			$prezzo_db = $results_articoli['prezzo'];
			$prezzo_articolo = str_replace(',', '.', $prezzo_db);
			$sconto = $results_articoli['sconto'];
			
			if ($sconto != 0) {
				$prezzo_articolo = $prezzo_articolo-(($prezzo_articolo*$sconto)/100);
			}
			$rimborso = $prezzo_articolo/2;
		
			//seleziono i movimenti da rimborsare
			$mese_oggi = date("m");
			$sql_mov = "SELECT * FROM mercusa_movimenti WHERE id_articolo = '$id_articolo' AND is_rimborsato = '0' ";
			$result_mov = mysqli_query($con, $sql_mov) or die('Errore...2 '. mysqli_error($con));
		 
			while ($results_mov = mysqli_fetch_array($result_mov)) {
				$id_mov = $results_mov['id'];
				$data_mov = $results_mov['data'];
				$data_mov_giorno = date('d',strtotime($data_mov));
				$data_mov_mese = date('m',strtotime($data_mov));
				$data_mov_anno = date('Y',strtotime($data_mov));
				$data_mov_stamp = $data_mov_giorno .'/'. $data_mov_mese .'/'. $data_mov_anno;
				$data_rimborso_giorno = '01';
				$data_rimborso_mese = date('m',strtotime($data_mov))+1;
				$data_rimborso_anno = date('Y',strtotime($data_mov));
				if ($data_rimborso_mese > 12) {
					$data_rimborso_mese = '01';
					$data_rimborso_anno = date('Y',strtotime($data_mov))+1;
				}
				$data_rimborso = $data_rimborso_giorno .'/'. $data_rimborso_mese .'/'. $data_rimborso_anno;
				if (($data_mov_mese==$mese_input)AND($data_mov_anno==$anno_input)){
					$rimborso = money_format('%.2n',$rimborso);
					$importo_rimborso = $importo_rimborso+$rimborso;
				}

				// if (($data_mov_mese==$mese_input)AND($data_mov_anno==$anno_input)){
				// 	$rimborso = $prezzo_articolo/2;
				// 	$rimborso = money_format('%.2n',$rimborso);
				// 	$importo_rimborso = $importo_rimborso+$rimborso;
				// }
			}
		}
		if ($importo_rimborso!=0){
			$riga .= "<tr><td>$cognome_cliente $nome_cliente</td><td>$importo_rimborso</td><td>$email</td><td>$telefono</td><td><a href=\"index.php?loc=04&sez=cont_rimborsi_cliente&id_cliente=$id_cliente\" alt=\"Visualizza il dettaglio\">Dettaglio</a></td></tr>";
			$totale = $totale+$importo_rimborso;
		}
	}
	return array($riga,$totale);
}

function query_select_tot_rimborsi($con){
	$query = "SELECT * FROM mercusa_articoli WHERE contabilita = 'cv' ";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id_articolo = $results['id'];
		$nome_articolo = $results['nome'];
		$prezzo_db = $results['prezzo'];
		$sconto = $results['sconto'];
		$prezzo = str_replace(',', '.', $prezzo_db);
		
		if ($sconto != 0) {
			$prezzo = $prezzo-(($prezzo*$sconto)/100);
		} else {
			$prezzo = $prezzo;
		}

		$contab_articolo = $results['contabilita'];
		
		$query_mov = "SELECT * FROM mercusa_movimenti WHERE id_articolo = '$id_articolo' AND is_rimborsato = '0' ";
		$result_mov = mysqli_query($con, $query_mov) or die('Errore...0 '. mysqli_error($con));
		while ($results_mov = mysqli_fetch_array($result_mov)) {
			$data_mov = $results_mov['data'];
			$data_mov_giorno = date('d',strtotime($data_mov));
			$data_mov_mese = date('m',strtotime($data_mov));
			$data_mov_anno = date('Y',strtotime($data_mov));
			$data_mov_stamp = $data_mov_giorno .'/'. $data_mov_mese .'/'. $data_mov_anno;
			
			$importo_rimborso = $prezzo/2;
			$data_rimborso_giorno = '01';
			$data_rimborso_mese = date('m',strtotime($data_mov))+1;
			$data_rimborso_anno = date('Y',strtotime($data_mov));
			if ($data_rimborso_mese > 12) {
				$data_rimborso_mese = '01';
				$data_rimborso_anno = date('Y',strtotime($data_mov))+1;
			}
			$data_rimborso = $data_rimborso_giorno .'/'. $data_rimborso_mese .'/'. $data_rimborso_anno;
			$mese_corrente = date('m');
			$anno_corrente = date('Y');
			if (($data_mov_mese==$mese_corrente)AND($data_mov_anno==$anno_corrente)){
				//echo "<br />".$importo_rimborso;
				$totale = $totale + $importo_rimborso;
			}
		}
	}
	return money_format('%.2n',$totale);
}
	
function query_select_doc_open($con,$tipo_doc){
	// conta se per caso esiste un documento che ancora non e' stato chiuso correttamente
	// imposto ed eseguo la query
	session_start();
	$user = $_SESSION['cod'];
	$query = "SELECT * FROM mercusa_documenti WHERE open = '1' AND tipo_doc = '$tipo_doc' AND user = '$user'";
	$result = mysqli_query($con, $query) or die('Errore...');
	
	// conto il numero di occorrenze trovate nel db
	$numrows = mysqli_num_rows($result);
	
	return $numrows;
	
}	

function query_select_doc_open_details($con,$tipo_doc){
	// seleziona tutti i dettagli del documento che ancora non e' stato chiuso del tutto
	// imposto ed eseguo la query
	session_start();
	$user = $_SESSION['cod'];
	$query = "SELECT * FROM mercusa_documenti WHERE open = '1' AND tipo_doc = '$tipo_doc' AND user = '$user'";
	$result = mysqli_query($con, $query) or die('Errore...');
	
	$results = mysqli_fetch_array($result);
	
	//restituisco un array completo di tutti i dettagli della riga trovata
	return $results;
	
}	

function query_svuota_ricevuta($con,$id){
	mysqli_query($con,"DELETE FROM mercusa_movimenti WHERE id_documento=$id");
	mysqli_query($con,"UPDATE mercusa_documenti SET open='0' WHERE id=$id");
	session_start();
	$user = $_SESSION['cod'];
	write_mysql_log("id=$id?user=$user?state=CANCEL RICEVUTA", $con);
}

function query_svuota_carico($con,$id){
	mysqli_query($con,"DELETE FROM mercusa_articoli WHERE codice='$id' ");
	mysqli_query($con,"UPDATE mercusa_documenti SET open='0' WHERE id=$id");
	session_start();
	$user = $_SESSION['cod'];
	write_mysql_log("id=$id?user=$user?state=CANCEL RICEVUTA", $con);
}

function query_apertura_carico_chiuso($con,$id){
	$sql = "UPDATE mercusa_documenti SET open='1' WHERE id='$id' ";
	if (!mysqli_query($con,$sql)) {
		die('Error: ' . mysqli_error($con));
	} 
	mysqli_close($con);
}

function query_stampa_ricevuta($con,$id){
	$sql = "UPDATE mercusa_documenti SET open='0' WHERE id=$id";
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
	session_start();
	$user = $_SESSION['cod'];
	write_mysql_log("id=$id?user=$user?state=CLOSE RICEVUTA", $con);
	mysqli_close($con);
}

function query_new_art_reso($con){
	// funzione che aggiunge un articolo alla lista di reso a fornitore
	
	$id_articolo = $_POST['id_articolo'];
	$id_doc = $_POST['reso'];
	$quantita = $_POST['art_quantita'];
	if ($quantita == "") {
		$quantita = 1;
	}
	$sql_mov = "INSERT INTO mercusa_movimenti_resi (id, id_reso, id_articolo, quantita)
		VALUES (NULL, '$id_doc', '$id_articolo', '$quantita')";
	if (!mysqli_query($con,$sql_mov)) {
  		die('Error: ' . mysqli_error($con));
	}

	mysqli_close($con);
}

function query_svuota_reso_fornitore($con,$id){
	// annulla completamente la ricevuta di reso a fornitore
	
	mysqli_query($con,"UPDATE mercusa_documenti SET open='0' WHERE id=$id");
	mysqli_query($con,"DELETE FROM mercusa_movimenti_resi WHERE id_reso=$id");
	
	session_start();
	$user = $_SESSION['cod'];
	write_mysql_log("id=$id?user=$user?state=CANCEL RESO", $con);
}

function query_salva_reso($con,$id){
	// salva completamente la ricevuta di reso a fornitore
	
	mysqli_query($con,"UPDATE mercusa_documenti SET open='0' WHERE id=$id");
	
	session_start();
	$user = $_SESSION['cod'];
	write_mysql_log("id=$id?user=$user?state=CLOSE RESO", $con);
}

function query_conta_ricevute_totali($con){
	$data = date("Y-01-01 00:00:00");
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '2' AND data_doc > '$data'";
	$result = mysqli_query($con, $query) or die('Errore...');
	
	// conto il numero di occorrenze trovate nel db
	$numrows = mysqli_num_rows($result);
	
	return $numrows;
	
}

function query_conta_ricevute_oggi($con){
	
	// imposto ed eseguo la query
	$data = date('Y-m-d')." 00:00:01";
	$query = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '2' AND data_doc >= '$data' ";
	$result = mysqli_query($con, $query) or die('Errore...');
	
	// conto il numero di occorrenze trovate nel db
	$numrows = mysqli_num_rows($result);
	
	return $numrows;
	
}

function query_conta_rimborsato_oggi($con){
	// questa conta quanto importo abbiamo pagato nella data odierna di articoli rimborsati
	$data = date('Y-m-d')." 00:00:01";
	//$data = "2015-02-12 00:00:01";
	$query_doc = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '4' AND data_doc >= '$data' ";
	$result_doc = mysqli_query($con, $query_doc) or die('Errore...0 '. mysqli_error($con));
		while ($results_doc = mysqli_fetch_array($result_doc)) {
			$id_doc = $results_doc['id'];
			$query_mov = "SELECT * FROM mercusa_movimenti WHERE id_rimborso = '$id_doc' ";
			$result_mov = mysqli_query($con, $query_mov) or die('Errore...0 '. mysqli_error($con));
			while ($results_mov = mysqli_fetch_array($result_mov)) {
				$id_articolo = $results_mov['id_articolo'];
				$campi_articolo = query_select_detail($con,$id_articolo,'mercusa_articoli');
				$prezzo = $campi_articolo['prezzo'];
				
				$importo_rimborso = ($prezzo*50)/100;
				$sconto = $campi_articolo['sconto'];
				if ($sconto != "0"){
					$importo_rimborso_scontato = ($importo_rimborso-(($importo_rimborso*$sconto)/100));
					$importo_rimborso = money_format('%.2n',$importo_rimborso_scontato);
				}
				$tot = $tot+$importo_rimborso;
			}
		}
	return money_format('%.2n',$tot);
}

function query_conta_chiusura_cassa($con){
	$data = date('Y-m-d')." 00:00:01";
	$query = "SELECT * FROM mercusa_documenti WHERE open = '0' AND tipo_doc = '2' AND data_doc >= '$data' ";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id_doc = $results['id'];
		$prezzo = str_replace(',', '.', $prezzo_db);
		
		$query_mov = "SELECT * FROM mercusa_movimenti WHERE id_documento = '$id_doc' ";
		$result_mov = mysqli_query($con, $query_mov) or die('Errore...0 '. mysqli_error($con));
		while ($results_mov = mysqli_fetch_array($result_mov)) {
			$id_art = $results_mov['id_articolo'];
			$campi_art = query_select_detail($con,$id_art,'mercusa_articoli');
			$sconto = $campi_art[13];
			$prezzo = $campi_art[5];
			$iva_articolo = $campi_art['iva'];
			if ($iva_articolo != ""){
				$prezzo = incremento_iva_articolo($con, $id_art);
			}
			
			if ($sconto != 0) {
				$importo = $prezzo-(($prezzo*$sconto)/100);
			} else {
				$importo = $prezzo;
			}
			$totale = $totale + $importo;
			
		}
	}
	return money_format('%.2n',$totale);
}

function add_new_prom($con){
	// aggiungo un promemoria all'agenda
	
	session_start();
	$user = $_SESSION['cod'];
	if ($user == ""){
		header("Location: login_form.php");
		die();
	}
		
	
	$data_prom = $_POST['anno']."-".$_POST['mese']."-".$_POST['giorno'];
	
	$testo = mysqli_real_escape_string($con,$_POST['testo']);
		
	$sql = "INSERT INTO mercusa_promemoria (id, data, testo, letto)
		VALUES (NULL, '$data_prom', '$testo', '0')";
	
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
	write_mysql_log("user=$user?state=AGGIUNTO PROMEMORIA", $con);
}

function query_select_prom($con){
	$query = "SELECT * FROM mercusa_promemoria ORDER BY id DESC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {
		$id_prom = $results['id'];
		$data_prom = date("d/m/Y", strtotime($results['data']));
		$testo_prom = $results['testo'];
		$testo = preg_replace('/\s+?(\S+)?$/', '', substr($testo_prom, 0, 25));
		$letto = $results['letto'];
		if ($letto == 0) {
			$riga .= "<tr><td><i class=\"glyphicon glyphicon-star\"></i> $id_prom</td><td>$data_prom</td><td><a href=\"index.php?loc=05&sez=edit_prom&id=$id_prom\" title=\"Clicca per leggere tutto\">$testo</a></td></tr>";
		} else {
			$riga .= "<tr><td>$id_prom</td><td>$data_prom</td><td><a href=\"index.php?loc=05&sez=edit_prom&id=$id_prom\" title=\"Clicca per leggere tutto\">$testo</a></td></tr>";
		}
	}
return $riga;
}

function query_select_single_prom($con, $id_prom){
	$query = "SELECT * FROM mercusa_promemoria WHERE id = '$id_prom'";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		  
	return mysqli_fetch_array($result);
}

function query_select_prom_bydate($con, $data_prom){
	$data_now = date("Y-m-d");
	$query = "SELECT * FROM mercusa_promemoria WHERE data BETWEEN '$data_now' AND '$data_prom' ORDER BY data DESC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	$numrows = mysqli_num_rows($result);
	if ($numrows==0){
		return $numrows;
	} else {
	while ($results = mysqli_fetch_array($result)) {
		$id_prom = $results['id'];
		$data_prom = date("d/m/Y", strtotime($results['data']));
		$testo_prom = $results['testo'];
		$testo = preg_replace('/\s+?(\S+)?$/', '', substr($testo_prom, 0, 25));
		$letto = $results['letto'];
		if ($letto == 0) {
			$riga .= "<tr><td><i class=\"glyphicon glyphicon-star\"></i></td><td>$data_prom</td><td><a href=\"index.php?loc=05&sez=edit_prom&id=$id_prom\" title=\"Clicca per leggere tutto\">$testo</a></td></tr>";
		} else {
			$riga .= "<tr><td></td><td>$data_prom</td><td><a href=\"index.php?loc=05&sez=edit_prom&id=$id_prom\" title=\"Clicca per leggere tutto\">$testo</a></td></tr>";
		}
	}
		  
	return $riga;
	}
}

function update_letto_prom($con, $id_prom, $letto){
	$sql = "UPDATE mercusa_promemoria SET letto='$letto' WHERE id='$id_prom' ";
	if (!mysqli_query($con,$sql)) {
		die('Error: ' . mysqli_error($con));
	}
}

function incremento_iva_articolo($con, $id){
// con il regime del margine l'articolo va incrementato dell'iva che si andra a pagare solo sulla provvigione

	$query = "SELECT * FROM mercusa_articoli WHERE id = '$id'";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	$results = mysqli_fetch_array($result);
	$prezzo = $results['prezzo'];
	$perc_provv = $results['provvigione'];
	if ($perc_provv == '0') { $perc_provv = 50; }
	$iva_articolo = $results['iva'];
	
	// calcolo la provvigione che rimane al negozio
	$provvigione = ($prezzo*$perc_provv)/100;
	
	$iva_vendita = ($provvigione*$iva_articolo)/100;
	$prezzo_tot = $prezzo+$iva_vendita;
	
	return $prezzo_tot;
}

function query_liquidazione_iva($con,$id_art){
	// da utilizzare per calcolare la liquidazione iva con regime del margine per singolo articolo, venduto 
	// (da verificare in fase di utilizzo che l'articolo sia venduto)
	
	$query = "SELECT * FROM mercusa_articoli WHERE id = '$id_art'";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	$results = mysqli_fetch_array($result);
	$prezzoImp = $results['prezzo'];
	$iva_articolo = $results['iva'];
	$sconto = $results['sconto'];
	$contab = $results['contabilita'];
	$prezzo_acq = $results['prezzo_acq'];
	
	// calcolo l'acquisto del bene
	if ($sconto != '0') {
		$acquisto = $prezzoImp-(($prezzoImp*$sconto)/100);
		$acquisto = ($acquisto*50)/100;
	} else {
		$acquisto = ($prezzoImp*50)/100;
	}
	
	if ($contab == 'ct'){
		$acquisto = $prezzo_acq;
	}
	
	// calcolo la vendita del bene maggiorando dell'iva solo la provvigione al mandatario
	
	$vendita = incremento_iva_articolo($con, $id_art);
	if ($sconto != 0) {
		$vendita = $vendita-(($vendita*$sconto)/100);
	}
	
	// eseguo la liquidazione
	$margine_lordo = $vendita-$acquisto;
	$margine_netto = $margine_lordo/1.22;
	$liquidazione = $margine_lordo-$margine_netto;
	
	return array(money_format('%.2n',$liquidazione),money_format('%.2n',$vendita),money_format('%.2n',$acquisto),money_format('%.2n',$margine_lordo),money_format('%.2n',$margine_netto));
	
}

function haveTestataFattura($con, $id_doc){
	// questa funzione restituisce 1 se trova la testata oppure 0 se non la trova
	$query = "SELECT * FROM mercusa_movimenti_fatture WHERE id_documento = '$id_doc' AND totale != '0' AND importo = '0'";
	$result = mysqli_query($con, $query) or die('Errore... haveTestataFattura() '. mysqli_error($con));
	$results = mysqli_num_rows($result);
	
	return $results;
}

function selectTestataFatturaDettaglio($con,$id_doc){
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_movimenti_fatture WHERE id_documento=$id_doc AND totale!= '0' ";
	$result = mysqli_query($con, $query) or die('Errore...funzione selectTestataFatturaDettaglio '.$id_doc);
	$riga = mysqli_fetch_array($result);
	
	return $riga;
}

function insertRigaComuneFattura($con, $id_doc){
	// questa funzione aggiunge una riga pulita al netto dei campi di testata, per la fattura. Funziona solo POST
	$data_post = $_POST['data'];
	$importo_post = $_POST['prezzo'];
	$iva_post = $_POST['aliquota'];
	$note_post = $_POST['nota'];
	$qta_post = $_POST['qta'];
	$sconto_post = $_POST['sconto'];

	$sql_riga = "INSERT INTO mercusa_movimenti_fatture (id, data, importo, iva, note, id_documento, qta, unimis, sconto)
			VALUES (NULL, '$data_post', '$importo_post', '$iva_post', '$note_post', '$id_doc', '$qta_post', '$sconto_post')";
	if (!mysqli_query($con,$sql_riga)) {
		die('Error: insertRigaComuneFattura() - insert di riga comune di fattura' . mysqli_error($con));
	}
}

function updateTestataFattura($con, $id_movTestata){
	// questa funzione aggiorna le informazioni di testata della fattura, ogni volta venga richiamata POST
	
	$totale_post = $_POST['tot_fatt'];
	$pagam_post = $_POST['pagamento'];
	
	$sql_1 = "UPDATE mercusa_movimenti_fatture SET totale='$totale_post', pagamento='$pagam_post'
			WHERE id=$id_movTestata";
	if (!mysqli_query($con,$sql_1)) {
		die('Error: update riga di testata' . mysqli_error($con));
	}
}

function menuPagamentiFattura($con, $id_doc){
	$campi_testata = selectTestataFatturaDettaglio($con,$id_doc);
	if ($campi_testata['pagamento']=="rimdir"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" selected>Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="10ms"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" selected>al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="15ms"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" selected>al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="30fm"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" selected>30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="60fm"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" selected>60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="90fm"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" selected>90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="30df"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" selected>30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="60df"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" selected>60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="90df"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" selected>90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif ($campi_testata['pagamento']=="bb"){
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" selected>Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" >Non indicato</option>
		</select>
		";
	} elseif (($campi_testata['pagamento']=="0") OR ($campi_testata['pagamento']=="")) {
		$menu = "
		<select name=\"pagamento\" style=\"border: 1px #000 solid; padding: 3px;\">
			<option name=\"pagamento\" value=\"rimdir\" >Rimessa diretta</option>
			<option name=\"pagamento\" value=\"10ms\" >al 10 m.s.</option>
			<option name=\"pagamento\" value=\"15ms\" >al 15 m.s.</option>
			<option name=\"pagamento\" value=\"30fm\" >30 gg f.m.</option>
			<option name=\"pagamento\" value=\"60fm\" >60 gg f.m.</option>
			<option name=\"pagamento\" value=\"90fm\" >90 gg f.m.</option>
			<option name=\"pagamento\" value=\"30df\" >30 gg d.f.</option>
			<option name=\"pagamento\" value=\"60df\" >60 gg d.f.</option>
			<option name=\"pagamento\" value=\"90df\" >90 gg d.f.</option>
			<option name=\"pagamento\" value=\"bb\" >Bonifico Bancario</option>
			<option name=\"pagamento\" value=\"0\" selected>Non indicato</option>
		</select>
		";
	}
	return $menu;	
}

function aggiungiRiga_fattura($con,$id_doc){
// questa funzione aggiunge le righe all'interno di una fattura tipo_doc=5.
//
// controllo se esiste gia la prima riga di testata (con totale, data e pagamento), obbligatoria per ogni fattura
$ID_doc_post = $_POST['id_doc'];
$totale_post = $_POST['tot_fatt'];
$data_post = $_POST['data'];
$pagam_post = $_POST['pagamento'];

	if (haveTestataFattura($con,$numDoc_post)=="0"){
		// aggiungo la prima riga di testata tra i movimenti e poi la riga che l'utente ha compilato
		$sql_insertTestata = "INSERT INTO mercusa_movimenti_fatture (id, data, totale, id_documento, pagamento)
		VALUES (NULL, '$data_post', '$totale_post', '$ID_doc_post', '$pagam_post')";
		if (!mysqli_query($con,$sql_insertTestata)) {
			die('Error: aggiungiRiga_fattura() - insert di testata' . mysqli_error($con));
		}
		insertRigaComuneFattura($con, $ID_doc_post);
	} else {
		$campi_testata = selectTestataFatturaDettaglio($con,$ID_doc_post);
		$idTestata = $campi_testata['id'];
		// prima controllo se la riga di testata ha i campi "totale" e "pagamento" uguali a quelli POST
		// se sono uguali{
		// ---	aggiungo la riga che l'utente ha compilato
		if (($campi_testata['totale'] == $totale_post) OR ($campi_testata['pagamento'] == $pagam_post)) {
			insertRigaComuneFattura($con, $ID_doc_post);
		} else {
		// }se sono diversi{
		// ---	lancio un update sulla riga di testata della fattura in compilazione e aggiungo la nuova riga
			updateTestataFattura($con, $idTestata);
			insertRigaComuneFattura($con, $ID_doc_post);
		}
	}

}

function query_liquidazione_iva_periodo($con,$data_from,$data_to){
	$data_from = $data_from.' 00:00:00';
	$data_to = $data_to.' 23:59:59';
	$query = "SELECT * FROM mercusa_movimenti WHERE data BETWEEN '$data_from' AND '$data_to' ORDER BY data DESC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	while ($results = mysqli_fetch_array($result)) {
		$id_articolo = $results['id_articolo'];
		$id_doc = $results['id_documento'];
		$data_mov = date("d/m/Y", strtotime($results['data']));
		$campi_articolo = query_select_detail($con,$id_articolo,'mercusa_articoli');
			$nome_art = $campi_articolo['nome'];
			$codice = $campi_articolo['codice'];
		$campi_doc = query_select_detail($con,$id_doc,'mercusa_documenti');
			$num_doc = $campi_doc['num_doc'];
		$campi_doc_acq = query_select_detail($con,$codice,'mercusa_documenti');
			$num_doc_acq = $campi_doc_acq['num_doc'];
		
		$liq_articolo = query_liquidazione_iva($con, $id_articolo);
		$vendita = $liq_articolo[1];
		$acquisto = $liq_articolo[2];
		$margine_lordo = $liq_articolo[3];
		$margine_netto = $liq_articolo[4];
		$iva = $liq_articolo[0];
		
		echo "
		<br />Data operazione: $data_mov<br />
		Bene di riferimento: $id_articolo - $nome_art<br />
		<table width=\"300px\">
		<tr>
		<td><i>Operazione</i> </td><td> <i>Importo</i> </td><td> <i>Fattura di riferimento</i></td></tr>
		<tr><td>Cessione</td><td>&euro; $vendita</td><td>Ricevuta n. $num_doc</td></tr>
		<tr><td>Acquisto</td><td>-&euro; $acquisto</td><td>Ricevuta n. $num_doc_acq</td></tr>
		</table>
		<table width=\"600px\">
		<tr><td>Margine realizzato sul bene:</td><td>&euro; $margine_lordo</td><td>Rilevanza fiscale:</td><td>&euro; $margine_lordo</td></tr>
		<tr><td>Imponibile:</td><td>&euro; $margine_netto</td><td>IVA:</td><td>&euro; $iva</td></tr>
		</table>
		";
		//$totIva = $totIva+$iva;
		$totLordo = $totLordo+$margine_lordo;
		$totNetto = $totNetto+$margine_netto;
		$totVendita = $totVendita+$vendita;
	}
	$totLordo = money_format('%.2n',$totLordo);
	$totVendita = money_format('%.2n',$totVendita);
	
	// preparo i calcoli per il riepilogo finale
	$impTot = $totLordo/1.22;
	$impTot = money_format('%.2n',$impTot);
	$ivaTot = $totLordo-$impTot;
	$ivaTot = money_format('%.2n',$ivaTot);
	$maggTrim = $ivaTot+(($ivaTot*1)/100);
	$maggTrim = money_format('%.2n',$maggTrim);
	echo "<br /><hr />
		<h4><a name=\"anchor\"></a>Riepilogo periodo considerato</h4>
		<table width=\"300px\">
		<tr><td><b>Margine maturato nel periodo:</b></td><td align=\"right\">&euro; $totLordo</td></tr>
		<tr><td><b>Margine a tassazione:</b></td><td align=\"right\">&euro; $totLordo</td></tr>
		<tr><td><b>Cessioni al 22%:</b></td><td align=\"right\">&euro; $totVendita</td></tr>
		<tr><td><b>Margine al 22%:</b></td><td align=\"right\">&euro; $totLordo</td></tr>
		<tr><td><b>Imponibile:</b></td><td align=\"right\">&euro; $impTot</td></tr>
		<tr><td colspan=\"2\"><hr /></td></tr>
		<tr><td><b>Calcolo IVA da versare:</b></td><td align=\"right\"><b>&euro; $ivaTot</b></td></tr>
		<tr><td><b>Maggiorazione 1% (trimestrale):</b><td align=\"right\"><b>&euro; $maggTrim</b></td></tr>
		</table><br /><br />
		";
}

function invia_mailingList($con){
	global $nome_azienda, $emailAzienda;
	// compongo il messaggio da inviare
	// costruiamo alcune intestazioni generali
	$header = "From: $nome_azienda <$emailAzienda>\n";
	$header .= "X-Mailer: MercUsa Gestionale\n";
	// costruiamo le intestazioni specifiche per il formato HTML
	$header .= "MIME-Version: 1.0\n";
	$header .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
	$header .= "Content-Transfer-Encoding: 7bit\n\n";
	
	$destinatario = $_POST['destinatario'];
	$oggetto = $_POST['oggetto'];
	$corpo_db = mysqli_real_escape_string($con, $_POST['corpomail']);
	$corpo = $_POST['corpomail'];
	$data_mail = date("Y-m-d H:i:s");	

	if ($destinatario != ""){
		mail("$destinatario","$oggetto","$corpo","$header");
	} else {
		$query = "SELECT * FROM mercusa_clienti WHERE email != '' ";
		$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
		while ($results = mysqli_fetch_array($result)) {	
			$email = $results['email'];
			mail("$email","$oggetto","$corpo","$header");
		}
	}
	$sql = "INSERT INTO mercusa_email (id, oggetto, destinatario, corpo, data)
		VALUES (NULL, '$oggetto', '$destinatario', \"$corpo_db\", '$data_mail')";
	
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
}

function query_select_mailinglist($con){
	$query = "SELECT * FROM mercusa_email ORDER BY id DESC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	while ($results = mysqli_fetch_array($result)) {	
		$id = $results['id'];
		$oggetto = $results['oggetto'];
		$data = $results['data'];
		
		$riga .= "<tr><td>$id</td><td><a href=\"?loc=05&sez=dettaglio_mail&id_mail=$id\">$oggetto</a></td><td>$data</td></tr>";
	}
	return $riga;
}

function thumbize ($art_id, $url, $widthThumb, $heightThumb )
{
	global $endofurl;
	$path = '../accounts/'.$endofurl.'/thumb/';
	$cleantitle = preg_replace("/\W/", "", $art_id);
 
	$nomeimg = $cleantitle.'_'.$widthThumb.'x'.$heightThumb.'_thumb.jpg';
 
	if ( !file_exists( $path.$nomeimg ) ) {
		$tmpimgfile =  $path.'tmp_'.$cleantitle;
 
		$curl = curl_init($url);
		$fp = fopen($tmpimgfile , 'wb');
		curl_setopt($curl, CURLOPT_FILE, $fp);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_exec($curl);
		curl_close($curl);
		fclose($fp);
		 
		$tmpimg = imagecreatefromjpeg ( $tmpimgfile );
		$img = imagecreatetruecolor($widthThumb, $heightThumb);
		 
		$widthIm = imagesx ($tmpimg);
		$heightIm = imagesy ($tmpimg);
		 
		$rapportoIm = $widthIm/$heightIm;
		$rapportoThumb = $widthThumb/$heightThumb;
		 
		if ( $rapportoIm > $rapportoThumb )
		{
			$widthCrop = (int)($heightIm * $rapportoThumb);
			$heightCrop = $heightIm;
		 
			$setx = (int)(( $widthIm - $widthCrop ) / 2);
			$sety = 0;			
		} else if ( $rapportoIm < $rapportoThumb )
		{
			$widthCrop = $widthIm;
			$heightCrop = $widthIm / $rapportoThumb;
		 
			$setx = 0;
			$sety = ( $heightIm - $heightCrop ) /2;
		} else if ( $rapportoIm == $rapportoThumb )
		{
			$widthCrop = $widthIm;
			$heightCrop = $heightIm;
		 
			$setx = 0;
			$sety = 0;
		}
		 
		imagecopyresampled ( $img, $tmpimg, 0, 0, $setx, $sety, $widthThumb, $heightThumb , $widthCrop, $heightCrop );
		 
		imagejpeg($img, $path.$nomeimg, 50);
		 
		imagedestroy( $img ); 
		imagedestroy( $tmpimg );
		unlink ( $path.'tmp_'.$cleantitle );
	}
 
	return $path.$nomeimg;
}

function emailMessage() {
	global $endofurl;
	
	/* try to connect */
	$hostname = "{imap.gmail.com:993/imap/ssl}INBOX";
	$username = "spcnet.info";
	$password = "dariof4d";
	$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to E-mail: ' .     imap_last_error());
	/* grab emails */
	$emails = imap_search($inbox,'ALL');
	/* if emails are returned, cycle through each... */
	if($emails) {  
		/* begin output var */
		$output = '';  
		/* put the newest emails on top */
		rsort($emails);  
		/* for every email... */
		foreach($emails as $email_number) {
			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);
			$message = imap_fetchbody($inbox,$email_number,2);   
			$subject = $overview[0]->subject;
			$pos = strstr($subject, "articolo");
			//echo $pos."<br />";
			if ($pos !== false) {
				$oggetto .= '<p><span class="subject">subject '.$overview[0]->subject.'</span></p>';
			}
			
			/* output the email header information */    
			$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
			$output.= '<span class="subject">subject '.$overview[0]->subject.'</span> ';
			$output.= '<span class="from">'.$overview[0]->from.'</span>';
			$output.= '<span class="date">on '.$overview[0]->date.'</span>';
			$output.= '</div>';    
			/* output the email body */
			$output.= '<div class="body">'.$message.'</div>';
			//$pos = strpos("FIND_THIS", $message);
			//if ($pos !== false) {
				// print "found<br/>";
			//}
			//else {
				// print " not found <br/>";
			//}
		}  
		echo $oggetto.$message;
	} 
	/* close the connection */
	imap_close($inbox);
}

function selectAllSettings($con){
	$query = "SELECT * FROM mercusa_settings";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	while ($results = mysqli_fetch_array($result)) {	
		$id = $results['id'];
		$nome = $results['nome'];
		$contenuto = $results['contenuto'];
		$attivo = $results['attivo'];
		$descrizione = $results['descrizione'];
		if ($attivo == 1) :
			$attivo = "<i class=\"glyphicon glyphicon-ok-circle\" title=\"Funzione disponibile e utilizzabile\"> </i>";
		else :
			$attivo = "<i class=\"glyphicon glyphicon-remove-circle\" title=\"Funzione non utilizzabile\"> </i>";
		endif;
		if ($nome == "url_imgLogo") :
			$disabled = "disabled";
		else :
			$disabled = "";
		endif;
		$riga .= "<tr><td width=\"50px\">$id</td>
			<td width=\"200px\">$nome</td>
			<td width=\"300px\"><input type=\"text\" name=\"setting$id\" class=\"form-control\" value=\"$contenuto\" $disabled /></td>
			<td width=\"50px\" align=\"center\">$attivo</td>
			<td width=\"300px\">$descrizione</td>
			</tr>";
	}
	return $riga;
}

function UpdateAllSettings($con){
	for ($i=0; $i<=17; $i++){
		$form = "setting$i";
		$setting = $_POST["$form"];
		$sql = "UPDATE mercusa_settings SET contenuto='$setting'
				WHERE id=$i";
		if (!mysqli_query($con,$sql)) {
			die('Error: update riga di settings' . mysqli_error($con));
		}
	}
}

function logoutDropboxList() {
	include "lib/Dropbox/sample.php";
	
	delete_token('access');
	header("Location: http://5.249.150.30/mercusa/servers/ilripostiglio/index.php?loc=05&sez=Mailmessage");
}

function statusDocsOpen($con, $tipo_doc){
	$query = "SELECT * FROM mercusa_documenti WHERE tipo_doc = $tipo_doc AND open = 1";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	$rows = mysqli_num_rows($result);
	if ($rows == 0) :
		$status = "<span style=\"color: green;\"><i class=\"glyphicon glyphicon-ok-circle\" title=\"Tutti i documenti sono chiusi correttamente\"> </i></span>";
	else :
		$status = "<span style=\"color: red;\"><i class=\"glyphicon glyphicon-remove-circle\" title=\"Hai un documento ancora da chiudere\"> </i></span>";
	endif;
	
	return $status;
}

function query_selectCompleanni($con, $data){
	//questa funzione estrae i nominativi che compiono gli anni in una certa data
	$query = "SELECT * FROM mercusa_clienti WHERE data_nascita LIKE '%$data%' ";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	$numrows = mysqli_num_rows($result);
	if ($numrows==0){
		return $numrows;
	} else {
	while ($results = mysqli_fetch_array($result)) {
		$id_cliente = $results['id'];
		$data_nascita = date("d/m/Y", strtotime($results['data_nascita']));
		$nome = $results['nome'];
		$cognome = $results['cognome'];
		$riga .= "<tr><td></td><td>$data_nascita</td><td><a href=\"index.php?loc=05&sez=mailinglist&id_cliente=$id_cliente\" title=\"Invia gli auguri\">$nome $cognome</a></td></tr>";
		
	}
		  
	return $riga;
	}
	
}

?>


