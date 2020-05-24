<?php 
//include "query_articoli.php";
//include "config.php";
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
  
  
  <?php if ($_GET['sez']==NULL){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione Documenti</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=03&sez=arch_ricevute"><i class="glyphicon glyphicon-eur"></i> Gestione Ricevute di Vendita</a>
		  	<a class="btn btn-default" href="?loc=03&sez=arch_ricevute_carico"><i class="glyphicon glyphicon-shopping-cart"></i> Gestione Ricevute di Carico</a>
		  	<a class="btn btn-default" href="?loc=03&sez=arch_rimborsi"><i class="glyphicon glyphicon-piggy-bank"></i> Gestione Documenti Rimborso</a>
		  	<a class="btn btn-default" href="?loc=03&sez=gestione_resi"><i class="glyphicon glyphicon-retweet"></i> Gestione Resi</a>
	  	 </div>
  <?php } elseif ($_GET['sez']=="gestione_resi"){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione Resi</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="index.php?loc=03"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=03&sez=reso_fornitore"><i class="glyphicon glyphicon-resize-full"></i> Reso a fornitore</a>
	</div><br /><br />
	<?php } elseif ($_GET['sez']=="reso_fornitore"){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Reso a Fornitore</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="index.php?loc=03&sez=gestione_resi"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="index.php?loc=03&sez=nuovo_reso"><i class="glyphicon glyphicon-file"></i> Nuovo reso</a>
		  	<a class="btn btn-default" href="index.php?loc=03&sez=arch_resi_fornitore"><i class="glyphicon glyphicon-th-list"></i> Elenco dei Resi</a>
	</div><br /><br />
	
	<?php }elseif ($_GET['sez']=='nuovo_reso'){ 
  	//controllo per stabilire se aprire una nuova sessione di reso o chiudere quella gia in compilazione
  	$open = query_select_doc_open($con,3);
	if ($open!=0){
		echo "<script> window.location.replace('index.php?loc=03&sez=reso_fornitore_aperto') </script>";
	}
  	?>
  <div class="panel-heading" style="text-align: center;"><b>Nuovo Reso a Fornitore</b></div>
	 	 <div class="panel-body">
	 	 <a class="btn btn-default" href="index.php?loc=03&sez=reso_fornitore"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
	 	 <br /><br />
		  	<!-- Campi del modulo -->
		  	<form action="query_start.php" method="post" onsubmit="return IsEmpty();" >
		  		<input type="hidden" name="sez" value="apri_reso_fornitore" />
		  		<input type="hidden" id="idclientejson" name="id_cliente" value="" />
		  		<table class="table">
		  			<tr>
		  			<td>Cerca Anagrafica Fornitore: &nbsp;<i class="glyphicon glyphicon-user"></i>&nbsp;</td>
		  			<td align="right"><input type="text" id="id_cliente" name="cliente" placeholder="Cerca l'anagrafica" class="form-control" /></td>
		  			
		  			<td>Data di reso: &nbsp;&nbsp;</td>
		  			<td align="right"><input type="text" name="data_doc" class="form-control" value="<?php echo date('Y-m-d');?>" /></td>
		  			
		  			</tr><tr>
		  			<td><button type="submit" id="submit"><i class="glyphicon glyphicon-file"></i> Inizia nuovo reso</button></td>
		  		</tr></table>
			</form>
			<br /><br />
	  	 </div>
		  <br /><br />
		  
<?php } elseif ($_GET['sez']=="reso_fornitore_aperto"){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Reso a Fornitore Compilazione [APERTO]</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<?php
			$campo_doc = query_select_doc_open_details($con,3);
			$cliente = query_select_detail($con, $campo_doc['id_cliente'], 'mercusa_clienti');
			?>
		  	<a class="btn btn-default" href="#" onClick="history.go(-1);return true;"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<br /><br />
		  	<table class="table">
	  	 		<form action="query_start.php?sez=new_art_reso" method="post">
		  			<tr>
		  			<td>Scegli articolo: &nbsp;&nbsp;</td>
		  			<td><input type="text" id="id_articolo" name="id_articolo" placeholder="Cerca articolo" class="form-control" /></td>
		  			<td>&nbsp;
		  			<input type="hidden" name="reso" value="<?php echo $campo_doc['id']; ?>" />
		  			</td>
		  			<td><input type="text" name="art_quantita" placeholder="Quantit&agrave; RESA *default: 1" class="form-control" />
		  			</td>
		  			<td><button type="submit" class="btn-default form-control"><i class="glyphicon glyphicon-plus"></i> Aggiungi</button></td>
		  		</form>
		  	</tr></table>
		  	
		  	<br />
			Lista Articoli RESI al Fornitore: <i class="glyphicon glyphicon-user"></i> <b><?php echo ' '. $cliente['nome'].' '.$cliente['cognome']; ?></b>
			<br />
			<table class="table">
		    <tr><td><b>Codice</b></td><td><b>Nome</b></td><td><b>Data di Carico</b></td><td><b>Quantit&agrave; RESA</b></td><td><b>Lista di Carico</b></td><td><b>Mandante</b></td></tr>
		   <?php 
			   $riga_mov = query_select_list_articoli_resi($con, $campo_doc['id']);
			   echo $riga_mov[0];
		    ?>
		    
		    </table><br /><br />
  		<form action="query_start.php" method="post">
  			<input type="hidden" name="sez" value="stampa_reso_fornitore" />
  			<input type="hidden" name="id_documento" value="<?php echo $campo_doc['id']; ?>" />
  			<table class="table"><tr>
  				<td>
  					<button type="submit" class="btn btn-default" 
  					onClick=window.open("stampa_reso_fornitore.php?id=<?php echo $campo_doc['id']; ?>","ricevuta") 
  					title="Chiude l'operazione corrente e Stampa il Reso"><i class="glyphicon glyphicon-floppy-disk"></i> Salva/Stampa</button>
  				</td>
  				<td align="right">
  					<a class="btn btn-default" title="Annulla definitivamente l'operazione di reso" 
  						href="query_start.php?sez=svuota_reso_fornitore&id=<?php echo $campo_doc['id']; ?>" 
  						onclick="return confirm('Sicuro di voler ANNULLARE? Operazione NON REVERSIBILE');">
  						<i class="glyphicon glyphicon-remove"></i> Annulla e Chiudi
  					</a>
  				</td>
  				</tr>
  			</table>
  		</form>
	</div><br /><br />	
	
	<?php } elseif ($_GET['sez']=="arch_resi_fornitore"){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Archivio Documenti di Reso a Fornitore</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="index.php?loc=03&sez=reso_fornitore"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
	</div>
	
	<!-- Lista delle anagrafiche -->
	<div style="text-align: center; border: 1px #ccc solid;">
		<b>Filtro mensile:</b> 
		<table align="center"><tr>
			<td>
			<form action="index.php?loc=03&sez=arch_resi_fornitore" method="post">
				<select name="mese" style="width: 60px;">
				<?php
				if ($_POST['mese'] == "") {
					$mese = (int)date("m");
				} else {
					$mese = $_POST['mese'];
				}
				for($i=1;$i<13;$i++){
					 if ($i==$mese){
						 echo "<option selected value=\"" . $i . "\">" . $i . "</option>\n";
					 }else{
					 	 echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
					 }
				}
				?>
				</select>
				<select name="anno">
				<?php
					$anno_oggi = date("Y");
					if ($_GET['anno'] == ""){
						$anno = $anno_oggi;
					}else{
						$anno = $_GET['anno'];
					}
					$anno_end = $anno_oggi+1;
					for($i=2014;$i<$anno_end;$i++){
						if ($i==$anno){
							echo "<option name=\"anno\" selected value=\"" . $i . "\">" . $i . "</option>\n";
						}else{
						echo "<option name=\"anno\" value=\"" . $i . "\">" . $i . "</option>\n";
						}
					}
				?>
				</select>
				<button type="submit">Filtra</button>
			</form>
			</td>
			</tr>
		</table>
	</div><br />
		  <table class="table">
		    <tr><td><b># Numero</b></td><td><b>Mandante</b></td><td><b>Data Inserimento</b></td><td><b>Opzioni</b></td><td><b>Generato da</b></td></tr>
		    <?php 
		    if ($_POST['mese'] == ""){
		    	    $data_inizio = date("mY");
		    } else{
		    	    $data_inizio = $_POST['mese'].$_POST['anno'];
		    }
		    echo query_select_resi_fornitore_list($con,$data_inizio); ?>
  		</table>
		  <br /><br />

	<?php } elseif ($_GET['sez']=="arch_rimborsi"){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Archivio Documenti di Rimborso</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="index.php?loc=03"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=04&sez=cont_rimborsi"><i class="glyphicon glyphicon-plus"></i> Nuovo Rimborso</a>
	</div>
	
	<!-- Lista delle anagrafiche -->
	<div style="text-align: center; border: 1px #ccc solid;">
		<b>Filtro mensile:</b> 
		<table align="center"><tr>
			<td>
			<form action="index.php?loc=03&sez=arch_rimborsi" method="post">
				<select name="mese" style="width: 60px;">
				<?php
				if ($_POST['mese'] == "") {
					$mese = (int)date("m");
				} else {
					$mese = $_POST['mese'];
				}
				for($i=1;$i<13;$i++){
					 if ($i==$mese){
						 echo "<option selected value=\"" . $i . "\">" . $i . "</option>\n";
					 }else{
					 	 echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
					 }
				}
				?>
				</select>
				<select name="anno">
				<?php
					$anno_oggi = date("Y");
					if ($_GET['anno'] == ""){
						$anno = $anno_oggi;
					}else{
						$anno = $_GET['anno'];
					}
					$anno_end = $anno_oggi+1;
					for($i=2014;$i<$anno_end;$i++){
						if ($i==$anno){
							echo "<option name=\"anno\" selected value=\"" . $i . "\">" . $i . "</option>\n";
						}else{
						echo "<option name=\"anno\" value=\"" . $i . "\">" . $i . "</option>\n";
						}
					}
				?>
				</select>
				<button type="submit">Filtra</button>
			</form>
			</td>
			</tr>
		</table>
	</div><br />
		  <table class="table">
		    <tr><td><b># Numero</b></td><td><b>Mandante</b></td><td><b>Data Inserimento</b></td><td><b>Opzioni</b></td><td><b>Generato da</b></td></tr>
		    <?php 
		    if ($_POST['mese'] == ""){
		    	    $data_inizio = date("mY");
		    } else{
		    	    $data_inizio = $_POST['mese'].$_POST['anno'];
		    }
		    echo query_select_doc_rimborso_list($con,$data_inizio); ?>
  		</table>
		  <br /><br />
		  
  <?php } elseif ($_GET['sez']=="arch_ricevute"){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione Ricevute di Vendita</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=03"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=00&sez=vend_dett"><i class="glyphicon glyphicon-plus"></i> Nuova Vendita</a>
	</div>
	
	<!-- Lista delle anagrafiche -->
	<div style="text-align: center; border: 1px #ccc solid;">
		<b>Filtro mensile:</b> 
		<table align="center"><tr>
			<td>
			<form action="index.php?loc=03&sez=arch_ricevute" method="post">
				<select name="mese" style="width: 60px;">
				<?php
				if ($_POST['mese'] == "") {
					$mese = (int)date("m");
				} else {
					$mese = $_POST['mese'];
				}
				for($i=1;$i<13;$i++){
					 if ($i==$mese){
						 echo "<option selected value=\"" . $i . "\">" . $i . "</option>\n";
					 }else{
					 	 echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
					 }
				}
				?>
				</select>
				<select name="anno">
				<?php
					$anno_oggi = date("Y");
					if ($_GET['anno'] == ""){
						$anno = $anno_oggi;
					}else{
						$anno = $_GET['anno'];
					}
					$anno_end = $anno_oggi+1;
					for($i=2014;$i<$anno_end;$i++){
						if ($i==$anno){
							echo "<option name=\"anno\" selected value=\"" . $i . "\">" . $i . "</option>\n";
						}else{
						echo "<option name=\"anno\" value=\"" . $i . "\">" . $i . "</option>\n";
						}
					}
				?>
				</select>
				<button type="submit">Filtra</button>
			</form>
			</td>
			</tr>
		</table>
	</div><br />
		  <table class="table">
		    <tr><td><b># Numero</b></td><td><b>Importo</b></td><td><b>Data Inserimento</b></td><td><b>Opzioni</b></td><td><b>Generato da</b></td></tr>
		    <?php 
		    if ($_POST['mese'] == ""){
		    	    $data_inizio = date("mY");
		    } else{
		    	    $data_inizio = $_POST['mese'].$_POST['anno'];
		    }
		    echo query_select_ricevute_list($con,$data_inizio); ?>
  		</table><br /><br />
		  
  <?php } elseif ($_GET['sez']=="arch_ricevute_carico"){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione Ricevute di Carico</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=03"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=02&sez=carico_multiplo"><i class="glyphicon glyphicon-plus"></i> Nuovo Carico</a>
	</div>
	
	<!-- Lista delle anagrafiche -->
	<div style="text-align: center; border: 1px #ccc solid;">
		<b>Filtro mensile:</b> 
		<table align="center"><tr>
			<td>
			<form action="index.php?loc=03&sez=arch_ricevute_carico" method="post">
				<select name="mese" style="width: 60px;">
				<?php
				if ($_POST['mese'] == "") {
					$mese = (int)date("m");
				} else {
					$mese = $_POST['mese'];
				}
				for($i=1;$i<13;$i++){
					 if ($i==$mese){
						 echo "<option selected value=\"" . $i . "\">" . $i . "</option>\n";
					 }else{
					 	 echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
					 }
				}
				?>
				</select>
				<select name="anno">
				<?php
					$anno_oggi = date("Y");
					if ($_GET['anno'] == ""){
						$anno = $anno_oggi;
					}else{
						$anno = $_GET['anno'];
					}
					$anno_end = $anno_oggi+1;
					for($i=2014;$i<$anno_end;$i++){
						if ($i==$anno){
							echo "<option name=\"anno\" selected value=\"" . $i . "\">" . $i . "</option>\n";
						}else{
						echo "<option name=\"anno\" value=\"" . $i . "\">" . $i . "</option>\n";
						}
					}
				?>
				</select>
				<button type="submit">Filtra</button>
			</form>
			</td>
			</tr>
		</table>
	</div><br />
		  <table class="table">
		    <tr><td><b># Numero</b></td><td><b>Mandante</b></td><td><b>Importo</b></td><td><b>Data Inserimento</b></td><td><b>Opzioni</b></td><td><b>Generato da</b></td></tr>
		    <?php 
		    if ($_POST['mese'] == ""){
		    	    $data_inizio = date("mY");
		    } else{
		    	    $data_inizio = $_POST['mese'].$_POST['anno'];
		    }
		    
		    echo query_select_ricevute_carico_list($con,$data_inizio); ?>
  		</table><br /><br />
		  
  <?php } ?>