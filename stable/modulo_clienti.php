<?php 
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
  
  <?php if ($_GET['sez']=='cl_new'){ ?>
  <div class="panel-heading" style="text-align: center;"><b>Inserimento nuovo cliente</b></div>
  <div class="panel-body">
  	
  	
  			<a class="btn btn-default" href="?loc=01&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a><br /><br />
  			
  			<form action="query_clienti.php?sez=cl_new_insert" method="post">
  			<?php if ($_GET['fatt']==1){ ?>
  				<input type="hidden" name="fatt" value="1" />
  			<?php } ?>
  			<table class="table">
  			  <tr>
  			  	<td><input type="text" name="nome" class="form-control" placeholder="Nome/Ragione sociale"></td>
  			  	<td><input type="text" name="cognome" class="form-control" placeholder="Cognome"></td>
  			  	<td><input type="text" name="cod_fiscale" class="form-control" placeholder="Codice Fiscale"></td>
  			  	<td><input type="text" name="p_iva" class="form-control" placeholder="Partita IVA"></td>
  			  	<td><input type="text" name="indirizzo" class="form-control" placeholder="Indirizzo"></td>
  			  	</tr>
  			  <tr>
  			  	<td><input type="text" id="comune" name="citta" class="form-control" placeholder="Citt&agrave; di residenza"></td>
  			  	<td><input type="text" name="cap" class="form-control" placeholder="CAP"></td>
  			  	<td><input type="text" id="provincia" name="provincia" class="form-control" placeholder="Provincia"></td>
  			  	<td><input type="text" name="telefono" class="form-control" placeholder="Telefono"></td>
  			  	<td><input type="text" name="email" class="form-control" placeholder="E-mail"></td>
  			  </tr>
  			  <tr>
  			  	<td>Data di Nascita:
  			  		<table><tr><td><select name="giorno" class="form-control" style="width: 65px;">
						<?php
						for($i=1;$i<32;$i++){
							echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
						}
						?>
					</select> 
				</td><td>
					<select name="mese" class="form-control" style="width: 60px;">
						<?php
						$mesiArray = array("Gennaio","Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre");
						for($i=1;$i<13;$i++){
							echo "<option value=\"" . $i . "\">" . $i." ". $mesiArray[$i-1] . "</option>\n";
						}
						?>
					</select>
				</td><td> 
					<select name="anno" class="form-control" style="width: 80px;">
						<?php
						for($i=1920;$i<2012;$i++){
							echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
						}
						?>
					</select>
				</td></tr></table>
				</td>
				<td><br /><input type="text" id="nascita" name="citta_nascita" class="form-control" placeholder="Luogo di nascita"></td>
			
  			  	
				<td>Tipo di documento:<br /><select name="tipo_doc_id" class="form-control">
					<option value="1">Carta d'Identit&agrave;</option>
					<option value="2">Patente di Guida</option>
					<option value="3">Patente Nautica</option>
					<option value="4">Porto d'armi</option>
					<option value="5">Passaporto</option>
				</select>
				</td>
				<td><br /><input type="text" name="num_doc_id" class="form-control" placeholder="Numero Documento"></td>
				<td align="right"><br /><input type="submit" class="btn btn-default" value="Salva" /></td>
  			  </tr>  
  			</table>
  			</form>
  			  </div>
  			  
  	<?php } elseif ($_GET['sez']=='cl_edit'){ 
  		$dettaglio = query_select_detail($con, $_GET['id'], 'mercusa_clienti');
  		?>
  		
  		<div class="panel-heading" style="text-align: center;"><b>Modifica cliente</b></div>
  			<div class="panel-body">
  			<a class="btn btn-default" href="?loc=01&sez=start">Torna indietro</a>
  			<a class="btn btn-default" href="stampa_mandato.php?id=<?php echo $dettaglio[0]; ?>" target="mandato" onclick="return confirm('Ricorda che questo documento va stampato in DUPLICE COPIA!!');">Stampa Mandato</a>
  			<a class="btn btn-default" href="stampa_mandato_pdf.php?id=<?php echo $dettaglio[0]; ?>" target="mandato">GENERA PDF MANDATO</a>

  			<br /><br />
  			
  			<form action="query_clienti.php?sez=cl_update" method="post">
  			<table class="table">
  			  <tr>
  			  	<td>Nome: <br />
  			  	<input type="text" name="nome" class="form-control" value="<?php echo $dettaglio[1]; ?>">
  			  	</td>
  			  	<td>Cognome: <br />
  			  	<input type="text" name="cognome" class="form-control" value="<?php echo $dettaglio[2]; ?>">
  			  	</td>
  			  	<td>Codice Fiscale: <br />
  			  	<input type="text" name="cod_fiscale" class="form-control" value="<?php echo $dettaglio[3]; ?>">
  			  	</td>
  			  	<td>Partita IVA: <br />
  			  	<input type="text" name="p_iva" class="form-control" value="<?php echo $dettaglio[4]; ?>">
  			  	</td>
  			  	<td>Indirizzo: <br />
  			  	<input type="text" name="indirizzo" class="form-control" value="<?php echo $dettaglio[5]; ?>">
  			  	</td>
  			  	</tr>
  			  <tr>
  			  	<td>Citt&agrave;: <br />
  			  	<input type="text" name="citta" class="form-control" value="<?php echo $dettaglio[7]; ?>">
  			  	</td>
  			  	<td>CAP: <br />
  			  	<input type="text" name="cap" class="form-control" value="<?php echo $dettaglio[8]; ?>">
  			  	</td>
  			  	<td>Provincia: <br />
  			  	<input type="text" name="provincia" class="form-control" value="<?php echo $dettaglio[9]; ?>">
  			  	</td>
  			  	<td>Telefono: <br />
  			  	<input type="text" name="telefono" class="form-control" value="<?php echo $dettaglio[10]; ?>">
  			  	</td>
  			  	<td>E-Mail: <br />
  			  	<input type="text" name="email" class="form-control" value="<?php echo $dettaglio[11]; ?>">
  			  	</td>
  			  </tr>
  			  <tr>
  			  	<td>Data di Nascita: <br />
  			  	<input type="text" name="data_nascita" class="form-control" value="<?php echo $dettaglio[6]; ?>">
				</td>
				<td>Luogo di nascita: <br />
  			  	<input type="text" name="citta_nascita" class="form-control" value="<?php echo $dettaglio[16]; ?>">
				<td>Password: <br />
  			  	<input type="text" name="password" class="form-control" value="<?php echo $dettaglio[12]; ?>">
				</td>
				<td>Tipo di documento:<br /><select name="tipo_doc_id" class="form-control">
				<?php if(!$dettaglio['13']) { ?>
					<option selected> </option>
					<option value="1">Carta d'Identit&agrave;</option>
					<option value="2">Patente di Guida</option>
					<option value="3">Patente Nautica</option>
					<option value="4">Porto d'armi</option>
					<option value="5">Passaporto</option>
				<?php }elseif($dettaglio['13']=='1') { ?>
					<option value="1" selected>Carta d'Identit&agrave;</option>
					<option value="2">Patente di Guida</option>
					<option value="3">Patente Nautica</option>
					<option value="4">Porto d'armi</option>
					<option value="5">Passaporto</option>
				<?php }elseif($dettaglio['13']=='2') { ?>
					<option value="2" selected>Patente di Guida</option>
					<option value="1">Carta d'Identit&agrave;</option>
					<option value="3">Patente Nautica</option>
					<option value="4">Porto d'armi</option>
					<option value="5">Passaporto</option>
				<?php }elseif($dettaglio['13']=='3') { ?>
					<option value="3" selected>Patente Nautica</option>
					<option value="1">Carta d'Identit&agrave;</option>
					<option value="2">Patente di Guida</option>
					<option value="4">Porto d'armi</option>
					<option value="5">Passaporto</option>
				<?php }elseif($dettaglio['13']=='4') { ?>
					<option value="4">Porto d'armi</option>
					<option value="1">Carta d'Identit&agrave;</option>
					<option value="2">Patente di Guida</option>
					<option value="3">Patente Nautica</option>
					<option value="5">Passaporto</option>
				<?php }elseif($dettaglio['13']=='5') { ?>
					<option value="5">Passaporto</option>
					<option value="1">Carta d'Identit&agrave;</option>
					<option value="2">Patente di Guida</option>
					<option value="3">Patente Nautica</option>
					<option value="4">Porto d'armi</option>
				<?php } ?>
				</select>
				</td>
				<td>Numero di Documento: <br />
				<input type="text" name="num_doc_id" class="form-control" value="<?php echo $dettaglio[14]; ?>">
				</td>
				<input type="hidden" name="id" value="<?php echo $dettaglio[0]; ?>">
				<td align="right"><br /><input type="submit" class="btn btn-default" value="Salva" /></td>
  			  </tr>  
  			</table>
  			</form>
  			  </div>
  			  
  	<?php } elseif ($_GET['sez']=='cl_trash'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Il Cestino - Clienti</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=01&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=01&sez=cl_new"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		  <table class="table">
		    <tr><td><b>Codice</b></td><td><b>Nome/Ragione sociale</b></td><td><b>Indirizzo</b></td><td><b>Telefono</b></td><td><b>Email</b></td><td><b>Opzioni</b></td></tr>
		    <?php echo query_select_clienti_list($con,0,100); ?>
  		</table><br /><br />	
  	
  	<?php } elseif ($_GET['sez']=='lista_nominativi'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Lista Completa Anagrafiche</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=01&sez=start"><i class="glyphicon glyphicon-hand-left"></i> Torna indietro</a>
		  	<a class="btn btn-default" href="?loc=01&sez=cl_new"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		  <table class="table">
		    <tr><td><b>Codice</b></td><td><b>Nome/Ragione sociale</b></td><td><b>Indirizzo</b></td><td><b>Telefono</b></td><td><b>Email</b></td><td><b>Opzioni</b></td></tr>
		    <?php echo query_select_clienti_list($con,1,500); ?>
  		</table><br /><br />
  			  
  	<?php } elseif ($_GET['sez']=='start'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Anagrafica Nominativi</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=01&sez=cl_new"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
		  	<a class="btn btn-default" href="?loc=01&sez=cl_trash"><i class="glyphicon glyphicon-trash"></i> Il Cestino</a>
		  	<a class="btn btn-default" href="?loc=01&sez=lista_nominativi"><i class="glyphicon glyphicon-th-list"></i> Lista Nominativi Completa</a>
	  	 </div>
		  <!-- funzione filtro anagrafiche -->
		  <table class="table">
	  	 		<form action="query_clienti.php?sez=cliente_dettaglio" method="post">
	  	 		<input type="hidden" id="idclientejson" name="id_cliente" value="" />
		  			<tr>
		  			<td>Filtra anagrafica: &nbsp;&nbsp;</td>
		  			<td><input type="text" id="id_cliente" name="cliente" placeholder="Cerca anagrafica" class="form-control" /></td>
		  			<td>&nbsp;</td>
		  			<td><button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-screenshot"></i> Vai a dettaglio</button></td>
		  		</form>
	  	 </tr></table>
	  	 <b>Ultime 3 anagrafiche inserite</b><br />
	  	 <table class="table">
	  	 <?php echo query_select_clienti_list($con,1,3); ?>
	  	 </table>
	  	 <?php if ($_GET['fatt']==1){ ?>
			 <br /><br /> 
			 <p align="center"><a href="index.php?loc=04&sez=nuova_fattura_acquisto">Torna a gestione Fatture d'Acquisto</a></p>
	  	 <?php } ?>
		 <br /><br /> 
  
  <?php } ?>
  
</div>