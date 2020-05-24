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
  <!-- cerca per rimborsi -->
  
<?php if ($_GET['sez']=='cont_rimborsi'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione rimborsi</b></div>
	 	 <div class="panel-body">
	 	 <a class="btn btn-default" href="?loc=04"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a><br />
		  	<!-- Strumenti principali -->
		  	Cerca il fornitore del quale vuoi controllare la situazione dei rimborsi (<b>articoli gi&agrave; venduti non ancora rimborsati</b>)<br /><br />
		  <form action="query_start.php" method="post">
		  <input type="hidden" id="idclientejson" name="id_cliente" value="" />
		  	<input type="hidden" name="sez" value="scheda_rimborso" />
			  	<table>
			  		<tr>
			  			<td><input type="text" name="cliente" id="id_cliente" class="form-control" placeholder="Cerca per Cognome" /></td>
			  			<td><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Cerca</button></td>
			  		</tr>
			  	</table>
		  </form>
	  	 </div>
		  <!-- Cerca per movimenti -->
		 <br /><br />
<?php }elseif ($_GET['sez']=='lista_movimenti'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Lista Movimenti</b></div>
	 	 <div class="panel-body">
	 	 <a class="btn btn-default" href="?loc=04"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a><br />
		  	<!-- Strumenti principali -->
		  	Cerca il fornitore del quale vuoi controllare la lista movimenti (<b>articoli portati in negozio ed eventuali vendite effettuate</b>)<br /><br />
		  <form action="query_start.php" method="post">
		  <input type="hidden" id="idclientejson" name="id_cliente" value="" />
		  	<input type="hidden" name="sez" value="scheda_movimenti" />
			  	<table>
			  		<tr>
			  			<td><input type="text" name="cliente" id="id_cliente" class="form-control" placeholder="Cerca per Cognome" /></td>
			  			<td><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Cerca</button></td>
			  		</tr>
			  	</table>
		  </form>
	  	 </div>
		  <!-- Lista dei rimborsi -->
		 <br /><br />
<?php } elseif ($_GET['sez']=='cont_rimborsi_cliente'){ 
	$dettaglio_cliente = query_select_detail($con, $_GET['id_cliente'],'mercusa_clienti');
	?>
	<div class="panel-heading" style="text-align: center;"><b>Scheda del cliente [RIMBORSI]</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04&sez=cont_rimborsi"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=04&sez=clienti_rimborsabili">Vai a Clienti da Rimborsare</a>
		  	<a class="btn btn-default" href="stampa_rimborso.php?id_cliente=<?php echo $_GET['id_cliente']; ?>" target="rimborso" onclick="return confirm('Ricorda che questo documento va stampato in DUPLICE COPIA!!');"><i class="glyphicon glyphicon-print"></i> Esegui Rimborso</a>
		  	<a class="btn btn-default" href="?loc=03&sez=arch_rimborsi" target="rimborso" ><i class="glyphicon glyphicon-pig"></i> Gestione Doc Rimborsi</a>
		  	
		  	<br /><br />
		  	Situazione rimborsi (articoli gi&agrave; venduti ma non ancora rimborsati) di <b><?php echo $dettaglio_cliente[2]." ".$dettaglio_cliente[1]; ?></b>: <br /><br />
			  	<table class="table">
			  		<tr><td><b># Codice Articolo</b></td><td><b>Nome</b></td><td><b>Data di Vendita</b></td><td><b>Prezzo</b></td><td><b>Importo rimborso</b></td><td><b>Sconto %</b></td><td><b>Rimborso scontato</b></td><td><b>Rimborsabile da</b></td></tr>
					<?php echo query_rimborsi_cliente($con, $_GET['id_cliente'],0); ?>
			  	</table>
			  	
		  </form>
	  	 </div>
		  <!-- Lista dei movimenti -->
		 <br /><br />

<?php } elseif ($_GET['sez']=='scheda_movimenti'){ 
	$dettaglio_cliente = query_select_detail($con, $_GET['id_cliente'],'mercusa_clienti');
	?>
	<div class="panel-heading" style="text-align: center;"><b>Scheda del cliente [MOVIMENTI]</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04&sez=lista_movimenti"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="stampa_movimenti.php?id_cliente=<?php echo $_GET['id_cliente']; ?>" target="movimenti"><i class="glyphicon glyphicon-print"></i> Esegui Stampa</a>
		  	<a class="btn btn-default" href="?loc=04&sez=scheda_giacenze&id_cliente=<?php echo $_GET['id_cliente']; ?>"><i class="glyphicon glyphicon-gift"></i> Vedi Giacenze</a>
		  	<br /><br />
		  	Situazione movimenti (articoli portati in negozio ed eventuali vendite) di <b><?php echo $dettaglio_cliente[2]." ".$dettaglio_cliente[1]; ?></b>: <br /><br />
			  	<table class="table">
			  		<tr><td><b># Codice Articolo</b></td>
			  		<td><b>Nome</b></td>
			  		<td><b>Quantit&agrave;</b></td>
			  		<td><b>Prezzo di Carico</b></td>
			  		<td><b>Rimborso</b></td>
			  		<td><b>% Sconto</b></td>
			  		<td><b>Rimborso scontato</b></td>
			  		<td><b>Data di Carico</b></td>
			  		<td><b>Data di Vendita</b></td>
			  		<td><b>Rimborsato</b></td>
			  		<td><b>Reso il</b></td>
			  		</tr>
					<?php echo query_lista_movimenti_cliente($con, $_GET['id_cliente'],0); ?>
			  	</table>
		  </form>
	  	 </div>
		 <br /><br />

<?php } elseif ($_GET['sez']=='scheda_giacenze'){ 
	$dettaglio_cliente = query_select_detail($con, $_GET['id_cliente'],'mercusa_clienti');
	?>
	<div class="panel-heading" style="text-align: center;"><b>Scheda del cliente [GIACENZE]</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04&sez=lista_movimenti"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="stampa_movimenti.php?id_cliente=<?php echo $_GET['id_cliente']; ?>" target="movimenti"><i class="glyphicon glyphicon-print"></i> Esegui Stampa</a>
		  	<br /><br />
		  	Situazione giacenze (articoli presenti in negozio) di <b><?php echo $dettaglio_cliente[2]." ".$dettaglio_cliente[1]; ?></b>: <br /><br />
			  	<table class="table">
			  		<tr><td><b># Codice Articolo</b></td>
			  		<td><b>Nome</b></td>
			  		<td><b>Quantit&agrave;</b></td>
			  		<td><b>Prezzo di Carico</b></td>
			  		<td><b>Rimborso</b></td>
			  		<td><b>Data di Carico</b></td>
			  		</tr>
					<?php echo query_lista_giacenze_cliente($con, $_GET['id_cliente']); ?>
			  	</table>
		  </form>
	  	 </div>
		 <br /><br />
		 
<?php } elseif ($_GET['sez']=='clienti_rimborsabili'){ 	?>
	<div class="panel-heading" style="text-align: center;"><b>Clienti da Rimborsare</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<br /><br />
		  	Elenco dei clienti che nel mese scelto percepiranno rimborsi <br /><br />
		  	Anno: 
		  	<select name="anno" onchange="window.location.href='index.php?loc=04&sez=clienti_rimborsabili&anno='+this.value">
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
		  	Mesi: <?php
		  			if ($_GET['mese_input']==""){
						$mese_input = date("m");
					}else{
						$mese_input = $_GET['mese_input'];
					}
					
						for($i=1;$i<13;$i++){
							if ($i==$mese_input){
							  echo "<a style=\"color: red;\" href=\"index.php?loc=04&sez=clienti_rimborsabili&anno=$anno&mese_input=" . $i . "\">" . $i . "</a> ";
							}else{
							  echo "<a href=\"index.php?loc=04&sez=clienti_rimborsabili&anno=$anno&mese_input=" . $i . "\">" . $i . "</a> ";
							}
						}
						?>
		  	<br />
			  	<table class="table">
			  		<tr><td><b>Cliente</b></td><td><b>Importo rimborso</b></td><td><b>Email</b></td><td><b>Telefono</b></td><td></td></tr>
					<?php 
					if ($_GET['mese_input']==""){
						$mese_input = date("m");
					}else{
						$mese_input = $_GET['mese_input'];
					}
					$rimborsi = query_clienti_rimborsabili($con,$mese_input,$anno);
					echo $rimborsi[0];
					?>
			  	</table>
			  	Hai un totale di rimborsi di euro: <b><?php echo $rimborsi[1]; ?></b>
	  	 </div>
	  	 
		  <!-- Start -->
		 <br /><br />
<?php } elseif ($_GET['sez']=='calcolo_percentuale'){
	
	?>
  	<div class="panel-heading" style="text-align: center;"><b>Calcolo Percentuale di Rimborso</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="#" onClick="history.go(-1);return true;">Torna indietro</a>
		  	<br /><br />
		  	<form action="index.php?loc=04&sez=calcolo_percentuale" method="post">
		  	1) Quanto vuole guardagnare il cliente?<br />
		  	<input type="text" name="rimborso" /><br /><br />
		  	2) Che prezzo di vendita vorresti avesse l'articolo?<br />
		  	<input type="text" name="prezzo" /><br /><br />
		  	<input type="submit" class="btn btn-default" value="Calcola" />
		  	</form>
		  	<br /><br />
		  	<?php
		  	if (($_POST['rimborso'] != "") AND ($_POST['prezzo'] != "")){
		  		$perc_result = ($_POST['rimborso']*100)/$_POST['prezzo'];
		  		$perc_negozio = 100-$perc_result;
		  		$euro_negozio = $_POST['prezzo']-$_POST['rimborso'];
		  	?>
		  	<b>Risultati:</b><br />
		  	viene calcolato che al cliente spetta la percentuale di rimborso pari a <b><?php echo number_format($perc_result,2,',','.'); ?></b> % da indicare nel suo mandato di vendita e nell'anagrafica<br />
		  	rimane dunque il <b><?php echo number_format($perc_negozio,2,',','.'); ?></b> % come percentuale per il negozio pari a Euro <b><?php echo $euro_negozio; ?></b>.
		  	<?php } ?>
	  	 </div>
		 
	  	 <br /><br />
	  	 
	  	 <?php } elseif ($_GET['sez']=='gestione_iva'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione I.V.A. e documenti fiscali</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=04&sez=nuova_fattura_acquisto" onclick="return confirm('ATTENZIONE: questa funzione &egrave; ancora in fase di sviluppo, pu&ograve; non funzionare correttamente.');"><i class="glyphicon glyphicon-list-alt"></i> Gestione Fatture d'acquisto</a>
		  	<br /><br />
			
		  	<form action="?loc=04&sez=gestione_iva_post" method="post">
		  	<center>
		  	<table width="300px">
		  	<tr><td>
		  	<b>Da:</b><br />
		  	<input type="text" class="form-control" name="data_from" value="<?php echo date("Y-m-d"); ?>" id="datepicker" /></td>
		  	<td>
		  	<b>A:</b><br />
		  	<input type="text" class="form-control" name="data_to" value="<?php echo date("Y-m-d"); ?>" id="datepicker1"/></td>
		  	</tr>
		  	<tr><td colspan="2" align="right"><br />
		  	<input type="submit" value="Genera documenti" />
		  	</td>
		  	</tr>
		  	</table>
		  	</center>
		  	</form><br /><br />
		  	<div class="alert alert-info" role="alert">
		  		<b>HELP:</b> la gestione IVA comprende una generazione completa delle stampe fiscali
				obbligatorie per legge: Stampa registro Acquisti, Stampa registro Cessioni, Stampa Liquidazione IVA. <br />
				Tutti i documenti fiscali vengono generati sulla base del Regime del Margine, obbligatorio per chi effettua transazioni di beni usati.<br />
				1) Per quanto riguarda la registrazione delle fatture di acquisto, si pu&ograve; premere il pulsante qua in alto, di modo che la liquidazione IVA sia comprensiva anche di tutti i movimenti che non rientrano nel regime del margine.<br />
				2) L'operazione subito seguente pu&ograve; essere la generazione della liquidazione IVA con relativa stampa (o PDF).<br />
				3) Inserire qui le date del periodo relativamente al quale si devono generare i documenti fiscali.<br />
				4) Si possono generare un numero infinito di volte tutti i documenti fiscali, mentre invece i salvataggi delle liquidazioni IVA sono limitati a quelli trimestrali obbligatori per legge.<br />
				5) La stampa del Registro del Margine pu&ograve; essere utilizzata (se stampata sui fogli vidimati antecedentemente dal Comune di esercizio) come stampa del Giornale degli Affari, obbligatorio per tutte le attivit&agrave; di commercio di beni usati.
				
			</div>
		 </div>
	</div>
		  	<br /><br />
