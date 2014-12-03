<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");
include_once("simple_html_dom.php");

//constants and variables
define('SITE_ID', 'JOFOGAS');
define('SITE_PREFIX', 'jofogas');

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
if(ereg('<p>Nincs találat, ', $html)){ 
	mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");
	exit;
}

//cut off advertisement
$from = '
	<div id="adverticum_inter_list_container" class="listing-row">
		<div id="adverticum_inter_list" class="goAdverticum"></div>
	</div>
';
$from = trim($from);
$to = '';
$html = str_replace($from, $to, $html);
	
$dom = str_get_html($html);

$hirdetesSum = 0;
$hirdetesek = $dom->find('div[class=listing-row]');
foreach($hirdetesek as $hirdetes){
	
	$hirdetesSum++;
	
	$link = $hirdetes->find('a[class=listing-ad-link]', 0)->href;
	if(!ereg('jofogas.hu', $link)) $link = 'http://www.jofogas.hu'.$link;
	
	$res = mysql_query("SELECT * FROM apro_hirdetesek_mezogazdasag WHERE hirdetesKod = '".$link."'");
	$data = mysql_fetch_assoc($res);
	if(!$data){
    
		if($rowp['varos'] != 'Budapest'){ 
			$varos = $hirdetes->find('div[class=clean_links]', 0)->innertext;
			$varos = explode("<br/>", $varos);
			$varos = trim($varos[1]);
			$varos = explode('<', $varos);
			$varos = trim($varos[0]);
		}
		else $varos = $rowp['varos'];
		$varos = explode(',', $varos);
		$varos = trim($varos[0]);
		$varos = utf8_encode($varos);
		
		if(ereg('kerület', $varos)){
			$kerulet = explode('.', $varos);
			$kerulet = romanToArabic($kerulet[0]);
			$megye = 'Budapest';
			$varos = 'Budapest';
		}
		else{
			$megye = getCountyFromSettlement($varos);
			$kerulet = '';
		}
		
		//check settlement
		$res = mysql_query("SELECT id FROM apro_telepulesek WHERE telepules = '".$varos."'");
		if($varos == 'Budapest' || mysql_num_rows($res)){
		
			$cim = $hirdetes->find('div[class=thumbs_subject]', 0)->innertext;
			$cim = explode('<', $cim);
			$cim = trim($cim[0]);
			$cim = utf8_encode($cim);
			
			$ar = $hirdetes->find('span[class=price]', 0)->plaintext;
			$ar = preg_replace('/[^0-9]/', '', $ar);
			
			$kep = $hirdetes->find('div[class=img-counter]', 0)->find('img', 0)->src;
			if($kep && !ereg('image-placeholder.png', $kep)){
				
				$temp = explode('/', $kep);
				$filename = $temp[count($temp)-1];
				$fname = "uploads/mezogazdasag/".SITE_PREFIX."/".$filename;
				$filename = "uploads/mezogazdasag/".SITE_PREFIX."/".$filename;
				
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
				INSERT INTO apro_hirdetesek_mezogazdasag SET 
					hirdetesKod	= '".$link."', 
					hirdetesForras = '".$rowp['forras']."', 
					hirdetesTipus = '".$rowp['tipus']."', 
					hirdetesCim = '".mysql_real_escape_string($cim)."', 
					hirdetesStatus = '1', 
					hirdetesKategoria = '".$rowp['kategoria']."', 
					hirdetesAlkategoria = '".$rowp['alkategoria']."', 
					hirdetesMegye = '".$megye."',
					hirdetesVaros = '".$varos."', 
					hirdetesKerulet = '".$kerulet."', 
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
		
		$log = "Alredy in database<br>(".$link.")...\r\n";
	}
	
	echo $log;
	
	file_put_contents('harvest-logs/'.SITE_ID.'.log', $log, FILE_APPEND);
}
	
//empty list
if(!$hirdetesSum) mysql_query("UPDATE apro_scheduler SET status = '2' WHERE id = '".$rowp['id']."'");

mysql_close($dbLink);

?>