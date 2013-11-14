<?php
// A class to help work with Sessions 
// - defines and creates an instance of itself
// (primarly to manage logging useres in and out)

	class Session{
	// inadvisable to store DB-related objects in sessions
	// because data can become stale better to store id's for reference
	
		private $logged_in=false;
		public $user_id;
		public $message;
			

		function __construct(){
			defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
			session_save_path(DS.'hermes'.DS.'bosoraweb022'.DS.'b1553'.DS.'ipg.frompointatobeyondco'.DS.'photo_social');
			session_start();			
			$this->check_message();
			$this->check_login();
			if($this->logged_in){
				// actions to do if user is logged in
			} else {
				// actions to do if user is not logged in
			}
		}
		
		public function is_logged_in(){
			return $this->logged_in;
		}
		
		// check to see if any messages are in the session,
		// if so then it sets its class variable and erases the one in session
		private function check_message(){
			if(isset($_SESSION['message'])){
				// Add it as an attribute and erase the stored version
				$this->message = $_SESSION['message'];
				unset($_SESSION['message']);
			} else {
				$this->message = "";
			}
		}
		
		// sets or gets value depending on if the session message
		// key has been set with a value
		public function message($msg=""){
			if(!empty($msg)){
				// this sets the message
				$_SESSION['message'] = $msg;
			} else {
				// this gets the message
				return $this->message;
			}
		}
		
		// creates session variable so it can be authenticated
		// and marks logged in as true
		public function login($user){
			//database should find user based on username/password
			if($user){
				$this->user_id = $user->id;
				$_SESSION['user_id'] = $user->id;
				$this->logged_in = true;
			}
		}
		
		// deletes session variable and sets logged in to false
		public function logout(){
			unset($_SESSION['user_id']); 
			unset($this->user_id);
			$this->logged_in = false;
		}
		
		// checks session variable for authentication
		private function check_login(){
			if(isset($_SESSION['user_id'])){
				$this->user_id = $_SESSION['user_id'];
				$this->logged_in = true;
			} else {
				unset($this->user_id);
				$this->logged_in = false;
			}
		} 		
	}

$session = new Session();	
// set the session message
$message = $session->message();
	
?>