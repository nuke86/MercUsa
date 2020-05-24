<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
</head>

<body>
<h3 align="center">Imposta le date di inizio e fine, poi premi su <i>Stampa</i></h3>
<form action="query_articoli.php?sez=stampa_maga" method="POST">
<table>
<tr><td colspan="3" align="center"><b>Dal:</b></td></tr>
<tr><td><select name="giorno" style="width: 65px;">
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
</tr>
<tr><td colspan="3" align="center"><b>Al:</b></td></tr>
<tr><td><select name="giornoFine" style="width: 65px;">
		<?php
			$giorno_oggi = date("d");
			if ($_GET['giornoFine'] == ""){
				$giorno = $giorno_oggi;
			}else{
				$giorno = $_GET['giornoFine'];
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
	<select name="meseFine" style="width: 60px;">
		<?php
			$mese_oggi = date("m");
			if ($_GET['meseFine'] == ""){
				$mese = $mese_oggi;
			}else{
				$mese = $_GET['meseFine'];
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
	<select name="annoFine">
		<?php
			$anno_oggi = date("Y");
			if ($_GET['annoFine'] == ""){
				$anno = $anno_oggi;
			}else{
				$anno = $_GET['annoFine'];
			}
			$anno_end = $anno_oggi+1;
			for($i=2014;$i<$anno_end;$i++){
				if ($i==$anno){
					echo "<option name=\"annoFine\" selected value=\"" . $i . "\">" . $i . "</option>\n";
				}else{
				echo "<option name=\"annoFine\" value=\"" . $i . "\">" . $i . "</option>\n";
				}
			}
		?>
	</select>
</td>
<td>&nbsp;</td>
<td width="75px" rowspan="3" colspan="2" align="right"><input type="submit" class="btn-xs btn-default" value="Stampa" /></td>
</tr>
</table>
</form>
<?php 
if ($_GET['dataInizio'] != ""){ 
	$url = "stampa_magazzino.php?dataInizio=".$_GET['dataInizio']."&dataFine=".$_GET['dataFine'];
?>
	
Stampa generata con SUCCESSO <a href="<?php echo $url; ?>" target="maga">clicca qui per stampare</a>

<?php } ?>
</body>
</html>