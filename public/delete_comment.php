<?php
require_once('../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	if(empty($_GET['id'])){
		$session->message("No Comment id was provided");
		// calls header function needs to be in output buffering potentially
		redirect_to('manage_photo.php');	
	}
		
	$comment = Comment::find_by_id($_GET['id']);	
	if($comment && $comment->delete()){
		$user = User::find_by_id($session->user_id);
		$photo = Photograph::find_by_id($comment->photo_id);
		// Success
		$session->message("Comment was deleted");
		log_action("\"{$user->user_name}\" deleted", " \"{$comment->body}\" from \"{$photo->filename}\"");
		// calls header function needs to be in output buffering potentially
		$addr = 'edit_comment.php?id='.$_GET['pid'];
		redirect_to($addr);
	} else {
		// Failure
		$session->message("Comment could not be deleted");
		// calls header function needs to be in output buffering potentially
		$addr = 'edit_comment.php?id='.$_GET['pid'];
		redirect_to($addr);
	}
	
?>
<?php if(isset($database)){$database->close_connection();} ?>

