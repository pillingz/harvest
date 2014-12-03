<?php

function dbConnect() {
	
	$dbLink = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
	mysql_select_db('apro', $dbLink);
	mysql_query('SET NAMES utf8');
	mysql_query('SET CHARACTER utf8');
	
	return $dbLink;
}


function getSizeInterval($size) {
	
	$size += 0;
	
	if($size >= 0 && $size <= 25) $return = '0-25';
	elseif($size >= 25 && $size <= 35) $return = '25-35';
	elseif($size >= 35 && $size <= 50) $return = '35-50';
	elseif($size >= 50 && $size <= 60) $return = '50-60';
	elseif($size >= 60 && $size <= 70) $return = '60-70';
	elseif($size >= 70 && $size <= 80) $return = '70-80';
	elseif($size >= 80 && $size <= 90) $return = '80-90';
	elseif($size >= 90 && $size <= 100) $return = '90-100';
	elseif($size >= 100 && $size <= 125) $return = '100-125';
	elseif($size >= 125 && $size <= 150) $return = '125-150';
	elseif($size >= 150 && $size <= 175) $return = '150-175';
	elseif($size >= 175 && $size <= 200) $return = '175-200';
	elseif($size > 200) $return = '200-';
	
	return $return;
}


function getCountyFromSettlement($settlement) {
	
	$query = mysql_query("SELECT megye FROM apro_telepulesek WHERE telepules = '".$settlement."'");
	$data = mysql_fetch_assoc($query);
	
	if($data['megye']) $return = $data['megye'];
	else $return = false;
	
	return $return;
}


function setCron($name = '', $running = 1) {
	
	return false;
	
	if($name){
		
		$query = mysql_query("SELECT * FROM apro_cron WHERE name = '".$name."'");
		$data = mysql_fetch_assoc($query);
		if($data){
		
			//we dont't let to run over previous instance
			if($running && $data['running']){ 
				
				$query = mysql_query("SELECT NOW()");
				$now = mysql_fetch_row($query);
				$now = $now[0];
				
				//too much time elapsed from last start
				$diff = strtotime($now) - strtotime($data['start']);
				if($diff >= 1800) mysql_query("DELETE FROM apro_cron WHERE name = '".$data['name']."'");
				
				exit;
			}
			
			//start
			if($running){
				mysql_query("UPDATE apro_cron SET running = '".$running."', start = NOW(), end = '0000-00-00 00:00:00' WHERE name = '".$name."'");
			}
			//end
			else{
				mysql_query("UPDATE apro_cron SET running = '".$running."', end = NOW() WHERE name = '".$name."'");
			}
		}
		else{
			
			mysql_query("INSERT INTO apro_cron (name,running,start) VALUES ('".$name."','".$running."',NOW())");
		}
		
		$return = true;
	}
	else{
		
		$return = false;
	}
	
	return $return;
}

function romanToArabic($roman) {
	
	$romans = array(
		'M' => 1000,
		'CM' => 900,
		'D' => 500,
		'CD' => 400,
		'C' => 100,
		'XC' => 90,
		'L' => 50,
		'XL' => 40,
		'X' => 10,
		'IX' => 9,
		'V' => 5,
		'IV' => 4,
		'I' => 1,
	);

	$result = 0;

	foreach($romans as $key => $value){
		while(strpos($roman, $key) === 0){
			$result += $value;
			$roman = substr($roman, strlen($key));
		}
	}
	
	return $result;
}

?>