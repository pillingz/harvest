<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");
include_once("simple_html_dom.php");

//constants and variables
define('SITE_ID', 'INGATLANTAJOLO');
define('SITE_PREFIX', 'ingatlantajolo');

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
	
$ch = curl_init($rowp['url']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

//empty list
if(ereg('<span class="search-count">0</span>', $html)){ 
	mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");
	exit;
}

$dom = str_get_html($html);
	
$hirdetesSum = 0;
$hirdetesek = $dom->find('div[class=property]');
foreach($hirdetesek as $hirdetes){
	
	//skip ad
	if(ereg('adsbygoogle', $hirdetes)) continue;
 	
	//skip empty element
	if(!$hirdetes->plaintext) continue;
	
	$hirdetesSum++;
	
	$link = $hirdetes->find('a', 0)->href;
	if(!ereg('ingatlantajolo.hu', $link)) $link = 'http://www.ingatlantajolo.hu'.$link;
	$link = explode('#', $link);
	$link = $link[0];
	
	$res = mysql_query("SELECT * FROM apro_hirdetesek_ingatlan WHERE hirdetesKod = '".$link."'");
	$data = mysql_fetch_assoc($res);
	if(!$data){
		
		$cim = $hirdetes->find('h3', 0)->plaintext;
		
		if($rowp['varos'] != 'Budapest'){ 
			$temp = $hirdetes->find('header', 0)->find('figure', 0)->plaintext;
			$temp = explode(',', $temp);
			foreach($temp as $t){
				$res = mysql_query("SELECT telepules FROM apro_telepulesek WHERE telepules = '".trim($t)."'");
				$varos = mysql_fetch_assoc($res);
				if($varos['telepules']){
					$varos = $varos['telepules'];
					break;
				}
				else{
					$varos = '';
				}
			}
		}
		else $varos = $rowp['varos'];
		
		//check settlement
		$res = mysql_query("SELECT id FROM apro_telepulesek WHERE telepules = '".$varos."'");
		if($varos == 'Budapest' || mysql_num_rows($res)){
			
			if($varos == 'Budapest') $megye = $rowp['kerulet'];
			else $megye = $varos;
			$megye = getCountyFromSettlement($megye);
			
			$ar = $hirdetes->find('div[class=price]', 0)->plaintext;
			$ar = preg_replace('/[^0-9]/', '', $ar);
			
			$szoba = $hirdetes->find('aside', 0)->innertext;
			$szoba = explode('<dt>Szobák száma</dt><dd>', $szoba);
			if(count($szoba) > 1){
				$szoba = explode('</dd>', $szoba[1]);
				$szoba = trim($szoba[0]);
				if(ereg('\+', $szoba)){
					$szoba = explode(' ', $szoba);
					$szoba = trim($szoba[0]) + trim($szoba[2]);
				}
			}
			else $szoba = 0;
			
			$kep = $hirdetes->find('div[class=property-image]', 0)->find('img', 0)->src;
			if($kep && ereg('ingatlantajolo.hu', $kep)) {
				
				$temp = explode('/', $kep);
				$filename = $temp[count($temp)-1];
				$fname = "uploads/ingatlan/".SITE_PREFIX."/".$filename;
				$filename = "uploads/ingatlan/".SITE_PREFIX."/".$filename;
				
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
				INSERT INTO apro_hirdetesek_ingatlan SET 
					hirdetesKod	= '".$link."', 
					hirdetesForras = '".$rowp['forras']."', 
					hirdetesTipus = '".$rowp['tipus']."', 
					hirdetesCim = '".mysql_real_escape_string($cim)."', 
					hirdetesStatus = '1', 
					hirdetesKategoria = '".$rowp['kategoria']."', 
					hirdetesAlkategoria = '".$rowp['alkategoria']."', 
					hirdetesMegye = '".$megye."',
					hirdetesVaros = '".$varos."', 
					hirdetesKerulet = '".$rowp['kerulet']."', 
					hirdetesParam1 = '".$szoba."', 
					hirdetesParam2 = '".$rowp['param2']."', 
					hirdetesKep = '".$filename."', 
					hirdetesAr = '".$ar."'
			");
			
			$log = "Row inserted: #".mysql_insert_id()." (".$link.")\r\n";
		}
		else{
			
			$log = "Wrong settlement: ".$varos." (".$link.")\r\n";
		}
	}
	else{
		
		$log = "Alredy in database<br>(".$link.")...\r\n";
	}
	
	echo $log;
	
	file_put_contents('harvest-logs/'.SITE_ID.'.log', $log, FILE_APPEND);
}
	
//empty list
if(!$hirdetesSum) mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");

mysql_close($dbLink);

?>