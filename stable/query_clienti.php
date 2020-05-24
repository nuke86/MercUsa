<?php
include_once 'include.php';

if ($_GET['sez']=='cl_new_insert'){
	
	query_insert_clienti($con);
	if ($_POST['fatt']==1){
		header("location: index.php?loc=04&sez=nuova_fattura_acquisto");
	} else {
		header("location: index.php?loc=01&sez=start");
	}
	
} elseif($_GET['sez']=='cl_delete'){
	
	query_delete_from($con, 'mercusa_clienti', $_GET['id']);
	header("location: index.php?loc=01&sez=start");
	
} elseif($_GET['sez']=='cl_update'){
	
	query_update_from($con, 'mercusa_clienti', $_POST['id']);
	header("location: index.php?loc=01&sez=start");
	
} elseif($_GET['sez']=='cl_cestina'){
	
	query_cestina($con, 'mercusa_clienti', $_GET['id']);
	header("location: index.php?loc=01&sez=start");
	
} elseif($_GET['sez']=='cl_restore'){
	
	query_restore($con, 'mercusa_clienti', $_GET['id']);
	header("location: index.php?loc=01&sez=start");
} elseif($_GET['sez']=='cliente_dettaglio'){
	$id_cliente = $_POST['id_cliente'];
	header("location: index.php?loc=01&sez=cl_edit&id=$id_cliente");
}

// ******************************
// ******* funzioni utili *******
// ******************************
function query_insert_clienti($con){
	// escape variables for security
	$nome = mysqli_real_escape_string($con, $_POST['nome']);
	$cognome = mysqli_real_escape_string($con, $_POST['cognome']);
	$cod_fiscale = mysqli_real_escape_string($con, $_POST['cod_fiscale']);
	$p_iva = mysqli_real_escape_string($con, $_POST['p_iva']);
	$indirizzo = mysqli_real_escape_string($con, $_POST['indirizzo']);
	$data_nascita = $_POST['anno'].'-'.$_POST['mese'].'-'.$_POST['giorno'];
	$citta = mysqli_real_escape_string($con, $_POST['citta']);
	$cap = mysqli_real_escape_string($con, $_POST['cap']);
	$provincia = mysqli_real_escape_string($con, $_POST['provincia']);
	$telefono = mysqli_real_escape_string($con, $_POST['telefono']);
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$password = random_password(6);
	$tipo_doc_id = mysqli_real_escape_string($con, $_POST['tipo_doc_id']);
	$num_doc_id = mysqli_real_escape_string($con, $_POST['num_doc_id']);
	$citta_nascita = mysqli_real_escape_string($con, $_POST['citta_nascita']);

	
	$sql="INSERT INTO mercusa_clienti (id, nome, cognome, cod_fiscale, p_iva, indirizzo, data_nascita, citta, cap, provincia, telefono, email, password, 
		tipo_doc_id, num_doc_id, attivo, citta_nascita)
		VALUES (NULL, '$nome', '$cognome', '$cod_fiscale', '$p_iva', '$indirizzo', '$data_nascita', '$citta', '$cap', '$provincia', '$telefono', '$email', 
		'$password', '$tipo_doc_id', '$num_doc_id', '1', '$citta_nascita')";
	
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}
	//echo "1 record added";

	mysqli_close($con);
}

