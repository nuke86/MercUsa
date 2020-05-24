<?php
include_once "include.php"; 


if ($_GET['sez']=='art_new_insert'){
	
	query_insert_articoli($con);
	header("location: index.php?loc=02&sez=carico_multiplo");
	
} elseif($_GET['sez']=='art_cestina'){
	
	query_cestina($con, 'mercusa_articoli', $_GET['id']);
	header("location: index.php?loc=02&sez=start");
	
} elseif($_GET['sez']=='art_restore'){
	
	query_restore($con, 'mercusa_articoli', $_GET['id']);
	header("location: index.php?loc=02&sez=start");
	
} elseif($_GET['sez']=='art_update'){
	
	query_update_from_articoli($con, 'mercusa_articoli', $_POST['id']);
	header("location: index.php?loc=02&sez=start");
	
} elseif($_GET['sez']=='art_dettaglio'){
	
	$id_articolo = $_POST['id_articolo'];
	header("location: index.php?loc=02&sez=art_edit&id=$id_articolo");
	
} elseif($_GET['sez']=='filtra_sconto'){
	
	$data_filtro = $_POST['anno']."-".$_POST['mese']."-".$_POST['giorno']." 00:00:00";
	$giorno = $_POST['giorno'];
	$mese = $_POST['mese'];
	$anno = $_POST['anno'];
	$tipo = $_POST['tipo'];
	header("Location: index.php?loc=00&sez=da_scontare&data=$data_filtro&tipo=$tipo&giorno=$giorno&mese=$mese&anno=$anno");
} elseif($_GET['sez']=='search_engine'){
	$keys = $_POST['keys'];
	header("Location: index.php?loc=02&sez=start&search_keys=$keys");
} elseif($_GET['sez']=='stampa_maga'){
	$data_inizio = $_POST['anno']."-".$_POST['mese']."-".$_POST['giorno']." 00:00:01";
	$data_fine = $_POST['annoFine']."-".$_POST['meseFine']."-".$_POST['giornoFine']." 23:59:59";
	header("Location: modulo_stampa_magazzino.php?dataInizio=$data_inizio&dataFine=$data_fine");
} elseif($_POST['sez']=='aggiungi_inHome'){
	$id = $_POST['id_articolo'];
	query_aggiungi_inHome($con,$id);
	header("Location: index.php?loc=02&sez=gest_homepage");
} elseif($_GET['sez']=='art_no_homepage'){
	$id = $_GET['id'];
	query_art_no_homepage($con,$id);
	header("Location: index.php?loc=02&sez=gest_homepage");
}


// ******************************
// ******* funzioni utili *******
// ******************************
function query_insert_articoli($con){
	// escape variables for security
	$id_cliente = mysqli_real_escape_string($con, $_POST['id_cliente']);
	$nome = mysqli_real_escape_string($con, $_POST['nome']);
	$descrizione = mysqli_real_escape_string($con, $_POST['descrizione']);
	$prezzo = mysqli_real_escape_string($con, $_POST['prezzo']);
	$iva = mysqli_real_escape_string($con, $_POST['iva']);
	$prezzo_acq = mysqli_real_escape_string($con, $_POST['prezzo_acq']);
	$data_inserimento = $_POST['data_inserimento']." ".date('H:i:s');
	$codice_carico = $_POST['codice'];
	// tipo_mandato assume i seguenti valori: 
	// 1=standard, 2=no sconto50%, 3=personalizzato 
	$tipo_mandato = $_POST['tipo_mandato'];
	$provvigione = $_POST['provvigione'];
	
	$quantita = mysqli_real_escape_string($con, $_POST['quantita']);
	if ($quantita=="") $quantita = 1;
	$contabilita = $_POST['contabilita'];
	
	$sql="INSERT INTO mercusa_articoli (id, id_cliente, codice, nome, descrizione, prezzo, iva, 
		data_inserimento, quantita, attivo, contabilita, prezzo_acq, tipo_mandato, provvigione)
		VALUES (NULL, '$id_cliente', '$codice_carico', '$nome', '$descrizione', '$prezzo', '$iva', 
		'$data_inserimento', '$quantita', 1, '$contabilita', '$prezzo_acq', '$tipo_mandato', '$provvigione')
		";
	
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
	//echo "1 record added";

	mysqli_close($con);
}

