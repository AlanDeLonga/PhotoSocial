<?php
require_once(LIB_PATH.DS.'database.php');

class Category extends DatabaseObject {
	protected static $table_name = "categories";
	protected static $db_fields = array('id', 'name');
	
	public $id;
	public $name;
	
	
	public static function get_cat_name($id){
		global $database;
		
		$sql = "SELECT name FROM categories ";
		$sql .= "WHERE id=".$id;
		$sql .= " LIMIT 1 ";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
}

?>