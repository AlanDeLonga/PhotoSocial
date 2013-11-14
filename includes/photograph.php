<?php
require_once(LIB_PATH.DS.'database.php');

class Photograph extends DatabaseObject {
	protected static $table_name = "photographs";
	protected static $db_fields = array('id', 'uid', 'filename', 'type', 'size', 'caption','cat_id', 'rating', 'public', 'rate', 'download', 'dl_times');
	
	public $id;
	public $filename;
	public $type;
	public $size;
	public $caption;
	public $cat_id;
	public $uid;
	public $rating;
	public $public;
	public $rate;
	public $download;
	public $dl_times;
	
	private $temp_path;
	protected $upload_dir = "images";
	public $errors = array();
	
	protected $upload_errors = array(
	  // http://www.php.net/manual/en/features.file-upload.errors.php
	  UPLOAD_ERR_OK 		=> "No errors.",
	  UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
	  UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
	  UPLOAD_ERR_PARTIAL 	=> "Partial upload.",
	  UPLOAD_ERR_NO_FILE 	=> "No file.",
	  UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
	  UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
	  UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
    );
	
	public function image_path(){
		// DS is giving the wrong directory seperator on localhost
		return $this->upload_dir."/".$this->filename;
	}
	
	public function admin_image_path(){
		// DS is giving the wrong directory seperator on localhost
		return "../".$this->upload_dir."/".$this->filename;
	}
	
	public function size_as_text(){
		if($this->size < 1024){
			return "{$this->size} bytes";
		} elseif($this->size < 1048576){
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_kb = round($this->size/1048576);
			return "{$size_mb} MB";
		}
	}
	
	public function comments() {
		return Comment::find_comments_on($this->id);
	}
	
	// Pass in $_FILE(['uploaded_file']) as an argument
	public function attach_file($file){
		// Preform error checking onthe form parameters
		if(!$file || empty($file) ||!is_array($file)){
			// error: nothing uploaded or wrong argument usage
			$this->errors[] = "No file was uploaded.";
			return false;
		} elseif ($file['error'] != 0){
			// error: report the PHP error
			$this->errors[] =  $this->upload_errors[$file['error']];
			return false;
		} else {		
			// Set object attributes to the form parameters
			$this->temp_path = $file['tmp_name'];
			$this->filename = str_replace(' ', '_', basename($file['name']));
			$this->type = $file['type'];
			$this->size = $file['size'];
			return true;
		}
	}
	
	public function find_all_public(){
			return static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE public=1");
		}
	
	// Move file to the correct directory and 
	// save where it has been moved to in the database
	public function save(){
		// New records dont have id yet
		if(isset($this->id)){
			$this->update();
		} else {
			// Make sure there are no errors			
			// Can't save if there are pre-existing errors
			if(!empty($this->errors)){ return false;}
			
			// Make sure teh caption is not too long for the DB
			if(strlen($this->caption) > 255){
				$this->errors[] = "The caption can only be 255 characters long";
				return false;
			}
			
			// Determine the target_path
			$target_path = SITE_ROOT.DS.'public'.DS.$this->upload_dir.DS.$this->filename;
			
			// Attempt to move the file
			if(move_uploaded_file($this->temp_path, $target_path)){
				// Success
				// Save a corresponding entry to the database
				if($this->create()){
					// Done with temp_path, the file isn't there anymore
					unset($this->temp_path);
					return true;
				}
			} else {
				// Failure
				$this->errors[] = "The file upload failed, possibly due to incorrect
				permissions on the upload folder.";
				return false;
			}
			
			// Save a corresponding entry to the database
			$this->create();
		}
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
	
	// ***************** replaced with custom save ******************
	// this function check to see if the record is already there
	// and determines whether to create or update.
	//public function save(){
	//	return isset($this->id) ? $this->update() : $this->create();
	//}
		
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
		$sql .= " WHERE id=". $database->escape_values($this->id);
		
		$database->query($sql);
		return($database->affected_rows() == 1) ? true : false;
	}
	
	// updates only information about the image as set by the
	// person who uploaded the file
	// only caption, permission, and categroy can be edited
	
	public function update_photo($cap, $perm, $cat, $rate, $dl){
		global $database;
		
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= "caption='{$cap}', public={$perm}, cat_id={$cat}, rate={$rate}, download={$dl}";
		$sql .= " WHERE id=". $database->escape_values($this->id);
		
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
	
	public function destroy(){
		// First remove the database entry
		// Then remove the file
		if($this->delete()){
			// remove file
			$target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
		
	}

	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}	
	
	public static function count_all_public() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$sql .= " WHERE public=1";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public static function count_all_for_category($c_id) {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$sql .= " WHERE cat_id=".$c_id." AND public=1";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}

	public static function count_all_for_user($u_id) {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$sql .= " WHERE uid=".$u_id;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}

	public static function pages_for_category($c_id){
		global $database;
		
		$sql = "SELECT * ";
		$sql .= "FROM ".self::$table_name.", categories ";
		$sql .= "WHERE ".self::$cat_id."=".$c_id;
		
		return self::find_by_sql($sql);
	}
	
	public static function last_uploaded_photo($u_id){
		global $database;
		
		$offset = (int)self::count_all_for_user($u_id);
		$offset -= 1;
		
		$sql = "SELECT * FROM photographs ";
		$sql .= "WHERE uid=".$u_id;
		$sql .= " LIMIT 1 ";
		$sql .= "OFFSET ".$offset;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public static function top_rated_photo($u_id){
		global $database;
		
		// select the row that contains the max rating
		/*
		select *
		from photographs ph
		inner join(
    		select max(rating) rating
    		from photographs
   			 where uid=1
		) phs on ph.rating = phs.rating
		*/
		$sql = "SELECT * FROM photographs ph ";
		$sql .= " INNER JOIN(";
    	$sql .= " SELECT MAX(rating) rating";
    	$sql .= " FROM photographs";
   		$sql .= " WHERE uid={$u_id} ) phs";
		$sql .= " on ph.rating = phs.rating";
		$sql .= " LIMIT 1 ";
		return self::find_by_sql($sql);
	}
}
?>