function query_calcola_quantita_stmaga($con,$id_articolo,$data_inizio,$data_fine){
	// serve per calcolare quanti oggetti con un certo id sono stati venduti
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_movimenti WHERE id_articolo=$id_articolo AND data BETWEEN '$data_inizio' AND '$data_fine'";
	$result = mysqli_query($con, $query) or die('Errore...');
	
	// conto il numero di occorrenze trovate nel db
	$numrows = mysqli_num_rows($result);
	return $numrows;
}

function query_calcola_quantita($con,$id_articolo){
	// serve per calcolare quanti oggetti con un certo id sono stati venduti e/o resi
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_movimenti WHERE id_articolo=$id_articolo ";
	$result = mysqli_query($con, $query) or die('Errore...');
	// conto il numero di occorrenze trovate nel db
	$numrows_vendite = mysqli_num_rows($result);
	
	$query1 = "SELECT * FROM mercusa_movimenti_resi WHERE id_articolo=$id_articolo ";
	$result1 = mysqli_query($con, $query1) or die('Errore...');
	while ($results = mysqli_fetch_array($result1)) {
		$quantita = $results['quantita'];
		$tot_resi = $tot_resi+$quantita;
	}
	
	$calcoloQuantita = $numrows_vendite+$tot_resi;
	return $calcoloQuantita;
}

function query_select_articoli_scaduti($con,$data,$tipo) {
	// serve per estrarre una lista di articoli i quali sono 
	// in scadenza in una certa data per l'inserimento di uno sconto.
	$data_oggi_inizio60 = date("Y-m-d 00:00:00", strtotime($data. ' - 60 days'));
	$data_oggi_fine60 = date("Y-m-d 23:59:59", strtotime($data. ' - 60 days'));
	$data_oggi_inizio90 = date("Y-m-d 00:00:00", strtotime($data. ' - 90 days'));
	$data_oggi_fine90 = date("Y-m-d 23:59:59", strtotime($data. ' - 90 days'));
	
	if ($tipo == '60'){
		$query = "SELECT * FROM mercusa_articoli WHERE id NOT IN (SELECT id_articolo FROM mercusa_movimenti) AND (data_inserimento BETWEEN '$data_oggi_inizio60' AND '$data_oggi_fine60') ";
	} elseif ($tipo =='90'){
		$query = "SELECT * FROM mercusa_articoli WHERE id NOT IN (SELECT id_articolo FROM mercusa_movimenti) AND (data_inserimento BETWEEN '$data_oggi_inizio90' AND '$data_oggi_fine90') ";
	}
	
	$result = mysqli_query($con, $query) or die('Errore...');
	if (mysqli_num_rows($result)==0){
		$riga = "<tr><td colspan=\"5\">La ricerca non ha prodotto risultati per la data <b>$data</b></td></tr>";
	} else {
		$riga = "<div>Stai filtrando la data: <b>$data</b> con lo SCONTO <b>$tipo</b></div>";
	}
	while ($results = mysqli_fetch_array($result)) {   
    
		// recupero il contenuto di ogni record trovato
		$id = $results['id'];
		$nome = $results['nome'];
		$prezzo = $results['prezzo'];
		$descrizione = $results['descrizione'];
		$data_inserimento = $results['data_inserimento'];
		$id_cliente = $results['id_cliente'];
		$cliente = query_select_detail($con,$id_cliente,'mercusa_clienti');
		$mandante = $cliente['cognome']." ".$cliente['nome'];
		$quantita = $results['quantita'] - query_calcola_quantita($con, $id);
		$data_sconto60 = date("Y-m-d", strtotime($data_inserimento. ' + 60 days'));
		$data_sconto90 = date("Y-m-d", strtotime($data_inserimento. ' + 90 days'));
		
		$riga .= "
		<tr><td>$id</td><td>$nome</td><td>$descrizione</td><td>$mandante</td><td>$quantita</td><tr>
		";
	}
	return $riga;
}

