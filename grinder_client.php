<?php

//debug
//exit;

//sets
session_start();

//includes
include_once("config/config.php");
include_once("functions.php");

//database connecting
$dbLink = dbConnect();

class grinder_client {
    
    protected $options = null;
    protected $key = '';
    
    public function __construct() {
        
		$this->options = array(
			'location' => _SCHEDULER_URL.'grinder.php',
			'harvest_id' => _HARVEST_ID,
			'harvest_key' => _HARVEST_KEY
		);
        
		$this->getGrinder();
    }
    
    public function getGrinder() {
		
		$result = file_get_contents($this->options['location']."?harvest_id=".$this->options['harvest_id']."&harvest_key=".$this->options['harvest_key']);
		$result = unserialize($result);
        
        if(is_array($result)){
            
			foreach($result as $row){
                
				mysql_query("
					INSERT INTO apro_scheduler SET 
						parse_url = '".$row['url']."', 
						parse_script = '".$row['script']."', 
						parse_status = '0', 
						parse_sid = '".$this->key."', 
						forras = '".$row['hirdetesForras']."', 
						tipus = '".$row['hirdetesTipus']."', 
						kategoria = '".$row['hirdetesKategoria']."', 
						alkategoria = '".$row['hirdetesAlkategoria']."', 
						varos = '".$row['hirdetesVaros']."', 
						kerulet = '".$row['hirdetesKerulet']."', 
						param1 = '".$row['hirdetesParam1']."', 
						param2 = '".$row['hirdetesParam2']."', 
						param3 = '".$row['hirdetesParam3']."'
				");
                
                //$console .= "Ginder link inserted @ ".date("Y-m-d H:i")." URL: ".$row['url']." | SCRIPT: ".$row['script']."\r\n";
            }
            
            //$log_file = "scheduler-logs/".time()."_ginder.log";
            //file_put_contents($log_file, $console, FILE_APPEND);
        }
    }
}

//start scheduler
$grinder = new grinder_client();

mysql_close($dbLink);

?>
