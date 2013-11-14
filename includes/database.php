<?php
require_once(LIB_PATH.DS."config.php");

class MySQLDatabase{
	
	private $connection;
	public $last_query;
	private $magic_quotes_active;
	private $real_escape_string_exists;
	
	function __construct(){
		$this->open_connection();
		// sees if your server has GPC active so you dont have to worry about 
		// escaping special cahracters in mySQL query variables that are passed 
		// in through php
		$this->magic_quotes_active = get_magic_quotes_gpc();
		
		// checks to see if the php version on the server is new enough to handle
		// the php function mysql_real_escape_string that sets up escaping special
		// characters in mySQL query variables
		$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" );
	}
	
	// opens the connection to the database using the config vars
	public function open_connection(){
		// create the connection to the database
		$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
		if (!$this->connection) {
			die("Database connection failed: " . mysql_error());
		} else {
			// sets up database to be use if connection was created
			$db_select = mysql_select_db(DB_NAME, $this->connection);
			if (!$db_select) {
				die("Database selection failed: " . mysql_error());
			}
		}	
	}

	// closes the connection with the database
	public function close_connection(){
		if(isset($this->connection)){
			mysql_close($this->connection);
			unset($this->connection);
		}
	}
	
	// sets the last query attempts to run it 
	// then confirms the result before returning the value
	public function query($sql){
		$this->last_query = $sql;
		$result = mysql_query($sql, $this->connection);
		$this->confirm_query($result);
		return $result;
	}
	
	// sets up special characters so that the variable is safe for mysql queries
	public function escape_values( $value ){
		if($this->real_escape_string_exists){
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if($this->magic_quotes_active){ $value = stripslaches($value); }
			$value = mysql_real_escape_string($value);
		} else{ // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if(!$this->magic_quotes_active){ $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;		
	}	
	
	//***************** "database-neutral" methods **************************
	// alows for results to be returned depending on the type of database
	
	public function fetch_array($result_set) {
		return mysql_fetch_array($result_set);
	}
	  
	public function num_rows($result_set) {
	   return mysql_num_rows($result_set);
	}
	  
	public function insert_id() {
		// get the last id inserted over the current db connection
		return mysql_insert_id($this->connection);
	}
	  
	public function affected_rows() {
		return mysql_affected_rows($this->connection);
	}

	// confirms if the result set could be found in the DB if not returns error
	private function confirm_query($result){
		if (!$result) {
			$output = "Database query failed: " . mysql_error() ."<br />";
			// this line for testing only, take out for production
			// prints out last query
			$output .= "Last SQL query: " . $this->last_query;
			die($output);
		}
	}
}

// creates a new object after the class is defined
$database = new MySQLDatabase();

?>