function query_select_clienti_list($con,$active,$limite){
	// imposto ed eseguo la query
$query = "SELECT * FROM mercusa_clienti ORDER BY id DESC LIMIT $limite";
$result = mysqli_query($con, $query) or die('Errore...');

// conto il numero di occorrenze trovate nel db
$numrows = mysqli_num_rows($result);

// se il database è vuoto lo stampo a video
if ($numrows == 0){
  $select_finale = "Database vuoto!";
}
// se invece trovo delle occorrenze...
else
{
  // avvio un ciclo for che si ripete per il numero di occorrenze trovate
  while ($results = mysqli_fetch_array($result)) {   
    
    // recupero il contenuto di ogni record rovato
    $id = $results['id'];
    $nome = $results['nome'];
    $cognome = $results['cognome'];
	$cod_fiscale = $results['cod_fiscale'];
	$p_iva = $results['p_iva'];
	$indirizzo = $results['indirizzo'];
	$data_nascita = $results['data_nascita'];
	$citta = $results['citta'];
	$cap = $results['cap'];
	$provincia = $results['provincia'];
	$telefono = $results['telefono'];
	$email = $results['email'];
	$password = $results['password'];
	$tipo_doc_id = $results['tipo_doc_id'];
	$num_doc_id = $results['num_doc_id'];
	$attivo = $results['attivo'];

    // stampo a video il risultato
    if(($attivo==1)AND($active==1)){
	    $select_finale .= "
	    <tr><td>".$id."</td><td>".$cognome." ".$nome."</td><td>".$indirizzo."</td><td>".$telefono.
	    "</td><td>".$email."</td><td><b>|<a href=\"query_clienti.php?sez=cl_cestina&id=$id\" title=\"Sposta nel Cestino\">C</a>|
	    |<a href=\"stampa_mandato.php?id=$id\" title=\"Stampa Mandato\" target=\"$id\">S.M.</a>|
	    |<a href=\"stampa_mandato_pdf.php?id=$id\" title=\"Genera PDF Mandato\" target=\"$id\">PDF</a>|
	    |<a href=\"index.php?loc=01&sez=cl_edit&id=$id\" title=\"Modifica\">M</a>|</b></td></tr>
	    ";
	}elseif(($attivo==0)AND($active==0)){
		$select_finale .= "
	    <tr><td>".$id."</td><td>".$cognome." ".$nome."</td><td>".$indirizzo."</td><td>".$telefono.
	    "</td><td>".$email."</td><td><b>|<a href=\"query_clienti.php?sez=cl_restore&id=$id\" title=\"Ripristina in Clienti\">R</a>| 
	    |<a href=\"index.php?loc=01&sez=cl_edit&id=$id\" title=\"Modifica\">M</a>|</b></td></tr>
	    ";
	}
  }
}

// chiudo la connessione
mysqli_close($con);

return $select_finale;
}

function query_select_detail($con,$id,$table){
	// imposto ed eseguo la query
	$query = "SELECT * FROM $table WHERE id=$id";
	$result = mysqli_query($con, $query) or die('Errore...funzione select_details '.$table.' '.$id);
	$riga = mysqli_fetch_array($result);
	
	return $riga;
}

function query_update_from($con,$table,$id){
	$nome = mysqli_real_escape_string($con, $_POST['nome']);
	$cognome = mysqli_real_escape_string($con, $_POST['cognome']);
	$cod_fiscale = mysqli_real_escape_string($con, $_POST['cod_fiscale']);
	$p_iva = $_POST['p_iva'];
	$indirizzo = mysqli_real_escape_string($con, $_POST['indirizzo']);
	$data_nascita = $_POST['data_nascita'];
	$citta = mysqli_real_escape_string($con, $_POST['citta']);
	$cap = $_POST['cap'];
	$provincia = mysqli_real_escape_string($con, $_POST['provincia']);
	$telefono = $_POST['telefono'];
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$citta_nascita = mysqli_real_escape_string($con, $_POST['citta_nascita']);	
	$password = $_POST['password'];
	$tipo_doc_id = $_POST['tipo_doc_id'];
	$num_doc_id = $_POST['num_doc_id'];
	
	$sql = "UPDATE $table SET nome='$nome', cognome='$cognome', cod_fiscale='$cod_fiscale', 
			p_iva='$p_iva', indirizzo='$indirizzo', data_nascita='$data_nascita', 
			citta='$citta', cap='$cap', provincia='$provincia', telefono='$telefono', 
			email='$email', password='$password', tipo_doc_id='$tipo_doc_id', 
			num_doc_id='$num_doc_id', citta_nascita='$citta_nascita'
			WHERE id=$id";
			
	// controllo l'esito
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}

	mysqli_close($con);
}

function query_restore($con,$table,$id){
	$sql = "UPDATE $table SET attivo='1' WHERE id=$id";
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}

	mysqli_close($con);
}

function query_cestina($con,$table,$id){
	$sql = "UPDATE $table SET attivo='0' WHERE id=$id";
	if (!mysqli_query($con,$sql)) {
  		die('Error: ' . mysqli_error($con));
	}

	mysqli_close($con);
}

function query_delete_from($con,$table,$id){
	
	mysqli_query($con,"DELETE FROM $table WHERE id=$id");
	mysqli_close($con);
}


function random_password( $length = 8 ) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

?>