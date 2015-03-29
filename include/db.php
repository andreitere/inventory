<?php


require_once("functions.php");

$_db_handle = null;

function db_connect() {
	global $_db_handle;
	
	$db_user = "root";
	$db_password = "";
	$db_name = "inventar_lse";
	$db_host = "127.0.0.1";
	
	try {
		$DBH = new PDO ( "mysql:host=$db_host;dbname=$db_name", $db_user, $db_password );
	} catch ( PDOException $e ) {
		print_json_error( $e->getMessage(), "database");
	}
	$_db_handle = $DBH;
}


/**
 * 
 * Prepare, execute and returns a statement. Intermediate function
 * 
 * @param string $query The SQL query string.
 * @param mixed $argument Universal argument to be parsed and given to PDO::execute(.. )
 * @return PDOStatement The PDO statement handle
 */
function db_get_statement_handle($query, $argument = null) {
	global $_db_handle;
	
	if(!$_db_handle) {
		db_connect();
	}
	
	$STH = $_db_handle->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	
	if($argument===null) {
		
		// No argument given, execute it right away.
		$STH->execute();
		
	} else {
		
		if(is_array($argument)) {
			
			// Argument is array, pass it to the ->execute(..) function.
			$STH->execute($argument);
			
		} else {
			
			// The argument should be a single element, pass it as an array of one element.
			$STH->execute(array($argument));
		}
	}
	return $STH;
}

 
/**
 *  Runs a database query. 
 *  Returns the first fetched argument.
 * @param string $q The SQL query
 * @param mixed $argument The prepared statement argument/argument array
 * @return mixed
 */
function db_query($q, $argument = null) {
	$STH = db_get_statement_handle($q, $argument);
	try {
		$result = $STH->fetch();
	} catch ( PDOException $e ) {
		print_json_error( $e->getMessage(), "database");
	}
	return $result;
}


/**
 * 
 *  Runs queries that cannot be fetched afterwards, like INSERT, ALTER or UPDATE. 
 *  Returns row count.
 * @param string $q The SQL query
 * @param mixed $argument The prepared statement argument/argument array
 * @return number Row count
 */
function db_insert($q, $argument) {
	$STH = db_get_statement_handle($q, $argument);
	return $STH->rowCount();
}

function db_execute($q, $argument) {
	$STH = db_get_statement_handle($q, $argument);
	return $STH->rowCount();
}

function db_update_named(string $table, array $columns_set, array $columns_where, array $arguments) {
    $set_lines = array();
    
    foreach($columns_set as $column) {
        $set_lines[] = $column . " = " . ":$column";        
    }
    $set_text = implode(", ", $set_lines);
    
    
    $where_lines = array();
    foreach($columns_where as $column) {
        $where_lines[] = $column . " = " . ":$column";        
    }
    
    $where_text = implode(", ", $where_lines);
    
    $q = "UPDATE $table SET $set_text WHERE $where_text";
    
    $arguments_used = array();
    foreach(array_merge( $columns_set , $columns_where ) as $col) {
        $arguments_used[$col] = $arguments[$col];
    }
    return db_execute($q, $arguments_used);
    // UPDATE $TABELA SET a = b, c = d, e = f WHERE id = $id
}


/**
 * 
 *  Runs insert query on a given table, into given columns, with an associative array of arguments.
 *  The keys of $arguments must be in $columns.
 * 
 * @param string $table The table name to be inserted into
 * @param array $columns A list of the columns that should be inserted. These must be keys of the $arguments associative array.
 * @param assoc $arguments Associative array with the data: array('column' => 'value', ...)
 * @return number Row count
 */
function db_insert_named($table, $columns, $arguments) {
	$column_placeholders = array();
	$arg_assoc = array();
	foreach($columns as $col) {
		$column_placeholders[] = ':' . $col;
		$arg_assoc[$col] = $arguments[$col];
	}

	$str_columns = implode(', ', $columns);
	$str_placeholders = implode(', ', $column_placeholders);
	$q = "INSERT INTO $table ($str_columns) VALUES ($str_placeholders)";
    //echo "\n" .$q . "\n";
    //print_r($arg_assoc); echo "\n";
	return db_insert($q, $arg_assoc);
}


/**
 *  Runs a query that could return more than one row. 
 *
 * @param string $q_str The SQL query
 * @param mixed $argument The prepared statement argument / argument array
 * @return multitype:mixed Array of associative arrays containing results.
 */
function db_query_array($q_str, $argument = null) {
	$STH = db_get_statement_handle($q_str, $argument);
	$arr = array();
	
	try {
		while($x = $STH->fetch()) $arr[] = $x;
	} catch ( PDOException $e ) {
		print_json_error( $e->getMessage(), "database" );
	}
	return $arr;
}

?>
