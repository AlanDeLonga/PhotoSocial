<?php
	require_once('../includes/initialize.php');

	$user = User::find_by_id($session->user_id);
	$session->logout();
	log_action("Logout", "\"{$user->user_name}\" logged out.");
	redirect_to("index.php");

?>
