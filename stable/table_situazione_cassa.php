<br />
	<table width="400px" border="1" style="border: solid 1px #000;">
		  <tr><td colspan="2" align="center">Situazione di Cassa</td></tr>
		  <tr>
		  <td align="center">Rimborsi al: 01/<?php 
		  $data_rim_mese = date('m')+1;
		  $data_rim_anno = date('Y');
		  if ($data_rim_mese > 12){
		  	  $data_rim_mese = '01';
		  	  $data_rim_anno = date('Y')+1;
		  }
		  $data_rim = $data_rim_mese."/".$data_rim_anno;
		  echo $data_rim."<br /><b>".query_select_tot_rimborsi($con)."</b>"; ?>
		  </td>
		  <td align="center">N. ric. Vendita <u><?php echo date('Y'); ?></u><br />
		  <b><?php echo query_conta_ricevute_totali($con); ?></b>
		  </td></tr>
		  <tr><td align="center">Tot. Rimborsi Oggi<br />
		  <b><?php echo query_conta_rimborsato_oggi($con); ?></b>
		  </td>
		  <td align="center">Venduto del: <?php echo date('d/m/Y'); ?><br />
		  <b><?php
			$cassa = query_conta_chiusura_cassa($con);
			if ($cassa==0){
				echo "[...]";
			}else{
				echo $cassa;
			}
			?></b>
		  </td></tr>
		  <tr>
		  <td align="center">N. ric. Vendita Oggi<br />
		  <b><?php echo query_conta_ricevute_oggi($con); ?></b>
		  </td><td></td></tr>
	</table>