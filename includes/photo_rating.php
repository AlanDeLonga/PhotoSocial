<?php
require_once(LIB_PATH.DS.'database.php');

class PhotoRating extends DatabaseObject {
	protected static $table_name = "photo_ratings";
	protected static $db_fields = array('p_id', 'u_id', 'rating');
	
	public $p_id;
	public $u_id;
	public $rating;
	
	function __construct($pid, $uid, $rating){
		$this->p_id = $pid;
		$this->u_id = $uid;
		$this->rating = $rating;
	}
	
	// saves the current photo_rating into the database
	// Then calls update photo rating up update the 
	// rating in the photo database table
	function save_rating_update_photo(){
		if($this->save()){
			// Success
			$message = "entry saved in photo_rating";
			//$session->message("{$photo->filename} was uploaded");		
		} else {
			// Failure
			$message = join("<br />", $this->errors);
		}
		
		$this->update_photo_rating();
	}
		
	function update_photo_rating(){
		$photo = Photograph::find_by_id($this->p_id);
		$newRating = ($this->rating + self::sum_all_ratings($this->p_id))/($this->count_all_for_photo($this->p_id)+1);
		
		$photo->rating = $newRating%10;
		
		if($photo->save()){
			// Success
			
		} else {
			// Failure
			$message = join("<br />", $photo->errors);
		}
	}
	
			// this function check to see if the record is already there
		// and determines whether to create or update.
	public function save(){
		return isset($this->id) ? $this->update() : $this->create();
	}
		
	public function create(){
		global $database;
		$attributes = $this->sanitized_attributes();
		
		$sql = "INSERT INTO ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql)){
			$this->id = $database->insert_id();
			return true;
		} else {
			return false;
		}
	}
		
	public function update(){
		global $database;
		$attributes = $this->sanitized_attributes();
		
		// set up the key, value string needed for the update statement
		foreach ($attributes as $key => $value){
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id='". $database->escape_values($this->id)."'";
		
		$database->query($sql);
		return($database->affected_rows() == 1) ? true : false;
	}
		
	public function delete(){
		global $database;
			
		$sql = "DELETE FROM ".self::$table_name." ";
		$sql .= "WHERE id=". $database->escape_values($this->id);
		$sql .= " LIMIT 1";
			
		$database->query($sql);
		return($database->affected_rows() == 1) ? true : false;
	}	
		
			// checks to see if the given attribute exsits for the current object
	private function has_attribute($attribute){
		// associative array with all attributes key and value pairs
		$object_vars = $this->attributes();
		
		// just check if the key exists and return true or false
		return array_key_exists($attribute, $object_vars);			
	}
		
	// returns a key value hash of the objects variables and values
	protected function attributes(){
		// get_object_vars returns an associative array with all attributes
		// (incl private) as the keys and their current values as value
		// return get_object_vars($this);	
		
		// this goes through the db fields and populates the hash
		$attributes = array();
		foreach(self::$db_fields as $field){
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
		
	// returns a sanitized (sql escaped) array of all the attributes
	protected function sanitized_attributes(){
		global $database;
		$clean_attributes = array();
			
		// sanitize the values before submitting
		// Note: does not alter the actual value of each attribute
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_values($value);
		}
		return $clean_attributes;
	}
	
	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}	
		
	public static function count_all_for_photo($p_id) {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name." WHERE p_id=".$p_id;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}	
	
	public static function sum_all_ratings($p_id) {
		global $database;
		$sql = "SELECT SUM(rating) FROM ".self::$table_name." WHERE p_id=".$p_id;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
}

?>