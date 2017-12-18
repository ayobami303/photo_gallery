<?php 
/**
* 
*/
require_once 'database.php';
class photographs 
{
	protected static $table_name = 'photographs';
	protected static $db_fields = array("id","filename","type","size","caption");
	public $id;
	public $filename;
	public $type;
	public $size;
	public $caption;

	private $tmp_path;
	protected $upload_dir = "images";
	public $errors = array();
	protected $upload_errors = array(
		UPLOAD_ERR_OK => 'No errors.',
		UPLOAD_ERR_INI_SIZE => 'Larger than upload_max_filesize.',
		UPLOAD_ERR_FORM_SIZE => 'Larger than form MAX_FILE_SIZE.',
		UPLOAD_ERR_PARTIAL => 'partial upload',
		UPLOAD_ERR_NO_FILE => 'No file',
		UPLOAD_ERR_NO_TMP_DIR => 'No temporary directory',
		UPLOAD_ERR_CANT_WRITE => 'CANT write to disk.',
		UPLOAD_ERR_EXTENSION =>'File upload stopped by extension');


	function __construct()
	{
		
	}

	public function destroy($id='',$filename ='')
	{
		if ($this->delete($id)) {
			//delete the database 
			$target_path = $_SERVER['DOCUMENT_ROOT']."/photo_gallery/public/upload/".$filename;
			//$target_path = $_SERVER['DOCUMENT_ROOT']."/photo_gallery/public/upload/".$this->filename;
			return unlink($target_path) ? true : false;
		} else {
			//deleting failed
			return false;
		}
		
	}
	public function save()
	{
		
		if (isset($this->id)) {
			//just to update caption

			//cant save if there are prexisting errors
			//$this->update();
		} else {
			//make sure there are no errors
			if (!empty($this->errors)) {
				return false;
			}
			//make sure caption is not too long
			if(strlen($this->caption) >= 255){
				$this->errors[] = 'the caption can only be 255 characters long';
				return false;
			}

			//cant save without afile name or tenp location
			if (empty($this->filename) || empty($this->tmp_path)) {
				echo $this->filename;
				$this->errors[] = 'file location was not available';
				return false;
			} 

			$target_path = $_SERVER['DOCUMENT_ROOT']."/photo_gallery/public/upload/".$this->filename;
			if (file_exists($target_path)){
				$this->errors[]= "the file {$this->filename} already exist";
				return false;
			}

			if (move_uploaded_file($this->tmp_path, $target_path)) {
				if($this->create()){
					echo $this->filename;
					//we are done with temp_path, the file no longer exist
					unset($this->tmp_path);
					return true;
				}
			}else {
				//file not moved
				$this->errors[] = "The file upload failed, possible due to incorrect permissions on the upload folder";
				return false;
			}
			
			
			//$this->create();
		}
		
	}

	public function size_to_text($value="")
	{
		if ($value < 1024) {
			return round($value)." bytes";
		} elseif ($value < 1048576) {
			return round($value/1024)." kb";
		}else {
			return round($value/1048576)." mb";
		}
		
	}
	//pass in $_FILE(['uploaded_file']) as an argument
	public function attach_file($file)
	{
		if (!$file || empty($file) || !is_array($file)) {
			$this->errors[] = 'no file was uploaded';
			return false;
		} elseif ($file['error'] != 0) {
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		}else {
			//set object attributes to form parameters
			$this->filename = basename($file['name']);
			$this->tmp_path = $file['tmp_name'];
			$this->type     = $file['type'];
			$this->size     = $file['size'];

			//echo $this->filename;
			return true;
		}
		
	}
	
	public function find_all()
	{
		global $database;
		return $this->find_by_sql("SELECT * FROM photographs ");
	}
	public function count_all($sql='')
	{
		global $database;
		$sql = "SELECT count(*) FROM ".self::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	public function find_by_id($id = 0)
	{
		global $database;	

		$result_array = $this->find_by_sql("SELECT * FROM photographs WHERE id= ".$database->escape_value($id)." LIMIT 1");
		
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
		/*foreach ($object as $key => $value) {
			echo $value;
		}*/
		return $object;
	}

	public function has_attribute($attribute)
	{
		$object_vars = $this->attributes();
		return array_key_exists($attribute, $object_vars);
	}

	public function attributes()
	{
		$attribute = array();
		foreach (self::$db_fields as $field) {
			if (property_exists($this, $field)) {
				$attribute[$field] = $this->$field;
			}
		}
		//echo join(",",array_values($attribute));
		return $attribute;
		//return get_object_vars($this);
	}

	public function sanitised_attributes()
	{
		global $database ;
		$clean_attributes = array();

		foreach ($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);

		}
		
		return $clean_attributes;
	}
	/*public function save()
	{
		//A new record wont have an id
		return isset($this->id) ? $this->update() : $this->create();
	}*/
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

	public function delete($id ='')
	{
		global $database;
		//Dont forget your SQL syntax and good habit
		//-DELETE FROM table Where condition LIMIT 1
		//-escapes all values to prevent SQL prevention
		//-use LIMIT 1

		$sql = "DELETE FROM ".self::$table_name;
		$sql .= " WHERE id=".$database->escape_value($id);
		$sql .= " LIMIT 1";

		$database->query($sql);
		return ($database->affected_rows()==1) ? true : false ;
	}
}
 ?>