<?php
$r = $_SERVER['REQUEST_URI'];
$r = explode('/', $r);
$r = array_filter($r);
$r = array_merge($r, array());
$endofurl = $r[2];
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>MercUsa - Gestionale Web - <?php echo $nome_azienda; ?></title>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

</head>
<body>
<h1 class="alert alert-warning" align="center">ALT!! Effettua l'accesso al sistema.</h1>
<div style="position: absolute; left: 40%; width: 300px;"><h2 align="center" class="alert alert-info"><a href="http://www.mercusa.it" target="mercusa"><img src="http://www.mercusa.it/wp-content/uploads/2016/01/LOGO1.png" /></a></div></h2>
<div style="position: absolute; left: 40%; top: 40%; width: 300px;">
<?php
if ($endofurl == 'sostecnologia') {
echo "Il vostro account MercUsa &egrave; scaduto. Per continuare a utilizzare questa licenza occorre rinnovare l'abbonamento seguendo le istruzioni inviate negli scorsi giorni tramite e-mail.";
}else{

?>
<form id="login" action="login.php" method="post">
	<fieldset id="inputs">
	    <input class="form-control" id="username" name="username" type="text" placeholder="Username" autofocus required>
	    <input class="form-control" id="password" name="password" type="password" placeholder="Password" required>
	</fieldset>
	<fieldset id="actions">
	    <button class="form-control" type="submit" id="submit"><i class="glyphicon glyphicon-play-circle"></i> Collegati</button>
	</fieldset>
</form>
<?php
}
?>
</div>
</body>
</html>
