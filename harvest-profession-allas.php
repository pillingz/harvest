<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");
include_once("simple_html_dom.php");

//constants and variables
define('SITE_ID', 'PROFESSION');
define('SITE_PREFIX', 'profession');

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
//TODO

$dom = str_get_html($html);
	
$hirdetesSum = 0;
$hirdetesek = $dom->find('li[data-prof-id]');
foreach($hirdetesek as $hirdetes){
	
	$hirdetesSum++;
	
	$link = $hirdetes->find('h2', 0)->find('a', 0)->href;
	if(!ereg('profession.hu', $link)) $link = 'http://www.profession.hu'.$link;
	 
	$res = mysql_query("SELECT * FROM apro_hirdetesek_allas WHERE hirdetesKod = '".$link."'");
	$data = mysql_fetch_assoc($res);
	if(!$data){
		
		$cim = $hirdetes->find('strong', 0)->plaintext;
		
		$varos = $hirdetes->find('div[class=newarea]', 0)->find('span', 0)->plaintext;
		$varos = trim($varos);
		if(ereg('Budapest', $varos)){
			$kerulet = '';
			$temp = explode(',', $varos);
			foreach($temp as $t){
				if(ereg('kerület', $t)){
					$temp2 = explode('.', $t);
					$temp2 = str_replace('Budapest', '', $temp2[0]);
					$temp2 = trim($temp2);
					$kerulet = romanToArabic($temp2);
				}
			}
			$varos = 'Budapest';
			$megye = 'Budapest';
		}
		else{
			$varos = explode(',', $varos);
			$varos = $varos[0];
			$megye = getCountyFromSettlement($varos);
			$kerulet = '';
		}
		
		$res = mysql_query("SELECT id FROM apro_telepulesek WHERE telepules = '".$varos."'");
		if($varos == 'Budapest' || mysql_num_rows($res)){
		
			mysql_query("
				INSERT INTO apro_hirdetesek_allas SET 
					hirdetesKod	= '".$link."', 
					hirdetesForras = '".$rowp['forras']."', 
					hirdetesTipus = '', 
					hirdetesCim = '".mysql_real_escape_string($cim)."', 
					hirdetesStatus = '1', 
					hirdetesKategoria = '".$rowp['kategoria']."', 
					hirdetesAlkategoria = '".$rowp['alkategoria']."', 
					hirdetesMegye = '".$megye."',
					hirdetesVaros = '".$varos."',
					hirdetesKerulet = '".$kerulet."'
			");
		}
		
		$log = "Row inserted: #".mysql_insert_id()." (".$link.")\r\n";
	}
	else{
		
		$log = "Alredy in database (".$link.")...\r\n";
	}
	
	echo $log;
	
	file_put_contents('harvest-logs/'.SITE_ID.'.log', $log, FILE_APPEND);
}
	
//empty list
if(!$hirdetesSum) mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");

mysql_close($dbLink);

?>