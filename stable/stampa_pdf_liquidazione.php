<?php
require('lib/fpdf/fpdf.php');
include "include.php"; 

class PDF extends FPDF
{
// Page header
function Header()
{
	global $nome_azienda, $pivaAzienda, $cfAzienda, $indirizzo_azienda;
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    if ($_GET['affari'] == 'yes') {
    	    $this->Cell(0,0,'REGISTRO GIORNALE DEGLI AFFARI',0,1,'L');
    }else{
    	    $this->Cell(0,0,'REGISTRO DEL MARGINE',0,1,'L');
    }
    $this->SetFont('Arial','',10);
    $this->Cell(10,10,'    '.$nome_azienda.' - '.$indirizzo_azienda,0,1,'L');
    $this->Cell(10,0,'    P.IVA: '.$pivaAzienda.' C.F.: '.$cfAzienda,0,1,'L');
    $this->Ln(5);
    $this->SetFont('Arial','B',10);
    $anno = date("Y", strtotime($_GET['data_from']));
    $periodo_da = date("d/m/Y", strtotime($_GET['data_from']));
    $periodo_a = date("d/m/Y", strtotime($_GET['data_to']));
    $this->Cell(10,10,'ANNO: '.$anno.'         Periodo da: '.$periodo_da.'    a: '.$periodo_a,0,1,'L');
    // Line break
    $this->Ln(5);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class

define('EURO', chr(128));
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();


	$data_from = $_GET['data_from'].' 00:00:00';
	$data_to = $_GET['data_to'].' 23:59:59';
	$query = "SELECT * FROM mercusa_movimenti WHERE data BETWEEN '$data_from' AND '$data_to' ORDER BY data DESC";
	$result = mysqli_query($con, $query) or die('Errore...0 '. mysqli_error($con));
	while ($results = mysqli_fetch_array($result)) {
		$id_articolo = $results['id_articolo'];
		$id_doc = $results['id_documento'];
		$data_mov = date("d/m/Y", strtotime($results['data']));
		$campi_articolo = query_select_detail($con,$id_articolo,'mercusa_articoli');
			$nome_art = $campi_articolo['nome'];
			$codice = $campi_articolo['codice'];
		$campi_doc = query_select_detail($con,$id_doc,'mercusa_documenti');
			$num_doc = $campi_doc['num_doc'];
		$campi_doc_acq = query_select_detail($con,$codice,'mercusa_documenti');
			$num_doc_acq = $campi_doc_acq['num_doc'];
			
		if ($_GET['affari'] == 'yes') {
			$campi_fornitore = query_select_detail($con,$campi_articolo['id_cliente'],'mercusa_clienti');
		}
		
		$liq_articolo = query_liquidazione_iva($con, $id_articolo);
		$vendita = $liq_articolo[1];
		$acquisto = $liq_articolo[2];
		$margine_lordo = $liq_articolo[3];
		$margine_netto = $liq_articolo[4];
		$iva = $liq_articolo[0];
		
		
		$pdf->SetFont('Times','',12);
		$pdf->Cell(0,10,'Data operazione: '.$data_mov,0,1);
		$pdf->Cell(0,0,'Bene di riferimento: '.$id_articolo.' - '.$nome_art,0,1);
		$pdf->SetFont('Times','I',12);
		$pdf->Cell(0,10,'Operazione --- Importo --- Fattura di riferimento',0,1);
		$pdf->SetFont('Times','',12);
		$pdf->Cell(0,0,'Cessione        '.EURO.' '.$vendita.'        Ricevuta n. '.$num_doc,0,1);
		$pdf->Cell(0,10,'Acquisto       -'.EURO.' '.$acquisto.'        Lista carico n. '.$num_doc_acq,0,1);
		$pdf->Cell(0,0,'Margine realizzato sul bene:       '.EURO.' '.$margine_lordo.'       Rilevanza fiscale:       '.EURO.' '.$margine_lordo,0,1);
		$pdf->Cell(0,10,'Imponibile:                                  '.EURO.' '.$margine_netto.'       IVA:                           '.EURO.' '.$iva,0,1);
		
		if ($_GET['affari'] == 'yes') {
			$pdf->Cell(0,0,'Soggetto conto vendita: '.$campi_fornitore['cognome'].' '.$campi_fornitore['nome'].' - Residente in: '.$campi_fornitore['indirizzo'].' - '.$campi_fornitore['citta'],0,1);
			$pdf->Cell(0,10,'                 NATO il: '.$campi_fornitore['data_nascita'].' - A: '.$campi_fornitore['citta_nascita'].' - C.F. '.$campi_fornitore['cod_fiscale'],0,1);
		}
		$pdf->Ln(4);
		
		$totIva = $totIva+$iva;
		$totLordo = $totLordo+$margine_lordo;
		$totNetto = $totNetto+$margine_netto;
		$totVendita = $totVendita+$vendita;
	}
	//$totIva = money_format('%.2n',$totIva);
	$totLordo = money_format('%.2n',$totLordo);
	$totNetto = money_format('%.2n',$totNetto);
	$totVendita = money_format('%.2n',$totVendita);
	
	// preparo i calcoli per il riepilogo finale
	$impTot = $totLordo/1.22;
	$ivaTot = $totLordo-$impTot;
	$maggTrim = $ivaTot+(($ivaTot*1)/100);
	$impTot = money_format('%.2n',$impTot);
	$ivaTot = money_format('%.2n',$ivaTot);
	$maggTrim = money_format('%.2n',$maggTrim);
$num_pages = $pdf->PageNo();
if ($num_pages > 1){
	$pdf->AddPage();
}
$pdf->Ln(4);
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,10,'Riepilogo periodo considerato',0,1);
$pdf->SetFont('Times','',12);
$pdf->Cell(0,10,'Margine maturato nel periodo:       '.EURO.' '.$totLordo,0,1);
$pdf->Cell(0,10,'Margine a tassazione:                     '.EURO.' '.$totLordo,0,1);
$pdf->Cell(0,10,'Cessioni al 22%:                            '.EURO.' '.$totVendita,0,1);
$pdf->Cell(0,10,'Margine al 22%:                             '.EURO.' '.$totLordo,0,1);
$pdf->Cell(0,10,'Imponibile:                                     '.EURO.' '.$impTot,0,1);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,10,'Calcolo IVA da versare:                      '.EURO.' '.$ivaTot,0,1);
$pdf->Cell(0,10,'Maggiorazione 1% (trimestrale):       '.EURO.' '.$maggTrim,0,1);

$pdf->Output();

?>