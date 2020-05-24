<!DOCTYPE html>
<html>
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>MercUsa - Gestionale Web - <?php echo $nome_azienda; ?></title>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">



<!-- Optional theme -->

<style>
.btn-primary:visited { 
	background-color: red;
}
.btn-default {
	border: 1px solid;
}
.form-control {
	border: 1px solid;
}

</style>
<script>
$(document).ready(function($){
$("#id_cliente").autocomplete({
source:"cerca_clienti.php",
select: function( event, ui ) {
	var idCliente = ui.item.id_cliente;
	document.getElementById("idclientejson").value=idCliente;
      }
    });
});
</script>

<script>
$(document).ready(function($){
    $("#id_articolo").autocomplete({
	source:"cerca_articoli.php"
    });
});
</script>

<script>
$(document).ready(function($){
    $("#comune").autocomplete({
	source:"cerca_comuni.php"
    });
});
</script>

<script>
$(document).ready(function($){
    $("#nascita").autocomplete({
	source:"cerca_comuni.php"
    });
});
</script>

<script>
$(document).ready(function($){
    $("#provincia").autocomplete({
	source:"cerca_province.php"
    });
});
</script>

<script type="text/javascript">
$(function() {

	$('.open').click(function(event) {
	
	    var $link = $(this);
	
		var $dialog = $('<div style=\"overflow: auto;\"></div>')
				.load($link.attr('href') + ' #content')
				.dialog({
					autoOpen: false,
					title: $link.attr('title'),
					open: function(){
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.append('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text"></span>');
        },
					width: 450,
					height: 350
					
		});
		
		$dialog.dialog('open');
	
		event.preventDefault();
	
	});

});
</script>

<style>
.box{
    display: none;
    width: 350px;
    background-color: #fff;
}

a:hover + .box,.box:hover{
    display: block;
    position: fixed;
    top: 50%;
    left: 5%;
    z-index: 100;
}
</style>

<script type="text/javascript">
function update()
	{
	document.getElementById("resto").value = document.getElementById("banconote").value-document.getElementById("tot").value;
	return;
	}
	
function updateIva()
	{
	document.getElementById("totIva").value = parseFloat(document.getElementById("prezzo").value)+((document.getElementById("prezzo").value*11)/100);
	return;
	}
function updateSconto()
	{
	document.getElementById("totSconto").value = document.getElementById("prezzoIva").value-((document.getElementById("prezzoIva").value*document.getElementById("sconto").value)/100);
	return;
	}
</script>

<script type="text/javascript" language="javascript">
function show_hide(){
  
    if(document.getElementById("contab").value == 'ct'){
      document.getElementById("prezzo_acq").style.display = 'block';
      document.getElementById("prezzo_acq_label").style.display = 'block';
    }else{
      document.getElementById("prezzo_acq").style.display = 'none';
      document.getElementById("prezzo_acq_label").style.display = 'none';
    }
}

function IsEmpty(){ 
	
	if(document.getElementById("idclientejson").value == "")
	{
		alert("Attenzione NON hai selezionato il Fornitore");
		return false;
	}else{
	return true;
	}
}

$(function () {
    var iframe = $('<iframe frameborder="0" marginwidth="0" marginheight="0" allowfullscreen></iframe>');
    var dialog = $("<div></div>").append(iframe).appendTo("body").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: "auto",
        height: "auto",
        close: function () {
            iframe.attr("src", "");
        }
    });
    $(".window-iframe").on("click", function (e) {
        e.preventDefault();
        var src = $(this).attr("href");
        var title = $(this).attr("data-title");
        var width = $(this).attr("data-width");
        var height = $(this).attr("data-height");
        iframe.attr({
            width: +width,
            height: +height,
            src: src
        });
        dialog.dialog("option", "title", title).dialog("openI");
    });
});

function sbloccaCampi() {
document.getElementById("nome").disabled = false;
document.getElementById("descr").disabled = false;
document.getElementById("prezzo").disabled = false;
document.getElementById("prezzo_acq").disabled = false;
document.getElementById("home").disabled = false;
document.getElementById("iva").disabled = false;
document.getElementById("data").disabled = false;
document.getElementById("quant").disabled = false;
document.getElementById("contab").disabled = false;
document.getElementById("mandato").disabled = false;
document.getElementById("salva").disabled = false;


}

function apri(url) { 
    newin = window.open(url,'titolo','scrollbars=no,resizable=yes, width=200,height=200,status=no,location=no,toolbar=no');
} 

$(function() {
		$( "#datepicker" ).datepicker();
		$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		$("#datepicker").datepicker("setDate", new Date());
		
		$( "#datepicker1" ).datepicker();
		$( "#datepicker1" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		
});

</script>


<script type="text/javascript">
var LHCChatOptions = {};
LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500,domain:'95.110.226.234'};
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
po.src = '//95.110.226.234/mercusa/stable/lib/livehelperchat-master/lhc_web/index.php/ita/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true/(department)/1/(disable_pro_active)/true?r='+referrer+'&l='+location;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>

