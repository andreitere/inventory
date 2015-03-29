<?php



/**
 * Prints error messages to a (to be returned) JSON string and exits.
 * @param string $message 'message' field of JSON
 * @param string $type    'type'    field of JSON
 */
function print_json_error($message, $type = "unspecified") {

	echo '{ "status": "error", "type": "' . $type .
	'", "message": "' . $message . '"}';
    // this causes invalid json because it seems debug_print_backtrace()
    // inserts a newline at the end.
    //debug_print_backtrace();
    //echo '"}';
        log_to_file("[JSON ERROR]: $message", $type);

	exit();
}

function log_to_file($message, $type = 'unspecified', $echo = false, $stack = false) {
	
	$time = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
	$file = fopen("../logs/$date.txt", 'a+');
	
	$lines = "\n$time \n[$type] : $message \n"; 
	
    // theres a bug in here so i just commented it out (moriz)
	//if($stack) {
		//$lines .= "\t BACKTRACE: \n" . print_r($debug_backtrace(), true) . "\n";
	//}
		
	fwrite($file, $lines);
	
	fclose($file);
	
	if($echo) {
		echo $lines;
	}
	
}

function redirect($link) {
    session_write_close();
    session_regenerate_id(true);
    header("location: $link");
    exit();
  }
  