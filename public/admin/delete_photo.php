<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	if(empty($_GET['id'])){
		$session->message("No Photo id was provided to delete image");
		// calls header function needs to be in output buffering potentially
		redirect_to('index.php');	
	}
		
	$photo = Photograph::find_by_id($_GET['id']);	
	if($photo && $photo->destroy()){
		$user = User::find_by_id($session->user_id);
		// Success
		$session->message("{$photo->filename} was deleted");		
		log_action("\"{$user->user_name}\" deleted", " \"{$photo->filename}\"");
		// calls header function needs to be in output buffering potentially
		redirect_to('show_photos.php');
	} else {
		// Failure
		$session->message("Photo could not be deleted");
		// calls header function needs to be in output buffering potentially
		redirect_to('show_photos.php');
	}
	
?>
<?php if(isset($database)){$database->close_connection();} ?>