function query_search_engine($con,$keys){
	// imposto ed eseguo la query

	$query = "SELECT * FROM mercusa_articoli WHERE nome LIKE '%$keys%' OR descrizione LIKE '%$keys%' ORDER BY data_inserimento DESC";
	$result = mysqli_query($con, $query) or die('Errore...1');

	// avvio un ciclo for che si ripete per il numero di occorrenze trovate
	while ($results = mysqli_fetch_array($result)) {
		$id = $results['id'];
		$nome = $results['nome'];
		$descrizione = $results['descrizione'];
		$prezzo = $results['prezzo'];
		$iva_articolo = $results['iva'];
		if ($iva_articolo != ""){
			$prezzo = $prezzo+(($prezzo*$iva_articolo)/100);
		}
		$data = $results['data_inserimento'];
		$riga .= "<tr><td><a href=\"index.php?loc=02&sez=art_edit&id=$id\">$id</a></td><td>$nome $descrizione</td><td>$prezzo</td><td>$data</td></tr>";
	}
	return $riga;
}

function query_select_articoli_list($con,$active,$contabilita,$limit){
	// imposto ed eseguo la query
$where = "WHERE contabilita='$contabilita' AND attivo='$active'";
if ($contabilita=='all') {
	$where = "WHERE attivo='$active'";
}
$limit_inizio = $limit-20;
$paginazione = "LIMIT $limit_inizio,20 ";
if ($limit == 'all'){
	$paginazione = "";
}
$query = "SELECT * FROM mercusa_articoli $where ORDER BY id DESC $paginazione";
$result = mysqli_query($con, $query) or die('Errore...1');

// conto il numero di occorrenze trovate nel db
$numrows = mysqli_num_rows($result);

// se il database è vuoto lo stampo a video
if ($numrows == 0){
  $select_finale = "Database vuoto!";
  echo $select_finale;
}
// se invece trovo delle occorrenze...
else {
  // avvio un ciclo for che si ripete per il numero di occorrenze trovate
  while ($results = mysqli_fetch_array($result)) {   
    
    // recupero il contenuto di ogni record trovato
     $id = $results['id'];
     $id_cliente = $results['id_cliente'];
	$nome = $results['nome'];
	$descrizione = $results['descrizione'];
	$data_inserimento_en = $results['data_inserimento'];
	$data_giorno = date('d',strtotime($data_inserimento_en));
	$data_mese = date('m',strtotime($data_inserimento_en));
	$data_anno = date('Y',strtotime($data_inserimento_en));
	$data_inserimento = $data_giorno .'/'. $data_mese .'/'. $data_anno;
	
	$quantita = $results['quantita'] - query_calcola_quantita($con, $id);
	$iva = $results['iva'];
	$prezzo_imp = floatval($results['prezzo']);
	$prezzo_online = $results['prezzo_online'];
	
	if ($iva != ""){
		$prezzo_tot = incremento_iva_articolo($con,$id);
	} else {
		$prezzo_tot = $prezzo_imp;
	}
	if ($contabilita=='ct') $print_contab='CT';
	if ($contabilita=='cv') $print_contab='CV';
	if ($prezzo_online == "") $prezzo_online=$prezzo_tot;
	
    	// questo e' il contenuto della tabella principale
    	if ($quantita > 0){
    		if ($active==1) {
    			$cestino = "<a href=\"query_articoli.php?sez=art_cestina&id=$id\" class=\"btn btn-sm btn-primary\" title=\"Segna come Prenotato/Acconto\"><i class=\"glyphicon glyphicon-lock\"></i></a>";
    		}elseif ($active==0) {
    			$cestino = "<a href=\"query_articoli.php?sez=art_restore&id=$id\" class=\"btn btn-sm btn-primary\" title=\"Ripristina in Vendita\"><i class=\"glyphicon glyphicon-eur\"></i></a>";
    		}
    		$prezzo_tot = money_format('%.2n',$prezzo_tot);
    		$prezzo_online = money_format('%.2n',$prezzo_online);
	    $select_finale .= "
	    <tr><td><a href=\"index.php?loc=02&sez=art_edit&id=$id\" id=\"hoverMe\" title=\"Dettaglio articolo\">".$print_contab."00".$id."</a><div class=\"box\"><iframe src=\"modulo_dettaglio_articolo_little.php?id=$id\" width = \"350px\" height = \"200px\"></iframe></div></td>
	    <td>".$nome."</td>
	    <td>".$prezzo_imp." &euro;</td>
	    <td>".$iva." %</td>
	    <td>".$prezzo_tot." &euro;</td>
	    <td>".$prezzo_online." &euro;</td>
	    <td>".$quantita."</td>
	    <td>".$data_inserimento.
	    "<td>$cestino
	<a href=\"index.php?loc=02&sez=art_edit&id=$id\" class=\"btn btn-sm btn-primary\" title=\"Modifica\"><i class=\"glyphicon glyphicon-pencil\"></i></a>
	<a href=\"etichetta.php?id=".$id."\" class=\"btn btn-sm btn-primary open\" title=\"Visualizza Etichetta\" ><i class=\"glyphicon glyphicon-tag\"></i></a>
  	<a href=\"etichetta.php?id=".$id."\" class=\"btn btn-sm btn-primary\" target=\"etic".$id."\" title=\"Stampa Etichetta\" ><i class=\"glyphicon glyphicon-print\"></i></a>
  	<a href=\"upload-form.php?id_art=".$id."\" class=\"btn btn-sm btn-primary open\" title=\"Carica Immagine\"><i class=\"glyphicon glyphicon-camera\"></i></a>
	    </td></tr>
	    ";
		$totale_prezzi=$totale_prezzi+$prezzo;
		}
}
if ($_GET['sez']=="start"){
//echo "Hai un valore totale ipotetico di ".$totale_prezzi." Euro. <br /><br />";
}

// chiudo la connessione
mysqli_close($con);

return $select_finale;
}
}

