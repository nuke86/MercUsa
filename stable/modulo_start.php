<?php 
//include "query_clienti.php"; 
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
  	<div class="panel-heading" style="text-align: center;"><b>Inizio attivit&agrave; [DESKTOP]</b> <div style="float: right;"><a style="font-size: 14px;" class="label label-success" href="index.php?loc=00"><i class="glyphicon glyphicon-refresh"></i> Aggiorna dati</a></div></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<table width="100%"><tr><td style="vertical-align: top;">
		  	<a class="btn btn-default" href="?loc=00&sez=vend_dett"><i class="glyphicon glyphicon-euro"></i> Nuova vendita</a>
		  	<a class="btn btn-default" href="?loc=02&sez=carico_multiplo"><i class="glyphicon glyphicon-plus"></i> Carica Articolo/i</a>
		  	<a class="btn btn-default" href="?loc=04&sez=clienti_rimborsabili"><i class="glyphicon glyphicon-gbp"></i> Clienti da Rimborsare</a>
		  	<a class="btn btn-default" href="?loc=00&sez=da_scontare"><i class="glyphicon glyphicon-time"></i> Riepilogo Sconti</a><br /><br />
		  	<a class="btn btn-default" href="?loc=03&sez=arch_rimborsi"><i class="glyphicon glyphicon-piggy-bank"></i> Archivio Rimborsi</a>
		  	<?php if ($configModuloPunti=='yes'){ ?>
		  	<a class="btn btn-default" href="stampa_tessera.php" target="punti" title="Fare riferimento all'articolo codice 2129 per ogni modifica"><i class="glyphicon glyphicon-print"></i> Genera Tessera Punti</a>
		  	<?php } ?>
		  	</td>
		  	<td align="right">
		  	<div style="width: 300px;">
		  		<b>MUSICA PER IL TUO NEGOZIO</b><br />
		  		<ul>
		  		<li><a href="javascript:;" onClick="
		  		window.open('http://95.110.226.234/mercusa/stable/plugin_radio.php', 'La tua Musica', 'width=250, height=350, resizable, status, scrollbars=1, location');">
		  		Live365-Radio</a> - Un servizio gratuito,
		  		che ti permette di diffondere la tua musica preferita nel negozio.</li>
		  		</ul>
		  	</div>
		  	</td>
		  	</tr></table>
		  	<table><tr><td width="500px">
				<?php include('table_situazione_cassa.php'); ?>
				</td><td width="500px" align="right">
				<?php 
				$data_now = date("Y-m-d");
				//$data_now = "2015-06-17";
				$data_prom = date("Y-m-d", strtotime($data_now. ' + 1 days')); 
				$num_prom = query_select_prom_bydate($con, "$data_prom");
				
				if ($num_prom != 0){
				?>
				<table class="table">
					<tr><td align="center" colspan="4"><b>CI SONO PROMEMORIA!</b></td></tr>
					<?php
					echo query_select_prom_bydate($con, "$data_prom"); 
					?>
				</table>
				<?php } 
				$data_nascita = date("m-d");
				//$data_nascita = "02-13";
				$num_comple = query_selectCompleanni($con, "$data_nascita");
				
				if ($num_comple != "0"){ ?>
				<table class="table">
					<tr><td align="center" colspan="4"><b>CI SONO COMPLEANNI!</b></td></tr>
					<?php
					echo query_selectCompleanni($con, "$data_nascita"); 
					?>
				</table>
				<?php } ?>
				
		  	</td><td><br /><div class="alert alert-info">
		  		<p style="font-size: smaller; text-align: center;">Controlla se tutti i documenti sono stati chiusi/salvati correttamente</p>
		  		<hr style="height: 5px; margin: 0 0 0 0; padding: 0 0 0 0; border-top: double;" />
		  		<table><tr>
		  		<td align="center"><b>Verifica documenti</b></td><td align="right">Stato</td></tr>
		  		<tr><td><a href="index.php?loc=00&sez=vend_dett">Ricevute di vendita:</a></td><td align="right"><?php echo statusDocsOpen($con, 2); ?></td></tr>
		  		<tr><td><a href="index.php?loc=02&sez=carico_multiplo">Ricevute di carico:</a></td><td align="right"><?php echo statusDocsOpen($con, 1); ?></td></tr>
		  		<tr><td><a href="index.php?loc=03&sez=nuovo_reso">Documenti di reso:</a></td><td align="right"><?php echo statusDocsOpen($con, 3); ?></td></tr>
		  		<tr><td><a href="index.php?loc=04&sez=cont_rimborsi">Documenti di rimborso:</a></td><td align="right"><?php echo statusDocsOpen($con, 4); ?></td></tr>
		  		</table></div>
		  		</td></tr></table>
	  	 </div>
		  
		  
  <?php } elseif ($_GET['sez']=='vend_dett'){ 
  	//controllo per stabilire se aprire nuova ricevuta o chiudere quella gia in compilazione
  	$open = query_select_doc_open($con,2);
	if ($open!=0){
		echo "<script> window.location.replace('index.php?loc=00&sez=vend_dett01') </script>";
	}
  	?>
  <div class="panel-heading" style="text-align: center;"><b>Vendita di Cassa (cessione)</b></div>
	 	 <div class="panel-body">
		  	<!-- Campi del modulo -->
		  	<form action="query_start.php" method="post">
		  		<input type="hidden" name="sez" value="apri_ric" />
		  		<input type="hidden" id="idclientejson" name="id_cliente" value="" />
		  		<table class="table">
		  			<tr>
		  			<td align="right"><b>Intestazione cliente:</b> </td>
		  			<td><input type="text" id="id_cliente" name="cliente" class="form-control" placeholder="Nome/Cognome anagrafica" /></td>
		  			<td align="right"><b>Data ricevuta:</b> </td>
		  			<td align="right"><input type="text" name="data_doc" class="form-control" id="datepicker" value="<?php echo date('Y-m-d');?>" /></td>
		  			
		  			</tr><tr>
		  			<td colspan="4" align="center"><button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-file"></i> Inizia nuova Ricevuta</button></td>
		  		</tr></table>
			</form>
			<?php include('table_situazione_cassa.php'); ?>
	  	 </div>
	  	 <br />
	  	 <div class="alert alert-info" role="alert">
	  	 <b>HELP:</b> <br />
	  	 1) contrariamente a quanto avviene nel caso dei <i>documenti di carico</i> (acquisti), per la vendita 
	  	 (cessione) non ha importanza separare ricevute di vendita per articoli di CONTO VENDITA da quelle per 
	  	 articoli di CONTO TERZI. Infatti in contabilit&agrave; il movimento di cessione &egrave; UNICO e non effettua 
	  	 alcuna discriminazione tra i due differenti tipi di carico. Quindi in una stessa ricevuta di vendita possono essere
	  	 contenuti articoli di CV cos&igrave; come di CT.<br />
	  	 2) Il nome che viene richiesto in questa schermata appena prima di aprire la ricevuta di vendita &egrave; fortemente 
	  	 facoltativo, infatti le ricevute di vendita normalmente sono anonime, ma se dovesse esser necesario farne una intestata,
	  	 MercUsa &egrave; abilitato a questa scelta, semplicemente specificando qui il nome del cliente che sta richiedendo tale 
	  	 ricevuta, che a quel punto pu&ograve; essere a tutti gli effetti equiparata a una <i>fattura di vendita</i>.
	  	 </div>
	  	 <br />
	  	 </div>
  <?php } elseif ($_GET['sez']=='da_scontare'){ ?>
  <div class="panel-heading" style="text-align: center;"><b>Articoli da scontare</b></div>
	 	 <div class="panel-body">
	 	 <a class="btn btn-default" href="index.php"><i class="glyphicon glyphicon-hand-left"></i> Torna Indietro</a>
	 	 <p align="center">
	 	 <b>Qui trovi gli articoli che non sono stati ancora venduti e sono prossimi o sono gi&agrave; scontabili, 
	 	 secondo le regole del mandato di vendita</b><br /><br />
	 	 </p>
	 	 <p>Filtra gli articoli su una data specifica:<br />
	 	 	<form method="post" action="query_articoli.php?sez=filtra_sconto">
	 	 		<table><tr><td><select name="giorno" style="width: 65px;">
						<?php
							$giorno_oggi = date("d");
							if ($_GET['giorno'] == ""){
								$giorno = $giorno_oggi;
							}else{
								$giorno = $_GET['giorno'];
							}
						for($i=1;$i<32;$i++){
							if ($i==$giorno) {
								echo "<option selected value=\"" . $i . "\">" . $i . "</option>\n";
							} else {
							echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
							}
						}
						?>
					</select> 
				</td><td>/
					<select name="mese" style="width: 60px;">
						<?php
							$mese_oggi = date("m");
							if ($_GET['mese'] == ""){
								$mese = $mese_oggi;
							}else{
								$mese = $_GET['mese'];
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
				</td><td> /
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
				</td>
				<td> - <select name="tipo">
				<?php if (($_GET['tipo']=='60')OR($_GET['tipo']=='')){ ?>
				<option value="60" selected>SCONTO 60</option>
				<option value="90">SCONTO 90</option>
				<?php }elseif ($_GET['tipo']=='90'){ ?>
				<option value="60">SCONTO 60</option>
				<option value="90" selected>SCONTO 90</option>
				<?php } ?>
				</select></td>
				<td><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-filter"></i> Filtra</button></td>
				</tr></table>
	 	 		
	 	 	</form>
	 	 </p>
			 <table class="table"><tr>
			 <td><b># Codice</b></td><td><b>Nome Articolo</b></td><td><b>Descrizione</b></td><td><b>Mandante</b></td><td><b>Quantita</b></td></tr>
			 <?php 
			 	if ($_GET['data']==""){
			 		$data_filtro = date("Y-m-d");
			 	} else {
			 		$data_filtro = $_GET['data'];
			 	}
			 	if ($_GET['tipo']==""){
			 		$tipo = '60';
			 	} else {
			 		$tipo = $_GET['tipo'];
			 	}
			 	echo query_select_articoli_scaduti($con,$data_filtro,$tipo); 
			 ?>
			 
			 </table>
	 	 </div>
	 	 <br /><br />
  <?php } elseif ($_GET['sez']=='vend_dett01'){ ?>
  <div class="panel-heading" style="text-align: center;"><b>Compilazione Ricevuta di Vendita [APERTA]</b></div>
	 	 <div class="panel-body">
	 	 
	 	 	<?php
			$campo_doc = query_select_doc_open_details($con,2);
			$cliente = query_select_detail($con, $campo_doc['id_cliente'], 'mercusa_clienti');
			?>
		  	<!-- Campi del modulo -->
		  	<form action="query_start.php" method="post">
		  		<input type="hidden" name="sez" value="aggiungi_articolo" />
		  		<input type="hidden" name="id_documento" value="<?php echo $campo_doc['id']; ?>" />

		  		<table >
		  			<tr>
		  			<td>Seleziona il Codice Articolo: &nbsp;&nbsp;</td>
		  			<td><input type="text" id="id_articolo" name="id_articolo" placeholder="Cerca l'articolo" class="form-control" /></td>
		  			<td>&nbsp;</td>
		  			<td><button type="submit" class="btn-default form-control"><i class="glyphicon glyphicon-file"></i> Aggiungi articolo</button></td>
		  			
		  		</tr></table>
			</form>
			<?php if ($endofurl == "ilripostiglio") { ?>
			<br />* Per inserire lo sconto Omaggio di Tessera Punti inserire sempre il codice articolo N.: <i>2129</i>
			<?php } ?>
			<br />
			Dettaglio della Ricevuta di vendita del cliente: <b><?php echo ' '. $cliente['nome'].' '.$cliente['cognome']; ?></b>
			<br />
			<table class="table">
		    <tr><td><b>Codice</b></td>
		    <td><b>Nome</b></td>
		    <td><b>Imponibile</b></td>
		    <td><b>IVA</b></td>
		    <td><b>Prezzo+IVA</b></td>
		    <td><b>Tipo Mandato</b></td>
		    <?php if ($cod != 'aldo') { ?>
		    	    <td><b>Sconti</b></td>
		    <?php } ?>
		    <td><b>Opzioni</b></td>
		    </tr>
		   <?php 
			   $riga_mov = query_select_list_mov($con, $campo_doc['id']);
			   echo $riga_mov[0];
		    ?>
		    <tr><td><b>Totale:</b> <?php echo $riga_mov[1]; ?> Euro</td></tr>
  		</table><br /><br />
  		<form action="query_start.php" method="post">
  			<input type="hidden" name="sez" value="stampa_ricevuta" />
  			<input type="hidden" name="id_documento" value="<?php echo $campo_doc['id']; ?>" />
  			<table><tr>
  				<td>
  					<button type="submit" class="btn btn-default" onClick=window.open("stampa_ricevuta.php?id=<?php echo $campo_doc['id']; ?>","ricevuta") title="Stampa e Conferma l'operazione corrente"><i class="glyphicon glyphicon-print"></i> Stampa ricevuta</button>
  				</td>
  				<td>&nbsp;</td>
  				<td><a class="form-control" title="Annulla definitivamente l'operazione corrente" href="query_start.php?sez=svuota&id=<?php echo $campo_doc['id']; ?>"><i class="glyphicon glyphicon-remove-circle"></i> Annulla e Chiudi</a></td>
  				<td>&nbsp;</td>
  				<td>&nbsp;</td>
  				<form name="resto">
  					<input type="hidden" id="tot" name="tot" value="<?php echo $riga_mov[1]; ?>" />
  				<td><input type="text" name="banconote" placeholder="Totale Soldi" onkeyup="update()" id="banconote" size="10" /></td>
  				<td> Resto: <input type="text" id="resto" readonly="yes" size="6" /> Euro</td>
				</form>
  				</tr>
  			</table>
  		</form>
  		
	  	 </div>
		  <br /><br />
  <?php } ?>
  
  
</div>
