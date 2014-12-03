<?php

//aaa

include('config/config.php');

$dbLink = dbConnect();

//kategóriák szinkronra jelölése
mysql_query("UPDATE apro_hirdetesek_allas SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_allat SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_baba SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_divat SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_gyujtemeny SET hirdetesSync = 0");
//mysql_query("UPDATE apro_hirdetesek_ingatlan SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_ingyen SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_jarmu SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_jatek SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_kert SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_konyv SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_mezogazdasag SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_sport SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_szolgaltatas SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_tech SET hirdetesSync = 0");
mysql_query("UPDATE apro_hirdetesek_zene SET hirdetesSync = 0");

/*
//az "uploads" mappastruktúrájának kialakítása
$uploadDir = array(
	'allat' => array('jofogas','olx'),
	'baba' => array('jofogas','olx'),
	'divat' => array('jofogas','olx'),
	'gyujtemeny' => array('jofogas','olx'),
	'ingatlan' => array('dh','ingatlanbazar','ingatlancom','ingatlanegy','ingatlanka','ingatlanok','ingatlantajolo','jofogas','oc','olx'),
	'ingyen' => array('jofogas','olx'),
	'jarmu' => array('hasznaltauto','jofogas','olx'),
	'jatek' => array('jofogas','olx'),
	'kert' => array('jofogas','olx'),
	'konyv' => array('jofogas','olx'),
	'mezogazdasag' => array('jofogas','olx'),
	'sport' => array('jofogas','olx'),
	'szolgaltatas' => array('jofogas','olx'),
	'tech' => array('jofogas','olx'),
	'zene' => array('jofogas','olx'),
);

foreach($uploadDir as $mainDir => $subDirs){
	
	$dir = ABSPATH.'uploads/'.$mainDir;
	if(!is_dir($dir)) mkdir($dir, 0777);
	
	foreach($subDirs as $subDir){
		
		$dir = ABSPATH.'uploads/'.$mainDir.'/'.$subDir;
		if(!is_dir($dir)) mkdir($dir, 0777);
	}
}
*/

?>