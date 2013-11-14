<?php
	// Define the core paths
	// Define them as absolute paths to make sure that require_once works as expected

	//DIRECTORY_SEPARATOR is a php pre-defined constant
	// ( \ for Windows, / for Unix)
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

	// checks if lib path exists if not defines the site root with the directory specified below
	// ***** needs to be updated when switched directories, servers or computers
	// ***** set up directory path using magic consts __FILE__ and __DIR__
	defined('SITE_ROOT') ? null :
		// for server
		define('SITE_ROOT', DS.'hermes'.DS.'bosoraweb022'.DS.'b1553'.DS.'ipg.frompointatobeyondco'.DS.'photo_social');
		//define('SITE_ROOT', DS.'wamp'.DS.'www'.DS.'photo_gallery');
		
	// Checks if lib path has been defined, if not it defines it using site path above
	defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');
	
	// first load config
	require_once(LIB_PATH.DS.'config.php');
	
	// load basic functions that are available for all other files
	require_once(LIB_PATH.DS.'functions.php');
	
	// load core object classes for application
	require_once(LIB_PATH.DS.'session.php');
	require_once(LIB_PATH.DS.'database.php');
	require_once(LIB_PATH.DS.'database_object.php');
	require_once(LIB_PATH.DS.'pagination.php');
	
	// load database-related object classes
	require_once(LIB_PATH.DS.'user.php'); 
	require_once(LIB_PATH.DS.'photograph.php'); 
	require_once(LIB_PATH.DS.'comment.php');
	require_once(LIB_PATH.DS.'category.php');
	require_once(LIB_PATH.DS.'photo_rating.php');
?>