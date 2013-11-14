<?php 
	function strip_zeros_from_date($marked_string=""){
		//first remove the marked zeros
		$no_zeros = str_replace('*', '', $no_zeros);
		// then remove any remaining marks
		$cleaned_string = str_replace('*', '', $no_zeros);
		return $cleaned_string;
	}
	
	// Uses the header function to redirect to a new page
	// **** header has to be the first thing in the php file even before any
	// white spaces. If not you have to use output buffering tags around the
	// page so that nothing is displayed until the entire page is read and 
	// if header is hit then the redirection happens before anything is output
	function redirect_to( $location=NULL ){
		if($location != NULL){
			header("Location: {$location}");
			exit;
		}
	}
	
	function output_message($message=""){
		if(!empty($message)){
			return "<p class=\"message\">{$message}</p>";
		} else {
			return "";
		}		
	}	
	
	// function is called when ever a file is missing a required file
	// where a class is created that has no definition. This funciton
	// will try to load the file witht he same class name.
	function __autoload($class_name) {
		$class_name = strtolower($class_name);
	  $path = LIB_PATH.DS."{$class_name}.php";
	  if(file_exists($path)) {
		require_once($path);
	  } else {
			die("The file {$class_name}.php could not be found.");
		}
	}
	
	// allows to dynamically load templates for displaying layouts
	function include_layout_template($template=""){
		include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
	}

	function log_action($action, $message=""){
		// check if the file exists or create new file
		$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
		
		// check the file is writable or output error
		// append new entries to the end of the file
		if($handle = fopen($logfile, 'a')){ // append
		
			// Sample Entry: 2012-01-01 13:10:03 | Login: freeze logged in. 
			//(for windows newline is \r\n) for unix it is just \n
			$timestamp = date('Y-m-d h:i:s A');
			$content = "{$timestamp} | {$action}: {$message}\r\n";
			fwrite($handle, $content); 
		
			fclose($handle);
		} else {
			echo "Could not open file for writing";
		}	
	}

	function datetime_to_text($datetime=""){
		$unixdatetime = strtotime($datetime);
		return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
	}
?>