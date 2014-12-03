<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");
include_once("simple_html_dom.php");

//constants and variables
define('SITE_ID', 'INGATLANEGY');
define('SITE_PREFIX', 'ingatlanegy');

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
if(ereg('Sajnáljuk, de nincs a keresési feltételeknek megfelelő ingatlan az adatbázisunkban.', $html)){ 
	mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");
	exit;
}
	
$dom = str_get_html($html);
	
$hirdetesSum = 0;
$hirdetesek = $dom->find('div[class=listelem]');
foreach($hirdetesek as $hirdetes){
	
	//skip ad
	if(ereg('adsbygoogle', $hirdetes)) continue;
	
	$hirdetesSum++;
	
	$link = $hirdetes->find('div[class=datas]', 0)->find('a', 0)->href;
	if(!ereg('ingatlanegy.hu', $link)) $link = 'http://ingatlanegy.hu'.$link;
	
	$res = mysql_query("SELECT * FROM apro_hirdetesek_ingatlan WHERE hirdetesKod = '".$link."'");
	$data = mysql_fetch_assoc($res);
	if(!$data){
		
		if($rowp['varos'] != 'Budapest'){ 
			$varos = $hirdetes->find('div[class=datas]', 0)->innertext;
			$varos = explode('<br/>', $varos);
			$varos = explode(' ', $varos[1]);
			$varos = $varos[0];
		}
		else $varos = $rowp['varos'];
		
		//check settlement
		$res = mysql_query("SELECT id FROM apro_telepulesek WHERE telepules = '".$varos."'");
		if($varos == 'Budapest' || mysql_num_rows($res)){
			
			if($varos == 'Budapest') $megye = $rowp['kerulet'];
			else $megye = $varos;
			$megye = getCountyFromSettlement($megye);
			
			$cim = $hirdetes->find('div[class=datas]', 0)->find('div', 0)->find('span[class=value]', 0)->plaintext;
			$cim .= ' '.$varos;
			$temp = trim($hirdetes->find('div[class=datas]', 0)->find('a', 0)->plaintext);
			$cim .= ' '.$temp;
			
			$ar = $hirdetes->find('div[class=pprice]', 0)->innertext;
			$ar = explode('<br/>', $ar);
			$ar = explode(' ', $ar[0]);
			if($rowp['tipus'] == 'Eladó') $ar = $ar[0] * 1000000;
			else $ar = $ar[0] * 1000;
			
			$szoba = $hirdetes->find('div[class=datas]', 0)->innertext;
			$szoba = explode('Szobaszám:', $szoba);
			if(count($szoba) > 1){
				$szoba = explode('<span class="value">', $szoba[1]);
				$szoba = explode('</span>', $szoba[1]);
				$szoba = trim($szoba[0]);
			}
			else $szoba = '0';
			
			$kep = $hirdetes->find('div[class=pic]', 0)->find('img', 0)->src;
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