<?php 

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

<?php if ($_GET['sez']=='new_pro'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Inserisci nuovo Promemoria</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=05&sez=promemoria"><i class="glyphicon glyphicon-hand-left"></i> Indietro</a>
		  	<br /><br />
		  	<form action="query_start.php?sez=add_new_prom" method="post">
		  	<table class="table"><tr>
		  	<td>Descrizione: <br />
		  		<textarea name="testo" class="form-control" cols="20" rows="5" placeholder="Testo del tuo promemoria"></textarea></td>
		  	<td valign="top">Data di Inizio promemoria: <br />
		  		<table><tr><td><select name="giorno" class="form-control" style="width: 65px;">
						<?php
						for($i=1;$i<32;$i++){
							if ($i == date("d")) {
									echo "<option value=\"" . $i . "\" selected>" . $i . "</option>\n";
							} else {
								echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
							}
						}
						?>
					</select> 
				</td><td>
					<select name="mese" class="form-control" style="width: 60px;">
						<?php
						for($i=1;$i<13;$i++){
							if ($i == date("m")) {
									echo "<option value=\"" . $i . "\" selected>" . $i . "</option>\n";
							} else {
								echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
							}
						}
						?>
					</select>
				</td><td> 
					<select name="anno" class="form-control" style="width: 80px;">
						<?php
						$anno_end = date("Y")+2;
						for($i=2014;$i<$anno_end;$i++){
							if ($i == date("Y")) {
									echo "<option value=\"" . $i . "\" selected>" . $i . "</option>\n";
							} else {
								echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
							}
						}
						?>
					</select>
				</td></tr></table>
		  	<br /><br />
		  	<input type="submit" class="btn btn-default" value="Crea" /></td>
		  	</tr>
		  	</table>
		  	</form>

	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>

<?php } elseif ($_GET['sez']=='edit_prom'){ 
	update_letto_prom($con, $_GET['id'], 1);
	$dettaglio_prom = query_select_single_prom($con, $_GET['id']);
	?>
  	<div class="panel-heading" style="text-align: center;"><b>Dettaglio Promemoria</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=05&sez=promemoria"><i class="glyphicon glyphicon-hand-left"></i> Indietro</a>
		  	<a class="btn btn-default" href="?loc=05&sez=new_pro"><i class="glyphicon glyphicon-plus"></i> Nuovo</a>
		  	<br /><br />
		  	<form action="query_start.php?sez=edit_prom" method="post">
		  	<table class="table"><tr>
		  	<td>Descrizione: <br />
		  		<textarea name="testo" class="form-control" cols="20" rows="5"><?php echo $dettaglio_prom['testo']; ?></textarea></td>
		  	<td valign="top">Data di Inizio promemoria: <br />
		  		<input type="text" class="form-control" name="data" value="<?php echo $dettaglio_prom['data']; ?>" />
		  	<br /><br />
		  	<input type="submit" class="btn btn-default" value="Salva" disabled="true" /></td>
		  	</tr>
		  	</table>
		  	</form>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>

<?php } elseif ($_GET['sez']=='promemoria'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Promemoria</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=05"><i class="glyphicon glyphicon-hand-left"></i> Indietro</a>
		  	<a class="btn btn-default" href="?loc=05&sez=new_pro"><i class="glyphicon glyphicon-plus"></i> Nuovo</a>
		  	<br /><br />
		  	<div style="width: 800px; position: relative; left: 20%;">
		  	<center><i class="glyphicon glyphicon-star"></i> = "ancora non letti"</center>
		  		<table class="table">
		  		<tr><td><b>#</b></td><td><b>DATA INIZIO PROMEMORIA</b></td><td><b>TESTO</b></td></tr>
		  		<?php echo query_select_prom($con); ?>
		  		</table>
		  	</div>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>

<?php } elseif ($_GET['sez']=='mailinglist'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Mailing List</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=05&sez=gest_mailinglist"><i class="glyphicon glyphicon-hand-left"></i> Indietro</a>
		  	<br />
		  	<div class="alert alert-info" role="alert">
		  		<b>HELP:</b> Con questo strumento si possono inviare e-mail ai propri clienti: SINGOLARMENTE specificando gli 
		  		indirizzi dei destinatari separati da virgole, oppure in MASSA lasciando in bianco il campo <i>destinatario</i>.
		  		Nel caso di e-mail di MASSA bisogna tener presente che il messaggio che specificate arriver&agrave; identico a tutti
		  		i vostri clienti.
		  	</div>
		  	<br />
		  	<div style="width: 800px; position: relative; left: 20%;">
		  	<center>Invia una nuova Mass-Mail alla lista anagrafiche
		  		<table width="100%">
		  		<form action="query_start.php?sez=sendmail" method="POST">
		  		<?php if ($_GET["id_cliente"] != ""){ ?>
		  		<tr><td><input type="text" class="form-control" name="oggetto" value="Tanti auguri a te" /><br /></td>
		  		<?php 
		  		$cliente = query_select_detail($con, $_GET['id_cliente'], 'mercusa_clienti');
		  		$destinatario = $cliente["email"];
		  		?>
		  		<td><input type="text" class="form-control" name="destinatario" value="<?php echo $destinatario; ?>" /><br /></td>
		  		<?php } else { ?> 
		  		<tr><td><input type="text" class="form-control" name="oggetto" placeholder="Oggetto" /><br /></td>
		  		<td><input type="text" class="form-control" name="destinatario" placeholder="Destinatario - lasciare vuoto per invio a TUTTI" /><br /></td>

		  		<?php } ?>
		  		
		  		</tr>
		  		<tr><td colspan="2">Per incollare dall'esterno: "CTRL-V"
		  		<?php if ($_GET["id_cliente"] != ""){ ?>
		  			<textarea name="corpomail" rows="20" >
		  			Gent.le Cliente, <br />
		  			Tutto lo Staff de <b>Il Ripostiglio</b> ti augura i pi&ugrave; felici e sinceri AUGURI di Buon Compleanno, in questa tua giornata speciale.<br />
		  			Cogliamo l'occasione per ricordarti che quando vorrai potrai sempre venirci a trovare presso il nostro punto vendita di Quartu S. Elena, via Piemonte 37A.<br />
		  			<br />Felice Compleanno,<br />
		  			<b><i>http://www.ilripostiglio.net</i></b></textarea>
		  		<?php } else { ?>
		  		<textarea name="corpomail" rows="20" placeholder="Corpo del messaggio" ></textarea>
		  		<?php } ?>
		  		</td></tr>
		  		<tr><td align="right"><button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-ok"></i> Invia</button></td></tr>
		  		</form>
		  		</table></center>
		  	</div>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>
<?php } elseif ($_GET['sez']=='gest_mailinglist'){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Mailing List</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=05"><i class="glyphicon glyphicon-hand-left"></i> Indietro</a>
		  	<a class="btn btn-default" href="?loc=05&sez=mailinglist"><i class="glyphicon glyphicon-plus"></i> Nuova Mail</a>
		  	
		  	<br /><br />
		  	<div style="width: 800px; position: relative; left: 20%;">
		  	<center>Elenco delle Mass-Mail inviate
		  		<table class="table">
		  		<tr><td><b># Codice</b></td><td><b>Oggetto</b></td><td><b>Data</b></td></tr>
		  		<?php echo query_select_mailinglist($con); ?>
		  		</table></center>
		  	</div>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>
<?php } elseif ($_GET['sez']=='dettaglio_mail'){ 
	$campi_email = query_select_detail($con,$_GET['id_mail'],'mercusa_email');
	?>
  	<div class="panel-heading" style="text-align: center;"><b>Mailing List</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=05&sez=gest_mailinglist"><i class="glyphicon glyphicon-hand-left"></i> Indietro</a>
		  	<a class="btn btn-default" href="?loc=05&sez=mailinglist"><i class="glyphicon glyphicon-plus"></i> Nuova Mail</a>
		  	
		  	<br /><br />
		  	<div style="width: 800px; position: relative; left: 20%;">
		  	<center>Dettaglio E-Mail
		  		<table class="table">
		  		<tr><td><b># Codice: </b></td><td><?php echo $campi_email['id']; ?></td></tr>
		  		<tr><td><b>Oggetto: </b></td><td><?php echo $campi_email['oggetto']; ?></td></tr>
		  		<tr><td><b>Data: </b></td><td><?php echo $campi_email['data']; ?></td></tr>
		  		<tr><td><b>Destinatario: </b></td><td><?php 
		  		if ($campi_email['destinatario']==""){
		  			echo "TUTTI";
		  		}else{
		  			echo $campi_email['destinatario'];
		  		}
		  		?></td></tr>
		  		<tr><td valign="top"><b>Messaggio: </b></td><td><?php echo $campi_email['corpo']; ?></td></tr>
		  		
		  		</table></center>
		  	</div>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>
	
<?php } elseif ($_GET['sez']=='dropbox'){ 
	if ($_GET['logoutDB'] == 'ok') {
		logoutDropboxList();
	}
?>
  	<div class="panel-heading" style="text-align: center;"><b>Gestione DropBox personale</b></div>
	 	 <div class="panel-body">
	 	 <div class="alert">Con questa funzione hai la possibilit&agrave; di associare foto o immagini che hai caricato 
	 	 nella cartella <b>mercusa</b> del tuo DropBox, agli articoli presenti nel tuo magazzino.</div>
		  	<!-- Strumenti principali -->
		  	<?php 
		  		include "lib/Dropbox/sample.php";
		  		
		  		echo 'Accesso Dropbox eseguito con l\'account: <span class="alert alert-info">'.$accountArr->display_name.' - <b>'.$accountArr->email.'</b></span> | <a href="index.php?loc=05&sez=Mailmessage&logoutDB=ok" class="btn btn-default">Scollega</a><br /><br />';
				$files = $dropbox->GetFiles("",false);
				echo '<b>Files trovati:</b><div style="width: 50%;"><ul class="list-group">';
				foreach ($files as $f) {
					$img_data = base64_encode($dropbox->GetThumbnail($f->path));
					echo '<li class="list-group-item"><img src="data:image/jpeg;base64,'.$img_data.'" alt="NO-IMG" style="border: 1px solid black;" /> '.$f->path.'<span class="badge">Associa</span></li>';
				}
				echo '</ul></div>';
		  	?>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>
	
	<?php } elseif ($_GET['sez']=='settings'){ 
		$setting18 = selectSettingsArray($con,'url_imgLogo');
		$url_imgLogo = 	"/mercusa/accounts/$endofurl/img/".$setting18['contenuto'];
		?>
  	<div class="panel-heading" style="text-align: center;"><b>Impostazioni di Sistema</b></div>
	 	 <div class="panel-body">
	 	 	<div class="alert">
	 	 		Da questo menu hai la possibilit&agrave; di personalizzare le <b>opzioni base di sistema</b>, secondo le tue preferenze.
	 	 	</div>
	 	 	<form action="query_start.php?sez=update_settings" method="post">
	 	 	<div style="width: 500px; height: 103px;" class="alert alert-info"><b>Carica nuovo Logo</b> <a href="upload-form.php?logo_si=yes" class="btn btn-sm btn-primary open" title="Carica nuovo Logo" ><i class="glyphicon glyphicon-camera"></i></a>
	 	 	<img src="<?php echo $url_imgLogo; ?>" width="250px" height="83px" style="float: right;"/>
	 	 	<p style="font-size: smaller; text-align: left;">corrisponde al tuo logo aziendale presente nelle testate dei documenti</p>
	 	 	</div>
	 	 	<table class="table">
	 	 		<tr><td><b>ID #</b></td>
	 	 		<td><b>Impostazione</b></td>
	 	 		<td><b>Valore dell'impostazione</b></td>
	 	 		<td><b>Disponibile</b></td>
	 	 		<td><b>Descrizione</b></td></tr>
	 	 		
	 	 		<?php echo selectAllSettings($con); ?>
	 	 		
	 	 		<tr><td> </td><td> </td><td> </td><td> </td><td> </td></tr>
	 	 	</table>
	 	 	<p align="right"><button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i> Salva tutto</button></p>
	 	 	</form>
	 	 </div>
	 	  <br /><br />
	</div><br /><br />
	
<?php } elseif ($_GET['sez']==''){ ?>
  	<div class="panel-heading" style="text-align: center;"><b>Strumenti Extra</b></div>
	 	 <div class="panel-body">
		  	<!-- Strumenti principali -->
		  	<a class="btn btn-default" href="?loc=05&sez=promemoria"><i class="glyphicon glyphicon-time"></i> Promemoria</a>
		  	<a class="btn btn-default" href="?loc=05&sez=gest_mailinglist"><i class="glyphicon glyphicon-envelope"></i> Mailing List</a>
		  	<?php if ($configModuloPunti=='yes'){ ?>
		  	<a class="btn btn-default" href="stampa_tessera.php" target="punti" title="Fare riferimento all'articolo codice 2129 per ogni modifica"><i class="glyphicon glyphicon-print"></i> Genera Tessera Punti</a>
		  	<?php } ?>
		  	<a class="btn btn-default" href="?loc=05&sez=settings"><i class="glyphicon glyphicon-wrench"></i> Impostazioni Sistema</a>
		  	<a class="btn btn-default" href="?loc=04&sez=calcolo_percentuale"><i class="glyphicon glyphicon-scale"></i> Calcolo percetuale di rimborso</a>
	  	 </div>
		  <!-- Lista delle anagrafiche -->
		 <br /><br />
	</div>
<?php } ?>
  
