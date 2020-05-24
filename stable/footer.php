
<div id="footer" class="container">
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="navbar-inner navbar-content-center">
            <p class="text-muted credit"> <a href="http://www.mercusa.it" target="labs">MercUsa</a> is Powered by <a href="http://www.spcnet.it/labs" target="labs">Spcnet - Labs</a>
            <a href="/mercusa/news.php" title="Tutti gli aggiornamenti del Programma" class="btn btn-default open"><i class="glyphicon glyphicon-globe"></i> MercUsa NEWS</a>

<a href="javascript:;" class="btn btn-default" onClick="window.open('http://95.110.226.234/mercusa/stable/chat-help.php', 'Bisogno di aiuto?', 'width=250, height=350, resizable, status, scrollbars=1, location');"><i class="glyphicon glyphicon-search"></i>Chat Help</a>           
		<span class="label label-default">
		Scadenza Abbonamento: 
		<?php
			$scadenza = date("d-m-Y", strtotime($scad_abb. ' + '.$tipo_abb.' days'));
			echo $scadenza;
		?></span>            
	</p>
        </div>
    </nav>
</div>
	</body>
</html>
