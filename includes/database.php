<?php

class MySQLDatabase{
	private $connection;
	public $last_query;
	private $magic_quote_active;
	private $real_escape_string_exists;

	public function __construct(){
		$this->open_connection();
		$this->magic_quote_active = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists("mysql_real_escape_string");
	}

	public function close_connection(){
		if(isset($this->connection)){
			mysql_close($this->connection);
			unset($this->connection);
		}
	}

	public function query($sql){
		$this->last_query = $sql;
		$result = mysql_query($sql);
		if (!$result) {
			$output = "Database query failed".mysql_error()."</br> </br>";
			$output .= "Last query: " .$this->last_query;
			die($output);
		}else{
			return $result;
		}
	}

	function escape_value($value){
		//checks if php version supports
		if ($this->real_escape_string_exists){
			//removes slashes added by magic quote
			if ($this->magic_quote_active){
				$value= stripslashes($value);
			}
			$value = mysql_real_escape_string($value);	
		}else{
			//add aslashes again
			if (!$this->magic_quote_active){
				$value = addslashes($value);
			}
		}
		return $value;
	}

	public function	open_connection(){
		$this->connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS);

		if (!$this->connection){
			die("Database connection failed".mysql_error());
		}else{
			$db_select = mysql_select_db(DB_NAME);
			if (!isset($db_select)) {
				die("could not select database".mysql_error());
			}
		}
	}

//database nuetral methods
	public function fetch_array($result_set)
	{
		return mysql_fetch_array($result_set);
	}

	public function nums_rows($result_set)
	{
		return mysql_num_rows($result_set);
	}

	public function insert_id()
	{
		return mysql_insert_id($this->connection);
	}

	public function affected_rows()
	{
		return mysql_affected_rows($this->connection);
	}
}

$database = new MySQLDatabase();

?>