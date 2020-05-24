<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
</head>

<body onload="window.print()">

<div id="content">

<?php

include_once "include.php";
$dettaglio = query_select_detail($con, $_GET['id'],'mercusa_articoli');
$data = $dettaglio[7];
$data_giorno = date('d',strtotime($data));
$data_mese = date('m',strtotime($data));
$data_anno = date('Y',strtotime($data));
$data_ins = $data_giorno.'/'.$data_mese.'/'.$data_anno;

if (($configIvaEtichette == "yes") AND ($dettaglio['iva'] != "")){
	$prezzo = $dettaglio[5]+(($dettaglio[5]*11)/100);
	$msg_iva = "<br />IVA inclusa";
} elseif (($configIvaEtichette == "no") OR ($dettaglio['iva'] == ""))  {
	$prezzo = $dettaglio[5];
	$msg_iva = "";
}

echo "<b>$titolo_etichetta</b>";
?>
<table width="200px" border="1px">
	<tr>
	<td align="center">Cod: <?php echo $dettaglio[0]; ?></td>
	<td align="center"><b> &euro; <?php 
		if ($ArrotondaPrezzi == "yes"){
			echo money_format('%.1n0',$prezzo); 
		} else {
			echo money_format('%.2n',$prezzo); 
		}
	?></b></td>
	</tr>
	<tr>
	<td align="center"><?php echo $dettaglio[3]." ".$dettaglio[4]; ?></td>
	<td align="center"><b><?php echo $data_ins; ?></b></td>
	</tr>
	<br />
</table>
</div>

</body>
</html>