function query_stampa_magazzino($con,$data_inizio,$data_fine){
	// imposto ed eseguo la query
	
$query = "SELECT * FROM mercusa_articoli WHERE data_inserimento BETWEEN '$data_inizio' AND '$data_fine' AND contabilita='cv' ORDER BY id ASC";
$result = mysqli_query($con, $query) or die('Errore...1');

// conto il numero di occorrenze trovate nel db
$numrows = mysqli_num_rows($result);

// se il database è vuoto lo stampo a video
if ($numrows == 0){
  $select_finale = "Database vuoto!";
  echo $select_finale;
}
// se invece trovo delle occorrenze...
else {
  // avvio un ciclo for che si ripete per il numero di occorrenze trovate
  while ($results = mysqli_fetch_array($result)) {   
    
    // recupero il contenuto di ogni record trovato
     $id = $results['id'];
     $id_cliente = $results['id_cliente'];
	$nome = $results['nome'];
	$descrizione = $results['descrizione'];
	$data_inserimento_en = $results['data_inserimento'];
	$contabilita = $results['contabilita'];
	$data_giorno = date('d',strtotime($data_inserimento_en));
	$data_mese = date('m',strtotime($data_inserimento_en));
	$data_anno = date('Y',strtotime($data_inserimento_en));
	$data_inserimento = $data_giorno .'/'. $data_mese .'/'. $data_anno;
	
	$quantita = $results['quantita'] - query_calcola_quantita_stmaga($con, $id, $data_inizio, $data_fine);
	$prezzo = floatval($results['prezzo']);
	if ($contabilita=='ct') $print_contab='CT';
	if ($contabilita=='cv') $print_contab='CV';

	
    	// questo e' il contenuto della tabella principale
    	if ($quantita > 0){
	    $select_finale .= "
	    <tr><td>".$print_contab."00".$id."</td><td>".$nome."</td><td>".$descrizione."</td><td>".money_format('%.2n',$prezzo)." &euro;</td><td>".$quantita."</td><td>".$data_inserimento.
	    "</tr>
	    ";
	    $totale_prezzi=$totale_prezzi+($prezzo*$quantita);
	}
  }

return array($select_finale,$totale_prezzi);
}
}

