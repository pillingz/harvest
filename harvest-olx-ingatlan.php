<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");
include_once("simple_html_dom.php");

//constants and variables
define('SITE_ID', 'OLX');
define('SITE_PREFIX', 'olx');

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

//cut off recommendations
$html = explode('<td class="recommended-distance-with-ads">', $html);
$html = $html[0];

//empty list
if(ereg('Nem található!</h1>', $html) || ereg('Az OLX.hu nem találja a keresett oldalt', $html)){ 
	mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");
	exit;
}

$dom = str_get_html($html);
	
$hirdetesSum = 0;
$hirdetesek = $dom->find('td[class=offer]');
foreach($hirdetesek as $hirdetes){
	
	$hirdetesSum++;
	
	$link = $hirdetes->find('a[class=link]', 0)->href;
	if(!ereg('olx.hu', $link)) $link = 'http://olx.hu'.$link;
	$link = explode('#', $link);
	$link = $link[0];
	
	$res = mysql_query("SELECT * FROM apro_hirdetesek_ingatlan WHERE hirdetesKod = '".$link."'");
	$data = mysql_fetch_assoc($res);
	if(!$data){
		
		$cim = $hirdetes->find('a[class=link]', 0)->find('span', 0)->plaintext;
		
		if($rowp['varos'] != 'Budapest'){ 
			$varos = $hirdetes->find('small[class=breadcrumb]', 0)->find('span', 0)->plaintext;
			$varos = trim($varos);
		}
		else $varos = $rowp['varos'];
		
		//check settlement
		$res = mysql_query("SELECT id FROM apro_telepulesek WHERE telepules = '".$varos."'");
		if($varos == 'Budapest' || mysql_num_rows($res)){
		
			if($varos == 'Budapest') $megye = $rowp['kerulet'];
			else $megye = $varos;
			$megye = getCountyFromSettlement($megye);
		
			$ar = $hirdetes->find('p[class=price]', 0)->find('strong', 0)->plaintext;
			$ar = preg_replace('/[^0-9]/', '', $ar);
			
			$kep = $hirdetes->find('img[class=fleft]', 0)->src;
			if($kep){
				
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
					hirdetesParam1 = '".$rowp['param1']."', 
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
		
		//update rooms
		if(!$data['hirdetesParam1'] && $rowp['param1']){
			mysql_query("
				UPDATE apro_hirdetesek_ingatlan SET
					hirdetesParam1 = '".$rowp['param1']."',
					hirdetesSync = 0
				WHERE id = '".$data['id']."'
			");
		}
		
		//update size
		if(!$data['hirdetesParam2'] && $rowp['param2']){
			mysql_query("
				UPDATE apro_hirdetesek_ingatlan SET
					hirdetesParam2 = '".$rowp['param2']."',
					hirdetesSync = 0
				WHERE id = '".$data['id']."'
			");
		}
		
		$log = "Alredy in database<br>(".$link.")...\r\n";
	}
	
	echo $log;
	
	file_put_contents('harvest-logs/'.SITE_ID.'.log', $log, FILE_APPEND);
}
	
//empty list
if(!$hirdetesSum) mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");

mysql_close($dbLink);

?>