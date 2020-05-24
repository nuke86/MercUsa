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

echo "<b>Il Ripostiglio</b> <u style=\"font-size: 0.9em;\">www.ilripostiglio.net</u><br />
<span style=\"font-size: 0.8em;\">Negozio dell'usato - Quartu S.E.</span>
";
?>
<table width="200px" height="60px" border="1" style="border: 2px solid #000000;">
	<tr><td align="center"><b>1</b></td><td align="center"><b>2</b></td><td align="center"><b>3</b></td><td align="center"><b>4</b></td>
	<td align="center"><b>5</b></td></tr>
	<tr><td align="center"><b>6</b></td><td align="center"><b>7</b></td><td align="center"><b>8</b></td><td align="center"><b>9</b></td>
	<td align="center"><b><u>10</u></b></td></tr>
</table>
Scadenza tessera: <b>31/12/<?php echo date("Y") ?></b>
</div>

</body>
</html>
