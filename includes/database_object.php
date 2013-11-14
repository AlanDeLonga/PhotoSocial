<?php 
	// all objects are going to need access to the database
	require_once(LIB_PATH.DS.'database.php');

	class DatabaseObject{
		
		// **** Common Database methods/calls *****
	
		/* reurn a count of all that are containted within the calling table class	
		public static function count_all(){
			global $database;
			$sql = "SELECT COUNT(*) FROM ".static::$table_name;
			$result = static::find_by_sql($sql);
			return $result;	
		}
		*/
		
		// by making functions static it allows you to use the function without
		// having a User variable defined so instead of calling it by $user->f()
		// you call it by the class name User::f()
		public static function find_all(){
			return static::find_by_sql("SELECT * FROM ".static::$table_name);
		}
		
		// searches for object based on id, if a row is found in the db
		// it returns the results array otherwise it returns false
		public static function find_by_id($id=0){
			global $database;
			$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name.
			" WHERE id=".$database->escape_values($id)." LIMIT 1");
			// checks that the result array is not empty then pulls the first element
			// out of the array, if it is empty it returns false
			return !empty($result_array) ? array_shift($result_array) : false;		
		}
		
		// takes any sql and preforms query on the database
		// set up to return an array of objects instead of 
		// the array of results that fetch_array returns
		public static function find_by_sql($sql=""){
			global $database;
			$result_set = $database->query($sql);
			$object_array = array();
			while($row = $database->fetch_array($result_set)){
				$object_array[] = static::instantiate($row);
			}
			return $object_array;
		}
	
		// sets up all the variables for the object
		// based on the table rows in the db
		private static function instantiate($record){
			// could check that $record exists and is an array
			$class_name = get_called_class();
			$object = new $class_name;
			// Simple long form example of initiation
			//$object->id = $record['id'];
			//$object->user_name = $record['user_name'];
			//$object->password = $record['password'];
			//$object->first_name = $record['first_name'];
			//$object->last_name = $record['last_name'];
			
			foreach($record as $attribute=>$value){
				if($object->has_attribute($attribute)){
					$object->$attribute = $value;
				}
			}
			return $object;				
		}
		
		private function has_attribute($attribute) {
		  // get_object_vars returns an associative array with all attributes 
		  // (incl. private ones!) as the keys and their current values as the value
		  $object_vars = get_object_vars($this);
		  
		  // We don't care about the value, we just want to know if the key exists
		  // Will return true or false
		  return array_key_exists($attribute, $object_vars);
		}

		/* ************ Methods need to be added here ******************
		
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
		
		/* ********************** C R U D ******************************
		
		// this function check to see if the record is already there
		// and determines whether to create or update.
		public function save(){
			return isset($this->id) ? $this->update() : $this->create();
		}
		
		public function create(){
			global $database;
			
			$sql = "INSERT INTO ".static::$table_name." (";
			$sql .= "user_name, password, first_name, last_name";
			$sql .= ") VALUES (";
			$sql .= $database->escape_value(this->user_name) . "', '";
			$sql .= $database->escape_value(this->password) . "', '";
			$sql .= $database->escape_value(this->first_name) . "', '";
			$sql .= $database->escape_value(this->last_name) . "')";
			
			if($database->query($sql)){
				$this->id = $database->insert_id();
				return true;
			} else {
				return false;
			}
		}
		
		public function update(){
			global $database;
			
			$sql = "UPDATE ".static::$table_name." SET ";
			$sql .= "user_name='". $database->escape_value($this->user_name) ."', ";
			$sql .= "password='". $database->escape_value($this->password) ."', ";
			$sql .= "first_name='". $database->escape_value($this->first_name) ."', ";
			$sql .= "last_name='". $database->escape_value($this->last_name) ."' ";
			$sql .= "WHERE id='". $database->escape_value($this->id);
			
			$database->query($sql);
			return($database->affected_rows() == 1) ? true : false;
		}
		
		public function delete(){
			global $database;
			
			$sql = "DELETE FROM ".static::$table_name." ";
			$sql .= "WHERE id=". $database->escape_value($this->id);
			$sql .= " LIMIT 1";
			
			$database->query($sql);
			return($database->affected_rows() == 1) ? true : false;
		}
*/
	}

?>