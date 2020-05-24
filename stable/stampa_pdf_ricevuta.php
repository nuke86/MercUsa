<?php
include_once "include.php";

$campi_ricevuta = query_select_detail($con, $_GET['id'], 'mercusa_documenti');
$campi_cliente = query_select_detail($con, $campi_ricevuta['id_cliente'], 'mercusa_clienti');
$campi_lista = query_select_list_mov_ricevuta($con, $_GET['id']);
// recupero l'header delle ricevute dal config
$header = $headerExportPDFdocumenti;

$num_doc = $campi_ricevuta['num_doc'];
$data_doc = $campi_ricevuta['data_doc'];
$body = "
<p align=\"center\"><b>RICEVUTA DI VENDITA</b></p>
<table align=\"center\" border=\"1\">
	<tr><td align=\"center\">DOC n.
		<b>$num_doc</b>
	</td><td align=\"center\">
		<b>$data_doc</b>
	</td>
";
if ($campi_ricevuta['id_cliente'] != 0){
	$nome_cliente = $campi_cliente['nome'];
	$cognome_cliente = $campi_cliente['cognome'];
	$indirizzo_cliente = $campi_cliente['indirizzo'];
	$citta_cliente = $campi_cliente['citta'];
	$cap_cliente = $campi_cliente['cap'];
	$p_iva_cliente = $campi_cliente['p_iva'];
	
	$body .= "
	<td colspan=\"4\">Spett.le<br />
		<b>$nome_cliente $cognome_cliente</b> $indirizzo_cliente - $citta_cliente - $cap_cliente P.I. $p_iva_cliente
	</td>
	";
} else {
	$body .= "
	<td colspan=\"4\"> </td></tr>
	";
}
$riga_lista = $campi_lista['0'];
$totale = money_format('%.2n',$campi_lista['1']);
$body .= "
	<tr><td align=\"center\">COD ARTICOLO</td>
	<td align=\"center\">DESCRIZIONE</td>
	<td align=\"center\">QUANTITA'</td>
	<td align=\"center\">PREZZO</td>
	<td align=\"center\">% SCONTO</td>
	<td align=\"center\">IMPORTO</td></tr>
	$riga_lista
	<tr><td colspan=\"3\" align=\"right\">TOTALE RICEVUTA: </td> <td></td><td> </td>
	<td>$totale EURO</td></tr>
</table>
";

ob_end_clean ();

$content = "<page>".$header.$body."<br /><hr /><br />".$header.$body.
"<table width=\"100%\" style=\"font-size: 0.9em;\">
	<tr><td>Per quietanza</td></tr><tr><td><br /><br />______________________</td></tr></table></page>";
    // convert in PDF
    require_once(dirname(__FILE__).'/lib/html2pdf/vendor/autoload.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
//      $html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('exemple00.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
	
?>
