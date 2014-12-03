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
$category = 'allat';
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
				'hirdetesVaros' => $row['hirdetesVaros'], 
				'hirdetesKerulet' => $row['hirdetesKerulet'], 
				'hirdetesParam1' => $row['hirdetesParam1'], 
				'hirdetesParam2' => $row['hirdetesParam2'], 
				'hirdetesParam3' => $row['hirdetesParam3'],
				'hirdetesParam4' => $row['hirdetesParam4'], 
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
				'hirdetesVaros' => $row['hirdetesVaros'], 
				'hirdetesKerulet' => $row['hirdetesKerulet'], 
				'hirdetesParam1' => $row['hirdetesParam1'], 
				'hirdetesParam2' => $row['hirdetesParam2'], 
				'hirdetesParam3' => $row['hirdetesParam3'],
				'hirdetesParam4' => $row['hirdetesParam4'], 
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