function query_update_from_articoli($con,$table,$id){
    $id_cliente = $_POST['id_cliente'];
	$nome = mysqli_real_escape_string($con, $_POST['nome']);
	$descrizione = mysqli_real_escape_string($con, $_POST['descrizione']);
	$prezzo = $_POST['prezzo'];
	$prezzo_acq = $_POST['prezzo_acq'];
	$iva = $_POST['iva'];
	$data_inserimento = $_POST['data_inserimento'];
	$contabilita = $_POST['contabilita'];
	$quantita = $_POST['quantita'] + query_calcola_quantita($con, $id);
	$tipo_mandato = $_POST['tipo_mandato'];
	$flag_homepage = $_POST['flag_homepage'];
	$provvigione = $_POST['provvigione'];
	$prezzo_online = $_POST['prezzo_online'];
	
	$sql = "UPDATE $table SET id_cliente='$id_cliente', nome='$nome', descrizione='$descrizione', 
			prezzo='$prezzo', iva='$iva', data_inserimento='$data_inserimento', quantita='$quantita', 
			contabilita='$contabilita', prezzo_acq='$prezzo_acq', tipo_mandato='$tipo_mandato', 
			flag_homepage='$flag_homepage', provvigione='$provvigione', prezzo_online='$prezzo_online'
			WHERE id=$id";
			
	// controllo l'esito
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
	session_start();
	$user = $_SESSION['cod'];
	write_mysql_log("id=$id?user=$user?state=ARTICOLO MODIFICATO", $con);

	mysqli_close($con);
}

function query_aggiungi_inHome($con,$id){
	
	$sql = "UPDATE mercusa_articoli SET flag_homepage='1'
			WHERE id=$id";
			
	// controllo l'esito
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}

	mysqli_close($con);
}

function query_art_no_homepage($con,$id){
	
	$sql = "UPDATE mercusa_articoli SET flag_homepage='0'
			WHERE id=$id";
			
	// controllo l'esito
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}

	mysqli_close($con);
}



function query_select_articoli_homepage($con){
	global $imagesURL;
	// questo mostra gli articoli presenti in HomePage
	
	$query = "SELECT * FROM mercusa_articoli WHERE flag_homepage = '1' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
    
    // recupero il contenuto di ogni record rovato
    	$id = $results['id'];
	
	$nome_articolo = $results['nome'];
	$prezzo = $results['prezzo'];
	$prezzo_online = $results['prezzo_online'];
	$url_img = $results['url_img'];
	$img = $imagesURL.$url_img;
	$quantita = $results['quantita'] - query_calcola_quantita($con, $id);
	
	if ($quantita > 0) {
		$riga .= "
		<tr><td>00$id</td>
		<td>$nome_articolo</td>
		<td><b><i>$prezzo</i></b> &euro;</td>
		<td><b><i>$prezzo_online</i></b> &euro;</td>
		<td><img src=\"$img\" width=\"60px\" height=\"60px\" /></td>
		<td><b>
		|<a href=\"query_articoli.php?sez=art_no_homepage&id=$id\" title=\"Elimina da HomePage\">E</a>|</b>
		</td></tr>
		";
	}

	}
	return $riga;
}

function query_select_list_articoli_carico($con, $id_documento){
	// questo genera una lista di articoli (stampata a video) di uno specifico documento di carico (usato per il carico multiplo)
	// imposto ed eseguo la query
	$query = "SELECT * FROM mercusa_articoli WHERE codice = '$id_documento' ";
	$result = mysqli_query($con, $query) or die('Errore... '. mysqli_error($con));
		 
	while ($results = mysqli_fetch_array($result)) {   
    
    // recupero il contenuto di ogni record rovato
    	$id = $results['id'];
	
	$nome_articolo = $results['nome'];
	$prezzo_articolo = $results['prezzo'];
	$quantita = $results['quantita'];
	$contab_articolo = $results['contabilita'];
	if ($contab_articolo=='ct') {
		$print_contab='CT';
		$prezzo_articolo = $results['prezzo_acq'];
	}
	if ($contab_articolo=='cv') $print_contab='CV';
	
	$prezzo = $prezzo_articolo*$quantita;	
	$totale_prezzi=$totale_prezzi+$prezzo;
	
	$riga .= "
	<tr><td>$print_contab"."00$id</td><td>$nome_articolo</td><td><b>$prezzo</b> &euro;</td><td>$quantita</td>
	<td><b>
	|<a href=\"index.php?loc=02&sez=art_edit&id=$id\" title=\"Modifica\">M</a>|
	|<a href=\"query_start.php?sez=art_del_carico&id=$id\" title=\"Elimina da questo Carico\">E</a>|
	|<a href=\"etichetta.php?id=".$id."\" target=\"etic$id\" title=\"Stampa Etichetta\" >S.E.</a>|</b></td></tr>
	";

	
	}
	
	return array($riga,$totale_prezzi);	
}


?>
