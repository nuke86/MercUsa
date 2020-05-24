<html><head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link rel="stylesheet" href="lib/uploadstyle.css" />
<script src="lib/uploadscript.js"></script>
</head>
<body>
<?php
ini_set("session.gc_maxlifetime","86400");
ini_set("session.cookie_lifetime","86400");
session_start();
//se non c'e la sessione registrata
if (!$_SESSION['autorizzato']) {
  header("Location: login_form.php");
  die;
}
?>

<div class="main">
<form id="uploadimage" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="id_art" id="id_art" value="<?php echo $_GET['id_art']; ?>" />
<input type="hidden" name="logo_si" id="logo_si" value="<?php echo $_GET['logo_si']; ?>" />
<div id="image_preview"><img id="previewing" src="lib/noimage.png" height="150px" width="150px" /></div>
<div id="selectImage">
<label>Scegli la tua immagine</label><br/>
<input type="file" name="image" id="image" required />
<input type="submit" value="Carica" class="submit" />
</div>
</form>
</div>
<h5 id='loading' >Caricamento..</h5>
<div id="message"></div>
</body>
</html>