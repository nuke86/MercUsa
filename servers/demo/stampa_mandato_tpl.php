<?php 
echo $headerStampeHTML;
echo $headerStampeDocumenti;
?>
<p align="center"><b>MANDATO DI VENDITA CON DEPOSITO</b> N.: <?php echo $campi_cliente["id"]; ?></p>

Io sottoscritto <b><?php echo $campi_cliente["nome"]; ?> <?php echo $campi_cliente["cognome"]; ?> (MANDANTE)</b>
<table border="1px" width="70%" align="center" style="font-size: 1.1em;">
<tr><td> Nato/a a </td><td> <?php echo $campi_cliente["citta_nascita"]; ?> </td><td> il <?php echo $campi_cliente["data_nascita"]; ?> </td></tr>
<tr><td> Residente a </td><td> <?php echo $campi_cliente["citta"]; ?> </td><td> <?php echo $campi_cliente["indirizzo"]; ?> </td></tr>
<tr><td> C.F. / P.I. </td><td> <?php echo $campi_cliente["cod_fiscale"]; ?> </td><td> <?php echo $documento; ?> N. <?php echo $campi_cliente["num_doc_id"]; ?> </td></tr>
<tr><td> Tel. / Cell </td><td> <?php echo $campi_cliente["telefono"]; ?> </td><td> <?php echo $campi_cliente["email"]; ?> </td></tr>
</table>
<p><b>
Do mandato di vendita a nome e per mio conto degli oggetti di mia propriet&agrave;, come da allegata 
"LISTA DEGLI OGGETTI RICEVUTI DAL MANDANTE", alla ditta sopra citata e di seguito denominata MANDATARIA, alle 
seguenti condizioni:</b>
</p>
<p>
<b>1) I beni restano di mia propriet&agrave;</b> fino alla loro vendita e permangono in esposizione gratuita presso 
il MANDATARIO per un periodo minimo di 90 giorni cos&igrave; articolati:<br />
<b>- esposizione gratuita di 60 giorni</b> (festivit&agrave; e ferie comprese) al prezzo stabilito al momento 
della consegna del bene + altri 30 giorni in cui <b>i beni verranno scontati del 30%</b>.<br />
Sono consapevole che, per gli oggetti ritirati prima dei 90 giorni, dovr&ograve; pagare al MANDATARIO una 
penale pari al 50% della provvigione che a lui sarebbe spettata in caso di vendita.<br />
<b>- trascorsi i 90 giorni (60+30)</b>, potr&ograve; decidere di ritirare gli oggetti ancora invenduti, pagando l'eventuale trasporto, se necessario, 
a mie spese. Diversamente, sono consapevole che il MANDATARIO applicher&agrave; uno sconto progressivo crescente, sino al miglior realizzo. 
Sollevo il mandatario dall'obbligo di comunicare 
l'esecuzione del mandato di vendita, n&egrave; le date e le scadenze dei tempi di esposizione in deroga all'art. 1712 C.C.<br />
<p>
2) &#9674; Do facolt&agrave; all'acquirente di <b>pagamento dilazionato</b> per i beni di importo pari o superiore ai 200.00 &euro;, in un massimo di n&deg; 3 rate (da corrispondersi una al mese), 
se e solo se la prenotazione del bene avverr&agrave; entro il 59esimo giorno dalla data di presa in carico, cio&egrave; prima che il bene stesso entri in sconto. <br />
-Decido di ricevere il rimborso a me spettante<br />
&#9674; <b>il 1&deg; giorno del mese successivo</b> all'estinzione della dilazione, per l'intero importo (pari al 50% del prezzo di vendita risultante dalla lista di carico)<br />
&#9674; <b>a cadenza mensile</b>, contestualmente al pagamento della singola rata (50% dell'importo rateale mensile corrisposto dall'acquirente).<br />
<b>IL COMPENSO</b> spettante al mandatario maturer&agrave; anche per TRANCHE nel momento in cui il prezzo pattuito sar&agrave; corrisposto dal compratore. Il mandatario sar&agrave; custode per mio conto 
dell'importo del prezzo pagato e quindi anche della mia quota di retrocessione (artt. 1775/1781 Cod. Civ.)
</p>
<p>
<b><u>3) Dopo la vendita, mi sar&agrave; corrisposto un importo pari al 50% del prezzo di vendita, al netto dell'IVA, come indicato nella 
"LISTA OGGETTI RICEVUTI". L'importo a me spettante sar&agrave; disponibile a partire dal 1&deg; giorno del 
mese successivo alla data di vendita.</u></b> Sono consapevole che dall'importo a me spettante verr&agrave; detratto un importo di &euro; _________, pari al costo 
del trasporto dei miei beni dalla mia abitazione alla sede de Il Ripostiglio, costo anticipato in nome e per mio conto dalla ditta mandataria.
</p>
<p>
<b>4) Ogni mio diritto di riscossione dei crediti decade trascorso un anno</b> dalla vendita degli stessi 
(art. 2964 C.C.)
</p>
<p>
<b>5) Il mandatario &egrave; autorizzato a pubblicare le foto dei beni di mia propriet&agrave; sulla vetrina del 
suo Sito Internet www.ilripostiglio.net</b> e su qualsiasi altro sito di annunci economici e ad 
effettuare la vendita tramite e-commerce.
</p>
<p>
<b>6) IL MANDATARIO, in qualunque momento, potr&agrave; apportare modifiche al presente mandato</b>, visibili presso 
il punto vendita o sul Sito Internet www.ilripostiglio.net, con evidenziata la data dell'ultima modifica.
</p>
<p>
<b>7) In caso di calamit&agrave; naturali</b>, che causassero il danneggiamento o la distruzione dei beni in conto 
vendita, il MANDATARIO &egrave; sollevato da qualsiasi responsabilit&agrave; e non &egrave; tenuto a rimborsare il danno.
</p>
<p>Confermo di aver letto il mandato in ogni sua parte e di averne ricevuto copia
<br />Quartu S. Elena, l&igrave; <u><?php echo date("d/m/Y"); ?></u> .</p>
Il Mandante __________________________
<br /><br />
<p>Ai sensi e per gli effetti di cui agli articoli 1341 e 1342 c.c. il sottoscritto dichiara di aver esaminato attentamente 
tutte le condizioni previste nel presente atto e di approvare specificatamente le clausole di cui ai punti 1, 2, 3, 4 , 5, 6, 7, 8 e 9.
Per conferma e specifica accettazione delle clausole su indicate.</p>
Il Mandante __________________________
<br /><br />
<p>
Ai sensi e per gli effetti del D.Lvo n. 196/2003 presto sin d'ora espresso consenso ed autorizzo il trattamento dei dati personali, 
in particolare, di quelli sensibili, per l'espletamento dell'incarico conferito nonch&egrave; dichiaro di esser stato informato ai sensi 
dell'art. 4, 3&deg; comma, del D.Lvo n. 28/2010, della possibilit&agrave; di ricorrere al procedimento di mediazione ivi previsto e dei benefici 
fiscali di cui agli artt. 17 e 20 del medesimo decreto.</p>
Il Mandante __________________________
<br />

Dati di accesso per l'Area Riservata su <b>www.ilripostiglio.net</b>:<br />
<b>Username:</b> <?php echo $campi_cliente["cod_fiscale"]; ?> <b>Password:</b> <?php echo $campi_cliente["password"]; ?>
</body>
</html>