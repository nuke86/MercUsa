<div id="content">

<?php
include_once "include.php";
$dettaglio = query_select_detail($con, $_GET['id'],'mercusa_articoli');

$url_img = $dettaglio['url_img'];
$url = stripslashes($url_img);

$path = '/mercusa/accounts/'.$endofurl.'/thumb/';
$cleantitle = preg_replace("/\W/", "", $url_img);
$cleantitle = substr($cleantitle, 6);
$nomeimg = $cleantitle.'_135x180_thumb.jpg';
?>
<table>
<tr><td><b><?php echo $dettaglio['nome']; ?></b> </td><td><?php echo $dettaglio['descrizione']; ?> </td></tr>
<tr><td colspan="2"><img src="<?php echo $path.$nomeimg; ?>" /> </td></tr>
</table>


</div>