<?php
//include 'config.php';
include_once "include.php"; 

$query = "SELECT * FROM mercusa_documenti WHERE tipo_doc = 2";
		$result = mysqli_query($con, $query) or die('Errore...');
		$numero = 0;
		while ($results = mysqli_fetch_array($result)) {   
			$id = $results['id'];
			$numero = $numero+1;
			// aggiorno i numeri dei documenti
			$update = "UPDATE mercusa_documenti SET num_doc='$numero' WHERE id = $id ";
			mysqli_query($con,$update);
		}
// qui c'era un footer che ho tolto
?>