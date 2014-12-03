<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");
include_once("simple_html_dom.php");

//constants and variables
define('SITE_ID', 'OC');
define('SITE_PREFIX', 'oc');

$transformAlkategoria = array(
	'Társasházi' => 'Lakás',
	'Családi ház' => 'Ház',
	'Ikerház' => 'Ház',
	'Sorház' => 'Ház',
	'Házrész' => 'Ház',
	'Panel' => 'Lakás',
	'Tanya' => 'Ház',
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
	
$ch = curl_init($rowp['url']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

//empty list
if(ereg('Jelenleg az adatbázisban nincsen a keresési feltételeknek megfelelő lakás.', $html)){ 
	mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");
	exit;
}

$dom = str_get_html($html);
	
$hirdetesSum = 0;
$hirdetesek = $dom->find('div[class=row]');
foreach($hirdetesek as $hirdetes){
	
	$hirdetesSum++;
	
	$link = $hirdetes->find('div[class=loc]', 0)->find('a', 0)->href;
	if(!ereg('oc.hu', $link)) $link = 'http://www.oc.hu'.$link;
	
	$res = mysql_query("SELECT * FROM apro_hirdetesek_ingatlan WHERE hirdetesKod = '".$link."'");
	$data = mysql_fetch_assoc($res);
	if(!$data){
		
		$cim = $hirdetes->find('div[class=loc]', 0)->find('a', 0)->plaintext;
		
		if($rowp['varos'] != 'Budapest'){ 
			$varos = explode(',', $cim);
			$varos = trim($varos[1]);
		}
		else $varos = $rowp['varos'];
		
		//check settlement
		$res = mysql_query("SELECT id FROM apro_telepulesek WHERE telepules = '".$varos."'");
		if($varos == 'Budapest' || mysql_num_rows($res)){
			
			if($varos == 'Budapest') $megye = $rowp['kerulet'];
			else $megye = $varos;
			$megye = getCountyFromSettlement($megye);
			
			$ar = $hirdetes->find('div[class=price]', 0)->find('b', 0)->plaintext;
			if(ereg('M Ft', $ar)){
				$ar = explode(' ', $ar);
				$ar = $ar[0] * 1000000;
			}
			else{
				$ar = preg_replace('/[^0-9]/', '', $ar);
			}
			
			$szoba = $hirdetes->find('div[class=detail]', 0)->find('ul', 0)->innertext;
			$szoba = explode('Szobák:', $szoba);
			if(count($szoba) > 1){
				$szoba = explode('</li>', $szoba[1]);
				$szoba = trim($szoba[0]);
				$szoba = explode('+', $szoba);
				$szoba = preg_replace('/[^0-9]/', '', $szoba[0]) + preg_replace('/[^0-9]/', '', $szoba[1]);
			}
			else $szoba = 0;
			
			if($rowp['alkategoria'] == 'Lakás'){	
				$alkategoria = $hirdetes->find('div[class=type]', 0)->plaintext;
				$alkategoria = $transformAlkategoria[trim($alkategoria)];
			}
			else{
				$alkategoria = $rowp['alkategoria'];
			}				
			
			$kep = $hirdetes->find('img', 0)->src;				
			if(!ereg('oc.hu', $kep)) $kep = 'http://www.oc.hu'.$kep;
			if($kep) {
				
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
					hirdetesAlkategoria = '".$alkategoria."', 
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

	if($hirdetesSum == 20) break;
}
	
//empty list
if(!$hirdetesSum) mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");

mysql_close($dbLink);

?>