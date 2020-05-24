<?php 
// modulo di gestione schermate articoli


include_once "include.php";
ini_set("session.gc_maxlifetime","86400");
ini_set("session.cookie_lifetime","86400");
session_start();
//se non c'e la sessione registrata
if (!$_SESSION['autorizzato']) {
  header("Location: login_form.php");
  die;
}

?>

<div class="panel panel-primary">
  <!-- Default panel contents -->
  
  <?php if ($_GET['sez']=='art_edit'){ 
  		$dettaglio = query_select_detail($con, $_GET['id'],'mercusa_articoli');
		$dettaglio_cliente = query_select_detail($con, $dettaglio[1],'mercusa_clienti');
		$quantita = $dettaglio[8] - query_calcola_quantita($con, $_GET['id']);
		$quantita_db = $dettaglio[8];
  	?>
  		
  		<div class="panel-heading" style="text-align: center;"><b>Modifica Articolo</b></div>
  			<div class="panel-body">
  			<a class="btn btn-default" href="?loc=02&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna Indietro</a><br /><br />
  			<?php
  			if ($dettaglio[9]==1) {
    			$cestino = "<a href=\"query_articoli.php?sez=art_cestina&id=".$_GET['id']."\" class=\"btn btn-sm btn-primary\" title=\"Segna come Prenotato\"><i class=\"glyphicon glyphicon-flag\"></i></a>";
    			}elseif ($dettaglio[9]==0) {
    			$cestino = "<a href=\"query_articoli.php?sez=art_restore&id=".$_GET['id']."\" class=\"btn btn-sm btn-primary\" title=\"Ripristina in Vendita\"><i class=\"glyphicon glyphicon-download-alt\"></i></a>";
    			}
  			echo "
  			$cestino
  			<a href=\"etichetta.php?id=".$_GET['id']."\" class=\"btn btn-sm btn-primary open\" title=\"Visualizza Etichetta\" ><i class=\"glyphicon glyphicon-tag\"></i></a>
  			<a href=\"etichetta.php?id=".$_GET['id']."\" class=\"btn btn-sm btn-primary\" target=\"etic".$_GET['id']."\" title=\"Stampa Etichetta\" ><i class=\"glyphicon glyphicon-print\"></i></a>
  			<a href=\"upload-form.php?id_art=".$_GET['id']."\" class=\"btn btn-sm btn-primary open\" title=\"Carica Immagine\" ><i class=\"glyphicon glyphicon-camera\"></i></a>
  			";
  			?>
  			PER MODIFICARE I CAMPI -> 
  			<a onClick="sbloccaCampi();" class="btn btn-sm btn-primary" title="Modifica Articolo" ><i class="glyphicon glyphicon-pencil"></i></a>
	    <br /><br />
  			
  			<form action="query_articoli.php?sez=art_update" method="post" onsubmit="return false;">
  			<input type="hidden" name="id_cliente" value="<?php echo $dettaglio_cliente[0]; ?>" />
  			<input type="hidden" id="idclientejson" value="" />
  			<table class="table">
  			  <tr>
  			  	<td>Cliente/Fornitore: <br />
  			  	<input type="text" name="cliente" id="id_cliente" class="form-control" value="<?php echo $dettaglio_cliente[2]." ".$dettaglio_cliente[1]; ?>" disabled="true">
  			  	</td>
  			  	<td align="center">Nome: <br />
  			  	<input type="text" name="nome" class="form-control" value="<?php echo $dettaglio[3]; ?>" id="nome" disabled="true">
  			  	</td>
  			  	<td>Descrizione: <br />
  			  	<textarea name="descrizione" class="form-control" id="descr" disabled="true"><?php echo $dettaglio[4]; ?></textarea>
  			  	</td>
  			  	<td>Prezzo unitario: <br />
  			  	<input type="text" name="prezzo" class="form-control" value="<?php echo $dettaglio[5]; ?>" id="prezzo" disabled="true">
  			  	</td>
  			  	<td>IVA %: <br />
  			  	<input type="text" name="iva" class="form-control" value="<?php echo $dettaglio[6]; ?>" id="iva" disabled="true">
  			  	</td>
  			  	
  			  	<td>Totale (+IVA):<br />
					<?php $tot = $dettaglio['prezzo']+(($dettaglio['prezzo']*($dettaglio[6]/2))/100); 
					?>
					<input type="text" disabled="true" class="form-control" value="<?php echo $tot; ?>" />
  			  	</td>
  			  	</tr>
  			  <tr>
  			  <?php if ($CustomProvvigioni == 'yes'){ ?>
  			  	  <td>Provvigione Negozio:<br />
					<select class="form-control" name="provvigione">
  			  			<option name="provvigione" value="40" selected>40 %</option>
  			  			<option name="provvigione" value="30">30 %</option>
  			  			<option name="provvigione" value="50">50 %</option>
  			  		</select>
  			  	</td>
  			  	  
  			  <?php }else{ ?>
  			  	<td>Tipo Mandato:<br />
					<select class="form-control" name="tipo_mandato" id="mandato" disabled="true">
					<?php if ($dettaglio['tipo_mandato']==1) { ?>
  			  			<option name="tipo_mandato" value="1" selected>Standard</option>
  			  			<option name="tipo_mandato" value="2">No Sconto 50%</option>
  			  			<option name="tipo_mandato" value="3">Personalizzato Cartaceo</option>
  			  		<?php } elseif ($dettaglio['tipo_mandato']==2) { ?>
  			  			<option name="tipo_mandato" value="1">Standard</option>
  			  			<option name="tipo_mandato" value="2" selected>No Sconto 50%</option>
  			  			<option name="tipo_mandato" value="3">Personalizzato Cartaceo</option>
  			  		<?php } elseif ($dettaglio['tipo_mandato']==3) { ?>
  			  			<option name="tipo_mandato" value="1">Standard</option>
  			  			<option name="tipo_mandato" value="2">No Sconto 50%</option>
  			  			<option name="tipo_mandato" value="3" selected>Personalizzato Cartaceo</option>
  			  		<?php } else { ?>
  			  			<option name="tipo_mandato" value="1" selected>Standard</option>
  			  			<option name="tipo_mandato" value="2">No Sconto 50%</option>
  			  			<option name="tipo_mandato" value="3">Personalizzato Cartaceo</option>
  			  		<?php } ?>
  			  	</select>
  			  	</td>
  			  <?php } ?>	
  			  	<td>Data Inserimento: <br />
  			  	<input type="text" name="data_inserimento" class="form-control" value="<?php echo $dettaglio[7]; ?>" id="data" disabled="true">
  			  	</td>
  			  	<td>Quantit&agrave; (netto VENDITE/RESI): <br />
  			  	<input type="text" name="quantita" class="form-control" value="<?php echo $quantita; ?>" id="quant" disabled="true">
  			  	<br />Movimenti (RESI/VENDITE): <span class="badge"><?php echo query_calcola_quantita($con,$_GET['id']); ?></span>
  			  	<br />Quantit&agrave; originali di carico: <span class="badge"><?php echo $quantita_db; ?></span>
  			  	</td>
  			  	<td>Tipo di Magazzino:<br />
  			  		<select class="form-control" name="contabilita" id="contab" onchange="show_hide()" disabled="true">
  			  		<?php if ($dettaglio[10]=="ct") { ?>
  			  			<option name="contabilita" value="ct" selected>Conto Terzi (CT)</option>
  			  			<option name="contabilita" value="cv" >Conto Vendita (CV)</option>
  			  		<?php }else{ ?>
  			  			<option name="contabilita" value="cv" selected>Conto Vendita (CV)</option>
  			  			<option name="contabilita" value="ct" >Conto Terzi (CT)</option>
  			  		<?php } ?>
  			  		
  			  		</select>
  			  	</td>
  			  	<td>HomePage: <br />
  			  		<select class="form-control" name="flag_homepage" id="home" disabled="true">
  			  		<?php if ($dettaglio['flag_homepage']==1) { ?>
  			  			<option name="flag_homepage" value="1" selected>Si</option>
  			  			<option name="flag_homepage" value="0" >No</option>
  			  		<?php }else{ ?>
  			  			<option name="flag_homepage" value="0" selected>No</option>
  			  			<option name="flag_homepage" value="1" >Si</option>
  			  		<?php } ?>
  			  		</select>
  			  	</td>
  			  	<td>Prezzo online: <br />
  			  		<input type="text" name="prezzo_online" value="<?php echo $dettaglio[18]; ?>">
  			  	</td>
				<input type="hidden" name="id" value="<?php echo $dettaglio[0]; ?>">
				<td><span id="prezzo_acq_label" style="display: none;">Prezzo acquisto:</span>
				<input type="text" name="prezzo_acq" class="form-control" id="prezzo_acq" style="display: none;" value="<?php echo $dettaglio[11]; ?>" disabled="true" />
				</td>

				<td align="right"><br />
				<button type="submit" onmouseup="submit();" class="btn btn-default" id="salva"><i class="glyphicon glyphicon-floppy-disk"></i> Salva</button></td>
  			  </tr><tr><td colspan="3">
  			  <?php 
  			$sconto60 = date("d-m-Y", strtotime($dettaglio[7]. ' + 60 days')); 
  			$sconto90 = date("d-m-Y", strtotime($dettaglio[7]. ' + 90 days'));
  			echo "Questo articolo ha SCONTO-60 il: $sconto60<br />
  			e SCONTO-90 il: $sconto90
  			";
  			?></td></tr>
  			</table>
  			
  			<img src="<?php echo $imagesURL.$dettaglio[12]; ?>" width="30%" height="30%" />
  			
  			</form>
  			  </div><br /><br /><br /><br />
  			  
  	<?php } elseif ($_GET['sez']=='art_trash'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Articoli Prenotati/Bloccati su Acconto</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=02&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna Indietro</a>
		  	<a class="btn btn-default" href="?loc=02&sez=art_new"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		  <table class="table">
		    <tr><td><b>Codice</b></td><td><b>Nome</b></td><td><b>Prezzo</b></td><td><b>Quantit&agrave;</b></td><td><b>Data Inserimento</b></td><td><b>Opzioni</b></td></tr>
		    <?php echo query_select_articoli_list($con,0,'all',20); ?>
  		</table><br /><br />	
  	
  	<?php } elseif ($_GET['sez']=='ct_maga'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Magazzino Conto Terzi [CT]</b></div>
	 	<div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=02&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna Indietro</a>
		 
	  	</div>
		  <!-- Lista delle anagrafiche -->
		  <p align="center">
		  Pagina: 
		  <select name="pagine" onchange="window.location.href='index.php?loc=02&sez=ct_maga&limit='+this.value">
		  <?php
		  $query = "SELECT * FROM mercusa_articoli WHERE contabilita='ct'";
		  $result = mysqli_query($con, $query) or die('Errore...');
		  $numrows = mysqli_num_rows($result);
		  $pagine = $numrows/20;
		  $pagina = $_GET['limit']/20;
		  if ($_GET['limit']==""){
		  	  $pagina = 1;
		  }
		  for($i=1;$i<$pagine;$i++){
		  	  $p = $i*20;
		  	  if ($pagina==$i){
		  	  	  
		  	  	  echo "<option name=\"pagine\" selected value=\"$p\">$i</option>";
		  	  	  //echo "<a style=\"color: red;\" href=\"index.php?loc=02&sez=cv_maga&limit=" . $i*20 . "\">" . $i . "</a> ";
		  	  }else{
		  	  	  echo "<option name=\"pagine\" value=\"$p\">$i</option>";
		  	  	  //echo "<a href=\"index.php?loc=02&sez=cv_maga&limit=" . $i*20 . "\">" . $i . "</a> ";
		  	  }
		  }
		  ?>
		  </select>
		  <a href="index.php?loc=02&sez=ct_maga&limit=<?php echo $_GET['limit']-20; ?>">Indietro</a> | 
		  <a href="index.php?loc=02&sez=ct_maga&limit=<?php echo $_GET['limit']+20; ?>">Avanti</a>
		  </p>
		  <br />
		<table class="table">
		    <tr><td><b>Codice</b></td>
		    <td><b>Nome</b></td><td><b>Imponibile</b></td>
		    <td><b>IVA</b></td>
		    <td><b>Prezzo Totale</b></td>
		    <td><b>Prezzo Online</b></td>
		    <td><b>Quantit&agrave;</b></td>
		    <td><b>Data Inserimento</b></td>
		    <td><b>Opzioni</b></td></tr>
		    <?php 
		    if ($_GET['limit']==""){
		    	    $limit = 20;
		    }else{
		    	    $limit = $_GET['limit'];
		    }
		    echo query_select_articoli_list($con,1,'ct',$limit); ?>
  		</table>
  		
  		<br /><br /><br /><br />
  	
  	<?php } elseif ($_GET['sez']=='cv_maga'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Magazzino Conto Vendita [CV]</b></div>
	 	<div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=02&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna Indietro</a>
		  	<a class="window-iframe btn btn-default" data-title="Stampa di Magazzino" data-width="412" data-height="184" href="modulo_stampa_magazzino.php"><i class="glyphicon glyphicon-print"></i> Stampa</a>
	  	</div>
		  <!-- Lista delle anagrafiche -->
		  <p align="center">
		  Pagina: 
		  <select name="pagine" onchange="window.location.href='index.php?loc=02&sez=cv_maga&limit='+this.value">
		  <?php
		  $query = "SELECT * FROM mercusa_articoli WHERE contabilita='cv'";
		  $result = mysqli_query($con, $query) or die('Errore...');
		  $numrows = mysqli_num_rows($result);
		  $pagine = $numrows/20;
		  $pagina = $_GET['limit']/20;
		  if ($_GET['limit']==""){
		  	  $pagina = 1;
		  }
		  for($i=1;$i<$pagine;$i++){
		  	  $p = $i*20;
		  	  if ($pagina==$i){
		  	  	  
		  	  	  echo "<option name=\"pagine\" selected value=\"$p\">$i</option>";
		  	  	  //echo "<a style=\"color: red;\" href=\"index.php?loc=02&sez=cv_maga&limit=" . $i*20 . "\">" . $i . "</a> ";
		  	  }else{
		  	  	  echo "<option name=\"pagine\" value=\"$p\">$i</option>";
		  	  	  //echo "<a href=\"index.php?loc=02&sez=cv_maga&limit=" . $i*20 . "\">" . $i . "</a> ";
		  	  }
		  }
		  ?>
		  </select>
		  <a href="index.php?loc=02&sez=cv_maga&limit=<?php echo $_GET['limit']-20; ?>">Indietro</a> | 
		  <a href="index.php?loc=02&sez=cv_maga&limit=<?php echo $_GET['limit']+20; ?>">Avanti</a>
		  </p>
		  <br />
		<table class="table">
		    <tr><td><b>Codice</b></td>
		    <td><b>Nome</b></td>
		    <td><b>Imponibile</b></td>
		    <td><b>IVA</b></td>
		    <td><b>Prezzo Totale</b></td>
		    <td><b>Prezzo Online</b></td>
		    <td><b>Quantit&agrave;</b></td>
		    <td><b>Data Inserimento</b></td>
		    <td><b>Opzioni</b></td></tr>
		    <?php 
		    if ($_GET['limit']==""){
		    	    $limit = 20;
		    }else{
		    	    $limit = $_GET['limit'];
		    }
		    echo query_select_articoli_list($con,1,'cv',$limit); ?>
  		</table>
  		
  		<br /><br /><br /><br />
  <?php } 
  	  // da qui inizio i moduli per il carico multiplo
  	elseif ($_GET['sez']=='carico_multiplo'){ 
  	//controllo per stabilire se aprire una nuova sessione di carico o chiudere quella gia in compilazione
  	$open = query_select_doc_open($con,1);
	if ($open!='0'){
		echo "<script> window.location.replace('index.php?loc=02&sez=carico_multiplo01') </script>";
	}
  	?>
  <div class="panel-heading" style="text-align: center;"><b>Carico Multiplo articoli</b></div>
	 	 <div class="panel-body">
		  	<!-- Campi del modulo -->
		  	<form action="query_start.php" method="post" onsubmit="return IsEmpty();" >
		  		<input type="hidden" name="sez" value="apri_carico" />
		  		<input type="hidden" id="idclientejson" name="id_cliente" value="" />
		  		<table class="table">
		  			<tr>
		  			<td align="right"><b>Seleziona il Codice Fornitore:</b> &nbsp;<i class="glyphicon glyphicon-user"></i>&nbsp;</td>
		  			<td><input type="text" id="id_cliente" name="cliente" placeholder="Cerca l'anagrafica" class="form-control" /></td>
		  			
		  			<td align="right"><b>Data di carico:</b> &nbsp;&nbsp;</td>
		  			<td><input type="text" name="data_doc" class="form-control" value="<?php echo date('Y-m-d');?>" /></td>
		  			
		  			</tr><tr>
		  			<td colspan="4" align="center"><button type="submit" id="submit"><i class="glyphicon glyphicon-file"></i> Inizia nuovo Carico</button></td>
		  		</tr></table>
			</form>
			<br />
			<div class="alert alert-info" role="alert">
				<b>HELP:</b> bisogna tenere presente che non &egrave; consentito, nello stesso carico, inserire articoli in CONTO VENDITA 
				e contemporaneamente altri in CONTO TERZI. Per i due modelli contabili bisogna inserire <u>due distinte</u> ricevute di carico: in una, solo 
				articoli CONTO VENDITA (beni che i clienti ci affidano senza perderne il possesso fino alla vendita) e in un'altra solo CONTO TERZI (beni che 
				decidiamo di acquistare immediatamente ai clienti che li portano in negozio per poi rivenderli per nostro conto).
			</div>
			<br />
	  	 </div>
		  <br /><br />
  <?php } elseif ($_GET['sez']=='carico_multiplo01'){ ?>
  <div class="panel-heading" style="text-align: center;"><b>Lista di Carico [APERTA]</b></div>
	 	 <div class="panel-body">
	 	 	<?php
			$campo_doc = query_select_doc_open_details($con,1);
			$cliente = query_select_detail($con, $campo_doc['id_cliente'], 'mercusa_clienti');
			?>
		  	<!-- Campi del modulo -->
		  	<a class="btn btn-default" href="?loc=02&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna Indietro</a><br /><br />
  			
  			<form action="query_articoli.php?sez=art_new_insert" method="post">
  			<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $cliente['id']; ?>" />

  			<table class="table">
  			  <tr>
  			  	<td><input type="text" name="nome" class="form-control" placeholder="Nome Articolo" autofocus required /></td>
  			  	<td><textarea name="descrizione" class="form-control" placeholder="Breve descrizione" required ></textarea></td>
  			  	<td><select class="form-control" name="contabilita" id="contab" onchange="show_hide()">
  			  			<option name="contabilita" value="ct" >Conto Terzi (CT)</option>
  			  			<option name="contabilita" value="cv" selected>Conto Vendita (CV)</option>
  			  		</select>
  			  	</td>
  			  	<td>IVA <input type="text" name="iva" value="22" size="10" /> %</td>
  			  	<td><input type="text" name="data_inserimento" id="datepicker" class="form-control" value="<?php echo date('Y-m-d');?>" /></td>
  			  	</tr>
  			  <tr>
  			  	<td><input type="text" name="prezzo" id="prezzo" class="form-control" onkeyup="updateIva()" autocomplete="off" placeholder="Prezzo di vendita unitario" required />
  			  		<input type="text" id="totIva" readonly="yes" size="10" /> Euro
  			  	</td>
  			  	
  			  	<td><input type="text" name="quantita" class="form-control" placeholder="Quantit&agrave; *default: 1" /></td>
  			  	
  			  	<?php if ($CustomProvvigioni == 'yes'){ ?>
  			  	<td>Provvigione Negozio:<br />
					<select class="form-control" name="provvigione">
  			  			<option name="provvigione" value="40" selected>40 %</option>
  			  			<option name="provvigione" value="30">30 %</option>
  			  			<option name="provvigione" value="50">50 %</option>
  			  		</select>
  			  	</td>
  			  	  
  			  	<?php }else{ ?>
  			  	<td><select class="form-control" name="tipo_mandato">
  			  			<option name="tipo_mandato" value="1" selected>Standard</option>
  			  			<option name="tipo_mandato" value="2">No Sconto 50%</option>
  			  			<option name="tipo_mandato" value="3">Personalizzato Cartaceo</option>
  			  	</select>
  			  	</td>
  			  	<?php } ?>
  			  	<input type="hidden" name="codice" value="<?php echo $campo_doc['id']; ?>" />
  			  
				<td align="right"><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> Aggiungi</button></td>
  			  </tr> 
  			  <tr><td><input type="text" name="prezzo_acq" class="form-control" id="prezzo_acq" style="display: none;" placeholder="Prezzo di acquisto" /></td>
  			  </tr>
  			</table>
  			</form>
			<br />
			Lista di Carico del cliente: <i class="glyphicon glyphicon-user"></i> <b><?php echo ' '. $cliente['nome'].' '.$cliente['cognome']; ?></b>
			<br />
			<table class="table">
		    <tr><td><b># Codice</b></td><td><b>Nome</b></td><td><b>Prezzo</b></td><td><b>Quantit&agrave;</b></td><td><b>Opzioni</b></td></tr>
		   <?php 
			   $riga_mov = query_select_list_articoli_carico($con, $campo_doc['id']);
			   echo $riga_mov[0];
		    ?>
		    <tr><td><b>Totale:</b> <?php echo $riga_mov[1]; ?> Euro</td></tr>
  		</table><br /><br />
  		<form action="query_start.php" method="post">
  			<input type="hidden" name="sez" value="stampa_carico" />
  			<input type="hidden" name="id_documento" value="<?php echo $campo_doc['id']; ?>" />
  			<table class="table"><tr>
  				<td>
  					<button type="submit" class="btn btn-default" 
  					onClick=window.open("stampa_carico.php?id=<?php echo $campo_doc['id']; ?>","ricevuta") 
  					title="Chiude l'operazione corrente e Stampa la Lista"><i class="glyphicon glyphicon-floppy-disk"></i> Salva/Stampa</button>
  				</td>
  				<td align="right">
  					<a class="btn btn-default" title="Annulla definitivamente l'operazione di carico" 
  						href="query_start.php?sez=svuota_carico&id=<?php echo $campo_doc['id']; ?>" 
  						onclick="return confirm('Sicuro di voler ANNULLARE? Operazione NON REVERSIBILE');">
  						<i class="glyphicon glyphicon-remove"></i> Annulla e Chiudi
  					</a>
  				</td>
  				</tr>
  			</table>
  		</form>
  		
	  	 </div>
		  <br /><br />
		  
  	<?php } elseif ($_GET['sez']=='gest_homepage'){ ?>
  		<div class="panel-heading" style="text-align: center;"><b>Gestione Home Page</b></div>
	 	 <div class="panel-body">
	 	 <a class="btn btn-default" href="?loc=02&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna Indietro</a><br /><br />
	 	 Da qui puoi gestire gli articoli presenti in HomePage, scegliere quali far visualizzare e quali togliere 
	 	 (se il tuo sito &egrave; abilitato a mostrarli).<br /><br />
	 	 <form action="query_articoli.php" method="post">
		  		<input type="hidden" name="sez" value="aggiungi_inHome" />
		  		<table >
		  			<tr>
		  			<td>Seleziona il Codice Articolo: &nbsp;&nbsp;</td>
		  			<td><input type="text" id="id_articolo" name="id_articolo" placeholder="Cerca l'articolo" class="form-control" /></td>
		  			<td>&nbsp;</td>
		  			<td><button type="submit" class="btn-default form-control"><i class="glyphicon glyphicon-file"></i> Aggiungi articolo</button></td>
		  		</tr></table>
		</form><br /><br />
	 	 <table class="table">
	 	 <tr>
	 	 <td><b># Codice</b></td>
	 	 <td><b>Nome articolo</b></td>
	 	 <td><b>Prezzo</b></td>
	 	 <td><b>Prezzo Online</b></td>
	 	 <td><b>Anteprima</b></td>
	 	 <td><b>Opzioni</b></td></tr>
	 	 <?php echo query_select_articoli_homepage($con); ?>
	 	 </table>
	 	 </div>
		  <br /><br /><br /><br />
  			  
  	<?php } elseif ($_GET['sez']=='start'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione Articoli</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=02&sez=carico_multiplo"><i class="glyphicon glyphicon-file"></i> Carica Articolo/i</a>
		  	<a class="btn btn-default" href="?loc=02&sez=art_trash"><i class="glyphicon glyphicon-lock"></i> Prenotati</a>
		  	<a class="btn btn-default" href="?loc=02&sez=ct_maga"><i class="glyphicon glyphicon-gift"></i> Magazzino Conto Terzi</a>
		  	<a class="btn btn-default" href="?loc=02&sez=cv_maga"><i class="glyphicon glyphicon-retweet"></i> Magazzino Conto Vendita</a>
		  	<a class="btn btn-default" href="?loc=02&sez=gest_homepage"><i class="glyphicon glyphicon-star"></i> Situazione HomePage</a>
	  	 </div>
	  	 	<table class="table">
	  	 		<tr>
	  	 		<td>
	  	 		<form action="query_articoli.php?sez=art_dettaglio" method="post">
		  			Filtra articolo specifico:<br />
		  			<input type="text" id="id_articolo" name="id_articolo" placeholder="Cerca articolo" /><br />
		  			<button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-screenshot"></i> Vai a Dettaglio</button>
		  		</form>
		  		</td>
		  		<td align="center">
	  	 		<form action="query_articoli.php?sez=search_engine" method="post">
		  			Motore di ricerca Articoli:<br />
		  			<input type="text" name="keys" placeholder="Parola chiave" class="form-control" /><br />
		  			<button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Cerca</button>
		  		</form>
		  		</td>
		  		</tr>
	  	 </table>
	  	 <?php 
	  	 	if ($_GET['search_keys'] != "") {
	  	 		$search_keys = $_GET['search_keys'];
	  	 		echo "<h3>Risultati di ricerca per: <i>$search_keys</i></h3>";
	  	 		echo "<table class=\"table\"><tr>
	  	 			<td># Codice</td><td>Nome</td><td>Prezzo</td><td>Data inserimento</td></tr>
	  	 			";
	  	 		echo query_search_engine($con,$search_keys);
	  	 		echo "</table>";
	  	 	}
	  	 ?>
  		<br /><br />
  <?php } ?>
  
</div>
