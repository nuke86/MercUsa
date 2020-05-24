<?php
/*
Copyright 2014, 2015 Dario Fadda.
This file is part of MercUsa.

    MercUsa is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    MercUsa is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with MercUsa.  If not, see <http://www.gnu.org/licenses/>.
*/

// questo file effettua ricerche in tempo reale per completare le caselle di testo quando viene richiesto un articolo
include 'include.php';

try {
  $conn = new PDO("mysql:host=$mysql_ip;dbname=$db_name", $db_user, $db_pass);
} catch(PDOException $e) {
  echo $e->getMessage();
}
//$cmd = 'SELECT * FROM mercusa_articoli WHERE id LIKE :term OR nome LIKE :term OR descrizione LIKE :term ORDER BY id';
$cmd = 'SELECT mercusa_movimenti.id_articolo, mercusa_movimenti.quantita, 
mercusa_movimenti_resi.id_articolo, mercusa_movimenti_resi.quantita,
mercusa_articoli.* 
FROM mercusa_articoli
LEFT JOIN mercusa_movimenti ON mercusa_articoli.id = mercusa_movimenti.id_articolo
LEFT JOIN mercusa_movimenti_resi ON mercusa_articoli.id = mercusa_movimenti_resi.id_articolo
WHERE
(((mercusa_articoli.quantita - mercusa_movimenti.quantita) > 0 OR (mercusa_articoli.quantita - mercusa_movimenti.quantita) is null) 
AND ((mercusa_articoli.quantita - mercusa_movimenti_resi.quantita) > 0 OR (mercusa_articoli.quantita - mercusa_movimenti_resi.quantita) is null)) 
AND (mercusa_articoli.id LIKE :term OR mercusa_articoli.nome LIKE :term OR mercusa_articoli.descrizione LIKE :term)

';

$term = "%" . $_GET['term'] . "%";

$result = $conn->prepare($cmd);
$result->bindValue(":term", $term);
$result->execute();
$arrayArticoli = array();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['quantita']>0){
	if ($row['contabilita']=='ct') $print_contab = "CT";
	if ($row['contabilita']=='cv') $print_contab = "CV";
	$nome = $row['nome'];
	$descrizione = $row['descrizione'];
	$str = mb_convert_encoding($nome, "UTF-8");
	$descr = mb_convert_encoding($descrizione, "UTF-8");
	$rowArray['label'] = $print_contab."00".$row['id']." ".$str." ".$descr;
	$rowArray['value'] = $row['id'];
	array_push($arrayArticoli, $rowArray);
	}

}
$conn = NULL;

echo json_encode($arrayArticoli);
?>
