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

class scheduler_client {
    
    protected $options = null;
    protected $key = '';
    
    public function __construct() {
        
		$this->options = array(
			'location' => _SCHEDULER_URL.'scheduler.php',
			'harvest_id' => _HARVEST_ID,
			'harvest_key' => _HARVEST_KEY
		);
        
		$this->getScheduler();
    }
    
    public function getScheduler() {
        
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
						forras = '".$row['forras']."', 
						tipus = '".$row['tipus']."', 
						kategoria = '".$row['kategoria']."', 
						alkategoria = '".$row['alkategoria']."', 
						varos = '".$row['varos']."', 
						kerulet = '".$row['kerulet']."', 
						param1 = '".$row['param1']."', 
						param2 = '".$row['param2']."', 
						param3 = '".$row['param3']."'
				");
                
                //$console .= "Scheduled link inserted @ ".date("Y-m-d H:i")." URL: ".$row['url']." | SCRIPT: ".$row['script']."\r\n";
            }
            
            //$log_file = "scheduler-logs/".time()."_sch.log";
            //file_put_contents($log_file, $console, FILE_APPEND);
        }
    }
}

//start scheduler
$scheduler = new scheduler_client();

mysql_close($dbLink);

?>