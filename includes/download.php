<?php
ob_start();
require_once('initialize.php');
?>
 <?php

// Force download of image file specified in URL query string and which
// is in the same directory as this script:
if(!empty($_POST['img']))
{
   $file = $_POST['img'];
   if (file_exists($file)) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($file));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));
	    ob_clean();
	    flush();
	    readfile($file);
	    exit;
	}
   
   $addy = "../public/photo.php?id=".$_POST['id'];
   if(isset($_POST['cat'])){
		$addy .= "&cat=".$_POST['cat'];
   }
   redirect_to($addy);
}
header("HTTP/1.0 404 Not Found");
?> 