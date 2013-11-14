<?php
	require_once('database.php');
	
	class User extends DatabaseObject{
		
		protected static $db_fields = array('id', 'user_name', 'password', 
		'first_name', 'last_name', 'email', 'user_type');
		protected static $table_name="users";
		
		public $id;
		public $user_name;
		public $password;
		public $first_name;
		public $last_name;
		public $email;
		public $user_type;
	
		public function full_name(){
			if(isset($this->first_name) && isset($this->last_name)){
				return $this->first_name . " " . $this->last_name;
			} else {
				return "";
			}		
		}
		
		// authenticates user by checking if there is a row with the 
		// input user name and password, then returns the object if found
		// if not it returns false
		public static function authenticate($user_name="", $password=""){
			global $database;
			$user_name = $database->escape_values($user_name);
			$password = $database->escape_values($password);
			
			$sql = "SELECT * FROM users ";
			$sql .= "WHERE user_name = '{$user_name}' ";
			$sql .= "AND password = '{$password}' ";
			$sql .= "LIMIT 1";
			$result_array = self::find_by_sql($sql);
			
			return !empty($result_array) ? array_shift($result_array) : false;
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
			$sql .= " WHERE id='". $database->escape_values($this->id);
			
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
	
	}
?>