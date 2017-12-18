<?php 
/**
* 
*/
require_once 'database.php';
class Users  
{
	protected static $table_name = 'users';
	protected static $db_fields = array('id','username','password','first_name','last_name');
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	
	function __construct()
	{
		# code...
	}

	public function full_name()
	{
		if (isset($this->first_name) && isset($this->last_name)) {
			return $this->first_name." ".$this->last_name;
		} else {
			return "";
		}
		
	}

	//Common database class
	public function authenticate($username='', $password='')
	{
		global $database;
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);

		$sql = "SELECT * FROM users ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql.= "LIMIT 1";

		$result_array = $this->find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;

	}
	public function find_all()
	{
		global $database;
		return $this->find_by_sql("SELECT * FROM users ");
	}

	public function find_by_id($id = 0)
	{
		global $database;	
		$result_array = $this->find_by_sql("SELECT * FROM users WHERE id= {$id}");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public function find_by_sql($sql='')
	{
		global $database;	
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}

	private static function instantiate($record)
	{
		$object = new self;
		// $object->id 		  = $record['id'];
		// $object->username   = $record['username'];
		// $object->password   = $record['password'];	
		// $object->first_name = $record['first_name'];
		// $object->last_name  = $record['last_name'];

		foreach ($record as $attribute => $value) {
			if ($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}

		return $object;
	}

	public function has_attribute($attribute)
	{
		$object_vars = $this->attributes();
		return array_key_exists($attribute, $object_vars);
	}

	public function attributes()
	{
		$attributes = array();
		foreach (self::$db_fields as $field) {
			if (property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		
		return $attributes;
	}

	public function sanitised_attributes()
	{
		global $database ;
		$clean_attributes[] = array();

		foreach ($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}

	public function save()
	{
		//A new record wont have an id
		return isset($this->id) ? $this->update() : $this->create();
	}
	public function create()
	{
		global $database;
		//Dont forget your SQL syntax and good habit
		//-INSERT INTO table (key,key) VALUES ('value','value')
		//-single-quotes around all values
		//-escapes all values to prevent SQL prevention

		$attributes = $this->sanitised_attributes();
		$sql = "INSERT INTO ".self::$table_name." (";
		//$sql .= "username,password,first_name,last_name";
		$sql .= join(',',array_keys($attributes));
		$sql .= ") VALUES ('";
		/*$sql .= $database->escape_value($this->username)."', '";
		$sql .= $database->escape_value($this->password)."', '";
		$sql .= $database->escape_value($this->first_name)."', '";
		$sql .= $database->escape_value($this->last_name)."')";*/
		$sql .= join("', '",array_values($attributes));
		$sql .= "')";

		if($database->query($sql)) {
			$this->id = $database->insert_id();
			return true;
		} else {
			return false;
		}
		
	}

	public function update()
	{
		global $database;
		//Dont forget your SQL syntax and good habit
		//-UPDATE table SET key='value' and key='value' WHERE condition
		//-single-quotes around all values
		//-escapes all values to prevent SQL prevention
		$sql = "UPDATE ".self::$table_name." SET ";

		$attributes = $this->sanitised_attributes();
		$attribute_pair = array();

		foreach ($attributes as $key => $value) {
			$attribute_pair[] ="{$key} = '{$value}'";

		}
		/*$sql .= "username='".$database->escape_value($this->username)."',";
		$sql .= "password='".$database->escape_value($this->password)."',";
		$sql .= "first_name='".$database->escape_value($this->first_name)."',";
		$sql .= "last_name='".$database->escape_value($this->last_name)."'";*/

		$sql .= join(",",$attribute_pair);
		$sql .= " WHERE id=".$database->escape_value($this->id);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false ;
	}

	public function delete()
	{
		$database;
		//Dont forget your SQL syntax and good habit
		//-DELETE FROM table Where condition LIMIT 1
		//-escapes all values to prevent SQL prevention
		//-use LIMIT 1

		$sql = "DELETE FROM ".self::$table_name;
		$sql .= " WHERE id=".$database->escape_value($this->id);
		$sql .= " LIMIT 1";

		$database->query($sql);
		return ($database->affected_rows()==1) ? true : false ;
	}
}
 ?>