<?php
// con questo controllo l'url e ne prendo solo il nome azienda

$r = $_SERVER['REQUEST_URI']; 
$r = explode('/', $r);
$r = array_filter($r);
$r = array_merge($r, array()); 
$endofurl = $r[2];

include_once '../config/config-'.$endofurl.'.php';

include_once 'query_start.php';
include_once 'query_articoli.php';
include_once 'query_clienti.php';
include_once 'log_system.php';


?>