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

// questo file effettua ricerche in tempo reale per completare le caselle di testo quando viene richiesto un cliente
include 'include.php';

try {
  $conn = new PDO("mysql:host=$mysql_ip;dbname=$db_name", $db_user, $db_pass);
} catch(PDOException $e) {
  echo $e->getMessage();
}

$cmd = 'SELECT * FROM mercusa_clienti WHERE cognome LIKE :term OR nome LIKE :term ORDER BY id';

$term = "%" . $_GET['term'] . "%";

$result = $conn->prepare($cmd);
$result->bindValue(":term", $term);
$result->execute();
$arrayArticoli = array();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	$nome = $row['nome'];
	$cognome = $row['cognome'];
	$str_nome = mb_convert_encoding($nome, "UTF-8");
	$str_cognome = mb_convert_encoding($cognome, "UTF-8");
  $rowArray['label'] = $str_cognome." ".$str_nome;
  $rowArray['value'] = $str_cognome." ".$str_nome;
  $rowArray['id_cliente'] = $row['id'];
  array_push($arrayArticoli, $rowArray);
}
$conn = NULL;
echo json_encode($arrayArticoli);
?>
