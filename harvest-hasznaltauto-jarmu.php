<?php
 
/*
* A 100-as és 50-s lista html hibás, így a simple html dom nem tudja feldolgozni a forrást. Az 20-es lista nem hibás, így jelenleg azt dolgozza fel.
*/ 
 
//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");
include_once("simple_html_dom.php");

//constants and variables
define('SITE_ID', 'HASZNALTAUTO');
define('SITE_PREFIX', 'hasznaltauto');

$fuelTypes = array(
	'Benzin',
	'Benzin/Gáz',
	'CNG',
	'LPG',
	'Biodízel',
	'Dízel',
	'Elektromos',
	'Etanol',
	'Hibrid',
	'Hibrid (Benzin)',
	'Hibrid (Dízel)',
); 

//database connecting
$dbLink = dbConnect();

//get parameters
$rowp['url'] = $argv[1];
$rowp['id'] = $argv[2];
$rowp['forras'] = $argv[3];
$rowp['tipus'] = $argv[4];
$rowp['kategoria'] = $argv[5];
$rowp['alkategoria'] = $argv[6];
$rowp['varos'] = $argv[7];
$rowp['kerulet'] = $argv[8];
$rowp['param1'] = $argv[9];
$rowp['param2'] = $argv[10];
$rowp['param3'] = $argv[11];
	
$log  = "*****************************************************************\r\n";
$log .= "Parse link: ".$rowp['url']."\r\n";
$log .= "*****************************************************************\r\n";
file_put_contents('harvest-logs/'.SITE_ID.'.log', $log, FILE_APPEND);
    
//first request
$url = explode('/', $rowp['url']);
if(ereg('page', $url[count($url)-1])){
	$page = $url[count($url)-1];
	unset($url[count($url)-1]);
}
$url = implode('/', $url);
if($page == 1) $page = false;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

//get data for big list
$data = explode('<input type="hidden" name="data" value="', $html);
$data = explode('"', $data[1]);
$data = trim($data[0]);

$kereso = explode('<input type="hidden" name="kereso" value="', $html);
$kereso = explode('"', $kereso[1]);
$kereso = trim($kereso[0]);

$limit = 1;

$results = 20;

//request for big list
$ch = curl_init('http://www.hasznaltauto.hu/szukites');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.$data.'&kereso='.$kereso.'&limit='.$limit.'&results='.$results);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

//paging on list
if($page){

	//get data for big list
	$data = explode('<input type="hidden" name="data" value="', $html);
	$data = explode('"', $data[1]);
	$data = trim($data[0]);
	
	$url = 'http://www.hasznaltauto.hu/talalatilista/auto/'.$data.'/'.$page;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$html = curl_exec($ch);
	curl_close($ch);
}
	
//empty list
//TODO

$dom = str_get_html($html);

