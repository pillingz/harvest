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

class threads_client {
    
    protected $options = null;
    protected $key = '';
    
    public function __construct() {
        
        $this->getThreads();
    }
    
    public function getThreads() {
        
		$res = mysql_query("SELECT * FROM apro_scheduler WHERE parse_status = '0' LIMIT 0,20");
        if(mysql_num_rows($res)){
            
			while($row = mysql_fetch_array($res)){
                
				mysql_query("UPDATE apro_scheduler SET parse_status = '1' WHERE id = '".$row['id']."'");
                
		$params = escapeshellarg($row['parse_url']);
                $params .= " '".$row['id']."' '".$row['forras']."' '".$row['tipus']."' '".$row['kategoria']."' '".$row['alkategoria']."' '".$row['varos']."' '".$row['kerulet']."' '".$row['param1']."' '".$row['param2']."' '".$row['param3']."'";

                exec("php ".$row['parse_script'].".php ".$params); //." > /var/www/thread-logs/".time()."_run.log"
                //$console .= "Scheduled link harvest started @ ".date("Y-m-d H:i")." URL: ".$row['parse_url']." | SCRIPT: ".$row['parse_script']." ".$params."\r\n";
            }
        }
        else{
            
			//$console = "Empty result set for scheduled threads...\r\n";
        }
        
        //$log_file = "thread-logs/".time()."_thr.log";
        //file_put_contents($log_file, $console, FILE_APPEND);
    }
}

//start threads
$threads = new threads_client();

mysql_close($dbLink);

?>
