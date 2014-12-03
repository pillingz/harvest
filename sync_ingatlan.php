<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");

//database connecting
$dbLink = dbConnect();

//constants and variables
$category = 'ingatlan';
$receiver = 'http://apro.hmcs.hu/server.php';
$target_table = 'apro_hirdetesek_'.$category;
$target_path = 'upload/'.$category.'/';
$running_file = 'sync_'.$category.'.run';

//check overrunning
if(is_file($running_file)){
    die("Sync alredy running in instance...\r\n");
}
else{
    file_put_contents($running_file, date());
}

//synchronize entities
$res = mysql_query("SELECT * FROM apro_hirdetesek_".$category." WHERE hirdetesSync = 0");
$count = mysql_num_rows($res);

echo "Start for ".$count." record...\r\n";

if($count){
    
	while($row = mysql_fetch_array($res)){
		
		if($row['hirdetesKep']){
			
            $params = array(
				'target_table' => $target_table, 
				'target_path' => $target_path, 
				'hirdetesKod' => $row['hirdetesKod'], 
				'hirdetesForras' => $row['hirdetesForras'], 
				'hirdetesTipus' => $row['hirdetesTipus'], 
				'hirdetesCim' => $row['hirdetesCim'], 
				'hirdetesDatum' => $row['hirdetesDatum'], 
				'hirdetesStatus' => $row['hirdetesStatus'], 
				'hirdetesKategoria' => $row['hirdetesKategoria'], 
				'hirdetesAlkategoria' => $row['hirdetesAlkategoria'], 
				'hirdetesMegye' => $row['hirdetesMegye'],
				'hirdetesVaros' => $row['hirdetesVaros'], 
				'hirdetesKerulet' => $row['hirdetesKerulet'], 
				'hirdetesParam1' => $row['hirdetesParam1'], 
				'hirdetesParam2' => $row['hirdetesParam2'], 
				'hirdetesParam3' => $row['hirdetesParam3'],
				'hirdetesParam4' => $row['hirdetesParam4'],
				'hirdetesParam5' => $row['hirdetesParam5'], 
				'hirdetesParam6' => $row['hirdetesParam6'], 
				'hirdetesParam7' => $row['hirdetesParam7'], 
				'hirdetesParam8' => $row['hirdetesParam8'], 
				'hirdetesParam9' => $row['hirdetesParam9'], 
				'hirdetesParam10' => $row['hirdetesParam10'], 
				'hirdetesParam11' => $row['hirdetesParam11'], 
				'hirdetesParam12' => $row['hirdetesParam12'], 
				'hirdetesParam13' => $row['hirdetesParam13'], 
				'hirdetesParam14' => $row['hirdetesParam14'], 
				'hirdetesParam15' => $row['hirdetesParam15'], 
				'hirdetesParam16' => $row['hirdetesParam16'], 
				'hirdetesParam17' => $row['hirdetesParam17'], 
				'hirdetesParam18' => $row['hirdetesParam18'], 
				'hirdetesParam19' => $row['hirdetesParam19'], 
				'hirdetesParam20' => $row['hirdetesParam20'], 
				'hirdetesParam21' => $row['hirdetesParam21'], 
				'hirdetesKep' => '@'.$row['hirdetesKep'], 
				'hirdetesText' => $row['hirdetesText'], 
				'hirdetesAr' => $row['hirdetesAr'], 
				'hirdetesHash' => $row['hirdetesHash']
            );
        }
        else{
            
			$params = array(
				'target_table' => $target_table, 
				'target_path' => $target_path, 
				'hirdetesKod' => $row['hirdetesKod'], 
				'hirdetesForras' => $row['hirdetesForras'], 
				'hirdetesTipus' => $row['hirdetesTipus'], 
				'hirdetesCim' => $row['hirdetesCim'], 
				'hirdetesDatum' => $row['hirdetesDatum'], 
				'hirdetesStatus' => $row['hirdetesStatus'], 
				'hirdetesKategoria' => $row['hirdetesKategoria'], 
				'hirdetesAlkategoria' => $row['hirdetesAlkategoria'],
				'hirdetesMegye' => $row['hirdetesMegye'],
				'hirdetesVaros' => $row['hirdetesVaros'], 
				'hirdetesKerulet' => $row['hirdetesKerulet'], 
				'hirdetesParam1' => $row['hirdetesParam1'], 
				'hirdetesParam2' => $row['hirdetesParam2'], 
				'hirdetesParam3' => $row['hirdetesParam3'],
				'hirdetesParam4' => $row['hirdetesParam4'],
				'hirdetesParam5' => $row['hirdetesParam5'], 
				'hirdetesParam6' => $row['hirdetesParam6'], 
				'hirdetesParam7' => $row['hirdetesParam7'], 
				'hirdetesParam8' => $row['hirdetesParam8'], 
				'hirdetesParam9' => $row['hirdetesParam9'], 
				'hirdetesParam10' => $row['hirdetesParam10'], 
				'hirdetesParam11' => $row['hirdetesParam11'], 
				'hirdetesParam12' => $row['hirdetesParam12'], 
				'hirdetesParam13' => $row['hirdetesParam13'], 
				'hirdetesParam14' => $row['hirdetesParam14'], 
				'hirdetesParam15' => $row['hirdetesParam15'], 
				'hirdetesParam16' => $row['hirdetesParam16'], 
				'hirdetesParam17' => $row['hirdetesParam17'], 
				'hirdetesParam18' => $row['hirdetesParam18'], 
				'hirdetesParam19' => $row['hirdetesParam19'], 
				'hirdetesParam20' => $row['hirdetesParam20'], 
				'hirdetesParam21' => $row['hirdetesParam21'], 
				'hirdetesText' => $row['hirdetesText'], 
				'hirdetesAr' => $row['hirdetesAr'], 
				'hirdetesHash' => $row['hirdetesHash']
            );
        }
        
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $receiver);
        curl_setopt($post, CURLOPT_POST, 1);
        curl_setopt($post, CURLOPT_POSTFIELDS, $params);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post);
		curl_close($post);
        
		$log_file_name = 'sync_'.$category.'_'.date('Y-m-d-H').'.log';
        file_put_contents('sync-logs/'.$log_file_name, $result, FILE_APPEND);
        echo $result." (for: ".$row['hirdetesKod'].")\r\n";
        
        mysql_query("UPDATE apro_hirdetesek_".$category." SET hirdetesSync = 1 WHERE hirdetesId = '".$row['hirdetesId']."'");
    }
}

unlink($running_file);

mysql_close($dbLink);

?>