<?php } elseif ($_GET['sez']=='gestione_iva_post'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione I.V.A. Registro Margine</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04&sez=gestione_iva"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="stampa_pdf_liquidazione.php?data_from=<?php echo $_POST['data_from']; ?>&data_to=<?php echo $_POST['data_to']; ?>" target="pdf">
		  	<i class="glyphicon glyphicon-save"></i> Esporta PDF</a>
		  	<a class="btn btn-default" href="stampa_pdf_liquidazione.php?data_from=<?php echo $_POST['data_from']; ?>&data_to=<?php echo $_POST['data_to']; ?>&affari=yes" target="pdf">
		  	<i class="glyphicon glyphicon-book"></i> Giornale degli Affari</a>
		  	<a class="btn btn-default" href="stampa_pdf_registro_vendite.php?data_from=<?php echo $_POST['data_from']; ?>&data_to=<?php echo $_POST['data_to']; ?>" target="pdf">
		  	<i class="glyphicon glyphicon-euro"></i> Registro Vendite</a>
		  	<a class="btn btn-default" href="stampa_pdf_registro_acquisti.php?data_from=<?php echo $_POST['data_from']; ?>&data_to=<?php echo $_POST['data_to']; ?>" target="pdf">
		  	<i class="glyphicon glyphicon-shopping-cart"></i> Registro Acquisti</a>
		  	<a class="btn btn-default" href="#anchor"><i class="glyphicon glyphicon-hand-down"></i> RIEPILOGO</a>

		  	<br /><br />
		  <b>REGISTRO DEL MARGINE</b><br />
		  <i><?php echo $nome_azienda; ?></i><br />
		  P.IVA: <i><?php echo $pivaAzienda; ?></i> C.F.: <i><?php echo $cfAzienda; ?></i><br />
		  Indirizzo: <i><?php echo $indirizzo_azienda; ?></i><br /><br />
		  <b><u>ANNO:</u>  <u><?php echo date("Y", strtotime($_POST['data_from'])); ?></u></b><br />
		  <b>Periodo da: <i><?php echo date("d/m/Y", strtotime($_POST['data_from'])); ?></i> a: <i><?php echo date("d/m/Y", strtotime($_POST['data_to'])); ?></i></b>
		  <br />
		 <?php echo query_liquidazione_iva_periodo($con, $_POST['data_from'],$_POST['data_to']); ?>
		 <br /><br />
	  	 </div>
		 <br /><br />
		 
<?php } elseif ($_GET['sez']=='nuova_fattura_acquisto'){ 
	//controllo per stabilire se aprire nuova fattura o chiudere quella gia in compilazione
  	$open = query_select_doc_open($con,5);
	if ($open!=0){
		echo "<script> window.location.replace('index.php?loc=04&sez=nuova_fattura_acquisto01') </script>";
	}
	?>
  	<div class="panel-heading" style="text-align: center;"><b>Registrazione nuova Fattura d'Acquisto</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04&sez=gestione_iva"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<br /><br />
		 <form action="query_start.php" method="post">
			<input type="hidden" name="sez" value="apri_fatt_acqu" />
			<input type="hidden" id="idclientejson" name="id_cliente" value="" />
			<table class="table">
				<tr>
				<td align="right">Anagrafica Fornitore: &nbsp;&nbsp; <a class="btn btn-default" title="Crea nuova anagrafica" href="?loc=01&sez=cl_new&fatt=1"><i class="glyphicon glyphicon-plus"></i></a></td>
				<td><input type="text" id="id_cliente" name="cliente" class="form-control" placeholder="Ragione sociale Anagrafica" required /></td>
				<td align="right">Data fattura: &nbsp;&nbsp;</td>
				<td><input type="text" name="data_doc" class="form-control" value="<?php echo date('Y-m-d');?>" id="datepicker" /></td>
				</tr>
				<tr><td align="right">Numero di fattura: &nbsp;&nbsp;</td>
				<td><input type="text" name="num_doc" class="form-control" placeholder="Il numero come riportato in fattura" required /></td>
				
				</tr><tr>
				<td colspan="4" align="center"><button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-file"></i> Crea nuova Fattura</button></td>
			</tr></table>
		</form>
		 
	  	 </div>
		 <br /><br />
		 
<?php } elseif ($_GET['sez']=='nuova_fattura_acquisto01'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Compilazione Fattura d'Acquisto [APERTA]</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<?php
			$campo_doc = query_select_doc_open_details($con,5);
			$fornitore = query_select_detail($con, $campo_doc['id_cliente'], 'mercusa_clienti');
			$campi_testata = selectTestataFatturaDettaglio($con,$campo_doc['id']);
			?>
		  	<a class="btn btn-default" href="?loc=04&sez=gestione_iva"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<br /><br />
		  	<form action="query_start.php" method="post">
		  	<table width="500px"><tr><td>
		  	<b>Totale Fattura:</b><br />
		  	<input type="text" name="tot_fatt" placeholder="Totale &euro;" value="<?php echo $campi_testata['totale']; ?>" style="border: 1px #000 solid; padding: 3px;" />
		  	</td><td align="right">
		  	<b>Pagamento:</b><br />
		  	<?php echo menuPagamentiFattura($con, $campo_doc['id']); ?>
		  	</td></tr></table>
		
		<input type="hidden" name="id_doc" value="<?php echo $campo_doc['id']; ?>" />
		<br /><p align="right">Dettaglio FATTURA n: <input type="text" name="num_doc" value="<?php echo $campo_doc['num_doc']; ?>" /> 
			del: <input type="data" name="data" value="<?php echo date("Y-m-d", strtotime($campo_doc['data_doc'])); ?>" /> 
			di: <b><?php echo $fornitore['cognome']." ".$fornitore['nome']; ?></b></p>
			<br />
			<table class="table">
				<tr>
				<td align="center"><b>Nota</b> <br />
				<input type="text" name="nota" class="form-control" placeholder="Descriz. facoltat." /></td>
				<td align="center"><b>Quantit&agrave;</b> <br />
				<input type="text" name="qta" class="form-control" placeholder="N." /></td>
				<td align="center"><b>Un. Mis.</b> <br />
				<input type="text" name="unimis" class="form-control" placeholder="Unit&agrave; di misura" /></td>
				<td align="center"><b>Prezzo Unit.</b> <br />
				<input type="text" name="prezzo" class="form-control" placeholder="Importo &euro;" /></td>
				<td align="center"><b>Sconto</b> <br />
				<input type="text" name="sconto" class="form-control" placeholder="Sconto &euro;" /></td>
				<td align="center"><b>I.V.A.</b> <br />
				<select name="aliquota" class="form-control">
					<option name="aliquota" value="22" selected>IVA al 22 %</option>
					<option name="aliquota" value="21">IVA al 21 %</option>
					<option name="aliquota" value="10">IVA al 10 %</option>
					<option name="aliquota" value="4">IVA al 4 %</option>
					<option name="aliquota" value="0">Esclusa art. 15</option>
					<option name="aliquota" value="0">Non imponibile art. 8 c.2</option>
					<option name="aliquota" value="21ind">IVA al 21 % Indetraibile</option>
					<option name="aliquota" value="10ind">IVA al 10 % Indetraibile</option>
					<option name="aliquota" value="4ind">IVA al 4 % Indetraibile</option>
					<option name="aliquota" value="0">Esente art. 10</option>
					<option name="aliquota" value="0">Fuori campo IVA art. 2 c.3</option>
					<option name="aliquota" value="0">Fuori campo IVA art. 3 c.4</option>
					<option name="aliquota" value="0">Fuori campo IVA art. 4</option>
					<option name="aliquota" value="0">Fuori campo IVA art. 5</option>
					<option name="aliquota" value="0">Non imponibile art. 41</option>
					<option name="aliquota" value="0">Non imponibile art. 8</option>
					<option name="aliquota" value="0">Non imponibile art. 8-bis</option>
					<option name="aliquota" value="0">Non imponibile art. 9</option>
					<option name="aliquota" value="0">Non soggetta art. 7</option>
					<option name="aliquota" value="0">Non soggetta art. 26</option>
					<option name="aliquota" value="20">IVA al 20%</option>
					
				</select>
				<br />
				<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-plus"></i> Aggiungi riga</button>
				</td>
				</tr><tr>
				<td colspan="5"><button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-file"></i> Concludi Fattura</button></td>
				<td>Quadratura fattura: <input type="text" name="tot_bil" disabled="true" /></td>
			</tr></table>
		</form>
		 
	  	 </div>
		 <br /><br />
		 
<?php } elseif ($_GET['sez']==''){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Contabilit&agrave; e Strumenti utili</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=04&sez=cont_rimborsi"><i class="glyphicon glyphicon-piggy-bank"></i> Gestione Rimborsi</a>
		  	<a class="btn btn-default" href="?loc=04&sez=lista_movimenti"><i class="glyphicon glyphicon-list-alt"></i> Lista Movimenti</a>	
		  	<a class="btn btn-default" href="?loc=04&sez=clienti_rimborsabili"><i class="glyphicon glyphicon-calendar"></i> Clienti da Rimborsare</a>
		  	<a class="btn btn-default" href="?loc=04&sez=gestione_iva"><i class="glyphicon glyphicon-object-align-bottom"></i> Gestione IVA</a>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
<?php } ?>
  
</div>