$hirdetesSum = 0;
$hirdetesek = $dom->find('div[class=talalati_lista]');
foreach($hirdetesek as $hirdetes){
	
	$hirdetesSum++;
	
	$link = $hirdetes->find('h2', 0)->find('a', 0)->href;
	if(!ereg('hasznaltauto.hu', $link)) $link = 'http://www.hasznaltauto.hu'.$link;
	
	$res = mysql_query("SELECT * FROM apro_hirdetesek_jarmu WHERE hirdetesKod = '".$link."'");
	if(mysql_num_rows($res) == 0){
		
		$cim = $hirdetes->find('h2', 0)->find('a', 0)->plaintext;
		
		/* Nem nyerhető ki egyértelműen pl ennél: MERCEDES-BENZ E 220 D Elegance
		$param2 = explode(' ', trim($cim));
		$param2 = trim($param2[1]);
		*/
		
		$param2 = $rowp['param2'];
		
		$ar = $hirdetes->find('div[class=arsor]', 0)->find('strong', 0)->plaintext;
		$ar = preg_replace('/[^0-9]/', '', $ar);
		
		$infosor = $hirdetes->find('div[class="talalati_lista_infosor"]', 0)->plaintext;
		$infosor = explode('&middot;', $infosor);
		
		$evjarat = trim(str_replace('&nbsp;', '', $infosor[1]));
		$evjarat = explode('/', $evjarat);
		$evjarat = trim($evjarat[0]);
		
		$uzemanyag = trim(str_replace('&nbsp;', '', $infosor[2]));
		if(!in_array($uzemanyag, $fuelTypes)) $uzemanyag = '';
		
		$hengerurtartalom = '';
		if(ereg('cm', $infosor[3])){
			$hengerurtartalom = trim(str_replace('&nbsp;', '', $infosor[3]));
			$hengerurtartalom = explode(' ', $hengerurtartalom);
			$hengerurtartalom = trim($hengerurtartalom[0]);
		}
		
		$futott_km = '';
		if(ereg('km', $infosor[count($infosor)-1])){
			$futott_km = $infosor[count($infosor)-1];
			$futott_km = preg_replace('/[^0-9]/', '', $futott_km);
		}
		
		$hash = array();
		$temp = $hirdetes->find('span[class=cimke-szurke-szurke]');
		foreach($temp as $t) $hash[] = trim($t->plaintext);
		$hash = "#".implode('#', $hash)."#";
		
		$kep = $hirdetes->find('div[class=talalati_lista_kep]', 0)->find('img', 0)->src;
		if($kep){
			
			$temp = explode('/', $kep);
			$filename = $temp[count($temp)-1];
			$fname = "uploads/jarmu/".SITE_PREFIX."/".$filename;
			$filename = "uploads/jarmu/".SITE_PREFIX."/".$filename;
			
			$ch3 = curl_init($kep);
			$fp = fopen($fname, 'wb');
			curl_setopt($ch3, CURLOPT_FILE, $fp);
			curl_setopt($ch3, CURLOPT_HEADER, 0);
			curl_exec($ch3);
			curl_close($ch3);
			fclose($fp);
		}
		else{
			
			$filename = "";
		}
		
		mysql_query("
			INSERT INTO apro_hirdetesek_jarmu SET 
				hirdetesKod = '".$link."', 
				hirdetesForras = '".$rowp['forras']."', 
				hirdetesTipus = '".$rowp['tipus']."', 
				hirdetesCim = '".mysql_real_escape_string($cim)."', 
				hirdetesStatus = '1', 
				hirdetesKategoria = '".$rowp['kategoria']."', 
				hirdetesAlkategoria = '".$rowp['alkategoria']."', 
				hirdetesVaros = '',
				hirdetesKerulet = '', 
				hirdetesParam1 = '".$rowp['param1']."', 
				hirdetesParam2 = '".$param2."', 
				hirdetesParam3 = '".$uzemanyag."',
				hirdetesParam4 = '".$evjarat."',
				hirdetesParam5 = '".$hengerurtartalom."',					
				hirdetesParam6 = '".$futott_km."',
				hirdetesKep = '".$filename."', 
				hirdetesAr = '".$ar."',
				hirdetesHash = '".mysql_real_escape_string($hash)."'
		");
		
		$log = "Row inserted: #".mysql_insert_id()." (".$link.")\r\n";
	}
	else{
		
		//update fuel type
		if(!$data['hirdetesParam3'] && $rowp['param3']){
			mysql_query("
				UPDATE apro_hirdetesek_jarmu SET
					hirdetesParam3 = '".$rowp['param3']."',
					hirdetesSync = 0
				WHERE id = '".$data['id']."'
			");
		}
		
		//update year
		if(!$data['hirdetesParam4'] && $rowp['param4']){
			mysql_query("
				UPDATE apro_hirdetesek_jarmu SET
					hirdetesParam4 = '".$rowp['param4']."',
					hirdetesSync = 0
				WHERE id = '".$data['id']."'
			");
		}
		
		//update ccm
		if(!$data['hirdetesParam5'] && $rowp['param5']){
			mysql_query("
				UPDATE apro_hirdetesek_jarmu SET
					hirdetesParam5 = '".$rowp['param5']."',
					hirdetesSync = 0
				WHERE id = '".$data['id']."'
			");
		}
		
		//update kms
		if(!$data['hirdetesParam6'] && $rowp['param6']){
			mysql_query("
				UPDATE apro_hirdetesek_jarmu SET
					hirdetesParam6 = '".$rowp['param6']."',
					hirdetesSync = 0
				WHERE id = '".$data['id']."'
			");
		}
		
		$log = "Alredy in database (".$link.")...\r\n";
	}
	
	echo $log;
	
	file_put_contents('harvest-logs/'.SITE_ID.'.log', $log, FILE_APPEND);
}

if(!$hirdetesSum) mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");

mysql_close($dbLink);

?>