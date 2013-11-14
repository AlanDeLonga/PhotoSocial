<?php
require_once('../../includes/initialize.php');

if (!$session->is_logged_in()) { redirect_to("login.php"); }

// add link "Clear Log File" that requests "logfile.php?clear=true"
// check if log file set to clear, and clear if it is
if(isset($_GET['clear'])){
	if($_GET['clear'] == 'true'){
		$user = User::find_by_id($session->user_id);
		$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
		unlink($logfile);
		log_action("Logs cleared", "by \"{$user->user_name}\"");
		// this is so clear is no longer a GET var
		redirect_to('logfile.php');
	}	
}


// locate logs/log.txt using SITE_ROOT and DS
// if file does not exist or is not readable, output an error
// if file exists read its contents
// output the entries to HTML (nl2br, CSS, table, etc)


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Photo Gallery</title>
    <link href="../stylesheets/main.css" media="all" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <div id="header">
      <h1>Photo Gallery: Log file</h1>
    </div>
    <div id="main">
		<h2>Logs</h2>
		<?php 
			$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
			$content = "";
			// check that the file exists and is readable then get handle
			if(file_exists($logfile)&& is_readable($logfile) &&
			   $handle = fopen($logfile, 'r')){ // read
				// set the logs up in an unordered list for formatting
				$content .= "<ul class=\"log-entries\">";
				// loop through until you get to the end of the file, EOF
				while(!feof($handle)){
					// gets one line from the current pointer to the file in handle
					// then it adds a br tag for spacing to html output on page
					$content .= "<li>".fgets($handle)."</li>";
				}
				$content .= "</ul>";
				fclose($handle);
			}
			echo $content;		
		?> 
        <a href="logfile.php?clear=true">Clear Logs</a>
		<a href="index.php">Back to Menu</a>

	</div>
		
    <div id="footer">Copyright <?php echo date("Y", time()); ?>, Alan DeLonga</div>
  </body>
</html>