<?php if ($_GET['sez']=='mailinglist') { ?>
	<script src="http://cdn.tinymce.com/4/tinymce.min.js"></script>
	<script type="text/javascript">tinymce.init({ selector:'textarea'});</script>
<?php } ?>

	</head>
	<body <?php if ($_GET['sez']=="art_edit") { ?> onload="show_hide()" <?php } ?> >	
      
		<div class="page-header">
  			<div style="position: absolute; float: left; top: 10px;"><img src="http://www.mercusa.it/wp-content/uploads/2016/01/LOGO1.png" /> <i><b>Azienda:</b></i> <?php echo $nome_azienda; ?></div>
  			<div style="text-align: right; position: absolute; float: right; left: 80%; top: 10px;">
  			<?php
  			$data = date("d/m/Y");
  			$giorno_evento = date("d");
  			$mese_evento = date("m");
  			if (($mese_evento == "12")OR($mese_evento=="01")){
  				if (($mese_evento == "12") AND ($giorno_evento >= "08")){ ?>
  					<div><img src="img/natale.png" width="20%" heigth="20%" align="left" /></div>
  			<?php
  				}elseif (($mese_evento == "01") AND ($giorno_evento < "07")){ ?>
  					<img src="img/natale.png" width="20%" heigth="20%" align="left" />
  				<?php } 
  			} ?>
  			<span>
				Buon lavoro <b><i><?php echo $cod ?></i></b> ! 
				<a href="logout.php"><i class="glyphicon glyphicon-off"></i> Esci</a><br />
					Oggi &egrave; <b><?php echo $data; ?></b>
  			</span>
  			</div>
		</div>
		
		
		<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <table style="float: left; margin-right: 4px;"><tr><td>
    <a class="btn-xs btn-warning" href="index.php?loc=00&sez=vend_dett"><i class="glyphicon glyphicon-euro"></i> Vendita</a>
    </td></tr><tr><td>
    <a class="btn-xs btn-success" href="index.php?loc=02&sez=carico_multiplo"><i class="glyphicon glyphicon-plus"></i> Carico &nbsp;</a>    
    </td></tr></table>
    <div class="navbar-header">
      
      <ul class="nav nav-pills">
      	<li role="presentation" <?php if (($_GET['loc']=='00') OR ($_GET['loc']=='')) {
		  				echo 'class="active dropdown"'; 
		  				}else{
		  					echo'class="dropdown"';
		  				}
		  	  ?> 
		  	  >
		  <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-home"></i> Start <span class="caret"></span></a>
		  <ul class="dropdown-menu">
		  <li><a href="index.php?loc=00"><i class="glyphicon glyphicon-home"></i> Desktop</a></li>
		  <li class="dropdown-header pull-right">Link utili</li>
		  <li><a href="?loc=00&sez=vend_dett"><i class="glyphicon glyphicon-euro"></i> Nuova vendita</a></li>
		  <li><a href="?loc=02&sez=carico_multiplo"><i class="glyphicon glyphicon-plus"></i> Carica articoli</a></li>
		  <li><a href="?loc=04&sez=clienti_rimborsabili"><i class="glyphicon glyphicon-gbp"></i> Clienti da rimborsare</a></li>
		  <li><a href="?loc=00&sez=da_scontare"><i class="glyphicon glyphicon-time"></i> Riepilogo Sconti</a></li>
		  <li><a href="?loc=03&sez=arch_rimborsi"><i class="glyphicon glyphicon-piggy-bank"></i> Archivio rimborsi</a></li>
		  <?php if ($configModuloPunti=='yes'){ ?>
		  <li><a href="stampa_tessera.php" target="punti"><i class="glyphicon glyphicon-print"></i> Tessera punti</a></li>
		  <?php } ?>
		  </ul>
		  </li>
		  
		  
		  <li <?php if ($_GET['loc']=='01') {
		  				echo 'class="active dropdown"'; 
		  				}else{
		  					echo'class="dropdown"';
		  				}
		  	  ?> 
		  ><a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> Anagrafica Nominativi <span class="caret"></span></a>
		  <ul class="dropdown-menu">
		  <li><a href="?loc=01&sez=start"><i class="glyphicon glyphicon-home"></i> Anagrafe generale</a></li>
		  <li class="dropdown-header pull-right">Opzioni</li>
		  <li><a href="?loc=01&sez=cl_new"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a></li>
		  <li><a href="?loc=01&sez=cl_trash"><i class="glyphicon glyphicon-trash"></i> Il cestino</a></li>
		  <li><a href="?loc=01&sez=lista_nominativi"><i class="glyphicon glyphicon-th-list"></i> Lista completa nominativi</a></li>
		  </ul>
		  </li>
		  
		  <li <?php if ($_GET['loc']=='02') {
		  				echo 'class="active dropdown"'; 
		  				}else{
		  					echo'class="dropdown"';
		  				}
		  	  ?> 
		  ><a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-shopping-cart"></i> Gestione articoli <span class="caret"></span></a>
		  <ul class="dropdown-menu">
		  <li><a href="?loc=02&sez=start"><i class="glyphicon glyphicon-home"></i> Articoli e magazzino</a></li>
		  <li class="dropdown-header pull-right">Opzioni</li>
		  <li><a href="?loc=02&sez=carico_multiplo"><i class="glyphicon glyphicon-plus"></i> Carica articolo/i</a></li>
		  <li><a href="?loc=02&sez=art_trash"><i class="glyphicon glyphicon-lock"></i> Prenotati</a></li>
		  <li><a href="?loc=02&sez=ct_maga"><i class="glyphicon glyphicon-gift"></i> Magazzino Conto Terzi (CT)</a></li>
		  <li><a href="?loc=02&sez=cv_maga"><i class="glyphicon glyphicon-retweet"></i> Magazzino Conto Vendita (CV)</a></li>
		  <li><a href="?loc=02&sez=gest_homepage"><i class="glyphicon glyphicon-star"></i> Situazione home page</a></li>
		  </ul>
		  </li>
		  <li <?php if ($_GET['loc']=='03') {
		  				echo 'class="active dropdown"'; 
		  				}else{
		  					echo'class="dropdown"';
		  				}
		  	  ?> 
		  ><a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-folder-open"></i>  Documenti <span class="caret"></span></a>
		  <ul class="dropdown-menu">
		  <li><a href="?loc=03"><i class="glyphicon glyphicon-home"></i> Gestione doc.</a></li>
		  <li class="dropdown-header pull-right">Opzioni</li>
		  <li><a href="?loc=03&sez=arch_ricevute"><i class="glyphicon glyphicon-eur"></i> Gestione ricevute di vendita</a></li>
		  <li><a href="?loc=03&sez=arch_ricevute_carico"><i class="glyphicon glyphicon-shopping-cart"></i> Gestione ricevute di carico</a></li>
		  <li><a href="?loc=03&sez=arch_rimborsi"><i class="glyphicon glyphicon-piggy-bank"></i> Gestione doc. rimborso</a></li>
		  <li><a href="?loc=03&sez=gestione_resi"><i class="glyphicon glyphicon-retweet"></i> Gestione resi</a></li>
		  </ul>
		  </li>
		  
		  <li <?php if ($_GET['loc']=='04') {
		  				echo 'class="active dropdown"'; 
		  				}else{
		  					echo'class="dropdown"';
		  				}
		  	  ?> 
		  ><a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-euro"></i> Contabilit&agrave; <span class="caret"></span></a>
		  <ul class="dropdown-menu">
		  <li><a href="?loc=04"><i class="glyphicon glyphicon-home"></i> Tutta la contabilit&agrave;</a></li>
		  <li class="dropdown-header pull-right">Opzioni</li>
		  <li><a href="?loc=04&sez=cont_rimborsi"><i class="glyphicon glyphicon-piggy-bank"></i> Gestione rimborsi</a></li>
		  <li><a href="?loc=04&sez=lista_movimenti"><i class="glyphicon glyphicon-list-alt"></i> Lista movimenti</a></li>
		  <li><a href="?loc=04&sez=clienti_rimborsabili"><i class="glyphicon glyphicon-calendar"></i> Clienti da rimborsare</a></li>
		  <li><a href="?loc=04&sez=gestione_iva"><i class="glyphicon glyphicon-object-align-bottom"></i> Gestione IVA</a></li>
		  </ul>
		  </li>
		  
		  <li <?php if ($_GET['loc']=='05') {
		  				echo 'class="active dropdown"'; 
		  				}else{
		  					echo'class="dropdown"';
		  				}
		  	  ?> 
		  ><a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-th-large"></i> Extra <span class="caret"></span></a>
		  <ul class="dropdown-menu">
		  <li><a href="?loc=05"><i class="glyphicon glyphicon-home"></i> Strumenti Extra</a></li>
		  <li class="dropdown-header pull-right">Opzioni</li>
		  <li><a href="?loc=05&sez=promemoria"><i class="glyphicon glyphicon-time"></i> Promemoria</a></li>
		  <li><a href="?loc=05&sez=gest_mailinglist"><i class="glyphicon glyphicon-envelope"></i> Mailing list</a></li>
		  <li><a href="?loc=05&sez=settings"><i class="glyphicon glyphicon-wrench"></i> Impostazioni Sistema</a></li>
		  <?php if ($configModuloPunti=='yes'){ ?>
		  <li><a href="stampa_tessera.php"><i class="glyphicon glyphicon-print"></i> Genera tessera punti</a></li>
		  <?php } ?>
		  <li><a href="?loc=04&sez=calcolo_percentuale"><i class="glyphicon glyphicon-scale"></i> Calcolo percentuale rimborso</a></li>
		  </ul>
		  </li>
	  </ul>
      
    </div>
  </div><!-- /.container-fluid -->
</nav>
